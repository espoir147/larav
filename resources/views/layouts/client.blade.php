<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Espace Client - ImmoLoc</title>
    <style>
        /* ===== STYLES COMPLETS POUR CLIENT ===== */

/* ===== VARIABLES CSS ===== */
:root {
    --client-primary: #059669;
    --client-primary-dark: #047857;
    --client-primary-light: #d1fae5;
    --client-primary-soft: #ecfdf5;

    --client-secondary: #1e3a8a;
    --client-secondary-dark: #1e40af;
    --client-secondary-light: #dbeafe;

    --client-success: #10b981;
    --client-danger: #ef4444;
    --client-warning: #f59e0b;
    --client-info: #3b82f6;

    --client-text-primary: #0f172a;
    --client-text-secondary: #475569;
    --client-text-tertiary: #64748b;
    --client-text-inverse: #ffffff;

    --client-bg-primary: #f8fafc;
    --client-bg-secondary: #f1f5f9;
    --client-bg-card: #ffffff;
    --client-bg-sidebar: #ffffff;

    --client-border-light: #e2e8f0;
    --client-border-medium: #cbd5e1;
    --client-border-focus: #059669;

    --client-shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
    --client-shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1);
    --client-shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    --client-shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1);

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

/* ===== THÈME SOMBRE CLIENT ===== */
[data-client-theme="dark"] {
    --client-primary: #34d399;
    --client-primary-dark: #10b981;
    --client-primary-light: #064e3b;
    --client-primary-soft: #022c22;

    --client-secondary: #60a5fa;
    --client-secondary-dark: #3b82f6;
    --client-secondary-light: #1e3a8a;

    --client-text-primary: #f1f5f9;
    --client-text-secondary: #cbd5e1;
    --client-text-tertiary: #94a3b8;

    --client-bg-primary: #0f172a;
    --client-bg-secondary: #1e293b;
    --client-bg-card: #1e293b;
    --client-bg-sidebar: #1e293b;

    --client-border-light: #334155;
    --client-border-medium: #475569;
    --client-border-focus: #34d399;

    --client-dark-bg: #0f172a;
    --client-dark-card: #1e293b;
    --client-dark-text: #e2e8f0;
    --client-dark-border: #334155;
    --client-dark-accent: #34d399;
}

/* ===== RESET & BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.client-body {
    background: var(--client-bg-primary);
    color: var(--client-text-primary);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.5;
    min-height: 100vh;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* ===== HEADER CLIENT - VERT EN MODE CLAIR ===== */
