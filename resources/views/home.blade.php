<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ImmoLoc - Trouvez votre logement idéal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        /* Styles inline pour la démo - À déplacer dans CSS */
        .modal-map {
            height: 300px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            margin: 1rem 0;
        }
        /* ===== VARIABLES CSS ===== */
        :root {
            /* ===== LIGHT MODE ===== */
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

            --secondary-50: #f0fdfa;
            --secondary-100: #ccfbf1;
            --secondary-200: #99f6e4;
            --secondary-300: #5eead4;
            --secondary-400: #2dd4bf;
            --secondary-500: #14b8a6;
            --secondary-600: #0d9488;
            --secondary-700: #0f766e;
            --secondary-800: #115e59;
            --secondary-900: #134e4a;

            --accent-50: #faf5ff;
            --accent-100: #f3e8ff;
            --accent-200: #e9d5ff;
            --accent-300: #d8b4fe;
            --accent-400: #c084fc;
            --accent-500: #a855f7;
            --accent-600: #9333ea;
            --accent-700: #7e22ce;
            --accent-800: #6b21a8;
            --accent-900: #581c87;

            --neutral-50: #f8fafc;
            --neutral-100: #f1f5f9;
            --neutral-200: #e2e8f0;
            --neutral-300: #cbd5e1;
            --neutral-400: #94a3b8;
            --neutral-500: #64748b;
            --neutral-600: #475569;
            --neutral-700: #334155;
            --neutral-800: #1e293b;
            --neutral-900: #0f172a;

            --success-50: #f0fdf4;
            --success-500: #22c55e;
            --success-700: #15803d;

            --warning-50: #fefce8;
            --warning-500: #eab308;
            --warning-700: #a16207;

            --error-50: #fef2f2;
            --error-500: #ef4444;
            --error-700: #b91c1c;

            --surface-0: #ffffff;
            --surface-50: #f8fafc;
            --surface-100: #f1f5f9;
            --surface-200: #e2e8f0;
            --surface-300: #cbd5e1;

            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #64748b;
            --text-inverse: #ffffff;

            --border-light: #e2e8f0;
            --border-medium: #cbd5e1;
            --border-strong: #94a3b8;

            --shadow-color: rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 1px 3px var(--shadow-color);
            --shadow-md: 0 4px 6px -1px var(--shadow-color);
            --shadow-lg: 0 10px 15px -3px var(--shadow-color);
            --shadow-xl: 0 20px 25px -5px var(--shadow-color);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);

            --overlay-light: rgba(255, 255, 255, 0.8);
            --overlay-medium: rgba(0, 0, 0, 0.5);
            --overlay-dark: rgba(0, 0, 0, 0.75);

            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
            --radius-2xl: 1.5rem;
            --radius-full: 9999px;

            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-normal: 300ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 500ms cubic-bezier(0.4, 0, 0.2, 1);

            /* Spacing */
            --space-xs: 0.25rem;
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            --space-xl: 2rem;
            --space-2xl: 3rem;
        }

        /* ===== DARK MODE ===== */
        [data-theme="dark"] {
            /* Couleurs primaires inversées */
            --primary-50: #1e3a8a;
            --primary-100: #1e40af;
            --primary-200: #1d4ed8;
            --primary-300: #2563eb;
            --primary-400: #3b82f6;
            --primary-500: #60a5fa;
            --primary-600: #93c5fd;
            --primary-700: #bfdbfe;
            --primary-800: #dbeafe;
            --primary-900: #eff6ff;

            /* Couleurs secondaires */
            --secondary-50: #134e4a;
            --secondary-100: #115e59;
            --secondary-200: #0f766e;
            --secondary-300: #0d9488;
            --secondary-400: #14b8a6;
            --secondary-500: #2dd4bf;
            --secondary-600: #5eead4;
            --secondary-700: #99f6e4;
            --secondary-800: #ccfbf1;
            --secondary-900: #f0fdfa;

            /* Accents */
            --accent-50: #581c87;
            --accent-100: #6b21a8;
            --accent-200: #7e22ce;
            --accent-300: #9333ea;
            --accent-400: #a855f7;
            --accent-500: #c084fc;
            --accent-600: #d8b4fe;
            --accent-700: #e9d5ff;
            --accent-800: #f3e8ff;
            --accent-900: #faf5ff;

            /* Neutres inversés complètement */
            --neutral-50: #0f172a;
            --neutral-100: #1e293b;
            --neutral-200: #334155;
            --neutral-300: #475569;
            --neutral-400: #64748b;
            --neutral-500: #94a3b8;
            --neutral-600: #cbd5e1;
            --neutral-700: #e2e8f0;
            --neutral-800: #f1f5f9;
            --neutral-900: #f8fafc;

            /* Status colors */
            --success-50: #052e16;
            --success-500: #16a34a;
            --success-700: #4ade80;

            --warning-50: #422006;
            --warning-500: #d97706;
            --warning-700: #fbbf24;

            --error-50: #450a0a;
            --error-500: #dc2626;
            --error-700: #f87171;

            /* Surfaces - tout est sombre */
            --surface-0: #0f172a;
            --surface-50: #1e293b;
            --surface-100: #334155;
            --surface-200: #475569;
            --surface-300: #64748b;

            /* Texte - clair sur fond sombre */
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-tertiary: #94a3b8;
            --text-inverse: #0f172a;

            /* Bordures adaptées */
            --border-light: #334155;
            --border-medium: #475569;
            --border-strong: #64748b;

            /* Ombres adaptées au dark */
            --shadow-color: rgba(0, 0, 0, 0.3);
            --shadow-sm: 0 1px 3px var(--shadow-color);
            --shadow-md: 0 4px 6px -1px var(--shadow-color);
            --shadow-lg: 0 10px 15px -3px var(--shadow-color);
            --shadow-xl: 0 20px 25px -5px var(--shadow-color);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);

            /* Overlays adaptés */
            --overlay-light: rgba(15, 23, 42, 0.8);
            --overlay-medium: rgba(0, 0, 0, 0.7);
            --overlay-dark: rgba(0, 0, 0, 0.9);
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
            background-color: var(--surface-0);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            transition: background-color var(--transition-normal),
                        color var(--transition-normal);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--space-lg);
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 var(--space-md);
            }
        }

        /* ===== HEADER ===== */
        .header {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            padding: var(--space-md) 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-lg);
            transition: background var(--transition-normal);
        }

        [data-theme="dark"] .header {
            background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-900) 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Logo */
        .logo {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text-inverse);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            transition: transform var(--transition-fast);
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo::before {
            content: "🏠";
            font-size: 1.5rem;
        }

        /* Navigation Desktop */
        .nav-desktop {
            display: flex;
            gap: var(--space-lg);
            align-items: center;
        }

        @media (max-width: 768px) {
            .nav-desktop {
                display: none;
            }
        }

        .nav-link {
            color: var(--text-inverse);
            text-decoration: none;
            font-weight: 500;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            position: relative;
            overflow: hidden;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--text-inverse);
            transition: all var(--transition-normal);
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 80%;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        /* Bouton Connexion */
        .btn-connexion {
            background: var(--text-inverse);
            color: var(--primary-600);
            padding: var(--space-sm) var(--space-lg);
            border-radius: var(--radius-lg);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            transition: all var(--transition-normal);
            box-shadow: var(--shadow-md);
            border: 2px solid transparent;
        }

        [data-theme="dark"] .btn-connexion {
            background: var(--neutral-800);
            color: var(--primary-300);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .btn-connexion:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--neutral-100);
            color: var(--primary-700);
            border-color: var(--primary-400);
        }

        [data-theme="dark"] .btn-connexion:hover {
            background: var(--neutral-700);
            color: var(--primary-200);
            border-color: var(--primary-500);
        }

        /* Menu Burger Button */
        .menu-burger-btn {
            display: none;
            background: none;
            border: none;
            color: var(--text-inverse);
            font-size: 1.5rem;
            cursor: pointer;
            padding: var(--space-sm);
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-burger-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: rotate(90deg);
        }

        @media (max-width: 768px) {
            .menu-burger-btn {
                display: flex;
            }
        }

        /* Menu Mobile */
        .mobile-menu {
            position: fixed;
            top: 0;
            left: -300px;
            width: 280px;
            height: 100vh;
            background: var(--surface-0);
            z-index: 1001;
            transition: transform var(--transition-normal);
            box-shadow: var(--shadow-2xl);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-light);
        }

        .mobile-menu.active {
            transform: translateX(300px);
        }

        .mobile-menu-header {
            padding: var(--space-lg);
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: var(--text-inverse);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        [data-theme="dark"] .mobile-menu-header {
            background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-900) 100%);
        }

        .mobile-menu-header h3 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .close-menu-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: var(--text-inverse);
            font-size: 1.25rem;
            cursor: pointer;
            padding: var(--space-sm);
            border-radius: var(--radius-full);
            transition: all var(--transition-fast);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-menu-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(90deg);
        }

        .mobile-menu-content {
            padding: var(--space-lg);
            flex: 1;
            overflow-y: auto;
            background: var(--surface-50);
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            padding: var(--space-md);
            color: var(--text-primary);
            text-decoration: none;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            margin-bottom: var(--space-sm);
            background: var(--surface-100);
            border: 1px solid var(--border-light);
        }

        .mobile-nav-link:hover {
            background: var(--surface-200);
            color: var(--primary-600);
            transform: translateX(var(--space-sm));
            border-color: var(--primary-400);
        }

        .mobile-nav-link i {
            color: var(--primary-500);
            width: 20px;
            text-align: center;
        }

        .menu-divider {
            border: none;
            height: 1px;
            background: var(--border-light);
            margin: var(--space-lg) 0;
        }

        /* Menu Overlay */
        .menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--overlay-dark);
            backdrop-filter: blur(4px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-normal);
        }

        .menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Theme Toggle */
        .theme-toggle {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: var(--text-inverse);
            width: 44px;
            height: 44px;
            border-radius: var(--radius-full);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        .theme-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: opacity var(--transition-fast);
        }

        .theme-toggle:hover::before {
            opacity: 1;
        }

        .theme-toggle:hover {
            transform: rotate(30deg) scale(1.1);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(var(--overlay-dark), var(--overlay-dark)),
                        url('/images/appartement.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 80vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        [data-theme="dark"] .hero {
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)),
                        url('/images/appartement.jpeg');
        }

        .hero-overlay {
            width: 100%;
            padding: var(--space-2xl) var(--space-md);
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            color: var(--text-inverse);
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: var(--space-md);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-inverse) 0%, var(--primary-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        [data-theme="dark"] .hero-title {
            background: linear-gradient(135deg, var(--text-inverse) 0%, var(--primary-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: var(--space-2xl);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: var(--neutral-200);
        }

        [data-theme="dark"] .hero-subtitle {
            color: var(--neutral-300);
        }

        /* Indicateurs de confiance */
        .trust-indicators {
            display: flex;
            justify-content: center;
            gap: var(--space-2xl);
            margin: var(--space-2xl) 0;
            flex-wrap: wrap;
        }

        .trust-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: var(--space-lg);
            background: rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-xl);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-width: 150px;
            transition: all var(--transition-normal);
        }

        [data-theme="dark"] .trust-item {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(255, 255, 255, 0.1);
        }

        .trust-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .trust-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-inverse);
            margin-bottom: var(--space-xs);
            line-height: 1;
        }

        .trust-text {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--neutral-200);
        }

        /* Formulaire de recherche */
        .search-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .search-form {
            background: var(--surface-0);
            padding: var(--space-2xl);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-2xl);
            border: 1px solid var(--border-light);
        }

        [data-theme="dark"] .search-form {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .search-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        @media (max-width: 1024px) {
            .search-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .search-grid {
                grid-template-columns: 1fr;
            }
        }

        .search-field {
            position: relative;
        }

        .search-field i {
            position: absolute;
            left: var(--space-md);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
            z-index: 1;
        }

        .search-input {
            width: 100%;
            padding: var(--space-md) var(--space-md) var(--space-md) calc(var(--space-md) * 2 + 16px);
            border: 2px solid var(--border-medium);
            border-radius: var(--radius-lg);
            font-size: 1rem;
            transition: all var(--transition-fast);
            background: var(--surface-50);
            color: var(--text-primary);
        }

        [data-theme="dark"] .search-input {
            background: var(--surface-100);
            border-color: var(--border-strong);
        }

        .search-input::placeholder {
            color: var(--text-tertiary);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: var(--surface-0);
        }

        [data-theme="dark"] .search-input:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            background: var(--surface-50);
        }

        .search-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--secondary-500) 0%, var(--secondary-600) 100%);
            color: var(--text-inverse);
            border: none;
            padding: var(--space-md);
            border-radius: var(--radius-lg);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            transition: all var(--transition-normal);
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .search-btn {
            background: linear-gradient(135deg, var(--secondary-600) 0%, var(--secondary-700) 100%);
        }

        .search-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .search-btn:hover::before {
            left: 100%;
        }

        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-xl);
        }

        /* ===== SECTION À PROPOS ===== */
        .about-section {
            padding: var(--space-2xl) var(--space-md);
            background: var(--surface-50);
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-light), transparent);
        }

        [data-theme="dark"] .about-section {
            background: var(--surface-100);
        }

        .section-header {
            text-align: center;
            margin-bottom: var(--space-2xl);
            position: relative;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: var(--space-md);
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--accent-500));
            border-radius: 2px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .about-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-xl);
            max-width: 1000px;
            margin: 0 auto;
        }

        .about-card {
            background: var(--surface-0);
            padding: var(--space-xl);
            border-radius: var(--radius-xl);
            text-align: center;
            box-shadow: var(--shadow-lg);
            transition: all var(--transition-normal);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .about-card {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .about-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-500), var(--accent-500));
            opacity: 0;
            transition: opacity var(--transition-normal);
        }

        .about-card:hover::before {
            opacity: 1;
        }

        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-400);
        }

        .about-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-lg);
            font-size: 2rem;
            color: var(--text-inverse);
            transition: all var(--transition-normal);
        }

        .about-card:hover .about-icon {
            transform: scale(1.1) rotate(10deg);
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        }

        .about-card h3 {
            font-size: 1.5rem;
            margin-bottom: var(--space-md);
            color: var(--text-primary);
        }

        .about-card p {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* ===== SECTION PROPRIÉTÉS ===== */
        .properties-section {
            padding: var(--space-2xl) var(--space-md);
            background: var(--surface-0);
            position: relative;
        }

        .properties-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-light), transparent);
        }

        .no-properties {
            text-align: center;
            padding: var(--space-2xl);
            background: var(--surface-50);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-lg);
            border: 2px dashed var(--border-medium);
            max-width: 600px;
            margin: 0 auto;
        }

        [data-theme="dark"] .no-properties {
            background: var(--surface-100);
            border-color: var(--border-strong);
        }

        .no-properties i {
            color: var(--text-tertiary);
            margin-bottom: var(--space-lg);
            opacity: 0.5;
        }

        .no-properties h3 {
            color: var(--text-primary);
            margin-bottom: var(--space-md);
            font-size: 1.5rem;
        }

        .no-properties p {
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        .properties-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: var(--space-xl);
            margin-top: var(--space-xl);
        }

        @media (max-width: 768px) {
            .properties-grid {
                grid-template-columns: 1fr;
                gap: var(--space-lg);
            }
        }

        /* Carte de propriété */
        .property-card {
            background: var(--surface-0);
            border-radius: var(--radius-xl);
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            transition: all var(--transition-normal);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-light);
        }

        [data-theme="dark"] .property-card {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .property-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-400);
        }

        /* Badge */
        .property-badge {
            position: absolute;
            top: var(--space-md);
            right: var(--space-md);
            background: linear-gradient(135deg, var(--secondary-500) 0%, var(--secondary-600) 100%);
            color: var(--text-inverse);
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-full);
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            box-shadow: var(--shadow-md);
            border: 2px solid var(--surface-0);
        }

        [data-theme="dark"] .property-badge {
            border-color: var(--surface-50);
        }

        .property-badge i {
            font-size: 0.7rem;
        }

        /* Image */
        .property-image-container {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: var(--surface-100);
        }

        .property-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
            background: var(--surface-200);
        }

        .property-card:hover .property-image {
            transform: scale(1.1);
        }

        .property-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 50%, transparent 100%);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            transition: opacity var(--transition-normal);
            padding: var(--space-lg);
        }

        .property-card:hover .property-image-overlay {
            opacity: 1;
        }

        .view-gallery-btn {
            background: var(--surface-0);
            color: var(--text-primary);
            border: none;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-full);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            transition: all var(--transition-fast);
            border: 2px solid var(--border-light);
        }

        [data-theme="dark"] .view-gallery-btn {
            background: var(--surface-100);
            color: var(--text-inverse);
            border-color: var(--border-strong);
        }

        .view-gallery-btn:hover {
            background: var(--primary-500);
            color: var(--text-inverse);
            transform: scale(1.05);
            border-color: var(--primary-500);
        }

        /* Contenu */
        .property-content {
            padding: var(--space-lg);
            flex: 1;
            display: flex;
            flex-direction: column;
            background: var(--surface-0);
        }

        [data-theme="dark"] .property-content {
            background: var(--surface-50);
        }

        .property-location {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--text-secondary);
            font-size: 0.9rem;
            margin-bottom: var(--space-xs);
        }

        .property-location i {
            color: var(--primary-500);
        }

        .property-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--space-md);
            line-height: 1.3;
        }

        .property-features {
            display: flex;
            gap: var(--space-md);
            margin-bottom: var(--space-md);
            padding-bottom: var(--space-md);
            border-bottom: 1px solid var(--border-light);
        }

        [data-theme="dark"] .property-features {
            border-bottom-color: var(--border-medium);
        }

        .feature {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            font-size: 0.9rem;
            color: var(--text-secondary);
            flex: 1;
        }

        .feature i {
            color: var(--primary-500);
            font-size: 0.9rem;
        }

        .property-description {
            color: var(--text-secondary);
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: var(--space-md);
            flex: 1;
        }

        /* Prix */
        .property-price {
            margin-bottom: var(--space-lg);
            padding: var(--space-md);
            background: var(--surface-50);
            border-radius: var(--radius-lg);
            border-left: 4px solid var(--primary-500);
        }

        [data-theme="dark"] .property-price {
            background: var(--surface-100);
        }

        .price-amount {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-600);
            display: block;
            line-height: 1;
        }

        [data-theme="dark"] .price-amount {
            color: var(--primary-400);
        }

        .price-period {
            font-size: 0.9rem;
            color: var(--text-tertiary);
            display: block;
            margin-top: var(--space-xs);
        }

        /* Actions - 2 BOUTONS */
        .property-actions {
            display: flex;
            gap: var(--space-sm);
            margin-top: auto;
        }

        .btn-details, .btn-contact, .btn-register {
            flex: 1;
            padding: var(--space-md);
            border-radius: var(--radius-lg);
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-sm);
            transition: all var(--transition-normal);
            border: 2px solid transparent;
            font-size: 0.9rem;
            text-decoration: none;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .btn-details {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            color: var(--text-inverse);
        }

        [data-theme="dark"] .btn-details {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
        }

        .btn-contact {
            background: linear-gradient(135deg, var(--secondary-500) 0%, var(--secondary-600) 100%);
            color: var(--text-inverse);
        }

        [data-theme="dark"] .btn-contact {
            background: linear-gradient(135deg, var(--secondary-600) 0%, var(--secondary-700) 100%);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--accent-500) 0%, var(--accent-600) 100%);
            color: var(--text-inverse);
        }

        [data-theme="dark"] .btn-register {
            background: linear-gradient(135deg, var(--accent-600) 0%, var(--accent-700) 100%);
        }

        .btn-details:hover, .btn-contact:hover, .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
            border-color: var(--text-inverse);
        }

        @media (max-width: 480px) {
            .property-actions {
                flex-direction: column;
            }
        }

        /* ===== MODALS ===== */
        .modal-content {
            border-radius: var(--radius-2xl);
            border: 1px solid var(--border-light);
            background: var(--surface-0);
            color: var(--text-primary);
            box-shadow: var(--shadow-2xl);
        }

        [data-theme="dark"] .modal-content {
            background: var(--surface-50);
            border-color: var(--border-medium);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: var(--text-inverse);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: var(--space-lg) var(--space-xl);
            border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
        }

        [data-theme="dark"] .modal-header {
            background: linear-gradient(135deg, var(--primary-800) 0%, var(--primary-900) 100%);
        }

        .modal-title {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .btn-close {
            filter: invert(1) brightness(2);
            opacity: 0.8;
            transition: opacity var(--transition-fast);
        }

        [data-theme="dark"] .btn-close {
            filter: invert(1) brightness(1);
        }

        .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: var(--space-xl);
            background: var(--surface-50);
        }

        [data-theme="dark"] .modal-body {
            background: var(--surface-100);
        }

        /* Onglets */
        #propertyTabs {
            padding: 0 var(--space-xl);
            margin: 0 0 var(--space-lg);
            border-bottom: 2px solid var(--border-light);
            background: var(--surface-0);
        }

        [data-theme="dark"] #propertyTabs {
            border-bottom-color: var(--border-medium);
            background: var(--surface-50);
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--text-secondary);
            padding: var(--space-md) var(--space-lg);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            transition: all var(--transition-fast);
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            background: transparent;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-500);
            background: var(--surface-100);
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-500);
            background: transparent;
            border-bottom: 3px solid var(--primary-500);
        }

        .tab-content {
            padding: var(--space-lg);
            min-height: 400px;
            background: var(--surface-0);
            border-radius: var(--radius-lg);
        }

        [data-theme="dark"] .tab-content {
            background: var(--surface-50);
        }

        /* Contenu des onglets */
        .property-details-content {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-xl);
        }

        @media (max-width: 768px) {
            .property-details-content {
                grid-template-columns: 1fr;
            }
        }

        .modal-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--space-md);
        }

        .modal-gallery img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all var(--transition-normal);
            border: 2px solid var(--border-light);
        }

        [data-theme="dark"] .modal-gallery img {
            border-color: var(--border-medium);
        }

        .modal-gallery img:hover {
            transform: scale(1.05);
            border-color: var(--primary-500);
            box-shadow: var(--shadow-lg);
        }

        /* Carte */
        .modal-map-container {
            background: var(--surface-100);
            border-radius: var(--radius-xl);
            padding: var(--space-lg);
            border: 1px solid var(--border-light);
        }

        [data-theme="dark"] .modal-map-container {
            background: var(--surface-200);
            border-color: var(--border-medium);
        }

        .modal-map {
            height: 300px;
            background: var(--surface-200);
            border-radius: var(--radius-lg);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--text-tertiary);
            margin-bottom: var(--space-lg);
            border: 2px dashed var(--border-medium);
        }

        [data-theme="dark"] .modal-map {
            background: var(--surface-300);
            border-color: var(--border-strong);
        }

        .modal-map i {
            font-size: 3rem;
            margin-bottom: var(--space-md);
            opacity: 0.5;
        }

        .map-address {
            padding: var(--space-lg);
            background: var(--surface-0);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-light);
        }

        [data-theme="dark"] .map-address {
            background: var(--surface-100);
            border-color: var(--border-medium);
        }

        .map-address h6 {
            color: var(--text-primary);
            margin-bottom: var(--space-sm);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
        }

        .map-address p {
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 0;
        }

        /* Info propriétaire */
        .owner-info {
            background: var(--surface-100);
            padding: var(--space-xl);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border-light);
        }

        [data-theme="dark"] .owner-info {
            background: var(--surface-200);
            border-color: var(--border-medium);
        }

        .modal-footer {
            border-top: 1px solid var(--border-light);
            padding: var(--space-lg) var(--space-xl);
            background: var(--surface-50);
            border-radius: 0 0 var(--radius-2xl) var(--radius-2xl);
        }

        [data-theme="dark"] .modal-footer {
            border-top-color: var(--border-medium);
            background: var(--surface-100);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: linear-gradient(135deg, var(--neutral-800) 0%, var(--neutral-900) 100%);
            color: var(--text-inverse);
            padding: var(--space-2xl) 0 var(--space-xl);
            position: relative;
            margin-top: var(--space-2xl);
        }

        [data-theme="dark"] .footer {
            background: linear-gradient(135deg, var(--neutral-900) 0%, var(--surface-100) 100%);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary-500), transparent);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-xl);
            margin-bottom: var(--space-xl);
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: var(--space-lg);
            }
        }

        .footer-logo h3 {
            font-size: 1.8rem;
            margin-bottom: var(--space-sm);
            color: var(--text-inverse);
        }

        .footer-logo p {
            opacity: 0.8;
            font-size: 0.9rem;
            color: var(--neutral-300);
        }

        .footer-links {
            display: flex;
            flex-direction: column;
            gap: var(--space-sm);
        }

        .footer-links a {
            color: var(--text-inverse);
            text-decoration: none;
            opacity: 0.8;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            padding: var(--space-xs) 0;
        }

        @media (max-width: 768px) {
            .footer-links a {
                justify-content: center;
            }
        }

        .footer-links a:hover {
            opacity: 1;
            color: var(--primary-300);
            transform: translateX(var(--space-xs));
        }

        .footer-contact p {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-xs);
            opacity: 0.8;
            color: var(--neutral-300);
        }

        @media (max-width: 768px) {
            .footer-contact p {
                justify-content: center;
            }
        }

        .footer-contact i {
            color: var(--primary-400);
        }

        .footer-bottom {
            text-align: center;
            padding-top: var(--space-lg);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
            font-size: 0.9rem;
            color: var(--neutral-400);
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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .slide-in-left {
            animation: slideInLeft 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* ===== LOADING STATES ===== */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--border-light);
            border-top: 2px solid var(--primary-500);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .skeleton {
            background: linear-gradient(90deg, var(--surface-100) 25%, var(--surface-200) 50%, var(--surface-100) 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: var(--radius-md);
        }

        @keyframes skeleton-loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* ===== TOOLTIPS ===== */
        [data-tooltip] {
            position: relative;
        }

        [data-tooltip]::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: var(--space-xs) var(--space-sm);
            background: var(--neutral-800);
            color: var(--text-inverse);
            font-size: 0.75rem;
            border-radius: var(--radius-sm);
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all var(--transition-fast);
            z-index: 1000;
            pointer-events: none;
        }

        [data-tooltip]:hover::before {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-5px);
        }

        /* ===== CUSTOM SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--surface-100);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--neutral-500);
            border-radius: var(--radius-full);
            border: 2px solid var(--surface-100);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--neutral-600);
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb {
            background: var(--neutral-600);
        }

        [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
            background: var(--neutral-500);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .trust-indicators {
                gap: var(--space-xl);
            }

            .trust-item {
                min-width: 120px;
                padding: var(--space-md);
            }

            .trust-number {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }

            .trust-indicators {
                gap: var(--space-lg);
            }

            .trust-item {
                min-width: 100px;
                padding: var(--space-sm);
            }

            .trust-number {
                font-size: 1.5rem;
            }

            .search-form {
                padding: var(--space-lg);
            }

            .about-card {
                padding: var(--space-lg);
            }

            .property-card {
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.75rem;
            }

            .hero-subtitle {
                font-size: 0.9rem;
            }

            .search-form {
                padding: var(--space-md);
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            .properties-grid {
                gap: var(--space-md);
            }

            .modal-content {
                margin: var(--space-sm);
            }
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            .header, .footer, .theme-toggle, .menu-burger-btn,
            .search-form, .property-actions, .modal {
                display: none !important;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .property-card {
                break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }



/* DANS LA SECTION [data-theme="dark"] DU CSS DE HOME.BLADE.PHP */
/* AJOUTER CES RÈGLES POUR LES INDICATEURS DE CONFIANCE : */

