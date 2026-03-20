@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-profile-page">
    <!-- En-tête -->
    <div class="profile-header">
        <h1>
            <span class="header-icon">👤</span>
            Mon Profil
        </h1>
        <p>Gérez vos informations personnelles et paramètres de compte</p>
    </div>

    <!-- Messages d'alerte -->
    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <span class="alert-icon">❌</span>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <div class="profile-container">
        <!-- Colonne Gauche - Photo -->
        <div class="profile-photo-column">
            <div class="photo-card">
                <div class="photo-current" onclick="document.getElementById('photo-upload').click()">
                    <img id="profile-preview"
                         src="{{ Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default-avatar.jpg') }}"
                         alt="Photo de profil">
                    <div class="photo-overlay">
                        <span class="photo-change-text">📷 Changer</span>
                    </div>
                </div>

                <input type="file" id="photo-upload" name="photo_profile" accept="image/*" style="display: none;">

                <div class="photo-info">
                    <h2>{{ Auth::user()->nom }}</h2>
                    <p>Propriétaire</p>
                </div>

                <div class="photo-actions">
                    <button type="button" class="btn-photo" onclick="document.getElementById('photo-upload').click()">
                        <span class="btn-icon">📸</span>
                        <span class="btn-text">Changer la photo</span>
                    </button>
                    <button type="button" class="btn-photo-remove" onclick="removePhoto()">
                        <span class="btn-icon">🗑️</span>
                        <span class="btn-text">Supprimer</span>
                    </button>
                </div>

                <div class="photo-hint">
                    <small><span class="hint-icon">📋</span> Formats: JPG, PNG, GIF</small>
                    <small><span class="hint-icon">⚖️</span> Max: 2MB</small>
                </div>

                <!-- Preview nouvelle photo -->
                <div class="photo-preview" id="photoPreview" style="display: none;">
                    <h4>Aperçu :</h4>
                    <div class="preview-container">
                        <img id="previewImage" alt="Aperçu">
                    </div>
                    <div class="preview-actions">
                        <button class="btn-confirm" onclick="confirmPhoto()">
                            <span class="btn-icon">✅</span> Valider
                        </button>
                        <button class="btn-cancel" onclick="cancelPhoto()">
                            <span class="btn-icon">❌</span> Annuler
                        </button>
                    </div>
                </div>
            </div>

            <!-- Navigation rapide -->
            <div class="quick-nav-card">
                <h4>Navigation rapide</h4>
                <a href="{{ route('proprietaire.biens') }}" class="quick-nav-item">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">Mes biens</span>
                    <span class="nav-arrow">→</span>
                </a>
                <a href="{{ route('proprietaire.locataires') }}" class="quick-nav-item">
                    <span class="nav-icon">👥</span>
                    <span class="nav-text">Mes locataires</span>
                    <span class="nav-arrow">→</span>
                </a>
                <a href="{{ route('proprietaire.paiements') }}" class="quick-nav-item">
                    <span class="nav-icon">💰</span>
                    <span class="nav-text">Mes paiements</span>
                    <span class="nav-arrow">→</span>
                </a>
            </div>
        </div>

        <!-- Colonne Droite - Formulaire -->
        <div class="profile-form-column">
            <form id="profile-form" action="{{ route('proprietaire.profil') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Section Informations personnelles -->
                <div class="form-section">
                    <h3>
                        <span class="section-icon">👤</span>
                        Informations personnelles
                    </h3>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">
                                <span class="label-icon">📛</span>
                                Nom complet
                            </label>
                            <input type="text"
                                   id="nom"
                                   name="nom"
                                   value="{{ old('nom', $proprietaire->nom) }}"
                                   placeholder="Votre nom complet"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <span class="label-icon">📧</span>
                                Adresse email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $proprietaire->email) }}"
                                   placeholder="votre@email.com"
                                   required>
                        </div>

                        <div class="form-group full-width">
                            <label>
                                <span class="label-icon">📞</span>
                                Téléphone
                            </label>
                            <div class="phone-group">
                                <select name="indicatif_pays" class="phone-code">
                                    <option value="+225" {{ $proprietaire->indicatif_pays == '+225' ? 'selected' : '' }}>+225 (CI)</option>
                                    <option value="+33" {{ $proprietaire->indicatif_pays == '+33' ? 'selected' : '' }}>+33 (FR)</option>
                                    <option value="+229" {{ $proprietaire->indicatif_pays == '+229' ? 'selected' : '' }}>+229 (BJ)</option>
                                    <option value="+226" {{ $proprietaire->indicatif_pays == '+226' ? 'selected' : '' }}>+226 (BF)</option>
                                    <option value="+223" {{ $proprietaire->indicatif_pays == '+223' ? 'selected' : '' }}>+223 (ML)</option>
                                </select>
                                <input type="tel"
                                       name="telephone"
                                       value="{{ old('telephone', $proprietaire->telephone) }}"
                                       placeholder="0701234567"
                                       class="phone-number"
                                       required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Sécurité -->
                <div class="form-section">
                    <h3>
                        <span class="section-icon">🔐</span>
                        Sécurité
                    </h3>

                    <div class="security-note">
                        <span class="note-icon">ℹ️</span>
                        <span>Laissez vide si vous ne souhaitez pas changer de mot de passe</span>
                    </div>

                    <button type="button" id="togglePasswordBtn" class="btn-toggle-password">
                        <span class="btn-icon">🔑</span>
                        <span class="btn-text">Changer le mot de passe</span>
                    </button>

                    <div class="password-fields" id="passwordFields" style="display: none;">
                        <div class="form-group">
                            <label for="current_password">
                                <span class="label-icon">🔒</span>
                                Mot de passe actuel
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   placeholder="••••••••">
                            <small class="field-hint">Requis pour changer le mot de passe</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password">
                                <span class="label-icon">🔑</span>
                                Nouveau mot de passe
                            </label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   placeholder="Minimum 6 caractères">
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar"></div>
                                <span class="strength-text">Non défini</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">
                                <span class="label-icon">✅</span>
                                Confirmer le mot de passe
                            </label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   placeholder="••••••••">
                            <div class="password-match" id="passwordMatch">
                                <span class="match-text">⛔ Les mots de passe ne correspondent pas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Informations système -->
                <div class="form-section">
                    <h3>
                        <span class="section-icon">📊</span>
                        Informations système
                    </h3>

                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">Membre depuis :</span>
                            <span class="info-value">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dernière modification :</span>
                            <span class="info-value">{{ Auth::user()->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Statut :</span>
                            <span class="status-badge status-{{ Auth::user()->statut }}">
                                {{ Auth::user()->statut }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <span class="btn-icon">💾</span>
                        Enregistrer les modifications
                    </button>
                    <button type="button" class="btn-cancel" onclick="resetForm()">
                        <span class="btn-icon">🔄</span>
                        Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal-content confirmation-modal">
        <div class="modal-header">
            <h3>Confirmation</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="confirmation-icon">❓</div>
            <p>Êtes-vous sûr de vouloir enregistrer les modifications ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Annuler</button>
            <button class="btn-confirm" onclick="submitForm()">Confirmer</button>
        </div>
    </div>
</div>

<style>
/* ===== PAGE PROFIL PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-profile-page {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.profile-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--proprio-border-light);
}

.profile-header h1 {
    color: var(--proprio-text-primary);
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, var(--proprio-primary) 0%, var(--proprio-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-icon {
    font-size: 2.5rem;
    background: none;
    -webkit-text-fill-color: initial;
    color: var(--proprio-primary);
}

.profile-header p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* ===== ALERTES ===== */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    margin-bottom: 2rem;
    position: relative;
    animation: slideIn 0.3s ease;
    border: 1px solid transparent;
}

.alert-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border-color: var(--proprio-primary);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert ul {
    margin: 0;
    padding-left: 1.5rem;
    flex: 1;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: inherit;
    opacity: 0.5;
    transition: opacity 0.3s;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-full);
}

.alert-close:hover {
    opacity: 1;
    background: rgba(0,0,0,0.05);
}

/* ===== CONTAINER PRINCIPAL ===== */
.profile-container {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2rem;
}

/* ===== COLONNE GAUCHE ===== */
.profile-photo-column {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.photo-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s;
    box-shadow: var(--proprio-shadow-sm);
}

.photo-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.photo-current {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--proprio-border-light);
    cursor: pointer;
    transition: all 0.3s;
}

.photo-current:hover {
    border-color: var(--proprio-primary);
    transform: scale(1.02);
}

.photo-current img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.photo-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
    backdrop-filter: blur(2px);
}

.photo-current:hover .photo-overlay {
    opacity: 1;
}

.photo-change-text {
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-full);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.photo-info h2 {
    color: var(--proprio-text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.photo-info p {
    color: var(--proprio-text-secondary);
    margin: 0;
    font-size: 0.95rem;
}

.photo-actions {
    display: flex;
    gap: 0.75rem;
    margin: 1.5rem 0 1rem;
}

.btn-photo, .btn-photo-remove {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.8rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-photo:hover {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

.btn-photo-remove:hover {
    background: var(--proprio-danger);
    color: white;
    border-color: var(--proprio-danger);
}

.btn-icon {
    font-size: 1.1rem;
}

.photo-hint {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.75rem;
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    font-size: 0.8rem;
    color: var(--proprio-text-tertiary);
}

.photo-hint small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.hint-icon {
    font-size: 0.9rem;
}

/* Preview photo */
.photo-preview {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--proprio-border-light);
}

.photo-preview h4 {
    margin: 0 0 1rem 0;
    color: var(--proprio-text-primary);
    font-size: 1rem;
}

.preview-container {
    width: 100px;
    height: 100px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--proprio-primary);
    box-shadow: var(--proprio-shadow-md);
}

.preview-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.preview-actions button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s;
}

.btn-confirm {
    background: var(--proprio-primary);
    color: white;
}

.btn-confirm:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
}

/* Navigation rapide */
.quick-nav-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--proprio-shadow-sm);
}

.quick-nav-card h4 {
    margin: 0 0 1rem 0;
    color: var(--proprio-text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.quick-nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.8rem 1rem;
    border-radius: var(--radius-lg);
    color: var(--proprio-text-secondary);
    text-decoration: none;
    transition: all 0.3s;
    margin-bottom: 0.5rem;
    background: var(--proprio-bg-secondary);
    border: 1px solid transparent;
}

.quick-nav-item:hover {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    transform: translateX(5px);
    border-color: var(--proprio-primary);
}

.nav-icon {
    font-size: 1.2rem;
}

.nav-text {
    flex: 1;
    font-weight: 500;
}

.nav-arrow {
    font-size: 1rem;
    opacity: 0.5;
    transition: opacity 0.3s;
}

.quick-nav-item:hover .nav-arrow {
    opacity: 1;
}

/* ===== COLONNE FORMULAIRE ===== */
.profile-form-column {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 2rem;
    box-shadow: var(--proprio-shadow-sm);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--proprio-border-light);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section h3 {
    color: var(--proprio-text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-icon {
    font-size: 1.4rem;
}

/* Grille formulaire */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--proprio-text-primary);
    font-size: 0.9rem;
}

.label-icon {
    font-size: 1rem;
}

.form-group input,
.form-group select {
    padding: 0.9rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
    background: var(--proprio-bg-card);
}

.form-group input::placeholder {
    color: var(--proprio-text-tertiary);
    opacity: 0.7;
}

/* Groupe téléphone */
.phone-group {
    display: flex;
    gap: 0.5rem;
}

.phone-code {
    width: 120px;
    flex-shrink: 0;
}

.phone-number {
    flex: 1;
}

/* Sécurité */
.security-note {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--proprio-text-secondary);
    border: 1px solid var(--proprio-border-light);
}

.note-icon {
    font-size: 1.2rem;
}

.btn-toggle-password {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: var(--proprio-bg-secondary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    color: var(--proprio-text-primary);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 1rem;
    width: fit-content;
}

.btn-toggle-password:hover {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

.password-fields {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-top: 1rem;
    border: 1px solid var(--proprio-border-light);
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    height: 4px;
    background: var(--proprio-border-light);
    border-radius: var(--radius-full);
    margin-bottom: 0.25rem;
    width: 0%;
    transition: all 0.3s;
}

.strength-text {
    font-size: 0.8rem;
    color: var(--proprio-text-secondary);
}

.password-match {
    margin-top: 0.5rem;
}

.match-text {
    font-size: 0.8rem;
    color: #dc2626;
    display: none;
    align-items: center;
    gap: 0.25rem;
}

.field-hint {
    font-size: 0.75rem;
    color: var(--proprio-text-tertiary);
    margin-top: 0.25rem;
}

/* Informations système */
.system-info {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--proprio-border-light);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--proprio-border-light);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--proprio-text-primary);
}

.info-value {
    color: var(--proprio-text-secondary);
}

.status-badge {
    padding: 0.25rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-actif {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
}

.status-en_attente {
    background: #fef3c7;
    color: #92400e;
}

.status-bloque {
    background: #fee2e2;
    color: #dc2626;
}

/* Actions formulaire */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--proprio-border-light);
}

.btn-save, .btn-cancel {
    flex: 1;
    padding: 1rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
}

.btn-save {
    background: var(--proprio-primary);
    color: white;
}

.btn-save:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

.btn-cancel {
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
    border: 1px solid var(--proprio-border-light);
}

.btn-cancel:hover {
    background: var(--proprio-danger);
    color: white;
    transform: translateY(-2px);
    border-color: var(--proprio-danger);
}

