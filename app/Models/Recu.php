<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
    use HasFactory;

    protected $table = 'recus';
    protected $primaryKey = 'id';

    protected $fillable = ['paiement_id', 'chemin_pdf'];

    protected $casts = [
        'date_creation' => 'datetime'
    ];

    // Relations
    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'paiement_id');
    }
}
