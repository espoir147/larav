@extends('layouts.admin')

@section('admin-content')
<div class="admin-transactions-page">
    <!-- En-tête -->
    <div class="page-header">
        <h2>💰 Transactions</h2>
        <p>Historique de tous les paiements effectués sur la plateforme</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3>Total transactions</h3>
                <p class="stat-number">{{ $totalTransactions ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Montant total</h3>
                <p class="stat-number">{{ number_format($montantTotal ?? 0, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3>Ce mois</h3>
                <p class="stat-number">{{ $transactionsMois ?? 0 }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💵</div>
            <div class="stat-content">
                <h3>Montant du mois</h3>
                <p class="stat-number">{{ number_format($montantMois ?? 0, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.transactions') }}" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label>Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Statut</label>
                    <select name="statut" class="filter-select">
                        <option value="">Tous</option>
                        <option value="paye" {{ request('statut') == 'paye' ? 'selected' : '' }}>Payé</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Opérateur</label>
                    <select name="operateur" class="filter-select">
                        <option value="">Tous</option>
                        <option value="MTN" {{ request('operateur') == 'MTN' ? 'selected' : '' }}>MTN</option>
                        <option value="MOOV" {{ request('operateur') == 'MOOV' ? 'selected' : '' }}>Moov</option>
                        <option value="CELTIIS" {{ request('operateur') == 'CELTIIS' ? 'selected' : '' }}>Celtiis</option>
                    </select>
                </div>
            </div>

            <div class="filters-row">
                <div class="filter-group">
                    <label>Référence</label>
                    <input type="text" name="reference" value="{{ request('reference') }}" placeholder="Réf..." class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Locataire</label>
                    <input type="text" name="locataire" value="{{ request('locataire') }}" placeholder="Nom..." class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Montant min</label>
                    <input type="number" name="montant_min" value="{{ request('montant_min') }}" placeholder="0" class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Montant max</label>
                    <input type="number" name="montant_max" value="{{ request('montant_max') }}" placeholder="1000000" class="filter-input">
                </div>
            </div>

            <div class="filters-actions">
                <button type="submit" class="btn-filter">
                    <span>🔍</span> Filtrer
                </button>
                <a href="{{ route('admin.transactions') }}" class="btn-reset">
                    <span>🗑️</span> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des transactions -->
    <div class="transactions-table-wrapper">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Référence</th>
                    <th>Locataire</th>
                    <th>Propriétaire</th>
                    <th>Bien</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $transaction)
                <tr>
                    <td>
                        <div class="date-cell">
                            <span>{{ $transaction->created_at->format('d/m/Y') }}</span>
                            <small>{{ $transaction->created_at->format('H:i') }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="reference">{{ $transaction->reference_transaction }}</span>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="{{ $transaction->utilisateur->photo_profil ? asset('storage/' . $transaction->utilisateur->photo_profil) : asset('images/default-avatar.png') }}"
                                 alt="" class="user-avatar">
                            <div>
                                <div>{{ $transaction->utilisateur->nom }}</div>
                                <small>{{ $transaction->utilisateur->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $proprietaire = $transaction->maison->proprietaire ?? $transaction->appartement->proprietaire ?? null;
                        @endphp
                        @if($proprietaire)
                        <div class="user-info">
                            <img src="{{ $proprietaire->photo_profil ? asset('storage/' . $proprietaire->photo_profil) : asset('images/default-avatar.png') }}"
                                 alt="" class="user-avatar">
                            <div>
                                <div>{{ $proprietaire->nom }}</div>
                                <small>{{ $proprietaire->email }}</small>
                            </div>
                        </div>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($transaction->maison)
                            Maison<br>
                            <small>{{ $transaction->maison->nom }}</small>
                        @elseif($transaction->appartement)
                            Appartement<br>
                            <small>{{ $transaction->appartement->numero_appartement }}</small>
                        @else
                            <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span class="montant">{{ number_format($transaction->montant, 0, ',', ' ') }} FCFA</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $transaction->statut ?? 'paye' }}">
                            {{ $transaction->statut ?? 'Payé' }}
                        </span>
                    </td>
                    <td>
                        <div class="actions">
                            <button class="action-btn" onclick="showTransactionDetails({{ $transaction->id }})" title="Voir">
                                👁️
                            </button>
                            <a href="{{ route('admin.transactions.recu', $transaction->id) }}" class="action-btn" title="Télécharger">
                                📥
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="empty-row">
                        <div class="empty-state">
                            <div class="empty-icon">💰</div>
                            <h3>Aucune transaction</h3>
                            <p>Aucune transaction trouvée</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($transactions->hasPages())
    <div class="pagination">
        {{ $transactions->links() }}
    </div>
    @endif
</div>

<!-- Modal détails -->
<div id="transactionModal" class="admin-modal">
    <div class="modal-content">
        <span class="close" onclick="closeTransactionModal()">&times;</span>
        <h3>Détails de la transaction</h3>
        <div id="transactionDetails" class="modal-body">
            <div class="loading">Chargement...</div>
        </div>
    </div>
</div>

<style>
/* ===== PAGE TRANSACTIONS ADMIN ===== */
.admin-transactions-page {
    padding: 2rem;
}

/* En-tête */
.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.page-header h2 {
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    font-size: 1.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-header p {
    color: var(--text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

/* Statistiques */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border-radius: 8px;
}

.stat-content h3 {
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin: 0 0 0.5rem 0;
    font-weight: 600;
    text-transform: uppercase;
}

.stat-number {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.2;
}

/* Filtres */
.filters-section {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.filters-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    color: var(--text-primary);
    font-weight: 600;
    font-size: 0.85rem;
    margin-bottom: 0.25rem;
}

.filter-input,
.filter-select {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-size: 0.9rem;
}

.filter-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--accent-color);
}

.filters-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.btn-filter,
.btn-reset {
    padding: 0.5rem 1.5rem;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-filter {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
}

.btn-filter:hover {
    opacity: 0.9;
}

.btn-reset {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.btn-reset:hover {
    background: var(--border-color);
}

/* Tableau */
.transactions-table-wrapper {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    overflow-x: auto;
    margin-bottom: 2rem;
}

.transactions-table {
    width: 100%;
    min-width: 1200px;
    border-collapse: collapse;
}

.transactions-table th {
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.transactions-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
}

.transactions-table tr:last-child td {
    border-bottom: none;
}

.transactions-table tr:hover td {
    background: var(--bg-secondary);
}

/* Date */
.date-cell {
    display: flex;
    flex-direction: column;
}

.date-cell small {
    color: var(--text-secondary);
    font-size: 0.75rem;
}

/* Référence */
.reference {
    font-family: monospace;
    background: var(--bg-secondary);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.85rem;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border-color);
}

.user-info small {
    color: var(--text-secondary);
    font-size: 0.7rem;
}

/* Montant */
.montant {
    font-weight: 700;
    color: var(--success-color);
}

/* Statut */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-paye {
    background: rgba(0, 170, 0, 0.1);
    color: var(--success-color);
    border: 1px solid var(--success-color);
}

.status-en_attente {
    background: rgba(255, 215, 0, 0.1);
    color: #B8860B;
    border: 1px solid #FFD700;
}

/* Actions */
.actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    width: 32px;
    height: 32px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    text-decoration: none;
    font-size: 1rem;
}

.action-btn:hover {
    background: var(--accent-color);
    color: var(--bg-primary);
    border-color: var(--accent-color);
}

/* État vide */
.empty-row td {
    padding: 3rem;
}

.empty-state {
    text-align: center;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--text-primary);
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-secondary);
}

/* Modal */
.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.loading {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-transactions-page {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .filters-actions {
        flex-direction: column;
    }

    .btn-filter,
    .btn-reset {
        width: 100%;
        justify-content: center;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
function showTransactionDetails(transactionId) {
    const modal = document.getElementById('transactionModal');
    const detailsDiv = document.getElementById('transactionDetails');

    modal.style.display = 'block';
    detailsDiv.innerHTML = '<div class="loading">Chargement...</div>';

    fetch(`/admin/transactions/${transactionId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const t = data.transaction;
                detailsDiv.innerHTML = `
                    <div style="padding: 1rem;">
                        <p><strong>Référence:</strong> ${t.reference}</p>
                        <p><strong>Date:</strong> ${t.date}</p>
                        <p><strong>Montant:</strong> ${t.montant}</p>
                        <p><strong>Statut:</strong> <span class="status-badge status-${t.statut}">${t.statut}</span></p>
                        <p><strong>Opérateur:</strong> ${t.operateur}</p>
                        <p><strong>Locataire:</strong> ${t.locataire.nom} (${t.locataire.email})</p>
                        <p><strong>Propriétaire:</strong> ${t.proprietaire.nom} (${t.proprietaire.email})</p>
                        <p><strong>Bien:</strong> ${t.bien.nom}</p>
                        <p><strong>Adresse:</strong> ${t.bien.adresse}</p>
                    </div>
                `;
            }
        })
        .catch(() => {
            detailsDiv.innerHTML = '<p class="loading">Erreur de chargement</p>';
        });
}

function closeTransactionModal() {
    document.getElementById('transactionModal').style.display = 'none';
}

window.onclick = function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target == modal) {
        closeTransactionModal();
    }
}
</script>
@endsection
