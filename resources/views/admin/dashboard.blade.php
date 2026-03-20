@extends('layouts.admin')

@section('admin-content')
<div class="admin-dashboard">
    <!-- Cartes Statistiques -->
    <div class="admin-stats-grid">
        <div class="stat-card">
            <h3>👥 Utilisateurs</h3>
            <p class="stat-number">{{ $totalUsers }}</p>
            <div class="stat-breakdown">
                <span class="breakdown-item">
                    <span class="badge badge-actif">●</span>
                    Actifs: {{ $activeUsers }}
                </span>
                <span class="breakdown-item">
                    <span class="badge badge-en_attente">●</span>
                    En attente: {{ $pendingUsers }}
                </span>
                <span class="breakdown-item">
                    <span class="badge badge-bloque">●</span>
                    Bloqués: {{ $blockedUsers }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <h3>🏠 Biens</h3>
            <p class="stat-number">{{ $totalProperties }}</p>
            <div class="stat-breakdown">
                <span class="breakdown-item">🏡 Maisons: {{ $maisonsCount }}</span>
                <span class="breakdown-item">🏢 Apparts: {{ $appartementsCount }}</span>
                <span class="breakdown-item">
                    <span class="badge badge-disponible">●</span>
                    Disponibles: {{ $availableProperties }}
                </span>
            </div>
        </div>

        <div class="stat-card">
            <h3>💰 Revenus Totaux</h3>
            <p class="stat-number">{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</p>
            <div class="stat-breakdown">
                <span class="breakdown-item">📅 Ce mois: {{ number_format($monthRevenue, 0, ',', ' ') }} FCFA</span>
                <span class="breakdown-item">⏱️ Aujourd'hui: {{ number_format($todayRevenue, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <div class="stat-card">
            <h3>📝 Transactions</h3>
            <p class="stat-number">{{ $totalTransactions }}</p>
            <div class="stat-breakdown">
                <span class="breakdown-item">📅 Ce mois: {{ $monthTransactions }}</span>
                <span class="breakdown-item">⏱️ Aujourd'hui: {{ $todayTransactions }}</span>
            </div>
        </div>
    </div>

    <!-- Listes Récentes -->
    <div class="admin-recent-grid">
        <!-- Derniers utilisateurs -->
        <div class="recent-card">
            <h4>👤 Nouveaux utilisateurs (7 jours)</h4>
            <ul class="recent-list">
                @forelse($recentUsers as $user)
                <li class="recent-item">
                    <div class="recent-item-content">
                        <div class="recent-item-header">
                            <strong>{{ $user->nom }}</strong>
                            <span class="badge badge-{{ $user->statut }}">{{ $user->statut }}</span>
                        </div>
                        <div class="recent-item-details">
                            <span class="recent-item-email">{{ $user->email }}</span>
                            <span class="recent-item-date">📅 {{ $user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </li>
                @empty
                <li class="recent-item empty">Aucun nouvel utilisateur</li>
                @endforelse
            </ul>
            <a href="{{ route('admin.users') }}" class="view-all-link">
                <span>Voir tous les utilisateurs</span>
                <span class="arrow">→</span>
            </a>
        </div>

        <!-- Derniers biens -->
        <div class="recent-card">
            <h4>🏠 Nouveaux biens (7 jours)</h4>
            <ul class="recent-list">
                @forelse($recentProperties as $property)
                <li class="recent-item">
                    <div class="recent-item-content">
                        <div class="recent-item-header">
                            <strong>{{ $property->nom }}</strong>
                            <span class="badge {{ $property->disponible ? 'badge-disponible' : 'badge-loue' }}">
                                {{ $property->disponible ? 'Disponible' : 'Loué' }}
                            </span>
                        </div>
                        <div class="recent-item-details">
                            <span class="recent-item-price">{{ number_format($property->prix, 0, ',', ' ') }} FCFA</span>
                            <span class="recent-item-location">📍 {{ $property->ville }}</span>
                            <span class="recent-item-type">{{ $property->type }}</span>
                        </div>
                    </div>
                </li>
                @empty
                <li class="recent-item empty">Aucun nouveau bien</li>
                @endforelse
            </ul>
            <a href="{{ route('admin.properties') }}" class="view-all-link">
                <span>Voir tous les biens</span>
                <span class="arrow">→</span>
            </a>
        </div>

        <!-- Dernières transactions -->
        <div class="recent-card">
            <h4>💳 Transactions récentes (24h)</h4>
            <ul class="recent-list">
                @forelse($recentTransactions as $transaction)
                <li class="recent-item">
                    <div class="recent-item-content">
                        <div class="recent-item-header">
                            <strong>{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</strong>
                            <span class="badge badge-success">Payé</span>
                        </div>
                        <div class="recent-item-details">
                            <span class="recent-item-ref">📄 {{ $transaction->reference_transaction }}</span>
                            <span class="recent-item-date">⏱️ {{ $transaction->created_at->format('H:i d/m/Y') }}</span>
                        </div>
                    </div>
                </li>
                @empty
                <li class="recent-item empty">Aucune transaction récente</li>
                @endforelse
            </ul>
            <a href="{{ route('admin.transactions') }}" class="view-all-link">
                <span>Voir toutes les transactions</span>
                <span class="arrow">→</span>
            </a>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="quick-actions">
        <h4>⚡ Actions Rapides</h4>
        <div class="action-buttons-grid">
            <a href="{{ route('admin.users') }}?filter=en_attente" class="quick-action-card">
                <span class="quick-action-icon">👥</span>
                <span class="quick-action-text">Valider inscriptions</span>
                <span class="quick-action-badge">{{ $pendingUsers }}</span>
            </a>

            <a href="{{ route('admin.properties') }}?filter=en_attente" class="quick-action-card">
                <span class="quick-action-icon">🏠</span>
                <span class="quick-action-text">Vérifier nouveaux biens</span>
                <span class="quick-action-badge">{{ $pendingProperties ?? 0 }}</span>
            </a>

            <a href="{{ route('admin.transactions') }}" class="quick-action-card">
                <span class="quick-action-icon">💰</span>
                <span class="quick-action-text">Voir transactions</span>
                <span class="quick-action-badge">{{ $todayTransactions }}</span>
            </a>

            <a href="{{ route('admin.logs') }}" class="quick-action-card">
                <span class="quick-action-icon">📝</span>
                <span class="quick-action-text">Journal activité</span>
            </a>
        </div>
    </div>
</div>

<style>
/* ===== STYLES DASHBOARD ADMIN ===== */

.admin-dashboard {
    padding: 1rem 0;
    width: 100%;
}

/* ===== GRILLE STATISTIQUES ===== */
.admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-number {
    font-size: 2.8rem;
    font-weight: 700;
    margin: 0.5rem 0;
    color: var(--text-primary);
    line-height: 1.2;
}

.stat-breakdown {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.breakdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.breakdown-item .badge {
    width: 8px;
    height: 8px;
    padding: 0;
    border-radius: 50%;
    display: inline-block;
}

/* ===== GRILLE RÉCENTE ===== */
.admin-recent-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.recent-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: transform 0.3s ease;
    height: fit-content;
}

.recent-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.recent-card h4 {
    margin: 0 0 1rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid var(--border-color);
}

.recent-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1rem 0;
    max-height: 300px;
    overflow-y: auto;
}

/* Personnalisation scrollbar pour les listes */
.recent-list::-webkit-scrollbar {
    width: 6px;
}

.recent-list::-webkit-scrollbar-track {
    background: var(--bg-secondary);
    border-radius: 3px;
}

.recent-list::-webkit-scrollbar-thumb {
    background: var(--text-secondary);
    border-radius: 3px;
}

.recent-list::-webkit-scrollbar-thumb:hover {
    background: var(--accent-color);
}

.recent-item {
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
    transition: background-color 0.2s;
}

.recent-item:last-child {
    border-bottom: none;
}

.recent-item:hover {
    background-color: var(--bg-secondary);
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    border-radius: 6px;
}

.recent-item.empty {
    color: var(--text-secondary);
    text-align: center;
    padding: 2rem 0;
    font-style: italic;
}

.recent-item-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.recent-item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.5rem;
}

.recent-item-header strong {
    color: var(--text-primary);
    font-size: 0.95rem;
    word-break: break-word;
}

.recent-item-details {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.recent-item-email,
.recent-item-price,
.recent-item-ref {
    color: var(--text-primary);
    font-weight: 500;
}

.recent-item-location,
.recent-item-type,
.recent-item-date {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.badge-actif, .badge-success, .badge-disponible {
    background: var(--success-color);
    color: white;
}

.badge-en_attente {
    background: var(--warning-color);
    color: #000000;
}

.badge-bloque, .badge-danger {
    background: var(--danger-color);
    color: white;
}

.badge-rejete {
    background: var(--text-secondary);
    color: white;
}

.badge-loue {
    background: #FF6B00;
    color: white;
}

[data-admin-theme="dark"] .badge-loue {
    background: #F97316;
}

/* ===== LIEN VOIR TOUT ===== */
.view-all-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.2s;
}

.view-all-link:hover {
    color: var(--text-primary);
    transform: translateX(4px);
}

.view-all-link .arrow {
    font-size: 1.2rem;
}

[data-admin-theme="dark"] .view-all-link {
    color: var(--text-primary);
}

/* ===== ACTIONS RAPIDES ===== */
.quick-actions {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.quick-actions h4 {
    margin: 0 0 1.5rem 0;
    color: var(--text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.action-buttons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.quick-action-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 10px;
    color: var(--text-primary);
    text-decoration: none;
    transition: all 0.3s;
    position: relative;
}

.quick-action-card:hover {
    transform: translateY(-3px);
    background: var(--card-bg);
    border-color: var(--accent-color);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.quick-action-icon {
    font-size: 1.8rem;
}

.quick-action-text {
    flex: 1;
    font-weight: 600;
    font-size: 0.9rem;
}

.quick-action-badge {
    background: var(--accent-color);
    color: var(--bg-primary);
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    min-width: 24px;
    text-align: center;
}

[data-admin-theme="dark"] .quick-action-badge {
    background: var(--text-primary);
    color: var(--bg-primary);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .admin-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .admin-recent-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .admin-stats-grid {
        grid-template-columns: 1fr;
    }

    .admin-recent-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons-grid {
        grid-template-columns: 1fr;
    }

    .stat-number {
        font-size: 2.2rem;
    }

    .recent-item-details {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 480px) {
    .stat-card {
        padding: 1rem;
    }

    .stat-number {
        font-size: 2rem;
    }

    .recent-card {
        padding: 1rem;
    }

    .quick-action-card {
        padding: 0.75rem;
    }

    .quick-action-icon {
        font-size: 1.5rem;
    }

    .quick-action-text {
        font-size: 0.85rem;
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

.stat-card, .recent-card, .quick-actions {
    animation: fadeIn 0.5s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

/* ===== THÈME SOMBRE - AJUSTEMENTS SPÉCIFIQUES ===== */
[data-admin-theme="dark"] .stat-card,
[data-admin-theme="dark"] .recent-card,
[data-admin-theme="dark"] .quick-actions {
    background: var(--card-bg);
    border-color: var(--border-color);
}

[data-admin-theme="dark"] .stat-number {
    color: var(--text-primary);
}

[data-admin-theme="dark"] .breakdown-item {
    color: var(--text-secondary);
}

[data-admin-theme="dark"] .recent-item:hover {
    background-color: var(--bg-primary);
}

[data-admin-theme="dark"] .quick-action-card {
    background: var(--card-bg);
    border-color: var(--border-color);
}

[data-admin-theme="dark"] .quick-action-card:hover {
    background: var(--bg-secondary);
    border-color: var(--text-primary);
}

[data-admin-theme="dark"] .badge-loue {
    background: #F97316;
}

[data-admin-theme="dark"] .badge-disponible {
    background: var(--success-color);
}
</style>

<script>
// Animation supplémentaire au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Mise à jour automatique des badges
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        // Assurer que les badges sont bien stylisés
        if (badge.classList.contains('status-en_attente')) {
            badge.classList.add('badge-en_attente');
        } else if (badge.classList.contains('status-actif')) {
            badge.classList.add('badge-actif');
        } else if (badge.classList.contains('status-bloque')) {
            badge.classList.add('badge-bloque');
        } else if (badge.classList.contains('status-rejete')) {
            badge.classList.add('badge-rejete');
        }
    });
});

// Rafraîchissement périodique des données (optionnel)
// setInterval(() => {
//     location.reload();
// }, 300000); // 5 minutes
</script>
@endsection
