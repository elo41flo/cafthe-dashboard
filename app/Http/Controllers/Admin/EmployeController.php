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
            'mdp_employe'       => [
                'required',
                'string',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.\-_])[A-Za-z\d@$!%*?&#.\-_]{12,}$/',
            ],
        ], [
    // Message personnalisé affiché si le regex du mot de passe échoue
    'mdp_employe.regex' => 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
]);

        // On hash le mot de passe avant de l'enregistrer : jamais de mot de passe en clair en base
        $donnees['mdp_employe'] = Hash::make($donnees['mdp_employe']);

        Employe::create($donnees);

        return redirect()->route('dashboard')->with('succes', 'Vendeur créé avec succès.');
    }
}