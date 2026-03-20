<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Location;
use App\Models\Paiement;
use App\Models\Message;
use App\Models\Maison;
use App\Models\Appartement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class ClientController extends Controller
{
    // Tableau de bord client
    public function dashboard()
    {
        $clientId = Auth::id();

        // Statistiques du client
        $stats = [
            'locationsActives' => Location::where('utilisateur_id', $clientId)
                                        ->where('statut', 'acceptee')
                                        ->where('date_debut', '<=', now())
                                        ->count(),

            'prochainPaiement' => $this->getProchainPaiement($clientId),

            'messagesNonLus' => Message::where('destinataire_id', $clientId)
                                      ->where('lu', false)
                                      ->count(),

            'favorisCount' => $this->getFavorisCount($clientId),

            'totalPaiements' => Paiement::where('utilisateur_id', $clientId)
                                         ->where('statut', 'payé')
                                         ->count(),
        ];

        // Locations en cours
        $locationsEnCours = Location::where('utilisateur_id', $clientId)
                                  ->where('statut', 'acceptee')
                                  ->where('date_debut', '<=', now())
                                  ->with(['maison', 'appartement'])
                                  ->orderBy('date_debut', 'desc')
                                  ->limit(5)
                                  ->get();

        // Derniers paiements confirmés seulement
        $derniersPaiements = Paiement::where('utilisateur_id', $clientId)
                                   ->where('statut', 'payé')
                                   ->with(['maison', 'appartement'])
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        // Messages récents
        $messagesRecents = Message::where('destinataire_id', $clientId)
                                ->orWhere('expediteur_id', $clientId)
                                ->with(['expediteur', 'destinataire'])
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();

        return view('client.dashboard', array_merge($stats, [
            'locationsEnCours' => $locationsEnCours,
            'derniersPaiements' => $derniersPaiements,
            'messagesRecents' => $messagesRecents,
        ]));
    }

    // Méthodes utilitaires
    private function getProchainPaiement($clientId)
    {
        $dernierPaiement = Paiement::where('utilisateur_id', $clientId)
                                 ->orderBy('created_at', 'desc')
                                 ->first();

        if ($dernierPaiement) {
            return $dernierPaiement->created_at->addMonth()->format('d/m/Y');
        }

        return 'Aucun paiement';
    }

    private function getFavorisCount($clientId)
    {
        return 0;
    }

    // ========== PROFIL ==========
    public function profil()
    {
        $client = Auth::user();
        return view('client.profil', compact('client'));
    }

    public function updateProfil(Request $request)
    {
        $client = Auth::user();

        $rules = [
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email,'.$client->id,
            'telephone' => 'required|string|max:10',
            'indicatif_pays' => 'required|string|max:5',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        $data = $request->only('nom', 'email', 'telephone', 'indicatif_pays');

        if ($request->hasFile('photo_profil')) {
            if ($client->photo_profil) {
                Storage::disk('public')->delete($client->photo_profil);
            }

            $path = $request->file('photo_profil')->store('profiles', 'public');
            $data['photo_profil'] = $path;
        }

        if ($request->filled('new_password') && $request->filled('current_password')) {
            $data['password'] = Hash::make($request->new_password);
        }

        $client->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil mis à jour avec succès',
                'photo' => $client->photo_profil ? asset('storage/' . $client->photo_profil) : null
            ]);
        }

        return back()->with('success', 'Profil mis à jour avec succès');
    }

    // ========== LOCATIONS ==========
    public function locations()
    {
        $clientId = Auth::id();

        $locations = Location::where('utilisateur_id', $clientId)
                           ->with(['maison.proprietaire', 'appartement.proprietaire'])
                           ->orderBy('created_at', 'desc')
                           ->get();

        // Statistiques personnalisées du client
        $stats = [
            'actives' => $locations->where('statut', 'acceptee')->count(),
            'total' => $locations->count(),
            'prochain_date' => null,
            'prochain_montant' => null
        ];

        // Trouver le prochain paiement à effectuer
        $prochainPaiement = Paiement::where('utilisateur_id', $clientId)
                                  ->orderBy('date_paiement', 'desc')
                                  ->first();

        if ($prochainPaiement) {
            $stats['prochain_date'] = $prochainPaiement->date_paiement->addMonth()->format('d/m/Y');
            $bien = $prochainPaiement->maison ?? $prochainPaiement->appartement;
            $stats['prochain_montant'] = $bien->prix ?? $bien->prix_mensuel ?? 0;
        }

        return view('client.locations', compact('locations', 'stats'));
    }

    public function locationDetails($id)
    {
        $location = Location::where('utilisateur_id', Auth::id())
                          ->with(['maison.proprietaire', 'appartement.proprietaire'])
                          ->findOrFail($id);

        $bien = $location->maison ?? $location->appartement;
        $proprietaire = $bien->proprietaire;

        return response()->json([
            'success' => true,
            'location' => [
                'id' => $location->id,
                'nom' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                'adresse' => $bien->adresse . ', ' . $bien->ville,
                'type' => $location->type_logement,
                'date_debut' => $location->date_debut->format('d/m/Y'),
                'loyer' => number_format($bien->prix ?? $bien->prix_mensuel ?? 0, 0, ',', ' '),
                'chambres' => $bien->nombre_chambres,
                'proprietaire' => $proprietaire->nom ?? 'N/A',
                'telephone' => $proprietaire->telephone ?? 'N/A',
                'email' => $proprietaire->email ?? 'N/A',
                'description' => $bien->description ?? 'Aucune description disponible'
            ]
        ]);
    }

    public function locationPaiements($id)
    {
        $location = Location::where('utilisateur_id', Auth::id())->findOrFail($id);
        $bien = $location->maison ?? $location->appartement;

        $paiements = Paiement::where('utilisateur_id', Auth::id())
                           ->where('statut', 'payé')
                           ->where(function($query) use ($bien) {
                               $query->where('maison_id', $bien->id)
                                     ->orWhere('appartement_id', $bien->id);
                           })
                           ->orderBy('date_paiement', 'desc')
                           ->get();

        return response()->json([
            'success' => true,
            'paiements' => $paiements->map(function($p) {
                return [
                    'id'        => $p->id,
                    'date'      => $p->date_paiement->format('d/m/Y'),
                    'montant'   => number_format($p->montant, 0, ',', ' '),
                    'reference' => $p->reference_transaction,
                    'statut'    => $p->statut,
                ];
            })
        ]);
    }

    // ========== PAIEMENTS ==========
    public function paiements(Request $request)
    {
        $user = Auth::user();

        $query = Paiement::where('utilisateur_id', $user->id)
                        ->with(['maison', 'appartement', 'operateur'])
                        ->orderBy('date_paiement', 'desc');

        if ($request->filled('mois')) {
            $query->where('mois_paye', $request->mois);
        }

        if ($request->filled('annee')) {
            $query->where('annee_paye', $request->annee);
        }

        $paiements = $query->paginate(10);

        $annees = Paiement::where('utilisateur_id', $user->id)
                         ->selectRaw('DISTINCT annee_paye')
                         ->orderBy('annee_paye', 'desc')
                         ->pluck('annee_paye');

        $mois = Paiement::where('utilisateur_id', $user->id)
                       ->selectRaw('DISTINCT mois_paye')
                       ->orderByRaw("FIELD(mois_paye, 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre')")
                       ->pluck('mois_paye');

        $total = $query->sum('montant');

        return view('client.paiements', [
            'paiements' => $paiements,
            'annees' => $annees,
            'mois' => $mois,
            'total' => $total,
            'filtreMois' => $request->mois,
            'filtreAnnee' => $request->annee
        ]);
    }

    /**
     * Webhook FedaPay — appelé automatiquement par FedaPay après confirmation du paiement
     * Route : POST /client/paiements/webhook (exclue du CSRF)
     */
    public function webhookFedaPay(Request $request)
    {
        // Vérifier la signature FedaPay
        $payload   = $request->getContent();
        $signature = $request->header('X-FEDAPAY-SIGNATURE', '');
        $secret    = config('fedapay.webhook_secret');

        if ($secret && $signature) {
            $expected = hash_hmac('sha256', $payload, $secret);
            if (!hash_equals($expected, $signature)) {
                \Illuminate\Support\Facades\Log::warning('FedaPay webhook signature invalide');
                return response()->json(['error' => 'Signature invalide'], 401);
            }
        }

        $data = $request->all();

        // FedaPay envoie l'événement et la transaction
        $eventType     = $data['name'] ?? null;
        $transactionId = $data['data']['object']['id'] ?? null;
        $statut        = $data['data']['object']['status'] ?? null;

        \Illuminate\Support\Facades\Log::info('FedaPay webhook reçu', [
            'event'          => $eventType,
            'transaction_id' => $transactionId,
            'statut'         => $statut,
        ]);

        if (!$transactionId) {
            return response()->json(['error' => 'Transaction ID manquant'], 400);
        }

        $paiement = Paiement::where('fedapay_id', $transactionId)->first();

        if (!$paiement) {
            return response()->json(['error' => 'Paiement introuvable'], 404);
        }

        // Mettre à jour selon le statut FedaPay
        if (in_array($statut, ['approved', 'transferred'])) {
            $paiement->update([
                'statut'         => 'payé',
                'date_paiement'  => now(),
            ]);
        } elseif (in_array($statut, ['declined', 'canceled'])) {
            $paiement->update(['statut' => 'echoue']);
        }

        return response()->json(['success' => true]);
    }

    public function telechargerRecu($id)
    {
        $paiement = Paiement::where('utilisateur_id', Auth::id())
                           ->with(['maison.proprietaire', 'appartement.proprietaire', 'operateur', 'utilisateur'])
                           ->findOrFail($id);

        $pdf = PDF::loadView('pdf.recu-paiement', compact('paiement'));

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download('recu-paiement-' . $paiement->reference_transaction . '.pdf');
    }

    public function detailsPaiement($id)
    {
        $paiement = Paiement::where('utilisateur_id', Auth::id())
                           ->with(['maison.proprietaire', 'appartement.proprietaire', 'operateur'])
                           ->findOrFail($id);

        $bien = $paiement->maison ?? $paiement->appartement;
        $proprietaire = $bien->proprietaire ?? null;

        return response()->json([
            'success' => true,
            'paiement' => [
                'reference' => $paiement->reference_transaction,
                'date' => $paiement->date_paiement->format('d/m/Y H:i'),
                'montant' => number_format($paiement->montant, 0, ',', ' ') . ' FCFA',
                'mois_annee' => $paiement->mois_paye . ' ' . $paiement->annee_paye,
                'operateur' => $paiement->operateur->nom ?? 'Mobile Money',
                'type_paiement' => 'Mobile Money',
                'nom_bien' => $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? ''),
                'adresse' => $bien->adresse ?? 'N/A',
                'ville' => $bien->ville ?? 'N/A',
                'chambres' => $bien->nombre_chambres ?? 'N/A',
                'prix_mensuel' => number_format($bien->prix ?? $bien->prix_mensuel ?? 0, 0, ',', ' ') . ' FCFA',
                'proprietaire' => $proprietaire->nom ?? 'N/A',
                'email_proprietaire' => $proprietaire->email ?? 'N/A',
                'telephone_proprietaire' => $proprietaire->telephone ?? 'N/A',
            ]
        ]);
    }

    public function initierPaiement(Request $request)
    {
        $rules = [
            'location_id'    => 'required|exists:locations,id',
            'montant'        => 'required|numeric|min:1000',
            'type'           => 'required|in:maison,appartement',
            'payment_method' => 'required|in:mobile_money,card',
        ];

        if ($request->input('payment_method') === 'mobile_money') {
            $rules['operateur'] = 'required|in:MTN,MOOV,CELTIIS';
            $rules['telephone'] = 'required|string|max:20';
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $location = Location::where('utilisateur_id', Auth::id())->findOrFail($request->location_id);
        $bien     = $location->maison ?? $location->appartement;
        $client   = Auth::user();

        // Configurer FedaPay
        \FedaPay\FedaPay::setApiKey(config('fedapay.secret_key'));
        \FedaPay\FedaPay::setEnvironment(config('fedapay.environment', 'live'));

        try {
            $telephone = $request->telephone ?? $client->telephone;

            // Créer la transaction FedaPay
            $transaction = \FedaPay\Transaction::create([
                'description' => 'Paiement loyer - ' . ($bien->nom ?? 'Bien #' . $bien->id),
                'amount'      => (int) $request->montant,
                'currency'    => ['iso' => 'XOF'],
                'callback'    => route('client.paiements.webhook'),
                'customer'    => [
                    'firstname'    => $client->nom,
                    'lastname'     => '',
                    'email'        => $client->email,
                    'phone_number' => $telephone,
                ],
            ]);

            // Envoyer le paiement mobile money
            if ($request->payment_method === 'mobile_money') {
                $transaction->sendNow([
                    'phone_number' => $telephone,
                ]);
            }

            // Sauvegarder en DB seulement si FedaPay a accepté — statut en_attente
            $paiement = Paiement::create([
                'utilisateur_id'        => Auth::id(),
                'maison_id'             => $request->type === 'maison' ? $bien->id : null,
                'appartement_id'        => $request->type === 'appartement' ? $bien->id : null,
                'operateur_id'          => $request->payment_method === 'mobile_money'
                                            ? $this->getOperateurId($request->operateur)
                                            : null,
                'montant'               => $request->montant,
                'date_paiement'         => now(),
                'mois_paye'             => now()->format('F'),
                'annee_paye'            => now()->format('Y'),
                'reference_transaction' => 'FEDA-' . $transaction->id,
                'fedapay_id'            => $transaction->id,
                'statut'                => 'en_attente',
            ]);

            return response()->json([
                'success'   => true,
                'reference' => 'FEDA-' . $transaction->id,
                'montant'   => number_format($request->montant, 0, ',', ' '),
                'operateur' => $request->operateur ?? 'Carte',
                'message'   => $request->payment_method === 'mobile_money'
                                ? 'Un SMS a été envoyé sur votre téléphone pour confirmer le paiement.'
                                : 'Redirection vers FedaPay en cours...',
            ]);

        } catch (\FedaPay\Error\ApiConnection $e) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de contacter FedaPay. Vérifiez votre connexion.',
            ], 503);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('FedaPay error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement : ' . $e->getMessage(),
            ], 500);
        }
    }

    private function getOperateurId($code)
    {
        return DB::table('operateurs')->where('code', $code)->value('id') ?? 1;
    }

    // ========== MESSAGERIE ==========
    public function messagerie(Request $request)
    {
        $clientId = Auth::id();

        // Récupérer toutes les conversations (groupées par interlocuteur)
        $conversations = $this->getConversations($clientId);

        // Nombre total de messages non lus
        $nonLus = Message::where('destinataire_id', $clientId)
                        ->where('lu', false)
                        ->count();

        // ID de la conversation à ouvrir automatiquement (depuis l'URL)
        $conversationActive = $request->get('conversation');

        return view('client.messagerie', compact('conversations', 'nonLus', 'conversationActive'));
    }


    private function getConversations($userId)
    {
        // Récupérer tous les messages du client
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
                if (!in_array($bienKey, $conversations[$interlocuteurId]['biens'])) {
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

    public function getConversation(Request $request, $interlocuteurId)
    {
        $clientId = Auth::id();

        // Récupérer tous les messages entre le client et cet interlocuteur
        $messages = Message::where(function($query) use ($clientId, $interlocuteurId) {
                $query->where('expediteur_id', $clientId)
                      ->where('destinataire_id', $interlocuteurId);
            })
            ->orWhere(function($query) use ($clientId, $interlocuteurId) {
                $query->where('expediteur_id', $interlocuteurId)
                      ->where('destinataire_id', $clientId);
            })
            ->with(['expediteur', 'destinataire', 'maison', 'appartement'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus
        Message::where('expediteur_id', $interlocuteurId)
              ->where('destinataire_id', $clientId)
              ->where('lu', false)
              ->update(['lu' => true]);

        // Formater les messages pour le JSON
        $formattedMessages = $messages->map(function($message) use ($clientId) {
            $bien = $message->maison ?? $message->appartement;

            return [
                'id' => $message->id,
                'contenu' => $message->contenu,
                'date' => $message->created_at->format('d/m/Y'),
                'heure' => $message->created_at->format('H:i'),
                'est_moi' => $message->expediteur_id == $clientId,
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

    public function supprimerConversation(Request $request, $interlocuteurId)
    {
        $clientId = Auth::id();

        // Supprimer tous les messages entre le client et cet interlocuteur
        Message::where(function($query) use ($clientId, $interlocuteurId) {
                $query->where('expediteur_id', $clientId)
                      ->where('destinataire_id', $interlocuteurId);
            })
            ->orWhere(function($query) use ($clientId, $interlocuteurId) {
                $query->where('expediteur_id', $interlocuteurId)
                      ->where('destinataire_id', $clientId);
            })
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Conversation supprimée'
        ]);
    }

    // ========== FAVORIS ==========
    public function favoris()
    {
        $favoris = collect();
        return view('client.favoris', compact('favoris'));
    }

    public function ajouterFavori(Request $request)
    {
        return back()->with('success', 'Ajouté aux favoris');
    }

    public function supprimerFavori($id)
    {
        return back()->with('success', 'Retiré des favoris');
    }
}
