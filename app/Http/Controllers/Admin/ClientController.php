<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;

class ClientController extends Controller
{
    // LISTE : tous les clients, avec le nombre de commandes de chacun
    public function index()
    {
        // withCount('commandes') ajoute une colonne virtuelle "commandes_count"
        // sans charger toutes les commandes (juste un COUNT côté SQL, plus léger)
        $clients = Client::withCount('commandes')
            ->orderBy('nom_client')
            ->get();

        return view('admin.clients.index', compact('clients'));
    }

    // FICHE DÉTAILLÉE : infos client + historique commandes + statistiques
    public function show(Client $client)
    {
        // On charge les commandes du client, avec leurs produits, les plus récentes d'abord
        $client->load(['commandes' => function ($query) {
            $query->orderByDesc('date_commande');
        }, 'commandes.produits']);

        $commandes = $client->commandes;

        // --- STATISTIQUES ---

        // Nombre total de commandes
        $nbCommandes = $commandes->count();

        // Chiffre d'affaires total généré par ce client
        $caTotal = $commandes->sum('montant_paiement');

        // Panier moyen = CA total / nombre de commandes (on évite la division par zéro)
        $panierMoyen = $nbCommandes > 0 ? $caTotal / $nbCommandes : 0;

        // Produits favoris : on parcourt toutes les commandes et on compte combien de fois
        // chaque produit a été commandé, puis on garde les 3 plus fréquents
        $compteurProduits = [];
        foreach ($commandes as $commande) {
            foreach ($commande->produits as $produit) {
                $nom = $produit->nom_produit;
                $compteurProduits[$nom] = ($compteurProduits[$nom] ?? 0) + 1;
            }
        }
        // Tri décroissant par nombre d'occurrences, puis on garde les 3 premiers
        arsort($compteurProduits);
        $produitsFavoris = array_slice($compteurProduits, 0, 3, true);

        return view('admin.clients.show', compact(
            'client',
            'commandes',
            'nbCommandes',
            'caTotal',
            'panierMoyen',
            'produitsFavoris'
        ));
    }
}