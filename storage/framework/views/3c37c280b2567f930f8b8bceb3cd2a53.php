<?php $__env->startSection('proprietaire-content'); ?>
<div class="proprio-payer-loyer-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">💰</span>
            Payer mes loyers
        </h1>
        <p>Gérez et payez vos loyers en tant que locataire</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-content">
                <h3>Locations actives</h3>
                <p class="stat-number"><?php echo e($stats['locations_actives']); ?></p>
                <div class="stat-details">
                    <span class="detail-item">Biens que vous louez</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-content">
                <h3>Total mensuel</h3>
                <p class="stat-number"><?php echo e(number_format($stats['total_mensuel'], 0, ',', ' ')); ?> FCFA</p>
                <div class="stat-details">
                    <span class="detail-item">Loyers à payer ce mois</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3>Prochain paiement</h3>
                <p class="stat-number"><?php echo e($stats['prochain_paiement'] ?? 'Aucun'); ?></p>
                <div class="stat-details">
                    <?php if($stats['prochain_montant'] > 0): ?>
                    <span class="detail-item"><?php echo e(number_format($stats['prochain_montant'], 0, ',', ' ')); ?> FCFA</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des locations -->
    <div class="locations-section">
        <div class="section-header">
            <h2>
                <span class="section-icon">🏠</span>
                Mes locations (<?php echo e($locations->count()); ?>)
            </h2>
        </div>

        <?php if($locations->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-icon">🏠</div>
            <h3>Aucune location active</h3>
            <p>Vous n'avez aucune location active pour le moment.</p>
            <a href="<?php echo e(route('home')); ?>" class="btn-primary">
                <span>🔍 Rechercher un bien</span>
                <span class="arrow">→</span>
            </a>
        </div>
        <?php else: ?>
        <div class="locations-list">
            <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $bien = $location->maison ?? $location->appartement;
                $prix = $bien->prix ?? $bien->prix_mensuel ?? 0;
                $proprietaire = $bien->proprietaire;
                $dernierPaiement = $location->paiements()->latest()->first();
            ?>
            <div class="location-card">
                <div class="location-header">
                    <h3><?php echo e($bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? '')); ?></h3>
                    <span class="badge badge-success">Actif</span>
                </div>

                <div class="location-content">
                    <div class="location-info">
                        <div class="info-row">
                            <span class="info-label">Adresse</span>
                            <span class="info-value"><?php echo e($bien->adresse); ?>, <?php echo e($bien->ville); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Propriétaire</span>
                            <span class="info-value"><?php echo e($proprietaire->nom ?? 'N/A'); ?></span>
                        </div>
                        <?php if($proprietaire->telephone ?? false): ?>
                        <div class="info-row">
                            <span class="info-label">Téléphone</span>
                            <span class="info-value">📞 <?php echo e($proprietaire->indicatif_pays ?? ''); ?> <?php echo e($proprietaire->telephone ?? ''); ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="info-row">
                            <span class="info-label">Caractéristiques</span>
                            <span class="info-value">🛏️ <?php echo e($bien->nombre_chambres); ?> chambre(s) • 🏠 <?php echo e(ucfirst($bien->type)); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Depuis le</span>
                            <span class="info-value"><?php echo e($location->date_debut->format('d/m/Y')); ?></span>
                        </div>
                    </div>

                    <div class="location-actions">
                        <div class="price-box">
                            <span class="price-label">Loyer mensuel</span>
                            <span class="price-value"><?php echo e(number_format($prix, 0, ',', ' ')); ?> FCFA</span>
                        </div>

                        <div class="action-buttons">
                            <button class="btn-secondary" onclick="showLocationDetails(<?php echo e($location->id); ?>)">
                                <span class="btn-icon">📋</span>
                                <span>Détails</span>
                            </button>
                            <button class="btn-primary" onclick="openPaymentModal(<?php echo e($location->id); ?>, '<?php echo e($location->type_logement); ?>', <?php echo e($prix); ?>, '<?php echo e(addslashes($bien->nom ?? $bien->numero_appartement)); ?>')">
                                <span class="btn-icon">💰</span>
                                <span>Payer</span>
                            </button>
                        </div>
                    </div>
                </div>

                <?php if($dernierPaiement): ?>
                <div class="last-payment">
                    <div class="payment-info">
                        <span class="payment-label">Dernier paiement</span>
                        <span class="payment-detail"><?php echo e($dernierPaiement->date_paiement->format('d/m/Y')); ?> - <?php echo e(number_format($dernierPaiement->montant, 0, ',', ' ')); ?> FCFA</span>
                    </div>
                    <a href="<?php echo e(route('proprietaire.loyer.recu', $dernierPaiement->id)); ?>" class="btn-receipt">
                        <span class="btn-icon">📄</span>
                        <span>Reçu</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Historique des paiements -->
    <div class="history-section">
        <div class="section-header">
            <h2>
                <span class="section-icon">📊</span>
                Historique des paiements (<?php echo e($paiements->total()); ?>)
            </h2>
        </div>

        <?php if($paiements->isEmpty()): ?>
        <div class="empty-state small">
            <div class="empty-icon">💰</div>
            <p>Aucun paiement effectué</p>
        </div>
        <?php else: ?>
        <div class="payments-list">
            <?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="payment-item">
                <div class="payment-date">
                    <span class="date-day"><?php echo e($paiement->created_at->format('d/m/Y')); ?></span>
                    <span class="date-period"><?php echo e($paiement->mois_paye); ?> <?php echo e($paiement->annee_paye); ?></span>
                </div>
                <div class="payment-amount">
                    <span class="amount"><?php echo e(number_format($paiement->montant, 0, ',', ' ')); ?> FCFA</span>
                    <span class="operator"><?php echo e($paiement->operateur->nom ?? 'Mobile Money'); ?></span>
                </div>
                <div class="payment-action">
                    <a href="<?php echo e(route('proprietaire.loyer.recu', $paiement->id)); ?>" class="btn-receipt small">
                        <span class="btn-icon">📄</span>
                        <span>Reçu</span>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($paiements->hasPages()): ?>
        <div class="pagination">
            <?php echo e($paiements->links()); ?>

        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL PAIEMENT -->