[data-theme="dark"] .trust-item {
    background: rgba(30, 41, 59, 0.7); /* Plus clair que le fond précédent */
    border: 1px solid rgba(100, 116, 139, 0.4); /* Bordure plus visible */
    backdrop-filter: blur(15px); /* Effet de flou plus prononcé */
}

[data-theme="dark"] .trust-item:hover {
    background: rgba(51, 65, 85, 0.8); /* Fond plus clair au survol */
    border-color: rgba(148, 163, 184, 0.6); /* Bordure plus claire */
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .trust-number {
    color: #f1f5f9; /* Blanc très clair */
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .trust-text {
    color: #cbd5e1; /* Gris clair */
    font-weight: 500;
}

/* OPTIONNEL : Ajouter un léger effet de lueur */
[data-theme="dark"] .trust-item {
    position: relative;
}

[data-theme="dark"] .trust-item::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg,
        rgba(59, 130, 246, 0.2),
        rgba(139, 92, 246, 0.2),
        rgba(59, 130, 246, 0.2));
    border-radius: calc(var(--radius-xl) + 2px);
    z-index: -1;
    opacity: 0;
    transition: opacity 0.3s ease;
}

[data-theme="dark"] .trust-item:hover::before {
    opacity: 1;
}
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Header avec Menu Burger -->
    <header class="header">
        <div class="container flex-between">
            <!-- Menu Burger (mobile) -->
            <button class="menu-burger-btn" id="menuBurgerBtn">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Logo -->
            <h1 class="logo">ImmoLoc</h1>

            <!-- Navigation Desktop -->
            <nav class="nav-desktop">
                <a href="{{ route('home') }}" class="nav-link">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="#propos" class="nav-link">
                    <i class="fas fa-info-circle"></i> À propos
                </a>
                <a href="{{ route('guide') }}" class="nav-link">
                    <i class="fas fa-book"></i> Guide
                </a>
            </nav>

            <!-- Actions droite -->
            <div class="header-actions">
                <!-- Switch Thème -->
                <button class="theme-toggle" id="themeToggle" aria-label="Changer de thème">
                    <i class="fas fa-moon"></i>
                </button>

                <!-- Bouton Connexion -->
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-connexion">
                        <i class="fas fa-user-circle"></i> Mon Compte
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-connexion">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                @endauth
            </div>
        </div>

        <!-- Menu Mobile Overlay -->
        <div class="menu-overlay" id="menuOverlay"></div>

        <!-- Menu Mobile -->
        <div class="mobile-menu" id="mobileMenu">
            <div class="mobile-menu-header">
                <h3>ImmoLoc</h3>
                <button class="close-menu-btn" id="closeMenuBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mobile-menu-content">
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="fas fa-home"></i> Accueil
                </a>
                <a href="#propos" class="mobile-nav-link">
                    <i class="fas fa-info-circle"></i> À propos
                </a>
                <a href="{{ route('guide') }}" class="mobile-nav-link">
                    <i class="fas fa-book"></i> Guide
                </a>
                <hr class="menu-divider">
                @auth
                    <a href="{{ route('dashboard') }}" class="mobile-nav-link">
                        <i class="fas fa-user-circle"></i> Mon Compte
                    </a>
                @else
                    <a href="{{ route('login') }}" class="mobile-nav-link">
                        <i class="fas fa-sign-in-alt"></i> Connexion
                    </a>
                    <a href="{{ route('register') }}" class="mobile-nav-link">
                        <i class="fas fa-user-plus"></i> Inscription
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section avec Recherche -->
    <section class="hero">
        <div class="hero-overlay">
            <div class="hero-content">
                <h1 class="hero-title">Trouvez votre prochain logement</h1>
                <p class="hero-subtitle">Facilement. Rapidement. En toute sécurité.</p>

                <!-- Indicateurs de confiance -->
                <div class="trust-indicators">
                    <div class="trust-item">
                        <span class="trust-number">{{ $maisons->count() + $appartements->count() }}+</span>
                        <span class="trust-text">Biens disponibles</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-number">24/7</span>
                        <span class="trust-text">Support</span>
                    </div>
                    <div class="trust-item">
                        <span class="trust-number">100%</span>
                        <span class="trust-text">Sécurisé</span>
                    </div>
                </div>

                <!-- Formulaire de recherche -->
                <div class="search-container">
                    <form action="{{ route('property.search') }}" method="GET" class="search-form">
                        <div class="search-grid">
                            <div class="search-field">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="ville" placeholder="Ville, quartier..." class="search-input">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-money-bill-wave"></i>
                                <input type="number" name="prix_min" placeholder="Prix min" class="search-input">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-money-bill-wave"></i>
                                <input type="number" name="prix_max" placeholder="Prix max" class="search-input">
                            </div>
                            <div class="search-field">
                                <i class="fas fa-bed"></i>
                                <input type="number" name="chambres" placeholder="Chambres" class="search-input">
                            </div>
                        </div>
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section À propos -->
    <section id="propos" class="about-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">À propos de Location Immo</h2>
                <p class="section-subtitle">Votre partenaire de confiance pour trouver le logement parfait</p>
            </div>
            <div class="about-content">
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-search-home"></i>
                    </div>
                    <h3>Recherche simplifiée</h3>
                    <p>Trouvez rapidement un logement adapté à vos besoins grâce à nos filtres intelligents.</p>
                </div>
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Paiements sécurisés</h3>
                    <p>Payez vos loyers en ligne en toute sécurité avec notre système de paiement crypté.</p>
                </div>
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3>Pour propriétaires</h3>
                    <p>Publiez vos annonces, gérez vos biens et suivez les paiements automatiquement.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Maisons en vedette -->
    <section class="properties-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Maisons à louer</h2>
                <p class="section-subtitle">Des villas et maisons sélectionnées pour vous</p>
            </div>

            @if($maisons->isEmpty())
                <div class="no-properties">
                    <i class="fas fa-home fa-3x"></i>
                    <h3>Aucune maison disponible pour le moment</h3>
                    <p>Revenez plus tard pour découvrir nos nouvelles offres.</p>
                </div>
            @else
                <div class="properties-grid">
                    @foreach($maisons as $maison)
                        <div class="property-card" data-property-id="{{ $maison->id }}" data-type="maison">
                            <!-- Badge Disponible -->
                            <div class="property-badge available">
                                <i class="fas fa-check-circle"></i> Disponible
                            </div>

                            <!-- Image -->
                            <div class="property-image-container">
                                <img src="{{ $maison->first_photo }}"
                                     alt="{{ $maison->nom }}"
                                     class="property-image"
                                     onclick="openGallery({{ json_encode($maison->photos_array) }})">
                                <div class="property-image-overlay">
                                    <button class="view-gallery-btn" onclick="openGallery({{ json_encode($maison->photos_array) }})">
                                        <i class="fas fa-images"></i> Voir photos
                                    </button>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="property-content">
                                <!-- Location -->
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $maison->ville }}, {{ $maison->adresse }}</span>
                                </div>

                                <!-- Titre -->
                                <h3 class="property-title">{{ $maison->nom }}</h3>

                                <!-- Caractéristiques -->
                                <div class="property-features">
                                    <div class="feature">
                                        <i class="fas fa-bed"></i>
                                        <span>{{ $maison->nombre_chambres }} Chambre(s)</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-{{ $maison->salon ? 'check' : 'times' }}"></i>
                                        <span>{{ $maison->salon ? 'Avec salon' : 'Sans salon' }}</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-home"></i>
                                        <span>{{ ucfirst($maison->type) }}</span>
                                    </div>
                                </div>

                                <!-- Description courte -->
                                <p class="property-description">
                                    {{ Str::limit($maison->description, 100) }}
                                </p>

                                <!-- Prix -->
                                <div class="property-price">
                                    <span class="price-amount">{{ number_format($maison->prix, 0, '', ' ') }} FCFA</span>
                                    <span class="price-period">/ mois</span>
                                </div>

                                <!-- Actions - 2 BOUTONS -->
                                <div class="property-actions">
                                    <!-- Bouton Détails -->
                                    <button class="btn-details"
                                            onclick="openPropertyModal('maison', {{ $maison->id }})">
                                        <i class="fas fa-search"></i> Détails
                                    </button>

                                    <!-- Bouton Contacter/Inscription -->
                                    @auth
                                        <button class="btn-contact"
                                                onclick="envoyerMessageEtRediriger(event, this)"
                                                data-proprietaire-id="{{ $maison->proprietaire->id }}"
                                                data-logement-id="{{ $maison->id }}"
                                                data-type-logement="maison">
                                            <i class="fas fa-comment"></i> Contacter
                                        </button>
                                    @else
                                        <a href="{{ route('register') }}" class="btn-register">
                                            <i class="fas fa-user-plus"></i> S'inscrire
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Appartements en vedette -->
    <section class="properties-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Appartements à louer</h2>
                <p class="section-subtitle">Des appartements modernes et fonctionnels</p>
            </div>

            @if($appartements->isEmpty())
                <div class="no-properties">
                    <i class="fas fa-building fa-3x"></i>
                    <h3>Aucun appartement disponible pour le moment</h3>
                    <p>Revenez plus tard pour découvrir nos nouvelles offres.</p>
                </div>
            @else
                <div class="properties-grid">
                    @foreach($appartements as $appartement)
                        <div class="property-card" data-property-id="{{ $appartement->id }}" data-type="appartement">
                            <!-- Badge Disponible -->
                            <div class="property-badge available">
                                <i class="fas fa-check-circle"></i> Disponible
                            </div>

                            <!-- Image -->
                            <div class="property-image-container">
                                <img src="{{ $appartement->first_photo }}"
                                     alt="{{ $appartement->numero_appartement }}"
                                     class="property-image"
                                     onclick="openGallery({{ json_encode($appartement->photos_array) }})">
                                <div class="property-image-overlay">
                                    <button class="view-gallery-btn" onclick="openGallery({{ json_encode($appartement->photos_array) }})">
                                        <i class="fas fa-images"></i> Voir photos
                                    </button>
                                </div>
                            </div>

                            <!-- Contenu -->
                            <div class="property-content">
                                <!-- Location -->
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $appartement->ville }}, {{ $appartement->adresse }}</span>
                                </div>

                                <!-- Titre -->
                                <h3 class="property-title">Appartement {{ $appartement->numero_appartement }}</h3>

                                <!-- Caractéristiques -->
                                <div class="property-features">
                                    <div class="feature">
                                        <i class="fas fa-bed"></i>
                                        <span>{{ $appartement->nombre_chambres }} Chambre(s)</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-{{ $appartement->salon ? 'check' : 'times' }}"></i>
                                        <span>{{ $appartement->salon ? 'Avec salon' : 'Sans salon' }}</span>
                                    </div>
                                    <div class="feature">
                                        <i class="fas fa-home"></i>
                                        <span>{{ ucfirst($appartement->type) }}</span>
                                    </div>
                                </div>

                                <!-- Description courte -->
                                <p class="property-description">
                                    {{ Str::limit($appartement->description, 100) }}
                                </p>

                                <!-- Prix -->
                                <div class="property-price">
                                    <span class="price-amount">{{ number_format($appartement->prix_mensuel, 0, '', ' ') }} FCFA</span>
                                    <span class="price-period">/ mois</span>
                                </div>

                                <!-- Actions - 2 BOUTONS -->
                                <div class="property-actions">
                                    <!-- Bouton Détails -->
                                    <button class="btn-details"
                                            onclick="openPropertyModal('appartement', {{ $appartement->id }})">
                                        <i class="fas fa-search"></i> Détails
                                    </button>

                                    <!-- Bouton Contacter/Inscription -->
                                    @auth
                                        <button class="btn-contact"
                                                onclick="envoyerMessageEtRediriger(event, this)"
                                                data-proprietaire-id="{{ $appartement->proprietaire->id }}"
                                                data-logement-id="{{ $appartement->id }}"
                                                data-type-logement="appartement">
                                            <i class="fas fa-comment"></i> Contacter
                                        </button>
                                    @else
                                        <a href="{{ route('register') }}" class="btn-register">
                                            <i class="fas fa-user-plus"></i> S'inscrire
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h3>ImmoLoc</h3>
                    <p>Votre plateforme immobilière de confiance</p>
                </div>
                <div class="footer-links">
                    <a href="{{ route('home') }}">Accueil</a>
                    <a href="#propos">À propos</a>
                    <a href="{{ route('guide') }}">Guide</a>
                    <a href="{{ route('login') }}">Connexion</a>
                </div>
                <div class="footer-contact">
                    <p><i class="fas fa-phone"></i> +229 XX XX XX XX</p>
                    <p><i class="fas fa-envelope"></i> contact@immoloc.bj</p>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; {{ date('Y') }} ImmoLoc. Tous droits réservés.
            </div>
        </div>
    </footer>

    <!-- Modal Galerie Photos -->
    <div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Galerie photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner"></div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Détails Bien (NOUVEAU) -->
    <div class="modal fade" id="propertyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="propertyModalTitle">Détails du bien</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Onglets -->
                    <ul class="nav nav-tabs" id="propertyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button">
                                <i class="fas fa-info-circle"></i> Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button">
                                <i class="fas fa-images"></i> Galerie
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button">
                                <i class="fas fa-map-marker-alt"></i> Localisation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="owner-tab" data-bs-toggle="tab" data-bs-target="#owner" type="button">
                                <i class="fas fa-user-tie"></i> Propriétaire
                            </button>
                        </li>
                    </ul>

                    <!-- Contenu des onglets -->
                    <div class="tab-content" id="propertyTabContent">
                        <!-- Onglet Informations -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="property-details-content" id="propertyInfoContent">
                                <!-- Chargé dynamiquement -->
                            </div>
                        </div>

                        <!-- Onglet Galerie -->
                        <div class="tab-pane fade" id="gallery" role="tabpanel">
                            <div id="modalGalleryContent" class="modal-gallery">
                                <!-- Chargé dynamiquement -->
                            </div>
                        </div>

                        <!-- Onglet Localisation -->
                        <div class="tab-pane fade" id="location" role="tabpanel">
                            <div class="modal-map-container">
                                <div id="propertyMap" style="height: 350px; border-radius: 12px; overflow: hidden;"></div>
                                <div class="map-address mt-3">
                                    <h6><i class="fas fa-map-pin"></i> Adresse complète :</h6>
                                    <p id="propertyFullAddress">Chargement...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Propriétaire -->
                        <div class="tab-pane fade" id="owner" role="tabpanel">
                            <div class="owner-info" id="ownerInfoContent">
                                <!-- Chargé dynamiquement -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    @auth
                        <button type="button" class="btn btn-primary" id="contactOwnerBtn">
                            <i class="fas fa-comment"></i> Contacter le propriétaire
                        </button>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> S'inscrire pour contacter
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// home.js - Script principal pour ImmoLoc (version corrigée 2025)

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add('show'), 10);

    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    });

    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // VARIABLES GLOBALES
    const body = document.body;
    let currentTheme = localStorage.getItem('theme') || 'light';

    // INITIALISATION
    initTheme();
    initMenuBurger();
    initPropertyCards();
    initSearchForm();
    initModals();

    // GESTION DU THÈME
    function initTheme() {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;

        body.setAttribute('data-theme', currentTheme);
        updateThemeIcon();

        themeToggle.addEventListener('click', toggleTheme);
    }

    function toggleTheme() {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        body.setAttribute('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme);
        updateThemeIcon();
    }

    function updateThemeIcon() {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;
        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = currentTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
        }
    }

    // MENU BURGER
    function initMenuBurger() {
        const menuBurgerBtn = document.getElementById('menuBurgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        if (!menuBurgerBtn || !mobileMenu) return;

        menuBurgerBtn.addEventListener('click', () => {
            mobileMenu.classList.add('active');
            menuOverlay.classList.add('active');
            body.style.overflow = 'hidden';
        });

        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            body.style.overflow = '';
        }

        if (closeMenuBtn) closeMenuBtn.addEventListener('click', closeMobileMenu);
        if (menuOverlay) menuOverlay.addEventListener('click', closeMobileMenu);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }

    // CARTES BIENS (animations hover + apparition)
    function initPropertyCards() {
        document.querySelectorAll('.property-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.animation = `fadeIn 0.5s ease ${index * 0.1}s forwards`;

            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px) scale(1.02)';
                card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '0 8px 25px rgba(0,0,0,0.08)';
            });
        });
    }

    // FORMULAIRE RECHERCHE
    function initSearchForm() {
        const searchForm = document.querySelector('.search-form');
        if (!searchForm) return;

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const prixMin = parseInt(formData.get('prix_min')) || 0;
            const prixMax = parseInt(formData.get('prix_max')) || Infinity;

            if (prixMin > prixMax) {
                showNotification('Le prix minimum ne peut pas être supérieur au prix maximum', 'error');
                return;
            }

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';
            submitBtn.disabled = true;

            setTimeout(() => {
                this.submit();
            }, 800);
        });
    }

    // INITIALISATION MODALS
    function initModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                if (this.id === 'propertyModal') {
                    document.getElementById('propertyInfoContent').innerHTML = '';
                    document.getElementById('modalGalleryContent').innerHTML = '';
                    document.getElementById('propertyFullAddress').textContent = 'Chargement...';
                    document.getElementById('ownerInfoContent').innerHTML = '';
                }
            });
        });
    }
});

