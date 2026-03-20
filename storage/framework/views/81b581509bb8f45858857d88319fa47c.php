<?php $__env->startSection('client-content'); ?>
<div class="client-dashboard">
    <!-- En-tête du dashboard -->
    <div class="dashboard-header">
        <h1>
            <span class="header-icon">📊</span>
            Tableau de bord
        </h1>
        <p>Bienvenue, <?php echo e(Auth::user()->nom); ?> ! Voici un aperçu de votre activité.</p>
    </div>

    <!-- Cartes Statistiques -->
    <div class="client-stats-grid">
        <div class="client-stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-content">
                <h3>Locations Actives</h3>
                <p class="stat-number"><?php echo e($locationsActives ?? 0); ?></p>
                <div class="stat-details">
                    <span>En cours</span>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>

        <div class="client-stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Prochain Paiement</h3>
                <p class="stat-number"><?php echo e($prochainPaiement ?? 'Aucun'); ?></p>
                <div class="stat-details">
                    <span>Date estimée</span>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>

        <div class="client-stat-card">
            <div class="stat-icon">✉️</div>
            <div class="stat-content">
                <h3>Messages Non Lus</h3>
                <p class="stat-number"><?php echo e($messagesNonLus ?? 0); ?></p>
                <div class="stat-details">
                    <span>À lire</span>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>

        <div class="client-stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-content">
                <h3>Favoris</h3>
                <p class="stat-number"><?php echo e($favorisCount ?? 0); ?></p>
                <div class="stat-details">
                    <span>Biens sauvegardés</span>
                </div>
            </div>
            <div class="stat-card-glow"></div>
        </div>
    </div>

    <!-- Grille Contenu -->
    <div class="client-content-grid">
        <!-- Locations en Cours -->
        <div class="client-content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">🏠</span>
                    Mes Locations Actives
                </h4>
                <a href="<?php echo e(route('client.locations')); ?>" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                <?php $__empty_1 = true; $__currentLoopData = $locationsEnCours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="location-item">
                    <div class="location-info">
                        <strong><?php echo e($location->maison->nom ?? $location->appartement->numero_appartement); ?></strong>
                        <span class="badge <?php echo e($location->type_logement === 'maison' ? 'badge-info' : 'badge-success'); ?>">
                            <?php echo e($location->type_logement === 'maison' ? 'Maison' : 'Appartement'); ?>

                        </span>
                    </div>
                    <div class="location-meta">
                        <span class="meta-date">
                            <span class="meta-icon">📅</span>
                            Début: <?php echo e($location->date_debut->format('d/m/Y')); ?>

                        </span>
                        <span class="meta-location">
                            <span class="meta-icon">📍</span>
                            <?php echo e($location->maison->ville ?? $location->appartement->ville); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <div class="empty-icon">🏠</div>
                    <p>Aucune location active</p>
                    <a href="<?php echo e(route('home')); ?>" class="empty-action">
                        <span>Explorer les biens</span>
                        <span class="arrow">→</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Derniers Paiements -->
        <div class="client-content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">💳</span>
                    Derniers Paiements
                </h4>
                <a href="<?php echo e(route('client.paiements')); ?>" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                <?php $__empty_1 = true; $__currentLoopData = $derniersPaiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="paiement-item">
                    <div class="paiement-info">
                        <strong><?php echo e($paiement->maison->nom ?? $paiement->appartement->numero_appartement); ?></strong>
                        <span class="paiement-montant"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <div class="paiement-meta">
                        <span class="meta-date">
                            <span class="meta-icon">📅</span>
                            <?php echo e($paiement->created_at->format('d/m/Y')); ?>

                        </span>
                        <small class="paiement-ref">Réf: <?php echo e($paiement->reference_transaction); ?></small>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <div class="empty-icon">💰</div>
                    <p>Aucun paiement effectué</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Messages Récents -->
        <div class="client-content-card">
            <div class="card-header">
                <h4>
                    <span class="header-icon">✉️</span>
                    Messages Récents
                </h4>
                <a href="<?php echo e(route('client.messagerie')); ?>" class="view-all">
                    <span>Voir tout</span>
                    <span class="arrow">→</span>
                </a>
            </div>
            <div class="card-content">
                <?php $__empty_1 = true; $__currentLoopData = $messagesRecents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="message-item <?php echo e($message->destinataire_id == Auth::id() && !$message->lu ? 'unread' : ''); ?>">
                    <div class="message-avatar">
                        <img src="<?php echo e($message->expediteur->photo_profil ? asset('storage/' . $message->expediteur->photo_profil) : asset('images/default-avatar.png')); ?>"
                             alt="Photo de <?php echo e($message->expediteur->nom); ?>">
                    </div>
                    <div class="message-content">
                        <div class="message-header">
                            <strong><?php echo e($message->expediteur->nom); ?></strong>
                            <?php if($message->destinataire_id == Auth::id() && !$message->lu): ?>
                                <span class="unread-dot" title="Non lu"></span>
                            <?php endif; ?>
                        </div>
                        <p class="message-preview"><?php echo e(Str::limit($message->contenu, 60)); ?></p>
                        <small class="message-time"><?php echo e($message->created_at->diffForHumans()); ?></small>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <div class="empty-icon">✉️</div>
                    <p>Aucun message récent</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="client-quick-actions">
        <h4>
            <span class="header-icon">⚡</span>
            Actions Rapides
        </h4>
        <div class="actions-grid">
            <a href="<?php echo e(route('client.locations')); ?>" class="quick-action">
                <span class="action-icon">📋</span>
                <span class="action-text">Mes Locations</span>
                <span class="action-hint">Voir vos locations</span>
            </a>
            <a href="<?php echo e(route('client.paiements')); ?>" class="quick-action">
                <span class="action-icon">💰</span>
                <span class="action-text">Effectuer un paiement</span>
                <span class="action-hint">Payer votre loyer</span>
            </a>
            <a href="<?php echo e(route('client.messagerie')); ?>" class="quick-action">
                <span class="action-icon">✉️</span>
                <span class="action-text">Messagerie</span>
                <span class="action-hint">Vos conversations</span>
            </a>
            <a href="<?php echo e(route('home')); ?>" class="quick-action">
                <span class="action-icon">🔍</span>
                <span class="action-text">Rechercher un bien</span>
                <span class="action-hint">Trouver un logement</span>
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<style>
/* ===== STYLES SPÉCIFIQUES AU DASHBOARD CLIENT ===== */

