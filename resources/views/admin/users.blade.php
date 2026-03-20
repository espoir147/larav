@extends('layouts.admin')

@section('admin-content')
<div class="admin-users-page">
    <!-- En-tête avec filtres -->
    <div class="page-header">
        <h2>👥 Gestion des Utilisateurs</h2>

        <div class="filters">
            <div class="filter-group">
                <label>Filtrer par statut:</label>
                <select id="statusFilter" onchange="filterUsers()">
                    <option value="">Tous</option>
                    <option value="en_attente">En attente</option>
                    <option value="actif">Actifs</option>
                    <option value="bloque">Bloqués</option>
                    <option value="rejete">Rejetés</option>
                </select>
            </div>

            <div class="search-group">
                <input type="text" id="searchInput" placeholder="Rechercher par nom ou email..." onkeyup="searchUsers()">
                <button class="search-btn" onclick="searchUsers()">🔍</button>
            </div>
        </div>
    </div>

    <!-- Tableau des utilisateurs avec scroll horizontal -->
    <div class="users-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Statut</th>
                    <th>Inscription</th>
                    <th class="actions-header">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr data-status="{{ $user->statut }}">
                    <td class="user-info">
                        <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default-avatar.jpg') }}" alt="Photo profil" class="user-avatar">
                        <div class="user-details">
                            <strong>{{ $user->nom }}</strong>
                            <small>ID: {{ $user->id }}</small>
                        </div>
                    </td>

                    <td class="contact-info">
                        <div>{{ $user->email }}</div>
                        <small>{{ $user->indicatif_pays }}{{ $user->telephone }}</small>
                    </td>

                    <td>
                        <span class="badge badge-type">{{ $user->type }}</span>
                    </td>

                    <td>
                        <span class="badge badge-{{ $user->statut }}">
                            {{ $user->statut }}
                        </span>
                    </td>

                    <td>
                        {{ $user->created_at->format('d/m/Y') }}
                    </td>

                    <td class="actions-cell">
                        <div class="action-buttons">
                            @if($user->statut == 'en_attente')
                                <button class="action-btn btn-success" onclick="validateUser({{ $user->id }})" title="Valider">
                                    <span class="btn-icon">✅</span>
                                    <span class="btn-text">Valider</span>
                                </button>
                                <button class="action-btn btn-danger" onclick="rejectUser({{ $user->id }})" title="Rejeter">
                                    <span class="btn-icon">❌</span>
                                    <span class="btn-text">Rejeter</span>
                                </button>
                            @endif

                            @if($user->statut == 'actif')
                                <button class="action-btn btn-warning" onclick="blockUser({{ $user->id }})" title="Bloquer">
                                    <span class="btn-icon">🚫</span>
                                    <span class="btn-text">Bloquer</span>
                                </button>
                            @endif

                            @if($user->statut == 'bloque')
                                <button class="action-btn btn-success" onclick="unblockUser({{ $user->id }})" title="Débloquer">
                                    <span class="btn-icon">🔓</span>
                                    <span class="btn-text">Débloquer</span>
                                </button>
                            @endif

                            <button class="action-btn btn-info" onclick="showUserDetails({{ $user->id }})" title="Voir détails">
                                <span class="btn-icon">👀</span>
                                <span class="btn-text">Voir</span>
                            </button>

                            <button class="action-btn btn-danger" onclick="confirmDelete({{ $user->id }})" title="Supprimer">
                                <span class="btn-icon">🗑️</span>
                                <span class="btn-text">Supprimer</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal de détails utilisateur -->
<div id="userModal" class="admin-modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Détails de l'utilisateur</h3>
        <div id="userDetails"></div>
    </div>
</div>

<style>
/* ===== STYLES SPÉCIFIQUES À LA PAGE UTILISATEURS ===== */

.admin-users-page {
    padding: 1rem 0;
    width: 100%;
}

/* ===== EN-TÊTE AVEC FILTRES ===== */
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

.filters {
    display: flex;
    gap: 1.5rem;
    align-items: center;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filter-group label {
    color: var(--text-primary);
    font-weight: 600;
    white-space: nowrap;
}

.filter-group select {
    padding: 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
    cursor: pointer;
}

.search-group {
    position: relative;
    display: flex;
    align-items: center;
}

.search-group input {
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
    width: 250px;
}

.search-btn {
    position: absolute;
    right: 0.5rem;
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1rem;
    padding: 0;
}

/* ===== CONTENEUR DU TABLEAU AVEC SCROLL HORIZONTAL ===== */
.users-table-container {
    width: 100%;
    overflow-x: auto;
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 10px;
    margin-bottom: 2rem;
    -webkit-overflow-scrolling: touch; /* Pour un scroll fluide sur mobile */
}

/* Personnalisation de la barre de défilement */
.users-table-container::-webkit-scrollbar {
    height: 8px;
}

.users-table-container::-webkit-scrollbar-track {
    background: var(--bg-secondary);
    border-radius: 4px;
}

.users-table-container::-webkit-scrollbar-thumb {
    background: var(--text-secondary);
    border-radius: 4px;
}

.users-table-container::-webkit-scrollbar-thumb:hover {
    background: var(--accent-color);
}

/* ===== TABLEAU ===== */
.admin-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 1100px; /* Largeur minimale pour que tout tienne sur une ligne */
    white-space: nowrap;
}

