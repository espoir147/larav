<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        $proprietaire = Auth::user();
        return view('proprietaire.profil', compact('proprietaire'));
    }

    public function update(Request $request)
    {
        $proprietaire = Auth::user();

        // Règles de validation de base
        $rules = [
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs,email,'.$proprietaire->id,
            'telephone' => 'required|string|max:10',
            'indicatif_pays' => 'required|string|max:5',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Ajouter les règles de mot de passe seulement si nouveau mot de passe fourni
        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['new_password'] = 'required|min:6|confirmed';
        }

        $request->validate($rules);

        $data = $request->only('nom', 'email', 'telephone', 'indicatif_pays');

        // Gestion de l'upload de photo
        if ($request->hasFile('photo_profil')) {
            // Supprimer l'ancienne photo si elle existe
            if ($proprietaire->photo_profil) {
                Storage::delete($proprietaire->photo_profil);
            }

            $path = $request->file('photo_profil')->store('profiles', 'public');
            $data['photo_profil'] = $path;
        }

        // Gestion du mot de passe (seulement si fourni)
        if ($request->filled('new_password') && $request->filled('current_password')) {
            $data['password'] = Hash::make($request->new_password);
        }

        $proprietaire->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Profil mis à jour avec succès');
    }
}
