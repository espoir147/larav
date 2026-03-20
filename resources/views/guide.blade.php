<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImmoLoc - Guide Utilisateur</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        /* ===== HERO SECTION GUIDE ===== */
        .guide-hero {
            background: linear-gradient(var(--overlay-medium), var(--overlay-medium)),
                        linear-gradient(135deg, var(--primary-600) 0%, var(--secondary-600) 100%);
            min-height: 40vh;
            display: flex;
            align-items: center;
            position: relative;
            padding: var(--space-2xl) 0;
        }

        [data-theme="dark"] .guide-hero {
            background: linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)),
                        linear-gradient(135deg, var(--primary-800) 0%, var(--secondary-800) 100%);
        }

        .guide-hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            color: var(--text-inverse);
            padding: var(--space-xl);
        }

        .guide-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: var(--space-md);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-inverse) 0%, var(--accent-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        [data-theme="dark"] .guide-title {
            background: linear-gradient(135deg, var(--text-inverse) 0%, var(--accent-400) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .guide-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: var(--space-xl);
            color: var(--neutral-200);
        }

        [data-theme="dark"] .guide-subtitle {
            color: var(--neutral-300);
        }

        /* ===== SECTION GUIDE ===== */
        .guide-section {
            padding: var(--space-2xl) 0;
            background: var(--surface-50);
        }

        [data-theme="dark"] .guide-section {
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

        /* Guide Content */
        .guide-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        .guide-card {
            background: var(--surface-0);
            padding: var(--space-xl);
            border-radius: var(--radius-xl);
            margin-bottom: var(--space-lg);
            box-shadow: var(--shadow-lg);
            transition: all var(--transition-normal);
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .guide-card {
            background: var(--surface-50);
            border-color: var(--border-medium);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .guide-card::before {
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

        .guide-card:hover::before {
            opacity: 1;
        }

        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-400);
        }

        .guide-card-header {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .guide-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--text-inverse);
            flex-shrink: 0;
        }

        .guide-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .guide-card-content {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .guide-card-content p {
            margin-bottom: var(--space-md);
        }

        .guide-card-content ul {
            padding-left: var(--space-lg);
            margin-bottom: var(--space-md);
        }

        .guide-card-content li {
            margin-bottom: var(--space-sm);
            position: relative;
        }

        .guide-card-content li::before {
            content: '✓';
            color: var(--primary-500);
            font-weight: bold;
            position: absolute;
            left: calc(-1 * var(--space-md));
        }

        .guide-note {
            background: var(--surface-100);
            border-left: 4px solid var(--warning-500);
            padding: var(--space-md);
            border-radius: var(--radius-md);
            margin-top: var(--space-md);
        }

        [data-theme="dark"] .guide-note {
            background: var(--surface-200);
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

        .fade-in {
            animation: fadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        .slide-in-left {
            animation: slideInLeft 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
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
            .guide-title {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .guide-title {
                font-size: 2rem;
            }

            .guide-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .section-subtitle {
                font-size: 1rem;
            }

            .guide-card {
                padding: var(--space-lg);
            }

            .guide-card-header {
                flex-direction: column;
                text-align: center;
                gap: var(--space-md);
            }

            .guide-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .guide-title {
                font-size: 1.75rem;
            }

            .guide-subtitle {
                font-size: 0.9rem;
            }

            .guide-card {
                padding: var(--space-md);
            }
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
                <a href="{{ route('home') }}#propos" class="nav-link">
                    <i class="fas fa-info-circle"></i> À propos
                </a>
                <a href="{{ route('guide') }}" class="nav-link active">
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
                <a href="{{ route('home') }}#propos" class="mobile-nav-link">
                    <i class="fas fa-info-circle"></i> À propos
                </a>
                <a href="{{ route('guide') }}" class="mobile-nav-link active">
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

    <!-- Hero Section Guide -->
    <section class="guide-hero">
        <div class="guide-hero-content">
            <h1 class="guide-title">Guide Utilisateur ImmoLoc</h1>
            <p class="guide-subtitle">Tout ce que vous devez savoir pour utiliser notre plateforme immobilière</p>
        </div>
    </section>

    <!-- Guide Content -->
    <section class="guide-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Documentation Complète</h2>
                <p class="section-subtitle">Guide étape par étape pour utiliser toutes les fonctionnalités d'ImmoLoc</p>
            </div>

            <div class="guide-content">
                <!-- Section 1 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h3 class="guide-card-title">1. Inscription & Connexion</h3>
                    </div>
                    <div class="guide-card-content">
                        <p>Choisissez votre rôle : <strong>client</strong> ou <strong>propriétaire</strong> lors de l'inscription.</p>
                        <p>📱 <strong>Important :</strong> Utilisez un <strong>numéro WhatsApp valide</strong> pour faciliter la communication.</p>
                        <div class="guide-note">
                            <i class="fas fa-lightbulb"></i> <strong>Astuce :</strong> Ajoutez une photo de profil pour gagner la confiance des autres utilisateurs.
                        </div>
                    </div>
                </div>

                <!-- Section 2 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3 class="guide-card-title">2. Ajouter une Maison ou un Appartement</h3>
                    </div>
                    <div class="guide-card-content">
                        <p><strong>Propriétaires :</strong> Remplissez soigneusement le formulaire d'ajout de bien :</p>
                        <ul>
                            <li>Sélectionnez le type de bien (maison ou appartement)</li>
                            <li>Précisez le prix, nombre de chambres, superficie</li>
                            <li>Ajoutez une description détaillée</li>
                            <li>Téléchargez <strong>plusieurs photos</strong> de qualité</li>
                        </ul>
                        <p>Les biens sont soumis à validation par l'administration avant publication.</p>
                    </div>
                </div>

                <!-- Section 3 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h3 class="guide-card-title">3. Contacter un Propriétaire</h3>
                    </div>
                    <div class="guide-card-content">
                        <p><strong>Clients :</strong> Pour manifester votre intérêt pour un logement :</p>
                        <ul>
                            <li>Connectez-vous à votre compte</li>
                            <li>Cliquez sur "Contacter le propriétaire"</li>
                            <li>Un message automatique est envoyé au propriétaire</li>
                            <li>Vous êtes redirigé vers WhatsApp pour discuter directement</li>
                        </ul>
                        <div class="guide-note">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Note :</strong> Vous devez être connecté pour contacter un propriétaire.
                        </div>
                    </div>
                </div>

                <!-- Section 4 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="guide-card-title">4. Messagerie Interne</h3>
                    </div>
                    <div class="guide-card-content">
                        <p>Utilisez notre messagerie intégrée pour :</p>
                        <ul>
                            <li>Discuter avec les propriétaires/clients</li>
                            <li>Négocier les conditions de location</li>
                            <li>Échanger des documents (contrats, pièces d'identité)</li>
                        </ul>
                        <p><strong>Propriétaires :</strong> Vous pouvez accepter un locataire directement depuis la messagerie.</p>
                    </div>
                </div>

                <!-- Section 5 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h3 class="guide-card-title">5. Paiement du Loyer</h3>
                    </div>
                    <div class="guide-card-content">
                        <p><strong>Clients :</strong> Dans "Mes Locations", utilisez le formulaire de paiement :</p>
                        <ul>
                            <li>Sélectionnez le mois et l'année</li>
                            <li>Entrez le montant du loyer</li>
                            <li>Confirmez le paiement</li>
                        </ul>
                        <p><strong>Propriétaires :</strong> Suivez les paiements dans "Payer mes loyers".</p>
                        <div class="guide-note">
                            <i class="fas fa-exclamation-triangle"></i> <strong>Limite :</strong> Un seul paiement par mois et par logement est autorisé.
                        </div>
                    </div>
                </div>

                <!-- Section 6 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-list-alt"></i>
                        </div>
                        <h3 class="guide-card-title">6. Gestion des Locations</h3>
                    </div>
                    <div class="guide-card-content">
                        <p><strong>Pour les clients :</strong></p>
                        <ul>
                            <li>Consultez vos logements loués dans "Mes Locations"</li>
                            <li>Visualisez l'historique des paiements</li>
                            <li>Téléchargez vos reçus de paiement</li>
                        </ul>
                        <p><strong>Pour les propriétaires :</strong></p>
                        <ul>
                            <li>Gérez vos locataires dans "Mes Locataires"</li>
                            <li>Suivez les paiements en temps réel</li>
                            <li>Possibilité de mettre fin à une location si nécessaire</li>
                        </ul>
                    </div>
                </div>

                <!-- Section 7 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="guide-card-title">7. Règles et Bonnes Pratiques</h3>
                    </div>
                    <div class="guide-card-content">
                        <ul>
                            <li>Utilisez toujours un <strong>numéro WhatsApp valide</strong></li>
                            <li>Ajoutez des <strong>photos réelles</strong> de vos logements</li>
                            <li>Ne proposez que des biens dont vous êtes propriétaire</li>
                            <li>Respectez les prix du marché</li>
                            <li>Effectuez les paiements uniquement via la plateforme</li>
                            <li>Signalez tout comportement suspect à l'administration</li>
                        </ul>
                        <div class="guide-note">
                            <i class="fas fa-gavel"></i> <strong>Important :</strong> Tout non-respect des règles peut entraîner la suspension de votre compte.
                        </div>
                    </div>
                </div>

                <!-- Section 8 -->
                <div class="guide-card fade-in">
                    <div class="guide-card-header">
                        <div class="guide-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3 class="guide-card-title">8. Assistance et Support</h3>
                    </div>
                    <div class="guide-card-content">
                        <p>En cas de problème ou pour toute question :</p>
                        <ul>
                            <li><strong>Support WhatsApp :</strong> <a href="https://wa.me/22941764941" style="color: var(--primary-500);">+229 41 76 49 41</a></li>
                            <li><strong>Email :</strong> support@immoloc.bj</li>
                            <li><strong>Heures d'ouverture :</strong> Lundi - Vendredi, 8h - 18h</li>
                        </ul>
                        <p>Nous nous engageons à répondre à vos demandes dans les 24 heures ouvrables.</p>
                    </div>
                </div>
            </div>
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
                    <a href="{{ route('home') }}#propos">À propos</a>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // guide.js - Script principal pour la page Guide
        document.addEventListener('DOMContentLoaded', function() {
            // ============ VARIABLES GLOBALES ============
            const body = document.body;
            let currentTheme = localStorage.getItem('theme') || 'light';

            // ============ INITIALISATION ============
            initTheme();
            initMenuBurger();
            initAnimations();

            // ============ GESTION DU THÈME ============
            function initTheme() {
                const themeToggle = document.getElementById('themeToggle');
                if (!themeToggle) return;

                // Appliquer le thème sauvegardé
                body.setAttribute('data-theme', currentTheme);
                updateThemeIcon();

                // Gérer le clic sur le bouton thème
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

            // ============ MENU BURGER ÉLÉGANT ============
            function initMenuBurger() {
                const menuBurgerBtn = document.getElementById('menuBurgerBtn');
                const closeMenuBtn = document.getElementById('closeMenuBtn');
                const mobileMenu = document.getElementById('mobileMenu');
                const menuOverlay = document.getElementById('menuOverlay');

                if (!menuBurgerBtn || !mobileMenu) return;

                // Ouvrir menu
                menuBurgerBtn.addEventListener('click', function() {
                    mobileMenu.classList.add('active');
                    menuOverlay.classList.add('active');
                    body.style.overflow = 'hidden';
                });

                // Fermer menu
                function closeMobileMenu() {
                    mobileMenu.classList.remove('active');
                    menuOverlay.classList.remove('active');
                    body.style.overflow = '';
                }

                if (closeMenuBtn) {
                    closeMenuBtn.addEventListener('click', closeMobileMenu);
                }

                if (menuOverlay) {
                    menuOverlay.addEventListener('click', closeMobileMenu);
                }

                // Fermer avec Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                        closeMobileMenu();
                    }
                });

                // Marquer le lien actif
                const currentPath = window.location.pathname;
                document.querySelectorAll('.mobile-nav-link').forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                    }
                });

                document.querySelectorAll('.nav-link').forEach(link => {
                    if (link.getAttribute('href') === currentPath) {
                        link.classList.add('active');
                    }
                });
            }

            // ============ ANIMATIONS ============
            function initAnimations() {
                // Animation des cartes guide
                const guideCards = document.querySelectorAll('.guide-card');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            // Délai progressif pour chaque carte
                            setTimeout(() => {
                                entry.target.style.opacity = '1';
                                entry.target.style.transform = 'translateY(0)';
                            }, index * 150);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                guideCards.forEach(card => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });

                // Effet hover sur les cartes
                guideCards.forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        card.style.transform = 'translateY(-5px)';
                    });

                    card.addEventListener('mouseleave', () => {
                        card.style.transform = 'translateY(0)';
                    });
                });
            }

            // ============ FONCTIONS UTILITAIRES ============
            function showNotification(message, type = 'success') {
                // Créer la notification
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

                // Ajouter au body
                document.body.appendChild(notification);

                // Animation d'entrée
                setTimeout(() => {
                    notification.classList.add('show');
                }, 10);

                // Fermer la notification
                const closeBtn = notification.querySelector('.notification-close');
                closeBtn.addEventListener('click', () => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                });

                // Auto-fermeture
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, 5000);
            }

            // ============ SCROLL SMOOTH ============
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;

                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        window.scrollTo({
                            top: targetElement.offsetTop - 80,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // ============ STYLES DYNAMIQUES ============
            const dynamicStyles = document.createElement('style');
            dynamicStyles.textContent = `
                /* Notifications */
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                    padding: 1rem 1.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 1rem;
                    z-index: 9999;
                    transform: translateX(120%);
                    transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                    max-width: 350px;
                }

                [data-theme="dark"] .notification {
                    background: #1e293b;
                    color: #f1f5f9;
                    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
                }

                .notification.show {
                    transform: translateX(0);
                }

                .notification-success {
                    border-left: 4px solid #10b981;
                }

                .notification-error {
                    border-left: 4px solid #ef4444;
                }

                .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    flex: 1;
                }

                .notification-content i {
                    font-size: 1.25rem;
                }

                .notification-success .notification-content i {
                    color: #10b981;
                }

                .notification-error .notification-content i {
                    color: #ef4444;
                }

                .notification-close {
                    background: none;
                    border: none;
                    color: #64748b;
                    cursor: pointer;
                    padding: 0.25rem;
                    border-radius: 4px;
                    transition: all 0.2s;
                }

                .notification-close:hover {
                    color: #334155;
                    background: #e2e8f0;
                }

                [data-theme="dark"] .notification-close:hover {
                    color: #cbd5e1;
                    background: #475569;
                }

                /* Active state for nav links */
                .nav-link.active {
                    background: rgba(255, 255, 255, 0.25);
                }

                .nav-link.active::after {
                    width: 80% !important;
                }

                .mobile-nav-link.active {
                    background: var(--primary-100);
                    color: var(--primary-700);
                    border-color: var(--primary-400);
                }

                [data-theme="dark"] .mobile-nav-link.active {
                    background: var(--primary-900);
                    color: var(--primary-300);
                }

                /* Smooth scrolling */
                html {
                    scroll-behavior: smooth;
                }

                /* Custom scrollbar */
                ::-webkit-scrollbar {
                    width: 10px;
                }

                ::-webkit-scrollbar-track {
                    background: #f1f5f9;
                }

                [data-theme="dark"] ::-webkit-scrollbar-track {
                    background: #1e293b;
                }

                ::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 9999px;
                }

                [data-theme="dark"] ::-webkit-scrollbar-thumb {
                    background: #475569;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }

                [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
                    background: #64748b;
                }
            `;

            document.head.appendChild(dynamicStyles);
        });
    </script>
</body>
</html>