/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.confirmation-modal {
    max-width: 450px;
    width: 100%;
    background: var(--proprio-bg-card);
    border-radius: var(--radius-xl);
    border: 1px solid var(--proprio-border-light);
    box-shadow: var(--proprio-shadow-xl);
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--proprio-border-light);
}

.modal-header h3 {
    margin: 0;
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--proprio-text-tertiary);
    transition: all 0.3s;
    line-height: 1;
}

.modal-close:hover {
    color: var(--proprio-danger);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
    text-align: center;
}

.confirmation-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

.modal-body p {
    color: var(--proprio-text-primary);
    font-size: 1.1rem;
    margin: 0;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--proprio-border-light);
}

.modal-footer button {
    flex: 1;
    padding: 0.75rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: none;
}

.modal-footer .btn-confirm {
    background: var(--proprio-primary);
    color: white;
}

.modal-footer .btn-confirm:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
}

.modal-footer .btn-cancel {
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
}

.modal-footer .btn-cancel:hover {
    background: var(--proprio-danger);
    color: white;
}

/* ===== ANIMATIONS ===== */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .profile-container {
        grid-template-columns: 300px 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .proprio-profile-page {
        padding: 1rem;
    }

    .profile-container {
        grid-template-columns: 1fr;
    }

    .profile-photo-column {
        order: 2;
    }

    .profile-form-column {
        order: 1;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .phone-group {
        flex-direction: column;
    }

    .phone-code {
        width: 100%;
    }

    .photo-current {
        width: 150px;
        height: 150px;
    }

    .btn-text {
        display: none;
    }

    .btn-icon {
        font-size: 1.2rem;
    }

    .quick-nav-item {
        padding: 0.6rem;
    }

    .modal-footer {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .profile-header h1 {
        font-size: 1.8rem;
    }

    .profile-header p {
        font-size: 0.95rem;
    }

    .photo-card {
        padding: 1.5rem;
    }

    .photo-actions {
        flex-direction: column;
    }

    .btn-photo, .btn-photo-remove {
        width: 100%;
    }

    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .photo-card,
[data-proprietaire-theme="dark"] .quick-nav-card,
[data-proprietaire-theme="dark"] .profile-form-column,
[data-proprietaire-theme="dark"] .confirmation-modal {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .form-group input,
[data-proprietaire-theme="dark"] .form-group select {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .form-group input:focus {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .quick-nav-item {
    background: var(--proprio-dark-bg);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .quick-nav-item:hover {
    background: rgba(96, 165, 250, 0.2);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .password-fields {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .system-info {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .btn-toggle-password {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-toggle-password:hover {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .modal-header,
[data-proprietaire-theme="dark"] .modal-footer {
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .modal-footer .btn-cancel {
    background: var(--proprio-dark-bg);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .modal-footer .btn-cancel:hover {
    background: var(--proprio-danger);
    color: white;
}

[data-proprietaire-theme="dark"] .status-actif {
    background: rgba(96, 165, 250, 0.2);
    color: var(--proprio-primary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==================== VARIABLES ====================
    let newPhotoFile = null;
    let passwordFieldsVisible = false;

    // ==================== GESTION PHOTO ====================
    const photoUpload = document.getElementById('photo-upload');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const profilePreview = document.getElementById('profile-preview');

    if (photoUpload) {
        photoUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    showToast('La photo ne doit pas dépasser 2MB', 'error');
                    this.value = '';
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showToast('Format non supporté. Utilisez JPG, PNG ou GIF.', 'error');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    photoPreview.style.display = 'block';
                    newPhotoFile = file;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // ==================== GESTION MOT DE PASSE ====================
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordFields = document.getElementById('passwordFields');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    const matchText = document.querySelector('.match-text');

    if (toggleBtn && passwordFields) {
        toggleBtn.addEventListener('click', function() {
            if (passwordFieldsVisible) {
                passwordFields.style.display = 'none';
                this.innerHTML = '<span class="btn-icon">🔑</span><span class="btn-text">Changer le mot de passe</span>';
            } else {
                passwordFields.style.display = 'block';
                this.innerHTML = '<span class="btn-icon">❌</span><span class="btn-text">Annuler le changement</span>';
            }
            passwordFieldsVisible = !passwordFieldsVisible;
        });
    }

    if (newPassword) {
        newPassword.addEventListener('input', checkPasswordStrength);
    }

    if (confirmPassword) {
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }

    function checkPasswordStrength() {
        const password = newPassword.value;
        let strength = 0;

        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
        if (password.match(/\d/)) strength++;
        if (password.match(/[^a-zA-Z\d]/)) strength++;

        const colors = ['#dc2626', '#f59e0b', '#fbbf24', '#10b981'];
        const texts = ['Très faible', 'Faible', 'Moyen', 'Fort'];

        strengthBar.style.background = colors[strength] || '#dc2626';
        strengthBar.style.width = `${(strength + 1) * 25}%`;
        strengthText.textContent = texts[strength] || 'Très faible';
        strengthText.style.color = colors[strength] || '#dc2626';
    }

    function checkPasswordMatch() {
        if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
            matchText.style.display = 'flex';
            return false;
        } else {
            matchText.style.display = 'none';
            return true;
        }
    }

    // ==================== VALIDATION FORMULAIRE ====================
    const form = document.getElementById('profile-form');
    const currentPassword = document.getElementById('current_password');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (newPassword.value && newPassword.value.length < 6) {
                showToast('Le mot de passe doit contenir au moins 6 caractères.', 'error');
                newPassword.focus();
                return;
            }

            if (newPassword.value && !currentPassword.value) {
                showToast('Veuillez saisir votre mot de passe actuel.', 'error');
                currentPassword.focus();
                return;
            }

            if (newPassword.value && newPassword.value !== confirmPassword.value) {
                showToast('Les mots de passe ne correspondent pas.', 'error');
                confirmPassword.focus();
                return;
            }

            showConfirmationModal();
        });
    }

    // ==================== FONCTIONS PHOTO ====================
    window.confirmPhoto = function() {
        if (!newPhotoFile) return;

        const formData = new FormData();
        formData.append('photo_profil', newPhotoFile);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'POST');

        const nom = document.getElementById('nom')?.value;
        const email = document.getElementById('email')?.value;
        const telephone = document.querySelector('input[name="telephone"]')?.value;
        const indicatif = document.querySelector('select[name="indicatif_pays"]')?.value;

        if (nom) formData.append('nom', nom);
        if (email) formData.append('email', email);
        if (telephone) formData.append('telephone', telephone);
        if (indicatif) formData.append('indicatif_pays', indicatif);

        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="btn-icon">⏳</span>';
        btn.disabled = true;

        fetch('{{ route("proprietaire.profil.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                profilePreview.src = data.photo;
                photoPreview.style.display = 'none';
                newPhotoFile = null;
                showToast('Photo mise à jour avec succès', 'success');

                const headerAvatar = document.getElementById('headerAvatar');
                if (headerAvatar) headerAvatar.src = data.photo;
            } else {
                showToast('Erreur lors de la mise à jour', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors de l\'upload', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    };

    window.cancelPhoto = function() {
        photoPreview.style.display = 'none';
        newPhotoFile = null;
        photoUpload.value = '';
    };

    window.removePhoto = function() {
        if (confirm('Supprimer la photo de profil ?')) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'POST');

            fetch('{{ route("proprietaire.profil.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    profilePreview.src = '/images/default-avatar.jpg';
                    showToast('Photo supprimée avec succès', 'success');
                }
            });
        }
    };

    // ==================== MODAL ====================
    window.showConfirmationModal = function() {
        document.getElementById('confirmationModal').style.display = 'flex';
    };

    window.closeModal = function() {
        document.getElementById('confirmationModal').style.display = 'none';
    };

    window.submitForm = function() {
        document.getElementById('profile-form').submit();
    };

    // ==================== RÉINITIALISATION ====================
    window.resetForm = function() {
        if (confirm('Annuler toutes les modifications ?')) {
            document.getElementById('profile-form').reset();
            passwordFields.style.display = 'none';
            passwordFieldsVisible = false;
            toggleBtn.innerHTML = '<span class="btn-icon">🔑</span><span class="btn-text">Changer le mot de passe</span>';
            strengthBar.style.width = '0%';
            strengthText.textContent = 'Non défini';
            matchText.style.display = 'none';
            showToast('Formulaire réinitialisé', 'info');
        }
    };

    // ==================== TOAST ====================
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `proprio-toast proprio-toast-${type}`;

        let icon = '✅';
        if (type === 'error') icon = '❌';
        if (type === 'info') icon = 'ℹ️';

        toast.innerHTML = `
            <span style="font-size: 1.2rem;">${icon}</span>
            <span>${message}</span>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    };

    // ==================== FERMETURE MODAL ====================
    window.onclick = function(event) {
        const modal = document.getElementById('confirmationModal');
        if (event.target == modal) {
            closeModal();
        }
    };
});
</script>
@endsection
