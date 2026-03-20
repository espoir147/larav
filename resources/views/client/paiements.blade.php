@extends('layouts.client')

@section('client-content')
<div class="client-paiements-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">💰</span>
            Mes Paiements
        </h1>
        <p>Historique de tous vos paiements de location</p>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <form method="GET" action="{{ route('client.paiements') }}" class="filters-form">
            <div class="filter-group">
                <label for="mois" class="filter-label">Mois</label>
                <select id="mois" name="mois" class="filter-select">
                    <option value="">Tous les mois</option>
                    @foreach($mois as $m)
                        <option value="{{ $m }}" {{ $filtreMois == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label for="annee" class="filter-label">Année</label>
                <select id="annee" name="annee" class="filter-select">
                    <option value="">Toutes les années</option>
                    @foreach($annees as $a)
                        <option value="{{ $a }}" {{ $filtreAnnee == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-filter">
                <span class="btn-icon">🔍</span>
                <span>Filtrer</span>
            </button>

            @if($filtreMois || $filtreAnnee)
                <a href="{{ route('client.paiements') }}" class="btn-reset">
                    <span class="btn-icon">🗑️</span>
                    <span>Réinitialiser</span>
                </a>
            @endif
        </form>

        @if($filtreMois || $filtreAnnee)
            <div class="filter-info">
                <div class="info-badge">
                    <span class="badge-icon">📊</span>
                    <span><strong>{{ $paiements->total() }}</strong> paiement(s) trouvé(s)</span>
                </div>
                @if($filtreMois && $filtreAnnee)
                    <div class="info-period">pour <strong>{{ $filtreMois }} {{ $filtreAnnee }}</strong></div>
                @elseif($filtreMois)
                    <div class="info-period">pour <strong>{{ $filtreMois }}</strong></div>
                @elseif($filtreAnnee)
                    <div class="info-period">pour <strong>{{ $filtreAnnee }}</strong></div>
                @endif
                <div class="info-total">
                    <span class="total-label">Total :</span>
                    <span class="total-amount">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Liste des paiements -->
    @if($paiements->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">💰</div>
            <h3>Aucun paiement trouvé</h3>
            <p>Vous n'avez effectué aucun paiement{{ $filtreMois || $filtreAnnee ? ' pour cette période' : '' }}.</p>
            @if($filtreMois || $filtreAnnee)
                <a href="{{ route('client.paiements') }}" class="btn-primary">
                    <span>Voir tous les paiements</span>
                    <span class="arrow">→</span>
                </a>
            @endif
        </div>
    @else
        <div class="paiements-list">
            @foreach($paiements as $paiement)
                <div class="paiement-card">
                    <div class="paiement-header">
                        <div class="paiement-amount">
                            <span class="amount">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                            <span class="date">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</span>
                        </div>
                        <div class="paiement-status">
                            <span class="status-badge status-success">Payé</span>
                        </div>
                    </div>

                    <div class="paiement-body">
                        <div class="property-info">
                            <h4 class="property-name">
                                @if($paiement->maison)
                                    {{ $paiement->maison->nom }}
                                @elseif($paiement->appartement)
                                    Appartement {{ $paiement->appartement->numero_appartement }}
                                @else
                                    Bien non disponible
                                @endif
                            </h4>
                            <p class="property-address">
                                @if($paiement->maison)
                                    {{ $paiement->maison->adresse }}, {{ $paiement->maison->ville }}
                                @elseif($paiement->appartement)
                                    {{ $paiement->appartement->adresse }}, {{ $paiement->appartement->ville }}
                                @endif
                            </p>
                        </div>

                        <div class="paiement-details">
                            <div class="detail-chip">
                                <span class="chip-icon">📅</span>
                                <span>{{ $paiement->mois_paye }} {{ $paiement->annee_paye }}</span>
                            </div>
                            <div class="detail-chip">
                                <span class="chip-icon">📱</span>
                                <span>{{ $paiement->operateur->nom ?? 'Mobile Money' }}</span>
                            </div>
                            <div class="detail-chip">
                                <span class="chip-icon">🔖</span>
                                <span>Ref: {{ $paiement->reference_transaction }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="paiement-footer">
                        <button class="btn-action btn-view" onclick="showPaiementDetails({{ $paiement->id }})">
                            <span class="btn-icon">👁️</span>
                            <span>Détails</span>
                        </button>
                        <a href="{{ route('client.paiements.telecharger', $paiement->id) }}" class="btn-action btn-download">
                            <span class="btn-icon">📥</span>
                            <span>Reçu PDF</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($paiements->hasPages())
            <div class="pagination-wrapper">
                {{ $paiements->links('vendor.pagination.custom') }}
            </div>
        @endif
    @endif
</div>

<!-- Modal des détails -->
<div id="detailsModal" class="modal-overlay">
    <div class="modal modal-details">
        <div class="modal-header">
            <h2>
                <span class="header-icon">📄</span>
                Détails du paiement
            </h2>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="details-summary">
                <div class="summary-amount" id="detail-amount">--</div>
                <div class="summary-ref" id="detail-ref">--</div>
            </div>

            <div class="details-grid">
                <div class="details-row">
                    <div class="details-label">Date et heure</div>
                    <div class="details-value" id="detail-date">--</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Période concernée</div>
                    <div class="details-value" id="detail-periode">--</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Opérateur</div>
                    <div class="details-value" id="detail-operateur">--</div>
                </div>
                <div class="details-row">
                    <div class="details-label">Type de paiement</div>
                    <div class="details-value" id="detail-type">--</div>
                </div>
            </div>

            <div class="details-section">
                <h3 class="section-title">Bien concerné</h3>
                <div class="property-details">
                    <div class="details-row">
                        <div class="details-label">Nom</div>
                        <div class="details-value" id="detail-bien">--</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Adresse</div>
                        <div class="details-value" id="detail-adresse">--</div>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h3 class="section-title">Propriétaire</h3>
                <div class="owner-details">
                    <div class="details-row">
                        <div class="details-label">Nom</div>
                        <div class="details-value" id="detail-proprietaire">--</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Téléphone</div>
                        <div class="details-value" id="detail-telephone">--</div>
                    </div>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-secondary" onclick="closeModal()">Fermer</button>
                <a href="#" id="detail-download-link" class="btn-primary">
                    <span class="btn-icon">📥</span>
                    Télécharger le reçu
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== PAGE PAIEMENTS CLIENT AMÉLIORÉE ===== */
.client-paiements-page {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--client-border-light);
}

.page-header h1 {
    color: var(--client-text-primary);
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: linear-gradient(135deg, var(--client-primary) 0%, var(--client-secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-icon {
    font-size: 2.5rem;
    background: none;
    -webkit-text-fill-color: initial;
    color: var(--client-primary);
}

.page-header p {
    color: var(--client-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* ===== SECTION FILTRES ===== */
.filters-section {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--client-shadow-sm);
}

.filters-form {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.filter-group {
    flex: 1;
    min-width: 180px;
}

.filter-label {
    display: block;
    color: var(--client-text-primary);
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
}

.filter-select {
    width: 100%;
    padding: 0.7rem 1rem;
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.9rem;
    background: var(--client-bg-primary);
    color: var(--client-text-primary);
    cursor: pointer;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: var(--client-primary);
    box-shadow: 0 0 0 3px var(--client-primary-light);
}

.btn-filter, .btn-reset {
    padding: 0.7rem 1.5rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    text-decoration: none;
    height: 42px;
}

.btn-filter {
    background: var(--client-primary);
    color: white;
}

.btn-filter:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
}

.btn-reset {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
}

.btn-reset:hover {
    background: var(--client-border-light);
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 1rem;
}

.filter-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1rem;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    color: var(--client-text-primary);
    font-size: 0.95rem;
    border: 1px solid var(--client-border-light);
}

.info-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: var(--client-primary-light);
    border-radius: var(--radius-full);
    color: var(--client-primary);
    font-weight: 600;
}

.badge-icon {
    font-size: 1rem;
}

.info-period {
    color: var(--client-text-secondary);
}

.info-total {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-left: auto;
}

.total-label {
    color: var(--client-text-secondary);
    font-weight: 500;
}

.total-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--client-primary);
}

/* ===== LISTE DES PAIEMENTS ===== */
.paiements-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.paiement-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: var(--client-shadow-sm);
}

.paiement-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary);
}

.paiement-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.2rem 1.5rem;
    background: var(--client-bg-secondary);
    border-bottom: 1px solid var(--client-border-light);
}

.paiement-amount {
    display: flex;
    align-items: baseline;
    gap: 1rem;
}

.paiement-amount .amount {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--client-primary);
}

