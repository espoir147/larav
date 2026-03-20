<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LocataireController extends Controller
{
    /**
     * Afficher la liste des locataires
     */
    public function index()
    {
        $proprietaireId = Auth::id();

        // Récupérer tous les biens du propriétaire
        $maisonsIds = Maison::where('utilisateur_id', $proprietaireId)->pluck('id');
        $appartementsIds = Appartement::where('utilisateur_id', $proprietaireId)->pluck('id');

        // Fusionner les IDs
        $biensIds = $maisonsIds->merge($appartementsIds)->toArray();

        // Si aucun bien, retourner une pagination vide
        if (empty($biensIds)) {
            $locataires = Location::whereNull('id')
                ->where('statut', 'acceptee')
                ->paginate(20);
        } else {
            // Récupérer les locataires avec toutes les relations nécessaires
            $locataires = Location::whereIn('id_logement', $biensIds)
                ->where('statut', 'acceptee')
                ->with([
                    'utilisateur',
                    'maison',
                    'appartement',
                    'paiements' => function($query) {
                        $query->orderBy('created_at', 'desc')->limit(5);
                    }
                ])
                ->orderBy('date_debut', 'desc')
                ->paginate(20);
        }

        // Calcul des statistiques
        $loyersMensuels = 0;
        $paiementsAJour = 0;

        foreach ($locataires as $location) {
            // Calcul loyer mensuel
            if ($location->maison) {
                $loyersMensuels += $location->maison->prix ?? 0;
            } elseif ($location->appartement) {
                $loyersMensuels += $location->appartement->prix_mensuel ?? 0;
            }

            // Vérification paiement à jour
            if (method_exists($location, 'estEnRetard')) {
                if (!$location->estEnRetard()) {
                    $paiementsAJour++;
                }
            } else {
                // Si la méthode n'existe pas, on considère le paiement à jour
                $paiementsAJour++;
            }
        }

        return view('proprietaire.locataires', [
            'locataires' => $locataires,
            'loyersMensuels' => $loyersMensuels,
            'paiementsAJour' => $paiementsAJour
        ]);
    }

    /**
     * Accepter un client comme locataire pour un bien
     */
    public function accepterLocataire(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:utilisateurs,id',
            'bien_type' => 'required|in:maison,appartement',
            'bien_id' => 'required|integer',
        ]);

        $proprietaireId = Auth::id();

        // Vérifier que le bien appartient bien au propriétaire
        if ($request->bien_type === 'maison') {
            $bien = Maison::where('id', $request->bien_id)
                          ->where('utilisateur_id', $proprietaireId)
                          ->first();
        } else {
            $bien = Appartement::where('id', $request->bien_id)
                               ->where('utilisateur_id', $proprietaireId)
                               ->first();
        }

        if (!$bien) {
            return response()->json([
                'success' => false,
                'message' => 'Bien non trouvé ou non autorisé'
            ], 404);
        }

        // Vérifier si une location existe déjà pour ce client et ce bien
        $locationExistante = Location::where('utilisateur_id', $request->client_id)
                                     ->where('id_logement', $request->bien_id)
                                     ->where('type_logement', $request->bien_type)
                                     ->whereIn('statut', ['en_attente', 'acceptee'])
                                     ->first();

        if ($locationExistante) {
            return response()->json([
                'success' => false,
                'message' => 'Ce client est déjà locataire ou a déjà une demande en cours pour ce bien'
            ], 400);
        }

        // Vérifier si le bien est déjà loué à quelqu'un d'autre
        $bienOccupe = Location::where('id_logement', $request->bien_id)
                              ->where('type_logement', $request->bien_type)
                              ->where('statut', 'acceptee')
                              ->first();

        if ($bienOccupe) {
            return response()->json([
                'success' => false,
                'message' => 'Ce bien est déjà loué à un autre locataire'
            ], 400);
        }

        // Créer la nouvelle location
        DB::beginTransaction();

        try {
            $location = Location::create([
                'utilisateur_id' => $request->client_id,
                'type_logement' => $request->bien_type,
                'id_logement' => $request->bien_id,
                'statut' => 'acceptee',
                'date_debut' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Marquer le bien comme non disponible
            if ($request->bien_type === 'maison') {
                $bien->update(['disponible' => false]);
            } else {
                $bien->update(['disponible' => false]);
            }

            // Envoyer un message automatique au client
            Message::create([
                'expediteur_id' => $proprietaireId,
                'destinataire_id' => $request->client_id,
                'logement_type' => $request->bien_type,
                'logement_id' => $request->bien_id,
                'contenu' => 'Félicitations ! Votre demande de location a été acceptée. Vous êtes désormais locataire de ce bien.',
                'lu' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Client accepté comme locataire avec succès',
                'location' => [
                    'id' => $location->id,
                    'client_id' => $location->utilisateur_id,
                    'bien_type' => $location->type_logement,
                    'bien_id' => $location->id_logement,
                    'date_debut' => $location->date_debut->format('d/m/Y')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la location : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refuser une demande de location
     */
    public function refuserDemande(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:utilisateurs,id',
            'bien_type' => 'required|in:maison,appartement',
            'bien_id' => 'required|integer',
        ]);

        $proprietaireId = Auth::id();

        // Vérifier que le bien appartient au propriétaire
        if ($request->bien_type === 'maison') {
            $bien = Maison::where('id', $request->bien_id)
                          ->where('utilisateur_id', $proprietaireId)
                          ->first();
        } else {
            $bien = Appartement::where('id', $request->bien_id)
                               ->where('utilisateur_id', $proprietaireId)
                               ->first();
        }

        if (!$bien) {
            return response()->json([
                'success' => false,
                'message' => 'Bien non trouvé ou non autorisé'
            ], 404);
        }

        // Trouver la demande en attente
        $demande = Location::where('utilisateur_id', $request->client_id)
                          ->where('id_logement', $request->bien_id)
                          ->where('type_logement', $request->bien_type)
                          ->where('statut', 'en_attente')
                          ->first();

        if (!$demande) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune demande en attente trouvée pour ce client et ce bien'
            ], 404);
        }

        // Mettre à jour le statut
        $demande->update(['statut' => 'refusee']);

        // Envoyer un message au client
        Message::create([
            'expediteur_id' => $proprietaireId,
            'destinataire_id' => $request->client_id,
            'logement_type' => $request->bien_type,
            'logement_id' => $request->bien_id,
            'contenu' => 'Votre demande de location a été refusée.',
            'lu' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Demande refusée avec succès'
        ]);
    }

    /**
     * Vérifier si un client est déjà locataire pour un bien
     */
    public function verifierStatut(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:utilisateurs,id',
            'bien_type' => 'required|in:maison,appartement',
            'bien_id' => 'required|integer',
        ]);

        $location = Location::where('utilisateur_id', $request->client_id)
                           ->where('id_logement', $request->bien_id)
                           ->where('type_logement', $request->bien_type)
                           ->where('statut', 'acceptee')
                           ->first();

        return response()->json([
            'success' => true,
            'est_locataire' => !is_null($location),
            'location' => $location ? [
                'id' => $location->id,
                'date_debut' => $location->date_debut->format('d/m/Y')
            ] : null
        ]);
    }

    /**
     * Résilier un contrat de location
     */
    public function terminer(Request $request, $id)
    {
        try {
            $location = Location::findOrFail($id);

            // Vérifier que le propriétaire est bien propriétaire du bien
            $proprietaireId = Auth::id();

            if ($location->type_logement == 'maison') {
                $bien = Maison::find($location->id_logement);
            } else {
                $bien = Appartement::find($location->id_logement);
            }

            // Vérifier que le bien appartient bien au propriétaire
            if (!$bien || $bien->utilisateur_id != $proprietaireId) {
                return redirect()->route('proprietaire.locataires')
                    ->with('error', 'Vous n\'êtes pas autorisé à résilier ce contrat.');
            }

            // Mettre à jour le statut de la location
            $location->update([
                'statut' => 'terminee',
                'date_fin' => now(),
            ]);

            // Rendre le bien disponible à nouveau
            $bien->update(['disponible' => true]);

            // Envoyer un message au client
            Message::create([
                'expediteur_id' => $proprietaireId,
                'destinataire_id' => $location->utilisateur_id,
                'logement_type' => $location->type_logement,
                'logement_id' => $location->id_logement,
                'contenu' => 'Votre contrat de location a été résilié.',
                'lu' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('proprietaire.locataires')
                ->with('success', 'Le contrat de location a été résilié avec succès.');

        } catch (\Exception $e) {
            return redirect()->route('proprietaire.locataires')
                ->with('error', 'Une erreur est survenue lors de la résiliation du contrat.');
        }
    }

    /**
     * Expulser un locataire (alias de terminer)
     */
    public function expulser(Request $request, $id)
    {
        return $this->terminer($request, $id);
    }
}
