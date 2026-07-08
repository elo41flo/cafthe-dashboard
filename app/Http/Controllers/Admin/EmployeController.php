<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EmployeController extends Controller
{
    // LISTE : affiche tous les employés
    public function index()
    {
        $employes = Employe::orderBy('nom_employe')->get();
        return view('admin.employes.index', compact('employes'));
    }

    // MISE À JOUR : affiche le formulaire pré-rempli
    public function edit(Employe $employe)
    {
        return view('admin.employes.edit', compact('employe'));
    }

    // MISE À JOUR : traite l'envoi du formulaire
    public function update(Request $request, Employe $employe)
    {
        $donnees = $request->validate([
            'nom_employe'       => 'required|string|max:60',
            'prenom_employe'    => 'nullable|string|max:60',
            // unique en ignorant l'employé actuel (sinon son propre email serait vu comme "déjà pris")
            'email_employe'     => 'required|email|max:320|unique:employes,email_employe,' . $employe->numero_employe . ',numero_employe',
            'telephone_employe' => 'nullable|string|max:10',
            'role'              => 'required|in:admin,vendeur',
            // Mot de passe optionnel à la modification : rempli seulement si on veut le changer
            'mdp_employe'       => [
                'nullable',
                'string',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#.\-_])[A-Za-z\d@$!%*?&#.\-_]{12,}$/',
            ],
        ], [
            'mdp_employe.regex' => 'Le mot de passe doit contenir au moins 12 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.',
        ]);

        // On ne met à jour le mot de passe que si un nouveau a été saisi
        if (!empty($donnees['mdp_employe'])) {
            $donnees['mdp_employe'] = Hash::make($donnees['mdp_employe']);
        } else {
            unset($donnees['mdp_employe']); // on retire la clé pour ne pas écraser l'ancien par du vide
        }

        $employe->update($donnees);

        return redirect()->route('admin.employes.index')->with('succes', 'Employé modifié avec succès.');
    }

    // SUPPRESSION
    public function destroy(Employe $employe)
    {
        // Sécurité : un admin ne peut pas se supprimer lui-même (éviter de se verrouiller dehors)
        if ($employe->numero_employe === Auth::user()->numero_employe) {
            return redirect()->route('admin.employes.index')
                ->with('erreur', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $employe->delete();
        return redirect()->route('admin.employes.index')->with('succes', 'Employé supprimé.');
    }

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