.paiement-amount .date {
    color: var(--client-text-tertiary);
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.3rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-success {
    background: var(--client-primary-light);
    color: var(--client-primary);
}

.paiement-body {
    padding: 1.5rem;
}

.property-info {
    margin-bottom: 1rem;
}

.property-name {
    color: var(--client-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.property-address {
    color: var(--client-text-tertiary);
    font-size: 0.9rem;
    margin: 0;
}

.paiement-details {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.detail-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.4rem 1rem;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    color: var(--client-text-secondary);
    border: 1px solid var(--client-border-light);
}

.chip-icon {
    font-size: 0.9rem;
    opacity: 0.7;
}

.paiement-footer {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 1.5rem 1.5rem 1.5rem;
}

.btn-action {
    flex: 1;
    padding: 0.8rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: var(--client-primary);
    color: white;
}

.btn-view:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
}

.btn-download {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
}

.btn-download:hover {
    background: var(--client-border-light);
    transform: translateY(-2px);
}

/* ===== ÉTAT VIDE ===== */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    box-shadow: var(--client-shadow-sm);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--client-text-primary);
    font-size: 1.8rem;
    margin: 0 0 1rem 0;
}

.empty-state p {
    color: var(--client-text-secondary);
    font-size: 1.1rem;
    margin: 0 0 2rem 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: var(--client-primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-full);
    font-weight: 700;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: var(--client-primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--client-shadow-lg);
    gap: 0.75rem;
}

