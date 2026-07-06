<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class VenteMagasinController extends Controller
{
    // Affiche le formulaire de vente : on lui passe la liste des clients et des produits
    // pour alimenter les menus déroulants / la recherche côté JS
    public function create()
    {
        $clients = Client::orderBy('nom_client')->get();
        $produits = Produit::where('stock', '>', 0)->orderBy('nom_produit')->get();

        return view('admin.ventes.create', compact('clients', 'produits'));
    }

    // Enregistre la vente. Reçoit du JS : un client (existant ou à créer) + une liste de lignes panier.
    public function store(Request $request)
    {
        $donnees = $request->validate([
            // Client : soit un id existant, soit les infos pour en créer un
            'numero_client'       => 'nullable|integer|exists:client,numero_client',
            'nouveau_nom'         => 'required_without:numero_client|nullable|string|max:60',
            'nouveau_prenom'      => 'nullable|string|max:60',
            'nouveau_email'       => 'required_without:numero_client|nullable|email|max:320',

            // Paiement
            'mode_paiement'       => 'required|in:CB,Espèces,Chèque',

            // Le panier : tableau de lignes, chacune avec un produit et une quantité
            'lignes'              => 'required|array|min:1',
            'lignes.*.numero_produit'  => 'required|integer|exists:produits,numero_produit',
            'lignes.*.quantite'        => 'required|numeric|min:0.01',
        ]);

        // Tout est englobé dans une transaction : si une étape échoue (ex: stock insuffisant),
        // rien n'est enregistré, on évite une commande à moitié créée / un stock incohérent.
        $commande = DB::transaction(function () use ($donnees) {

            // ÉTAPE 1 — Client : on récupère l'existant, ou on en crée un nouveau à la volée
            if (!empty($donnees['numero_client'])) {
                $numeroClient = $donnees['numero_client'];
            } else {
                $client = Client::create([
                    'nom_client'    => $donnees['nouveau_nom'],
                    'prenom_client' => $donnees['nouveau_prenom'] ?? null,
                    'email_client'  => $donnees['nouveau_email'],
                    // Client créé en magasin sans compte e-commerce : on génère un mot de passe
                    // temporaire aléatoire (hashé en bcrypt). Le client pourra définir le sien
                    // lors de sa première connexion sur le site via "mot de passe oublié".
                    'mdp_client'    => Hash::make(Str::random(32)),
                ]);
                $numeroClient = $client->numero_client;
            }

            // ÉTAPE 2/3 — On calcule le montant total TTC en parcourant les lignes du panier
            $montantTotal = 0;
            $lignesAvecPrix = [];

            foreach ($donnees['lignes'] as $ligne) {
                $produit = Produit::findOrFail($ligne['numero_produit']);

                // Vérification du stock avant de valider la vente
                if ($produit->stock < $ligne['quantite']) {
                    // Annule toute la transaction : le message remonte à l'utilisateur
                    abort(422, "Stock insuffisant pour le produit : {$produit->nom_produit}");
                }

                // Le prix TTC est déjà stocké sur le produit ; on multiplie par la quantité
                $sousTotal = $produit->prix_ttc * $ligne['quantite'];
                $montantTotal += $sousTotal;

                $lignesAvecPrix[] = [
                    'produit'  => $produit,
                    'quantite' => $ligne['quantite'],
                ];
            }

            // ÉTAPE 4 — Création de la commande (type "En magasin", statut "Payée" direct)
            $commande = Commande::create([
                'statut_de_commande' => 'Payée',
                'type_de_commande'   => 'En magasin',
                'date_commande'      => now(),
                'date_paiement'      => now(),
                'montant_paiement'   => round($montantTotal, 2),
                'mode_paiement'      => $donnees['mode_paiement'],
                'numero_client'      => $numeroClient,
            ]);

            // ÉTAPE 5 — On rattache chaque produit à la commande (table pivot "contenir")
            // et on décrémente le stock au passage
            foreach ($lignesAvecPrix as $item) {
                $commande->produits()->attach($item['produit']->numero_produit, [
                    'quantite_gramme' => $item['quantite'],
                ]);

                // Mise à jour automatique et instantanée du stock
                $item['produit']->decrement('stock', $item['quantite']);
            }

            return $commande;
        });

        return redirect()
            ->route('admin.commandes.show', $commande)
            ->with('succes', 'Vente enregistrée avec succès.');
    }
}