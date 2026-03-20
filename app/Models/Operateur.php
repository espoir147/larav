<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    use HasFactory;

    protected $table = 'operateurs';
    protected $primaryKey = 'id';

    protected $fillable = ['nom', 'code_operateur'];

    // Relations
    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'operateur_id');
    }
}
