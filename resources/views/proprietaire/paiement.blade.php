@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-paiements-page">
    <!-- En-tête -->
    <div class="page-header">
        <div class="header-title">
            <h1>
                <span class="header-icon">💰</span>
                Mes Paiements
            </h1>
            <p>Historique et gestion des paiements reçus</p>
        </div>

        <!-- Bouton d'export -->
        <div class="export-dropdown">
            <button class="btn-export" id="exportBtn">
                <span class="btn-icon">📥</span>
                <span>Exporter</span>
                <span class="dropdown-icon">▼</span>
            </button>

            <div class="export-menu" id="exportMenu">
                <a href="{{ route('proprietaire.paiements.exporter.pdf', ['periode' => 'mois']) }}" class="export-item">
                    <span class="item-icon">📄</span>
                    <span class="item-text">Exporter ce mois</span>
                </a>
                <a href="{{ route('proprietaire.paiements.exporter.pdf', ['periode' => 'trimestre']) }}" class="export-item">
                    <span class="item-icon">📅</span>
                    <span class="item-text">Exporter 3 derniers mois</span>
                </a>
                <a href="{{ route('proprietaire.paiements.exporter.pdf', ['periode' => 'annee']) }}" class="export-item">
                    <span class="item-icon">📊</span>
                    <span class="item-text">Exporter cette année</span>
                </a>
                <div class="export-item" onclick="openCustomExportModal()">
                    <span class="item-icon">⚙️</span>
                    <span class="item-text">Personnaliser l'export...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <h3>🔍 Filtrer les paiements</h3>

        <form method="GET" action="{{ route('proprietaire.paiements') }}" class="filters-form">
            <div class="filters-grid">
                <!-- Filtre par locataire -->
                <div class="filter-group">
                    <label for="locataire_id">Locataire</label>
                    <select name="locataire_id" id="locataire_id" class="filter-select">
                        <option value="">Tous les locataires</option>
                        @foreach($filtres['locataires'] ?? [] as $locataire)
                        <option value="{{ $locataire->id }}" {{ request('locataire_id') == $locataire->id ? 'selected' : '' }}>
                            {{ $locataire->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtre par type de bien -->
                <div class="filter-group">
                    <label for="type_bien">Type de bien</label>
                    <select name="type_bien" id="type_bien" class="filter-select">
                        <option value="">Tous les biens</option>
                        <option value="maison" {{ request('type_bien') == 'maison' ? 'selected' : '' }}>Maisons</option>
                        <option value="appartement" {{ request('type_bien') == 'appartement' ? 'selected' : '' }}>Appartements</option>
                    </select>
                </div>

                <!-- Filtre par période -->
                <div class="filter-group">
                    <label for="periode">Période</label>
                    <select name="periode" id="periode" class="filter-select">
                        <option value="">Toutes périodes</option>
                        <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
                        <option value="trimestre" {{ request('periode') == 'trimestre' ? 'selected' : '' }}>3 derniers mois</option>
                        <option value="annee" {{ request('periode') == 'annee' ? 'selected' : '' }}>Cette année</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="filter-actions">
                    <button type="submit" class="btn-primary">
                        <span class="btn-icon">🔍</span>
                        <span>Filtrer</span>
                    </button>
                    <a href="{{ route('proprietaire.paiements') }}" class="btn-secondary">
                        <span class="btn-icon">🗑️</span>
                        <span>Réinitialiser</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="payments-section">
        <div class="section-header">
            <h2>
                <span class="section-icon">📊</span>
                Historique des paiements ({{ $paiements->total() }})
            </h2>
        </div>

        @if($paiements->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💰</div>
            <h3>Aucun paiement trouvé</h3>
            <p>Les paiements reçus apparaîtront ici</p>
        </div>
        @else
        <div class="payments-list">
            @foreach($paiements as $paiement)
            <div class="payment-card">
                <div class="payment-header">
                    <div class="payment-ref">
                        <span class="ref-number">#{{ str_pad($paiement->id, 6, '0', STR_PAD_LEFT) }}</span>
                        <span class="payment-date">{{ $paiement->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="payment-actions-header">
                        <span class="badge badge-success">{{ ucfirst($paiement->statut ?? 'payé') }}</span>
                        <a href="{{ route('proprietaire.paiements.recu', $paiement->id) }}" class="btn-receipt" title="Télécharger le reçu">
                            <span class="btn-icon">📄</span>
                            <span>Reçu</span>
                        </a>
                    </div>
                </div>

                <div class="payment-content">
                    <div class="payment-info-grid">
                        <!-- Informations du locataire -->
                        <div class="info-block">
                            <h4>Locataire</h4>
                            <div class="info-details">
                                <div class="info-row">
                                    <span class="info-label">Nom :</span>
                                    <span class="info-value">{{ $paiement->utilisateur->nom ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Email :</span>
                                    <span class="info-value">{{ $paiement->utilisateur->email ?? 'N/A' }}</span>
                                </div>
                                @if($paiement->utilisateur->telephone ?? false)
                                <div class="info-row">
                                    <span class="info-label">Téléphone :</span>
                                    <span class="info-value">{{ $paiement->utilisateur->indicatif_pays ?? '' }} {{ $paiement->utilisateur->telephone ?? '' }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Informations du paiement -->
                        <div class="info-block">
                            <h4>Détails du paiement</h4>
                            <div class="info-details">
                                <div class="info-row">
                                    <span class="info-label">Montant :</span>
                                    <span class="info-value amount">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Bien :</span>
                                    <span class="info-value">
                                        @if($paiement->maison)
                                            Maison : {{ $paiement->maison->nom ?? 'N/A' }}
                                        @elseif($paiement->appartement)
                                            Appartement n°{{ $paiement->appartement->numero_appartement ?? 'N/A' }}
                                        @else
                                            N/A
                                        @endif
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">Méthode :</span>
                                    <span class="info-value">{{ $paiement->operateur->nom ?? 'Mobile Money' }}</span>
                                </div>
                                @if($paiement->reference_transaction)
                                <div class="info-row">
                                    <span class="info-label">Référence :</span>
                                    <span class="info-value ref">{{ $paiement->reference_transaction }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($paiements->hasPages())
        <div class="pagination-wrapper">
            {{ $paiements->links() }}
        </div>
        @endif
        @endif
    </div>
</div>

<!-- Modal pour export personnalisé -->
<div id="customExportModal" class="modal-overlay">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h2>
                <span class="header-icon">⚙️</span>
                Exporter les paiements
            </h2>
            <button class="modal-close" onclick="closeCustomExportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="exportForm" method="GET" action="{{ route('proprietaire.paiements.exporter.pdf') }}">
                <div class="form-group">
                    <label class="form-label">Période personnalisée</label>
                    <div class="date-range">
                        <div class="date-field">
                            <label>Du</label>
                            <input type="date" name="date_debut" class="form-input">
                        </div>
                        <div class="date-field">
                            <label>Au</label>
                            <input type="date" name="date_fin" class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Options</label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="avec_total" value="1" checked>
                        <span>Inclure le total général</span>
                    </label>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" onclick="closeCustomExportModal()">Annuler</button>
                    <button type="submit" class="btn-primary">
                        <span class="btn-icon">📥</span>
                        Générer le PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ===== PAGE PAIEMENTS PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-paiements-page {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--proprio-border-light);
    flex-wrap: wrap;
    gap: 1rem;
}

.header-title h1 {
    color: var(--proprio-text-primary);
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, var(--proprio-primary) 0%, var(--proprio-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-icon {
    font-size: 2.5rem;
    background: none;
    -webkit-text-fill-color: initial;
    color: var(--proprio-primary);
}

.header-title p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* ===== BOUTON EXPORT ===== */
.export-dropdown {
    position: relative;
}

.btn-export {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.5rem;
    background: var(--proprio-primary);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-export:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

.btn-icon {
    font-size: 1rem;
}

.dropdown-icon {
    font-size: 0.8rem;
    margin-left: 0.25rem;
}

.export-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 0.5rem;
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    box-shadow: var(--proprio-shadow-lg);
    min-width: 220px;
    z-index: 100;
    overflow: hidden;
}

.export-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.8rem 1.2rem;
    color: var(--proprio-text-primary);
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
    border-bottom: 1px solid var(--proprio-border-light);
}

.export-item:last-child {
    border-bottom: none;
}

.export-item:hover {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
}

.item-icon {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.item-text {
    flex: 1;
    font-size: 0.9rem;
}

/* ===== FILTRES ===== */
.filters-section {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--proprio-shadow-sm);
}

.filters-section h3 {
    color: var(--proprio-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    color: var(--proprio-text-primary);
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.filter-select {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.9rem;
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
    cursor: pointer;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 0.7rem 1rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
    text-decoration: none;
    border: none;
}

.btn-primary {
    background: var(--proprio-primary);
    color: white;
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

.btn-secondary {
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
    border: 1px solid var(--proprio-border-light);
}

.btn-secondary:hover {
    background: var(--proprio-border-light);
    transform: translateY(-2px);
}

/* ===== SECTION PAIEMENTS ===== */
.payments-section {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    box-shadow: var(--proprio-shadow-sm);
}

.section-header {
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--proprio-border-light);
}

.section-header h2 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-icon {
    font-size: 1.5rem;
}

/* ===== LISTE PAIEMENTS ===== */
.payments-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.payment-card {
    background: var(--proprio-bg-secondary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s;
}

.payment-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
    border-color: var(--proprio-primary);
}

.payment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--proprio-bg-card);
    border-bottom: 1px solid var(--proprio-border-light);
    flex-wrap: wrap;
    gap: 1rem;
}

.payment-ref {
    display: flex;
    flex-direction: column;
}

.ref-number {
    font-weight: 700;
    color: var(--proprio-primary);
    font-size: 1rem;
}

.payment-date {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
}

.payment-actions-header {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}

.badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
}

.btn-receipt {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: var(--proprio-bg-secondary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-md);
    color: var(--proprio-text-primary);
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-receipt:hover {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

.btn-receipt .btn-icon {
    font-size: 0.8rem;
}

.payment-content {
    padding: 1.5rem;
}

.payment-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
}

.info-block {
    background: var(--proprio-bg-card);
    padding: 1rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--proprio-border-light);
}

.info-block h4 {
    color: var(--proprio-text-primary);
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0 0 0.75rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
}

.info-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 0.9rem;
}

.info-label {
    color: var(--proprio-text-tertiary);
    font-weight: 500;
}

.info-value {
    color: var(--proprio-text-primary);
    font-weight: 600;
}

.info-value.amount {
    color: var(--proprio-primary);
    font-size: 1.1rem;
}

.info-value.ref {
    font-family: monospace;
    font-size: 0.8rem;
}

/* ===== ÉTAT VIDE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    margin: 0 0 0.5rem 0;
}

.empty-state p {
    color: var(--proprio-text-secondary);
    margin: 0;
}

/* ===== MODAL ===== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.modal {
    background: var(--proprio-bg-card);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 500px;
    border: 1px solid var(--proprio-border-light);
    box-shadow: var(--proprio-shadow-xl);
    animation: modalFadeIn 0.3s;
}

.modal-sm {
    max-width: 450px;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--proprio-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.modal-header h2 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--proprio-text-tertiary);
    cursor: pointer;
    transition: all 0.3s;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.modal-close:hover {
    background: var(--proprio-danger);
    color: white;
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

/* Formulaire modal */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: var(--proprio-text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.date-range {
    display: flex;
    gap: 1rem;
}

.date-field {
    flex: 1;
}

.date-field label {
    display: block;
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.form-input {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
}

.form-input:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--proprio-text-primary);
    font-size: 0.9rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--proprio-border-light);
}

.modal-actions button {
    flex: 1;
    padding: 0.8rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    border: none;
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .payment-info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .proprio-paiements-page {
        padding: 1rem;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .export-dropdown {
        width: 100%;
    }

    .btn-export {
        width: 100%;
        justify-content: center;
    }

    .filters-grid {
        grid-template-columns: 1fr;
    }

    .filter-actions {
        flex-direction: column;
    }

    .payment-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .payment-actions-header {
        width: 100%;
        justify-content: space-between;
    }

    .info-row {
        flex-direction: column;
        gap: 0.25rem;
    }

    .modal-actions {
        flex-direction: column;
    }

    .date-range {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .header-title h1 {
        font-size: 1.8rem;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .filters-section,
[data-proprietaire-theme="dark"] .payments-section,
[data-proprietaire-theme="dark"] .payment-card,
[data-proprietaire-theme="dark"] .modal,
[data-proprietaire-theme="dark"] .export-menu {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .payment-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .info-block {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .filter-select {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-secondary {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-secondary:hover {
    background: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .btn-receipt {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-receipt:hover {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .export-item {
    border-bottom-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .export-item:hover {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .modal-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .form-input {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}
</style>

<script>
// ==================== GESTION MENU EXPORT ====================
document.getElementById('exportBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    const menu = document.getElementById('exportMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    const menu = document.getElementById('exportMenu');
    const btn = document.getElementById('exportBtn');
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.style.display = 'none';
    }
});

// ==================== GESTION MODAL EXPORT ====================
function openCustomExportModal() {
    document.getElementById('customExportModal').style.display = 'flex';
    document.getElementById('exportMenu').style.display = 'none';

    // Pré-remplir les dates
    const today = new Date().toISOString().split('T')[0];
    const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1)
        .toISOString().split('T')[0];

    const dateDebut = document.querySelector('input[name="date_debut"]');
    const dateFin = document.querySelector('input[name="date_fin"]');

    if (dateDebut && !dateDebut.value) dateDebut.value = firstDayOfMonth;
    if (dateFin && !dateFin.value) dateFin.value = today;
}

function closeCustomExportModal() {
    document.getElementById('customExportModal').style.display = 'none';
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('customExportModal').addEventListener('click', function(e) {
    if (e.target === this) closeCustomExportModal();
});
</script>
@endsection
