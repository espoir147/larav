<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Appartement;
use Illuminate\Support\Facades\Auth;

class BienController extends Controller
{
    public function index()
    {
        $proprietaireId = Auth::id();

        $maisons = Maison::where('utilisateur_id', $proprietaireId)
                        ->withCount(['locations as locations_actives' => function($query) {
                            $query->where('statut', 'acceptee')
                                  ->where('date_debut', '<=', now());
                        }])
                        ->get();

        $appartements = Appartement::where('utilisateur_id', $proprietaireId)
                                 ->withCount(['locations as locations_actives' => function($query) {
                                     $query->where('statut', 'acceptee')
                                           ->where('date_debut', '<=', now());
                                 }])
                                 ->get();

        return view('proprietaire.biens', compact('maisons', 'appartements'));
    }
}
