<?php $__env->startSection('client-content'); ?>
<div class="client-locations-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">🏠</span>
            Mes Locations
        </h1>
        <p>Gérez et suivez toutes vos locations</p>
    </div>

    <!-- Statistiques personnalisées -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-content">
                <h3>Locations actives</h3>
                <p class="stat-number"><?php echo e($stats['actives'] ?? 0); ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-content">
                <h3>Total locations</h3>
                <p class="stat-number"><?php echo e($stats['total'] ?? 0); ?></p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Prochain paiement</h3>
                <p class="stat-number"><?php echo e($stats['prochain_date'] ?? 'Aucun'); ?></p>
                <?php if($stats['prochain_montant'] ?? false): ?>
                    <span class="stat-sub"><?php echo e(number_format($stats['prochain_montant'], 0, ',', ' ')); ?> FCFA</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="filters-section">
        <div class="filter-tabs">
            <button class="filter-tab active" data-status="all">Toutes</button>
            <button class="filter-tab" data-status="active">Actives</button>
            <button class="filter-tab" data-status="pending">En attente</button>
            <button class="filter-tab" data-status="terminated">Terminées</button>
        </div>
        <div class="search-box">
            <input type="text" id="search-location" placeholder="Rechercher par adresse, nom...">
            <span class="search-icon">🔍</span>
        </div>
    </div>

    <!-- Liste des locations -->
    <div class="locations-grid" id="locations-list">
        <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $bien = $location->maison ?? $location->appartement;
            $photo = $bien->first_photo ?? '/images/default-property.jpg';
            $type = $location->type_logement;
            $prix = $bien->prix ?? $bien->prix_mensuel ?? 0;
            $proprietaire = $bien->proprietaire;
        ?>
        <div class="location-card" data-status="<?php echo e($location->statut); ?>" data-id="<?php echo e($location->id); ?>">
            <div class="location-image" onclick="openGallery(<?php echo e(json_encode($bien->photos_array ?? [])); ?>)">
                <img src="<?php echo e(asset($photo)); ?>" alt="<?php echo e($bien->nom ?? 'Bien'); ?>">
                <span class="location-type <?php echo e($type); ?>">
                    <?php echo e($type === 'maison' ? 'Maison' : 'Appartement'); ?>

                </span>
                <div class="image-overlay">
                    <span class="overlay-text">👁️ Voir les photos</span>
                </div>
            </div>

            <div class="location-details">
                <div class="location-header">
                    <h3><?php echo e($bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? '')); ?></h3>
                    <span class="status-badge status-<?php echo e($location->statut); ?>">
                        <?php if($location->statut == 'acceptee'): ?> Active
                        <?php elseif($location->statut == 'en_attente'): ?> En attente
                        <?php else: ?> Terminée
                        <?php endif; ?>
                    </span>
                </div>

                <div class="location-address">
                    <span class="icon">📍</span> <?php echo e($bien->adresse ?? ''); ?>, <?php echo e($bien->ville ?? ''); ?>

                </div>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="label">Début</span>
                        <span class="value"><?php echo e($location->date_debut ? $location->date_debut->format('d/m/Y') : 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Loyer</span>
                        <span class="value"><?php echo e(number_format($prix, 0, ',', ' ')); ?> FCFA/mois</span>
                    </div>
                    <div class="info-item">
                        <span class="label">Propriétaire</span>
                        <span class="value"><?php echo e($proprietaire->nom ?? 'N/A'); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="label">Prochain paiement</span>
                        <span class="value highlight"><?php echo e($location->prochain_paiement ?? ($location->date_debut ? $location->date_debut->addMonth()->format('d/m/Y') : 'N/A')); ?></span>
                    </div>
                </div>

                <div class="location-actions">
                    <?php if($location->statut == 'acceptee'): ?>
                    <button class="btn-payer"
                            data-location-id="<?php echo e($location->id); ?>"
                            data-type="<?php echo e($type); ?>"
                            data-prix="<?php echo e($prix); ?>"
                            data-bien-nom="<?php echo e($bien->nom ?? ('Appartement ' . ($bien->numero_appartement ?? ''))); ?>"
                            data-prochain-paiement="<?php echo e($location->prochain_paiement ?? ($location->date_debut ? $location->date_debut->addMonth()->format('d/m/Y') : '')); ?>"
                            onclick="openPaymentModal(this)">
                        <span class="btn-icon">💰</span>
                        <span class="btn-text">Payer le loyer</span>
                    </button>
                    <?php endif; ?>
                    <button class="btn-details" onclick="showLocationDetails(<?php echo e($location->id); ?>)">
                        <span class="btn-icon">📋</span>
                        <span class="btn-text">Détails</span>
                    </button>
                    <button class="btn-contact" onclick="contacterProprietaire(<?php echo e($proprietaire->id ?? 0); ?>, <?php echo e($location->id); ?>, '<?php echo e($type); ?>')">
                        <span class="btn-icon">✉️</span>
                        <span class="btn-text">Contacter</span>
                    </button>
                    <button class="btn-history" onclick="showPaiementHistory(<?php echo e($location->id); ?>)">
                        <span class="btn-icon">📊</span>
                        <span class="btn-text">Historique</span>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state">
            <div class="empty-icon">🏠</div>
            <h3>Aucune location trouvée</h3>
            <p>Vous n'avez pas encore de location active ou d'historique de location.</p>
            <a href="<?php echo e(route('home')); ?>" class="btn-primary">
                <span>Explorer les biens</span>
                <span class="arrow">→</span>
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL PAIEMENT -->
<div id="paymentModal" class="modal-overlay">
    <div class="modal modal-payment">
        <div class="modal-header">
            <h2>💰 Payer votre loyer</h2>
            <button class="modal-close" onclick="closePaymentModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="payment-summary">
                <div class="summary-item">
                    <span class="summary-label">Bien</span>
                    <span class="summary-value" id="payment-bien-nom">-</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Mois à payer</span>
                    <span class="summary-value" id="payment-mois">-</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Montant</span>
                    <span class="summary-amount" id="payment-montant">-</span>
                </div>
            </div>

            <form id="fedapay-form" onsubmit="initiatePayment(event)">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="payment-location-id" name="location_id">
                <input type="hidden" id="payment-type" name="type">
                <input type="hidden" id="payment-montant-value" name="montant">

                <div class="form-group">
                    <label class="form-label">Moyen de paiement</label>
                    <div class="payment-methods">
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="mobile_money" checked>
                            <span class="method-icon">📱</span>
                            <span class="method-name">Mobile Money</span>
                            <span class="method-desc">MTN, Moov, Celtiis</span>
                        </label>
                        <label class="payment-method">
                            <input type="radio" name="payment_method" value="card">
                            <span class="method-icon">💳</span>
                            <span class="method-name">Carte bancaire</span>
                            <span class="method-desc">Visa, Mastercard</span>
                        </label>
                    </div>
                </div>

                <div id="mobile-money-fields">
                    <div class="form-group">
                        <label for="operateur" class="form-label">Opérateur</label>
                        <select id="operateur" name="operateur" class="form-select" required>
                            <option value="">Sélectionnez un opérateur</option>
                            <option value="MTN">MTN Mobile Money</option>
                            <option value="MOOV">Moov Money</option>
                            <option value="CELTIIS">Celtiis Cash</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="telephone" class="form-label">Numéro de téléphone</label>
                        <input type="tel" id="telephone" name="telephone" class="form-input" placeholder="Ex: 0701234567" value="<?php echo e(Auth::user()->telephone ?? ''); ?>" required>
                    </div>
                </div>

                <div id="card-fields" style="display: none;">
                    <p class="form-help">Vous serez redirigé vers FedaPay pour le paiement par carte</p>
                </div>

                <div class="payment-footer">
                    <div class="payment-security">
                        <span class="security-icon">🔒</span>
                        <span>Paiement sécurisé par <strong>FedaPay</strong></span>
                    </div>
                    <div class="payment-actions">
                        <button type="button" class="btn-secondary" onclick="closePaymentModal()">Annuler</button>
                        <button type="submit" class="btn-primary" id="payment-submit">
                            <span>💰 Payer</span>
                            <span id="payment-amount-display"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DÉTAILS LOCATION -->
<div id="locationModal" class="modal-overlay">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h2>📋 Détails de la location</h2>
            <button class="modal-close" onclick="closeLocationModal()">×</button>
        </div>
        <div class="modal-body" id="location-modal-content">
            <div class="loading-spinner"></div>
            <p class="loading-text">Chargement...</p>
        </div>
    </div>
</div>

<!-- MODAL HISTORIQUE PAIEMENTS -->
<div id="historyModal" class="modal-overlay">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h2>📊 Historique des paiements</h2>
            <button class="modal-close" onclick="closeHistoryModal()">×</button>
        </div>
        <div class="modal-body" id="history-modal-content">
            <div class="loading-spinner"></div>
            <p class="loading-text">Chargement...</p>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMATION -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h2>✅ Paiement initié</h2>
            <button class="modal-close" onclick="closeConfirmationModal()">×</button>
        </div>
        <div class="modal-body text-center">
            <div class="confirmation-icon">💰</div>
            <h3>Votre paiement est en cours</h3>
            <p id="confirmation-message">Un SMS vous a été envoyé pour confirmer le paiement.</p>
            <div class="transaction-details" id="transaction-details"></div>
            <button class="btn-primary" onclick="closeConfirmationModal()">J'ai compris</button>
        </div>
    </div>
</div>

<style>
/* ===== PAGE LOCATIONS CLIENT AMÉLIORÉE ===== */
.client-locations-page {
    padding: 2rem;
    max-width: 1400px;
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

/* ===== STATISTIQUES ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s;
    box-shadow: var(--client-shadow-sm);
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary);
}

.stat-icon {
    font-size: 2.5rem;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--client-primary-light);
    border-radius: 50%;
    color: var(--client-primary);
}

.stat-content h3 {
    color: var(--client-text-secondary);
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--client-text-primary);
    margin: 0 0 0.25rem 0;
    line-height: 1.2;
}

.stat-sub {
    color: var(--client-text-tertiary);
    font-size: 0.9rem;
    font-weight: 500;
}

/* ===== FILTRES ===== */
.filters-section {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    box-shadow: var(--client-shadow-sm);
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.filter-tab {
    padding: 0.6rem 1.5rem;
    border: 1px solid var(--client-border-light);
    background: var(--client-bg-primary);
    color: var(--client-text-secondary);
    font-weight: 600;
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all 0.3s;
    font-size: 0.9rem;
}

.filter-tab:hover {
    background: var(--client-primary-light);
    color: var(--client-primary);
    border-color: var(--client-primary);
}

.filter-tab.active {
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
}

.search-box {
    position: relative;
    min-width: 280px;
}

.search-box input {
    width: 100%;
    padding: 0.6rem 1rem 0.6rem 2.8rem;
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-full);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--client-bg-primary);
    color: var(--client-text-primary);
}

.search-box input:focus {
    outline: none;
    border-color: var(--client-primary);
    box-shadow: 0 0 0 3px var(--client-primary-light);
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--client-text-tertiary);
    font-size: 1rem;
}