.client-header {
    background: #166534; /* Vert foncé pour mode clair */
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

[data-client-theme="dark"] .client-header {
    background: var(--client-dark-card); /* Fond sombre pour mode dark */
    color: var(--client-dark-text);
    border-bottom: 1px solid var(--client-dark-border);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}

.header-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.client-header .logo {
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

[data-client-theme="dark"] .client-header .logo {
    background: var(--client-dark-bg);
    color: var(--client-dark-accent);
}

.client-header .logo:hover {
    transform: scale(1.1) rotate(5deg);
    background: var(--client-primary);
    color: white;
    box-shadow: var(--client-shadow-lg);
}

[data-client-theme="dark"] .client-header .logo:hover {
    background: var(--client-dark-accent);
    color: white;
}

.client-header .header-left h1 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
    color: white;
}

[data-client-theme="dark"] .client-header .header-left h1 {
    color: var(--client-dark-text);
    background: none;
    -webkit-text-fill-color: initial;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.client-header .user-name {
    font-weight: 500;
    color: white;
    font-size: 0.95rem;
}

[data-client-theme="dark"] .client-header .user-name {
    color: var(--client-dark-text);
}

.user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 2px solid white;
    object-fit: cover;
    transition: all 0.3s;
}

[data-client-theme="dark"] .user-avatar {
    border-color: var(--client-dark-accent);
}

.user-avatar:hover {
    transform: scale(1.05);
    border-color: var(--client-primary-dark);
    box-shadow: 0 0 0 3px var(--client-primary-light);
}

/* ===== CONTAINER PRINCIPAL ===== */
.client-container {
    display: flex;
    margin-top: 70px;
    min-height: calc(100vh - 70px);
}

/* ===== SIDEBAR ===== */
.client-sidebar {
    width: 280px;
    background: var(--client-bg-sidebar);
    border-right: 1px solid var(--client-border-light);
    padding: 2rem 1rem;
    position: fixed;
    height: calc(100vh - 70px);
    overflow-y: auto;
    transition: all 0.3s ease;
    z-index: 1100;
    box-shadow: 2px 0 10px rgba(0,0,0,0.02);
}

[data-client-theme="dark"] .client-sidebar {
    background: var(--client-dark-card);
    border-right-color: var(--client-dark-border);
}

.client-nav {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 1.2rem;
    color: var(--client-text-secondary);
    text-decoration: none;
    border-radius: var(--radius-lg);
    margin-bottom: 0.5rem;
    transition: all 0.3s;
    font-weight: 500;
    border: 1px solid transparent;
}

[data-client-theme="dark"] .nav-link {
    color: var(--client-dark-text);
}

.nav-link:hover {
    background: var(--client-primary-light);
    color: var(--client-primary);
    transform: translateX(5px);
    border-color: var(--client-primary-light);
}

[data-client-theme="dark"] .nav-link:hover {
    background: var(--client-dark-bg);
    color: var(--client-dark-accent);
}

.nav-link.active {
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
    box-shadow: var(--client-shadow-md);
}

[data-client-theme="dark"] .nav-link.active {
    background: var(--client-dark-accent);
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
    color: var(--client-danger);
}

[data-client-theme="dark"] .logout-btn {
    color: #f87171;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--client-danger);
}

[data-client-theme="dark"] .logout-btn:hover {
    background: #7f1d1d;
    color: #fca5a5;
}

/* ===== CONTENU PRINCIPAL ===== */
.client-content {
    flex: 1;
    padding: 2rem;
    margin-left: 280px;
    background: var(--client-bg-primary);
    min-height: calc(100vh - 70px);
    transition: all 0.3s ease;
}

[data-client-theme="dark"] .client-content {
    background: var(--client-dark-bg);
}

/* ===== BOUTON THÈME ===== */
#client-theme-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    background: var(--client-bg-card);
    color: var(--client-text-primary);
    border: 2px solid var(--client-border-light);
    border-radius: 50%;
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: var(--client-shadow-lg);
    backdrop-filter: blur(8px);
}

#client-theme-toggle:hover {
    transform: rotate(180deg) scale(1.1);
    border-color: var(--client-primary);
    color: var(--client-primary);
    box-shadow: 0 0 20px rgba(5, 150, 105, 0.3);
}

[data-client-theme="dark"] #client-theme-toggle {
    background: var(--client-dark-card);
    color: var(--client-dark-text);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] #client-theme-toggle:hover {
    border-color: var(--client-dark-accent);
    color: var(--client-dark-accent);
}

/* ===== SCROLLBAR PERSONNALISÉE ===== */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: var(--client-bg-secondary);
    border-radius: var(--radius-full);
}

[data-client-theme="dark"] ::-webkit-scrollbar-track {
    background: var(--client-dark-bg);
}

::-webkit-scrollbar-thumb {
    background: var(--client-text-tertiary);
    border-radius: var(--radius-full);
    border: 2px solid var(--client-bg-secondary);
}

[data-client-theme="dark"] ::-webkit-scrollbar-thumb {
    background: var(--client-dark-border);
    border-color: var(--client-dark-bg);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--client-primary);
}

/* ===== TOAST NOTIFICATION ===== */
.client-toast {
    position: fixed;
    top: 90px;
    right: 20px;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 10000;
    box-shadow: var(--client-shadow-lg);
    max-width: 350px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.1);
}

.client-toast.show {
    opacity: 1;
}

.client-toast-success {
    background: var(--client-success);
    color: white;
}

.client-toast-error {
    background: var(--client-danger);
    color: white;
}

.client-toast-info {
    background: var(--client-primary);
    color: white;
}

/* ===== LOADING SPINNER ===== */
.client-loading {
    display: inline-block;
    width: 24px;
    height: 24px;
    border: 3px solid var(--client-border-light);
    border-top-color: var(--client-primary);
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
    background: var(--client-primary-light);
    color: var(--client-primary);
    border-color: var(--client-primary);
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
    background: var(--client-secondary-light);
    color: var(--client-secondary);
    border-color: var(--client-secondary);
}

[data-client-theme="dark"] .badge-success {
    background: rgba(52, 211, 153, 0.2);
    color: #34d399;
    border-color: #34d399;
}

