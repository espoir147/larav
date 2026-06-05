<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace Propriétaire - ImmoLoc</title>
    <style>
        /* ===== STYLES COMPLETS POUR PROPRIETAIRE ===== */

/* ===== VARIABLES CSS ===== */
:root {
    --proprio-primary: #1e40af;
    --proprio-primary-dark: #1e3a8a;
    --proprio-primary-light: #dbeafe;
    --proprio-primary-soft: #eff6ff;

    --proprio-secondary: #0891b2;
    --proprio-secondary-dark: #0e7490;
    --proprio-secondary-light: #cffafe;

    --proprio-success: #10b981;
    --proprio-danger: #ef4444;
    --proprio-warning: #f59e0b;
    --proprio-info: #3b82f6;

    --proprio-text-primary: #0f172a;
    --proprio-text-secondary: #475569;
    --proprio-text-tertiary: #64748b;
    --proprio-text-inverse: #ffffff;

    --proprio-bg-primary: #f8fafc;
    --proprio-bg-secondary: #f1f5f9;
    --proprio-bg-card: #ffffff;
    --proprio-bg-sidebar: #ffffff;

    --proprio-border-light: #e2e8f0;
    --proprio-border-medium: #cbd5e1;
    --proprio-border-focus: #1e40af;

    --proprio-shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
    --proprio-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --proprio-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    --proprio-shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);

    --space-xs: 0.25rem;
    --space-sm: 0.5rem;
    --space-md: 1rem;
    --space-lg: 1.5rem;
    --space-xl: 2rem;
    --space-2xl: 3rem;

    --radius-sm: 0.375rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-xl: 1rem;
    --radius-2xl: 1.5rem;
    --radius-full: 9999px;
}

/* ===== THÈME SOMBRE PROPRIETAIRE ===== */
[data-proprietaire-theme="dark"] {
    --proprio-primary: #60a5fa;
    --proprio-primary-dark: #3b82f6;
    --proprio-primary-light: #1e3a8a;
    --proprio-primary-soft: #1e293b;

    --proprio-secondary: #2dd4bf;
    --proprio-secondary-dark: #14b8a6;
    --proprio-secondary-light: #115e59;

    --proprio-text-primary: #f1f5f9;
    --proprio-text-secondary: #cbd5e1;
    --proprio-text-tertiary: #94a3b8;

    --proprio-bg-primary: #0f172a;
    --proprio-bg-secondary: #1e293b;
    --proprio-bg-card: #1e293b;
    --proprio-bg-sidebar: #1e293b;

    --proprio-border-light: #334155;
    --proprio-border-medium: #475569;
    --proprio-border-focus: #60a5fa;

    --proprio-dark-bg: #0f172a;
    --proprio-dark-card: #1e293b;
    --proprio-dark-text: #e2e8f0;
    --proprio-dark-border: #334155;
    --proprio-dark-accent: #3b82f6;
}

/* ===== RESET & BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.proprietaire-body {
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.5;
    min-height: 100vh;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* ===== HEADER PROPRIETAIRE - BLEU EN MODE CLAIR ===== */
.proprietaire-header {
    background: #1e3a8a; /* Bleu foncé pour mode clair */
    color: white;
    padding: 0.75rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 70px;
}

