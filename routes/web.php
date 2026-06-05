<?php
// routes/web.php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\Proprietaire\DashboardController;
use App\Http\Controllers\Proprietaire\BienController;
use Illuminate\Support\Facades\Route;

// Authentification
Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
Route::post('/connexion', [AuthController::class, 'login']);
Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
Route::post('/inscription', [AuthController::class, 'register']);
Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');

// Pages de statut de compte
Route::get('/compte/en-attente', [AuthController::class, 'showEnAttente'])->name('compte.en-attente');
Route::get('/compte/bloque', [AuthController::class, 'showBloque'])->name('compte.bloque');
Route::get('/compte/rejete', [AuthController::class, 'showRejete'])->name('compte.rejete');

// Page d'accueil
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::get('/property/{type}/{id}/details', [PropertyController::class, 'getDetails'])->name('property.details');

// Route dashboard unique qui redirige selon le type d'utilisateur
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Redirection selon le type d'utilisateur
    switch ($user->type) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'proprietaire':
            return redirect()->route('proprietaire.dashboard');
        case 'client':
            return redirect()->route('client.dashboard');
        default:
            return redirect()->route('login');
    }
})->name('dashboard')->middleware('auth');

// Routes publiques
Route::get('/maison/{id}', [PropertyController::class, 'showMaison'])->name('maison.show');
Route::get('/appartement/{id}', [PropertyController::class, 'showAppartement'])->name('appartement.show');
Route::get('/recherche', [PropertyController::class, 'search'])->name('property.search');
Route::post('/contact-proprietaire', [PropertyController::class, 'contactProprietaire'])->name('property.contact');

// Pages statiques
Route::view('/guide', 'guide')->name('guide');

// ==================== ROUTES ADMIN ====================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion utilisateurs
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users');
    Route::get('/utilisateurs/{id}/details', [AdminController::class, 'userDetails'])->name('users.details');
    Route::post('/utilisateurs/{id}/valider', [AdminController::class, 'validateUser'])->name('users.validate');
    Route::post('/utilisateurs/{id}/rejeter', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::post('/utilisateurs/{id}/bloquer', [AdminController::class, 'blockUser'])->name('users.block');
    Route::post('/utilisateurs/{id}/debloquer', [AdminController::class, 'unblockUser'])->name('users.unblock');
    Route::delete('/utilisateurs/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    /* Empêcher les actions sur les admins
    Route::post('/utilisateurs/{id}/bloquer', [AdminController::class, 'blockUser'])->middleware('can:block-user,id');*/


    // Transactions
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');

    // Journal
    Route::get('/journal', [AdminController::class, 'logs'])->name('logs');
    Route::get('/journal/export', [AdminController::class, 'exportLogs'])->name('logs.export');

    // Profil admin
    Route::get('/profil', [AdminController::class, 'profile'])->name('profil');
    Route::put('/profil', [AdminController::class, 'updateProfile'])->name('profil.update');
    Route::post('/profil/photo', [AdminController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profil/photo', [AdminController::class, 'deletePhoto'])->name('profile.photo.delete');

    // Gestion biens immobiliers
    Route::get('/biens', [AdminController::class, 'properties'])->name('properties');
    Route::delete('/biens/{type}/{id}', [AdminController::class, 'deleteProperty'])->name('properties.delete');
    Route::get('/properties/{type}/{id}/details', [AdminController::class, 'propertyDetails'])->name('admin.properties.details');

    // Routes pour la gestion des biens (admin)
    Route::prefix('properties')->name('properties.')->group(function () {
        // Page principale
        Route::get('/', [AdminController::class, 'properties'])->name('index');

        // Actions AJAX
        Route::post('/{type}/{id}/approve', [AdminController::class, 'approveProperty'])->name('approve');
        Route::post('/{type}/{id}/reject', [AdminController::class, 'rejectProperty'])->name('reject');
        Route::post('/{type}/{id}/suspend', [AdminController::class, 'suspendProperty'])->name('suspend');
        Route::get('/{type}/{id}/details', [AdminController::class, 'propertyDetails'])->name('details');
        Route::get('/data', [AdminController::class, 'propertiesData'])->name('data');
        Route::post('/bulk-action', [AdminController::class, 'bulkAction'])->name('bulk');
    });
});

// ==================== ROUTES PROPRIÉTAIRE ====================
Route::prefix('proprietaire')->name('proprietaire.')->middleware(['auth', 'proprietaire'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Biens
    Route::get('/biens', [BienController::class, 'index'])->name('biens');
    // Locataires
    Route::get('/locataires', [\App\Http\Controllers\Proprietaire\LocataireController::class, 'index'])->name('locataires');
    Route::post('/location/accepter', [\App\Http\Controllers\Proprietaire\LocataireController::class, 'accepterLocataire'])->name('location.accepter');
    Route::delete('/location/{id}/terminer', [\App\Http\Controllers\Proprietaire\LocataireController::class, 'terminer'])->name('location.terminer');
    // Paiements
    Route::get('/paiements', [\App\Http\Controllers\Proprietaire\PaiementController::class, 'index'])->name('paiements');
    Route::get('/paiements/exporter-pdf', [\App\Http\Controllers\Proprietaire\PaiementController::class, 'exporterPDF'])->name('paiements.exporter.pdf');
    Route::get('/paiements/reçu/{id}', [\App\Http\Controllers\Proprietaire\PaiementController::class, 'exporterReçu'])->name('paiements.reçu');
    // Messagerie
    Route::get('/messagerie', [\App\Http\Controllers\Proprietaire\MessagerieController::class, 'index'])->name('messagerie');
    Route::get('/messagerie/conversation/{interlocuteurId}', [\App\Http\Controllers\Proprietaire\MessagerieController::class, 'getConversation'])->name('messagerie.conversation');
    Route::post('/messages/envoyer', [\App\Http\Controllers\Proprietaire\MessagerieController::class, 'envoyerMessage'])->name('messages.envoyer');
    Route::post('/messages/marquer-lu', [\App\Http\Controllers\Proprietaire\MessagerieController::class, 'marquerCommeLu'])->name('messages.marquer-lu');
    Route::delete('/messagerie/conversation/{interlocuteurId}', [\App\Http\Controllers\Proprietaire\MessagerieController::class, 'supprimerConversation'])->name('messagerie.supprimer');
    // Annonces
    Route::get('/annonces', [\App\Http\Controllers\Proprietaire\AnnonceController::class, 'index'])->name('annonces');
    // Payer loyer
    Route::get('/payer-loyer', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'index'])->name('payer-loyer');
    Route::post('/loyer/initier-paiement', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'initierPaiement'])->name('loyer.initier');
    Route::get('/loyer/location/{id}/details', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'locationDetails'])->name('loyer.location.details');
    Route::get('/loyer/location/{id}/paiements', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'locationPaiements'])->name('loyer.location.paiements');
    Route::get('/loyer/paiement/{id}/details', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'detailsPaiement'])->name('loyer.paiement.details');
    Route::get('/loyer/paiement/{id}/recu', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'telechargerRecu'])->name('loyer.recu');
    // Profil
    Route::get('/profil', [\App\Http\Controllers\Proprietaire\ProfilController::class, 'index'])->name('profil');
    Route::post('/profil', [\App\Http\Controllers\Proprietaire\ProfilController::class, 'update'])->name('profil.update');
    // Routes pour les maisons
    Route::controller(\App\Http\Controllers\Proprietaire\MaisonController::class)->group(function () {
        Route::post('/maison/store', 'store')->name('maison.store');
        Route::put('/maison/{id}/update', 'update')->name('maison.update');
        Route::delete('/maison/{id}', 'destroy')->name('maison.delete');
        Route::put('/maison/{id}/toggle', 'toggle')->name('maison.toggle');
        Route::get('/maison/{id}/edit', 'edit')->name('maison.edit');
        Route::get('/maison/{id}', 'show')->name('maison.show');
    });
    // Routes pour les appartements
    // Dans le groupe 'proprietaire'
    Route::controller(\App\Http\Controllers\Proprietaire\AppartementController::class)->group(function () {
        Route::post('/appartement/store', 'store')->name('appartement.store');
        Route::put('/appartement/{id}/update', 'update')->name('appartement.update');
        Route::delete('/appartement/{id}', 'destroy')->name('appartement.delete');
        Route::put('/appartement/{id}/toggle', 'toggle')->name('appartement.toggle');
        Route::get('/appartement/{id}/edit', 'edit')->name('appartement.edit');
        Route::get('/appartement/{id}', 'show')->name('appartement.show');
    });
});