/* ===== GRILLE LOCATIONS ===== */
.locations-grid {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.location-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    display: flex;
    transition: all 0.3s;
    box-shadow: var(--client-shadow-sm);
}

.location-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--client-shadow-lg);
    border-color: var(--client-primary);
}

/* Image */
.location-image {
    position: relative;
    width: 280px;
    flex-shrink: 0;
    height: 220px;
    overflow: hidden;
    cursor: pointer;
}

.location-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.location-card:hover .location-image img {
    transform: scale(1.08);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s;
}

.location-image:hover .image-overlay {
    opacity: 1;
}

.overlay-text {
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background: var(--client-primary);
    border-radius: var(--radius-full);
    font-size: 0.85rem;
}

.location-type {
    position: absolute;
    top: 1rem;
    left: 1rem;
    padding: 0.3rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    color: white;
    z-index: 2;
}

.location-type.maison {
    background: #0891b2;
}

.location-type.appartement {
    background: #7c3aed;
}

/* Détails */
.location-details {
    flex: 1;
    padding: 1.5rem;
}

.location-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.location-header h3 {
    color: var(--client-text-primary);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0;
}

.status-badge {
    padding: 0.3rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
}

.status-acceptee {
    background: var(--client-primary-light);
    color: var(--client-primary);
}

