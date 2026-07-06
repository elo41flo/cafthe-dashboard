<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ============ KPI CHIFFRE D'AFFAIRES (par période) ============
        // On somme le montant des commandes selon différentes fenêtres de temps.

        $caJour = Commande::whereDate('date_commande', Carbon::today())
            ->sum('montant_paiement');

        $caSemaine = Commande::whereBetween('date_commande', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ])->sum('montant_paiement');

        $caMois = Commande::whereMonth('date_commande', Carbon::now()->month)
            ->whereYear('date_commande', Carbon::now()->year)
            ->sum('montant_paiement');

        $caAnnee = Commande::whereYear('date_commande', Carbon::now()->year)
            ->sum('montant_paiement');

        // ============ NOMBRE DE VENTES + PANIER MOYEN ============
        $nbVentes = Commande::count();
        $caTotal = Commande::sum('montant_paiement');
        $panierMoyen = $nbVentes > 0 ? $caTotal / $nbVentes : 0;

        // ============ TOP 10 DES PRODUITS LES PLUS VENDUS ============
        // On s'appuie sur la table pivot "contenir" : on additionne les quantités par produit,
        // puis on joint la table produits pour récupérer les noms.
        $topProduits = DB::table('contenir')
            ->join('produits', 'contenir.numero_produit', '=', 'produits.numero_produit')
            ->select('produits.nom_produit', DB::raw('SUM(contenir.quantite_gramme) as total_vendu'))
            ->groupBy('produits.numero_produit', 'produits.nom_produit')
            ->orderByDesc('total_vendu')
            ->limit(10)
            ->get();

        // ============ RÉPARTITION DES VENTES PAR CATÉGORIE ============
        // Nombre de produits vendus regroupés par catégorie (Thé / Café / Accessoire)
        $ventesParCategorie = DB::table('contenir')
            ->join('produits', 'contenir.numero_produit', '=', 'produits.numero_produit')
            ->select('produits.categorie', DB::raw('SUM(contenir.quantite_gramme) as total'))
            ->groupBy('produits.categorie')
            ->get();

        // ============ ÉVOLUTION DU NOMBRE DE CLIENTS (par mois, sur l'année en cours) ============
        // Note : la table client n'ayant pas de date de création, on approxime l'évolution
        // via la date de la 1re commande de chaque client sur l'année. C'est une approximation
        // assumée (à améliorer si une colonne date_inscription est ajoutée plus tard).
        $clientsParMois = DB::table('commande')
            ->select(DB::raw('MONTH(date_commande) as mois'), DB::raw('COUNT(DISTINCT numero_client) as nb_clients'))
            ->whereYear('date_commande', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(date_commande)'))
            ->orderBy('mois')
            ->get();

        return view('admin.dashboard.index', compact(
            'caJour', 'caSemaine', 'caMois', 'caAnnee',
            'nbVentes', 'panierMoyen',
            'topProduits', 'ventesParCategorie', 'clientsParMois'
        ));
    }
}