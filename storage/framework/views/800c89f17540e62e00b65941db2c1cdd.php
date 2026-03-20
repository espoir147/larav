<?php $__env->startSection('client-content'); ?>
<div class="client-messagerie-page">
    <!-- En-tête -->
    <div class="messagerie-header">
        <div class="header-title">
            <h1>
                <span class="header-icon">✉️</span>
                Messagerie
            </h1>
            <p>Échangez avec vos propriétaires</p>
        </div>
        <div class="header-stats">
            <div class="unread-badge">
                <span class="unread-count"><?php echo e($nonLus ?? 0); ?></span>
                <span class="unread-text">messages non lus</span>
            </div>
        </div>
    </div>

    <!-- Container principal -->
    <div class="messagerie-container">
        <!-- COLONNE GAUCHE - Liste des conversations -->
        <div class="conversations-sidebar">
            <!-- Recherche et filtres -->
            <div class="sidebar-header">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="search-conversation" placeholder="Rechercher une conversation...">
                </div>
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">Toutes</button>
                    <button class="filter-tab" data-filter="unread">Non lues</button>
                </div>
            </div>

            <!-- Liste des conversations -->
            <div class="conversations-list" id="conversations-list">
                <?php $__empty_1 = true; $__currentLoopData = $conversations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $interlocuteur = $conv['interlocuteur'];
                        $dernierMessage = $conv['dernier_message'];
                        $nonLusConv = $conv['messages_non_lus'] ?? 0;
                        $biens = $conv['biens'] ?? [];
                        $nbBiens = count($biens);
                    ?>
                    <div class="conversation-item <?php echo e($loop->first ? 'active' : ''); ?>"
                         data-conversation-id="<?php echo e($interlocuteur->id); ?>"
                         data-unread="<?php echo e($nonLusConv > 0 ? 'true' : 'false'); ?>">
                        <div class="conversation-avatar">
                            <img src="<?php echo e($interlocuteur->photo_profil ? asset('storage/' . $interlocuteur->photo_profil) : asset('images/default-avatar.png')); ?>" alt="Avatar">
                            <?php if($nonLusConv > 0): ?>
                                <span class="avatar-badge"><?php echo e($nonLusConv); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="conversation-content">
                            <div class="conversation-header">
                                <h4><?php echo e($interlocuteur->nom); ?></h4>
                                <span class="conversation-time"><?php echo e($dernierMessage->created_at->diffForHumans()); ?></span>
                            </div>
                            <div class="conversation-preview">
                                <p class="preview-text"><?php echo e(Str::limit($dernierMessage->contenu, 50)); ?></p>
                                <?php if($nbBiens > 0): ?>
                                    <span class="badge badge-info"><?php echo e($nbBiens); ?> bien(s)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-conversations">
                        <div class="empty-icon">✉️</div>
                        <h3>Aucune conversation</h3>
                        <p>Vous n'avez pas encore échangé avec des propriétaires.</p>
                        <a href="<?php echo e(route('home')); ?>" class="btn-primary">
                            <span>Explorer les biens</span>
                            <span class="arrow">→</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLONNE DROITE - Conversation active -->
        <div class="conversation-main" id="conversation-active">
            <?php if(!empty($conversations)): ?>
                <?php
                    $firstConv = reset($conversations);
                    $interlocuteur = $firstConv['interlocuteur'];
                    $biens = $firstConv['biens'] ?? [];
                    $premierBien = count($biens) > 0 ? reset($biens) : null;
                ?>

                <!-- En-tête de la conversation -->
                <div class="conversation-header">
                    <div class="participant-info">
                        <div class="participant-avatar">
                            <img src="<?php echo e($interlocuteur->photo_profil ? asset('storage/' . $interlocuteur->photo_profil) : asset('images/default-avatar.png')); ?>" alt="Avatar">
                        </div>
                        <div class="participant-details">
                            <h2><?php echo e($interlocuteur->nom); ?></h2>
                            <?php if($premierBien): ?>
                                <div class="property-context">
                                    <span class="property-name"><?php echo e($premierBien['nom']); ?></span>
                                    <?php if(count($biens) > 1): ?>
                                        <span class="property-count">+<?php echo e(count($biens) - 1); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="conversation-tools">
                        <button class="tool-btn" title="Marquer comme lu" onclick="markAsRead(<?php echo e($interlocuteur->id); ?>)">
                            <span class="tool-icon">✅</span>
                        </button>
                        <button class="tool-btn" title="Supprimer" onclick="deleteConversation(<?php echo e($interlocuteur->id); ?>)">
                            <span class="tool-icon">🗑️</span>
                        </button>
                    </div>
                </div>

                <!-- Zone des messages -->
                <div class="messages-area" id="messages-container">
                    <?php $__currentLoopData = $firstConv['messages'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $bien = $message->maison ?? $message->appartement;
                            $isMoi = $message->expediteur_id == Auth::id();
                            $messageDate = $message->created_at->format('d/m/Y');
                            $previousDate = $loop->iteration > 1 ? $firstConv['messages'][$loop->index - 1]->created_at->format('d/m/Y') : null;
                        ?>

                        <?php if($previousDate !== $messageDate): ?>
                            <div class="date-divider">
                                <span>
                                    <?php if($message->created_at->isToday()): ?>
                                        Aujourd'hui
                                    <?php elseif($message->created_at->isYesterday()): ?>
                                        Hier
                                    <?php else: ?>
                                        <?php echo e($message->created_at->format('d/m/Y')); ?>

                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="message <?php echo e($isMoi ? 'message-sent' : 'message-received'); ?>">
                            <?php if(!$isMoi): ?>
                                <div class="message-avatar">
                                    <img src="<?php echo e($message->expediteur->photo_profil ? asset('storage/' . $message->expediteur->photo_profil) : asset('images/default-avatar.png')); ?>" alt="Avatar">
                                </div>
                            <?php endif; ?>
                            <div class="message-bubble">
                                <div class="message-header">
                                    <?php if(!$isMoi): ?>
                                        <span class="message-sender"><?php echo e($message->expediteur->nom); ?></span>
                                    <?php endif; ?>
                                    <span class="message-time"><?php echo e($message->created_at->format('H:i')); ?></span>
                                    <?php if($isMoi): ?>
                                        <span class="message-status <?php echo e($message->lu ? 'read' : ''); ?>">
                                            <?php echo e($message->lu ? '✓✓' : '✓'); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="message-text"><?php echo e($message->contenu); ?></div>
                                <?php if($bien): ?>
                                    <div class="message-context">
                                        <span>📍 <?php echo e($bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? '')); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Zone de saisie -->
                <div class="message-composer">
                    <form id="send-message-form" onsubmit="sendMessage(event)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="current-destinataire" value="<?php echo e($interlocuteur->id); ?>">
                        <input type="hidden" id="current-logement-type" value="<?php echo e($premierBien['type'] ?? ''); ?>">
                        <input type="hidden" id="current-logement-id" value="<?php echo e($premierBien['id'] ?? ''); ?>">
                        <div class="composer-wrapper">
                            <textarea
                                id="message-content"
                                placeholder="Écrivez votre message..."
                                rows="1"
                                oninput="autoResize(this)"
                            ></textarea>
                            <button type="submit" class="send-message-btn" id="send-btn">
                                <span class="btn-icon">📤</span>
                                <span class="btn-text">Envoyer</span>
                            </button>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="empty-conversation">
                    <div class="empty-icon">💬</div>
                    <h3>Aucune conversation sélectionnée</h3>
                    <p>Choisissez une conversation dans la liste pour commencer à discuter.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* ===== PAGE MESSAGERIE CLIENT AMÉLIORÉE ===== */
