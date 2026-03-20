@extends('layouts.admin')

@section('admin-content')
<div class="admin-properties-page">
    <!-- En-tête -->
    <div class="properties-header">
        <h1>🏠 Gestion des Biens Immobiliers</h1>
        <p>Validez et gérez les biens publiés par les propriétaires</p>
    </div>

    <!-- Statistiques -->
    <div class="properties-stats-grid">
        <div class="properties-stat-card">
            <h3>⏳ En attente</h3>
            <p class="properties-stat-number" id="stat-en-attente">{{ $stats['en_attente'] ?? 0 }}</p>
            <div class="properties-stat-breakdown">
                <span>À valider aujourd'hui</span>
            </div>
        </div>

        <div class="properties-stat-card">
            <h3>✅ Approuvés</h3>
            <p class="properties-stat-number" id="stat-approuves">{{ $stats['approuves'] ?? 0 }}</p>
            <div class="properties-stat-breakdown">
                <span>Visibles sur le site</span>
            </div>
        </div>

        <div class="properties-stat-card">
            <h3>❌ Rejetés</h3>
            <p class="properties-stat-number" id="stat-rejetes">{{ $stats['rejetes'] ?? 0 }}</p>
            <div class="properties-stat-breakdown">
                <span>Biens non validés</span>
            </div>
        </div>

        <div class="properties-stat-card">
            <h3>📊 Total</h3>
            <p class="properties-stat-number" id="stat-total">{{ $stats['total'] ?? 0 }}</p>
            <div class="properties-stat-breakdown">
                <span>Tous les biens</span>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="properties-filters-section">
        <form method="GET" action="{{ route('admin.properties') }}" id="filterForm">
            <div class="properties-filters-row">
                <div class="properties-filter-group">
                    <label>Type de bien</label>
                    <select name="type" id="typeFilter" onchange="filterProperties()">
                        <option value="">Tous les types</option>
                        <option value="maison" {{ request('type') == 'maison' ? 'selected' : '' }}>Maison</option>
                        <option value="appartement" {{ request('type') == 'appartement' ? 'selected' : '' }}>Appartement</option>
                    </select>
                </div>

                <div class="properties-filter-group">
                    <label>Ville</label>
                    <select name="ville" id="villeFilter" onchange="filterProperties()">
                        <option value="">Toutes les villes</option>
                        @foreach($villes ?? [] as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>
                                {{ $ville }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="properties-filter-group">
                    <label>Statut</label>
                    <select name="statut" id="statutFilter" onchange="filterProperties()">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>Approuvé</option>
                        <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                    </select>
                </div>

                <div class="properties-search-group">
                    <label>Rechercher</label>
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                        placeholder="Nom du bien, propriétaire..." onkeyup="searchProperties()">
                    <button type="button" class="properties-search-btn" onclick="searchProperties()">🔍</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Onglets -->
    <div class="properties-tabs">
        <button class="properties-tab {{ !request('statut') ? 'active' : '' }}" onclick="switchTab('all')">
            Tous les biens
        </button>
        <button class="properties-tab {{ request('statut') == 'en_attente' ? 'active' : '' }}" onclick="switchTab('pending')">
            En attente
            @if($stats['en_attente'] ?? 0 > 0)
                <span class="properties-tab-badge">{{ $stats['en_attente'] }}</span>
            @endif
        </button>
        <button class="properties-tab {{ request('statut') == 'approuve' ? 'active' : '' }}" onclick="switchTab('approved')">
            Approuvés
        </button>
        <button class="properties-tab {{ request('statut') == 'rejete' ? 'active' : '' }}" onclick="switchTab('rejected')">
            Rejetés
        </button>
    </div>

    <!-- Indicateur de chargement -->
    <div id="loadingIndicator" style="display: none; text-align: center; padding: 2rem;">
        <div class="loading-spinner"></div>
        <p style="margin-top: 1rem; color: var(--text-secondary);">Chargement des biens...</p>
    </div>

    <!-- Tableau des biens -->
    <div class="properties-table-wrapper">
        <table class="properties-table">
            <thead>
                <tr>
                    <th width="60"></th>
                    <th>Bien</th>
                    <th>Propriétaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="propertiesTableBody">
                <!-- Les biens seront chargés ici via JavaScript -->
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="properties-pagination" id="paginationContainer">
            <!-- La pagination sera générée ici -->
        </div>
    </div>

    <!-- Actions en masse -->
    <div class="properties-bulk-actions" id="bulkActions" style="display: none;">
        <div class="properties-selected-count" id="selectedCount">
            0 biens sélectionnés
        </div>
        <div class="properties-bulk-buttons">
            <button class="properties-action-btn properties-bulk-approve" onclick="bulkAction('approve')">
                ✅ Approuver la sélection
            </button>
            <button class="properties-action-btn properties-bulk-reject" onclick="bulkAction('reject')">
                ❌ Rejeter la sélection
            </button>
        </div>
    </div>
</div>

<!-- MODAL DÉTAILS -->
<div id="detailsModal" class="admin-modal">
    <div class="modal-content properties-detail-modal">
        <div class="modal-header">
            <h2>📋 Détails du bien immobilier</h2>
            <button class="modal-close" id="modalCloseBtn">&times;</button>
        </div>
        <div class="modal-body" id="propertyDetails">
            <div class="loading-spinner"></div>
            <p style="text-align: center; margin-top: 1rem;">Chargement des détails...</p>
        </div>
    </div>
</div>

<style>
/* ===== STYLES AMÉLIORÉS POUR LA PAGE PROPRIÉTÉS ===== */

.admin-properties-page {
    padding: 2rem;
    overflow-x: hidden;
    max-width: 100%;
    box-sizing: border-box;
}

/* En-tête */
.properties-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.properties-header h1 {
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
}

.properties-header p {
    color: var(--text-secondary);
    margin: 0;
}

/* ===== STATISTIQUES AMÉLIORÉES ===== */
.properties-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.properties-stat-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.properties-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), var(--success-color));
    opacity: 0;
    transition: opacity 0.3s;
}

