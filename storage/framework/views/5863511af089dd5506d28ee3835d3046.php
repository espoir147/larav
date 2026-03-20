<?php $__env->startSection('admin-content'); ?>
<div class="admin-profil-page">
    <div class="profil-header">
        <h2>⚙️ Profil Administrateur</h2>
        <p>Gérez vos informations personnelles et paramètres de compte</p>
    </div>

    <div class="profil-container">
        <!-- Colonne Gauche - Photo -->
        <div class="profil-photo-column">
            <div class="photo-card">
                <div class="photo-current" id="photoCurrent" onclick="document.getElementById('photoInput').click()">
                    <img id="currentPhoto" src="<?php echo e(Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default-avatar.jpg')); ?>" alt="Photo profil">
                    <div class="photo-overlay">
                        <span class="photo-change-text">📷 Changer</span>
                    </div>
                </div>

                <input type="file" id="photoInput" accept="image/*" style="display: none;">

                <div class="photo-actions">
                    <button type="button" class="btn-photo" onclick="document.getElementById('photoInput').click()">
                        <span class="btn-icon">📸</span>
                        <span class="btn-text">Choisir une photo</span>
                    </button>
                    <button type="button" class="btn-photo-remove" onclick="removePhoto()">
                        <span class="btn-icon">🗑️</span>
                        <span class="btn-text">Supprimer</span>
                    </button>
                </div>

                <div class="photo-info">
                    <small>📋 Formats: JPG, PNG, WEBP</small>
                    <small>⚖️ Taille max: 2MB</small>
                </div>

                <!-- Preview nouvelle photo -->
                <div class="photo-preview" id="photoPreview" style="display: none;">
                    <h4>Aperçu :</h4>
                    <div class="preview-image-container">
                        <img id="previewImage" alt="Preview">
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
        </div>

        <!-- Colonne Droite - Informations -->
        <div class="profil-info-column">
            <?php if(session('success')): ?>
                <div class="alert-success">
                    <span class="alert-icon">✅</span>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert-error">
                    <span class="alert-icon">❌</span>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.profil')); ?>" method="POST" id="profilForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <!-- Section Identité -->
                <div class="info-section">
                    <h3><span class="section-icon">👤</span> Identité</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom">Nom complet</label>
                            <input type="text" id="nom" name="nom"
                                   value="<?php echo e(old('nom', Auth::user()->nom)); ?>"
                                   placeholder="Votre nom complet"
                                   required>
                            <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-message"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                   value="<?php echo e(old('email', Auth::user()->email)); ?>"
                                   placeholder="votre@email.com"
                                   required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-message"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label>Type de compte</label>
                            <input type="text" value="Administrateur" disabled class="disabled-field">
                            <small class="field-info">Rôle non modifiable</small>
                        </div>
                    </div>
                </div>

                <!-- Section Contact -->
                <div class="info-section">
                    <h3><span class="section-icon">📞</span> Contact</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="indicatif_pays">Indicatif pays</label>
                            <input type="text" id="indicatif_pays" name="indicatif_pays"
                                   value="<?php echo e(old('indicatif_pays', Auth::user()->indicatif_pays ?? '+229')); ?>"
                                   placeholder="+229" required>
                        </div>

                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input type="tel" id="telephone" name="telephone"
                                   value="<?php echo e(old('telephone', Auth::user()->telephone)); ?>"
                                   placeholder="90123456"
                                   pattern="[0-9]{8,10}" maxlength="10" required>
                            <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="error-message"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>

                <!-- Section Sécurité -->
                <div class="info-section">
                    <h3><span class="section-icon">🔐</span> Sécurité</h3>
                    <div class="security-note">
                        <span class="note-icon">ℹ️</span>
                        <span>Laissez vide si vous ne souhaitez pas changer de mot de passe</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="current_password">Mot de passe actuel</label>
                            <input type="password" id="current_password" name="current_password"
                                   placeholder="••••••••">
                            <small class="field-info">Requis pour changer le mot de passe</small>
                        </div>

                        <div class="form-group">
                            <label for="new_password">Nouveau mot de passe</label>
                            <input type="password" id="new_password" name="new_password"
                                   placeholder="Minimum 8 caractères">
                            <div class="password-strength" id="passwordStrength">
                                <div class="strength-bar"></div>
                                <span class="strength-text">Non défini</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation">Confirmer le mot de passe</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                   placeholder="••••••••">
                            <div class="password-match" id="passwordMatch">
                                <span class="match-text">⛔ Les mots de passe ne correspondent pas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Informations système -->
                <div class="info-section">
                    <h3><span class="section-icon">📊</span> Informations système</h3>
                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">Date d'inscription :</span>
                            <span class="info-value"><?php echo e(Auth::user()->created_at->format('d/m/Y à H:i')); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Dernière modification :</span>
                            <span class="info-value"><?php echo e(Auth::user()->updated_at->format('d/m/Y à H:i')); ?></span>
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
<div id="confirmationModal" class="admin-modal">
    <div class="modal-content confirmation-modal">
        <div class="modal-header">
            <h3>Confirmation</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="confirmation-icon">❓</div>
            <p id="modalMessage">Êtes-vous sûr de vouloir sauvegarder les modifications ?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Annuler</button>
            <button class="btn-confirm" onclick="submitForm()">Confirmer</button>
        </div>
    </div>
