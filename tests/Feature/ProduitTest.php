<?php

namespace Tests\Feature;

use App\Models\Employe;
use App\Models\Produit;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProduitTest extends TestCase
{
    // Crée un employé et le connecte, pour tester les routes protégées par auth
    protected function employeConnecte(): Employe
    {
        $employe = Employe::create([
            'nom_employe'    => 'Test',
            'prenom_employe' => 'Vendeur',
            'email_employe'  => 'produit_' . uniqid() . '@cafthe.fr',
            'role'           => 'vendeur',
            'mdp_employe'    => Hash::make('MotDePasse12!'),
        ]);

        // actingAs() simule un utilisateur déjà connecté pour la suite du test
        $this->actingAs($employe);

        return $employe;
    }

    // Test : la page liste des produits est accessible pour un employé connecté
    public function test_un_employe_connecte_peut_voir_la_liste_des_produits(): void
    {
        $this->employeConnecte();

        $response = $this->get('/admin/produits');

        $response->assertStatus(200);
    }

    // Test : création d'un produit, avec calcul automatique du prix TTC
    public function test_un_employe_peut_creer_un_produit(): void
    {
        $this->employeConnecte();

        $response = $this->post('/admin/produits', [
            'nom_produit' => 'Thé test',
            'description' => 'Un thé de test',
            'categorie'   => 'The',
            'type_vente'  => 'Poids',
            'tva'         => 5.5,
            'prix_ht'     => 10,
            'stock'       => 100,
        ]);

        // Redirection après création réussie
        $response->assertRedirect('/admin/produits');

        // Le produit existe bien en base
        $this->assertDatabaseHas('produits', [
            'nom_produit' => 'Thé test',
        ]);

        // Le prix TTC a été calculé automatiquement : 10 € HT + 5,5 % = 10,55 €
        $produit = Produit::where('nom_produit', 'Thé test')->first();
        $this->assertEquals(10.55, $produit->prix_ttc);
    }

    // Test : la création échoue si un champ obligatoire manque (ici le nom)
    public function test_la_creation_echoue_sans_nom_de_produit(): void
    {
        $this->employeConnecte();

        $response = $this->post('/admin/produits', [
            // pas de nom_produit
            'categorie'  => 'The',
            'type_vente' => 'Poids',
            'tva'        => 5.5,
            'prix_ht'    => 10,
            'stock'      => 100,
        ]);

        // La validation renvoie une erreur sur le champ nom_produit
        $response->assertSessionHasErrors('nom_produit');
    }

    // Test : modification d'un produit existant
    public function test_un_employe_peut_modifier_un_produit(): void
    {
        $this->employeConnecte();

        $produit = Produit::create([
            'nom_produit' => 'Ancien nom',
            'categorie'   => 'Cafe',
            'type_vente'  => 'Poids',
            'tva'         => 5.5,
            'prix_ht'     => 20,
            'prix_ttc'    => 21.10,
            'stock'       => 50,
        ]);

        $response = $this->put('/admin/produits/' . $produit->numero_produit, [
            'nom_produit' => 'Nouveau nom',
            'categorie'   => 'Cafe',
            'type_vente'  => 'Poids',
            'tva'         => 5.5,
            'prix_ht'     => 20,
            'stock'       => 50,
        ]);

        $response->assertRedirect('/admin/produits');

        // Le nom a bien été mis à jour en base
        $this->assertDatabaseHas('produits', [
            'numero_produit' => $produit->numero_produit,
            'nom_produit'    => 'Nouveau nom',
        ]);
    }

    // Test : suppression d'un produit
    public function test_un_employe_peut_supprimer_un_produit(): void
    {
        $this->employeConnecte();

        $produit = Produit::create([
            'nom_produit' => 'À supprimer',
            'categorie'   => 'Accessoire',
            'type_vente'  => 'Unite',
            'tva'         => 20,
            'prix_ht'     => 15,
            'prix_ttc'    => 18,
            'stock'       => 10,
        ]);

        $response = $this->delete('/admin/produits/' . $produit->numero_produit);

        $response->assertRedirect('/admin/produits');

        // Le produit n'existe plus en base
        $this->assertDatabaseMissing('produits', [
            'numero_produit' => $produit->numero_produit,
        ]);
    }
}