.client-messagerie-page {
    padding: 2rem;
    max-width: 1600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
}

/* ===== EN-TÊTE ===== */
.messagerie-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--client-border-light);
    flex-shrink: 0;
}

.header-title h1 {
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

.header-title p {
    color: var(--client-text-secondary);
    margin: 0;
    font-size: 1.1rem;
}

.unread-badge {
    background: var(--client-bg-card);
    border: 2px solid var(--client-border-light);
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: var(--client-shadow-sm);
}

.unread-count {
    background: var(--client-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 1rem;
    font-weight: 700;
}

.unread-text {
    color: var(--client-text-primary);
    font-weight: 500;
}

/* ===== CONTAINER PRINCIPAL ===== */
.messagerie-container {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 1.5rem;
    height: 700px; /* Hauteur fixe */
    min-height: 0;
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--client-shadow-lg);
}

/* ===== COLONNE GAUCHE - CONVERSATIONS ===== */
.conversations-sidebar {
    display: flex;
    flex-direction: column;
    background: var(--client-bg-secondary);
    border-right: 1px solid var(--client-border-light);
    height: 100%;
    min-height: 0; /* clé pour le scroll */
    overflow: hidden;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--client-border-light);
    background: var(--client-bg-card);
    flex-shrink: 0;
}

