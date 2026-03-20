<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Location;
use App\Models\Paiement;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $proprietaireId = Auth::id();

        // Récupérer tous les biens du propriétaire
        $maisons = Maison::where('utilisateur_id', $proprietaireId)->get();
        $appartements = Appartement::where('utilisateur_id', $proprietaireId)->get();
        $tousLesBiens = $maisons->merge($appartements);

        // IDs de tous les biens
        $biensIds = $tousLesBiens->pluck('id')->toArray();

        // Statistiques de base
        $stats = [
            'totalBiens' => $tousLesBiens->count(),
            'maisonsCount' => $maisons->count(),
            'appartementsCount' => $appartements->count(),

            'locatairesActifs' => $this->getLocatairesActifsCount($biensIds),
            'locatairesMaisons' => $this->getLocatairesMaisonsCount($maisons->pluck('id')->toArray()),
            'locatairesApparts' => $this->getLocatairesAppartsCount($appartements->pluck('id')->toArray()),

            'revenusMensuels' => $this->getRevenusMensuels($proprietaireId),
            'tauxOccupation' => $this->getTauxOccupation($tousLesBiens, $biensIds),

            'messagesNonLus' => Message::where('destinataire_id', $proprietaireId)
                                      ->where('lu', false)
                                      ->count(),
            'demandesAttente' => Location::whereIn('id_logement', $biensIds)
                                        ->where('statut', 'en_attente')
                                        ->count(),

            'messagesUrgents' => Message::where('destinataire_id', $proprietaireId)
                                       ->where('lu', false)
                                       ->where('created_at', '>=', now()->subDays(1))
                                       ->count(),
        ];

        // Derniers paiements
        $derniersPaiements = Paiement::where(function($query) use ($proprietaireId) {
            $query->whereHas('maison', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            })->orWhereHas('appartement', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            });
        })
        ->with(['utilisateur', 'maison', 'appartement'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Demandes de location en attente
        $demandesLocation = Location::whereIn('id_logement', $biensIds)
        ->where('statut', 'en_attente')
        ->with(['utilisateur', 'logement'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

        // Derniers messages
        $derniersMessages = Message::where('destinataire_id', $proprietaireId)
                                 ->with('expediteur')
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get();

        return view('proprietaire.dashboard', array_merge($stats, [
            'derniersPaiements' => $derniersPaiements,
            'demandesLocation' => $demandesLocation,
            'derniersMessages' => $derniersMessages,
        ]));
    }

    private function getLocatairesActifsCount($biensIds)
    {
        return Location::whereIn('id_logement', $biensIds)
        ->where('statut', 'acceptee')
        ->where('date_debut', '<=', now())
        ->distinct('utilisateur_id')
        ->count('utilisateur_id');
    }

    private function getLocatairesMaisonsCount($maisonsIds)
    {
        if (empty($maisonsIds)) return 0;

        return Location::whereIn('id_logement', $maisonsIds)
        ->where('statut', 'acceptee')
        ->where('type_logement', 'maison')
        ->distinct('utilisateur_id')
        ->count('utilisateur_id');
    }

    private function getLocatairesAppartsCount($appartementsIds)
    {
        if (empty($appartementsIds)) return 0;

        return Location::whereIn('id_logement', $appartementsIds)
        ->where('statut', 'acceptee')
        ->where('type_logement', 'appartement')
        ->distinct('utilisateur_id')
        ->count('utilisateur_id');
    }

    private function getRevenusMensuels($proprietaireId)
    {
        return Paiement::where(function($query) use ($proprietaireId) {
            $query->whereHas('maison', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            })->orWhereHas('appartement', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            });
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->sum('montant');
    }

    private function getTauxOccupation($tousLesBiens, $biensIds)
    {
        if ($tousLesBiens->count() == 0) return 0;

        $biensOccupes = Location::whereIn('id_logement', $biensIds)
        ->where('statut', 'acceptee')
        ->where('date_debut', '<=', now())
        ->distinct('id_logement')
        ->count('id_logement');

        return round(($biensOccupes / $tousLesBiens->count()) * 100);
    }
}
