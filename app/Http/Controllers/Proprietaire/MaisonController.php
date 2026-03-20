<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Maison;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MaisonController extends Controller
{
    /**
     * Stocker une nouvelle maison
     */
    public function store(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'description' => 'required|string|min:10',
            'prix' => 'required|numeric|min:0',
            'nombre_chambres' => 'required|integer|min:1',
            'salon' => 'required|boolean',
            'type' => 'required|in:simple,sanitaires',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'photos' => 'required|array|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Traitement des photos
        $photosPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('maisons/photos', 'public');
                // Ajouter '/storage/' au chemin
                $photosPaths[] = '/storage/' . $path;
            }
        }

        // === MODIFICATION IMPORTANTE : statut_publication = 'en_attente' ===
        // Création de la maison
        $maison = Maison::create([
            'utilisateur_id' => Auth::id(),
            'nom' => $request->nom,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'description' => $request->description,
            'prix' => $request->prix,
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
            ->with('success', 'Maison publiée avec succès ! Elle est maintenant en attente de validation par l\'administrateur.');
    }

    /**
     * Mettre à jour une maison existante
     */
    public function update(Request $request, $id)
    {
        $maison = Maison::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($maison->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:100',
            'description' => 'required|string|min:10',
            'prix' => 'required|numeric|min:0',
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
        $data = $request->only([
            'nom', 'adresse', 'ville', 'description',
            'prix', 'nombre_chambres', 'salon', 'type',
            'latitude', 'longitude'
        ]);

        // Si le bien était rejeté et que le propriétaire le modifie, le remettre en attente
        if ($maison->statut_publication === 'rejete') {
            $data['statut_publication'] = 'en_attente';
        }

        // Gestion des nouvelles photos
        if ($request->hasFile('photos')) {
            // Supprimer les anciennes photos du stockage
            $oldPhotos = explode(',', $maison->photos);
            foreach ($oldPhotos as $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            // Uploader les nouvelles photos
            $newPhotosPaths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('maisons/photos', 'public');
                $newPhotosPaths[] = $path;
            }

            $data['photos'] = implode(',', $newPhotosPaths);
        }

        $maison->update($data);

        $message = 'Maison mise à jour avec succès !';
        if ($maison->statut_publication === 'en_attente') {
            $message .= ' Elle est de nouveau en attente de validation.';
        }

        return redirect()->route('proprietaire.annonces')
            ->with('success', $message);
    }

    /**
     * Supprimer une maison
     */
    public function destroy($id)
    {
        $maison = Maison::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($maison->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce bien.');
        }

        // Vérifier qu'il n'y a pas de locations actives
        $locationsActives = Location::where('type_logement', 'maison')
            ->where('id_logement', $id)
            ->where('statut', 'acceptee')
            ->where('date_debut', '<=', now())
            ->exists();

        if ($locationsActives) {
            return back()->with('error', 'Impossible de supprimer : cette maison a des locataires actifs.');
        }

        // Supprimer les photos du stockage
        $photos = explode(',', $maison->photos);
        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo);
        }

        $maison->delete();

        return redirect()->route('proprietaire.annonces')
            ->with('success', 'Maison supprimée avec succès !');
    }

    /**
     * Activer/désactiver une maison (toggle)
     */
    public function toggle($id)
    {
        $maison = Maison::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($maison->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        $maison->update([
            'disponible' => !$maison->disponible
        ]);

        $status = $maison->disponible ? 'activée' : 'désactivée';

        // Si le bien est approuvé et devient indisponible, informer
        $message = "Maison {$status} avec succès !";
        if ($maison->statut_publication === 'approuve' && !$maison->disponible) {
            $message .= ' Elle ne sera plus visible sur le site.';
        }

        return back()->with('success', $message);
    }

    /**
     * Afficher le formulaire d'édition d'une maison
     */
    public function edit($id)
    {
        $maison = Maison::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($maison->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce bien.');
        }

        return view('proprietaire.annonces-edit-maison', compact('maison'));
    }

    /**
     * Afficher les détails d'une maison (pour modification)
     */
    public function show($id)
    {
        $maison = Maison::findOrFail($id);

        // Vérifier que l'utilisateur est le propriétaire
        if ($maison->utilisateur_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce bien.');
        }

        return view('proprietaire.annonces-show-maison', compact('maison'));
    }
}
