<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ImmoLoc</title>

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* ===== VARIABLES CSS (similaires à home.blade.php) ===== */
        :root {
            /* Couleurs primaires - Identiques à ImmoLoc */
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

        /* ===== THÈME SOMBRE ===== */
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            color: var(--text-primary);
        }

        [data-theme="dark"] body {
            background: linear-gradient(135deg, var(--neutral-900) 0%, var(--neutral-800) 100%);
        }

        /* ===== CONTAINER PRINCIPAL ===== */
        .login-page {
            width: 100%;
            max-width: 440px;
            animation: fadeIn 0.6s ease;
        }

        /* ===== CARTE DE CONNEXION ===== */
        .login-card {
            background: var(--surface-0);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            padding: 2.5rem;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .login-card {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--secondary-500));
        }

        /* ===== HEADER DE LA CARTE ===== */
        .login-header {
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

        .login-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* ===== FORMULAIRE ===== */
        .login-form {
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
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

        .form-input {
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

        [data-theme="dark"] .form-input {
            background: var(--surface-100);
            border-color: var(--border-light);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: var(--surface-0);
        }

        [data-theme="dark"] .form-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.7;
        }

        /* ===== BOUTONS ===== */
        .btn-login {
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

        [data-theme="dark"] .btn-login {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-register {
            width: 100%;
            background: var(--surface-100);
            color: var(--text-primary);
            border: 2px solid var(--border-medium);
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
            text-decoration: none;
            margin-top: 1rem;
        }

        [data-theme="dark"] .btn-register {
            background: var(--surface-200);
            border-color: var(--border-light);
            color: var(--text-inverse);
        }

        .btn-register:hover {
            background: var(--surface-200);
            border-color: var(--primary-400);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ===== MESSAGES D'ERREUR ===== */
        .error-message {
            background: var(--error-50);
            color: var(--error-700);
            padding: 1rem;
            border-radius: var(--radius-lg);
            border-left: 4px solid var(--error-500);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        [data-theme="dark"] .error-message {
            background: var(--error-50);
            color: var(--error-300);
            border-left-color: var(--error-500);
        }

        .error-message i {
            color: var(--error-500);
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

        /* ===== LIENS UTILES ===== */
        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-light);
            font-size: 0.9rem;
        }

        .login-link {
            color: var(--primary-500);
            text-decoration: none;
            font-weight: 500;
            transition: color var(--transition-fast);
        }

        .login-link:hover {
            color: var(--primary-700);
            text-decoration: underline;
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

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .theme-toggle {
                top: 1rem;
                right: 1rem;
            }

            .login-links {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
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
    <div class="login-page">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <a href="{{ route('home') }}" class="logo">ImmoLoc</a>
                <h1 class="login-title">Connexion</h1>
                <p class="login-subtitle">Accédez à votre compte</p>
            </div>

            <!-- Messages d'erreur -->
            @if($errors->any())
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Formulaire -->
            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>
                        Adresse email
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-input"
                           placeholder="votre@email.com"
                           value="{{ old('email') }}"
                           required>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>
                        Mot de passe
                    </label>
                    <input type="password"
                           id="password"
                           name="mot_de_passe"
                           class="form-input"
                           placeholder="••••••••"
                           required>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </button>
            </form>

            <!-- Bouton d'inscription -->
            <a href="{{ route('register') }}" class="btn-register">
                <i class="fas fa-user-plus"></i>
                Créer un compte
            </a>

            
        </div>
    </div>

    <script>
        // Gestion du thème (simplifié)
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            let currentTheme = localStorage.getItem('theme') || 'light';

            // Appliquer le thème initial
            body.setAttribute('data-theme', currentTheme);
            updateThemeIcon();

            // Changer le thème au clic
            themeToggle.addEventListener('click', function() {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                body.setAttribute('data-theme', currentTheme);
                localStorage.setItem('theme', currentTheme);
                updateThemeIcon();

                // Animation du bouton
                themeToggle.style.transform = 'rotate(30deg) scale(1.1)';
                setTimeout(() => {
                    themeToggle.style.transform = '';
                }, 200);
            });

            // Mettre à jour l'icône
            function updateThemeIcon() {
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = currentTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
                }
            }

            // Focus sur le premier champ
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value) {
                setTimeout(() => emailInput.focus(), 100);
            }

            // Animation du formulaire à la soumission
            const loginForm = document.querySelector('.login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('.btn-login');
                    if (submitBtn) {
                        const originalHTML = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
                        submitBtn.disabled = true;

                        // Réactiver après 3 secondes (au cas où)
                        setTimeout(() => {
                            submitBtn.innerHTML = originalHTML;
                            submitBtn.disabled = false;
                        }, 3000);
                    }
                });
            }
        });
    </script>
</body>
</html>