</div>

<style>
/* === PAGE PROFIL ADMIN AMÉLIORÉE === */
.admin-profil-page {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* En-tête */
.profil-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--border-color);
}

.profil-header h2 {
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    font-size: 2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.profil-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

/* Container principal */
.profil-container {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2rem;
}

/* ===== COLONNE PHOTO AMÉLIORÉE ===== */
.profil-photo-column {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.photo-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s, box-shadow 0.3s;
}

.photo-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
}

.photo-current {
    position: relative;
    width: 200px;
    height: 200px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--border-color);
    cursor: pointer;
    transition: all 0.3s;
}

.photo-current:hover {
    border-color: var(--accent-color);
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
    background: rgba(0, 0, 0, 0.6);
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
    font-size: 1rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 30px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.photo-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.btn-photo, .btn-photo-remove {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.9rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    background: var(--bg-primary);
    color: var(--text-primary);
    cursor: pointer;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s;
    width: 100%;
}

.btn-photo:hover {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
    transform: translateY(-2px);
}

.btn-photo-remove:hover {
    background: #dc2626;
    color: white;
    border-color: #dc2626;
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 1.2rem;
}

.photo-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border-radius: 10px;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.photo-info small {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Preview photo */
.photo-preview {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--border-color);
}

.photo-preview h4 {
    margin: 0 0 1rem 0;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.preview-image-container {
    width: 120px;
    height: 120px;
    margin: 0 auto 1rem;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--success-color);
    box-shadow: 0 4px 12px rgba(0, 170, 0, 0.2);
}

.preview-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.preview-actions button {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

/* ===== COLONNE INFORMATIONS AMÉLIORÉE ===== */
.profil-info-column {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Alertes */
.alert-success, .alert-error {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    animation: slideIn 0.3s ease;
}

.alert-success {
    background: rgba(0, 170, 0, 0.1);
    border: 2px solid var(--success-color);
    color: var(--success-color);
}

.alert-error {
    background: rgba(220, 38, 38, 0.1);
    border: 2px solid #dc2626;
    color: #dc2626;
}

.alert-icon {
    font-size: 1.5rem;
}

.alert-error ul {
    margin: 0;
    padding-left: 1.5rem;
}

/* Sections */
.info-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid var(--border-color);
}

.info-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.info-section h3 {
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    font-size: 1.2rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-icon {
    font-size: 1.4rem;
}

/* Note sécurité */
.security-note {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: var(--bg-secondary);
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.note-icon {
    font-size: 1.2rem;
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

.form-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.form-group input {
    padding: 0.9rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s;
    background: var(--bg-primary);
    color: var(--text-primary);
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
}

.form-group input::placeholder {
    color: var(--text-secondary);
    opacity: 0.5;
}

.disabled-field {
    background: var(--bg-secondary) !important;
    color: var(--text-secondary) !important;
    cursor: not-allowed;
    border-color: var(--border-color) !important;
}

.field-info {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.error-message {
    color: #dc2626;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Indicateur force mot de passe */
.password-strength {
    margin-top: 0.75rem;
}

.strength-bar {
    height: 6px;
    background: var(--border-color);
    border-radius: 3px;
    margin-bottom: 0.5rem;
    width: 0%;
    transition: all 0.3s;
}

.strength-text {
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 600;
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

/* Informations système */
.system-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: var(--bg-secondary);
    padding: 1.5rem;
    border-radius: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--text-primary);
}

.info-value {
    color: var(--text-secondary);
}

.status-badge {
    padding: 0.3rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-actif {
    background: var(--success-color);
    color: white;
}

.status-en_attente {
    background: var(--warning-color);
    color: black;
}

.status-bloque {
    background: #dc2626;
    color: white;
}

/* Actions formulaire */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--border-color);
}

.btn-save, .btn-cancel {
    flex: 1;
    padding: 1rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.btn-save {
    background: var(--success-color);
    color: white;
}

.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 170, 0, 0.3);
}

.btn-cancel {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-color: var(--border-color);
}

.btn-cancel:hover {
    background: var(--border-color);
    transform: translateY(-2px);
}

/* Modal de confirmation */
.confirmation-modal {
    max-width: 450px !important;
    text-align: center;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 2px solid var(--border-color);
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.3rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--text-secondary);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.modal-close:hover {
    background: var(--bg-secondary);
    color: #dc2626;
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

.confirmation-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

.modal-body p {
    color: var(--text-primary);
    font-size: 1.1rem;
    margin: 0;
}

.modal-footer {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 2rem;
    border-top: 2px solid var(--border-color);
}

.modal-footer button {
    flex: 1;
    padding: 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.modal-footer .btn-confirm {
    background: var(--success-color);
    color: white;
}

.modal-footer .btn-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 170, 0, 0.3);
}

.modal-footer .btn-cancel {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border-color: var(--border-color);
}

.modal-footer .btn-cancel:hover {
    background: var(--border-color);
    transform: translateY(-2px);
}

/* Animations */
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
    .profil-container {
        grid-template-columns: 300px 1fr;
        gap: 1.5rem;
    }
}

@media (max-width: 768px) {
    .admin-profil-page {
        padding: 1rem;
    }

    .profil-container {
        grid-template-columns: 1fr;
    }

    .profil-photo-column {
        position: relative;
        top: 0;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .photo-current {
        width: 150px;
        height: 150px;
    }

    .modal-footer {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .profil-header h2 {
        font-size: 1.5rem;
    }

    .profil-header p {
        font-size: 0.95rem;
    }

    .photo-actions button {
        font-size: 0.85rem;
    }

    .btn-text {
        display: none;
    }

    .btn-icon {
        font-size: 1.2rem;
    }
}
</style>

<script>
    let newPhotoFile = null;
document.addEventListener('DOMContentLoaded', function() {
    // Gestion upload photo
    const photoInput = document.getElementById('photoInput');
    const currentPhoto = document.getElementById('currentPhoto');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');



    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (!validateImageFile(file)) return;
                newPhotoFile = file;
                showPhotoPreview(file);
            }
        });
    }

    // Validation force mot de passe
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');
    const matchText = document.querySelector('.match-text');

    if (newPassword) {
        newPassword.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
        });
    }

    if (confirmPassword) {
        confirmPassword.addEventListener('input', checkPasswordMatch);
    }

    // Validation formulaire avant envoi
    const profilForm = document.getElementById('profilForm');
    if (profilForm) {
        profilForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateForm()) {
                showConfirmationModal();
            }
        });
    }
});

