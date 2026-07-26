<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';
    protected $primaryKey = 'numero_produit';

    // Pas de colonnes created_at/updated_at dans la table
    public $timestamps = false;

    protected $fillable = [
        'nom_produit',
        'description',
        'categorie',
        'type_vente',
        'tva',
        'prix_ht',
        'prix_ttc',
        'stock',
        'image',
        'origine',
        'numero_promotion',
    ];

    public function scopeEnRupture($query)
    {
        // On cible la colonne 'stock' <= 0
        return $query->where('stock', '<=', 0);
    }

    public function scopeStockFaible($query, $seuil = 10)
    {
        // On cherche les produits qui sont encore du stock
        return $query->where('stock', '>', 0)
                    ->where('stock', '<=', $seuil);
    }
}