<div id="paymentModal" class="modal-overlay">
    <div class="modal modal-payment">
        <div class="modal-header">
            <h2>
                <span class="header-icon">💰</span>
                Payer votre loyer
            </h2>
            <button class="modal-close" onclick="closePaymentModal()">&times;</button>
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
            <h2>
                <span class="header-icon">📋</span>
                Détails de la location
            </h2>
            <button class="modal-close" onclick="closeLocationModal()">&times;</button>
        </div>
        <div class="modal-body" id="location-modal-content">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMATION -->
<div id="confirmationModal" class="modal-overlay">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h2>
                <span class="header-icon">✅</span>
                Paiement initié
            </h2>
            <button class="modal-close" onclick="closeConfirmationModal()">&times;</button>
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
/* ===== PAGE PAYER LOYER PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-payer-loyer-page {
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
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
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

/* ===== SECTIONS ===== */
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

.locations-section,
.history-section {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--proprio-shadow-sm);
}

/* ===== LISTE LOCATIONS ===== */
.locations-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.location-card {
    background: var(--proprio-bg-secondary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.3s;
}

.location-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
    border-color: var(--proprio-primary);
}

.location-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--proprio-bg-card);
    border-bottom: 1px solid var(--proprio-border-light);
}

.location-header h3 {
    color: var(--proprio-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
}

.badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
}

.location-content {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 1.5rem;
    padding: 1.5rem;
    align-items: center;
}

.location-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-row {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
}

.info-label {
    color: var(--proprio-text-tertiary);
    font-weight: 500;
    min-width: 100px;
}

.info-value {
    color: var(--proprio-text-primary);
    font-weight: 500;
}

.location-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    min-width: 200px;
}

.price-box {
    text-align: right;
    padding: 0.5rem;
    background: var(--proprio-bg-card);
    border-radius: var(--radius-lg);
    border: 1px solid var(--proprio-border-light);
}

.price-label {
    display: block;
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.price-value {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--proprio-primary);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 0.6rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
}

.btn-primary {
    background: var(--proprio-primary);
    color: white;
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--proprio-bg-card);
    color: var(--proprio-text-primary);
    border: 1px solid var(--proprio-border-light);
}

.btn-secondary:hover {
    background: var(--proprio-primary-light);
    border-color: var(--proprio-primary);
    color: var(--proprio-primary);
}

.btn-icon {
    font-size: 0.9rem;
}

/* Dernier paiement */
.last-payment {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: var(--proprio-primary-light);
    border-top: 1px solid var(--proprio-border-light);
}

