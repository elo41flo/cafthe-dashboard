<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'numero_client';
    public $timestamps = false;

    protected $fillable = [
        'nom_client',
        'prenom_client',
        'adresse_livraison',
        'code_postal_livraison',
        'ville_livraison',
        'adresse_facturation',
        'code_postal_facturation',
        'ville_facturation',
        'email_client',
        'telephone',
        'mdp_client',
        'numero_employe',
        'est_abonne',
        'type_abonnement',
        'date_debut_abo',
        'duree_abo_mois',
    ];

    // Jamais exposé si converti en JSON
    protected $hidden = [
        'mdp_client',
    ];

    // Un client peut avoir passé plusieurs commandes
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'numero_client', 'numero_client');
    }
}