.client-dashboard {
    padding: 1rem 0;
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== EN-TÊTE DASHBOARD ===== */
.dashboard-header {
    margin-bottom: 2.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid var(--client-border-light);
}

.dashboard-header h1 {
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

.dashboard-header .header-icon {
    font-size: 2.5rem;
    background: none;
    -webkit-text-fill-color: initial;
    color: var(--client-primary);
}

.dashboard-header p {
    color: var(--client-text-secondary);
    font-size: 1.1rem;
    margin: 0;
    padding-left: 0.5rem;
}

/* ===== GRILLE STATISTIQUES ===== */
.client-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.client-stat-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    box-shadow: var(--client-shadow-sm);
}

.client-stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--client-primary), var(--client-secondary));
    opacity: 0;
    transition: opacity 0.3s;
}

.client-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary-light);
}

.client-stat-card:hover::before {
    opacity: 1;
}

.stat-card-glow {
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(5, 150, 105, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.5s;
    pointer-events: none;
}

.client-stat-card:hover .stat-card-glow {
    opacity: 1;
}

.client-stat-card .stat-icon {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--client-primary-light);
    border-radius: var(--radius-xl);
    color: var(--client-primary);
    transition: all 0.3s;
    border: 2px solid transparent;
}

.client-stat-card:hover .stat-icon {
    transform: scale(1.1) rotate(5deg);
    background: var(--client-primary);
    color: white;
    border-color: white;
}

.client-stat-card .stat-content {
    flex: 1;
}

.client-stat-card .stat-content h3 {
    margin: 0 0 0.5rem 0;
    color: var(--client-text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.client-stat-card .stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--client-text-primary);
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.client-stat-card .stat-details span {
    font-size: 0.8rem;
    color: var(--client-text-tertiary);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== GRILLE CONTENU ===== */
.client-content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}

.client-content-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.3s ease;
    height: fit-content;
    box-shadow: var(--client-shadow-sm);
}

