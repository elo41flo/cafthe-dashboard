<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Affiche le formulaire de connexion (vue Blade)
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Traite les données envoyées par le formulaire de connexion
    public function login(Request $request)
    { 
        // On vérifie que les champs email et mot de pase sont bien remplis
        $identifiants = $request->validate([
            'email_employe' => 'required|email',
            'mdp_employe' => 'required',
        ]);

        // Auth::attempt() compare l'email/mot de passe envoyés avec ceux en base.
        // Il utilise automatiquement getAuthPassword() qu'on a défini dans le modèle,
        // et compare le mot de passe avec Hash::check() en interne (donc bcrypt, sécurisé).
        // On doit lui dire explicitement quel champ correspond au mot de passe.
        if (Auth::attempt([
            'email_employe' => $identifiants['email_employe'],
            'password' => $identifiants['mdp_employe'], // "password" est une clé spécialeattendue par attempt()
        ])) {
            // Connexion réussie : on régènre l'ID de session (sécurité anti-fixation de session)
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Connexion échouée : on retourne au formulaire avec un message d'erreur
        return back()->withErrors([
            'email_employe' => 'Identifiants incorrects.',
        ])->onlyInput('email_employe');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
