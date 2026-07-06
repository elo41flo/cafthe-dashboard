<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = 'commande';
    protected $primaryKey = 'numero_commande';
    public $timestamps = false;

    protected $fillable = [
        'date_paiement',
        'statut_de_commande',
        'type_de_commande',
        'date_commande',
        'mode_commande',
        'montant_paiement',
        'mode_paiement',
        'numero_client',
    ];

    // Une commande appartient à un client
    public function client()
    {
        return $this->belongsTo(Client::class, 'numero_client', 'numero_client');
    }

    // Une commande contient plusieurs produits, via la table pivot "contenir"
    // qui stocke en plus la quantité (en grammes) pour chaque produit de la commande
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'contenir', 'numero_commande', 'numero_produit')
                    ->withPivot('quantite_gramme');
    }

    // Une commande a une seule fiche livraison/retrait associée
    public function livraisonRetrait()
    {
        return $this->hasOne(LivraisonRetrait::class, 'numero_commande', 'numero_commande');
    }
}