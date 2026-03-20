<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Appartement;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index()
    {
        $proprietaireId = Auth::id();

        $maisons = Maison::where('utilisateur_id', $proprietaireId)->get();
        $appartements = Appartement::where('utilisateur_id', $proprietaireId)->get();

        return view('proprietaire.annonces', compact('maisons', 'appartements'));
    }
}