[data-client-theme="dark"] .badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    border-color: #f59e0b;
}

[data-client-theme="dark"] .badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border-color: #ef4444;
}

[data-client-theme="dark"] .badge-info {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
    border-color: #3b82f6;
}

/* ===== MENU MOBILE ===== */
.client-menu-toggle {
    display: none;
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 1200;
    background: var(--client-primary);
    color: white;
    border: none;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: var(--client-shadow-lg);
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
}

.client-menu-toggle:hover {
    transform: scale(1.1);
    background: var(--client-primary-dark);
}

[data-client-theme="dark"] .client-menu-toggle {
    background: var(--client-dark-accent);
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
    .client-sidebar {
        width: 240px;
    }
    .client-content {
        margin-left: 240px;
        padding: 1.5rem;
    }
}

@media (max-width: 768px) {
    .client-header {
        padding: 0.75rem 1rem;
    }

    .header-left h1 {
        font-size: 1.1rem;
    }

    .client-header .logo {
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

    .client-sidebar {
        position: fixed;
        left: -280px;
        width: 260px;
        transition: left 0.3s ease;
        box-shadow: var(--client-shadow-xl);
    }

    .client-sidebar.active {
        left: 0;
    }

    .client-menu-toggle {
        display: flex;
    }

    .client-content {
        margin-left: 0;
        padding: 1rem;
    }

    #client-theme-toggle {
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .client-header {
        padding: 0.5rem 0.75rem;
    }

    .header-left h1 {
        font-size: 1rem;
    }

    .client-header .logo {
        width: 36px;
        height: 36px;
        font-size: 1.3rem;
    }

    .user-avatar {
        width: 34px;
        height: 34px;
    }

    .client-menu-toggle {
        width: 48px;
        height: 48px;
        font-size: 1.3rem;
        bottom: 15px;
        left: 15px;
    }

    #client-theme-toggle {
        bottom: 15px;
        right: 15px;
        width: 40px;
        height: 40px;
    }
}
    </style>
</head>
<body class="client-body">
    <!-- Menu toggle pour mobile -->
    <button class="client-menu-toggle" id="menuToggle">☰</button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="client-header">
        <div class="header-left">
            <a href="{{ route('home') }}" class="logo">🏠</a>
            <h1>Espace Client</h1>
        </div>
        <div class="header-right">
            <span class="user-name">{{ Auth::user()->nom }}</span>
            <img src="{{ Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default-avatar.png') }}"
                 alt="Photo profil"
                 class="user-avatar"
                 id="headerAvatar">
        </div>
    </header>

    <!-- Main Container -->
    <div class="client-container">
        <!-- Sidebar Menu -->
        <aside class="client-sidebar" id="sidebar">
            <nav class="client-nav">
                <a href="{{ route('client.dashboard') }}" class="nav-link">
                    <span class="icon">📊</span> Tableau de bord
                </a>
                <a href="{{ route('client.profil') }}" class="nav-link">
                    <span class="icon">👤</span> Mon Profil
                </a>
                <a href="{{ route('client.locations') }}" class="nav-link">
                    <span class="icon">🏠</span> Mes Locations
                </a>
                <a href="{{ route('client.paiements') }}" class="nav-link">
                    <span class="icon">💰</span> Mes Paiements
                </a>
                <a href="{{ route('client.messagerie') }}" class="nav-link">
                    <span class="icon">✉️</span> Messagerie
                </a>
                <a href="{{ route('client.favoris') }}" class="nav-link">
                    <span class="icon">⭐</span> Favoris
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
        <main class="client-content">
            @yield('client-content')
        </main>
    </div>

    <script>
        // ==================== VARIABLES GLOBALES ====================
        let currentTheme = localStorage.getItem('client-theme') || 'light';

        // ==================== GESTION DU THEME ====================
        function initTheme() {
            const themeToggle = document.createElement('button');
            themeToggle.id = 'client-theme-toggle';
            themeToggle.setAttribute('aria-label', 'Changer de thème');
            document.body.appendChild(themeToggle);

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-client-theme', theme);
                localStorage.setItem('client-theme', theme);
                themeToggle.innerHTML = theme === 'light' ? '🌙' : '☀️';
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
            toast.className = `client-toast client-toast-${type}`;

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