.status-en_attente {
    background: #fef3c7;
    color: #92400e;
}

.status-terminated {
    background: #f1f5f9;
    color: #475569;
}

.location-address {
    color: var(--client-text-secondary);
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Grille infos */
.info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
    padding: 1.2rem;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-item .label {
    color: var(--client-text-tertiary);
    font-size: 0.7rem;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.info-item .value {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 700;
}

.info-item .value.highlight {
    color: var(--client-danger);
}

/* Actions */
.location-actions {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.location-actions button {
    padding: 0.7rem 1.2rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-icon {
    font-size: 1rem;
}

.btn-payer {
    background: var(--client-primary);
    color: white;
}

.btn-payer:hover {
    background: var(--client-primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--client-shadow-md);
}

.btn-details {
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
}

.btn-details:hover {
    background: var(--client-primary-light);
    color: var(--client-primary);
    border-color: var(--client-primary);
}

.btn-contact {
    background: var(--client-bg-secondary);
    color: #0891b2;
    border: 1px solid var(--client-border-light);
}

.btn-contact:hover {
    background: #cffafe;
    border-color: #0891b2;
}

.btn-history {
    background: var(--client-bg-secondary);
    color: #7c3aed;
    border: 1px solid var(--client-border-light);
}

.btn-history:hover {
    background: #ede9fe;
    border-color: #7c3aed;
}

/* État vide */
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
}

.btn-primary:hover {
    background: var(--client-primary-dark);
    transform: translateY(-3px);
    box-shadow: var(--client-shadow-lg);
    gap: 0.75rem;
}

/* ===== MODALS AMÉLIORÉS ===== */
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
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--client-border-light);
    box-shadow: var(--client-shadow-xl);
    animation: modalFadeIn 0.3s;
}

.modal-lg {
    max-width: 900px;
}

.modal-sm {
    max-width: 450px;
}

.modal-payment {
    max-width: 550px;
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
}

.modal-header h2 {
    color: var(--client-text-primary);
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: var(--client-text-tertiary);
    cursor: pointer;
    transition: all 0.3s;
    line-height: 1;
}

.modal-close:hover {
    color: var(--client-danger);
    transform: rotate(90deg);
}

.modal-body {
    padding: 2rem;
}

/* Loading */
.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--client-border-light);
    border-top-color: var(--client-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 1rem auto;
}