.admin-table th {
    background: var(--accent-color);
    color: var(--bg-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
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

/* Colonne des actions */
.actions-header {
    text-align: center;
}

.actions-cell {
    padding: 0.75rem !important;
}

/* ===== BOUTONS D'ACTIONS SUR LA MÊME LIGNE ===== */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: nowrap; /* Empêche le retour à la ligne */
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    padding: 0.5rem 0.8rem;
    border: none;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    min-width: 40px;
}

.action-btn .btn-icon {
    font-size: 0.9rem;
}

.action-btn .btn-text {
    display: inline;
}

/* Couleurs des boutons */
.action-btn.btn-success {
    background: var(--success-color);
    color: white;
}

.action-btn.btn-danger {
    background: var(--danger-color);
    color: white;
}

.action-btn.btn-warning {
    background: var(--warning-color);
    color: black;
}

.action-btn.btn-info {
    background: var(--accent-color);
    color: var(--bg-primary);
}

[data-admin-theme="dark"] .action-btn.btn-info {
    background: var(--text-primary);
    color: var(--bg-primary);
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.action-btn:active {
    transform: translateY(0);
}

/* ===== INFORMATIONS UTILISATEUR ===== */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    min-width: 180px;
}

.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid var(--border-color);
    object-fit: cover;
    flex-shrink: 0;
}

.user-details strong {
    display: block;
    color: var(--text-primary);
    font-size: 0.95rem;
    line-height: 1.3;
}

.user-details small {
    color: var(--text-secondary);
    font-size: 0.75rem;
}

/* ===== CONTACT INFO ===== */
.contact-info div {
    color: var(--text-primary);
    font-size: 0.9rem;
    word-break: break-word;
    max-width: 200px;
    white-space: normal;
    line-height: 1.3;
}

.contact-info small {
    color: var(--text-secondary);
    font-size: 0.75rem;
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.badge-type {
    background: var(--accent-color);
    color: var(--bg-primary);
}

.badge-en_attente {
    background: var(--warning-color);
    color: #000000;
}

.badge-actif {
    background: var(--success-color);
    color: white;
}

.badge-bloque {
    background: var(--danger-color);
    color: white;
}

.badge-rejete {
    background: var(--text-secondary);
    color: white;
}

[data-admin-theme="dark"] .badge-type {
    background: var(--text-primary);
    color: var(--bg-primary);
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

/* ===== TOAST NOTIFICATION ===== */
.admin-toast {
    position: fixed;
    top: 90px;
    right: 20px;
    padding: 1rem 1.5rem;
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
    color: white;
}

.admin-toast-error {
    background: var(--danger-color);
    color: white;
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
    max-width: 900px;
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

/* ===== PROFIL COMPACT DANS LE MODAL ===== */
.user-profile-compact {
    background: var(--card-bg);
    border-radius: 10px;
    padding: 1.5rem;
    border: 2px solid var(--border-color);
    max-height: 70vh;
    overflow-y: auto;
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
    line-height: 1.2;
}

.profile-badges-compact {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.profile-badges-compact .badge {
    padding: 0.25rem 0.6rem;
    font-size: 0.7rem;
}

.profile-grid-compact {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
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
    font-size: 0.8rem;
    flex: 1;
}

.info-value-compact {
    color: var(--text-primary);
    font-weight: 500;
    font-size: 0.8rem;
    text-align: right;
    flex: 1;
    word-break: break-word;
}

.email-compact {
    word-break: break-all;
    font-size: 0.75rem;
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

.profile-actions-compact .action-btn {
    min-width: 100px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .filters {
        gap: 1rem;
    }

    .search-group input {
        width: 200px;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
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

    .search-group input {
        width: 100%;
    }

    .profile-grid-compact {
        grid-template-columns: 1fr;
    }

    .profile-actions-compact {
        flex-direction: column;
    }

    .profile-actions-compact .action-btn {
        width: 100%;
    }

    .admin-toast {
        top: 80px;
        right: 10px;
        left: 10px;
        max-width: none;
    }
}

@media (max-width: 480px) {
    .page-header h2 {
        font-size: 1.5rem;
    }

    .action-btn .btn-text {
        display: none; /* Cache le texte sur très petits écrans */
    }

    .action-btn {
        padding: 0.5rem;
        min-width: 36px;
    }

    .action-btn .btn-icon {
        font-size: 1rem;
        margin: 0;
    }
}

/* Adaptation pour les très petits écrans */
@media (max-width: 360px) {
    .admin-table {
        min-width: 1000px; /* Maintient la largeur pour le scroll */
    }
}
</style>

<script>
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
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors de la validation', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors de la validation', 'error');
            console.error('Erreur:', error);
        });
    }
}

// Fonction pour bloquer un utilisateur
function blockUser(userId) {
    if (confirm('Voulez-vous vraiment bloquer cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${userId}/bloquer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Utilisateur bloqué avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors du blocage', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors du blocage', 'error');
            console.error('Erreur:', error);
        });
    }
}

