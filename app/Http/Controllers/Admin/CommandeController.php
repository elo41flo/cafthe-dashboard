<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    // LISTE : affiche uniquement les commandes en ligne (pas les ventes magasin)
    public function index(Request $request)
{
    // Requête de base : uniquement les commandes en ligne (comme avant)
    $query = Commande::with('client')->where('type_de_commande', 'En ligne');

    // FILTRE : statut de commande
    if ($request->filled('statut')) {
        $query->where('statut_de_commande', $request->statut);
    }

    // FILTRE : date de début (commandes à partir de cette date)
    if ($request->filled('date_debut')) {
        $query->whereDate('date_commande', '>=', $request->date_debut);
    }

    // FILTRE : date de fin (commandes jusqu'à cette date)
    if ($request->filled('date_fin')) {
        $query->whereDate('date_commande', '<=', $request->date_fin);
    }

    $commandes = $query->orderByDesc('date_commande')->get();

    return view('admin.commandes.index', compact('commandes'));
}

    // DÉTAIL : affiche une commande complète avec ses produits et sa livraison
    public function show(Commande $commande)
    {
        // On charge toutes les relations d'un coup pour la page de détail
        $commande->load('client', 'produits', 'livraisonRetrait');

        return view('admin.commandes.show', compact('commande'));
    }

    // MISE À JOUR DU STATUT : fait avancer la commande dans son cycle de vie
    public function updateStatut(Request $request, Commande $commande)
    {
        // On valide que le statut envoyé fait bien partie des valeurs autorisées
        $donnees = $request->validate([
            'statut_de_commande' => 'required|in:Payée,En attente,En préparation,Expédiée,Livrée',
        ]);

        $commande->update($donnees);

        return redirect()
            ->route('admin.commandes.show', $commande)
            ->with('succes', 'Statut mis à jour.');
    }
}