/* ===== MODAL DÉTAILS ===== */
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
    background: var(--client-bg-card);
    border-radius: var(--radius-xl);
    width: 100%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--client-border-light);
    box-shadow: var(--client-shadow-xl);
    animation: modalFadeIn 0.3s;
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
    border-bottom: 1px solid var(--client-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modal-header h2 {
    color: var(--client-text-primary);
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--client-text-tertiary);
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
    background: var(--client-danger);
    color: white;
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

.details-summary {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--client-border-light);
}

.summary-amount {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--client-primary);
    margin-bottom: 0.5rem;
}

.summary-ref {
    color: var(--client-text-tertiary);
    font-size: 0.9rem;
}

.details-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
    background: var(--client-bg-secondary);
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--client-border-light);
}

.details-section {
    margin-bottom: 2rem;
    background: var(--client-bg-secondary);
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--client-border-light);
}

.section-title {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--client-border-light);
}

.details-row {
    display: flex;
    padding: 0.5rem 0;
    border-bottom: 1px dashed var(--client-border-light);
}

.details-row:last-child {
    border-bottom: none;
}

.details-label {
    width: 120px;
    color: var(--client-text-tertiary);
    font-size: 0.85rem;
    font-weight: 500;
}

.details-value {
    flex: 1;
    color: var(--client-text-primary);
    font-weight: 600;
    word-break: break-word;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--client-border-light);
}

.modal-actions button,
.modal-actions a {
    flex: 1;
    padding: 0.9rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    border: none;
}

