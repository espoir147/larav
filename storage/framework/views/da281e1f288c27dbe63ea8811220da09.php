<?php $__env->startSection('client-content'); ?>
<div class="client-profile-page">
    <div class="profile-header">
        <h1>
            <span class="header-icon">👤</span>
            Mon Profil
        </h1>
        <p>Gérez vos informations personnelles et paramètres de compte</p>
    </div>

    <!-- Messages de succès/erreur -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <span class="alert-icon">✅</span>
            <span><?php echo e(session('success')); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-error">
            <span class="alert-icon">❌</span>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    <?php endif; ?>

    <div class="profile-container">
        <!-- Colonne Gauche - Photo et navigation -->
        <div class="profile-sidebar">
            <div class="photo-card">
                <div class="photo-current" onclick="document.getElementById('photo-upload').click()">
                    <img id="profile-preview"
                         src="<?php echo e(Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default-avatar.jpg')); ?>"
                         alt="Photo de profil">
                    <div class="photo-overlay">
                        <span class="photo-change-text">📷 Changer</span>
                    </div>
                </div>

                <input type="file" id="photo-upload" name="photo_profile" accept="image/*" style="display: none;">

                <div class="photo-info">
                    <h2><?php echo e(Auth::user()->nom); ?></h2>
                    <p>Client</p>
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
                    <small>📋 Formats: JPG, PNG, GIF</small>
                    <small>⚖️ Max: 2MB</small>
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
                <a href="<?php echo e(route('client.locations')); ?>" class="quick-nav-item">
                    <span class="nav-icon">🏠</span>
                    <span class="nav-text">Mes locations</span>
                    <span class="nav-arrow">→</span>
                </a>
                <a href="<?php echo e(route('client.paiements')); ?>" class="quick-nav-item">
                    <span class="nav-icon">💰</span>
                    <span class="nav-text">Mes paiements</span>
                    <span class="nav-arrow">→</span>
                </a>
                <a href="<?php echo e(route('client.messagerie')); ?>" class="quick-nav-item">
                    <span class="nav-icon">✉️</span>
                    <span class="nav-text">Messagerie</span>
                    <span class="nav-arrow">→</span>
                </a>
            </div>
        </div>

        <!-- Colonne Droite - Formulaire -->
        <div class="profile-form">
            <form id="profile-form" action="<?php echo e(route('client.profil')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

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
                                   value="<?php echo e(old('nom', $client->nom)); ?>"
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
                                   value="<?php echo e(old('email', $client->email)); ?>"
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
                                    <option value="+225" <?php echo e($client->indicatif_pays == '+225' ? 'selected' : ''); ?>>+225 (CI)</option>
                                    <option value="+33" <?php echo e($client->indicatif_pays == '+33' ? 'selected' : ''); ?>>+33 (FR)</option>
                                    <option value="+229" <?php echo e($client->indicatif_pays == '+229' ? 'selected' : ''); ?>>+229 (BJ)</option>
                                    <option value="+226" <?php echo e($client->indicatif_pays == '+226' ? 'selected' : ''); ?>>+226 (BF)</option>
                                    <option value="+223" <?php echo e($client->indicatif_pays == '+223' ? 'selected' : ''); ?>>+223 (ML)</option>
                                </select>
                                <input type="tel"
                                       name="telephone"
                                       value="<?php echo e(old('telephone', $client->telephone)); ?>"
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
                            <span class="info-value"><?php echo e(Auth::user()->created_at->format('d/m/Y')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dernière modification :</span>
                            <span class="info-value"><?php echo e(Auth::user()->updated_at->format('d/m/Y')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Statut :</span>
                            <span class="status-badge status-<?php echo e(Auth::user()->statut); ?>">
                                <?php echo e(Auth::user()->statut); ?>

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
    <div class="modal-content">
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
/* ===== PAGE PROFIL CLIENT AMÉLIORÉE ===== */
.client-profile-page {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.profile-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--client-border-light);
}

.profile-header h1 {
    color: var(--client-text-primary);
    margin: 0 0 0.5rem 0;
    font-size: 2.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, var(--client-primary) 0%, var(--client-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.profile-header .header-icon {
    font-size: 2.5rem;
    background: none;
    -webkit-text-fill-color: initial;
    color: var(--client-primary);
}

.profile-header p {
    color: var(--client-text-secondary);
    margin: 0;
    font-size: 1.1rem;
    padding-left: 0.5rem;
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
    background: var(--client-primary-light);
    color: var(--client-primary);
    border-color: var(--client-primary);
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
.profile-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Carte photo */
.photo-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 2rem;
    text-align: center;
    transition: all 0.3s;
    box-shadow: var(--client-shadow-sm);
}

.photo-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary-light);
}

.photo-current {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--client-border-light);
    cursor: pointer;
    transition: all 0.3s;
}

.photo-current:hover {
    border-color: var(--client-primary);
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
    color: var(--client-text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.photo-info p {
    color: var(--client-text-secondary);
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
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    cursor: pointer;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-photo:hover {
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
}

.btn-photo-remove:hover {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
}

.btn-icon {
    font-size: 1.1rem;
}

.photo-hint {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 0.75rem;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    font-size: 0.8rem;
    color: var(--client-text-secondary);
}

.photo-hint small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Preview photo */
.photo-preview {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--client-border-light);
}

.photo-preview h4 {
    margin: 0 0 1rem 0;
    color: var(--client-text-primary);
    font-size: 1rem;
}

.preview-container {
    width: 100px;
    height: 100px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--client-primary);
    box-shadow: var(--client-shadow-md);
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
    background: var(--client-primary);
    color: white;
}

.btn-confirm:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
}

/* Navigation rapide */
.quick-nav-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--client-shadow-sm);
}

.quick-nav-card h4 {
    margin: 0 0 1rem 0;
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.quick-nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.8rem 1rem;
    border-radius: var(--radius-lg);
    color: var(--client-text-secondary);
    text-decoration: none;
    transition: all 0.3s;
    margin-bottom: 0.5rem;
    background: var(--client-bg-secondary);
    border: 1px solid transparent;
}

.quick-nav-item:hover {
    background: var(--client-primary-light);
    color: var(--client-primary);
    transform: translateX(5px);
    border-color: var(--client-primary);
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
.profile-form {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 2rem;
    box-shadow: var(--client-shadow-sm);
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--client-border-light);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section h3 {
    color: var(--client-text-primary);
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
    color: var(--client-text-primary);
    font-size: 0.9rem;
}

.label-icon {
    font-size: 1rem;
}

.form-group input,
.form-group select {
    padding: 0.9rem 1rem;
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--client-primary);
    box-shadow: 0 0 0 3px var(--client-primary-light);
    background: var(--client-bg-card);
}

.form-group input::placeholder {
    color: var(--client-text-tertiary);
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
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: var(--client-text-secondary);
    border: 1px solid var(--client-border-light);
}

.note-icon {
    font-size: 1.2rem;
}

.btn-toggle-password {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: var(--client-bg-secondary);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    color: var(--client-text-primary);
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
    margin-bottom: 1rem;
    width: fit-content;
}

.btn-toggle-password:hover {
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
}

.password-fields {
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-top: 1rem;
    border: 1px solid var(--client-border-light);
}

.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    height: 4px;
    background: var(--client-border-light);
    border-radius: var(--radius-full);
    margin-bottom: 0.25rem;
    width: 0%;
    transition: all 0.3s;
}

.strength-text {
    font-size: 0.8rem;
    color: var(--client-text-secondary);
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
    color: var(--client-text-tertiary);
    margin-top: 0.25rem;
}

/* Informations système */
.system-info {
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    border: 1px solid var(--client-border-light);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--client-border-light);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--client-text-primary);
}

.info-value {
    color: var(--client-text-secondary);
}

.status-badge {
    padding: 0.25rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-actif {
    background: var(--client-primary-light);
    color: var(--client-primary);
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
    border-top: 1px solid var(--client-border-light);
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
    background: var(--client-primary);
    color: white;
}

.btn-save:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
}

.btn-cancel {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
}

.btn-cancel:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
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
    z-index: 2000;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    backdrop-filter: blur(2px);
}

.modal-content {
    background: var(--client-bg-card);
    border-radius: var(--radius-xl);
    max-width: 450px;
    width: 100%;
    box-shadow: var(--client-shadow-xl);
    animation: modalSlideIn 0.3s;
}

@keyframes modalSlideIn {
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
    border-bottom: 1px solid var(--client-border-light);
}

.modal-header h3 {
    margin: 0;
    color: var(--client-text-primary);
    font-size: 1.3rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--client-text-tertiary);
    transition: all 0.3s;
    line-height: 1;
}

