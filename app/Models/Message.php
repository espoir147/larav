<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'messages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'expediteur_id', 'destinataire_id', 'logement_type', 
        'logement_id', 'contenu', 'lu'
    ];

    protected $casts = [
        'lu' => 'boolean',
        'date_envoi' => 'datetime'
    ];

    // Relations
    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id');
    }

    public function destinataire()
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'logement_id');
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'logement_id');
    }

    // Scopes
    public function scopeNonLus($query)
    {
        return $query->where('lu', false);
    }
}