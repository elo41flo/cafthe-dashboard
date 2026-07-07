<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // Affiche la page de profil de l'employé connecté
    public function edit()
    {
        // Auth::user() renvoie l'employé actuellement connecté
        $employe = Auth::user();
        return view('admin.profil.edit', compact('employe'));
    }

    // Met à jour les infos personnelles (nom, prénom, email, téléphone)
    public function updateInfos(Request $request)
    {
        $employe = Auth::user();

        $donnees = $request->validate([
            'nom_employe'       => 'required|string|max:60',
            'prenom_employe'    => 'nullable|string|max:60',
            // unique en ignorant l'employé lui-même (sinon son propre email serait vu comme "déjà pris")
            'email_employe'     => 'required|email|max:320|unique:employes,email_employe,' . $employe->numero_employe . ',numero_employe',
            'telephone_employe' => 'nullable|string|max:10',
        ]);

        $employe->update($donnees);

        return redirect()->route('admin.profil.edit')->with('succes', 'Informations mises à jour.');
    }

    // Met à jour le mot de passe (avec vérification de l'ancien)
    public function updateMotDePasse(Request $request)
    {
        $employe = Auth::user();

        $donnees = $request->validate([
            'ancien_mdp'    => 'required|string',
            'nouveau_mdp'   => [
                'required',
                'string',
                'confirmed', // exige un champ "nouveau_mdp_confirmation" identique
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.\-_])[A-Za-z\d@$!%*?&#.\-_]{12,}$/',
            ],
        ], [
            'nouveau_mdp.regex'     => 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
            'nouveau_mdp.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Vérifie que l'ancien mot de passe saisi correspond bien à celui en base
        if (!Hash::check($donnees['ancien_mdp'], $employe->mdp_employe)) {
            return back()->withErrors(['ancien_mdp' => 'L\'ancien mot de passe est incorrect.']);
        }

        // Tout est bon : on enregistre le nouveau mot de passe hashé
        $employe->update([
            'mdp_employe' => Hash::make($donnees['nouveau_mdp']),
        ]);

        return redirect()->route('admin.profil.edit')->with('succes', 'Mot de passe modifié.');
    }
}