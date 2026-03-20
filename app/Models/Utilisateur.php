<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nom',
        'email',
        'indicatif_pays',
        'telephone',
        'date_naissance',
        'mot_de_passe',
        'type',
        'photo_profil',
        'statut',
        'last_login_at'
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
        'last_login_at'    => 'datetime',
    ];

    // Relations
    public function maisons()
    {
        return $this->hasMany(Maison::class, 'utilisateur_id');
    }

    public function appartements()
    {
        return $this->hasMany(Appartement::class, 'utilisateur_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'utilisateur_id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'utilisateur_id');
    }

    // Méthodes utilitaires pour vérifier le statut
    public function isActif()
    {
        return $this->statut === 'actif';
    }

    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function isBloque()
    {
        return $this->statut === 'bloque';
    }

    public function isRejete()
    {
        return $this->statut === 'rejete';
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'user_id');
    }
}