[data-proprietaire-theme="dark"] .proprietaire-header {
    background: var(--proprio-dark-card); /* Fond sombre pour mode dark */
    color: var(--proprio-dark-text);
    border-bottom: 1px solid var(--proprio-dark-border);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.proprietaire-header .logo {
    font-size: 2rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: var(--radius-lg);
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

[data-proprietaire-theme="dark"] .proprietaire-header .logo {
    background: var(--proprio-dark-bg);
    color: var(--proprio-dark-accent);
}

.proprietaire-header .logo:hover {
    transform: scale(1.1) rotate(5deg);
    background: var(--proprio-primary);
    color: white;
    box-shadow: var(--proprio-shadow-lg);
}

[data-proprietaire-theme="dark"] .proprietaire-header .logo:hover {
    background: var(--proprio-dark-accent);
    color: white;
}

.proprietaire-header .header-left h1 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
    color: white;
}

[data-proprietaire-theme="dark"] .proprietaire-header .header-left h1 {
    color: var(--proprio-dark-text);
    background: none;
    -webkit-text-fill-color: initial;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.proprietaire-header .user-name {
    font-weight: 500;
    color: white;
    font-size: 0.95rem;
}

[data-proprietaire-theme="dark"] .proprietaire-header .user-name {
    color: var(--proprio-dark-text);
}

.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
    transition: all 0.3s;
}

[data-proprietaire-theme="dark"] .user-avatar {
    border-color: var(--proprio-dark-accent);
}

.user-avatar:hover {
    transform: scale(1.05);
    border-color: var(--proprio-primary-dark);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

/* ===== CONTAINER PRINCIPAL ===== */
.proprietaire-container {
    display: flex;
    margin-top: 70px;
    min-height: calc(100vh - 70px);
}

/* ===== SIDEBAR ===== */
.proprietaire-sidebar {
    width: 280px;
    background: var(--proprio-bg-sidebar);
    border-right: 1px solid var(--proprio-border-light);
    padding: 2rem 1rem;
    position: fixed;
    height: calc(100vh - 70px);
    overflow-y: auto;
    transition: all 0.3s ease;
    z-index: 1100;
    box-shadow: 2px 0 10px rgba(0,0,0,0.02);
}

[data-proprietaire-theme="dark"] .proprietaire-sidebar {
    background: var(--proprio-dark-card);
    border-right-color: var(--proprio-dark-border);
}

.proprietaire-nav {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 1.2rem;
    color: var(--proprio-text-secondary);
    text-decoration: none;
    border-radius: var(--radius-lg);
    margin-bottom: 0.5rem;
    transition: all 0.3s;
    font-weight: 500;
    border: 1px solid transparent;
}

[data-proprietaire-theme="dark"] .nav-link {
    color: var(--proprio-dark-text);
}

.nav-link:hover {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    transform: translateX(5px);
    border-color: var(--proprio-primary-light);
}

[data-proprietaire-theme="dark"] .nav-link:hover {
    background: var(--proprio-dark-bg);
    color: var(--proprio-dark-accent);
}

.nav-link.active {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
    box-shadow: var(--proprio-shadow-md);
}

[data-proprietaire-theme="dark"] .nav-link.active {
    background: var(--proprio-dark-accent);
    color: white;
}

.nav-link .icon {
    font-size: 1.2rem;
    width: 24px;
    text-align: center;
}

.logout-form {
    margin-top: auto;
}

.logout-btn {
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    font-size: 1rem;
    width: 100%;
    color: var(--proprio-danger);
}

[data-proprietaire-theme="dark"] .logout-btn {
    color: #f87171;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--proprio-danger);
}

[data-proprietaire-theme="dark"] .logout-btn:hover {
    background: #7f1d1d;
    color: #fca5a5;
}

/* ===== CONTENU PRINCIPAL ===== */
.proprietaire-content {
    flex: 1;
    padding: 2rem;
    margin-left: 280px;
    background: var(--proprio-bg-primary);
    min-height: calc(100vh - 70px);
    transition: all 0.3s ease;
}

[data-proprietaire-theme="dark"] .proprietaire-content {
    background: var(--proprio-dark-bg);
}

/* ===== BOUTON THÈME ===== */
#proprietaire-theme-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    background: var(--proprio-bg-card);
    color: var(--proprio-text-primary);
    border: 2px solid var(--proprio-border-light);
    border-radius: 50%;
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: var(--proprio-shadow-lg);
    backdrop-filter: blur(8px);
}

#proprietaire-theme-toggle:hover {
    transform: rotate(180deg) scale(1.1);
    border-color: var(--proprio-primary);
    color: var(--proprio-primary);
    box-shadow: 0 0 20px rgba(30, 64, 175, 0.3);
}

[data-proprietaire-theme="dark"] #proprietaire-theme-toggle {
    background: var(--proprio-dark-card);
    color: var(--proprio-dark-text);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] #proprietaire-theme-toggle:hover {
    border-color: var(--proprio-dark-accent);
    color: var(--proprio-dark-accent);
}

/* ===== SCROLLBAR PERSONNALISÉE ===== */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-full);
}

[data-proprietaire-theme="dark"] ::-webkit-scrollbar-track {
    background: var(--proprio-dark-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--proprio-text-tertiary);
    border-radius: var(--radius-full);
    border: 2px solid var(--proprio-bg-secondary);
}

[data-proprietaire-theme="dark"] ::-webkit-scrollbar-thumb {
    background: var(--proprio-dark-border);
    border-color: var(--proprio-dark-bg);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--proprio-primary);
}

