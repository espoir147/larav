<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagerieController extends Controller
{
    public function index(Request $request)
    {
        $proprietaireId = Auth::id();

        // Récupérer toutes les conversations (groupées par interlocuteur)
        $conversations = $this->getConversations($proprietaireId);

        // Nombre total de messages non lus
        $nonLus = Message::where('destinataire_id', $proprietaireId)
                        ->where('lu', false)
                        ->count();

        // ID de la conversation à ouvrir automatiquement (depuis l'URL)
        $conversationActive = $request->get('conversation');

        return view('proprietaire.messagerie', compact('conversations', 'nonLus', 'conversationActive'));
    }

    // ===== MÉTHODES PRIVÉES =====

    private function getConversations($userId)
    {
        // Récupérer tous les messages du propriétaire
        $messages = Message::where('expediteur_id', $userId)
                          ->orWhere('destinataire_id', $userId)
                          ->with(['expediteur', 'destinataire', 'maison', 'appartement'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        // Grouper par interlocuteur
        $conversations = [];

        foreach ($messages as $message) {
            // Déterminer l'interlocuteur
            $interlocuteurId = $message->expediteur_id == $userId
                             ? $message->destinataire_id
                             : $message->expediteur_id;

            $interlocuteur = $message->expediteur_id == $userId
                           ? $message->destinataire
                           : $message->expediteur;

            if (!$interlocuteur) continue;

            // Initialiser la conversation si elle n'existe pas
            if (!isset($conversations[$interlocuteurId])) {
                $conversations[$interlocuteurId] = [
                    'interlocuteur' => $interlocuteur,
                    'dernier_message' => $message,
                    'messages_non_lus' => 0,
                    'biens' => [],
                    'messages' => []
                ];
            }

            // Ajouter le message à la conversation
            $conversations[$interlocuteurId]['messages'][] = $message;

            // Compter les messages non lus
            if ($message->destinataire_id == $userId && !$message->lu) {
                $conversations[$interlocuteurId]['messages_non_lus']++;
            }

            // Ajouter le bien concerné s'il n'est pas déjà listé
            $bien = $message->maison ?? $message->appartement;
            if ($bien) {
                $bienKey = $message->logement_type . '_' . $message->logement_id;
                if (!isset($conversations[$interlocuteurId]['biens'][$bienKey])) {
                    $conversations[$interlocuteurId]['biens'][$bienKey] = [
                        'type' => $message->logement_type,
                        'id' => $message->logement_id,
                        'nom' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                        'adresse' => $bien->adresse ?? ''
                    ];
                }
            }
        }

        // Trier les conversations par date du dernier message (plus récent en premier)
        uasort($conversations, function($a, $b) {
            return $b['dernier_message']->created_at <=> $a['dernier_message']->created_at;
        });

        // Pour chaque conversation, trier les messages par date (plus ancien en premier pour l'affichage)
        foreach ($conversations as &$conv) {
            $conv['messages'] = collect($conv['messages'])->sortBy('created_at')->values()->all();
        }

        return $conversations;
    }

    // ===== ROUTES API =====

    public function getConversation($interlocuteurId)
    {
        $proprietaireId = Auth::id();

        // Récupérer tous les messages entre le propriétaire et cet interlocuteur
        $messages = Message::where(function($query) use ($proprietaireId, $interlocuteurId) {
                $query->where('expediteur_id', $proprietaireId)
                      ->where('destinataire_id', $interlocuteurId);
            })
            ->orWhere(function($query) use ($proprietaireId, $interlocuteurId) {
                $query->where('expediteur_id', $interlocuteurId)
                      ->where('destinataire_id', $proprietaireId);
            })
            ->with(['expediteur', 'destinataire', 'maison', 'appartement'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus
        Message::where('expediteur_id', $interlocuteurId)
              ->where('destinataire_id', $proprietaireId)
              ->where('lu', false)
              ->update(['lu' => true]);

        // Formater les messages pour le JSON
        $formattedMessages = $messages->map(function($message) use ($proprietaireId) {
            $bien = $message->maison ?? $message->appartement;

            return [
                'id' => $message->id,
                'contenu' => $message->contenu,
                'date' => $message->created_at->format('d/m/Y'),
                'heure' => $message->created_at->format('H:i'),
                'est_moi' => $message->expediteur_id == $proprietaireId,
                'expediteur_nom' => $message->expediteur->nom ?? 'Inconnu',
                'expediteur_photo' => $message->expediteur->photo_profil
                                    ? asset('storage/' . $message->expediteur->photo_profil)
                                    : asset('images/default-avatar.png'),
                'bien_nom' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                'bien_type' => $message->logement_type,
                'bien_id' => $message->logement_id,
                'lu' => $message->lu
            ];
        });

        // Récupérer les infos de l'interlocuteur
        $interlocuteur = Utilisateur::find($interlocuteurId);

        // Récupérer les biens concernés dans cette conversation
        $biens = [];
        foreach ($messages as $message) {
            $bien = $message->maison ?? $message->appartement;
            if ($bien) {
                $key = $message->logement_type . '_' . $message->logement_id;
                if (!isset($biens[$key])) {
                    $biens[$key] = [
                        'type' => $message->logement_type,
                        'id' => $message->logement_id,
                        'nom' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                        'adresse' => $bien->adresse . ', ' . $bien->ville
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'messages' => $formattedMessages,
            'interlocuteur' => [
                'id' => $interlocuteur->id,
                'nom' => $interlocuteur->nom,
                'prenom' => $interlocuteur->prenom,
                'photo' => $interlocuteur->photo_profil
                         ? asset('storage/' . $interlocuteur->photo_profil)
                         : asset('images/default-avatar.png'),
                'telephone' => $interlocuteur->telephone
            ],
            'biens' => array_values($biens)
        ]);
    }

    public function envoyerMessage(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:utilisateurs,id',
            'contenu' => 'required|string|max:1000',
            'logement_type' => 'required|in:maison,appartement',
            'logement_id' => 'required|integer',
        ]);

        $message = Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'contenu' => $request->contenu,
            'logement_type' => $request->logement_type,
            'logement_id' => $request->logement_id,
            'lu' => false
        ]);

        // Charger les relations pour le retour JSON
        $message->load(['expediteur', 'destinataire', 'maison', 'appartement']);

        $bien = $message->maison ?? $message->appartement;

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'contenu' => $message->contenu,
                'date' => $message->created_at->format('d/m/Y'),
                'heure' => $message->created_at->format('H:i'),
                'est_moi' => true,
                'expediteur_nom' => $message->expediteur->nom,
                'bien_nom' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                'bien_type' => $message->logement_type,
                'bien_id' => $message->logement_id
            ]
        ]);
    }

    public function marquerCommeLu(Request $request)
    {
        $request->validate([
            'expediteur_id' => 'required|exists:utilisateurs,id'
        ]);

        Message::where('expediteur_id', $request->expediteur_id)
              ->where('destinataire_id', Auth::id())
              ->where('lu', false)
              ->update(['lu' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marqués comme lus'
        ]);
    }

    public function supprimerConversation($interlocuteurId)
    {
        $proprietaireId = Auth::id();

        // Supprimer tous les messages entre le propriétaire et cet interlocuteur
        Message::where(function($query) use ($proprietaireId, $interlocuteurId) {
                $query->where('expediteur_id', $proprietaireId)
                      ->where('destinataire_id', $interlocuteurId);
            })
            ->orWhere(function($query) use ($proprietaireId, $interlocuteurId) {
                $query->where('expediteur_id', $interlocuteurId)
                      ->where('destinataire_id', $proprietaireId);
            })
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation supprimée'
        ]);
    }
}
