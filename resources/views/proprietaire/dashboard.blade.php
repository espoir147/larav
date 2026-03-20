@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-dashboard-page">
    <!-- En-tête -->
    <div class="dashboard-header">
        <h1>
            <span class="header-icon">📊</span>
            Tableau de bord
        </h1>
        <p>Bienvenue, {{ Auth::user()->nom }} ! Voici un aperçu de votre activité.</p>
    </div>

    <!-- Cartes Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-content">
                <h3>Biens Immobiliers</h3>
                <p class="stat-number">{{ $totalBiens }}</p>
                <div class="stat-details">
                    <span class="detail-item">🏡 Maisons: {{ $maisonsCount }}</span>
                    <span class="detail-item">🏢 Apparts: {{ $appartementsCount }}</span>
                </div>
            </div>
            <div class="stat-glow"></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-content">
                <h3>Locataires Actifs</h3>
                <p class="stat-number">{{ $locatairesActifs }}</p>
                <div class="stat-details">
                    <span class="detail-item">🏡 Maisons: {{ $locatairesMaisons }}</span>
                    <span class="detail-item">🏢 Apparts: {{ $locatairesApparts }}</span>
                </div>
            </div>
            <div class="stat-glow"></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Revenus Mensuels</h3>
                <p class="stat-number">{{ number_format($revenusMensuels, 0, ',', ' ') }} FCFA</p>
                <div class="stat-details">
                    <span class="detail-item">📅 Ce mois</span>
                    <span class="detail-item">📊 Taux occupation: {{ $tauxOccupation }}%</span>
                </div>
            </div>
            <div class="stat-glow"></div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-content">
                <h3>Messages Non Lus</h3>
                <p class="stat-number">{{ $messagesNonLus }}</p>
                <div class="stat-details">
                    <span class="detail-item">⏳ Demandes: {{ $demandesAttente }}</span>
                    <span class="detail-item">⚠️ Urgent: {{ $messagesUrgents }}</span>
                </div>
            </div>
            <div class="stat-glow"></div>
        </div>
    </div>

    <!-- Grille Contenu -->
    <div class="content-grid">
        <!-- Derniers Paiements -->
        <div class="content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">💳</span>
                    Derniers Paiements Reçus
                </h4>
                <a href="{{ route('proprietaire.paiements') }}" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                @forelse($derniersPaiements as $paiement)
                <div class="paiement-item">
                    <div class="paiement-info">
                        <strong>{{ $paiement->utilisateur->nom }}</strong>
                        <span class="montant">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="paiement-meta">
                        <span class="bien">{{ $paiement->maison->nom ?? $paiement->appartement->numero_appartement }}</span>
                        <span class="date">{{ $paiement->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state small">
                    <div class="empty-icon">💰</div>
                    <p>Aucun paiement récent</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Demandes en Attente -->
        <div class="content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">⏳</span>
                    Demandes de Location
                </h4>
                <a href="{{ route('proprietaire.locataires') }}" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                @forelse($demandesLocation as $demande)
                <div class="demande-item">
                    <div class="demande-info">
                        <strong>{{ $demande->utilisateur->nom }}</strong>
                        <span class="badge badge-{{ $demande->statut }}">{{ $demande->statut }}</span>
                    </div>
                    <div class="demande-meta">
                        <span class="bien">{{ $demande->type_logement }} - #{{ $demande->id_logement }}</span>
                        <span class="date">{{ $demande->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="demande-actions">
                        <button class="btn-accept" onclick="accepterDemande({{ $demande->id }})">
                            <span class="btn-icon">✅</span>
                            <span>Accepter</span>
                        </button>
                        <button class="btn-refuse" onclick="refuserDemande({{ $demande->id }})">
                            <span class="btn-icon">❌</span>
                            <span>Refuser</span>
                        </button>
                    </div>
                </div>
                @empty
                <div class="empty-state small">
                    <div class="empty-icon">⏳</div>
                    <p>Aucune demande en attente</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Derniers Messages -->
        <div class="content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">✉️</span>
                    Messages Récents
                </h4>
                <a href="{{ route('proprietaire.messagerie') }}" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                @forelse($derniersMessages as $message)
                <div class="message-item {{ !$message->lu ? 'unread' : '' }}">
                    <div class="message-avatar">
                        <img src="{{ $message->expediteur->photo_profil ? asset('storage/' . $message->expediteur->photo_profil) : asset('images/default-avatar.png') }}" alt="Avatar">
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <strong>{{ $message->expediteur->nom }}</strong>
                            @if(!$message->lu)
                                <span class="unread-dot"></span>
                            @endif
                        </div>
                        <p class="message-preview">{{ Str::limit($message->contenu, 50) }}</p>
                        <small>{{ $message->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                @empty
                <div class="empty-state small">
                    <div class="empty-icon">✉️</div>
                    <p>Aucun message récent</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions Rapides - MODIFIÉ POUR 4 ÉLÉMENTS SUR LA MÊME LIGNE -->
    <div class="quick-actions-section">
        <h4>
            <span class="header-icon">⚡</span>
            Actions Rapides
        </h4>
        <div class="actions-grid-four">
            <a href="{{ route('proprietaire.biens') }}" class="quick-action">
                <span class="action-icon">➕</span>
                <span class="action-text">Ajouter un bien</span>
                <span class="action-hint">Publier une nouvelle annonce</span>
            </a>
            <a href="{{ route('proprietaire.messagerie') }}" class="quick-action">
                <span class="action-icon">✉️</span>
                <span class="action-text">Messagerie</span>
                <span class="action-hint">Voir vos conversations</span>
            </a>
            <a href="{{ route('proprietaire.paiements') }}" class="quick-action">
                <span class="action-icon">💰</span>
                <span class="action-text">Paiements</span>
                <span class="action-hint">Gérer vos paiements</span>
            </a>
            <a href="{{ route('proprietaire.annonces') }}" class="quick-action">
                <span class="action-icon">📢</span>
                <span class="action-text">Annonces</span>
                <span class="action-hint">Gérer vos annonces</span>
            </a>
        </div>
    </div>
</div>

<style>
/* ===== DASHBOARD PROPRIETAIRE AMÉLIORÉ ===== */
.proprio-dashboard-page {
    padding: 1.5rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE ===== */
.dashboard-header {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--proprio-border-light);
}

.dashboard-header h1 {
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

.dashboard-header p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

/* ===== STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.stat-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: var(--proprio-shadow-sm);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.stat-glow {
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(30, 64, 175, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s;
    pointer-events: none;
}

.stat-card:hover .stat-glow {
    opacity: 1;
}

.stat-icon {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--proprio-primary-light);
    border-radius: var(--radius-xl);
    color: var(--proprio-primary);
    transition: all 0.3s;
}

.stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
    background: var(--proprio-primary);
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-content h3 {
    color: var(--proprio-text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--proprio-text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item {
    color: var(--proprio-text-tertiary);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== GRILLE CONTENU ===== */
.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.content-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: var(--proprio-shadow-sm);
    height: fit-content;
}

.content-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
}

.card-header h4 {
    margin: 0;
    color: var(--proprio-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header .header-icon {
    font-size: 1.3rem;
}

.view-all {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--proprio-primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-lg);
    background: var(--proprio-primary-light);
    transition: all 0.3s;
}

.view-all:hover {
    background: var(--proprio-primary);
    color: white;
    transform: translateX(3px);
}

.view-all .arrow {
    font-size: 1.1rem;
    transition: transform 0.3s;
}

.view-all:hover .arrow {
    transform: translateX(3px);
}

.card-content {
    padding: 1.5rem;
    max-height: 400px;
    overflow-y: auto;
}

/* ===== ITEMS PAIEMENTS ===== */
.paiement-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--proprio-border-light);
    transition: all 0.3s;
}

.paiement-item:last-child {
    border-bottom: none;
}

.paiement-item:hover {
    background: var(--proprio-primary-light);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    border-radius: var(--radius-lg);
    transform: translateX(3px);
}

.paiement-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.paiement-info strong {
    color: var(--proprio-text-primary);
    font-size: 0.95rem;
}

.paiement-info .montant {
    color: var(--proprio-success);
    font-weight: 700;
    background: var(--proprio-primary-light);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
}

.paiement-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--proprio-text-tertiary);
}

.paiement-meta .bien {
    color: var(--proprio-primary);
}

/* ===== ITEMS DEMANDES ===== */
.demande-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--proprio-border-light);
    transition: all 0.3s;
}

.demande-item:last-child {
    border-bottom: none;
}

.demande-item:hover {
    background: var(--proprio-primary-light);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    border-radius: var(--radius-lg);
    transform: translateX(3px);
}

.demande-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.demande-info strong {
    color: var(--proprio-text-primary);
    font-size: 0.95rem;
}

.demande-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--proprio-text-tertiary);
    margin-bottom: 0.75rem;
}

.demande-meta .bien {
    color: var(--proprio-primary);
}

.demande-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-accept, .btn-refuse {
    flex: 1;
    padding: 0.5rem;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    transition: all 0.3s;
}

.btn-accept {
    background: var(--proprio-success);
    color: white;
}

.btn-accept:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-refuse {
    background: var(--proprio-danger);
    color: white;
}

.btn-refuse:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 0.9rem;
}

/* ===== ITEMS MESSAGES ===== */
.message-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--proprio-border-light);
    transition: all 0.3s;
}

.message-item:last-child {
    border-bottom: none;
}

.message-item:hover {
    background: var(--proprio-primary-light);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    border-radius: var(--radius-lg);
    transform: translateX(3px);
}

.message-item.unread {
    background: var(--proprio-primary-light);
    margin: 0 -0.5rem;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--proprio-primary);
}

.message-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--proprio-border-light);
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-content {
    flex: 1;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.25rem;
}

.message-header strong {
    color: var(--proprio-text-primary);
    font-size: 0.95rem;
}

.unread-dot {
    width: 8px;
    height: 8px;
    background: var(--proprio-primary);
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
}

.message-preview {
    color: var(--proprio-text-secondary);
    font-size: 0.85rem;
    margin: 0 0 0.25rem 0;
    line-height: 1.4;
}

.message-content small {
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
}

/* ===== ÉTAT VIDE ===== */
.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.empty-state.small {
    padding: 1.5rem 1rem;
}

.empty-icon {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    opacity: 0.5;
}

.empty-state p {
    color: var(--proprio-text-secondary);
    margin: 0;
}

/* ===== ACTIONS RAPIDES - 4 ÉLÉMENTS SUR LA MÊME LIGNE ===== */
.quick-actions-section {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-top: 1rem;
    box-shadow: var(--proprio-shadow-sm);
}

.quick-actions-section h4 {
    margin: 0 0 1.5rem 0;
    color: var(--proprio-text-primary);
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.actions-grid-four {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.quick-action {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 1.25rem;
    background: var(--proprio-bg-secondary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--proprio-text-primary);
    font-weight: 500;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.quick-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--proprio-primary), var(--proprio-secondary));
    opacity: 0;
    transition: opacity 0.3s;
}

.quick-action:hover {
    transform: translateY(-4px);
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
    box-shadow: var(--proprio-shadow-lg);
}

.quick-action:hover::before {
    opacity: 1;
}

.action-icon {
    font-size: 2.2rem;
    margin-bottom: 0.5rem;
}

.action-text {
    font-weight: 600;
    font-size: 0.95rem;
}

.action-hint {
    font-size: 0.75rem;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.quick-action:hover .action-hint {
    opacity: 1;
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-en_attente {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

.badge-acceptee {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border: 1px solid var(--proprio-primary);
}

.badge-refusee {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .content-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    /* Passage à 2 colonnes sur tablette */
    .actions-grid-four {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .proprio-dashboard-page {
        padding: 1rem;
    }

    .dashboard-header h1 {
        font-size: 1.8rem;
    }

    .dashboard-header p {
        font-size: 1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }

    /* Passage à 1 colonne sur mobile */
    .actions-grid-four {
        grid-template-columns: 1fr;
    }

    .quick-action {
        padding: 1rem;
        flex-direction: row;
        align-items: center;
    }

    .action-icon {
        font-size: 2rem;
        margin-bottom: 0;
    }

    .action-text {
        font-size: 0.95rem;
    }

    .action-hint {
        display: none;
    }

    .demande-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .dashboard-header h1 {
        font-size: 1.5rem;
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        font-size: 2rem;
    }

    .stat-number {
        font-size: 1.8rem;
    }

    .message-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .message-avatar {
        width: 40px;
        height: 40px;
    }

    .paiement-info,
    .paiement-meta {
        flex-direction: column;
        gap: 0.25rem;
        align-items: flex-start;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-card,
.content-card,
.quick-actions-section {
    animation: slideUp 0.5s ease-out forwards;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

.content-card:nth-child(1) { animation-delay: 0.2s; }
.content-card:nth-child(2) { animation-delay: 0.3s; }
.content-card:nth-child(3) { animation-delay: 0.4s; }

.quick-actions-section {
    animation-delay: 0.5s;
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .stat-card,
[data-proprietaire-theme="dark"] .content-card,
[data-proprietaire-theme="dark"] .quick-actions-section {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .stat-icon {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .stat-card:hover .stat-icon {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .card-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .card-header h4 {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .view-all {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .view-all:hover {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .message-item.unread {
    background: rgba(96, 165, 250, 0.15);
    border-left-color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .paiement-item:hover,
[data-proprietaire-theme="dark"] .demande-item:hover,
[data-proprietaire-theme="dark"] .message-item:hover {
    background: rgba(96, 165, 250, 0.1);
}

[data-proprietaire-theme="dark"] .badge-en_attente {
    background: rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    border-color: #f59e0b;
}

[data-proprietaire-theme="dark"] .badge-acceptee {
    background: rgba(96, 165, 250, 0.2);
    color: #60a5fa;
    border-color: #3b82f6;
}

[data-proprietaire-theme="dark"] .badge-refusee {
    background: rgba(239, 68, 68, 0.2);
    color: #f87171;
    border-color: #ef4444;
}

[data-proprietaire-theme="dark"] .quick-action {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .quick-action:hover {
    background: var(--proprio-primary);
    color: var(--proprio-dark-text);
}
</style>

<script>
// Fonctions pour les demandes
function accepterDemande(demandeId) {
    if (confirm('Voulez-vous accepter cette demande de location ?')) {
        showToast('Demande acceptée avec succès', 'success');
        // Ici votre logique AJAX
    }
}

function refuserDemande(demandeId) {
    if (confirm('Voulez-vous refuser cette demande de location ?')) {
        showToast('Demande refusée', 'info');
        // Ici votre logique AJAX
    }
}
</script>
@endsection