.loading-text {
    text-align: center;
    color: var(--client-text-secondary);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Formulaire paiement */
.payment-summary {
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    border: 1px solid var(--client-border-light);
}

.summary-item {
    display: flex;
    flex-direction: column;
}

.summary-label {
    color: var(--client-text-secondary);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 0.3rem;
}

.summary-value {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
}

.summary-amount {
    color: var(--client-primary);
    font-size: 1.6rem;
    font-weight: 800;
}

.payment-methods {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.payment-method {
    flex: 1;
    min-width: 140px;
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--client-bg-primary);
}

.payment-method:hover {
    border-color: var(--client-primary);
    background: var(--client-primary-light);
}

.payment-method input[type="radio"] {
    display: none;
}

.payment-method:has(input:checked) {
    border-color: var(--client-primary);
    background: var(--client-primary-light);
}

.method-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--client-bg-card);
    border-radius: 50%;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: var(--client-text-primary);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.form-input,
.form-select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--client-bg-primary);
    color: var(--client-text-primary);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--client-primary);
    box-shadow: 0 0 0 3px var(--client-primary-light);
}

.form-help {
    color: var(--client-text-tertiary);
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.payment-footer {
    margin-top: 2rem;
    border-top: 1px solid var(--client-border-light);
    padding-top: 1.5rem;
}

.payment-security {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--client-text-tertiary);
    font-size: 0.85rem;
    margin-bottom: 1.5rem;
    justify-content: center;
}

.payment-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-secondary {
    padding: 0.8rem 1.5rem;
    background: var(--client-bg-secondary);
    color: var(--client-text-primary);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: var(--client-border-light);
    transform: translateY(-2px);
}

.btn-primary {
    padding: 0.8rem 1.5rem;
    background: var(--client-primary);
    color: white;
    border: none;
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
}

/* Historique paiements */
.history-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.history-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--client-border-light);
    transition: all 0.3s;
}