/* ===== TOAST NOTIFICATION ===== */
.proprio-toast {
    position: fixed;
    top: 90px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 10000;
    box-shadow: var(--proprio-shadow-lg);
    max-width: 350px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.1);
}

.proprio-toast.show {
    opacity: 1;
}

.proprio-toast-success {
    background: var(--proprio-success);
    color: white;
}

.proprio-toast-error {
    background: var(--proprio-danger);
    color: white;
}

.proprio-toast-info {
    background: var(--proprio-primary);
    color: white;
}

/* ===== LOADING SPINNER ===== */
.proprio-loading {
    display: inline-block;
    width: 24px;
    height: 24px;
    border: 3px solid var(--proprio-border-light);
    border-top-color: var(--proprio-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid transparent;
}

.badge-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border-color: var(--proprio-primary);
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    border-color: #fbbf24;
}

.badge-danger {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.badge-info {
    background: var(--proprio-secondary-light);
    color: var(--proprio-secondary);
    border-color: var(--proprio-secondary);
}

[data-proprietaire-theme="dark"] .badge-success {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
    border-color: #3b82f6;
}

[data-proprietaire-theme="dark"] .badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    border-color: #f59e0b;
}

[data-proprietaire-theme="dark"] .badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border-color: #ef4444;
}

[data-proprietaire-theme="dark"] .badge-info {
    background: rgba(45, 212, 191, 0.2);
    color: #2dd4bf;
    border-color: #14b8a6;
}

/* ===== MENU MOBILE ===== */
.proprio-menu-toggle {
    display: none;
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1200;
    background: var(--proprio-primary);
    color: white;
    border: none;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: var(--proprio-shadow-lg);
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.proprio-menu-toggle:hover {
    transform: scale(1.1);
    background: var(--proprio-primary-dark);
}

[data-proprietaire-theme="dark"] .proprio-menu-toggle {
    background: var(--proprio-dark-accent);
}

.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1050;
    opacity: 0;
    transition: opacity 0.3s;
}

.sidebar-overlay.active {
    display: block;
    opacity: 1;
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

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .proprietaire-sidebar {
        width: 240px;
    }
    .proprietaire-content {
        margin-left: 240px;
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .proprietaire-header {
        padding: 0.75rem 1rem;
    }

    .header-left h1 {
        font-size: 1.1rem;
    }

    .proprietaire-header .logo {
        width: 40px;
        height: 40px;
        font-size: 1.5rem;
    }

    .user-name {
        display: none;
    }

    .user-avatar {
        width: 38px;
        height: 38px;
    }

    .proprietaire-sidebar {
        position: fixed;
        left: -280px;
        width: 260px;
        transition: left 0.3s ease;
        box-shadow: var(--proprio-shadow-xl);
    }

    .proprietaire-sidebar.active {
        left: 0;
    }

    .proprio-menu-toggle {
        display: flex;
    }

    .proprietaire-content {
        margin-left: 0;
        padding: 1rem;
    }

    #proprietaire-theme-toggle {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .proprietaire-header {
        padding: 0.5rem 0.75rem;
    }

    .header-left h1 {
        font-size: 1rem;
    }

    .proprietaire-header .logo {
        width: 36px;
        height: 36px;
        font-size: 1.3rem;
    }

    .user-avatar {
        width: 34px;
        height: 34px;
    }

    .proprio-menu-toggle {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
        bottom: 15px;
        left: 15px;
    }

    #proprietaire-theme-toggle {
        bottom: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
    }
}
    </style>
