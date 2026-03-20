<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Paiement;
use App\Models\Maison;
use App\Models\Appartement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LoyerController extends Controller
{
    /**
     * Afficher la liste des loyers à payer
     */
    public function index(Request $request)
    {
        $proprietaireId = Auth::id();

        // Récupérer toutes les locations où le propriétaire est locataire
        $locations = Location::where('utilisateur_id', $proprietaireId)
                            ->where('statut', 'acceptee')
                            ->with(['maison', 'appartement'])
                            ->orderBy('date_debut', 'desc')
                            ->get();

        // Statistiques
        $stats = [
            'locations_actives' => $locations->count(),
            'total_mensuel' => 0,
            'prochain_paiement' => null,
            'prochain_montant' => 0
        ];

        foreach ($locations as $location) {
            if ($location->maison) {
                $stats['total_mensuel'] += $location->maison->prix ?? 0;
            } elseif ($location->appartement) {
                $stats['total_mensuel'] += $location->appartement->prix_mensuel ?? 0;
            }
        }

        // Récupérer le dernier paiement
        $dernierPaiement = Paiement::where('utilisateur_id', $proprietaireId)
                                  ->orderBy('date_paiement', 'desc')
                                  ->first();

        if ($dernierPaiement) {
            $stats['prochain_paiement'] = $dernierPaiement->date_paiement->addMonth()->format('d/m/Y');

            $bien = $dernierPaiement->maison ?? $dernierPaiement->appartement;
            $stats['prochain_montant'] = $bien->prix ?? $bien->prix_mensuel ?? 0;
        }

        // Historique des paiements confirmés uniquement
        $paiements = Paiement::where('utilisateur_id', $proprietaireId)
                            ->where('statut', 'payé')
                            ->with(['maison', 'appartement', 'operateur'])
                            ->orderBy('date_paiement', 'desc')
                            ->paginate(10);

        return view('proprietaire.payer-loyer', compact('locations', 'stats', 'paiements'));
    }

    /**
     * Initier un paiement de loyer via FedaPay
     */
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

        $location = Location::where('utilisateur_id', Auth::id())
                           ->where('id', $request->location_id)
                           ->firstOrFail();

        $bien = $location->maison ?? $location->appartement;

        // Vérifier que le montant correspond
        $montantAttendu = $bien->prix ?? $bien->prix_mensuel ?? 0;
        if ($request->montant != $montantAttendu) {
            return response()->json([
                'success' => false,
                'message' => 'Le montant ne correspond pas au loyer mensuel'
            ], 400);
        }

        $proprietaire = Auth::user();
        $telephone = $request->telephone ?? $proprietaire->telephone;

        // Configurer FedaPay
        \FedaPay\FedaPay::setApiKey(config('fedapay.secret_key'));
        \FedaPay\FedaPay::setEnvironment(config('fedapay.environment', 'live'));

        try {
            // Créer la transaction FedaPay
            $transaction = \FedaPay\Transaction::create([
                'description' => 'Paiement loyer - ' . ($bien->nom ?? 'Bien #' . $bien->id),
                'amount'      => (int) $request->montant,
                'currency'    => ['iso' => 'XOF'],
                'callback'    => route('proprietaire.loyer.webhook'),
                'customer'    => [
                    'firstname'    => $proprietaire->nom,
                    'lastname'     => '',
                    'email'        => $proprietaire->email,
                    'phone_number' => $telephone,
                ],
            ]);

            // Envoyer le SMS pour mobile money
            if ($request->payment_method === 'mobile_money') {
                $transaction->sendNow([
                    'phone_number' => $telephone,
                ]);
            }

            // Sauvegarder en DB — statut en_attente jusqu'à confirmation webhook
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
            \Illuminate\Support\Facades\Log::error('FedaPay LoyerController error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'initiation du paiement : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook FedaPay — confirmation paiement propriétaire-locataire
     * Route : POST /proprietaire/loyer/webhook (exclue du CSRF)
     */
    public function webhookFedaPay(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('X-FEDAPAY-SIGNATURE', '');
        $secret    = config('fedapay.webhook_secret');

        if ($secret && $signature) {
            $expected = hash_hmac('sha256', $payload, $secret);
            if (!hash_equals($expected, $signature)) {
                \Illuminate\Support\Facades\Log::warning('FedaPay webhook proprietaire: signature invalide');
                return response()->json(['error' => 'Signature invalide'], 401);
            }
        }

        $data          = $request->all();
        $transactionId = $data['data']['object']['id'] ?? null;
        $statut        = $data['data']['object']['status'] ?? null;

        \Illuminate\Support\Facades\Log::info('FedaPay webhook proprietaire', [
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

        if (in_array($statut, ['approved', 'transferred'])) {
            $paiement->update([
                'statut'        => 'payé',
                'date_paiement' => now(),
            ]);
        } elseif (in_array($statut, ['declined', 'canceled'])) {
            $paiement->update(['statut' => 'echoue']);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Obtenir les détails d'un paiement
     */
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

    /**
     * Télécharger le reçu d'un paiement
     */
    public function telechargerRecu($id)
    {
        $paiement = Paiement::where('utilisateur_id', Auth::id())
                           ->with(['maison.proprietaire', 'appartement.proprietaire', 'operateur', 'utilisateur'])
                           ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.recu-paiement', compact('paiement'));

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

        return $pdf->download('recu-paiement-' . $paiement->reference_transaction . '.pdf');
    }

    /**
     * Obtenir l'ID de l'opérateur
     */
    private function getOperateurId($code)
    {
        return DB::table('operateurs')->where('code', $code)->value('id') ?? 1;
    }

    /**
     * Obtenir les détails d'une location
     */
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

    /**
     * Obtenir l'historique des paiements d'une location
     */
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
}
