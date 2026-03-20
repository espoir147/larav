<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Utilisateur;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $proprietaireId = Auth::id();

        // Récupérer les paiements avec filtres
        $query = $this->getPaiementsQuery($proprietaireId);

        // Appliquer les filtres de la requête
        $query = $this->appliquerFiltres($query, $request);

        // Récupérer les données pour les filtres
        $filtres = $this->getDonneesFiltres($proprietaireId);

        // Paginer les résultats
        $paiements = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('proprietaire.paiement', compact('paiements', 'filtres'));
    }

    // Méthode pour exporter en PDF
    public function exporterPDF(Request $request)
    {
        try {
            $proprietaireId = Auth::id();
            $proprietaire = Auth::user();

            // Construire la requête de base
            $query = $this->getPaiementsQuery($proprietaireId);

            // Appliquer les filtres depuis la requête
            $query = $this->appliquerFiltres($query, $request);

            // Récupérer les paiements filtrés
            $paiements = $query->orderBy('created_at', 'desc')->get();

            // Calculer le total
            $total = $paiements->sum('montant');

            // Préparer les données pour le PDF
            $periode = $this->getPeriodeTexte($request);

            $data = [
                'paiements' => $paiements,
                'proprietaire' => $proprietaire,
                'total' => $total,
                'periode' => $periode,
                'dateGeneration' => now()->format('d/m/Y H:i'),
                'avecTotal' => $request->has('avec_total'),
            ];

            // Générer le nom du fichier
            $nomFichier = 'paiements_' . $proprietaireId . '_' . now()->format('Ymd_His') . '.pdf';

            // Générer le PDF
            $pdf = Pdf::loadView('proprietaire.pdf.paiements', $data);

            // Options du PDF
            $pdf->setPaper('A4', 'portrait');

            // Télécharger le PDF
            return $pdf->download($nomFichier);

        } catch (\Exception $e) {
            return redirect()->route('proprietaire.paiements')
                ->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }

    // Méthode pour exporter un reçu individuel
    public function exporterRecu($id)
    {
        try {
            $proprietaireId = Auth::id();
            $proprietaire = Auth::user();

            // Récupérer le paiement
            $paiement = Paiement::where('id', $id)
                ->with(['utilisateur', 'maison', 'appartement', 'operateur'])
                ->firstOrFail();

            // Vérifier que le paiement appartient bien au propriétaire
            if ($paiement->maison && $paiement->maison->utilisateur_id != $proprietaireId) {
                if ($paiement->appartement && $paiement->appartement->utilisateur_id != $proprietaireId) {
                    return redirect()->route('proprietaire.paiements')
                        ->with('error', 'Accès non autorisé à ce paiement.');
                }
            }

            $data = [
                'paiement' => $paiement,
                'proprietaire' => $proprietaire,
                'dateGeneration' => now()->format('d/m/Y H:i'),
            ];

            // Générer le nom du fichier
            $nomFichier = 'recu_paiement_' . $paiement->id . '_' . now()->format('Ymd_His') . '.pdf';

            // Générer le PDF
            $pdf = Pdf::loadView('proprietaire.pdf.recu-individuel', $data);
            $pdf->setPaper('A4', 'portrait');

            // Télécharger le PDF
            return $pdf->download($nomFichier);

        } catch (\Exception $e) {
            return redirect()->route('proprietaire.paiements')
                ->with('error', 'Erreur lors de la génération du reçu : ' . $e->getMessage());
        }
    }

    // ===== MÉTHODES PRIVÉES =====

    private function getPaiementsQuery($proprietaireId)
    {
        return Paiement::where(function($query) use ($proprietaireId) {
            $query->whereHas('maison', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            })->orWhereHas('appartement', function($q) use ($proprietaireId) {
                $q->where('utilisateur_id', $proprietaireId);
            });
        })
        ->with(['utilisateur', 'maison', 'appartement', 'operateur']);
    }

    private function appliquerFiltres($query, Request $request)
    {
        // Filtre par locataire
        if ($request->filled('locataire_id')) {
            $query->where('utilisateur_id', $request->locataire_id);
        }

        // Filtre par type de bien
        if ($request->filled('type_bien')) {
            if ($request->type_bien == 'maison') {
                $query->whereHas('maison');
            } elseif ($request->type_bien == 'appartement') {
                $query->whereHas('appartement');
            }
        }

        // Filtre par période
        if ($request->filled('periode')) {
            $dateDebut = null;

            switch ($request->periode) {
                case 'mois':
                    $dateDebut = now()->startOfMonth();
                    break;
                case 'trimestre':
                    $dateDebut = now()->subMonths(3)->startOfDay();
                    break;
                case 'annee':
                    $dateDebut = now()->startOfYear();
                    break;
            }

            if ($dateDebut) {
                $query->where('created_at', '>=', $dateDebut);
            }
        }

        // Filtre par dates personnalisées
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->date_debut)->startOfDay(),
                Carbon::parse($request->date_fin)->endOfDay()
            ]);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        return $query;
    }

    private function getDonneesFiltres($proprietaireId)
{
    // Récupérer tous les IDs des biens du propriétaire
    $maisonsIds = Maison::where('utilisateur_id', $proprietaireId)->pluck('id');
    $appartementsIds = Appartement::where('utilisateur_id', $proprietaireId)->pluck('id');

    // Récupérer les locations acceptées pour ces biens
    $locations = Location::where(function($query) use ($maisonsIds, $appartementsIds) {
            $query->whereIn('id_logement', $maisonsIds)
                  ->where('type_logement', 'maison');
        })
        ->orWhere(function($query) use ($appartementsIds) {
            $query->whereIn('id_logement', $appartementsIds)
                  ->where('type_logement', 'appartement');
        })
        ->where('statut', 'acceptee') // Vérifie que c'est bien 'statut' (singulier)
        ->with('utilisateur')
        ->get();

    // Extraire les utilisateurs uniques
    $locataires = $locations->pluck('utilisateur')
        ->unique('id')
        ->values();

    return [
        'locataires' => $locataires,
    ];
}

    private function getPeriodeTexte(Request $request)
    {
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            return 'du ' . Carbon::parse($request->date_debut)->format('d/m/Y') .
                   ' au ' . Carbon::parse($request->date_fin)->format('d/m/Y');
        }

        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'mois':
                    return 'du mois de ' . now()->format('F Y');
                case 'trimestre':
                    return 'des 3 derniers mois';
                case 'annee':
                    return 'de l\'année ' . now()->format('Y');
            }
        }

        return 'toutes périodes';
    }
}
