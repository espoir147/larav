<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appartement extends Model
{
    use HasFactory;

    protected $table = 'appartements';
    protected $primaryKey = 'id';

    protected $fillable = [
        'utilisateur_id',
        'numero_appartement',
        'adresse',
        'ville',
        'descriptin',
        'prix_mensuel',
        'nombre_chambres',
        'salon',
        'type',
        'disponible',
        'statut_publication', // AJOUT
        'photos',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'salon' => 'boolean',
        'disponible' => 'boolean',
        'prix_mensuel' => 'decimal:2'
    ];

    // Valeurs par défaut
    protected $attributes = [
        'statut_publication' => 'en_attente',
        'disponible' => true
    ];

    // Relation avec le propriétaire
    public function proprietaire()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    // Relation avec les locations
    public function locations()
    {
        return $this->hasMany(Location::class, 'id_logement');
    }

    /// Modifier l'accesseur getPhotosArrayAttribute dans les deux modèles :

    public function getPhotosArrayAttribute()
    {
        if (empty($this->photos)) {
            return [];
        }

        $photos = explode(',', $this->photos);
        return array_map(function($photo) {
            $trimmed = trim($photo);
            // Si le chemin ne commence pas par /storage/, l'ajouter
            if (!empty($trimmed) && strpos($trimmed, '/storage/') !== 0) {
                return '/storage/' . $trimmed;
            }
            return $trimmed;
        }, $photos);
    }

    public function getFirstPhotoAttribute()
    {
        $photos = $this->photos_array;
        return empty($photos) ? '/images/default.jpg' : $photos[0];
    }

    // === NOUVEAUX ACCESSORS POUR LE STATUT ===

    // Badge CSS selon statut publication
    public function getStatutPublicationBadgeAttribute()
    {
        $badges = [
            'en_attente' => 'badge-warning',
            'approuve' => 'badge-success',
            'rejete' => 'badge-danger'
        ];
        return $badges[$this->statut_publication] ?? 'badge-secondary';
    }

    // Texte du statut formaté
    public function getStatutPublicationTexteAttribute()
    {
        $textes = [
            'en_attente' => 'En attente de validation',
            'approuve' => 'Approuvé',
            'rejete' => 'Rejeté'
        ];
        return $textes[$this->statut_publication] ?? $this->statut_publication;
    }

    // === SCOPES POUR FILTRER FACILEMENT ===

    public function scopeEnAttente($query)
    {
        return $query->where('statut_publication', 'en_attente');
    }

    public function scopeApprouves($query)
    {
        return $query->where('statut_publication', 'approuve');
    }

    public function scopeRejetes($query)
    {
        return $query->where('statut_publication', 'rejete');
    }

    public function scopeDisponibles($query)
    {
        return $query->where('disponible', true);
    }

    // Vérifier si le bien est visible sur le site
    public function getEstVisibleAttribute()
    {
        return $this->statut_publication === 'approuve' && $this->disponible === true;
    }
}
