@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-locataires-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">👥</span>
            Mes Locataires
        </h1>
        <p>Gérez vos locataires et suivez leurs paiements</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>Total locataires</h3>
                <p class="stat-number">{{ $locataires->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">Actuellement logés</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Loyers mensuels</h3>
                <p class="stat-number">{{ number_format($loyersMensuels ?? 0, 0, ',', ' ') }} FCFA</p>
                <div class="stat-details">
                    <span class="detail-item">Revenus mensuels totaux</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3>Paiements à jour</h3>
                <p class="stat-number">{{ $paiementsAJour ?? 0 }}</p>
                <div class="stat-details">
                    <span class="detail-item">sur {{ $locataires->count() }} locataires</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏰</div>
            <div class="stat-content">
                <h3>En retard</h3>
                <p class="stat-number">{{ ($locataires->count() - ($paiementsAJour ?? 0)) }}</p>
                <div class="stat-details">
                    <span class="detail-item">Paiements en retard</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des locataires -->
    <div class="locataires-list">
        @if($locataires->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>Aucun locataire</h3>
            <p>Vous n'avez pas encore de locataires. Les demandes de location acceptées apparaîtront ici.</p>
            <a href="{{ route('proprietaire.biens') }}" class="btn-primary">
                <span>Voir mes biens</span>
                <span class="arrow">→</span>
            </a>
        </div>
        @else
            @foreach($locataires as $location)
            @php
                $user = $location->utilisateur;
                $bien = $location->maison ?? $location->appartement;
                $prix = $bien->prix ?? $bien->prix_mensuel ?? 0;
                $dernierPaiement = $location->getDernierPaiement();
                $estEnRetard = $location->estEnRetard();
            @endphp
            <div class="locataire-card">
                <div class="locataire-header">
                    <div class="locataire-info">
                        <div class="locataire-avatar">
                            <img src="{{ $user->photo_profil ? asset('storage/' . $user->photo_profil) : asset('images/default-avatar.png') }}" alt="{{ $user->nom }}">
                        </div>
                        <div class="locataire-details">
                            <h3>{{ $user->nom }}</h3>
                            <div class="locataire-contact">
                                <span>📧 {{ $user->email }}</span>
                                <span>📞 {{ $user->indicatif_pays }} {{ $user->telephone }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="locataire-status">
                        <span class="badge badge-success">Actif</span>
                        @if($estEnRetard)
                        <span class="badge badge-danger">Paiement en retard</span>
                        @else
                        <span class="badge badge-success">À jour</span>
                        @endif
                    </div>
                </div>

                <div class="locataire-content">
                    <div class="bien-section">
                        <h4>
                            <span class="section-icon">🏠</span>
                            Bien loué
                        </h4>
                        <div class="bien-details">
                            @if($location->maison)
                            <div class="detail-row">
                                <span class="detail-label">Type :</span>
                                <span class="detail-value">Maison</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Nom :</span>
                                <span class="detail-value">{{ $bien->nom }}</span>
                            </div>
                            @else
                            <div class="detail-row">
                                <span class="detail-label">Type :</span>
                                <span class="detail-value">Appartement</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Numéro :</span>
                                <span class="detail-value">{{ $bien->numero_appartement }}</span>
                            </div>
                            @endif
                            <div class="detail-row">
                                <span class="detail-label">Adresse :</span>
                                <span class="detail-value">{{ $bien->adresse }}, {{ $bien->ville }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Loyer :</span>
                                <span class="detail-value highlight">{{ number_format($prix, 0, ',', ' ') }} FCFA/mois</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Depuis :</span>
                                <span class="detail-value">{{ $location->date_debut->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="paiement-section">
                        <h4>
                            <span class="section-icon">💰</span>
                            Dernier paiement
                        </h4>
                        @if($dernierPaiement)
                        <div class="paiement-details">
                            <div class="paiement-montant">
                                <span class="montant">{{ number_format($dernierPaiement->montant, 0, ',', ' ') }} FCFA</span>
                                <span class="date">le {{ $dernierPaiement->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="paiement-statut">
                                <span class="badge {{ $dernierPaiement->estRecent() ? 'badge-success' : 'badge-warning' }}">
                                    {{ $dernierPaiement->estRecent() ? 'Récent' : 'Ancien' }}
                                </span>
                            </div>
                        </div>
                        @else
                        <p class="no-paiement">Aucun paiement enregistré</p>
                        @endif
                    </div>
                </div>

                <div class="locataire-actions">
                    <a href="{{ route('proprietaire.paiements') }}?locataire={{ $user->id }}&bien={{ $location->id_logement }}&type={{ $location->type_logement }}"
                       class="action-btn btn-view">
                        <span class="btn-icon">📊</span>
                        <span>Voir paiements</span>
                    </a>

                    @if($user->telephone)
                    <a href="tel:{{ $user->indicatif_pays }}{{ $user->telephone }}"
                       class="action-btn btn-call">
                        <span class="btn-icon">📞</span>
                        <span>Appeler</span>
                    </a>
                    @endif

                    <a href="mailto:{{ $user->email }}"
                       class="action-btn btn-mail">
                        <span class="btn-icon">✉️</span>
                        <span>Email</span>
                    </a>

                    <form action="{{ route('proprietaire.location.terminer', $location->id) }}" method="POST" class="action-form"
                          onsubmit="return confirm('Êtes-vous sûr de vouloir résilier le contrat de {{ $user->nom }} ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-terminate">
                            <span class="btn-icon">🚪</span>
                            <span>Résilier</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            <!-- Pagination -->
            @if($locataires->hasPages())
            <div class="pagination">
                {{ $locataires->links() }}
            </div>
            @endif
        @endif
    </div>
</div>

<style>
/* ===== PAGE LOCATAIRES PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-locataires-page {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--proprio-border-light);
}

.page-header h1 {
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

.page-header p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* ===== STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
    box-shadow: var(--proprio-shadow-sm);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.stat-icon {
    font-size: 2.2rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--proprio-primary-light);
    border-radius: var(--radius-lg);
    color: var(--proprio-primary);
}

.stat-content {
    flex: 1;
}

.stat-content h3 {
    color: var(--proprio-text-secondary);
    font-size: 0.85rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--proprio-text-primary);
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
}

/* ===== LISTE LOCATAIRES ===== */
.locataires-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.locataire-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: var(--proprio-shadow-sm);
}

.locataire-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

/* En-tête locataire */
.locataire-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
    flex-wrap: wrap;
    gap: 1rem;
}

.locataire-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.locataire-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid var(--proprio-border-light);
}

.locataire-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.locataire-details h3 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
}

.locataire-contact {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    color: var(--proprio-text-secondary);
    font-size: 0.9rem;
}

.locataire-contact span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.locataire-status {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 0.4rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border: 1px solid var(--proprio-primary);
}

.badge-danger {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.badge-warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

/* Contenu */
.locataire-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    padding: 1.5rem;
}

.bien-section,
.paiement-section {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    border: 1px solid var(--proprio-border-light);
}

.bien-section h4,
.paiement-section h4 {
    color: var(--proprio-text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
}

.section-icon {
    font-size: 1.2rem;
}

.bien-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    padding: 0.25rem 0;
    border-bottom: 1px dashed var(--proprio-border-light);
}

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: var(--proprio-text-secondary);
    font-size: 0.85rem;
    font-weight: 500;
}

.detail-value {
    color: var(--proprio-text-primary);
    font-weight: 600;
    text-align: right;
}

.detail-value.highlight {
    color: var(--proprio-primary);
    font-size: 1.1rem;
}

/* Paiement */
.paiement-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.paiement-montant {
    display: flex;
    flex-direction: column;
}

.paiement-montant .montant {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--proprio-primary);
}

.paiement-montant .date {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
}

.no-paiement {
    color: var(--proprio-text-tertiary);
    font-style: italic;
    margin: 0;
}

/* Actions */
.locataire-actions {
    display: flex;
    gap: 0.75rem;
    padding: 1rem 1.5rem 1.5rem 1.5rem;
    flex-wrap: wrap;
}

.action-btn {
    flex: 1;
    min-width: 120px;
    padding: 0.8rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s;
    cursor: pointer;
    border: none;
}

.btn-view {
    background: var(--proprio-primary);
    color: white;
}

.btn-view:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

.btn-call {
    background: var(--proprio-success);
    color: white;
}

.btn-call:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-mail {
    background: var(--proprio-secondary);
    color: white;
}

.btn-mail:hover {
    background: var(--proprio-secondary-dark);
    transform: translateY(-2px);
}

.btn-terminate {
    background: var(--proprio-danger);
    color: white;
}

.btn-terminate:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 1rem;
}

.action-form {
    flex: 1;
    min-width: 120px;
}

.action-form button {
    width: 100%;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    box-shadow: var(--proprio-shadow-sm);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--proprio-text-primary);
    font-size: 1.8rem;
    margin: 0 0 1rem 0;
}

.empty-state p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0 0 2rem 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: var(--proprio-primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-full);
    font-weight: 700;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--proprio-shadow-lg);
    gap: 0.75rem;
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
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    padding: 0.5rem;
}

