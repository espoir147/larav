<?php

namespace App\Http\Controllers;

use App\Models\Maison;
use App\Models\Appartement;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    // Page d'accueil avec maisons et appartements
    public function index()
    {
        // === MODIFICATION : Filtrer uniquement les biens approuvés et disponibles ===
        $maisons = Maison::with('proprietaire')
            ->where('statut_publication', 'approuve') // UNIQUEMENT LES APPROUVÉS
            ->where('disponible', true)               // UNIQUEMENT LES DISPONIBLES
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $appartements = Appartement::with('proprietaire')
            ->where('statut_publication', 'approuve') // UNIQUEMENT LES APPROUVÉS
            ->where('disponible', true)               // UNIQUEMENT LES DISPONIBLES
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('home', compact('maisons', 'appartements'));
    }

    // Détails d'une maison
    public function showMaison($id)
    {
        $maison = Maison::with('proprietaire')->findOrFail($id);

        // Vérifier que la maison est approuvée et disponible
        if ($maison->statut_publication !== 'approuve' || !$maison->disponible) {
            abort(404, 'Ce bien n\'est pas disponible ou n\'a pas été approuvé.');
        }

        return view('properties.maison-show', compact('maison'));
    }

    // Détails d'un appartement
    public function showAppartement($id)
    {
        $appartement = Appartement::with('proprietaire')->findOrFail($id);

        // Vérifier que l'appartement est approuvé et disponible
        if ($appartement->statut_publication !== 'approuve' || !$appartement->disponible) {
            abort(404, 'Ce bien n\'est pas disponible ou n\'a pas été approuvé.');
        }

        return view('properties.appartement-show', compact('appartement'));
    }


    // PropertyController.php - Ajoute cette méthode

    // Dans PropertyController.php, modifiez la méthode getDetails
    public function getDetails($type, $id)
    {
        try {
            if ($type === 'maison') {
                $property = Maison::with('proprietaire')->findOrFail($id);
            } elseif ($type === 'appartement') {
                $property = Appartement::with('proprietaire')->findOrFail($id);
            } else {
                return response()->json(['error' => 'Type de bien invalide'], 400);
            }

            // Vérifier que le bien est approuvé et disponible
            if ($property->statut_publication !== 'approuve' || !$property->disponible) {
                return response()->json(['error' => 'Ce bien n\'est pas disponible'], 404);
            }

            // CORRECTION : Assurez-vous que les chemins des photos sont corrects
            // Dans la méthode getDetails, remplacez la gestion des photos par :
            $photos = [];
            if (!empty($property->photos)) {
                $photosArray = [];

                // Si c'est une chaîne (stockée comme "photo1.jpg,photo2.jpg")
                if (is_string($property->photos)) {
                    // Vérifier si c'est du JSON
                    if (strpos($property->photos, '[') === 0) {
                        $photosArray = json_decode($property->photos, true); 
                    } else {
                        // Sinon c'est une chaîne séparée par des virgules
                        $photosArray = explode(',', $property->photos);
                    }
                } elseif (is_array($property->photos)) {
                    $photosArray = $property->photos;
                }

                // Nettoyer et corriger chaque chemin
                foreach ($photosArray as $photo) {
                    $trimmedPhoto = trim($photo);
                    if (!empty($trimmedPhoto)) {
                        $photos[] = $this->fixPhotoPath($trimmedPhoto);
                    }
                }
            }

            // Si aucune photo n'a été trouvée, ajouter une image par défaut
            if (empty($photos)) {
                $photos = ['/images/default-property.jpg'];
            }

            // Préparer les données
            $data = [
                'success' => true,
                'property' => [
                    'id' => $property->id,
                    'type' => $type,
                    'nom' => $type === 'maison' ? $property->nom : 'Appartement ' . $property->numero_appartement,
                    'adresse' => $property->adresse,
                    'ville' => $property->ville,
                    'description' => $property->description,
                    'nombre_chambres' => $property->nombre_chambres,
                    'salon' => $property->salon,
                    'type_logement' => $property->type,
                    'prix' => $type === 'maison' ? $property->prix : $property->prix_mensuel,
                    'photos' => $photos, // Utiliser les photos corrigées
                    'disponible' => $property->disponible,
                    'statut_publication' => $property->statut_publication
                ],
                'owner' => $property->proprietaire ? [
                    'id' => $property->proprietaire->id,
                    'nom' => $property->proprietaire->nom,
                    'prenom' => $property->proprietaire->prenom,
                    'email' => $property->proprietaire->email,
                    'telephone' => $property->proprietaire->telephone,
                    'whatsapp_link' => $property->proprietaire->whatsapp_link
                ] : null
            ];

            return response()->json($data);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Bien non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        }
    }

    // Dans PropertyController.php, modifiez la méthode fixPhotoPath
        private function fixPhotoPath($photo)
    {
        $photo = trim($photo);

        if (empty($photo)) {
            return '/images/default-property.jpg';
        }

        // Si c'est déjà un chemin HTTP complet
        if (strpos($photo, 'http') === 0) {
            return $photo;
        }

        // Si le chemin ne commence pas par /storage/ ou /images/, l'ajouter
        if (strpos($photo, '/storage/') !== 0 && strpos($photo, '/images/') !== 0) {
            return '/storage/' . ltrim($photo, '/');
        }

        return $photo;
    }

    // Recherche de propriétés
    public function search(Request $request)
    {
        // === MODIFICATION : Filtrer uniquement les biens approuvés ===
        $query = Maison::query()
            ->where('statut_publication', 'approuve') // UNIQUEMENT LES APPROUVÉS
            ->where('disponible', true);

        if ($request->ville) {
            $query->where('ville', 'like', '%' . $request->ville . '%');
        }

        if ($request->prix_min) {
            $query->where('prix', '>=', $request->prix_min);
        }

        if ($request->prix_max) {
            $query->where('prix', '<=', $request->prix_max);
        }

        if ($request->chambres) {
            $query->where('nombre_chambres', '>=', $request->chambres);
        }

        $maisons = $query->orderBy('id', 'desc')->get();

        // Même chose pour les appartements si besoin
        $queryApparts = Appartement::query()
            ->where('statut_publication', 'approuve')
            ->where('disponible', true);

        if ($request->ville) {
            $queryApparts->where('ville', 'like', '%' . $request->ville . '%');
        }

        $appartements = $queryApparts->orderBy('id', 'desc')->get();

        return view('properties.search', compact('maisons', 'appartements'));
    }

    // Contacter un propriétaire (reste identique)
    public function contactProprietaire(Request $request)
    {
        $request->validate([
            'proprietaire_id' => 'required|exists:utilisateurs,id',
            'logement_id' => 'required',
            'type_logement' => 'required|in:maison,appartement',
            'message' => 'required|string'
        ]);

        // Vérifier que le bien est approuvé
        if ($request->type_logement === 'maison') {
            $bien = Maison::find($request->logement_id);
        } else {
            $bien = Appartement::find($request->logement_id);
        }

        if (!$bien || $bien->statut_publication !== 'approuve' || !$bien->disponible) {
            return response()->json([
                'success' => false,
                'error' => 'Ce bien n\'est plus disponible ou n\'a pas été approuvé.'
            ], 400);
        }

        // Créer le message
        DB::table('messages')->insert([
            'expediteur_id' => auth()->id(),
            'destinataire_id' => $request->proprietaire_id,
            'logement_type' => $request->type_logement,
            'logement_id' => $request->logement_id,
            'contenu' => $request->message,
            'date_envoi' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Message envoyé avec succès'
        ]);
    }

    // Générer lien WhatsApp (reste identique)
    public function generateWhatsAppLink($telephone, $propertyName)
    {
        $cleanedNumber = preg_replace('/[^0-9]/', '', $telephone);
        if (empty($cleanedNumber)) return '#';

        $last8Digits = substr($cleanedNumber, -8);
        $message = urlencode("Bonjour, je suis intéressé par votre logement : " . $propertyName);

        return 'https://wa.me/229' . $last8Digits . '?text=' . $message;
    }
}