// ==================== WEBHOOK FEDAPAY PROPRIETAIRE (hors middleware auth) ====================
Route::post('/proprietaire/loyer/webhook', [\App\Http\Controllers\Proprietaire\LoyerController::class, 'webhookFedaPay'])
    ->name('proprietaire.loyer.webhook');


Route::post('/client/paiements/webhook', [ClientController::class, 'webhookFedaPay'])
    ->name('client.paiements.webhook');

// ==================== ROUTES CLIENT ====================
Route::prefix('client')->name('client.')->middleware(['auth', 'client'])->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/profil', [ClientController::class, 'profil'])->name('profil');
    Route::put('/profil', [ClientController::class, 'updateProfil'])->name('profil.update');
    Route::post('/profil/photo', [ClientController::class, 'updatePhoto'])->name('profil.photo.update');
    Route::get('/locations', [ClientController::class, 'locations'])->name('locations');
    Route::get('/paiements', [ClientController::class, 'paiements'])->name('paiements');
    // Messagerie
    Route::get('/messagerie', [ClientController::class, 'messagerie'])->name('messagerie');
    Route::get('/messagerie/conversation/{interlocuteurId}', [ClientController::class, 'getConversation'])->name('messagerie.conversation');
    Route::post('/messages/envoyer', [ClientController::class, 'envoyerMessage'])->name('messages.envoyer');
    Route::post('/messages/marquer-lu', [ClientController::class, 'marquerCommeLu'])->name('messages.marquer-lu');
    Route::delete('/messagerie/conversation/{interlocuteurId}', [ClientController::class, 'supprimerConversation'])->name('messagerie.supprimer');
    // Dans la section Routes CLIENT, ajouter :
    Route::get('/locations/{id}/details', [ClientController::class, 'locationDetails'])->name('client.locations.details');
    Route::get('/locations/{id}/paiements', [ClientController::class, 'locationPaiements'])->name('client.locations.paiements');
    Route::post('/paiements/initier', [ClientController::class, 'initierPaiement'])->name('client.paiements.initier');
    Route::get('/paiements/{id}/details', [ClientController::class, 'detailsPaiement'])->name('paiements.details');
    Route::get('/paiements/{id}/telecharger', [ClientController::class, 'telechargerRecu'])->name('paiements.telecharger');
    Route::get('/favoris', [ClientController::class, 'favoris'])->name('favoris');
});