.pagination a,
.pagination span {
    color: var(--proprio-text-primary);
    text-decoration: none;
    padding: 0.5rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-md);
    transition: all 0.3s;
    display: inline-block;
}

.pagination a:hover {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

.pagination .active span {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .locataire-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

@media (max-width: 768px) {
    .proprio-locataires-page {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .locataire-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .locataire-info {
        width: 100%;
    }

    .locataire-contact {
        flex-direction: column;
        gap: 0.25rem;
    }

    .locataire-actions {
        flex-direction: column;
    }

    .action-btn,
    .action-form {
        width: 100%;
        min-width: auto;
    }

    .detail-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .detail-value {
        text-align: left;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .locataire-status {
        width: 100%;
        justify-content: flex-start;
    }

    .page-header h1 {
        font-size: 1.8rem;
    }

    .paiement-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card,
.locataire-card {
    animation: fadeIn 0.5s ease-out forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

.locataire-card:nth-child(1) { animation-delay: 0.2s; }
.locataire-card:nth-child(2) { animation-delay: 0.3s; }
.locataire-card:nth-child(3) { animation-delay: 0.4s; }

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .stat-card,
[data-proprietaire-theme="dark"] .locataire-card,
[data-proprietaire-theme="dark"] .empty-state {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .stat-icon {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .locataire-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .bien-section,
[data-proprietaire-theme="dark"] .paiement-section {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .detail-label {
    color: #94a3b8;
}

[data-proprietaire-theme="dark"] .detail-value {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .badge-success {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
    border-color: #3b82f6;
}

[data-proprietaire-theme="dark"] .badge-danger {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border-color: #ef4444;
}

[data-proprietaire-theme="dark"] .badge-warning {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    border-color: #f59e0b;
}

[data-proprietaire-theme="dark"] .pagination nav {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .pagination a {
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .pagination a:hover {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
    border-color: var(--proprio-primary);
}
</style>
@endsection