.search-box {
    position: relative;
    margin-bottom: 1rem;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--client-text-tertiary);
    font-size: 1rem;
    z-index: 1;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.8rem;
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

.filter-tabs {
    display: flex;
    gap: 0.5rem;
}

.filter-tab {
    flex: 1;
    padding: 0.6rem;
    background: var(--client-bg-primary);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-full);
    color: var(--client-text-secondary);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
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

/* Liste des conversations */
.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.conversation-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius-lg);
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
    background: var(--client-bg-card);
    border: 1px solid transparent;
}

.conversation-item:hover {
    background: var(--client-primary-light);
    transform: translateX(3px);
    border-color: var(--client-primary);
}

.conversation-item.active {
    background: var(--client-primary-light);
    border-left: 4px solid var(--client-primary);
}

.conversation-avatar {
    position: relative;
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.conversation-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--client-border-light);
}

.avatar-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--client-primary);
    color: white;
    font-size: 0.7rem;
    font-weight: 600;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
    border: 2px solid var(--client-bg-card);
}

.conversation-content {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 0.25rem;
}

.conversation-header h4 {
    color: var(--client-text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-time {
    color: var(--client-text-tertiary);
    font-size: 0.7rem;
    font-weight: 500;
    white-space: nowrap;
}

.conversation-preview {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.preview-text {
    color: var(--client-text-secondary);
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

/* ===== COLONNE DROITE - CONVERSATION ACTIVE ===== */
.conversation-main {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 0; /* clé pour le scroll */
    background: var(--client-bg-card);
    overflow: hidden;
}

/* En-tête conversation */
.conversation-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--client-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    background: var(--client-bg-card);
}

.participant-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.participant-avatar {
    width: 55px;
    height: 55px;
}

.participant-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--client-border-light);
}

.participant-details h2 {
    color: var(--client-text-primary);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.property-context {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--client-text-secondary);
    font-size: 0.9rem;
}

.property-name {
    color: var(--client-primary);
    font-weight: 600;
}

.property-count {
    background: var(--client-primary-light);
    color: var(--client-primary);
    padding: 0.2rem 0.6rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.conversation-tools {
    display: flex;
    gap: 0.5rem;
}

.tool-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid var(--client-border-light);
    background: var(--client-bg-primary);
    color: var(--client-text-secondary);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tool-btn:hover {
    background: var(--client-danger);
    color: white;
    border-color: var(--client-danger);
    transform: scale(1.1);
}

/* Zone des messages */
.messages-area {
    flex: 1;
    min-height: 0; /* clé pour que overflow-y fonctionne dans flex */
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: var(--client-bg-primary);
    scroll-behavior: smooth;
}

.date-divider {
    display: flex;
    justify-content: center;
    margin: 0.5rem 0;
}

.date-divider span {
    background: var(--client-bg-card);
    padding: 0.3rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--client-text-secondary);
    border: 1px solid var(--client-border-light);
}

.message {
    display: flex;
    gap: 1rem;
    max-width: 70%;
}

.message.message-sent {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message-avatar {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--client-border-light);
}

.message-bubble {
    background: var(--client-bg-card);
    padding: 1rem;
    border-radius: var(--radius-lg);
    position: relative;
    border: 1px solid var(--client-border-light);
    box-shadow: var(--client-shadow-sm);
}

.message-sent .message-bubble {
    background: var(--client-primary);
    color: white;
    border-color: var(--client-primary);
}

.message-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.message-sent .message-header {
    justify-content: flex-end;
}

.message-sender {
    font-weight: 600;
    color: var(--client-text-primary);
}

.message-sent .message-sender,
.message-sent .message-time,
.message-sent .message-status {
    color: rgba(255, 255, 255, 0.9);
}

.message-time {
    color: var(--client-text-tertiary);
}

.message-status {
    color: var(--client-text-tertiary);
    font-size: 0.8rem;
}

.message-status.read {
    color: var(--client-primary);
}

.message-sent .message-status.read {
    color: rgba(255, 255, 255, 0.9);
}

.message-text {
    line-height: 1.5;
    word-break: break-word;
}

.message-context {
    font-size: 0.7rem;
    color: var(--client-primary);
    background: var(--client-primary-light);
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    display: inline-block;
    margin-top: 0.5rem;
}

.message-sent .message-context {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Zone de saisie */
.message-composer {
    padding: 1.5rem;
    border-top: 1px solid var(--client-border-light);
    background: var(--client-bg-card);
    flex-shrink: 0;
}

.composer-wrapper {
    display: flex;
    gap: 0.75rem;
    background: var(--client-bg-primary);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-full);
    padding: 0.5rem;
    transition: all 0.3s;
}

.composer-wrapper:focus-within {
    border-color: var(--client-primary);
    box-shadow: 0 0 0 3px var(--client-primary-light);
}

.composer-wrapper textarea {
    flex: 1;
    border: none;
    padding: 0.5rem 1rem;
    font-size: 0.95rem;
    resize: none;
    max-height: 100px;
    outline: none;
    font-family: inherit;
    background: transparent;
    color: var(--client-text-primary);
}

.send-message-btn {
    padding: 0.5rem 1.5rem;
    background: var(--client-primary);
    color: white;
    border: none;
    border-radius: var(--radius-full);
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
}

.send-message-btn:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
}