// Validation fichier image
function validateImageFile(file) {
    const validTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    if (!validTypes.includes(file.type)) {
        showToast('Format non supporté. Utilisez JPG, PNG ou WEBP.', 'error');
        return false;
    }

    if (file.size > maxSize) {
        showToast('Fichier trop volumineux. Maximum 2MB.', 'error');
        return false;
    }

    return true;
}

// Aperçu photo
function showPhotoPreview(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const previewImage = document.getElementById('previewImage');
        const photoPreview = document.getElementById('photoPreview');
        if (previewImage && photoPreview) {
            previewImage.src = e.target.result;
            photoPreview.style.display = 'block';
        }
    };
    reader.readAsDataURL(file);
}

// Confirmer nouvelle photo
function confirmPhoto() {
    if (!newPhotoFile) return;

    const formData = new FormData();
    formData.append('photo', newPhotoFile);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch('/admin/profil/photo', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('currentPhoto').src = data.new_photo_url;
            document.getElementById('photoPreview').style.display = 'none';
            newPhotoFile = null;
            showToast('Photo mise à jour avec succès', 'success');
        } else {
            showToast('Erreur: ' + (data.message || 'Erreur inconnue'), 'error');
        }
    })
    .catch(error => {
        showToast('Erreur lors de l\'upload', 'error');
        console.error('Erreur:', error);
    });
}