</head>
<body class="proprietaire-body">
    <!-- Menu toggle pour mobile -->
    <button class="proprio-menu-toggle" id="menuToggle">☰</button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="proprietaire-header">
        <div class="header-left">
            <a href="{{ route('home') }}" class="logo">🏠</a>
            <h1>Espace Propriétaire</h1>
        </div>
        <div class="header-right">
            <span class="user-name">{{ Auth::user()->nom }}</span>
            <img id="headerAvatar" src="{{ Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) . '?v=' . time() : asset('images/default-avatar.jpg') }}" alt="Photo profil" class="user-avatar">
        </div>
    </header>

    <!-- Main Container -->
    <div class="proprietaire-container">
        <!-- Sidebar Menu -->
        <aside class="proprietaire-sidebar" id="sidebar">
            <nav class="proprietaire-nav">
                <a href="{{ route('proprietaire.dashboard') }}" class="nav-link">
                    <span class="icon">📊</span> Tableau de bord
                </a>
                <a href="{{ route('proprietaire.profil') }}" class="nav-link">
                    <span class="icon">👤</span> Profil
                </a>
                <a href="{{ route('proprietaire.biens') }}" class="nav-link">
                    <span class="icon">🏠</span> Mes Biens
                </a>
                <a href="{{ route('proprietaire.locataires') }}" class="nav-link">
                    <span class="icon">👥</span> Mes Locataires
                </a>
                <a href="{{ route('proprietaire.paiements') }}" class="nav-link">
                    <span class="icon">💰</span> Paiements
                </a>
                <a href="{{ route('proprietaire.payer-loyer') }}" class="nav-link">
                    <span class="icon">💳</span> Payer mes loyers
                </a>
                <a href="{{ route('proprietaire.messagerie') }}" class="nav-link">
                    <span class="icon">✉️</span> Messagerie
                </a>
                <a href="{{ route('proprietaire.annonces') }}" class="nav-link">
                    <span class="icon">📢</span> Annonces
                </a>

                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="nav-link logout-btn">
                        <span class="icon">🚪</span> Déconnexion
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Content Area -->
        <main class="proprietaire-content">
            @yield('proprietaire-content')
        </main>
    </div>

    <script>
        // ==================== VARIABLES GLOBALES ====================
        let currentTheme = localStorage.getItem('proprietaire-theme') || 'light';

        // ==================== GESTION DU THEME ====================
        function initTheme() {
            const themeToggle = document.getElementById('proprietaire-theme-toggle');
            if (!themeToggle) return;

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-proprietaire-theme', theme);
                localStorage.setItem('proprietaire-theme', theme);
                themeToggle.innerHTML = theme === 'light' ? '🌙' : '☀️';

                if (theme === 'light') {
                    themeToggle.style.background = 'rgba(30, 58, 138, 0.9)';
                    themeToggle.style.color = 'white';
                } else {
                    themeToggle.style.background = 'rgba(255, 255, 255, 0.9)';
                    themeToggle.style.color = '#1e3a8a';
                }
            }

            themeToggle.addEventListener('click', function() {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                applyTheme(currentTheme);

                this.style.transform = 'rotate(180deg) scale(0.8)';
                setTimeout(() => {
                    this.style.transform = 'rotate(0) scale(1)';
                }, 300);
            });

            applyTheme(currentTheme);
        }

        // ==================== GESTION DU MENU MOBILE ====================
        function initMobileMenu() {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (!menuToggle || !sidebar || !overlay) return;

            // Ouvrir/fermer le menu
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                menuToggle.textContent = sidebar.classList.contains('active') ? '✕' : '☰';
            });

            // Fermer en cliquant sur l'overlay
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                menuToggle.textContent = '☰';
            });

            // Fermer en cliquant sur un lien
            document.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    menuToggle.textContent = '☰';
                });
            });

            // Adapter au resize (rotation écran, redimensionnement)
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    menuToggle.textContent = '☰';
                }
            });
        }

        // ==================== GESTION DES LIENS ACTIFS ====================
        function setActiveLink() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link:not(.logout-btn)');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // ==================== TOAST NOTIFICATION ====================
        window.showToast = function(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `proprio-toast proprio-toast-${type}`;

            let icon = '✅';
            if (type === 'error') icon = '❌';
            if (type === 'info') icon = 'ℹ️';

            toast.innerHTML = `
                <span style="font-size: 1.2rem;">${icon}</span>
                <span style="flex: 1;">${message}</span>
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

        // ==================== INITIALISATION ====================
        document.addEventListener('DOMContentLoaded', function() {
            // Créer le bouton de thème s'il n'existe pas
            if (!document.getElementById('proprietaire-theme-toggle')) {
                const themeToggle = document.createElement('button');
                themeToggle.id = 'proprietaire-theme-toggle';
                document.body.appendChild(themeToggle);
            }

            initTheme();
            initMobileMenu();
            setActiveLink();

            // Gestion du redimensionnement
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    initMobileMenu();
                }, 250);
            });

            // Mise à jour de l'avatar si changé
            const avatarImg = document.getElementById('headerAvatar');
            if (avatarImg && localStorage.getItem('avatar-updated')) {
                avatarImg.src = localStorage.getItem('avatar-updated');
                localStorage.removeItem('avatar-updated');
            }
        });
    </script>
</body>
</html>
