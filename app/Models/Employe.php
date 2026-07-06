<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employe extends Authenticatable
{
    // Table et clé primaire différentes du standard Laravel (id) : on précise les vraies
    protected $table = 'employes';
    protected $primaryKey = 'numero_employe';

    // Pas de colonnes created_at/updated_at dans la table : on désactive leur gestion auto
    public $timestamps = false;

    // Colonnes autorisées en remplissage de masse (create/update), pour éviter qu'un
    // formulaire piégé modifie un champ sensible comme "role"
    protected $fillable = [
        'nom_employe',
        'prenom_employe',
        'email_employe',
        'telephone_employe',
        'role',
        'mdp_employe',
    ];

    // Jamais exposé si le modèle est converti en JSON (ex : réponse API)
    protected $hidden = [
        'mdp_employe',
    ];

    // Laravel cherche une colonne "password" par défaut : on le redirige vers mdp_employe
    public function getAuthPassword()
    {
        return $this->mdp_employe;
    }

    // Pas de colonne remember_token dans la table ("se souvenir de moi") :
    // on neutralise cette fonctionnalité sans toucher à la structure existante
    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        // rien à faire, pas de token à stocker
    }

    public function getRememberTokenName()
    {
        return null;
    }
}