.client-content-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary-light);
}

.client-content-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--client-border-light);
    background: var(--client-bg-secondary);
}

.client-content-card .card-header h4 {
    margin: 0;
    color: var(--client-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.client-content-card .card-header .header-icon {
    font-size: 1.3rem;
}

.client-content-card .view-all {
    color: var(--client-primary);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-lg);
    background: var(--client-primary-light);
    border: 1px solid transparent;
}

.client-content-card .view-all:hover {
    background: var(--client-primary);
    color: white;
    transform: translateX(3px);
    border-color: var(--client-primary);
}

.client-content-card .view-all .arrow {
    font-size: 1.1rem;
    transition: transform 0.3s;
}

.client-content-card .view-all:hover .arrow {
    transform: translateX(3px);
}

.client-content-card .card-content {
    padding: 1.5rem;
    max-height: 400px;
    overflow-y: auto;
}

/* Personnalisation scrollbar */
.client-content-card .card-content::-webkit-scrollbar {
    width: 4px;
}

.client-content-card .card-content::-webkit-scrollbar-track {
    background: var(--client-border-light);
    border-radius: var(--radius-full);
}

.client-content-card .card-content::-webkit-scrollbar-thumb {
    background: var(--client-primary);
    border-radius: var(--radius-full);
}

/* ===== ITEMS LOCATIONS ===== */
.location-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--client-border-light);
    transition: all 0.3s;
}

.location-item:last-child {
    border-bottom: none;
}

.location-item:hover {
    background: var(--client-primary-light);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    border-radius: var(--radius-lg);
    transform: translateX(3px);
}

.location-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.location-info strong {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.location-meta {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    color: var(--client-text-tertiary);
}

.meta-icon {
    margin-right: 0.25rem;
    opacity: 0.7;
}

.meta-date, .meta-location {
    display: flex;
    align-items: center;
}

/* ===== ITEMS PAIEMENTS ===== */
.paiement-item {
    padding: 1rem 0;
    border-bottom: 1px solid var(--client-border-light);
    transition: all 0.3s;
}

.paiement-item:last-child {
    border-bottom: none;
}

.paiement-item:hover {
    background: var(--client-primary-light);
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
    color: var(--client-text-primary);
    font-size: 0.95rem;
    font-weight: 600;
}

.paiement-montant {
    color: var(--client-success);
    font-weight: 700;
    font-size: 1rem;
    background: var(--client-primary-light);
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
}

.paiement-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
    color: var(--client-text-tertiary);
}

.paiement-ref {
    color: var(--client-text-tertiary);
    font-size: 0.7rem;
    background: var(--client-bg-secondary);
    padding: 0.2rem 0.5rem;
    border-radius: var(--radius-full);
}

/* ===== ITEMS MESSAGES ===== */
.message-item {
    display: flex;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--client-border-light);
    position: relative;
    transition: all 0.3s;
}

.message-item:last-child {
    border-bottom: none;
}

.message-item:hover {
    background: var(--client-primary-light);
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    border-radius: var(--radius-lg);
    transform: translateX(3px);
}

.message-item.unread {
    background: var(--client-primary-light);
    margin: 0 -0.5rem;
    padding: 1rem 1.25rem;
    border-radius: var(--radius-lg);
    border-left: 4px solid var(--client-primary);
}

.message-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--client-border-light);
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
    color: var(--client-text-primary);
    font-size: 0.95rem;
    font-weight: 600;
}

.unread-dot {
    width: 8px;
    height: 8px;
    background: var(--client-primary);
    border-radius: 50%;
    display: inline-block;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
}

.message-preview {
    color: var(--client-text-secondary);
    font-size: 0.85rem;
    margin: 0 0 0.25rem 0;
    line-height: 1.4;
}

.message-time {
    color: var(--client-text-tertiary);
    font-size: 0.7rem;
}

/* ===== ÉTAT VIDE ===== */
.empty-state {
    text-align: center;
    padding: 2.5rem 1rem;
}

.empty-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}

.empty-state p {
    color: var(--client-text-secondary);
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}

.empty-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: var(--client-primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-lg);
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.3s;
    border: 1px solid transparent;
}