/* États vides */
.empty-conversations,
.empty-conversation {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-conversations h3,
.empty-conversation h3 {
    color: var(--client-text-primary);
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
}

.empty-conversations p,
.empty-conversation p {
    color: var(--client-text-secondary);
    margin-bottom: 1.5rem;
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.8rem 1.8rem;
    background: var(--client-primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius-full);
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--client-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--client-shadow-md);
    gap: 0.75rem;
}

/* ===== BADGES ===== */
.badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-info {
    background: var(--client-primary-light);
    color: var(--client-primary);
}

/* ===== LOADING ===== */
.loading {
    text-align: center;
    padding: 2rem;
    color: var(--client-text-tertiary);
    font-style: italic;
}

/* ===== ANIMATIONS ===== */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.message {
    animation: slideIn 0.3s ease-out;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .messagerie-container {
        grid-template-columns: 300px 1fr;
    }
}

@media (max-width: 768px) {
    .client-messagerie-page {
        padding: 1rem;
    }

    .messagerie-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .messagerie-container {
        grid-template-columns: 1fr;
    }

    .conversations-sidebar {
        display: none;
    }

    .conversations-sidebar.active {
        display: flex;
        position: fixed;
        top: 70px;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 100;
    }

    .message {
        max-width: 90%;
    }

    .send-message-btn .btn-text {
        display: none;
    }

    .send-message-btn {
        padding: 0.5rem 1rem;
    }
}

