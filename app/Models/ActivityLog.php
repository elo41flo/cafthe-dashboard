<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    // On désactive 'update_at' car un log d'activité ne se modifie jamais
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_adress',
        'created_at',
    ];

    // Relation vers l'utilisateur / employé qui a fait l'action
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Méthode statique pour enregistrer un log très simplement de n'importe où
    public static function log(string $action, ?string $description = null): void
    {
        static::created([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'ip_adress' => request()->ip(),
            'created_at' => now(),
        ]);
    }
}