.empty-action:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
    gap: 0.75rem;
}

.empty-action .arrow {
    font-size: 1rem;
    transition: transform 0.3s;
}

.empty-action:hover .arrow {
    transform: translateX(3px);
}

/* ===== ACTIONS RAPIDES ===== */
.client-quick-actions {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-top: 1rem;
    box-shadow: var(--client-shadow-sm);
}

.client-quick-actions h4 {
    margin: 0 0 1.5rem 0;
    color: var(--client-text-primary);
    font-size: 1.2rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1rem;
}

.quick-action {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    padding: 1.25rem;
    background: var(--client-bg-secondary);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    text-decoration: none;
    color: var(--client-text-primary);
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
    background: linear-gradient(90deg, var(--client-primary), var(--client-secondary));
    opacity: 0;
    transition: opacity 0.3s;
}

.quick-action:hover {
    transform: translateY(-4px);
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
    box-shadow: var(--client-shadow-lg);
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
    letter-spacing: 0.5px;
    border: 1px solid transparent;
}

.badge-success {
    background: var(--client-primary-light);
    color: var(--client-primary);
    border-color: var(--client-primary);
}

.badge-info {
    background: var(--client-secondary-light);
    color: var(--client-secondary);
    border-color: var(--client-secondary);
}

/* ===== THÈME SOMBRE - AJUSTEMENTS ===== */
[data-client-theme="dark"] .client-stat-card {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .client-stat-card .stat-icon {
    background: rgba(52, 211, 153, 0.2);
    color: var(--client-primary);
}

[data-client-theme="dark"] .client-stat-card:hover .stat-icon {
    background: var(--client-primary);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .client-content-card {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .client-content-card .card-header {
    background: var(--client-dark-bg);
    border-bottom-color: var(--client-dark-border);
}

[data-client-theme="dark"] .client-content-card .card-header h4 {
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .client-content-card .view-all {
    background: rgba(52, 211, 153, 0.2);
    color: var(--client-primary);
}

[data-client-theme="dark"] .client-content-card .view-all:hover {
    background: var(--client-primary);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .location-item:hover,
[data-client-theme="dark"] .paiement-item:hover,
[data-client-theme="dark"] .message-item:hover {
    background: rgba(52, 211, 153, 0.1);
}

[data-client-theme="dark"] .message-item.unread {
    background: rgba(52, 211, 153, 0.15);
    border-left-color: var(--client-primary);
}

[data-client-theme="dark"] .client-quick-actions {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .quick-action {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .quick-action:hover {
    background: var(--client-primary);
    color: var(--client-dark-text);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .client-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .client-content-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 1.8rem;
    }

    .dashboard-header p {
        font-size: 1rem;
    }

    .client-stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .client-content-grid {
        grid-template-columns: 1fr;
    }

    .actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .quick-action {
        padding: 1rem;
    }

    .action-icon {
        font-size: 2rem;
    }

    .action-text {
        font-size: 0.85rem;
    }

    .action-hint {
        display: none;
    }
}

@media (max-width: 480px) {
    .dashboard-header h1 {
        font-size: 1.5rem;
    }

    .client-stat-card .stat-icon {
        width: 55px;
        height: 55px;
        font-size: 2rem;
    }

    .client-stat-card .stat-number {
        font-size: 1.8rem;
    }

    .actions-grid {
        grid-template-columns: 1fr;
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

    .location-meta {
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

.client-stat-card,
.client-content-card,
.client-quick-actions {
    animation: slideUp 0.5s ease-out forwards;
}

.client-stat-card:nth-child(1) { animation-delay: 0.1s; }
.client-stat-card:nth-child(2) { animation-delay: 0.2s; }
.client-stat-card:nth-child(3) { animation-delay: 0.3s; }
.client-stat-card:nth-child(4) { animation-delay: 0.4s; }

.client-content-card:nth-child(1) { animation-delay: 0.2s; }
.client-content-card:nth-child(2) { animation-delay: 0.3s; }
.client-content-card:nth-child(3) { animation-delay: 0.4s; }

.client-quick-actions {
    animation-delay: 0.5s;
}
</style>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/client/dashboard.blade.php ENDPATH**/ ?>