.properties-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.properties-stat-card:hover::before {
    opacity: 1;
}

.properties-stat-card h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 600;
}

.properties-stat-number {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 0.5rem 0;
    color: var(--text-primary);
    line-height: 1.2;
}

.properties-stat-breakdown {
    font-size: 0.85rem;
    color: var(--text-secondary);
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
}

/* ===== FILTRES AMÉLIORÉS ===== */
.properties-filters-section {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.properties-filters-row {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    align-items: flex-end;
}

.properties-filter-group {
    flex: 1;
    min-width: 160px;
    display: flex;
    flex-direction: column;
}

.properties-filter-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.properties-filter-group select,
.properties-filter-group input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
    height: 46px;
    box-sizing: border-box;
    transition: all 0.2s;
}

.properties-filter-group select:focus,
.properties-filter-group input:focus {
    border-color: var(--accent-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
}

.properties-search-group {
    flex: 2;
    min-width: 260px;
    position: relative;
    display: flex;
    flex-direction: column;
}

.properties-search-group input {
    padding-right: 3rem;
    width: 100%;
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
    height: 46px;
    box-sizing: border-box;
    transition: all 0.2s;
}

.properties-search-group input:focus {
    border-color: var(--accent-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
}

.properties-search-btn {
    position: absolute;
    right: 0.5rem;
    bottom: 0.5rem;
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0.4rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.properties-search-btn:hover {
    background: var(--bg-secondary);
    color: var(--accent-color);
}

/* ===== ONGLETS AMÉLIORÉS ===== */
.properties-tabs {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.properties-tab {
    padding: 0.75rem 1.5rem;
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 30px;
    color: var(--text-primary);
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.properties-tab:hover {
    background: var(--bg-secondary);
    transform: translateY(-2px);
}

.properties-tab.active {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
}

.properties-tab-badge {
    background: var(--danger-color);
    color: white;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: bold;
}

/* ===== TABLEAU AMÉLIORÉ ===== */
.properties-table-wrapper {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 2rem;
    overflow-x: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.properties-table {
    width: 100%;
    min-width: 1100px;
    border-collapse: collapse;
}

.properties-table th {
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
    white-space: nowrap;
}

.properties-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
}

.properties-table tr:last-child td {
    border-bottom: none;
}

.properties-table tr:hover td {
    background: var(--bg-secondary);
}

/* Photo miniature */
.properties-photo-cell {
    width: 60px;
    padding: 0.5rem !important;
}

.properties-photo {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid var(--border-color);
    transition: transform 0.3s;
}

.properties-photo:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.properties-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Informations du bien */
.properties-info h4 {
    margin: 0 0 0.25rem 0;
    color: var(--text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.properties-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.properties-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Propriétaire */
.properties-owner {
    font-weight: 500;
    color: var(--text-primary);
    font-size: 0.9rem;
}

.properties-owner small {
    display: block;
    color: var(--text-secondary);
    font-size: 0.7rem;
    margin-top: 0.25rem;
}

/* Badges de statut */
.properties-status-badge {
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    letter-spacing: 0.5px;
}

.properties-status-en_attente {
    background: #FFD700;
    color: #000000;
}

.properties-status-approuve {
    background: #00AA00;
    color: #FFFFFF;
}

.properties-status-rejete {
    background: #FF0000;
    color: #FFFFFF;
}

.badge-success {
    background: #00AA00;
    color: #FFFFFF;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-danger {
    background: #FF0000;
    color: #FFFFFF;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

/* Actions par bien */
.properties-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.properties-actions button {
    padding: 0.4rem 0.8rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.properties-btn-approve {
    background: #00AA00;
    color: white;
}

.properties-btn-reject {
    background: #FF0000;
    color: white;
}

.properties-btn-view {
    background: var(--accent-color);
    color: var(--bg-primary);
}

.properties-btn-suspend {
    background: #FF6B00;
    color: white;
}

.properties-btn-reapprove {
    background: #00AA00;
    color: white;
}

.properties-actions button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.properties-actions button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Actions en masse */
.properties-bulk-actions {
    background: var(--card-bg);
    padding: 1rem 1.5rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
}

.properties-selected-count {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.95rem;
}

.properties-bulk-buttons {
    display: flex;
    gap: 1rem;
}

.properties-action-btn {
    padding: 0.6rem 1.2rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.3s;
}

.properties-bulk-approve {
    background: #00AA00;
    color: white;
}

.properties-bulk-reject {
    background: #FF0000;
    color: white;
}

.properties-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Pagination */
.properties-pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.properties-pagination nav {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    justify-content: center;
}

.properties-pagination a,
.properties-pagination span {
    color: var(--text-primary);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    transition: all 0.3s;
    display: inline-block;
    min-width: 40px;
    text-align: center;
}

.properties-pagination a:hover {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
}

.properties-pagination .active span {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
}

/* ===== MODAL DÉTAILS AMÉLIORÉ ===== */
#detailsModal {
    z-index: 2000 !important; /* Dominer le z-index 1000 du layout */
}

.properties-detail-modal {
    max-width: 1000px !important;
    width: 95% !important;
    padding: 0 !important;
    overflow: visible !important; /* FIX: hidden bloquait les clics */
    border-radius: 16px !important;
    display: flex;
    flex-direction: column;
}

.properties-detail-modal .modal-header {
    background: linear-gradient(135deg, var(--accent-color), var(--bg-secondary));
    color: var(--text-primary);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid var(--border-color);
    position: relative;
    z-index: 10; /* FIX: s'assurer que le header est au-dessus du body */
    border-radius: 16px 16px 0 0;
    flex-shrink: 0;
}

.properties-detail-modal .modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.properties-detail-modal .modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--text-primary);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
    position: relative;
    z-index: 9999;
    flex-shrink: 0;
    pointer-events: all;
}

.properties-detail-modal .modal-close:hover {
    background: rgba(0, 0, 0, 0.1);
    transform: rotate(90deg);
}

.properties-detail-modal .modal-body {
    padding: 2rem;
    max-height: 70vh;
    overflow-y: auto;
    border-radius: 0 0 16px 16px;
}

/* Loading spinner */
.loading-spinner {
    display: inline-block;
    width: 40px;
    height: 40px;
    border: 3px solid var(--border-color);
    border-radius: 50%;
    border-top-color: var(--accent-color);
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== STYLES POUR LE CONTENU DU MODAL ===== */
.property-detail-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Galerie photos */
.detail-gallery {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
}

.detail-gallery h3 {
    margin: 0 0 1rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 1 / 1;
    cursor: pointer;
    border: 2px solid var(--border-color);
    transition: all 0.3s;
}

.gallery-item:hover {
    transform: scale(1.05);
    border-color: var(--accent-color);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-item-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: white;
    padding: 0.5rem;
    font-size: 0.7rem;
    opacity: 0;
    transition: opacity 0.3s;
}

.gallery-item:hover .gallery-item-overlay {
    opacity: 1;
}

/* En-tête du détail */
.detail-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
    flex-wrap: wrap;
    gap: 1rem;
}

.detail-title h2 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1.8rem;
}

.detail-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.detail-price {
    background: var(--success-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 30px;
    font-weight: 700;
    font-size: 1.2rem;
    white-space: nowrap;
}

/* Grille d'informations */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-section {
    background: var(--bg-secondary);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
}

.detail-section h3 {
    margin: 0 0 1rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
}

.detail-item {
    display: flex;
    margin-bottom: 0.75rem;
    line-height: 1.5;
}

.detail-item-label {
    font-weight: 600;
    color: var(--text-secondary);
    width: 120px;
    flex-shrink: 0;
}

.detail-item-value {
    color: var(--text-primary);
    flex: 1;
}

.detail-description {
    background: var(--card-bg);
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    margin-top: 0.5rem;
    line-height: 1.6;
    color: var(--text-primary);
}

/* Actions du modal */
.detail-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 2px solid var(--border-color);
    flex-wrap: wrap;
    justify-content: flex-end;
}

.detail-action-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
}

.detail-action-btn.approve {
    background: #00AA00;
    color: white;
}

.detail-action-btn.reject {
    background: #FF0000;
    color: white;
}

.detail-action-btn.suspend {
    background: #FF6B00;
    color: white;
}

.detail-action-btn.close {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.detail-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-properties-page {
        padding: 1rem;
    }

    .properties-filters-row {
        flex-direction: column;
        gap: 1rem;
    }

    .properties-filter-group,
    .properties-search-group {
        min-width: 100%;
        width: 100%;
    }

    .properties-tabs {
        flex-wrap: wrap;
    }

    .properties-tab {
        flex: 1;
        min-width: 120px;
        text-align: center;
        justify-content: center;
    }

    .properties-actions {
        flex-direction: column;
    }

    .properties-actions button {
        width: 100%;
        justify-content: center;
    }

    .properties-bulk-actions {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .properties-bulk-buttons {
        flex-direction: column;
        width: 100%;
    }

    .properties-action-btn {
        width: 100%;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .detail-header {
        flex-direction: column;
    }

    .detail-price {
        align-self: flex-start;
    }

    .detail-item {
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-item-label {
        width: 100%;
    }

    .detail-actions {
        flex-direction: column;
    }

    .detail-action-btn {
        width: 100%;
        justify-content: center;
    }

    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .properties-stats-grid {
        grid-template-columns: 1fr;
    }

    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// ==================== VARIABLES GLOBALES ====================
let currentTab = 'all';
let currentPage = 1;
let selectedProperties = new Set();
let filters = {
    type: '{{ request('type') }}',
    ville: '{{ request('ville') }}',
    statut: '{{ request('statut') }}',
    search: '{{ request('search') }}'
};

// ==================== FONCTIONS PRINCIPALES ====================

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    loadProperties();
    setupEventListeners();
    setupModalEvents();
});

// Initialiser la page
function initializePage() {
    // Déterminer l'onglet actif basé sur le statut
    if (filters.statut === 'en_attente') {
        currentTab = 'pending';
    } else if (filters.statut === 'approuve') {
        currentTab = 'approved';
    } else if (filters.statut === 'rejete') {
        currentTab = 'rejected';
    } else {
        currentTab = 'all';
    }
}

// Afficher l'indicateur de chargement
function showLoading() {
    document.getElementById('loadingIndicator').style.display = 'block';
}

// Cacher l'indicateur de chargement
function hideLoading() {
    document.getElementById('loadingIndicator').style.display = 'none';
}

// Charger les propriétés
function loadProperties() {
    showLoading();

    const params = new URLSearchParams({
        page: currentPage,
        type: filters.type,
        ville: filters.ville,
        statut: filters.statut,
        search: filters.search
    });

    fetch(`/admin/properties/data?${params}`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            renderProperties(data.properties);
            renderPagination(data.pagination);
            hideLoading();
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('propertiesTableBody').innerHTML = `
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem;">
                        <div style="font-size: 1.2rem; color: #FF0000; margin-bottom: 1rem;">
                            ❌ Erreur de chargement
                        </div>
                        <p style="color: var(--text-secondary);">Veuillez réessayer.</p>
                    </td>
                </tr>
            `;
            hideLoading();
        });
}

// Afficher les propriétés
function renderProperties(properties) {
    const tbody = document.getElementById('propertiesTableBody');
    tbody.innerHTML = '';

    if (properties.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" style="text-align: center; padding: 3rem;">
                    <div style="font-size: 1.2rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        📭 Aucun bien trouvé
                    </div>
                    <p style="color: var(--text-secondary);">Aucun bien ne correspond à vos critères de recherche.</p>
                </td>
            </tr>
        `;
        return;
    }

    properties.forEach(property => {
        const row = createPropertyRow(property);
        tbody.appendChild(row);
    });

    updateBulkActions();
}

// Créer une ligne de propriété
function createPropertyRow(property) {
    const tr = document.createElement('tr');
    tr.dataset.id = property.id;
    tr.dataset.type = property.type;

    let statusBadge = '';
    if (property.statut_publication === 'en_attente') {
        statusBadge = '<span class="properties-status-badge properties-status-en_attente">En attente</span>';
    } else if (property.statut_publication === 'approuve') {
        statusBadge = '<span class="properties-status-badge properties-status-approuve">Approuvé</span>';
    } else if (property.statut_publication === 'rejete') {
        statusBadge = '<span class="properties-status-badge properties-status-rejete">Rejeté</span>';
    }

    let actions = '';
    if (property.statut_publication === 'en_attente') {
        actions = `
            <button class="properties-btn-approve" onclick="approveProperty(${property.id}, '${property.type}')">✅ Approuver</button>
            <button class="properties-btn-reject" onclick="rejectProperty(${property.id}, '${property.type}')">❌ Rejeter</button>
            <button class="properties-btn-view" onclick="viewDetails(${property.id}, '${property.type}')">👁️ Voir</button>
        `;
    } else if (property.statut_publication === 'approuve') {
        actions = `
            <button class="properties-btn-suspend" onclick="suspendProperty(${property.id}, '${property.type}')">⏸️ Suspendre</button>
            <button class="properties-btn-reject" onclick="rejectProperty(${property.id}, '${property.type}')">❌ Rejeter</button>
            <button class="properties-btn-view" onclick="viewDetails(${property.id}, '${property.type}')">👁️ Voir</button>
        `;
    } else if (property.statut_publication === 'rejete') {
        actions = `
            <button class="properties-btn-reapprove" onclick="approveProperty(${property.id}, '${property.type}')">✅ Réapprouver</button>
            <button class="properties-btn-view" onclick="viewDetails(${property.id}, '${property.type}')">👁️ Voir</button>
        `;
    }

    const photo = property.first_photo || '/images/default-property.jpg';

    tr.innerHTML = `
        <td class="properties-photo-cell">
            <div class="properties-photo" onclick="viewDetails(${property.id}, '${property.type}')">
                <img src="${photo}" alt="${property.nom || 'Bien'}">
            </div>
        </td>
        <td>
            <div class="properties-info">
                <h4>${property.nom || property.numero_appartement || 'Sans nom'}</h4>
                <div class="properties-meta">
                    <span>${property.type === 'maison' ? '🏠 Maison' : '🏢 Appartement'}</span>
                    <span>💰 ${numberFormat(property.prix || property.prix_mensuel || 0)} FCFA/mois</span>
                    <span>📍 ${property.ville || 'Non spécifiée'}</span>
                </div>
            </div>
        </td>
        <td>
            <div class="properties-owner">
                ${property.proprietaire?.nom || 'Propriétaire inconnu'}
                <small>📧 ${property.proprietaire?.email || 'Non disponible'}</small>
            </div>
        </td>
        <td>${statusBadge}</td>
        <td>${new Date(property.created_at).toLocaleDateString('fr-FR')}</td>
        <td><div class="properties-actions">${actions}</div></td>
    `;

    return tr;
}

// ==================== FONCTIONS D'ACTION ====================

// Approuver un bien
function approveProperty(id, type) {
    if (confirm('Voulez-vous vraiment approuver ce bien ? Il sera visible sur le site.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '⏳...';
        button.disabled = true;

        fetch(`/admin/properties/${type}/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Bien approuvé avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur lors de l\'approbation', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            showToast('Erreur lors de l\'approbation', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Rejeter un bien
function rejectProperty(id, type) {
    if (confirm('Voulez-vous vraiment rejeter ce bien ? Il ne sera pas visible sur le site.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '⏳...';
        button.disabled = true;

        fetch(`/admin/properties/${type}/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Bien rejeté avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur lors du rejet', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            showToast('Erreur lors du rejet', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Suspendre un bien
function suspendProperty(id, type) {
    if (confirm('Voulez-vous suspendre ce bien ? Il ne sera plus visible sur le site.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '⏳...';
        button.disabled = true;

        fetch(`/admin/properties/${type}/${id}/suspend`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Bien suspendu avec succès', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erreur lors de la suspension', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(error => {
            showToast('Erreur lors de la suspension', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Voir les détails
function viewDetails(id, type) {
    // Afficher le modal avec le spinner à l'intérieur
    document.getElementById('propertyDetails').innerHTML = `
        <div style="text-align: center; padding: 3rem;">
            <div class="loading-spinner" style="margin: 0 auto;"></div>
            <p style="margin-top: 1rem; color: var(--text-secondary);">Chargement des détails...</p>
        </div>
    `;
    document.getElementById('detailsModal').style.display = 'block';

    fetch(`/admin/properties/${type}/${id}/details`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            // Injecter le type maison/appartement dans la réponse
            data.property._kind = type;
            showPropertyDetails(data);
        })
        .catch(error => {
            document.getElementById('propertyDetails').innerHTML = `
                <div style="text-align: center; padding: 3rem; color: var(--danger-color);">
                    ❌ Erreur lors du chargement des détails. Veuillez réessayer.
                </div>
            `;
            showToast('Erreur lors du chargement des détails', 'error');
        });
}

// Afficher les détails d'un bien
function showPropertyDetails(data) {
    const property = data.property;
    const owner = data.owner;

    // _kind = 'maison' ou 'appartement' injecté par viewDetails()
    const kind = property._kind || (property.numero_appartement ? 'appartement' : 'maison');
    const kindLabel = kind === 'maison' ? 'Maison' : 'Appartement';

    // Type de logement (simple / sanitaires) — champ BDD 'type'
    const typeLogement = property.type || 'Non spécifié';

    // Formater les dates
    const createdDate = new Date(property.created_at).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric'
    });
    const updatedDate = new Date(property.updated_at).toLocaleDateString('fr-FR', {
        day: '2-digit', month: '2-digit', year: 'numeric'
    });

    // FIX 4 — Normaliser les chemins photos de façon robuste
    let photosArray = [];
    if (property.photos) {
        photosArray = property.photos.split(',')
            .map(photo => {
                photo = photo.trim();
                if (!photo) return null;
                // Déjà une URL complète
                if (photo.startsWith('http')) return photo;
                // Déjà préfixé /storage/
                if (photo.startsWith('/storage/')) return photo;
                // Préfixé /images/
                if (photo.startsWith('/images/')) return photo;
                // Sinon ajouter /storage/
                return '/storage/' + photo;
            })
            .filter(Boolean);
    }

    // Générer la galerie photos
    let galleryHtml = '';
    if (photosArray.length > 0) {
        galleryHtml = photosArray.map((photo, index) => `
            <div class="gallery-item" onclick="openImage('${photo}')">
                <img src="${photo}" alt="Photo ${index + 1}" onerror="this.src='/images/default-property.jpg'">
                <div class="gallery-item-overlay">Photo ${index + 1}</div>
            </div>
        `).join('');
    } else {
        galleryHtml = '<div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--text-secondary);">Aucune photo disponible</div>';
    }

    const detailsHtml = `
        <div class="property-detail-container">
            <!-- Galerie photos -->
            <div class="detail-gallery">
                <h3><span>📸</span> Galerie photos (${photosArray.length})</h3>
                <div class="gallery-grid">
                    ${galleryHtml}
                </div>
            </div>

            <!-- En-tête -->
            <div class="detail-header">
                <div class="detail-title">
                    <h2>${property.nom || property.numero_appartement || 'Bien sans nom'}</h2>
                    <div class="detail-badges">
                        ${property.statut_publication === 'en_attente' ? '<span class="properties-status-badge properties-status-en_attente">En attente</span>' : ''}
                        ${property.statut_publication === 'approuve' ? '<span class="properties-status-badge properties-status-approuve">Approuvé</span>' : ''}
                        ${property.statut_publication === 'rejete' ? '<span class="properties-status-badge properties-status-rejete">Rejeté</span>' : ''}
                        ${property.disponible ? '<span class="badge-success">Disponible</span>' : '<span class="badge-danger">Indisponible</span>'}
                    </div>
                </div>
                <div class="detail-price">
                    ${numberFormat(property.prix || property.prix_mensuel || 0)} FCFA/mois
                </div>
            </div>

            <!-- Grille d'informations -->
            <div class="detail-grid">
                <!-- Colonne 1 : Caractéristiques -->
                <div class="detail-section">
                    <h3><span>🏠</span> Caractéristiques</h3>
                    <div class="detail-item">
                        <span class="detail-item-label">Type :</span>
                        <span class="detail-item-value">${kindLabel}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Chambres :</span>
                        <span class="detail-item-value">${property.nombre_chambres || 0}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Salon :</span>
                        <span class="detail-item-value">${property.salon ? 'Oui' : 'Non'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Type logement :</span>
                        <span class="detail-item-value">${typeLogement}</span>
                    </div>
                </div>

                <!-- Colonne 2 : Localisation -->
                <div class="detail-section">
                    <h3><span>📍</span> Localisation</h3>
                    <div class="detail-item">
                        <span class="detail-item-label">Ville :</span>
                        <span class="detail-item-value">${property.ville || 'Non spécifiée'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Adresse :</span>
                        <span class="detail-item-value">${property.adresse || 'Non spécifiée'}</span>
                    </div>
                    ${property.latitude && property.longitude ? `
                    <div class="detail-item">
                        <span class="detail-item-label">Coordonnées :</span>
                        <span class="detail-item-value">${property.latitude}, ${property.longitude}</span>
                    </div>
                    ` : ''}
                </div>

                <!-- Colonne 3 : Propriétaire -->
                <div class="detail-section">
                    <h3><span>👤</span> Propriétaire</h3>
                    <div class="detail-item">
                        <span class="detail-item-label">Nom :</span>
                        <span class="detail-item-value">${owner?.nom || 'Non disponible'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Email :</span>
                        <span class="detail-item-value">${owner?.email || 'Non disponible'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Téléphone :</span>
                        <span class="detail-item-value">${owner?.indicatif_pays || ''} ${owner?.telephone || 'Non disponible'}</span>
                    </div>
                </div>

                <!-- Colonne 4 : Métadonnées -->
                <div class="detail-section">
                    <h3><span>📊</span> Métadonnées</h3>
                    <div class="detail-item">
                        <span class="detail-item-label">ID :</span>
                        <span class="detail-item-value">${property.id}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Publié le :</span>
                        <span class="detail-item-value">${createdDate}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item-label">Modifié le :</span>
                        <span class="detail-item-value">${updatedDate}</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="detail-section">
                <h3><span>📝</span> Description</h3>
                <div class="detail-description">
                    ${property.description || property.descriptin || 'Aucune description disponible'}
                </div>
            </div>

            <!-- Actions -->
            <div class="detail-actions">
                ${property.statut_publication === 'en_attente' ? `
                    <button class="detail-action-btn approve" onclick="approveProperty(${property.id}, '${kind}'); closeModal();">
                        ✅ Approuver
                    </button>
                    <button class="detail-action-btn reject" onclick="rejectProperty(${property.id}, '${kind}'); closeModal();">
                        ❌ Rejeter
                    </button>
                ` : ''}

                ${property.statut_publication === 'approuve' ? `
                    <button class="detail-action-btn suspend" onclick="suspendProperty(${property.id}, '${kind}'); closeModal();">
                        ⏸️ Suspendre
                    </button>
                    <button class="detail-action-btn reject" onclick="rejectProperty(${property.id}, '${kind}'); closeModal();">
                        ❌ Rejeter
                    </button>
                ` : ''}

                ${property.statut_publication === 'rejete' ? `
                    <button class="detail-action-btn approve" onclick="approveProperty(${property.id}, '${kind}'); closeModal();">
                        ✅ Réapprouver
                    </button>
                ` : ''}

                <button class="detail-action-btn close" onclick="closeModal()">
                    ✕ Fermer
                </button>
            </div>
        </div>
    `;

    document.getElementById('propertyDetails').innerHTML = detailsHtml;
    document.getElementById('detailsModal').style.display = 'block';
}

// Ouvrir une image en grand
function openImage(src) {
    window.open(src, '_blank');
}

// ==================== FONCTIONS DE FERMETURE DU MODAL ====================

// Fonction de fermeture unique et simplifiée
function closeModal() {
    const modal = document.getElementById('detailsModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function setupModalEvents() {
    const modal = document.getElementById('detailsModal');
    if (!modal) return;

    // Fermer en cliquant sur l'overlay (fond sombre) — pas sur le contenu
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Bouton × header — addEventListener plus fiable que onclick inline
    const closeBtn = document.getElementById('modalCloseBtn');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            closeModal();
        });
    }

    // Empêcher les clics sur le contenu du modal de fermer le modal
    const modalContent = modal.querySelector('.modal-content');
    if (modalContent) {
        modalContent.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
}

// ==================== FONCTIONS D'INTERFACE ====================

// Changer d'onglet
function switchTab(tab) {
    currentTab = tab;
    currentPage = 1;

    // Mettre à jour les filtres selon l'onglet
    if (tab === 'pending') filters.statut = 'en_attente';
    else if (tab === 'approved') filters.statut = 'approuve';
    else if (tab === 'rejected') filters.statut = 'rejete';
    else filters.statut = '';

    // Mettre à jour le select
    document.getElementById('statutFilter').value = filters.statut;

    loadProperties();

    // Mettre à jour l'URL
    const url = new URL(window.location);
    url.searchParams.set('statut', filters.statut || '');
    window.history.pushState({}, '', url);
}

// Filtrer les propriétés
function filterProperties() {
    filters.type = document.getElementById('typeFilter').value;
    filters.ville = document.getElementById('villeFilter').value;
    filters.statut = document.getElementById('statutFilter').value;
    filters.search = document.getElementById('searchInput').value;

    currentPage = 1;
    loadProperties();

    // Mettre à jour l'URL
    const url = new URL(window.location);
    url.searchParams.set('type', filters.type || '');
    url.searchParams.set('ville', filters.ville || '');
    url.searchParams.set('statut', filters.statut || '');
    url.searchParams.set('search', filters.search || '');
    window.history.pushState({}, '', url);
}

// Rechercher
function searchProperties() {
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        filterProperties();
    }, 500);
}

// Pagination
function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');

    if (!pagination || pagination.total <= pagination.per_page) {
        container.innerHTML = '';
        return;
    }

    let html = '<nav>';
    const current = pagination.current_page;
    const last = pagination.last_page;

    if (current > 1) {
        html += `<a href="#" onclick="changePage(${current - 1}); return false;">←</a>`;
    }

    for (let i = 1; i <= last; i++) {
        if (i === 1 || i === last || (i >= current - 2 && i <= current + 2)) {
            html += i === current
                ? `<span class="active"><span>${i}</span></span>`
                : `<a href="#" onclick="changePage(${i}); return false;">${i}</a>`;
        } else if (i === current - 3 || i === current + 3) {
            html += '<span>...</span>';
        }
    }

    if (current < last) {
        html += `<a href="#" onclick="changePage(${current + 1}); return false;">→</a>`;
    }

    html += '</nav>';
    container.innerHTML = html;
}

function changePage(page) {
    currentPage = page;
    loadProperties();
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return false;
}

// ==================== ACTIONS EN MASSE ====================

function updateBulkActions() {
    const selectedCount = selectedProperties.size;
    const bulkActions = document.getElementById('bulkActions');
    const selectedCountElement = document.getElementById('selectedCount');

    selectedCountElement.textContent = `${selectedCount} bien${selectedCount > 1 ? 's' : ''} sélectionné${selectedCount > 1 ? 's' : ''}`;

    if (selectedCount > 0) {
        bulkActions.style.display = 'flex';
    } else {
        bulkActions.style.display = 'none';
    }
}

function bulkAction(action) {
    if (selectedProperties.size === 0) {
        showToast('Aucun bien sélectionné', 'warning');
        return;
    }

    const message = action === 'approve'
        ? `Voulez-vous approuver ${selectedProperties.size} bien(s) ?`
        : `Voulez-vous rejeter ${selectedProperties.size} bien(s) ?`;

    if (!confirm(message)) return;

    const data = {
        ids: Array.from(selectedProperties),
        action: action
    };

    fetch('/admin/properties/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Action en masse réussie sur ${data.processed} bien(s)`, 'success');
            selectedProperties.clear();
            updateBulkActions();
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        showToast('Erreur lors de l\'action en masse', 'error');
    });
}

// ==================== UTILITAIRES ====================

function numberFormat(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

function setupEventListeners() {
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchProperties();
        }
    });
}

function showToast(message, type = 'success') {
    // Supprimer tout toast existant
    const existing = document.getElementById('properties-toast');
    if (existing) existing.remove();

    const toast = document.createElement('div');
    toast.id = 'properties-toast';
    toast.style.cssText = `
        position: fixed; top: 90px; right: 20px; z-index: 9999;
        padding: 1rem 1.5rem; border-radius: 8px; font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15); opacity: 0;
        transition: opacity 0.3s; max-width: 350px; font-size: 0.9rem;
        background: ${type === 'success' ? '#00AA00' : type === 'error' ? '#FF0000' : '#FFD700'};
        color: ${type === 'warning' ? '#000' : '#fff'};
    `;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => { toast.style.opacity = '1'; }, 50);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
@endsection
