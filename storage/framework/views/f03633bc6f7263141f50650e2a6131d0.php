<?php $__env->startSection('admin-content'); ?>
<div class="admin-logs-page">
    <!-- En-tête -->
    <div class="page-header">
        <h2>📝 Journal d'activité</h2>
        <p>Historique de toutes les actions sur la plateforme</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-content">
                <h3>Total logs</h3>
                <p class="stat-number"><?php echo e($stats['total']); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3>Aujourd'hui</h3>
                <p class="stat-number"><?php echo e($stats['aujourd_hui']); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🔐</div>
            <div class="stat-content">
                <h3>Connexions</h3>
                <p class="stat-number"><?php echo e($stats['connexions']); ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⚡</div>
            <div class="stat-content">
                <h3>Actions</h3>
                <p class="stat-number"><?php echo e($stats['actions']); ?></p>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters-section">
        <form method="GET" action="<?php echo e(route('admin.logs')); ?>" class="filters-form">
            <div class="filters-row">
                <div class="filter-group">
                    <label>Type</label>
                    <select name="type" class="filter-select">
                        <option value="">Tous les types</option>
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('type') == $type ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($type)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Utilisateur</label>
                    <select name="user_id" class="filter-select">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(request('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Date début</label>
                    <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>" class="filter-input">
                </div>

                <div class="filter-group">
                    <label>Date fin</label>
                    <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>" class="filter-input">
                </div>
            </div>

            <div class="filters-row">
                <div class="filter-group" style="flex: 2;">
                    <label>Recherche</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                           placeholder="Description, IP..." class="filter-input">
                </div>
            </div>

            <div class="filters-actions">
                <button type="submit" class="btn-filter">
                    <span>🔍</span> Filtrer
                </button>
                <a href="<?php echo e(route('admin.logs')); ?>" class="btn-reset">
                    <span>🗑️</span> Réinitialiser
                </a>
                <a href="<?php echo e(route('admin.logs.export')); ?>?<?php echo e(http_build_query(request()->query())); ?>"
                   class="btn-export">
                    <span>📥</span> Exporter CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des logs -->
    <div class="logs-table-wrapper">
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Utilisateur</th>
                    <th>Description</th>
                    <th>IP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="date-cell">
                            <span><?php echo e($log->created_at->format('d/m/Y')); ?></span>
                            <small><?php echo e($log->created_at->format('H:i:s')); ?></small>
                        </div>
                    </td>
                    <td>
                        <span class="type-badge type-<?php echo e($log->type); ?>">
                            <?php echo e($log->type); ?>

                        </span>
                    </td>
                    <td>
                        <div class="user-info">
                            <img src="<?php echo e($log->user->photo_profil ? asset('storage/' . $log->user->photo_profil) : asset('images/default-avatar.png')); ?>"
                                 alt="" class="user-avatar">
                            <span><?php echo e($log->user->nom ?? 'Système'); ?></span>
                        </div>
                    </td>
                    <td>
                        <div class="description"><?php echo e($log->description); ?></div>
                        <?php if($log->old_values || $log->new_values): ?>
                            <button class="view-changes" onclick="showChanges(<?php echo e($log->id); ?>)">
                                Voir modifications
                            </button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="ip"><?php echo e($log->ip_address); ?></span>
                    </td>
                    <td>
                        <button class="action-btn" onclick="showLogDetails(<?php echo e($log->id); ?>)" title="Détails">
                            👁️
                        </button>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="empty-row">
                        <div class="empty-state">
                            <div class="empty-icon">📝</div>
                            <h3>Aucun log trouvé</h3>
                            <p>Aucune activité ne correspond à vos critères</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($logs->hasPages()): ?>
    <div class="pagination">
        <?php echo e($logs->links()); ?>

    </div>
    <?php endif; ?>
</div>

<!-- Modal détails -->
<div id="logModal" class="admin-modal">
    <div class="modal-content">
        <span class="close" onclick="closeLogModal()">&times;</span>
        <h3>Détails du log</h3>
        <div id="logDetails" class="modal-body">
            <div class="loading">Chargement...</div>
        </div>
    </div>
</div>

<!-- Modal modifications -->
<div id="changesModal" class="admin-modal">
    <div class="modal-content">
        <span class="close" onclick="closeChangesModal()">&times;</span>
        <h3>Modifications</h3>
        <div id="changesDetails" class="modal-body"></div>
    </div>
</div>

<style>
/* ===== PAGE LOGS ADMIN ===== */
.admin-logs-page {
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
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
    font-size: 2rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg-secondary);
    border-radius: 8px;
}

.stat-content h3 {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin: 0 0 0.25rem 0;
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
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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

.filters-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.btn-filter,
.btn-reset,
.btn-export {
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

.btn-reset {
    background: var(--bg-secondary);
    color: var(--text-primary);
}

.btn-export {
    background: var(--success-color);
    color: white;
    border-color: var(--success-color);
}

.btn-filter:hover,
.btn-reset:hover,
.btn-export:hover {
    opacity: 0.9;
    transform: translateY(-2px);
}

/* Tableau */
.logs-table-wrapper {
    background: var(--card-bg);
    border: 2px solid var(--border-color);
    border-radius: 8px;
    overflow-x: auto;
    margin-bottom: 2rem;
}

.logs-table {
    width: 100%;
    min-width: 1000px;
    border-collapse: collapse;
}

.logs-table th {
    background: var(--bg-secondary);
    color: var(--text-primary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid var(--border-color);
}

.logs-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
    vertical-align: middle;
}

.logs-table tr:last-child td {
    border-bottom: none;
}

.logs-table tr:hover td {
    background: var(--bg-secondary);
}

/* Date */
.date-cell {
    display: flex;
    flex-direction: column;
}

.date-cell small {
    color: var(--text-secondary);
    font-size: 0.7rem;
}

/* Type badge */
.type-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.type-connexion {
    background: rgba(0, 170, 0, 0.1);
    color: var(--success-color);
    border: 1px solid var(--success-color);
}

.type-action {
    background: rgba(0, 0, 0, 0.1);
    color: var(--text-primary);
    border: 1px solid var(--border-color);
}

.type-erreur {
    background: rgba(255, 0, 0, 0.1);
    color: var(--danger-color);
    border: 1px solid var(--danger-color);
}

.type-avertissement {
    background: rgba(255, 215, 0, 0.1);
    color: #B8860B;
    border: 1px solid #FFD700;
}

/* User info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.user-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border-color);
}

/* Description */
.description {
    max-width: 300px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.view-changes {
    background: none;
    border: none;
    color: var(--accent-color);
    font-size: 0.75rem;
    cursor: pointer;
    text-decoration: underline;
    margin-top: 0.25rem;
}

.view-changes:hover {
    color: var(--text-primary);
}

/* IP */
.ip {
    font-family: monospace;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Actions */
.action-btn {
    width: 30px;
    height: 30px;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    background: var(--bg-secondary);
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
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
    padding: 1rem;
}

.detail-row {
    display: flex;
    margin-bottom: 0.5rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.detail-label {
    width: 120px;
    font-weight: 600;
    color: var(--text-secondary);
}

.detail-value {
    flex: 1;
    color: var(--text-primary);
}

.changes-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1rem;
}

.changes-column {
    background: var(--bg-secondary);
    padding: 1rem;
    border-radius: 4px;
    border: 1px solid var(--border-color);
}

.changes-column h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 0.9rem;
    font-weight: 600;
}

.changes-item {
    padding: 0.25rem 0;
    border-bottom: 1px dashed var(--border-color);
    font-size: 0.85rem;
}

.loading {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
}

/* Responsive */
@media (max-width: 768px) {
    .admin-logs-page {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .filters-actions {
        flex-direction: column;
    }

    .btn-filter,
    .btn-reset,
    .btn-export {
        width: 100%;
        justify-content: center;
    }

    .user-info {
        flex-direction: column;
        align-items: flex-start;
    }

    .changes-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showLogDetails(logId) {
    const modal = document.getElementById('logModal');
    const details = document.getElementById('logDetails');

    modal.style.display = 'block';
    details.innerHTML = '<div class="loading">Chargement...</div>';

    fetch(`/admin/logs/${logId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const l = data.log;
                details.innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">Date</span>
                        <span class="detail-value">${l.date}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Type</span>
                        <span class="detail-value"><span class="type-badge type-${l.type}">${l.type}</span></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Utilisateur</span>
                        <span class="detail-value">${l.user}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Description</span>
                        <span class="detail-value">${l.description}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">IP</span>
                        <span class="detail-value">${l.ip}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">User Agent</span>
                        <span class="detail-value">${l.user_agent}</span>
                    </div>
                `;
            }
        })
        .catch(() => {
            details.innerHTML = '<p class="loading">Erreur de chargement</p>';
        });
}

function showChanges(logId) {
    const modal = document.getElementById('changesModal');
    const details = document.getElementById('changesDetails');

    modal.style.display = 'block';
    details.innerHTML = '<div class="loading">Chargement...</div>';

    fetch(`/admin/logs/${logId}/changes`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = '<div class="changes-grid">';

                if (data.old) {
                    html += '<div class="changes-column"><h4>Anciennes valeurs</h4>';
                    Object.entries(data.old).forEach(([key, value]) => {
                        html += `<div class="changes-item"><strong>${key}:</strong> ${value}</div>`;
                    });
                    html += '</div>';
                }

                if (data.new) {
                    html += '<div class="changes-column"><h4>Nouvelles valeurs</h4>';
                    Object.entries(data.new).forEach(([key, value]) => {
                        html += `<div class="changes-item"><strong>${key}:</strong> ${value}</div>`;
                    });
                    html += '</div>';
                }

                html += '</div>';
                details.innerHTML = html;
            }
        })
        .catch(() => {
            details.innerHTML = '<p class="loading">Erreur de chargement</p>';
        });
}

function closeLogModal() {
    document.getElementById('logModal').style.display = 'none';
}

function closeChangesModal() {
    document.getElementById('changesModal').style.display = 'none';
}

window.onclick = function(event) {
    const logModal = document.getElementById('logModal');
    const changesModal = document.getElementById('changesModal');

    if (event.target == logModal) closeLogModal();
    if (event.target == changesModal) closeChangesModal();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/admin/logs.blade.php ENDPATH**/ ?>