// ────────────────────────────────────────────────
// FONCTIONS GLOBALES
// ────────────────────────────────────────────────

function openGallery(photos) {
    if (!photos || photos.length === 0) photos = ['/images/default.jpg'];

    const carouselInner = document.getElementById('carousel-inner');
    if (!carouselInner) return;

    carouselInner.innerHTML = '';

    photos.forEach((photo, index) => {
        const item = document.createElement('div');
        item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
        item.innerHTML = `
            <img src="${photo.trim()}" class="d-block w-100" alt="Photo ${index+1}"
                 style="max-height:70vh; object-fit:contain;">
        `;
        carouselInner.appendChild(item);
    });

    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
    galleryModal.show();
}

async function envoyerMessageEtRediriger(event, button) {
    event.preventDefault();
    const proprietaireId = button.dataset.proprietaireId;
    const logementId = button.dataset.logementId;
    const typeLogement = button.dataset.typeLogement;

    if (!proprietaireId || !logementId || !typeLogement) {
        showNotification('Informations manquantes', 'error');
        return;
    }

    button.disabled = true;
    const original = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';

    try {
        const res = await fetch('/client/messages/envoyer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            },
            body: JSON.stringify({
                destinataire_id: proprietaireId,
                logement_id: logementId,
                logement_type: typeLogement,
                contenu: "Bonjour, je suis intéressé par votre logement."
            })
        });

        const data = await res.json();

        if (data.success) {
            showNotification('Message envoyé !', 'success');
            setTimeout(() => {
                window.location.href = `/client/messagerie?conversation=${proprietaireId}`;
            }, 1200);
        } else {
            showNotification(data.message || 'Erreur lors de l\'envoi', 'error');
            button.disabled = false;
            button.innerHTML = original;
        }
    } catch (err) {
        showNotification('Erreur de connexion', 'error');
        button.disabled = false;
        button.innerHTML = original;
    }
}