.history-item:hover {
    background: var(--client-bg-card);
    border-color: var(--client-primary);
}

.history-date {
    font-weight: 600;
    color: var(--client-text-primary);
    min-width: 100px;
}

.history-montant {
    font-weight: 700;
    color: var(--client-primary);
}

.history-ref {
    color: var(--client-text-tertiary);
    font-size: 0.8rem;
}

.history-status {
    padding: 0.2rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    background: var(--client-primary-light);
    color: var(--client-primary);
}

.history-download {
    color: var(--client-primary);
    text-decoration: none;
    font-size: 1.2rem;
    transition: all 0.3s;
}

.history-download:hover {
    transform: scale(1.2);
}

/* Détails location */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-item h4 {
    color: var(--client-text-tertiary);
    font-size: 0.8rem;
    text-transform: uppercase;
    margin-bottom: 0.25rem;
}

.detail-item p {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.detail-description {
    border-top: 1px solid var(--client-border-light);
    padding-top: 1.5rem;
}

.detail-description h4 {
    color: var(--client-text-primary);
    margin-bottom: 1rem;
}

.detail-description p {
    color: var(--client-text-secondary);
    line-height: 1.6;
}

/* Confirmation */
.confirmation-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.transaction-details {
    background: var(--client-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.2rem;
    margin: 1.5rem 0;
    font-size: 0.9rem;
    border: 1px solid var(--client-border-light);
}

.text-center {
    text-align: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .location-card {
        flex-direction: column;
    }

    .location-image {
        width: 100%;
        height: 220px;
    }

    .info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .client-locations-page {
        padding: 1rem;
    }

    .filters-section {
        flex-direction: column;
        align-items: stretch;
    }

    .search-box {
        width: 100%;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .location-actions {
        flex-direction: column;
    }

    .location-actions button {
        width: 100%;
        justify-content: center;
    }

    .payment-summary {
        flex-direction: column;
        align-items: flex-start;
    }

    .payment-methods {
        flex-direction: column;
    }

    .payment-actions {
        flex-direction: column;
    }

    .btn-secondary,
    .btn-primary {
        width: 100%;
        justify-content: center;
    }

    .history-item {
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .history-date {
        min-width: auto;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .location-header {
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-client-theme="dark"] .stat-card,
[data-client-theme="dark"] .location-card,
[data-client-theme="dark"] .filters-section,
[data-client-theme="dark"] .empty-state,
[data-client-theme="dark"] .modal {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .stat-icon {
    background: var(--client-dark-bg);
    color: var(--client-primary);
}

[data-client-theme="dark"] .filter-tab {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .search-box input {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .info-grid {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .payment-method {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .form-input,
[data-client-theme="dark"] .form-select {
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

[data-client-theme="dark"] .history-item {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .history-item:hover {
    background: var(--client-dark-card);
}

[data-client-theme="dark"] .transaction-details {
    background: var(--client-dark-bg);
}
</style>

<script>
// ==================== CONFIGURATION ====================
const FEDAPAY_CONFIG = {
    public_key: '<?php echo e(config("fedapay.public_key")); ?>',
    environment: '<?php echo e(config("fedapay.environment", "sandbox")); ?>'
};

// ==================== VARIABLES GLOBALES ====================
let currentLocationId = null;
let currentBienType = null;
let currentMontant = 0;
let currentBienNom = '';

// ==================== GESTION PAIEMENT ====================
function openPaymentModal(btn) {
    const locationId      = btn.dataset.locationId;
    const type            = btn.dataset.type;
    const montant         = parseFloat(btn.dataset.prix);
    const bienNom         = btn.dataset.bienNom;
    const prochainPaiement = btn.dataset.prochainPaiement;

    currentLocationId = locationId;
    currentBienType   = type;
    currentMontant    = montant;
    currentBienNom    = bienNom;

    document.getElementById('payment-bien-nom').textContent = bienNom;
    document.getElementById('payment-montant').textContent = montant.toLocaleString() + ' FCFA';
    document.getElementById('payment-amount-display').textContent = montant.toLocaleString() + ' FCFA';
    document.getElementById('payment-location-id').value = locationId;
    document.getElementById('payment-type').value = type;
    document.getElementById('payment-montant-value').value = montant;

    // Afficher le vrai mois du prochain paiement (depuis PHP)
    if (prochainPaiement) {
        // prochainPaiement est au format d/m/Y
        const parts = prochainPaiement.split('/');
        if (parts.length === 3) {
            const date = new Date(parts[2], parts[1] - 1, parts[0]);
            const mois = ['Janvier','Février','Mars','Avril','Mai','Juin',
                          'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
            document.getElementById('payment-mois').textContent = mois[date.getMonth()] + ' ' + date.getFullYear();
        } else {
            document.getElementById('payment-mois').textContent = prochainPaiement;
        }
    } else {
        const now = new Date();
        const mois = ['Janvier','Février','Mars','Avril','Mai','Juin',
                      'Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
        document.getElementById('payment-mois').textContent = mois[now.getMonth()] + ' ' + now.getFullYear();
    }

    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.getElementById('fedapay-form').reset();
}

// Méthodes de paiement
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const isMobileMoney = this.value === 'mobile_money';

        document.getElementById('mobile-money-fields').style.display = isMobileMoney ? 'block' : 'none';
        document.getElementById('card-fields').style.display = isMobileMoney ? 'none' : 'block';

        // Activer/désactiver required selon la méthode
        document.getElementById('operateur').required = isMobileMoney;
        document.getElementById('telephone').required = isMobileMoney;
    });
});

function initiatePayment(event) {
    event.preventDefault();

    const form = document.getElementById('fedapay-form');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('payment-submit');
    const paymentMethod = formData.get('payment_method');
    const isMobileMoney = paymentMethod === 'mobile_money';

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>⏳ Traitement...</span>';

    const payload = {
        location_id: formData.get('location_id'),
        montant: formData.get('montant'),
        type: formData.get('type'),
        payment_method: paymentMethod
    };

    if (isMobileMoney) {
        payload.operateur = formData.get('operateur');
        payload.telephone = formData.get('telephone');
    }

    fetch('/client/paiements/initier', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) throw new Error('Erreur serveur ' + response.status);
            return data;
        });
    })
    .then(data => {
        if (data.success) {
            closePaymentModal();
            showConfirmation(data);
        } else {
            showNotification(data.message || 'Erreur lors du paiement', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>💰 Payer</span><span id="payment-amount-display">' + currentMontant.toLocaleString() + ' FCFA</span>';
        }
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        showNotification('Erreur de connexion : ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>💰 Payer</span><span id="payment-amount-display">' + currentMontant.toLocaleString() + ' FCFA</span>';
    });
}

function showConfirmation(data) {
    document.getElementById('confirmation-message').textContent =
        `Un SMS vous a été envoyé au ${document.getElementById('telephone').value} pour confirmer le paiement de ${data.montant} FCFA.`;
    document.getElementById('transaction-details').innerHTML = `
        <strong>Référence:</strong> ${data.reference}<br>
        <strong>Opérateur:</strong> ${data.operateur}<br>
        <strong>Date:</strong> ${new Date().toLocaleDateString('fr-FR')}
    `;
    document.getElementById('confirmationModal').style.display = 'flex';
}

// ==================== GESTION LOCATIONS ====================
function showLocationDetails(locationId) {
    const modal = document.getElementById('locationModal');
    const content = document.getElementById('location-modal-content');

    modal.style.display = 'flex';
    content.innerHTML = '<div class="loading-spinner"></div><p class="loading-text">Chargement...</p>';

    fetch(`/client/locations/${locationId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const loc = data.location;
                content.innerHTML = `
                    <div class="detail-grid">
                        <div class="detail-item">
                            <h4>Bien</h4>
                            <p>${loc.nom}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Adresse</h4>
                            <p>${loc.adresse}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Type</h4>
                            <p>${loc.type === 'maison' ? 'Maison' : 'Appartement'}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Date de début</h4>
                            <p>${loc.date_debut}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Loyer mensuel</h4>
                            <p>${loc.loyer} FCFA</p>
                        </div>
                        <div class="detail-item">
                            <h4>Chambres</h4>
                            <p>${loc.chambres}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Propriétaire</h4>
                            <p>${loc.proprietaire}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Téléphone</h4>
                            <p>${loc.telephone}</p>
                        </div>
                        <div class="detail-item">
                            <h4>Email</h4>
                            <p>${loc.email}</p>
                        </div>
                    </div>
                    <div class="detail-description">
                        <h4>Description</h4>
                        <p>${loc.description || 'Aucune description disponible'}</p>
                    </div>
                `;
            }
        })
        .catch(() => {
            content.innerHTML = '<p class="loading-text">Erreur de chargement</p>';
        });
}

function showPaiementHistory(locationId) {
    const modal = document.getElementById('historyModal');
    const content = document.getElementById('history-modal-content');

    modal.style.display = 'flex';
    content.innerHTML = '<div class="loading-spinner"></div><p class="loading-text">Chargement...</p>';

    fetch(`/client/locations/${locationId}/paiements`)
        .then(response => {
            if (!response.ok) throw new Error('Erreur serveur ' + response.status);
            return response.json();
        })
        .then(data => {
            if (data.success) {
                let html = '<div class="history-list">';

                if (data.paiements.length === 0) {
                    html += '<p class="loading-text">Aucun paiement effectué</p>';
                } else {
                    data.paiements.forEach(p => {
                        html += `
                            <div class="history-item">
                                <span class="history-date">${p.date}</span>
                                <span class="history-montant">${p.montant} FCFA</span>
                                <span class="history-ref">${p.reference}</span>
                                <span class="history-status">${p.statut}</span>
                                <a href="/client/paiements/${p.id}/telecharger" class="history-download">📥</a>
                            </div>
                        `;
                    });
                }

                html += '</div>';
                content.innerHTML = html;
            } else {
                content.innerHTML = '<p class="loading-text">Impossible de charger l\'historique.</p>';
            }
        })
        .catch(error => {
            console.error('Erreur historique:', error);
            content.innerHTML = '<p class="loading-text">Erreur de connexion. Veuillez réessayer.</p>';
        });
}

// ==================== FERMETURE MODALS ====================
function closeLocationModal() {
    document.getElementById('locationModal').style.display = 'none';
}

function closeHistoryModal() {
    document.getElementById('historyModal').style.display = 'none';
}

function closeConfirmationModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

// Fallback si contacterProprietaire n'est pas définie dans le layout global
if (typeof contacterProprietaire === 'undefined') {
    function contacterProprietaire(proprietaireId, locationId, type) {
        window.location.href = `/client/messagerie?proprietaire=${proprietaireId}&location=${locationId}&type=${type}`;
    }
}

// ==================== FILTRES ====================
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const status = this.dataset.status;
        document.querySelectorAll('.location-card').forEach(card => {
            const cardStatus = card.dataset.status;
            let visible = status === 'all';
            if (!visible) {
                if (status === 'active' && (cardStatus === 'acceptee' || cardStatus === 'active')) visible = true;
                if (status === 'pending' && (cardStatus === 'en_attente' || cardStatus === 'pending')) visible = true;
                if (status === 'terminated' && cardStatus === 'terminated') visible = true;
            }
            card.style.display = visible ? 'flex' : 'none';
        });
    });
});

// Recherche
document.getElementById('search-location')?.addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    document.querySelectorAll('.location-card').forEach(card => {
        const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const address = card.querySelector('.location-address')?.textContent.toLowerCase() || '';
        card.style.display = (title.includes(term) || address.includes(term)) ? 'flex' : 'none';
    });
});

// ==================== NOTIFICATIONS ====================
function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `client-toast client-toast-${type}`;
    toast.innerHTML = `
        <span style="font-size:1.2rem;">${type === 'success' ? '✅' : '❌'}</span>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes
    document.querySelectorAll('.location-card').forEach((card, i) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, i * 100);
    });

    // Fermeture des modals
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            closePaymentModal();
            closeLocationModal();
            closeHistoryModal();
            closeConfirmationModal();
        }
    });

    // Styles toast
    const style = document.createElement('style');
    style.textContent = `
        .client-toast {
            position: fixed;
            top: 90px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: var(--radius-lg);
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 10000;
            box-shadow: var(--client-shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--client-success);
            color: white;
        }
        .client-toast-error { background: var(--client-danger); }
        .client-toast.show { opacity: 1; }
    `;
    document.head.appendChild(style);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/client/locations.blade.php ENDPATH**/ ?>