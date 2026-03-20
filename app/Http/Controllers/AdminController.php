<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Paiement;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Tableau de bord admin
    public function dashboard()
    {
        $stats = [
            'totalUsers' => Utilisateur::count(),
            'activeUsers' => Utilisateur::where('statut', 'actif')->count(),
            'pendingUsers' => Utilisateur::where('statut', 'en_attente')->count(),
            'blockedUsers' => Utilisateur::where('statut', 'bloque')->count(),

            'totalProperties' => Maison::count() + Appartement::count(),
            'maisonsCount' => Maison::count(),
            'appartementsCount' => Appartement::count(),
            'availableProperties' => Maison::where('disponible', true)->count() +
                                   Appartement::where('disponible', true)->count(),

            'totalRevenue' => Paiement::sum('montant'),
            'monthRevenue' => Paiement::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->sum('montant'),
            'todayRevenue' => Paiement::whereDate('created_at', today())->sum('montant'),

            'totalTransactions' => Paiement::count(),
            'monthTransactions' => Paiement::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)
                                          ->count(),
            'todayTransactions' => Paiement::whereDate('created_at', today())->count(),
        ];

        $recentData = [
            'recentUsers' => Utilisateur::orderBy('created_at', 'desc')
                                       ->limit(5)
                                       ->get(),
            'recentProperties' => Maison::select('id', 'nom', 'prix', 'ville', 'type', 'disponible', 'created_at')
                                      ->orderBy('created_at', 'desc')
                                      ->limit(5)
                                      ->get(),
            'recentTransactions' => Paiement::with(['utilisateur', 'maison', 'appartement'])
                                          ->orderBy('created_at', 'desc')
                                          ->limit(5)
                                          ->get(),
        ];

        return view('admin.dashboard', array_merge($stats, $recentData));
    }

    // Gestion des utilisateurs
    public function users(Request $request)
    {
        $query = Utilisateur::query();
        $query->where('type', '!=', 'admin')->where('id', '!=', auth()->id());

        // Filtres
        if ($request->has('filter')) {
            $query->where('statut', $request->filter);
        }

        if ($request->has('search')) {
            $query->where('nom', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users', compact('users'));
    }

    // Détails d'un utilisateur (pour API)
    public function userDetails($id)
    {
        $user = Utilisateur::withCount(['maisons', 'appartements'])
            ->findOrFail($id);

        return response()->json(
            $user->makeHidden(['mot_de_passe', 'remember_token'])
        );
    }

    // Valider un utilisateur
    public function validateUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->update(['statut' => 'actif']);

        return response()->json(['success' => true, 'message' => 'Utilisateur validé avec succès']);
    }

    // Rejeter un utilisateur
    public function rejectUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->update(['statut' => 'rejete']);

        return response()->json(['success' => true, 'message' => 'Utilisateur rejeté avec succès']);
    }

    // Bloquer un utilisateur
    public function blockUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        /* Vérifier qu'on ne bloque pas un admin
        if ($user->type === 'admin') {
        return back()->withErrors(['message' => 'Impossible de bloquer un administrateur']);
        }*/
        $user->update(['statut' => 'bloque']);

        return response()->json(['success' => true, 'message' => 'Utilisateur bloqué avec succès']);
    }

    // Débloquer un utilisateur
    public function unblockUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->update(['statut' => 'actif']);

        return response()->json(['success' => true, 'message' => 'Utilisateur débloqué avec succès']);
    }

    // Supprimer un utilisateur
    public function deleteUser($id)
    {
        $user = Utilisateur::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès']);
    }

    // Gestion des biens immobiliers
    public function properties(Request $request)
    {
        $queryMaisons = Maison::with('proprietaire');
        $queryAppartements = Appartement::with('proprietaire');

        // Récupérer les villes uniques
        $villesMaisons = Maison::distinct()->pluck('ville')->toArray();
        $villesAppartements = Appartement::distinct()->pluck('ville')->toArray();
        $villes = array_unique(array_merge($villesMaisons, $villesAppartements));
        sort($villes);

        // Appliquer les filtres
        if ($request->has('type') && $request->type) {
            if ($request->type === 'maison') {
                $queryAppartements = null;
            } else {
                $queryMaisons = null;
            }
        }

        if ($request->has('ville') && $request->ville) {
            if ($queryMaisons) {
                $queryMaisons->where('ville', $request->ville);
            }
            if ($queryAppartements) {
                $queryAppartements->where('ville', $request->ville);
            }
        }

        if ($request->has('statut') && $request->statut) {
            if ($queryMaisons) {
                $queryMaisons->where('statut_publication', $request->statut);
            }
            if ($queryAppartements) {
                $queryAppartements->where('statut_publication', $request->statut);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            if ($queryMaisons) {
                $queryMaisons->where(function($q) use ($search) {
                    $q->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('adresse', 'like', '%'.$search.'%')
                    ->orWhereHas('proprietaire', function($q) use ($search) {
                        $q->where('nom', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
                });
            }
            if ($queryAppartements) {
                $queryAppartements->where(function($q) use ($search) {
                    $q->where('numero_appartement', 'like', '%'.$search.'%')
                    ->orWhere('adresse', 'like', '%'.$search.'%')
                    ->orWhereHas('proprietaire', function($q) use ($search) {
                        $q->where('nom', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
                });
            }
        }

        // Pagination
        $maisons = $queryMaisons ? $queryMaisons->orderBy('created_at', 'desc')->paginate(15, ['*'], 'maisons_page') : collect();
        $appartements = $queryAppartements ? $queryAppartements->orderBy('created_at', 'desc')->paginate(15, ['*'], 'appartements_page') : collect();

        // Statistiques CORRIGÉES - Utiliser les mêmes filtres que la requête
        $stats = [
            'total' => Maison::count() + Appartement::count(),
            'en_attente' => Maison::where('statut_publication', 'en_attente')->count() +
                        Appartement::where('statut_publication', 'en_attente')->count(),
            'approuves' => Maison::where('statut_publication', 'approuve')->count() +
                        Appartement::where('statut_publication', 'approuve')->count(),
            'rejetes' => Maison::where('statut_publication', 'rejete')->count() +
                        Appartement::where('statut_publication', 'rejete')->count(),
        ];

        return view('admin.properties', compact('maisons', 'appartements', 'villes', 'stats'));
    }

    // Données AJAX pour la table - VERSION CORRIGÉE
    public function propertiesData(Request $request)
    {
        $type = $request->get('type', '');
        $ville = $request->get('ville', '');
        $statut = $request->get('statut', '');
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = 15;

        // Construire les requêtes de base
        $queryMaisons = Maison::with('proprietaire');
        $queryAppartements = Appartement::with('proprietaire');

        // IMPORTANT : Appliquer TOUS les filtres, pas seulement par onglet
        if ($type) {
            if ($type === 'maison') {
                $queryAppartements = null;
            } else {
                $queryMaisons = null;
            }
        }

        if ($ville) {
            if ($queryMaisons) {
                $queryMaisons->where('ville', $ville);
            }
            if ($queryAppartements) {
                $queryAppartements->where('ville', $ville);
            }
        }

        // FILTRAGE PAR STATUT - C'est ici que ça pourrait bugger
        if ($statut) {
            if ($queryMaisons) {
                $queryMaisons->where('statut_publication', $statut);
            }
            if ($queryAppartements) {
                $queryAppartements->where('statut_publication', $statut);
            }
        }

        if ($search) {
            $searchTerm = '%' . $search . '%';
            if ($queryMaisons) {
                $queryMaisons->where(function($q) use ($searchTerm) {
                    $q->where('nom', 'like', $searchTerm)
                      ->orWhere('adresse', 'like', $searchTerm)
                      ->orWhereHas('proprietaire', function($q) use ($searchTerm) {
                          $q->where('nom', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                      });
                });
            }
            if ($queryAppartements) {
                $queryAppartements->where(function($q) use ($searchTerm) {
                    $q->where('numero_appartement', 'like', $searchTerm)
                      ->orWhere('adresse', 'like', $searchTerm)
                      ->orWhereHas('proprietaire', function($q) use ($searchTerm) {
                          $q->where('nom', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                      });
                });
            }
        }

        // Pagination séparée mais avec fusion intelligente
        $maisonsData = $queryMaisons ? $queryMaisons->paginate($perPage, ['*'], 'page', $page) : null;
        $appartementsData = $queryAppartements ? $queryAppartements->paginate($perPage, ['*'], 'page', $page) : null;

        // Fusionner les résultats
        $properties = collect();

        if ($maisonsData) {
            foreach ($maisonsData->items() as $maison) {
                $maison->type = 'maison';
                // Ajouter la première photo pour l'affichage
                $maison->first_photo = $maison->photos ? explode(',', $maison->photos)[0] : null;
                $maison->photos_array = $maison->photos ? explode(',', $maison->photos) : [];
                $properties->push($maison);
            }
        }

        if ($appartementsData) {
            foreach ($appartementsData->items() as $appartement) {
                $appartement->type = 'appartement';
                $appartement->first_photo = $appartement->photos ? explode(',', $appartement->photos)[0] : null;
                $appartement->photos_array = $appartement->photos ? explode(',', $appartement->photos) : [];
                $properties->push($appartement);
            }
        }

        // Calculer la pagination combinée
        $totalMaisons = $maisonsData ? $maisonsData->total() : 0;
        $totalAppartements = $appartementsData ? $appartementsData->total() : 0;
        $totalCombined = $totalMaisons + $totalAppartements;

        $lastPageMaisons = $maisonsData ? $maisonsData->lastPage() : 1;
        $lastPageAppartements = $appartementsData ? $appartementsData->lastPage() : 1;
        $lastPageCombined = max($lastPageMaisons, $lastPageAppartements);

        // Statistiques en temps réel (avec les mêmes filtres)
        $statsQueryMaisons = Maison::query();
        $statsQueryAppartements = Appartement::query();

        // Appliquer les mêmes filtres pour les stats
        if ($type) {
            if ($type === 'maison') {
                $statsQueryAppartements = null;
            } else {
                $statsQueryMaisons = null;
            }
        }

        if ($ville) {
            if ($statsQueryMaisons) $statsQueryMaisons->where('ville', $ville);
            if ($statsQueryAppartements) $statsQueryAppartements->where('ville', $ville);
        }

        if ($search) {
            $searchTerm = '%' . $search . '%';
            if ($statsQueryMaisons) {
                $statsQueryMaisons->where(function($q) use ($searchTerm) {
                    $q->where('nom', 'like', $searchTerm)
                      ->orWhere('adresse', 'like', $searchTerm)
                      ->orWhereHas('proprietaire', function($q) use ($searchTerm) {
                          $q->where('nom', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                      });
                });
            }
            if ($statsQueryAppartements) {
                $statsQueryAppartements->where(function($q) use ($searchTerm) {
                    $q->where('numero_appartement', 'like', $searchTerm)
                      ->orWhere('adresse', 'like', $searchTerm)
                      ->orWhereHas('proprietaire', function($q) use ($searchTerm) {
                          $q->where('nom', 'like', $searchTerm)
                            ->orWhere('email', 'like', $searchTerm);
                      });
                });
            }
        }

        // Calculer les stats filtrées
        $enAttenteCount = 0;
        $approuvesCount = 0;
        $rejetesCount = 0;
        $totalCount = 0;

        if ($statsQueryMaisons) {
            $enAttenteCount += $statsQueryMaisons->where('statut_publication', 'en_attente')->count();
            $approuvesCount += $statsQueryMaisons->where('statut_publication', 'approuve')->count();
            $rejetesCount += $statsQueryMaisons->where('statut_publication', 'rejete')->count();
            $totalCount += $statsQueryMaisons->count();
        }

        if ($statsQueryAppartements) {
            $enAttenteCount += $statsQueryAppartements->where('statut_publication', 'en_attente')->count();
            $approuvesCount += $statsQueryAppartements->where('statut_publication', 'approuve')->count();
            $rejetesCount += $statsQueryAppartements->where('statut_publication', 'rejete')->count();
            $totalCount += $statsQueryAppartements->count();
        }

        $stats = [
            'en_attente' => $enAttenteCount,
            'approuves' => $approuvesCount,
            'rejetes' => $rejetesCount,
            'total' => $totalCount
        ];

        return response()->json([
            'properties' => $properties,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => $perPage,
                'total' => $totalCombined,
                'last_page' => $lastPageCombined
            ],
            'stats' => $stats,
            'filters' => [
                'type' => $type,
                'ville' => $ville,
                'statut' => $statut,
                'search' => $search
            ]
        ]);
    }

    // Approuver un bien
    public function approveProperty($type, $id)
    {
        try {
            if ($type === 'maison') {
                $property = Maison::findOrFail($id);
            } else {
                $property = Appartement::findOrFail($id);
            }

            $oldStatus = $property->statut_publication;
            $property->update(['statut_publication' => 'approuve']);

            return response()->json([
                'success' => true,
                'message' => 'Bien approuvé avec succès',
                'old_status' => $oldStatus,
                'new_status' => 'approuve'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    // Rejeter un bien
    public function rejectProperty($type, $id)
    {
        try {
            if ($type === 'maison') {
                $property = Maison::findOrFail($id);
            } else {
                $property = Appartement::findOrFail($id);
            }

            $oldStatus = $property->statut_publication;
            $property->update(['statut_publication' => 'rejete']);

            return response()->json([
                'success' => true,
                'message' => 'Bien rejeté avec succès',
                'old_status' => $oldStatus,
                'new_status' => 'rejete'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    // Suspendre un bien
    public function suspendProperty($type, $id)
    {
        try {
            if ($type === 'maison') {
                $property = Maison::findOrFail($id);
            } else {
                $property = Appartement::findOrFail($id);
            }

            $oldStatus = $property->statut_publication;
            $property->update(['statut_publication' => 'en_attente']);

            return response()->json([
                'success' => true,
                'message' => 'Bien suspendu avec succès',
                'old_status' => $oldStatus,
                'new_status' => 'en_attente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

    // Détails d'un bien
    public function propertyDetails($type, $id)
    {
        try {
            if ($type === 'maison') {
                $property = Maison::with('proprietaire')->findOrFail($id);
            } else {
                $property = Appartement::with('proprietaire')->findOrFail($id);
            }

            // Ajouter les photos en tableau
            $property->photos_array = $property->photos ? explode(',', $property->photos) : [];

            return response()->json([
                'property' => $property,
                'owner' => $property->proprietaire
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Bien non trouvé ou erreur de chargement'
            ], 404);
        }
    }

    // Supprimer un bien
    public function deleteProperty($type, $id)
    {
        if ($type === 'maison') {
            $property = Maison::findOrFail($id);
        } else {
            $property = Appartement::findOrFail($id);
        }

        $property->delete();

        return response()->json(['success' => true, 'message' => 'Bien supprimé avec succès']);
    }

    // Action en masse - NOUVELLE MÉTHODE
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'action' => 'required|in:approve,reject,suspend'
        ]);

        $processed = 0;
        $errors = [];

        foreach ($request->ids as $id) {
            try {
                // Détecter le type (maison ou appartement)
                $property = Maison::find($id);
                $type = 'maison';

                if (!$property) {
                    $property = Appartement::find($id);
                    $type = 'appartement';
                }

                if (!$property) {
                    $errors[] = "Bien ID {$id} non trouvé";
                    continue;
                }

                // Appliquer l'action
                switch ($request->action) {
                    case 'approve':
                        $property->update(['statut_publication' => 'approuve']);
                        break;
                    case 'reject':
                        $property->update(['statut_publication' => 'rejete']);
                        break;
                    case 'suspend':
                        $property->update(['statut_publication' => 'en_attente']);
                        break;
                }

                $processed++;

            } catch (\Exception $e) {
                $errors[] = "Erreur sur bien ID {$id}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Action effectuée sur {$processed} bien(s)",
            'processed' => $processed,
            'errors' => $errors
        ]);
    }

    // Transactions
    public function transactions()
    {
        $transactions = Paiement::with(['utilisateur', 'maison', 'appartement', 'operateur'])
                               ->orderBy('created_at', 'desc')
                               ->paginate(20);

        return view('admin.transactions', compact('transactions'));
    }

    /* Afficher le journal d'activité
     */
    public function logs(Request $request)
    {
        $query = Log::query();

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        // Charger les relations
        $query->with('user');

        // Statistiques
        $stats = [
            'total' => Log::count(),
            'aujourd_hui' => Log::whereDate('created_at', today())->count(),
            'connexions' => Log::where('type', 'connexion')->count(),
            'actions' => Log::where('type', 'action')->count(),
        ];

        // Types d'actions pour le filtre
        $types = Log::select('type')->distinct()->pluck('type');

        // Utilisateurs pour le filtre
        $users = Utilisateur::whereHas('logs')->orderBy('nom')->get(['id', 'nom']);

        $logs = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.logs', compact('logs', 'stats', 'types', 'users'));
    }

    /**
     * Exporter les logs
     */
    public function exportLogs(Request $request)
    {
        $query = Log::query()->with('user');

        // Appliquer les mêmes filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [$request->date_debut, $request->date_fin]);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        $filename = 'logs_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Date',
                'Heure',
                'Type',
                'Utilisateur',
                'Description',
                'IP',
                'User Agent'
            ]);

            // Données
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('d/m/Y'),
                    $log->created_at->format('H:i:s'),
                    $log->type,
                    $log->user->nom ?? 'Système',
                    $log->description,
                    $log->ip_address,
                    $log->user_agent
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Nettoyer les vieux logs
     */
    public function cleanLogs(Request $request)
    {
        $request->validate([
            'before' => 'required|date'
        ]);

        $count = Log::whereDate('created_at', '<', $request->before)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$count} logs supprimés"
        ]);
    }


    // Profil admin
    public function profile()
    {
        return view('admin.profil');
    }

    // Mettre à jour le profil admin
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email,'.$admin->id,
            'telephone' => 'required|string|max:10',
            'indicatif_pays' => 'required|string|max:5',
            'current_password' => 'sometimes|required_with:new_password',
            'new_password' => 'sometimes|min:8|confirmed',
        ]);

        // Vérifier le mot de passe actuel si changement demandé
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->mot_de_passe)) {
                return back()->withErrors([
                    'current_password' => 'Le mot de passe actuel est incorrect.'
                ]);
            }

            // Mettre à jour le mot de passe
            $admin->mot_de_passe = Hash::make($request->new_password);
        }

        // Mettre à jour les autres informations
        $admin->update([
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'indicatif_pays' => $request->indicatif_pays,
        ]);

        return back()->with('success', 'Profil mis à jour avec succès');
    }

    // Gestion de la photo de profil
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $admin = Auth::user();

        // Supprimer l'ancienne photo si ce n'est pas la photo par défaut
        if ($admin->photo_profil && !str_contains($admin->photo_profil, 'default-avatar')) {
            Storage::disk('public')->delete($admin->photo_profil);
        }

        // Sauvegarder la nouvelle photo
        $photoPath = $request->file('photo')->store('profils', 'public');

        $admin->update(['photo_profil' => $photoPath]);

        return response()->json([
            'success' => true,
            'new_photo_url' => asset('storage/'.$photoPath)
        ]);
    }

    // Supprimer la photo de profil
    public function deletePhoto()
    {
        $admin = Auth::user();

        if ($admin->photo_profil && !str_contains($admin->photo_profil, 'default-avatar')) {
            Storage::disk('public')->delete($admin->photo_profil);
        }

        $admin->update(['photo_profil' => null]);

        return response()->json(['success' => true]);
    }
}
