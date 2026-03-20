<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Afficher formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter la connexion AVEC VÉRIFICATION DU STATUT
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required'
        ]);

        $user = Utilisateur::where('email', $request->email)->first();

        if ($user && Hash::check($request->mot_de_passe, $user->mot_de_passe)) {
            // VÉRIFICATION DU STATUT DE L'UTILISATEUR
            switch ($user->statut) {
                case 'actif':
                    Auth::login($user);
                    $user->update(['last_login_at' => now()]);

                    // Redirection selon le type d'utilisateur
                    switch ($user->type) {
                        case 'admin':
                            return redirect()->route('admin.dashboard');
                        case 'proprietaire':
                            return redirect()->route('proprietaire.dashboard');
                        case 'client':
                        default:
                            return redirect()->route('client.dashboard');
                    }
                    break;

                case 'en_attente':
                    return redirect()->route('compte.en-attente')
                        ->with('message', 'Votre compte est en attente de validation par l\'administrateur.');

                case 'bloque':
                    return redirect()->route('compte.bloque')
                        ->with('message', 'Votre compte a été bloqué. Contactez l\'administrateur pour plus d\'informations.');

                case 'rejete':
                    return redirect()->route('compte.rejete')
                        ->with('message', 'Votre compte a été rejeté.');

                default:
                    return back()->withErrors([
                        'email' => 'Statut du compte non reconnu. Contactez l\'administrateur.'
                    ]);
            }
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.'
        ]);
    }

    // Afficher formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traiter l'inscription AVEC STATUT PAR DÉFAUT
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'required|email|unique:utilisateurs',
            'telephone' => 'required|string|max:10',
            'indicatif_pays' => 'required|string|max:5',
            'date_naissance' => 'required|date',
            'type' => 'required|in:client,proprietaire',
            'mot_de_passe' => 'required|min:8|confirmed',
            'photo_profil' => 'required|image|max:2048'
        ]);

        // Gestion de l'upload de la photo
        $photoPath = $request->file('photo_profil')->store('profils', 'public');

        $user = Utilisateur::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'indicatif_pays' => $request->indicatif_pays,
            'date_naissance' => $request->date_naissance,
            'type' => $request->type,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'photo_profil' => $photoPath,
            'statut' => 'en_attente' // TOUS LES NOUVEAUX COMPTES SONT EN ATTENTE
        ]);

        // NE PAS CONNECTER AUTOMATIQUEMENT - L'UTILISATEUR DOIT ÊTRE VALIDÉ
        // Auth::login($user); // COMMENTÉ CAR LE COMPTE DOIT ÊTRE VALIDÉ

        // Rediriger vers la page d'attente
        return redirect()->route('compte.en-attente')
            ->with('success', 'Votre inscription a été enregistrée ! Votre compte est en attente de validation par l\'administrateur.');
    }

    // Déconnexion
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    // Afficher page "Compte en attente"
    public function showEnAttente()
    {
        return view('auth.en-attente');
    }

    // Afficher page "Compte bloqué"
    public function showBloque()
    {
        return view('auth.bloque');
    }

    // Afficher page "Compte rejeté"
    public function showRejete()
    {
        return view('auth.rejete');
    }
}