@media (max-width: 480px) {
    .header-title h1 {
        font-size: 1.8rem;
    }

    .header-title p {
        font-size: 0.95rem;
    }

    .conversation-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .participant-info {
        width: 100%;
    }

    .conversation-tools {
        width: 100%;
        justify-content: flex-end;
    }

    .message {
        max-width: 100%;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-client-theme="dark"] .messagerie-container,
[data-client-theme="dark"] .conversations-sidebar,
[data-client-theme="dark"] .sidebar-header,
[data-client-theme="dark"] .conversation-main,
[data-client-theme="dark"] .conversation-header,
[data-client-theme="dark"] .message-composer,
[data-client-theme="dark"] .message-bubble {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .conversations-sidebar {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .conversation-item {
    background: var(--client-dark-card);
}

[data-client-theme="dark"] .conversation-item:hover,
[data-client-theme="dark"] .conversation-item.active {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .search-box input {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .filter-tab {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .messages-area {
    background: var(--client-dark-bg);
}

[data-client-theme="dark"] .date-divider span {
    background: var(--client-dark-card);
    border-color: var(--client-dark-border);
    color: var(--client-dark-text);
}

[data-client-theme="dark"] .message-sent .message-bubble {
    background: var(--client-dark-accent);
    border-color: var(--client-dark-accent);
}

[data-client-theme="dark"] .composer-wrapper {
    background: var(--client-dark-bg);
    border-color: var(--client-dark-border);
}

[data-client-theme="dark"] .composer-wrapper textarea {
    color: var(--client-dark-text);
}
</style>

<script>
// Auto-resize textarea
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Récupérer l'ID de conversation depuis les données PHP
const conversationActive = '<?php echo e($conversationActive ?? ''); ?>';

// Charger une conversation
function loadConversation(interlocuteurId) {
    fetch(`/client/messagerie/conversation/${interlocuteurId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderConversation(data);
                updateActiveConversation(interlocuteurId);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur lors du chargement', 'error');
        });
}

// Mettre à jour la conversation active
function updateActiveConversation(interlocuteurId) {
    document.querySelectorAll('.conversation-item').forEach(i => {
        i.classList.remove('active');
    });
    const activeItem = document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`);
    if (activeItem) {
        activeItem.classList.add('active');
        const unreadBadge = activeItem.querySelector('.avatar-badge');
        if (unreadBadge) unreadBadge.remove();
    }
}

// Afficher la conversation
function renderConversation(data) {
    const interlocuteur = data.interlocuteur;
    const biens = data.biens;
    const premierBien = biens.length > 0 ? biens[0] : null;

    let messagesHtml = '';
    let lastDate = '';

    data.messages.forEach(message => {
        const messageDate = message.date;
        const displayDate = getDisplayDate(messageDate);

        if (messageDate !== lastDate) {
            messagesHtml += `
                <div class="date-divider">
                    <span>${displayDate}</span>
                </div>
            `;
            lastDate = messageDate;
        }

        if (message.est_moi) {
            messagesHtml += `
                <div class="message message-sent">
                    <div class="message-bubble">
                        <div class="message-header">
                            <span class="message-time">${message.heure}</span>
                            <span class="message-status ${message.lu ? 'read' : ''}">${message.lu ? '✓✓' : '✓'}</span>
                        </div>
                        <div class="message-text">${escapeHtml(message.contenu)}</div>
                        <div class="message-context">📍 ${escapeHtml(message.bien_nom)}</div>
                    </div>
                </div>
            `;
        } else {
            messagesHtml += `
                <div class="message message-received">
                    <div class="message-avatar">
                        <img src="${interlocuteur.photo}" alt="Avatar">
                    </div>
                    <div class="message-bubble">
                        <div class="message-header">
                            <span class="message-sender">${escapeHtml(interlocuteur.nom)}</span>
                            <span class="message-time">${message.heure}</span>
                        </div>
                        <div class="message-text">${escapeHtml(message.contenu)}</div>
                        <div class="message-context">📍 ${escapeHtml(message.bien_nom)}</div>
                    </div>
                </div>
            `;
        }
    });

    const html = `
        <div class="conversation-header">
            <div class="participant-info">
                <div class="participant-avatar">
                    <img src="${interlocuteur.photo}" alt="Avatar">
                </div>
                <div class="participant-details">
                    <h2>${escapeHtml(interlocuteur.nom)}</h2>
                    ${premierBien ? `
                        <div class="property-context">
                            <span class="property-name">${escapeHtml(premierBien.nom)}</span>
                            ${biens.length > 1 ? `<span class="property-count">+${biens.length - 1}</span>` : ''}
                        </div>
                    ` : ''}
                </div>
            </div>
            <div class="conversation-tools">
                <button class="tool-btn" onclick="markAsRead(${interlocuteur.id})">
                    <span class="tool-icon">✅</span>
                </button>
                <button class="tool-btn" onclick="deleteConversation(${interlocuteur.id})">
                    <span class="tool-icon">🗑️</span>
                </button>
            </div>
        </div>
        <div class="messages-area" id="messages-container">
            ${messagesHtml}
        </div>
        <div class="message-composer">
            <form id="send-message-form" onsubmit="sendMessage(event)">
                <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" id="current-destinataire" value="${interlocuteur.id}">
                <input type="hidden" id="current-logement-type" value="${premierBien?.type || ''}">
                <input type="hidden" id="current-logement-id" value="${premierBien?.id || ''}">
                <div class="composer-wrapper">
                    <textarea
                        id="message-content"
                        placeholder="Écrivez votre message..."
                        rows="1"
                        oninput="autoResize(this)"
                    ></textarea>
                    <button type="submit" class="send-message-btn">
                        <span class="btn-icon">📤</span>
                        <span class="btn-text">Envoyer</span>
                    </button>
                </div>
            </form>
        </div>
    `;

    document.getElementById('conversation-active').innerHTML = html;
    scrollToBottom();
}

// Envoyer un message
function sendMessage(event) {
    event.preventDefault();

    const textarea = document.getElementById('message-content');
    const contenu = textarea.value.trim();
    const destinataireId = document.getElementById('current-destinataire')?.value;
    const logementType = document.getElementById('current-logement-type')?.value;
    const logementId = document.getElementById('current-logement-id')?.value;

    if (!contenu) {
        showNotification('Veuillez écrire un message', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('destinataire_id', destinataireId);
    formData.append('contenu', contenu);
    formData.append('logement_type', logementType);
    formData.append('logement_id', logementId);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    const sendBtn = document.querySelector('.send-message-btn');
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<span class="btn-icon">⏳</span>';

    fetch('/client/messages/envoyer', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            addMessageToUI(data.message);
            textarea.value = '';
            autoResize(textarea);
            updateConversationList(destinataireId, contenu);
        } else {
            showNotification('Erreur lors de l\'envoi', 'error');
        }
    })
    .catch(error => {
        showNotification('Erreur de connexion', 'error');
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<span class="btn-icon">📤</span><span class="btn-text">Envoyer</span>';
    });
}

// Ajouter un message à l'UI
function addMessageToUI(message) {
    const messagesArea = document.getElementById('messages-container');
    const now = new Date();
    const today = now.toLocaleDateString('fr-FR');

    const lastDivider = messagesArea.querySelector('.date-divider:last-child');
    const lastDividerText = lastDivider?.querySelector('span')?.textContent;

    if (lastDividerText !== 'Aujourd\'hui' && lastDividerText !== getDisplayDate(today)) {
        const divider = document.createElement('div');
        divider.className = 'date-divider';
        divider.innerHTML = '<span>Aujourd\'hui</span>';
        messagesArea.appendChild(divider);
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = 'message message-sent';
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-header">
                <span class="message-time">${message.heure}</span>
                <span class="message-status">✓</span>
            </div>
            <div class="message-text">${escapeHtml(message.contenu)}</div>
            <div class="message-context">📍 ${escapeHtml(message.bien_nom)}</div>
        </div>
    `;

    messagesArea.appendChild(messageDiv);
    scrollToBottom();
}

// Scroll en bas
function scrollToBottom() {
    const messagesArea = document.getElementById('messages-container');
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
}

// Mettre à jour la liste des conversations
function updateConversationList(interlocuteurId, dernierMessage) {
    const conversationItem = document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`);

    if (conversationItem) {
        const preview = conversationItem.querySelector('.preview-text');
        if (preview) preview.textContent = dernierMessage.substring(0, 50) + (dernierMessage.length > 50 ? '...' : '');

        const time = conversationItem.querySelector('.conversation-time');
        if (time) time.textContent = 'À l\'instant';

        const list = document.getElementById('conversations-list');
        list.prepend(conversationItem);
    }
}

// Marquer comme lu
function markAsRead(expediteurId) {
    fetch('/client/messages/marquer-lu', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ expediteur_id: expediteurId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll(`.conversation-item[data-conversation-id="${expediteurId}"] .avatar-badge`).forEach(b => b.remove());
            document.querySelectorAll('.message-status').forEach(status => {
                status.textContent = '✓✓';
                status.classList.add('read');
            });
            showNotification('Messages marqués comme lus', 'success');
        }
    });
}

// Supprimer une conversation
function deleteConversation(interlocuteurId) {
    if (!confirm('Supprimer cette conversation ?')) return;

    fetch(`/client/messagerie/conversation/${interlocuteurId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`)?.remove();

            if (document.querySelectorAll('.conversation-item').length === 0) {
                document.getElementById('conversations-list').innerHTML = `
                    <div class="empty-conversations">
                        <div class="empty-icon">✉️</div>
                        <h3>Aucune conversation</h3>
                        <p>Vous n'avez pas encore échangé avec des propriétaires.</p>
                        <a href="<?php echo e(route('home')); ?>" class="btn-primary">Explorer les biens</a>
                    </div>
                `;
            }

            document.getElementById('conversation-active').innerHTML = `
                <div class="empty-conversation">
                    <div class="empty-icon">💬</div>
                    <h3>Aucune conversation sélectionnée</h3>
                    <p>Choisissez une conversation dans la liste pour commencer à discuter.</p>
                </div>
            `;

            showNotification('Conversation supprimée', 'success');
        }
    });
}

// Filtrer les conversations
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        document.querySelectorAll('.conversation-item').forEach(item => {
            const isUnread = item.dataset.unread === 'true';
            item.style.display = filter === 'all' || (filter === 'unread' && isUnread) ? 'flex' : 'none';
        });
    });
});

// Recherche
document.getElementById('search-conversation')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.conversation-item').forEach(item => {
        const name = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const preview = item.querySelector('.preview-text')?.textContent.toLowerCase() || '';
        item.style.display = name.includes(searchTerm) || preview.includes(searchTerm) ? 'flex' : 'none';
    });
});

// Obtenir le libellé de date
function getDisplayDate(dateStr) {
    const date = new Date(dateStr.split('/').reverse().join('-'));
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) return 'Aujourd\'hui';
    if (date.toDateString() === yesterday.toDateString()) return 'Hier';
    return dateStr;
}

// Échapper le HTML
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Notification
function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `client-toast client-toast-${type}`;
    toast.innerHTML = `
        <span style="font-size: 1.2rem;">${type === 'success' ? '✅' : '❌'}</span>
        <span>${message}</span>
    `;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (conversationActive) {
        setTimeout(() => {
            loadConversation(conversationActive);
            updateActiveConversation(conversationActive);
        }, 300);
    }

    scrollToBottom();

    // Ajouter les styles d'animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
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
            max-width: 350px;
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

// Changer de conversation
document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', function() {
        loadConversation(this.dataset.conversationId);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/client/messagerie.blade.php ENDPATH**/ ?>