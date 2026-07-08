<?php

namespace Tests\Feature;

use App\Models\Employe;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthentificationTest extends TestCase
{
    // Prépare un employé de test avant chaque test de cette classe
    protected function creerEmploye(string $role = 'vendeur'): Employe
    {
        return Employe::create([
            'nom_employe'    => 'Test',
            'prenom_employe' => 'Utilisateur',
            'email_employe'  => 'test_' . uniqid() . '@cafthe.fr', // email unique à chaque fois
            'role'           => $role,
            'mdp_employe'    => Hash::make('MotDePasse12!'),
        ]);
    }

    // Test : un employé avec les bons identifiants peut se connecter
    public function test_un_employe_peut_se_connecter_avec_de_bons_identifiants(): void
    {
        $employe = $this->creerEmploye();

        $response = $this->post('/login', [
            'email_employe' => $employe->email_employe,
            'mdp_employe'   => 'MotDePasse12!',
        ]);

        // Après connexion réussie, on est redirigé vers le dashboard
        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    // Test : un mauvais mot de passe empêche la connexion
    public function test_un_mauvais_mot_de_passe_empeche_la_connexion(): void
    {
        $employe = $this->creerEmploye();

        $response = $this->post('/login', [
            'email_employe' => $employe->email_employe,
            'mdp_employe'   => 'MauvaisMotDePasse',
        ]);

        // La connexion échoue : on n'est pas authentifié
        $this->assertGuest();
    }

    // Test : une page protégée redirige un visiteur non connecté vers /login
    public function test_une_page_protegee_redirige_un_visiteur_non_connecte(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}