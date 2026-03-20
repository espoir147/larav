<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Appartement;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AppartementController extends Controller
{
    /**
     * Stocker un nouvel appartement
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'numero_appartement' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'prix_mensuel' => 'required|numeric|min:0',
            'nombre_chambres' => 'required|integer|min:1',
            'salon' => 'required|boolean',
            'type' => 'required|in:simple,sanitaires',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos' => 'required|array|min:1|max:10',
        ]);

        // Note: Le champ 'descriptin' (avec faute de frappe dans le modèle) est optionnel
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Traitement des photos
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('appartements/photos', 'public');
                $photosPaths[] = $path;
            }
        }

        // === MODIFICATION IMPORTANTE : statut_publication = 'en_attente' ===
        // Création de l'appartement
        $appartement = Appartement::create([
            'utilisateur_id' => Auth::id(),
            'numero_appartement' => $request->numero_appartement,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'description' => $request->description ?? '', // Note: faute de frappe 'descriptin' dans le modèle
            'prix_mensuel' => $request->prix_mensuel,
            'nombre_chambres' => $request->nombre_chambres,
            'salon' => $request->salon,
            'type' => $request->type,
            'disponible' => true, // Le propriétaire peut le rendre disponible
            'statut_publication' => 'en_attente', // EN ATTENTE DE VALIDATION ADMIN
            'photos' => implode(',', $photosPaths),
            'latitude' => $request->latitude ?? 6.3723, // Cotonou par défaut
            'longitude' => $request->longitude ?? 2.3647,
        ]);

        return redirect()->route('proprietaire.annonces')
            ->with('success', 'Appartement publié avec succès ! Il est maintenant en attente de validation par l\'administrateur.');
    }

    /**
     * Mettre à jour un appartement existant
     */
    public function update(Request $request, $id)
    {
        $appartement = Appartement::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($appartement->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'numero_appartement' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'prix_mensuel' => 'required|numeric|min:0',
            'nombre_chambres' => 'required|integer|min:1',
            'salon' => 'required|boolean',
            'type' => 'required|in:simple,sanitaires',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos' => 'nullable|array|max:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Préparer les données de mise à jour
        $data = [
            'numero_appartement' => $request->numero_appartement,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'description' => $request->description ?? '', // Note: faute de frappe
            'prix_mensuel' => $request->prix_mensuel,
            'nombre_chambres' => $request->nombre_chambres,
            'salon' => $request->salon,
            'type' => $request->type,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Si le bien était rejeté et que le propriétaire le modifie, le remettre en attente
        if ($appartement->statut_publication === 'rejete') {
            $data['statut_publication'] = 'en_attente';
        }

        // Gestion des nouvelles photos
        if ($request->hasFile('photos')) {
            // Supprimer les anciennes photos du stockage
            $oldPhotos = explode(',', $appartement->photos);
            foreach ($oldPhotos as $oldPhoto) {
                if ($oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }

            // Uploader les nouvelles photos
            $newPhotosPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('appartements/photos', 'public');
                // Ajouter '/storage/' au chemin
                $newPhotosPaths[] = '/storage/' . $path;
            }

            $data['photos'] = implode(',', $newPhotosPaths);
        }

        $appartement->update($data);

        $message = 'Appartement mis à jour avec succès !';
        if ($appartement->statut_publication === 'en_attente') {
            $message .= ' Il est de nouveau en attente de validation.';
        }

        return redirect()->route('proprietaire.annonces')
            ->with('success', $message);
    }

    /**
     * Supprimer un appartement
     */
    public function destroy($id)
    {
        $appartement = Appartement::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($appartement->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce bien.');
        }

        // Vérifier qu'il n'y a pas de locations actives
        $locationsActives = Location::where('type_logement', 'appartement')
            ->where('id_logement', $id)
            ->where('statut', 'acceptee')
            ->where('date_debut', '<=', now())
            ->exists();

        if ($locationsActives) {
            return back()->with('error', 'Impossible de supprimer : cet appartement a des locataires actifs.');
        }

        // Supprimer les photos du stockage
        $photos = explode(',', $appartement->photos);
        foreach ($photos as $photo) {
            if ($photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $appartement->delete();

        return redirect()->route('proprietaire.annonces')
            ->with('success', 'Appartement supprimé avec succès !');
    }

    /**
     * Activer/désactiver un appartement (toggle)
     */
    public function toggle($id)
    {
        $appartement = Appartement::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($appartement->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        $appartement->update([
            'disponible' => !$appartement->disponible
        ]);

        $status = $appartement->disponible ? 'activé' : 'désactivé';

        // Si le bien est approuvé et devient indisponible, informer
        $message = "Appartement {$status} avec succès !";
        if ($appartement->statut_publication === 'approuve' && !$appartement->disponible) {
            $message .= ' Il ne sera plus visible sur le site.';
        }

        return back()->with('success', $message);
    }

    /**
     * Afficher le formulaire d'édition d'un appartement
     */
    public function edit($id)
    {
        $appartement = Appartement::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($appartement->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        return view('proprietaire.annonces-edit-appartement', compact('appartement'));
    }

    /**
     * Afficher les détails d'un appartement (pour modification)
     */
    public function show($id)
    {
        $appartement = Appartement::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($appartement->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce bien.');
        }

        return view('proprietaire.annonces-show-appartement', compact('appartement'));
    }

    /**
     * Obtenir les appartements du propriétaire (pour API/AJAX)
     */
    public function index()
    {
        $appartements = Appartement::where('utilisateur_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Pour une réponse API
        if (request()->expectsJson()) {
            return response()->json($appartements);
        }

        return view('proprietaire.annonces', compact('appartements'));
    }
}