// Annuler changement photo
function cancelPhoto() {
    document.getElementById('photoPreview').style.display = 'none';
    newPhotoFile = null;
    document.getElementById('photoInput').value = '';
}

// Supprimer photo
function removePhoto() {
    if (confirm('Supprimer la photo de profil ?')) {
        fetch('/admin/profil/photo', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('currentPhoto').src = '/images/default-avatar.jpg';
                showToast('Photo supprimée avec succès', 'success');
            } else {
                showToast('Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors de la suppression', 'error');
            console.error('Erreur:', error);
        });
    }
}

// Vérifier force mot de passe
function checkPasswordStrength(password) {
    const bar = document.querySelector('.strength-bar');
    const text = document.querySelector('.strength-text');

    if (!bar || !text) return;

    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;

    const colors = ['#dc2626', '#f59e0b', '#fbbf24', '#10b981'];
    const texts = ['Très faible', 'Faible', 'Moyen', 'Fort'];

    bar.style.background = colors[strength] || '#dc2626';
    bar.style.width = `${(strength + 1) * 25}%`;
    text.textContent = texts[strength] || 'Très faible';
    text.style.color = colors[strength] || '#dc2626';
}

// Vérifier correspondance mots de passe
function checkPasswordMatch() {
    const password = document.getElementById('new_password').value;
    const confirm = document.getElementById('new_password_confirmation').value;
    const matchText = document.querySelector('.match-text');

    if (!matchText) return false;

    if (confirm && password !== confirm) {
        matchText.style.display = 'flex';
        return false;
    } else {
        matchText.style.display = 'none';
        return true;
    }
}

// Validation formulaire
function validateForm() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;

    if (newPassword && newPassword.length < 8) {
        showToast('Le mot de passe doit contenir au moins 8 caractères.', 'error');
        return false;
    }

    if (newPassword && newPassword !== confirmPassword) {
        showToast('Les mots de passe ne correspondent pas.', 'error');
        return false;
    }

    return true;
}

// Modal confirmation
function showConfirmationModal() {
    document.getElementById('confirmationModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

function submitForm() {
    document.getElementById('profilForm').submit();
}

// Réinitialiser formulaire
function resetForm() {
    if (confirm('Annuler toutes les modifications ?')) {
        document.getElementById('profilForm').reset();
        const bar = document.querySelector('.strength-bar');
        const text = document.querySelector('.strength-text');
        const matchText = document.querySelector('.match-text');

        if (bar) {
            bar.style.width = '0%';
            bar.style.background = '#ddd';
        }
        if (text) text.textContent = 'Non défini';
        if (matchText) matchText.style.display = 'none';

        showToast('Formulaire réinitialisé', 'info');
    }
}

// Toast notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `admin-toast admin-toast-${type}`;
    toast.innerHTML = `
        <span class="toast-icon">${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}</span>
        <span class="toast-message">${message}</span>
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
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/admin/profil.blade.php ENDPATH**/ ?>