<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin - ImmoLoc</title>
    <style>
        /* ===== VARIABLES CSS ===== */
        :root {
            --bg-primary: #FFFFFF;
            --bg-secondary: #F5F5F5;
            --text-primary: #000000;
            --text-secondary: #666666;
            --border-color: #DDDDDD;
            --accent-color: #000000;
            --success-color: #00AA00;
            --warning-color: #FFD700;
            --danger-color: #FF0000;
            --card-bg: #FFFFFF;
            --header-bg: #000000;
            --header-text: #FFFFFF;
        }

        [data-admin-theme="dark"] {
            --bg-primary: #111827;
            --bg-secondary: #1F2937;
            --text-primary: #FFFFFF;
            --text-secondary: #E5E7EB;
            --border-color: #374151;
            --accent-color: #FFFFFF;
            --success-color: #10B981;
            --warning-color: #F59E0B;
            --danger-color: #EF4444;
            --card-bg: #1A202C;
            --header-bg: #1F2937;
            --header-text: #FFFFFF;
        }

        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .admin-body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* ===== HEADER ===== */
        .admin-header {
            background: var(--header-bg);
            color: var(--header-text);
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: 70px;
        }

        .admin-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-size: 2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            transition: transform 0.3s ease;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.1);
        }

        .logo:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .admin-header-left h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--header-text);
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-name {
            font-weight: 500;
            color: var(--header-text);
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid var(--header-text);
            object-fit: cover;
        }

        /* ===== CONTAINER PRINCIPAL ===== */
        .admin-container {
            display: flex;
            margin-top: 70px;
            min-height: calc(100vh - 70px);
        }

        /* ===== SIDEBAR ===== */
        .admin-sidebar {
            width: 260px;
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-color);
            padding: 1.5rem 1rem;
            position: fixed;
            height: calc(100vh - 70px);
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 999;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .admin-nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.8rem 1rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .admin-nav-link:hover {
            background: var(--bg-primary);
            border-color: var(--border-color);
        }

        .admin-nav-link.active {
            background: var(--accent-color);
            color: var(--bg-primary);
            border-color: var(--accent-color);
        }

        [data-admin-theme="dark"] .admin-nav-link.active {
            background: var(--accent-color);
            color: var(--bg-primary);
        }

        .logout-btn {
            background: none;
            border: none;
            text-align: left;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: auto;
            color: var(--danger-color);
        }

        .logout-btn:hover {
            background: rgba(255, 0, 0, 0.1);
        }

        /* ===== CONTENU PRINCIPAL ===== */
        .admin-content {
            flex: 1;
            padding: 2rem;
            margin-left: 260px;
            background: var(--bg-primary);
            min-height: calc(100vh - 70px);
            overflow-x: auto;
        }

        /* ===== MENU BURGER MOBILE ===== */
        .admin-menu-toggle {
            display: none;
            position: fixed;
            top: 85px;
            left: 20px;
            z-index: 1000;
            background: var(--accent-color);
            color: var(--bg-primary);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* ===== BOUTON THÈME ===== */
        #admin-theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: var(--accent-color);
            color: var(--bg-primary);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        #admin-theme-toggle:hover {
            transform: rotate(180deg) scale(1.1);
        }

        /* ===== COMPOSANTS COMMUNS ===== */
        .admin-alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: opacity 0.3s;
        }

        .admin-alert-success {
            background: var(--success-color);
            color: white;
        }

        .admin-alert-error {
            background: var(--danger-color);
            color: white;
        }

        /* ===== STATS GRID ===== */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0.5rem 0;
            color: var(--text-primary);
        }

        .stat-breakdown {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        /* ===== RECENT GRID ===== */
        .admin-recent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .recent-card {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .recent-card h4 {
            margin: 0 0 1rem 0;
            color: var(--text-primary);
            font-size: 1.1rem;
            font-weight: 600;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
        }

        .recent-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .recent-card li {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .recent-card li:last-child {
            border-bottom: none;
        }

        .recent-card li strong {
            color: var(--text-primary);
            display: block;
            margin-bottom: 0.25rem;
        }

        .recent-card li small {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* ===== BADGES UNIFIÉS ===== */
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .badge-actif, .badge-success { background: var(--success-color); color: white; }
        .badge-en_attente, .badge-warning { background: var(--warning-color); color: black; }
        .badge-bloque, .badge-danger { background: var(--danger-color); color: white; }
        .badge-rejete { background: var(--text-secondary); color: white; }
        .badge-disponible { background: var(--success-color); color: white; }
        .badge-loue { background: var(--warning-color); color: black; }

        /* ===== TABLEAUX ===== */
        .admin-table-container {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            border-radius: 10px;
            overflow-x: auto;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .admin-table th {
            background: var(--accent-color);
            color: var(--bg-primary);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            white-space: nowrap;
        }

        [data-admin-theme="dark"] .admin-table th {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            vertical-align: middle;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .admin-table tr:hover td {
            background: var(--bg-secondary);
        }

        /* ===== PAGINATION ===== */
        .pagination {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .pagination nav {
            display: flex;
            gap: 0.25rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem;
        }

        .pagination a,
        .pagination span {
            color: var(--text-primary);
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: all 0.3s;
            display: inline-block;
        }

        .pagination a:hover {
            background: var(--accent-color);
            color: var(--bg-primary);
        }

        .pagination .active span {
            background: var(--accent-color);
            color: var(--bg-primary);
            border-color: var(--accent-color);
        }

        /* ===== MODAL ===== */
        .admin-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow-y: auto;
            padding: 1rem;
        }

        .modal-content {
            background: var(--card-bg);
            margin: 2rem auto;
            padding: 2rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            position: relative;
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .close {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 1.8rem;
            font-weight: bold;
            cursor: pointer;
            color: var(--text-secondary);
            transition: color 0.3s;
        }

        .close:hover {
            color: var(--danger-color);
        }

        /* ===== TOAST ===== */
        .admin-toast {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: var(--accent-color);
            color: var(--bg-primary);
            border-radius: 8px;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 350px;
        }

        .admin-toast.show {
            opacity: 1;
        }

        .admin-toast-success {
            background: var(--success-color);
        }

        .admin-toast-error {
            background: var(--danger-color);
        }

        /* ===== FORMULAIRES ===== */
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            background: var(--bg-primary);
            color: var(--text-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--accent-color);
            color: var(--bg-primary);
            border-color: var(--accent-color);
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
            border-color: var(--danger-color);
        }

        .btn-warning {
            background: var(--warning-color);
            color: black;
            border-color: var(--warning-color);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* ===== FILTRES ===== */
        .filters {
            display: flex;
            gap: 1.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group,
        .search-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-group label {
            color: var(--text-primary);
            font-weight: 600;
            white-space: nowrap;
        }

        .search-group {
            position: relative;
        }

        .search-group input {
            padding: 0.5rem 2.5rem 0.5rem 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            background: var(--bg-primary);
            color: var(--text-primary);
        }

        .search-btn {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
        }

        /* ===== QUICK ACTIONS ===== */
        .quick-actions {
            background: var(--card-bg);
            border: 2px solid var(--border-color);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--border-color);
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-header h2 {
            color: var(--text-primary);
            margin: 0;
            font-size: 1.8rem;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 240px;
            }
            .admin-content {
                margin-left: 240px;
            }
            .admin-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .admin-header {
                padding: 1rem;
                height: auto;
                min-height: 70px;
            }

            .admin-header-left h1 {
                font-size: 1.2rem;
            }

            .user-avatar {
                width: 40px;
                height: 40px;
            }

            .admin-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .admin-sidebar {
                position: fixed;
                top: 70px;
                left: -260px;
                width: 260px;
                height: calc(100vh - 70px);
                z-index: 999;
                transition: left 0.3s ease;
            }

            .admin-sidebar.active {
                left: 0;
            }

            .sidebar-overlay.active {
                display: block;
            }

            .admin-content {
                margin-left: 0;
                padding: 1.5rem;
            }

            .admin-stats-grid {
                grid-template-columns: 1fr;
            }

            .admin-recent-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                flex-direction: column;
                width: 100%;
            }

            .filter-group,
            .search-group {
                width: 100%;
            }

            .filter-group select,
            .search-group input {
                width: 100%;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            #admin-theme-toggle {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
            }
        }

        @media (max-width: 480px) {
            .admin-content {
                padding: 1rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .admin-header-left h1 {
                font-size: 1rem;
            }

            .logo {
                width: 40px;
                height: 40px;
                font-size: 1.5rem;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
            }

            .admin-name {
                font-size: 0.9rem;
            }

            #admin-theme-toggle {
                bottom: 15px;
                right: 15px;
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
        }

        @media (max-width: 360px) {
            .admin-header-left {
                gap: 0.5rem;
            }

            .admin-header-left h1 {
                font-size: 0.9rem;
            }

            .admin-menu-toggle {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                top: 80px;
            }
        }

        @media (orientation: landscape) and (max-width: 768px) {
            .admin-sidebar {
                overflow-y: auto;
                padding-bottom: 2rem;
            }
        }
    </style>
</head>
<body class="admin-body">
    <!-- Header Admin -->
    <header class="admin-header">
        <div class="admin-header-left">
            <!-- LOGO -->
            <a href="<?php echo e(route('home')); ?>" class="logo">🏠</a>
            <h1>Espace Administration</h1>
        </div>
        <div class="admin-header-right">
            <span class="admin-name"><?php echo e(Auth::user()->nom); ?></span>
            <img src="<?php echo e(Auth::user()->photo_profil ? asset('storage/' . Auth::user()->photo_profil) : asset('images/default-avatar.jpg')); ?>" alt="Photo profil" class="user-avatar">
        </div>
    </header>

    <!-- Menu burger pour mobile -->
    <button class="admin-menu-toggle" onclick="toggleSidebar()">☰</button>
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Main Container -->
    <div class="admin-container">
        <!-- Sidebar Menu -->
        <aside class="admin-sidebar">
            <nav class="admin-nav">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-nav-link">
                    📊 Tableau de bord
                </a>
                <a href="<?php echo e(route('admin.users')); ?>" class="admin-nav-link">
                    👥 Utilisateurs
                </a>
                <a href="<?php echo e(route('admin.properties')); ?>" class="admin-nav-link">
                    🏠 Biens immobiliers
                </a>
                <a href="<?php echo e(route('admin.transactions')); ?>" class="admin-nav-link">
                    💰 Transactions
                </a>
                <a href="<?php echo e(route('admin.logs')); ?>" class="admin-nav-link">
                    📝 Journal
                </a>
                <a href="<?php echo e(route('admin.profil')); ?>" class="admin-nav-link">
                    ⚙️ Profil
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin-top: auto;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="admin-nav-link logout-btn">
                        🚪 Déconnexion
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Content Area -->
        <main class="admin-content">
            <?php echo $__env->yieldContent('admin-content'); ?>
        </main>
    </div>

    <!-- Modal pour détails utilisateur -->
    <div id="userModal" class="admin-modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div id="userDetails"></div>
        </div>
    </div>

    <script>
        // ==================== FONCTIONS GLOBALES ====================

        // Fonction toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `admin-toast admin-toast-${type}`;
            toast.textContent = message;

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

        // Fonction pour valider un utilisateur
        function validateUser(userId) {
            if (confirm('Voulez-vous vraiment valider cet utilisateur ?')) {
                fetch(`/admin/utilisateurs/${userId}/valider`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Utilisateur validé avec succès', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showToast('Erreur lors de la validation', 'error');
                });
            }
        }

        // Fonction pour bloquer un utilisateur
        function blockUser(userId) {
            if (confirm('Voulez-vous vraiment bloquer cet utilisateur ?')) {
                fetch(`/admin/utilisateurs/${userId}/bloquer`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        showToast('Utilisateur bloqué avec succès', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showToast('Erreur lors du blocage', 'error');
                });
            }
        }

        // Fonction pour débloquer un utilisateur
        function unblockUser(userId) {
            if (confirm('Voulez-vous vraiment débloquer cet utilisateur ?')) {
                fetch(`/admin/utilisateurs/${userId}/debloquer`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        showToast('Utilisateur débloqué avec succès', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showToast('Erreur lors du déblocage', 'error');
                });
            }
        }

        // Fonction pour rejeter un utilisateur
        function rejectUser(userId) {
            if (confirm('Voulez-vous vraiment rejeter cet utilisateur ?')) {
                fetch(`/admin/utilisateurs/${userId}/rejeter`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        showToast('Utilisateur rejeté avec succès', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showToast('Erreur lors du rejet', 'error');
                });
            }
        }

        // Fonction pour supprimer un utilisateur
        function confirmDelete(userId) {
            if (confirm('⚠️ Cette action est irréversible ! Voulez-vous vraiment supprimer cet utilisateur ?')) {
                fetch(`/admin/utilisateurs/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => {
                    if (response.ok) {
                        showToast('Utilisateur supprimé avec succès', 'success');
                        location.reload();
                    }
                })
                .catch(error => {
                    showToast('Erreur lors de la suppression', 'error');
                });
            }
        }

        // Fonction pour afficher les détails d'un utilisateur
        function showUserDetails(userId) {
            fetch(`/admin/utilisateurs/${userId}/details`)
                .then(response => response.json())
                .then(data => {
                    const formattedDate = new Date(data.created_at).toLocaleDateString('fr-FR', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    document.getElementById('userDetails').innerHTML = `
                        <div class="user-profile-compact">
                            <div class="profile-header-compact">
                                <img src="${data.photo_profil || '/images/default-avatar.png'}"
                                     alt="Photo de ${data.nom}" class="profile-avatar-compact">
                                <div class="profile-title-compact">
                                    <h3>${data.nom}</h3>
                                    <div class="profile-badges-compact">
                                        <span class="badge badge-${data.type}">${data.type}</span>
                                        <span class="badge badge-${data.statut}">${data.statut}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-grid-compact">
                                <div class="profile-column">
                                    <h4 class="column-title">📋 Identité</h4>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">ID Utilisateur:</span>
                                        <span class="info-value-compact">${data.id}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Type de compte:</span>
                                        <span class="info-value-compact">${data.type}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Statut:</span>
                                        <span class="badge badge-${data.statut}">${data.statut}</span>
                                    </div>
                                </div>

                                <div class="profile-column">
                                    <h4 class="column-title">📞 Contact</h4>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Email:</span>
                                        <span class="info-value-compact email-compact">${data.email}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Téléphone:</span>
                                        <span class="info-value-compact">${data.indicatif_pays} ${data.telephone}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Date de naissance:</span>
                                        <span class="info-value-compact">${data.date_naissance ? new Date(data.date_naissance).toLocaleDateString('fr-FR') : 'Non renseignée'}</span>
                                    </div>
                                </div>

                                <div class="profile-column">
                                    <h4 class="column-title">📊 Compte</h4>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Inscrit le:</span>
                                        <span class="info-value-compact">${formattedDate}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Dernière connexion:</span>
                                        <span class="info-value-compact">${data.last_login_at ? new Date(data.last_login_at).toLocaleDateString('fr-FR') : 'Jamais'}</span>
                                    </div>
                                    <div class="info-item-compact">
                                        <span class="info-label-compact">Nombre de biens:</span>
                                        <span class="info-value-compact">${(data.maisons_count || 0) + (data.appartements_count || 0)}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-actions-compact">
                                <button class="btn btn-success" onclick="validateUser(${data.id})" ${data.statut !== 'en_attente' ? 'disabled' : ''}>
                                    ✅ Valider
                                </button>
                                <button class="btn btn-warning" onclick="blockUser(${data.id})" ${data.statut !== 'actif' ? 'disabled' : ''}>
                                    🚫 Bloquer
                                </button>
                                <button class="btn btn-danger" onclick="confirmDelete(${data.id})">
                                    🗑️ Supprimer
                                </button>
                                <button class="btn" onclick="closeModal()">
                                    ✕ Fermer
                                </button>
                            </div>
                        </div>
                    `;

                    // Ajouter le CSS compact pour la carte profil
                    const style = document.createElement('style');
                    style.textContent = `
                        .user-profile-compact {
                            background: var(--card-bg);
                            border-radius: 10px;
                            max-height: 75vh;
                            overflow-y: auto;
                            padding: 0.5rem;
                        }

                        .profile-header-compact {
                            display: flex;
                            align-items: center;
                            gap: 1rem;
                            margin-bottom: 1.5rem;
                            padding-bottom: 1rem;
                            border-bottom: 2px solid var(--border-color);
                        }

                        .profile-avatar-compact {
                            width: 80px;
                            height: 80px;
                            border-radius: 50%;
                            border: 3px solid var(--border-color);
                            object-fit: cover;
                            flex-shrink: 0;
                        }

                        .profile-title-compact h3 {
                            margin: 0 0 0.5rem 0;
                            font-size: 1.5rem;
                            color: var(--text-primary);
                        }

                        .profile-badges-compact {
                            display: flex;
                            gap: 0.5rem;
                            flex-wrap: wrap;
                        }

                        .profile-grid-compact {
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            gap: 1.5rem;
                            margin-bottom: 1.5rem;
                        }

                        @media (max-width: 1200px) {
                            .profile-grid-compact {
                                grid-template-columns: repeat(2, 1fr);
                            }
                        }

                        @media (max-width: 768px) {
                            .profile-grid-compact {
                                grid-template-columns: 1fr;
                            }
                        }

                        .profile-column {
                            background: var(--bg-secondary);
                            padding: 1rem;
                            border-radius: 8px;
                            border: 1px solid var(--border-color);
                        }

                        .column-title {
                            margin: 0 0 0.75rem 0;
                            color: var(--text-primary);
                            font-size: 1rem;
                            font-weight: 600;
                            display: flex;
                            align-items: center;
                            gap: 0.5rem;
                        }

                        .info-item-compact {
                            display: flex;
                            justify-content: space-between;
                            align-items: flex-start;
                            padding: 0.5rem 0;
                            border-bottom: 1px solid var(--border-color);
                            min-height: 2.5rem;
                        }

                        .info-item-compact:last-child {
                            border-bottom: none;
                        }

                        .info-label-compact {
                            font-weight: 600;
                            color: var(--text-secondary);
                            font-size: 0.85rem;
                            flex: 1;
                        }

                        .info-value-compact {
                            color: var(--text-primary);
                            font-weight: 500;
                            font-size: 0.85rem;
                            text-align: right;
                            flex: 1;
                            word-break: break-word;
                        }

                        .email-compact {
                            word-break: break-all;
                            font-size: 0.8rem;
                        }

                        .profile-actions-compact {
                            display: flex;
                            gap: 0.75rem;
                            margin-top: 1.5rem;
                            padding-top: 1rem;
                            border-top: 1px solid var(--border-color);
                            justify-content: center;
                            flex-wrap: wrap;
                        }

                        .profile-actions-compact button {
                            min-width: 100px;
                        }

                        .profile-actions-compact button:disabled {
                            opacity: 0.5;
                            cursor: not-allowed;
                        }
                    `;
                    document.head.appendChild(style);

                    document.getElementById('userModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showToast('Erreur lors du chargement des détails', 'error');
                });
        }

        // Fermer la modal
        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        // Filtrer les utilisateurs
        function filterUsers() {
            const status = document.getElementById('statusFilter')?.value;
            const url = new URL(window.location.href);

            if (status) {
                url.searchParams.set('filter', status);
            } else {
                url.searchParams.delete('filter');
            }

            window.location.href = url.toString();
        }

        // Rechercher des utilisateurs
        function searchUsers() {
            const searchTerm = document.getElementById('searchInput')?.value;
            const url = new URL(window.location.href);

            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }

            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                window.location.href = url.toString();
            }, 500);
        }

        // Gestion de la modal (cliquer en dehors pour fermer)
        window.onclick = function(event) {
            const modal = document.getElementById('userModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };

        // ==================== GESTION UI ====================

        // Activation des liens du menu
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.admin-nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.admin-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });

        // Gestion du thème
        (function() {
            const themeToggle = document.createElement('button');
            themeToggle.id = 'admin-theme-toggle';
            themeToggle.innerHTML = '🌙';
            themeToggle.setAttribute('aria-label', 'Changer de thème');

            document.body.appendChild(themeToggle);

            let currentTheme = localStorage.getItem('admin-theme') || 'light';

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-admin-theme', theme);
                localStorage.setItem('admin-theme', theme);
                themeToggle.innerHTML = theme === 'light' ? '🌙' : '☀️';
            }

            themeToggle.addEventListener('click', function() {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                applyTheme(currentTheme);
            });

            applyTheme(currentTheme);
        })();

        // Gestion du sidebar responsive
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        // Fermer le sidebar en cliquant sur un lien sur mobile
        document.querySelectorAll('.admin-nav-link').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Fermer le sidebar en redimensionnant la fenêtre
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const sidebar = document.querySelector('.admin-sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    </script>
</body>
</html>
<?php /**PATH /home/espoir/larav/resources/views/layouts/admin.blade.php ENDPATH**/ ?>