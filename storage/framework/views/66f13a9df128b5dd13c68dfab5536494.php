<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ImmoLoc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ===== VARIABLES CSS (cohérentes avec login.blade.php) ===== */
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-200: #bfdbfe;
            --primary-300: #93c5fd;
            --primary-400: #60a5fa;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-800: #1e40af;
            --primary-900: #1e3a8a;

            --secondary-500: #14b8a6;
            --secondary-600: #0d9488;

            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;

            --error-500: #ef4444;
            --error-50: #fef2f2;
            --error-700: #b91c1c;

            --surface-0: #ffffff;
            --surface-50: #f8fafc;
            --surface-100: #f1f5f9;

            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-inverse: #ffffff;

            --border-light: #e2e8f0;
            --border-medium: #cbd5e1;

            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);

            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;

            --transition-fast: 150ms ease;
            --transition-normal: 300ms ease;
        }

        /* ===== THÈME SOMBRE (IDENTIQUE À LOGIN) ===== */
        [data-theme="dark"] {
            --primary-500: #60a5fa;
            --primary-600: #3b82f6;
            --primary-700: #2563eb;

            --secondary-500: #2dd4bf;
            --secondary-600: #14b8a6;

            --neutral-50: #0f172a;
            --neutral-100: #1e293b;
            --neutral-200: #334155;
            --neutral-800: #f1f5f9;
            --neutral-900: #f8fafc;

            --error-500: #f87171;
            --error-50: #450a0a;
            --error-700: #dc2626;

            --surface-0: #0f172a;
            --surface-50: #1e293b;
            --surface-100: #334155;

            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-inverse: #0f172a;

            --border-light: #334155;
            --border-medium: #475569;

            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color var(--transition-normal),
                        border-color var(--transition-normal),
                        color var(--transition-normal);
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--primary-50) 0%, var(--neutral-100) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: var(--text-primary);
        }

        /* ===== BACKGROUND DARK MODE (IDENTIQUE À LOGIN) ===== */
        [data-theme="dark"] body {
            background: linear-gradient(135deg, var(--neutral-900) 0%, var(--neutral-800) 100%);
        }

        /* ===== CONTAINER PRINCIPAL ===== */
        .register-page {
            width: 100%;
            max-width: 900px;
            animation: fadeIn 0.6s ease;
        }

        /* ===== CARTE D'INSCRIPTION ===== */
        .register-card {
            background: var(--surface-0);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .register-card {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        }

        /* ===== HEADER ===== */
        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-600);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        [data-theme="dark"] .logo {
            color: var(--primary-400);
        }

        .logo::before {
            content: "🏠";
            font-size: 1.5rem;
        }

        .register-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* ===== MESSAGES D'ERREUR (APRÈS SUBMIT) ===== */
        .error-summary {
            background: var(--error-50);
            color: var(--error-700);
            padding: 1.25rem;
            border-radius: var(--radius-lg);
            border-left: 4px solid var(--error-500);
            margin-bottom: 2rem;
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        [data-theme="dark"] .error-summary {
            background: var(--error-50);
            color: var(--error-300);
            border-left-color: var(--error-500);
        }

        .error-summary i {
            color: var(--error-500);
            margin-top: 0.125rem;
            flex-shrink: 0;
        }

        .error-summary-content {
            flex: 1;
        }

        .error-summary strong {
            display: block;
            margin-bottom: 0.5rem;
        }

        .error-summary ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .error-summary li {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        /* ===== FORMULAIRE ===== */
        .register-form {
            width: 100%;
        }

        /* ===== COLONNES DU FORMULAIRE ===== */
        .form-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* ===== POUR MOBILE : 1 COLONNE ===== */
        @media (max-width: 768px) {
            .form-columns {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        .form-column {
            display: flex;
            flex-direction: column;
        }

        /* ===== GROUPES DE FORMULAIRE ===== */
        .form-group {
            margin-bottom: 1.5rem;
            width: 100%;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-label i {
            color: var(--primary-500);
            width: 16px;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-medium);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-family: inherit;
            background: var(--surface-50);
            color: var(--text-primary);
            transition: all var(--transition-fast);
        }

        [data-theme="dark"] .form-input,
        [data-theme="dark"] .form-select {
            background: var(--surface-100);
            border-color: var(--border-light);
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: var(--surface-0);
        }

        [data-theme="dark"] .form-input:focus,
        [data-theme="dark"] .form-select:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        /* ===== TÉLÉPHONE (ALIGNEMENT CORRECT) ===== */
        .phone-row {
            display: flex;
            gap: 0.75rem;
            width: 100%;
        }

        .phone-row .indicatif {
            flex: 0 0 100px;
        }

        .phone-row .numero {
            flex: 1;
        }

        /* ===== UPLOAD DE FICHIER ===== */
        .file-upload {
            margin-bottom: 2rem;
        }

        .upload-area {
            border: 2px dashed var(--border-medium);
            border-radius: var(--radius-lg);
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all var(--transition-normal);
            background: var(--surface-50);
            margin-top: 0.5rem;
        }

        [data-theme="dark"] .upload-area {
            background: var(--surface-100);
            border-color: var(--border-light);
        }

        .upload-area:hover {
            border-color: var(--primary-500);
            background: var(--surface-100);
            transform: translateY(-2px);
        }

        .upload-area i {
            font-size: 2rem;
            color: var(--primary-500);
            margin-bottom: 0.5rem;
            display: block;
        }

        .upload-area p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 0.95rem;
        }

        .file-preview {
            margin-top: 1rem;
        }

        .preview-image {
            position: relative;
            display: inline-block;
            max-width: 150px;
        }

        .preview-image img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            border: 2px solid var(--border-light);
        }

        .remove-file {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--error-500);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        /* ===== BOUTON D'INSCRIPTION ===== */
        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: var(--text-inverse);
            border: none;
            padding: 1rem;
            border-radius: var(--radius-lg);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all var(--transition-normal);
            margin-top: 0.5rem;
        }

        [data-theme="dark"] .btn-register {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        /* ===== LIEN VERS CONNEXION ===== */
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .login-link a {
            color: var(--primary-500);
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.5rem;
            transition: color var(--transition-fast);
        }

        .login-link a:hover {
            color: var(--primary-700);
            text-decoration: underline;
        }

        /* ===== BOUTON THÈME ===== */
        .theme-toggle {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            background: var(--surface-0);
            border: 1px solid var(--border-light);
            color: var(--text-primary);
            width: 44px;
            height: 44px;
            border-radius: var(--radius-full);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            transition: all var(--transition-normal);
            box-shadow: var(--shadow-md);
            z-index: 100;
        }

        [data-theme="dark"] .theme-toggle {
            background: var(--surface-50);
            border-color: var(--border-medium);
        }

        .theme-toggle:hover {
            transform: rotate(15deg) scale(1.1);
            box-shadow: var(--shadow-lg);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE COMPLET ===== */
        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
                align-items: flex-start;
                padding-top: 4rem;
            }

            .register-page {
                max-width: 100%;
            }

            .register-card {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            .register-title {
                font-size: 1.5rem;
            }

            .theme-toggle {
                top: 1rem;
                right: 1rem;
                width: 40px;
                height: 40px;
            }

            .form-columns {
                gap: 0;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .phone-row {
                flex-direction: column;
                gap: 0.75rem;
            }

            .phone-row .indicatif,
            .phone-row .numero {
                flex: 1;
                width: 100%;
            }

            .upload-area {
                padding: 1.5rem;
            }

            .upload-area i {
                font-size: 1.5rem;
            }

            .error-summary {
                padding: 1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .error-summary ul {
                padding-left: 1rem;
            }
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 1.25rem;
            }

            .logo {
                font-size: 1.5rem;
            }

            .register-title {
                font-size: 1.35rem;
            }

            .form-input,
            .form-select {
                padding: 0.75rem;
            }

            .btn-register {
                padding: 0.875rem;
            }
        }
    </style>
</head>

<body>
    <!-- Bouton thème -->
    <button class="theme-toggle" id="themeToggle" aria-label="Changer de thème">
        <i class="fas fa-moon"></i>
    </button>

    <!-- Contenu principal -->
    <div class="register-page">
        <div class="register-card">
            <!-- Header -->
            <div class="register-header">
                <a href="<?php echo e(route('home')); ?>" class="logo">ImmoLoc</a>
                <h1 class="register-title">Créer un compte</h1>
                <p class="register-subtitle">Rejoignez notre plateforme immobilière</p>
            </div>

            <!-- Messages d'erreur (uniquement après submit) -->
            <?php if($errors->any()): ?>
                <div class="error-summary">
                    <i class="fas fa-exclamation-circle"></i>
                    <div class="error-summary-content">
                        <strong>Veuillez corriger les erreurs suivantes :</strong>
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <form action="<?php echo e(route('register')); ?>" method="POST" enctype="multipart/form-data" class="register-form" id="registerForm">
                <?php echo csrf_field(); ?>

                <div class="form-columns">
                    <!-- Colonne gauche -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="nom" class="form-label">
                                <i class="fas fa-user"></i>
                                Nom complet
                            </label>
                            <input type="text"
                                   id="nom"
                                   name="nom"
                                   class="form-input"
                                   value="<?php echo e(old('nom')); ?>"
                                   placeholder="Votre nom complet">
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-input"
                                   value="<?php echo e(old('email')); ?>"
                                   placeholder="votre@email.com">
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Téléphone
                            </label>
                            <div class="phone-row">
                                <input type="text"
                                       name="indicatif_pays"
                                       class="form-input indicatif"
                                       value="<?php echo e(old('indicatif_pays', '+229')); ?>"
                                       placeholder="+229">
                                <input type="tel"
                                       name="telephone"
                                       class="form-input numero"
                                       value="<?php echo e(old('telephone')); ?>"
                                       placeholder="XXXXXXXXXX">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date_naissance" class="form-label">
                                <i class="fas fa-calendar-alt"></i>
                                Date de naissance
                            </label>
                            <input type="date"
                                   id="date_naissance"
                                   name="date_naissance"
                                   class="form-input"
                                   value="<?php echo e(old('date_naissance')); ?>">
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="form-column">
                        <div class="form-group">
                            <label for="type" class="form-label">
                                <i class="fas fa-user-tag"></i>
                                Rôle
                            </label>
                            <select id="type" name="type" class="form-select">
                                <option value="">-- Choisissez un rôle --</option>
                                <option value="client" <?php echo e(old('type') == 'client' ? 'selected' : ''); ?>>
                                    Client
                                </option>
                                <option value="proprietaire" <?php echo e(old('type') == 'proprietaire' ? 'selected' : ''); ?>>
                                    Propriétaire
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="mot_de_passe" class="form-label">
                                <i class="fas fa-lock"></i>
                                Mot de passe
                            </label>
                            <input type="password"
                                   id="mot_de_passe"
                                   name="mot_de_passe"
                                   class="form-input"
                                   placeholder="••••••••">
                        </div>

                        <div class="form-group">
                            <label for="mot_de_passe_confirmation" class="form-label">
                                <i class="fas fa-lock"></i>
                                Confirmer le mot de passe
                            </label>
                            <input type="password"
                                   id="mot_de_passe_confirmation"
                                   name="mot_de_passe_confirmation"
                                   class="form-input"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <!-- Photo de profil -->
                <div class="form-group file-upload">
                    <label for="photo_profil" class="form-label">
                        <i class="fas fa-camera"></i>
                        Photo de profil
                    </label>
                    <div class="upload-area" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Cliquez pour sélectionner une photo</p>
                        <input type="file"
                               id="photo_profil"
                               name="photo_profil"
                               accept="image/*"
                               hidden>
                    </div>
                    <div class="file-preview" id="filePreview"></div>
                </div>

                <!-- Bouton d'inscription -->
                <button type="submit" class="btn-register" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    Créer mon compte
                </button>

                <!-- Lien vers connexion -->
                <div class="login-link">
                    Déjà un compte ?
                    <a href="<?php echo e(route('login')); ?>">Se connecter</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== GESTION DU THÈME =====
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            let currentTheme = localStorage.getItem('theme') || 'light';

            function applyTheme() {
                body.setAttribute('data-theme', currentTheme);
                localStorage.setItem('theme', currentTheme);

                // Mettre à jour l'icône
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = currentTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
                }
            }

            // Initialiser le thème
            applyTheme();

            // Changer le thème au clic
            themeToggle.addEventListener('click', function() {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                body.setAttribute('data-theme', currentTheme);
                localStorage.setItem('theme', currentTheme);

                // Animation du bouton
                themeToggle.style.transform = 'rotate(30deg) scale(1.1)';
                setTimeout(() => {
                    themeToggle.style.transform = '';
                    applyTheme();
                }, 200);
            });

            // ===== UPLOAD DE FICHIER AVEC PREVIEW =====
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('photo_profil');
            const filePreview = document.getElementById('filePreview');

            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Vérifier la taille (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('La photo ne doit pas dépasser 5MB');
                        fileInput.value = '';
                        return;
                    }

                    // Vérifier le type
                    if (!file.type.startsWith('image/')) {
                        alert('Veuillez sélectionner une image');
                        fileInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        filePreview.innerHTML = `
                            <div class="preview-image">
                                <img src="${e.target.result}" alt="Preview de la photo">
                                <button type="button" class="remove-file" aria-label="Supprimer la photo">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;

                        // Ajouter l'événement pour supprimer
                        document.querySelector('.remove-file').addEventListener('click', function() {
                            fileInput.value = '';
                            filePreview.innerHTML = '';
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            // ===== GESTION DE LA SOUMISSION =====
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                // Animation du bouton
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création du compte...';
                submitBtn.disabled = true;

                // Réactiver après 5 secondes (au cas où)
                setTimeout(() => {
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }, 5000);
            });

            // ===== FOCUS SUR LE PREMIER CHAMP =====
            const firstInput = document.querySelector('.form-input');
            if (firstInput && !firstInput.value) {
                setTimeout(() => firstInput.focus(), 100);
            }
        });
    </script>
</body>
</html>
<?php /**PATH /home/espoir/larav/resources/views/auth/register.blade.php ENDPATH**/ ?>