// Fonction pour débloquer un utilisateur
function unblockUser(userId) {
    if (confirm('Voulez-vous vraiment débloquer cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${userId}/debloquer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Utilisateur débloqué avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors du déblocage', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors du déblocage', 'error');
            console.error('Erreur:', error);
        });
    }
}

// Fonction pour rejeter un utilisateur
function rejectUser(userId) {
    if (confirm('Voulez-vous vraiment rejeter cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${userId}/rejeter`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Utilisateur rejeté avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors du rejet', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors du rejet', 'error');
            console.error('Erreur:', error);
        });
    }
}

// Fonction pour supprimer un utilisateur
function confirmDelete(userId) {
    if (confirm('⚠️ Cette action est irréversible ! Voulez-vous vraiment supprimer cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Utilisateur supprimé avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Erreur lors de la suppression', 'error');
            }
        })
        .catch(error => {
            showToast('Erreur lors de la suppression', 'error');
            console.error('Erreur:', error);
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

            const maisonsCount = data.maisons_count || 0;
            const appartementsCount = data.appartements_count || 0;
            const totalBiens = maisonsCount + appartementsCount;

            document.getElementById('userDetails').innerHTML = `
                <div class="user-profile-compact">
                    <div class="profile-header-compact">
                        <img src="${data.photo_profil ? '/storage/' + data.photo_profil : '/images/default-avatar.png'}"
                             alt="Photo de ${data.nom}" class="profile-avatar-compact">
                        <div class="profile-title-compact">
                            <h3>${data.nom}</h3>
                            <div class="profile-badges-compact">
                                <span class="badge badge-type">${data.type}</span>
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
                                <span class="info-value-compact">${data.indicatif_pays || ''} ${data.telephone || ''}</span>
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
                                <span class="info-value-compact">${data.last_login_at ? new Date(data.last_login_at).toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'}) : 'Jamais connecté'}</span>
                            </div>
                            <div class="info-item-compact">
                                <span class="info-label-compact">Nombre de biens:</span>
                                <span class="info-value-compact">${(data.maisons_count || 0) + (data.appartements_count || 0)} (${data.maisons_count || 0} maison(s), ${data.appartements_count || 0} appart.)</span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions-compact">
                        ${data.statut === 'en_attente' ? `
                            <button class="action-btn btn-success" onclick="validateUser(${data.id})">
                                <span class="btn-icon">✅</span> Valider
                            </button>
                            <button class="action-btn btn-danger" onclick="rejectUser(${data.id})">
                                <span class="btn-icon">❌</span> Rejeter
                            </button>
                        ` : ''}

                        ${data.statut === 'actif' ? `
                            <button class="action-btn btn-warning" onclick="blockUser(${data.id})">
                                <span class="btn-icon">🚫</span> Bloquer
                            </button>
                        ` : ''}

                        ${data.statut === 'bloque' ? `
                            <button class="action-btn btn-success" onclick="unblockUser(${data.id})">
                                <span class="btn-icon">🔓</span> Débloquer
                            </button>
                        ` : ''}

                        <button class="action-btn btn-danger" onclick="confirmDelete(${data.id})">
                            <span class="btn-icon">🗑️</span> Supprimer
                        </button>
                        <button class="action-btn btn-info" onclick="closeModal()">
                            <span class="btn-icon">✕</span> Fermer
                        </button>
                    </div>
                </div>
            `;

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
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location.href);
    url.searchParams.delete('page');

    if (status) {
        url.searchParams.set('filter', status);
    } else {
        url.searchParams.delete('filter');
    }
    window.location.href = url.toString();
}

// Rechercher des utilisateurs
function searchUsers() {
    const searchTerm = document.getElementById('searchInput').value;
    const url = new URL(window.location.href);
    url.searchParams.delete('page');

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

// Initialisation des filtres depuis l'URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const filterValue = urlParams.get('filter');
    const searchValue = urlParams.get('search');

    if (filterValue) {
        document.getElementById('statusFilter').value = filterValue;
    }

    if (searchValue) {
        document.getElementById('searchInput').value = searchValue;
    }
});
</script>
@endsection