.btn-secondary {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
}

.btn-secondary:hover {
    background: var(--client-border-light);
    transform: translateY(-2px);
}

/* ===== PAGINATION ===== */
.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .client-paiements-page {
        padding: 1rem;
    }

    .filters-form {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        min-width: 100%;
    }

    .btn-filter, .btn-reset {
        width: 100%;
        justify-content: center;
    }

    .filter-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .info-total {
        margin-left: 0;
        width: 100%;
        justify-content: space-between;
    }

    .paiement-header {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-start;
    }

    .paiement-amount {
        width: 100%;
        justify-content: space-between;
    }

    .paiement-footer {
        flex-direction: column;
    }

    .modal-actions {
        flex-direction: column;
    }

    .details-row {
        flex-direction: column;
        gap: 0.25rem;
    }

    .details-label {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 1.8rem;
    }

    .paiement-amount {
        flex-direction: column;
        gap: 0.25rem;
    }

    .paiement-details {
        flex-direction: column;
    }

    .detail-chip {
        width: 100%;
        justify-content: center;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-client-theme="dark"] .filters-section,
[data-client-theme="dark"] .paiement-card,
[data-client-theme="dark"] .empty-state,
[data-client-theme="dark"] .modal {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .filter-select {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .filter-info,
[data-client-theme="dark"] .details-grid,
[data-client-theme="dark"] .details-section {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .info-badge {
    background: rgba(52, 211, 153, 0.2);
    color: var(--client-primary);
}

[data-client-theme="dark"] .btn-reset {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .btn-reset:hover {
    background: var(--client-dark-border);
}

[data-client-theme="dark"] .btn-download {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .btn-download:hover {
    background: var(--client-dark-border);
}

[data-client-theme="dark"] .detail-chip {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .btn-secondary {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .btn-secondary:hover {
    background: var(--client-dark-border);
}

[data-client-theme="dark"] .modal-header {
    background: var(--client-dark-bg);
}
</style>

<script>
// ==================== MODAL DÉTAILS ====================
function showPaiementDetails(paiementId) {
    const modal = document.getElementById('detailsModal');
    const modalContent = modal.querySelector('.modal-body');

    modalContent.innerHTML = '<div class="loading-spinner"></div><p class="loading-text">Chargement...</p>';
    modal.style.display = 'flex';

    fetch(`/client/paiements/${paiementId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const p = data.paiement;

                document.getElementById('detail-ref').textContent = p.reference;
                document.getElementById('detail-date').textContent = p.date;
                document.getElementById('detail-amount').textContent = p.montant;
                document.getElementById('detail-periode').textContent = p.mois_annee;
                document.getElementById('detail-operateur').textContent = p.operateur;
                document.getElementById('detail-type').textContent = p.type_paiement;
                document.getElementById('detail-bien').textContent = p.nom_bien;
                document.getElementById('detail-adresse').textContent = p.adresse;
                document.getElementById('detail-proprietaire').textContent = p.proprietaire;
                document.getElementById('detail-telephone').textContent = p.telephone_proprietaire;
                document.getElementById('detail-download-link').href = `/client/paiements/${paiementId}/telecharger`;
            } else {
                modalContent.innerHTML = '<p class="error-text">Erreur de chargement</p>';
            }
        })
        .catch(() => {
            modalContent.innerHTML = '<p class="error-text">Erreur de connexion</p>';
        });
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// ==================== FERMETURE MODAL ====================
document.getElementById('detailsModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// ==================== ANIMATIONS ====================
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes
    document.querySelectorAll('.paiement-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.4s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Styles de chargement
    const style = document.createElement('style');
    style.textContent = `
        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--client-border-light);
            border-top-color: var(--client-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 2rem auto;
        }
        .loading-text {
            text-align: center;
            color: var(--client-text-secondary);
        }
        .error-text {
            text-align: center;
            color: var(--client-danger);
            padding: 2rem;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection
