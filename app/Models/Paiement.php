<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';
    protected $primaryKey = 'id';

    protected $fillable = [
        'utilisateur_id', 'maison_id', 'appartement_id', 'montant',
        'mois_paye', 'annee_paye', 'operateur_id', 'reference_transaction'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime'
    ];

    // Relations
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function maison()
    {
        return $this->belongsTo(Maison::class, 'maison_id');
    } 

    public function appartement()
    {
        return $this->belongsTo(Appartement::class, 'appartement_id');
    }

    public function operateur()
    {
        return $this->belongsTo(Operateur::class, 'operateur_id');
    }

    public function recu()
    {
        return $this->hasOne(Recu::class, 'paiement_id');
    }

    /**
     * Un paiement est considéré récent s'il date de moins de 35 jours
     */
    public function estRecent(): bool
    {
        return $this->created_at->diffInDays(now()) <= 35;
    }
}
