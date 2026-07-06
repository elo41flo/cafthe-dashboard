<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    // LECTURE : liste de tous les produits
    public function index()
    {
        $produits = Produit::orderBy('nom_produit')->get();
        return view('admin.produits.index', compact('produits'));
    }

    // CRÉATION : affiche le formulaire vide
    public function create()
    {
        return view('admin.produits.create');
    }

    // CRÉATION : traite l'envoi du formulaire
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom_produit'      => 'required|string|max:50',
            'description'      => 'nullable|string',
            'categorie'        => 'required|string|max:15',
            'type_vente'       => 'required|string|max:10',
            'tva'              => 'required|numeric',
            'prix_ht'          => 'required|numeric',
            'stock'            => 'required|integer|min:0',
            'image'            => 'nullable|string|max:255',
            'origine'          => 'nullable|string|max:50',
        ]);

        // Le prix TTC n'est pas saisi manuellement : on le calcule automatiquement
        // à partir du prix HT et de la TVA, comme demandé dans le cahier des charges
        $donnees['prix_ttc'] = round($donnees['prix_ht'] * (1 + $donnees['tva'] / 100), 2);

        Produit::create($donnees);

        return redirect()->route('admin.produits.index')->with('succes', 'Produit créé avec succès.');
    }

    // MISE À JOUR : affiche le formulaire pré-rempli
    public function edit(Produit $produit)
    {
        return view('admin.produits.edit', compact('produit'));
    }

    // MISE À JOUR : traite l'envoi du formulaire
    public function update(Request $request, Produit $produit)
    {
        $donnees = $request->validate([
            'nom_produit'      => 'required|string|max:50',
            'description'      => 'nullable|string',
            'categorie'        => 'required|string|max:15',
            'type_vente'       => 'required|string|max:10',
            'tva'              => 'required|numeric',
            'prix_ht'          => 'required|numeric',
            'stock'            => 'required|integer|min:0',
            'image'            => 'nullable|string|max:255',
            'origine'          => 'nullable|string|max:50',
        ]);

        $donnees['prix_ttc'] = round($donnees['prix_ht'] * (1 + $donnees['tva'] / 100), 2);

        $produit->update($donnees);

        return redirect()->route('admin.produits.index')->with('succes', 'Produit modifié avec succès.');
    }

    // SUPPRESSION
    public function destroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('succes', 'Produit supprimé.');
    }
}