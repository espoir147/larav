<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'utilisateur_id', 'type_logement', 'id_logement', 'statut', 'date_debut'
    ];
 
    protected $casts = [
        'date_debut' => 'date'
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'id_logement');
    }

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'id_logement');
    }

    // Relation polymorphique pour obtenir le logement (maison ou appartement)
    public function logement()
    {
        if ($this->type_logement === 'maison') {
            return $this->belongsTo(Maison::class, 'id_logement');
        } else {
            return $this->belongsTo(Appartement::class, 'id_logement');
        }
    }

    // NOUVELLE RELATION : Paiements pour cette location
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'utilisateur_id', 'utilisateur_id')
                    ->where(function($query) {
                        $query->where('maison_id', $this->id_logement)
                              ->orWhere('appartement_id', $this->id_logement);
                    })
                    ->orderBy('date_paiement', 'desc');
    }

    // Récupère le bien associé (maison ou appartement)
    public function getBienAttribute()
    {
        if ($this->type_logement === 'maison') {
            return $this->maison;
        } else {
            return $this->appartement;
        }
    }

    // Scopes
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAcceptees($query)
    {
        return $query->where('statut', 'acceptee');
    }

    public function scopeActives($query)
    {
        return $query->where('statut', 'acceptee')
                    ->where('date_debut', '<=', now());
    }

    // Vérifie si le locataire est en retard de paiement
    public function estEnRetard()
    {
        $dernierPaiement = $this->getDernierPaiement();
        if (!$dernierPaiement) return true;

        // Considérer en retard si dernier paiement > 35 jours
        return $dernierPaiement->created_at->diffInDays(now()) > 35;
    }

    // Récupère le dernier paiement
    public function getDernierPaiement()
    {
        return $this->paiements()->latest()->first();
    }
}