.payment-info {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.payment-label {
    font-weight: 600;
    color: var(--proprio-primary);
    font-size: 0.85rem;
}

.payment-detail {
    color: var(--proprio-text-primary);
    font-size: 0.85rem;
}

.btn-receipt {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.3rem 0.8rem;
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-md);
    color: var(--proprio-text-primary);
    text-decoration: none;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-receipt.small {
    padding: 0.2rem 0.6rem;
    font-size: 0.7rem;
}

.btn-receipt:hover {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

/* ===== HISTORIQUE PAIEMENTS ===== */
.payments-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.payment-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    border: 1px solid var(--proprio-border-light);
    transition: all 0.3s;
}

.payment-item:hover {
    background: var(--proprio-bg-card);
    border-color: var(--proprio-primary);
}

.payment-date {
    display: flex;
    flex-direction: column;
    min-width: 100px;
}

.date-day {
    font-weight: 600;
    color: var(--proprio-text-primary);
    font-size: 0.9rem;
}

.date-period {
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
}

.payment-amount {
    display: flex;
    flex-direction: column;
    text-align: center;
    flex: 1;
}

.amount {
    font-weight: 700;
    color: var(--proprio-primary);
    font-size: 1rem;
}

.operator {
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
}

.payment-action {
    min-width: 80px;
    text-align: right;
}

/* ===== ÉTAT VIDE ===== */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state.small {
    padding: 2rem;
}

.empty-icon {
    font-size: 3rem;
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
    margin: 0 0 1.5rem 0;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.8rem;
    background: var(--proprio-primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-lg);
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

/* ===== PAGINATION ===== */
.pagination {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

/* ===== MODALS ===== */
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
    max-width: 550px;
    max-height: 90vh;
    overflow-y: auto;
    border: 1px solid var(--proprio-border-light);
    box-shadow: var(--proprio-shadow-xl);
    animation: modalFadeIn 0.3s;
}

.modal-lg {
    max-width: 900px;
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

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--proprio-border-light);
    border-top-color: var(--proprio-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 2rem auto;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== FORMULAIRE PAIEMENT ===== */
.payment-summary {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    border: 1px solid var(--proprio-border-light);
}

.summary-item {
    display: flex;
    flex-direction: column;
}

.summary-label {
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.summary-value {
    color: var(--proprio-text-primary);
    font-size: 0.95rem;
    font-weight: 600;
}

.summary-amount {
    color: var(--proprio-primary);
    font-size: 1.5rem;
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
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--proprio-bg-primary);
}

.payment-method:hover {
    border-color: var(--proprio-primary);
    background: var(--proprio-primary-light);
}

.payment-method input[type="radio"] {
    display: none;
}

.payment-method:has(input:checked) {
    border-color: var(--proprio-primary);
    background: var(--proprio-primary-light);
}

.method-icon {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--proprio-bg-card);
    border-radius: 50%;
}

.method-name {
    font-weight: 600;
    color: var(--proprio-text-primary);
    font-size: 0.85rem;
}

.method-desc {
    color: var(--proprio-text-tertiary);
    font-size: 0.7rem;
}

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

.form-input,
.form-select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

.form-help {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.payment-footer {
    margin-top: 2rem;
    border-top: 1px solid var(--proprio-border-light);
    padding-top: 1.5rem;
}

.payment-security {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
    margin-bottom: 1rem;
    justify-content: center;
}

.payment-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

.btn-secondary {
    padding: 0.8rem 1.5rem;
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-secondary:hover {
    background: var(--proprio-border-light);
    transform: translateY(-2px);
}

/* Confirmation */
.confirmation-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.transaction-details {
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin: 1.5rem 0;
    border: 1px solid var(--proprio-border-light);
}

.text-center {
    text-align: center;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .location-content {
        grid-template-columns: 1fr;
    }

    .location-actions {
        width: 100%;
    }

    .action-buttons {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .proprio-payer-loyer-page {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .info-row {
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        min-width: auto;
    }

    .last-payment {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .payment-info {
        flex-direction: column;
        gap: 0.25rem;
    }

    .payment-item {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }

    .payment-action {
        text-align: center;
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
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }

    .page-header h1 {
        font-size: 1.8rem;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .stat-card,
[data-proprietaire-theme="dark"] .locations-section,
[data-proprietaire-theme="dark"] .history-section,
[data-proprietaire-theme="dark"] .modal {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .stat-icon {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .location-card {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .location-header {
    background: var(--proprio-dark-card);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .price-box {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .btn-secondary {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-secondary:hover {
    background: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .last-payment {
    background: rgba(96, 165, 250, 0.1);
}

[data-proprietaire-theme="dark"] .payment-item {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .payment-item:hover {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .payment-method {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .payment-method:hover {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .payment-method:has(input:checked) {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .method-icon {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .modal-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .transaction-details {
    background: var(--proprio-dark-bg);
}
</style>

<script>
// ==================== VARIABLES GLOBALES ====================
let currentLocationId = null;
let currentBienType = null;
let currentMontant = 0;
let currentBienNom = '';

// ==================== GESTION MODAL PAIEMENT ====================
function openPaymentModal(locationId, type, montant, bienNom) {
    currentLocationId = locationId;
    currentBienType = type;
    currentMontant = montant;
    currentBienNom = bienNom;

    document.getElementById('payment-bien-nom').textContent = bienNom;
    document.getElementById('payment-montant').textContent = montant.toLocaleString() + ' FCFA';
    document.getElementById('payment-amount-display').textContent = montant.toLocaleString() + ' FCFA';
    document.getElementById('payment-location-id').value = locationId;
    document.getElementById('payment-type').value = type;
    document.getElementById('payment-montant-value').value = montant;

    const now = new Date();
    const mois = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
                  'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
    document.getElementById('payment-mois').textContent = mois[now.getMonth()] + ' ' + now.getFullYear();

    document.getElementById('paymentModal').style.display = 'flex';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.getElementById('fedapay-form').reset();
}

// ==================== GESTION MÉTHODES PAIEMENT ====================
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('mobile-money-fields').style.display =
            this.value === 'mobile_money' ? 'block' : 'none';
        document.getElementById('card-fields').style.display =
            this.value === 'card' ? 'block' : 'none';
    });
});

// ==================== INITIATION PAIEMENT ====================
function initiatePayment(event) {
    event.preventDefault();

    const form = document.getElementById('fedapay-form');
    const formData = new FormData(form);
    const paymentMethod = formData.get('payment_method');
    const submitBtn = document.getElementById('payment-submit');

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>⏳ Traitement...</span>';

    if (paymentMethod === 'mobile_money') {
        fetch('/proprietaire/loyer/initier-paiement', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                location_id:    formData.get('location_id'),
                montant:        formData.get('montant'),
                operateur:      formData.get('operateur'),
                telephone:      formData.get('telephone'),
                type:           formData.get('type'),
                payment_method: paymentMethod
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closePaymentModal();
                showConfirmation(data);
                setTimeout(() => location.reload(), 3000);
            } else {
                showNotification(data.message || 'Erreur lors du paiement', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>💰 Payer</span><span id="payment-amount-display">' + currentMontant.toLocaleString() + ' FCFA</span>';
            }
        })
        .catch(error => {
            showNotification('Erreur de connexion', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>💰 Payer</span><span id="payment-amount-display">' + currentMontant.toLocaleString() + ' FCFA</span>';
        });
    } else {
        showNotification('Paiement par carte sera bientôt disponible', 'info');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>💰 Payer</span><span id="payment-amount-display">' + currentMontant.toLocaleString() + ' FCFA</span>';
        closePaymentModal();
    }
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

// ==================== DÉTAILS LOCATION ====================
function showLocationDetails(locationId) {
    const modal = document.getElementById('locationModal');
    const content = document.getElementById('location-modal-content');

    modal.style.display = 'flex';
    content.innerHTML = '<div class="loading-spinner"></div>';

    fetch(`/proprietaire/loyer/location/${locationId}/details`)
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
            content.innerHTML = '<p class="error">Erreur de chargement</p>';
        });
}

// ==================== FERMETURE MODALS ====================
function closeLocationModal() {
    document.getElementById('locationModal').style.display = 'none';
}

function closeConfirmationModal() {
    document.getElementById('confirmationModal').style.display = 'none';
}

// ==================== NOTIFICATION ====================
function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `proprio-toast proprio-toast-${type}`;

    let icon = '✅';
    if (type === 'error') icon = '❌';
    if (type === 'info') icon = 'ℹ️';

    toast.innerHTML = `
        <span style="font-size: 1.2rem;">${icon}</span>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// ==================== FERMETURE MODALS EN CLIQUANT À L'EXTÉRIEUR ====================
window.addEventListener('click', function(e) {
    const modals = ['paymentModal', 'locationModal', 'confirmationModal'];
    modals.forEach(id => {
        const modal = document.getElementById(id);
        if (e.target === modal) {
            if (id === 'paymentModal') closePaymentModal();
            if (id === 'locationModal') closeLocationModal();
            if (id === 'confirmationModal') closeConfirmationModal();
        }
    });
});

// ==================== STYLES POUR TOAST ====================
const style = document.createElement('style');
style.textContent = `
    .proprio-toast {
        position: fixed;
        top: 90px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        opacity: 0;
        transition: opacity 0.3s;
        z-index: 10000;
        box-shadow: var(--proprio-shadow-lg);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: var(--proprio-primary);
        color: white;
    }
    .proprio-toast-error { background: var(--proprio-danger); }
    .proprio-toast.show { opacity: 1; }
`;
document.head.appendChild(style);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.proprietaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/proprietaire/payer-loyer.blade.php ENDPATH**/ ?>