.modal-close:hover {
    color: #dc2626;
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
    color: var(--client-text-primary);
    font-size: 1.1rem;
    margin: 0;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--client-border-light);
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
    background: var(--client-primary);
    color: white;
}

.modal-footer .btn-confirm:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
}

.modal-footer .btn-cancel {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
}

.modal-footer .btn-cancel:hover {
    background: #dc2626;
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
    .client-profile-page {
        padding: 1rem;
    }

    .profile-container {
        grid-template-columns: 1fr;
    }

    .profile-sidebar {
        order: 2;
    }

    .profile-form {
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

    .form-section h3 {
        font-size: 1.1rem;
    }

    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

/* ===== THÈME SOMBRE - AJUSTEMENTS ===== */
[data-client-theme="dark"] .photo-card,
[data-client-theme="dark"] .quick-nav-card,
[data-client-theme="dark"] .profile-form {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .form-group input,
[data-client-theme="dark"] .form-group select {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .form-group input:focus {
    background: var(--client-dark-card);
}

[data-client-theme="dark"] .quick-nav-item {
    background: var(--client-dark-bg);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .quick-nav-item:hover {
    background: rgba(52, 211, 153, 0.2);
    color: var(--client-primary);
}

[data-client-theme="dark"] .password-fields {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .system-info {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .btn-toggle-password {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .btn-toggle-password:hover {
    background: var(--client-primary);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .modal-content {
    background: var(--client-dark-card);
}

[data-client-theme="dark"] .modal-header {
    border-bottom-color: var(--client-dark-border);
}

[data-client-theme="dark"] .modal-footer {
    border-top-color: var(--client-dark-border);
}

[data-client-theme="dark"] .modal-footer .btn-cancel {
    background: var(--client-dark-bg);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .modal-footer .btn-cancel:hover {
    background: #dc2626;
    color: white;
}

[data-client-theme="dark"] .status-actif {
    background: rgba(52, 211, 153, 0.2);
    color: var(--client-primary);
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
                // Validation
                if (file.size > 2 * 1024 * 1024) {
                    alert('La photo ne doit pas dépasser 2MB');
                    this.value = '';
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format non supporté. Utilisez JPG, PNG ou GIF.');
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
                alert('Le mot de passe doit contenir au moins 6 caractères.');
                newPassword.focus();
                return;
            }

            if (newPassword.value && !currentPassword.value) {
                alert('Veuillez saisir votre mot de passe actuel.');
                currentPassword.focus();
                return;
            }

            if (newPassword.value && newPassword.value !== confirmPassword.value) {
                alert('Les mots de passe ne correspondent pas.');
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
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    formData.append('_method', 'PUT');

    // Ajouter les champs de formulaire existants
    const nom = document.getElementById('nom')?.value;
    const email = document.getElementById('email')?.value;
    const telephone = document.querySelector('input[name="telephone"]')?.value;
    const indicatif = document.querySelector('select[name="indicatif_pays"]')?.value;

    if (nom) formData.append('nom', nom);
    if (email) formData.append('email', email);
    if (telephone) formData.append('telephone', telephone);
    if (indicatif) formData.append('indicatif_pays', indicatif);

    // Afficher un indicateur de chargement
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '⏳...';
    btn.disabled = true;

    fetch('<?php echo e(route("client.profil")); ?>', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        console.log('Réponse brute:', text);

        try {
            const data = JSON.parse(text);
            if (!response.ok) throw new Error(data.message || 'Erreur serveur');
            return data;
        } catch (e) {
            throw new Error('Réponse non JSON: ' + text.substring(0, 100));
        }
    })
    .then(data => {
        if (data.success) {
            document.getElementById('profile-preview').src = data.photo;
            document.getElementById('photoPreview').style.display = 'none';
            newPhotoFile = null;
            showToast('Photo mise à jour avec succès', 'success');

            const headerAvatar = document.querySelector('.user-avatar');
            if (headerAvatar && data.photo) {
                headerAvatar.src = data.photo;
            }
        } else {
            throw new Error(data.message || 'Erreur inconnue');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        showToast('Erreur: ' + error.message, 'error');
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
            formData.append('_token', '<?php echo e(csrf_token()); ?>');
            formData.append('_method', 'PUT');

            fetch('<?php echo e(route("client.profil")); ?>', {
                method: 'POST',
                body: formData
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
        toast.className = `client-toast client-toast-${type}`;

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/client/profil.blade.php ENDPATH**/ ?>