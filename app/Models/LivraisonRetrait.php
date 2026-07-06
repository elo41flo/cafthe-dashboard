<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LivraisonRetrait extends Model
{
    protected $table = 'livraison_retrait';
    protected $primaryKey = 'numero_livraison';
    public $timestamps = false;

    protected $fillable = [
        'choix_transporteur',
        'date_livraison',
        'delais_livraison',
        'numero_commande',
    ];
}