<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    // Affiche le formulaire de création d'un vendeur
    public function create()
    {
        return view('admin.employes.create');
    }

    // Traite la création du vendeur
    public function store(Request $request)
    {
        $donnees = $request->validate([
            'nom_employe'       => 'required|string|max:60',
            'prenom_employe'    => 'nullable|string|max:60',
            'email_employe'     => 'required|email|max:320|unique:employes,email_employe',
            'telephone_employe' => 'nullable|string|max:10',
            'role'              => 'required|in:admin,vendeur',
            'mdp_employe'       => 'required|string|min:8',
        ]);

        // On hash le mot de passe avant de l'enregistrer : jamais de mot de passe en clair en base
        $donnees['mdp_employe'] = Hash::make($donnees['mdp_employe']);

        Employe::create($donnees);

        return redirect()->route('dashboard')->with('succes', 'Vendeur créé avec succès.');
    }
}