async function openPropertyModal(type, id) {
    const modalEl = document.getElementById('propertyModal');
    if (!modalEl) {
        showNotification('Erreur : modal introuvable', 'error');
        return;
    }

    const modalBody = modalEl.querySelector('.modal-body');

    // ────────────────────────────────────────────────
    // IMPORTANT : NE PAS ÉCRASER innerHTML ici !
    // On affiche le spinner **à l'intérieur** d'un conteneur temporaire
    // ────────────────────────────────────────────────

    // Option A : ajouter un overlay de chargement sans détruire le contenu
    let loadingOverlay = modalBody.querySelector('#loadingOverlay');
    if (!loadingOverlay) {
        loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'loadingOverlay';
        loadingOverlay.style.cssText = 'position:absolute; inset:0; background:rgba(255,255,255,0.9); display:flex; align-items:center; justify-content:center; z-index:10;';
        loadingOverlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-3" role="status"></div>
                <p>Chargement des détails...</p>
            </div>
        `;
        modalBody.style.position = 'relative';
        modalBody.appendChild(loadingOverlay);
    }

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();

    try {
        const response = await fetch(`/property/${type}/${id}/details`);
        if (!response.ok) throw new Error(`HTTP ${response.status}`);

        const data = await response.json();
        if (!data.success) throw new Error(data.error || 'Erreur serveur');

        // On attend que le modal soit visible
        await new Promise(resolve => {
            modalEl.addEventListener('shown.bs.modal', resolve, { once: true });
        });

        // Supprimer l'overlay de chargement
        if (loadingOverlay) loadingOverlay.remove();

        updateModalContent(data);
        updateContactButton(data);

    } catch (error) {
        console.error('Erreur:', error);
        if (loadingOverlay) loadingOverlay.remove();
        modalBody.innerHTML += `
            <div class="alert alert-danger m-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${error.message || 'Impossible de charger les détails'}
            </div>
        `;
        showNotification('Erreur chargement', 'error');
    }
}

function updateModalContent(data) {
    const info    = document.getElementById('propertyInfoContent');
    const gallery = document.getElementById('modalGalleryContent');
    const address = document.getElementById('propertyFullAddress');
    const owner   = document.getElementById('ownerInfoContent');
    const mapDiv  = document.getElementById('propertyMap');

    // Sécurité : si un élément manque, on logue et on sort
    if (!info || !gallery || !address || !owner) {
        console.warn("Un ou plusieurs conteneurs du modal sont absents");
        return;
    }

    const prop = data.property;
    const own  = data.owner;

    // ────────────────────────────────────────────────
    // 1. Onglet Informations
    // ────────────────────────────────────────────────
    info.innerHTML = `
        <div class="row g-4">
            <div class="col-md-6">
                <h5 class="mb-3 text-primary"><i class="fas fa-home me-2"></i>Caractéristiques</h5>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-bed text-primary me-2"></i>${prop.nombre_chambres || '?'} Chambre(s)</li>
                    <li class="mb-3"><i class="fas fa-door-open text-primary me-2"></i>${prop.salon ? 'Avec salon' : 'Sans salon'}</li>
                    <li class="mb-3"><i class="fas fa-building text-primary me-2"></i>${prop.type_logement || prop.type || '—'}</li>
                    <li class="mb-3"><i class="fas fa-ruler-combined text-primary me-2"></i>${prop.surface || '?'} m²</li>
                    ${prop.disponible !== undefined ? `<li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i>${prop.disponible ? 'Disponible' : 'Non disponible'}</li>` : ''}
                </ul>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
                <ul class="list-unstyled">
                    <li class="mb-3"><strong>Nom :</strong> ${prop.nom || prop.numero_appartement || '—'}</li>
                    <li class="mb-3"><strong>Adresse :</strong> ${prop.adresse || '—'}</li>
                    <li class="mb-3"><strong>Ville / Quartier :</strong> ${prop.ville || '—'}</li>
                    <li class="mb-3"><strong>Type :</strong> ${prop.type === 'maison' ? 'Maison' : 'Appartement'}</li>
                    ${prop.statut_publication ? `<li class="mb-3"><strong>Statut :</strong> ${prop.statut_publication === 'approuve' ? '✅ Approuvé' : '⏳ En attente'}</li>` : ''}
                </ul>
            </div>
        </div>

        <div class="mt-4">
            <h5 class="mb-3 text-primary"><i class="fas fa-align-left me-2"></i>Description complète</h5>
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="card-text">${prop.description?.replace(/\n/g, '<br>') || 'Aucune description disponible.'}</p>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4 d-flex align-items-center">
            <i class="fas fa-money-bill-wave fa-2x me-3"></i>
            <div>
                <strong>Prix :</strong><br>
                <span class="fs-4 fw-bold">${Number(prop.prix || prop.prix_mensuel || 0).toLocaleString('fr-FR')} FCFA</span> / mois
            </div>
        </div>
    `;

    // ────────────────────────────────────────────────
    // 2. Onglet Galerie
    // ────────────────────────────────────────────────
    gallery.innerHTML = prop.photos?.length > 0
        ? prop.photos.map((photo, i) => `
            <div class="col-6 col-md-4 col-lg-3 mb-3">
                <div class="ratio ratio-1x1 overflow-hidden rounded shadow-sm">
                    <img src="${photo.trim()}"
                         class="img-fluid object-fit-cover"
                         alt="Photo ${i+1}"
                         style="cursor: pointer; transition: transform 0.3s;"
                         onclick="openGallery(${JSON.stringify(prop.photos)})">
                </div>
            </div>
        `).join('')
        : `
            <div class="text-center py-5 text-muted">
                <i class="fas fa-images fa-4x mb-3 opacity-50"></i>
                <p class="lead">Aucune photo disponible pour ce bien</p>
            </div>
        `;

    // ────────────────────────────────────────────────
    // 3. Onglet Localisation – Adresse + Carte
    // ────────────────────────────────────────────────
    address.textContent = `${prop.adresse || 'Adresse non précisée'}, ${prop.ville || '—'}`;

    // Carte Leaflet
    if (mapDiv) {
        // Nettoyage si carte déjà existante
        if (mapDiv._leaflet_map) {
            mapDiv._leaflet_map.remove();
            delete mapDiv._leaflet_map;
        }

        if (prop.latitude && prop.longitude) {
            const lat = parseFloat(prop.latitude);
            const lng = parseFloat(prop.longitude);

            const map = L.map('propertyMap', {
                zoomControl: true,
                attributionControl: true
            }).setView([lat, lng], 15);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const marker = L.marker([lat, lng], {
                icon: L.divIcon({
                    className: 'custom-marker',
                    html: '<i class="fas fa-map-marker-alt fa-3x text-danger"></i>',
                    iconSize: [36, 36],
                    iconAnchor: [18, 36]
                })
            }).addTo(map);

            marker.bindPopup(`
                <strong>${prop.nom || prop.numero_appartement || 'Logement'}</strong><br>
                ${prop.adresse}, ${prop.ville}<br>
                <span class="text-primary fw-bold">${Number(prop.prix || prop.prix_mensuel || 0).toLocaleString('fr-FR')} FCFA/mois</span>
            `).openPopup();

            // Sauvegarde pour cleanup futur
            mapDiv._leaflet_map = map;

            // Ajustement taille quand onglet est affiché
            setTimeout(() => map.invalidateSize(), 300);
        } else {
            mapDiv.innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                    <div class="text-center">
                        <i class="fas fa-map-marker-slash fa-3x mb-3 opacity-50"></i>
                        <p>Coordonnées GPS non disponibles</p>
                    </div>
                </div>
            `;
        }
    }

    // ────────────────────────────────────────────────
    // 4. Onglet Propriétaire
    // ────────────────────────────────────────────────
    owner.innerHTML = own ? `
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:70px; height:70px; font-size:2rem;">
                ${own.prenom?.charAt(0) || '?'}${own.nom?.charAt(0) || ''}
            </div>
            <div>
                <h5 class="mb-1">${own.prenom || ''} ${own.nom || ''}</h5>
                <small class="text-muted"><i class="fas fa-user-tie me-1"></i>Propriétaire</small>
            </div>
        </div>

        <div class="list-group list-group-flush">
            ${own.telephone ? `
                <div class="list-group-item d-flex align-items-center">
                    <i class="fas fa-phone me-3 text-primary"></i>
                    <span>${own.telephone}</span>
                </div>` : ''}
            ${own.email ? `
                <div class="list-group-item d-flex align-items-center">
                    <i class="fas fa-envelope me-3 text-primary"></i>
                    <span>${own.email}</span>
                </div>` : ''}
            ${own.whatsapp || own.facebook ? `
                <div class="list-group-item">
                    <small class="text-muted">Contact préféré :</small><br>
                    ${own.whatsapp ? `<i class="fab fa-whatsapp me-2 text-success"></i>${own.whatsapp}` : ''}
                    ${own.facebook ? `<i class="fab fa-facebook me-2 text-primary"></i>${own.facebook}` : ''}
                </div>` : ''}
        </div>

        <div class="alert alert-success mt-4 small">
            <i class="fas fa-shield-alt me-2"></i>
            Vos messages sont envoyés de manière sécurisée et privée
        </div>
    ` : `
        <div class="text-center py-5 text-muted">
            <i class="fas fa-user-slash fa-4x mb-3 opacity-50"></i>
            <p>Informations du propriétaire non disponibles pour le moment</p>
        </div>
    `;
}

function updateContactButton(data) {
    const btn = document.getElementById('contactOwnerBtn');
    if (!btn || !data.owner) return;

    btn.onclick = () => {
        const virtualBtn = document.createElement('button');
        virtualBtn.dataset.proprietaireId = data.owner.id;
        virtualBtn.dataset.logementId = data.property.id;
        virtualBtn.dataset.typeLogement = data.property.type;
        envoyerMessageEtRediriger({ preventDefault: () => {} }, virtualBtn);
    };
}
</script>
</body>
</html>
