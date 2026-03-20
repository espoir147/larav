@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-messagerie-page">
    <!-- En-tête -->
    <div class="messagerie-header">
        <div class="header-title">
            <h1>
                <span class="header-icon">✉️</span>
                Messagerie
            </h1>
            <p>Échangez avec vos locataires et clients</p>
        </div>
        <div class="header-stats">
            <div class="unread-badge">
                <span class="unread-count">{{ $nonLus ?? 0 }}</span>
                <span class="unread-text">message(s) non lu(s)</span>
            </div>
        </div>
    </div>

    <!-- Container principal -->
    <div class="messagerie-container">
        <!-- COLONNE GAUCHE - Liste des conversations -->
        <div class="conversations-sidebar">
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

            <div class="conversations-list" id="conversations-list">
                @forelse($conversations ?? [] as $conv)
                    @php
                        $interlocuteur = $conv['interlocuteur'];
                        $dernierMessage = $conv['dernier_message'];
                        $nonLusConv = $conv['messages_non_lus'] ?? 0;
                        $biens = $conv['biens'] ?? [];
                        $nbBiens = count($biens);
                    @endphp
                    <div class="conversation-item {{ $loop->first ? 'active' : '' }}"
                         data-conversation-id="{{ $interlocuteur->id }}"
                         data-unread="{{ $nonLusConv > 0 ? 'true' : 'false' }}">
                        <div class="conversation-avatar">
                            <img src="{{ $interlocuteur->photo_profil ? asset('storage/' . $interlocuteur->photo_profil) : asset('images/default-avatar.png') }}" alt="Avatar">
                            @if($nonLusConv > 0)
                                <span class="avatar-badge">{{ $nonLusConv }}</span>
                            @endif
                        </div>
                        <div class="conversation-content">
                            <div class="conversation-header">
                                <h4>{{ $interlocuteur->nom }}</h4>
                                <span class="conversation-time">{{ $dernierMessage->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="conversation-preview">
                                <p class="preview-text">{{ Str::limit($dernierMessage->contenu, 60) }}</p>
                                @if($nbBiens > 0)
                                    <span class="badge badge-info">{{ $nbBiens }} bien(s)</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-conversations">
                        <div class="empty-icon">✉️</div>
                        <h3>Aucune conversation</h3>
                        <p>Vous n'avez pas encore échangé avec des locataires ou clients.</p>
                        <a href="{{ route('proprietaire.biens') }}" class="btn-primary">
                            <span>Voir mes biens</span>
                            <span class="arrow">→</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- COLONNE DROITE - Conversation active -->
        <div class="conversation-main" id="conversation-active">
            @if(!empty($conversations))
                @php
                    $firstConv = reset($conversations);
                    $interlocuteur = $firstConv['interlocuteur'];
                    $biens = $firstConv['biens'] ?? [];
                    $premierBien = count($biens) > 0 ? reset($biens) : null;
                @endphp

                <div class="conversation-split">
                    <!-- Zone messages (70%) -->
                    <div class="conversation-messages">
                        <div class="conversation-header">
                            <div class="participant-info">
                                <div class="participant-avatar">
                                    <img src="{{ $interlocuteur->photo_profil ? asset('storage/' . $interlocuteur->photo_profil) : asset('images/default-avatar.png') }}" alt="Avatar">
                                </div>
                                <div class="participant-details">
                                    <h2>{{ $interlocuteur->nom }}</h2>
                                    @if($premierBien)
                                        <div class="property-context">
                                            <span class="property-name">{{ $premierBien['nom'] }}</span>
                                            @if(count($biens) > 1)
                                                <span class="property-count">+{{ count($biens) - 1 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="conversation-tools">
                                <button class="tool-btn" title="Marquer comme lu" onclick="markAsRead({{ $interlocuteur->id }})">
                                    <span class="tool-icon">✅</span>
                                </button>
                                <button class="tool-btn" title="Supprimer" onclick="deleteConversation({{ $interlocuteur->id }})">
                                    <span class="tool-icon">🗑️</span>
                                </button>
                            </div>
                        </div>

                        <div class="messages-area" id="messages-container">
                            @foreach($firstConv['messages'] ?? [] as $message)
                                @php
                                    $bien = $message->maison ?? $message->appartement;
                                    $isMoi = $message->expediteur_id == Auth::id();
                                    $messageDate = $message->created_at->format('d/m/Y');
                                    $previousDate = $loop->iteration > 1 ? $firstConv['messages'][$loop->index - 1]->created_at->format('d/m/Y') : null;
                                @endphp

                                @if($previousDate !== $messageDate)
                                    <div class="date-divider">
                                        <span>
                                            @if($message->created_at->isToday())
                                                Aujourd'hui
                                            @elseif($message->created_at->isYesterday())
                                                Hier
                                            @else
                                                {{ $message->created_at->format('d/m/Y') }}
                                            @endif
                                        </span>
                                    </div>
                                @endif

                                <div class="message {{ $isMoi ? 'message-sent' : 'message-received' }}">
                                    @if(!$isMoi)
                                        <div class="message-avatar">
                                            <img src="{{ $message->expediteur->photo_profil ? asset('storage/' . $message->expediteur->photo_profil) : asset('images/default-avatar.png') }}" alt="Avatar">
                                        </div>
                                    @endif
                                    <div class="message-bubble">
                                        <div class="message-header">
                                            @if(!$isMoi)
                                                <span class="message-sender">{{ $message->expediteur->nom }}</span>
                                            @endif
                                            <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                                            @if($isMoi)
                                                <span class="message-status {{ $message->lu ? 'read' : '' }}">
                                                    {{ $message->lu ? '✓✓' : '✓' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="message-text">{{ $message->contenu }}</div>
                                        @if($bien)
                                            <div class="message-context">
                                                <span>📍 {{ $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? '') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="message-composer">
                            <form id="send-message-form" onsubmit="sendMessage(event)">
                                @csrf
                                <input type="hidden" id="current-destinataire" value="{{ $interlocuteur->id }}">
                                <input type="hidden" id="current-logement-type" value="{{ $premierBien['type'] ?? '' }}">
                                <input type="hidden" id="current-logement-id" value="{{ $premierBien['id'] ?? '' }}">
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
                    </div>

                    <!-- Zone biens discutés (30%) -->
                    <div class="conversation-properties">
                        <div class="properties-sidebar-header">
                            <span class="title-icon">🏠</span>
                            <span>Biens discutés</span>
                            @if(!empty($biens))
                                <span class="biens-count">{{ count($biens) }}</span>
                            @endif
                        </div>

                        <div class="properties-sidebar-body">
                            @if(!empty($biens))
                                <div class="properties-list" id="biens-list-{{ $interlocuteur->id }}">
                                    @foreach($biens as $bien)
                                        @php
                                            $estDejaLocataire = false;
                                            $isMaison = $bien['type'] === 'maison';
                                        @endphp
                                        <div class="property-card {{ $estDejaLocataire ? 'is-locataire' : '' }}"
                                             data-bien-type="{{ $bien['type'] }}"
                                             data-bien-id="{{ $bien['id'] }}"
                                             data-client-id="{{ $interlocuteur->id }}">

                                            <!-- Bannière type -->
                                            <div class="property-card-banner {{ $isMaison ? 'banner-maison' : 'banner-appart' }}">
                                                <span class="banner-icon">{{ $isMaison ? '🏠' : '🏢' }}</span>
                                                <span class="banner-label">{{ $isMaison ? 'Maison' : 'Appartement' }}</span>
                                            </div>

                                            <!-- Infos -->
                                            <div class="property-card-body">
                                                <h4 class="property-card-name">{{ $bien['nom'] }}</h4>
                                                <p class="property-card-address">
                                                    <span>📍</span> {{ Str::limit($bien['adresse'], 30) }}
                                                </p>

                                                @if($estDejaLocataire)
                                                    <p class="property-locataire-text">✅ Déjà locataire</p>
                                                @else
                                                    <button class="btn-accepter"
                                                            onclick="accepterLocataire({{ $interlocuteur->id }}, '{{ $bien['type'] }}', {{ $bien['id'] }}, this)">
                                                        Accepter comme locataire
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="no-properties">
                                    <div class="no-properties-icon">🏚️</div>
                                    <p>Aucun bien discuté dans cette conversation</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            @else
                <div class="empty-conversation">
                    <div class="empty-icon">💬</div>
                    <h3>Aucune conversation sélectionnée</h3>
                    <p>Choisissez une conversation dans la liste pour commencer à discuter.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* ===== PAGE MESSAGERIE PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-messagerie-page {
    padding: 1.5rem;
    max-width: 1600px;
    margin: 0 auto;
    /* height: calc(100vh - 70px);  ← Supprimé pour permettre la hauteur fixe */
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
    border-bottom: 2px solid var(--proprio-border-light);
    flex-shrink: 0;
}

.header-title h1 {
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

.header-title p {
    color: var(--proprio-text-secondary);
    font-size: 1.1rem;
    margin: 0;
}

.header-stats {
    display: flex;
    align-items: center;
}

.unread-badge {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-full);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: var(--proprio-shadow-sm);
}

.unread-count {
    background: var(--proprio-danger);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: var(--radius-full);
    font-size: 1rem;
    font-weight: 700;
}

.unread-text {
    color: var(--proprio-text-primary);
    font-weight: 500;
}

/* ===== CONTAINER PRINCIPAL - HAUTEUR FIXE ===== */
.messagerie-container {
    display: grid;
    grid-template-columns: 360px 1fr;
    gap: 1.5rem;
    height: 700px; /* Hauteur fixe comme demandé */
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--proprio-shadow-lg);
}

/* ===== COLONNE GAUCHE - CONVERSATIONS ===== */
.conversations-sidebar {
    display: flex;
    flex-direction: column;
    background: var(--proprio-bg-secondary);
    border-right: 1px solid var(--proprio-border-light);
    height: 100%;
    overflow: hidden;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-card);
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
    color: var(--proprio-text-tertiary);
    font-size: 1rem;
    z-index: 1;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.8rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-full);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
}

.search-box input:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
}

.filter-tab {
    flex: 1;
    padding: 0.6rem;
    background: var(--proprio-bg-primary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-full);
    color: var(--proprio-text-secondary);
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-tab:hover {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border-color: var(--proprio-primary);
}

.filter-tab.active {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
}

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
    background: var(--proprio-bg-card);
    border: 1px solid transparent;
}

.conversation-item:hover {
    background: var(--proprio-primary-light);
    transform: translateX(3px);
    border-color: var(--proprio-primary);
}

.conversation-item.active {
    background: var(--proprio-primary-light);
    border-left: 4px solid var(--proprio-primary);
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
    border: 2px solid var(--proprio-border-light);
}

.avatar-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--proprio-primary);
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
    border: 2px solid var(--proprio-bg-card);
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
    color: var(--proprio-text-primary);
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-time {
    color: var(--proprio-text-tertiary);
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
    color: var(--proprio-text-secondary);
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

.badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-info {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
}

/* ===== COLONNE DROITE - CONVERSATION ACTIVE ===== */
.conversation-main {
    flex: 1;
    min-height: 0; /* FIX */
    background: var(--proprio-bg-card);
    overflow: hidden;
}

.conversation-split {
    display: flex;
    height: 100%;
    min-height: 0; /* FIX */
}

/* Zone messages (70%) */
.conversation-messages {
    flex: 0 0 70%;
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 0; /* FIX: permet au flex-child de rétrécir correctement */
    border-right: 1px solid var(--proprio-border-light);
    overflow: hidden;
}

.conversation-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    background: var(--proprio-bg-card);
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
    border: 3px solid var(--proprio-border-light);
}

.participant-details h2 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.property-context {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--proprio-text-secondary);
    font-size: 0.9rem;
}

.property-name {
    color: var(--proprio-primary);
    font-weight: 600;
}

.property-count {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
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
    border: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-secondary);
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tool-btn:hover {
    background: var(--proprio-danger);
    color: white;
    border-color: var(--proprio-danger);
    transform: scale(1.1);
}

.messages-area {
    flex: 1;
    min-height: 0; /* FIX: clé pour que overflow-y fonctionne dans un flex container */
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: var(--proprio-bg-primary);
    scroll-behavior: smooth;
}

.date-divider {
    display: flex;
    justify-content: center;
    margin: 0.5rem 0;
}

.date-divider span {
    background: var(--proprio-bg-card);
    padding: 0.3rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--proprio-text-secondary);
    border: 1px solid var(--proprio-border-light);
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
    border: 2px solid var(--proprio-border-light);
}

.message-bubble {
    background: var(--proprio-bg-card);
    padding: 1rem;
    border-radius: var(--radius-lg);
    position: relative;
    border: 1px solid var(--proprio-border-light);
    box-shadow: var(--proprio-shadow-sm);
}

.message-sent .message-bubble {
    background: var(--proprio-primary);
    color: white;
    border-color: var(--proprio-primary);
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
    color: var(--proprio-text-primary);
}

.message-sent .message-sender,
.message-sent .message-time,
.message-sent .message-status {
    color: rgba(255, 255, 255, 0.9);
}

.message-time {
    color: var(--proprio-text-tertiary);
}

.message-status {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
}

.message-status.read {
    color: var(--proprio-primary);
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
    color: var(--proprio-primary);
    background: var(--proprio-primary-light);
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    display: inline-block;
    margin-top: 0.5rem;
}

.message-sent .message-context {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.message-composer {
    padding: 1.5rem;
    border-top: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-card);
    flex-shrink: 0;
}

.composer-wrapper {
    display: flex;
    gap: 0.75rem;
    background: var(--proprio-bg-primary);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-full);
    padding: 0.5rem;
    transition: all 0.3s;
}

.composer-wrapper:focus-within {
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
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
    color: var(--proprio-text-primary);
}

.send-message-btn {
    padding: 0.5rem 1.5rem;
    background: var(--proprio-primary);
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
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

/* Zone propriétés (30%) */
.conversation-properties {
    flex: 0 0 30%;
    height: 100%;
    min-height: 0;
    display: flex;
    flex-direction: column;
    background: var(--client-bg-secondary);
    overflow: hidden;
}

/* Header sticky */
.properties-sidebar-header {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    padding: 1.2rem 1.5rem;
    font-size: 1rem;
    font-weight: 700;
    color: var(--client-text-primary);
    border-bottom: 2px solid var(--client-border-light);
    background: var(--client-bg-card);
    flex-shrink: 0;
}

.biens-count {
    margin-left: auto;
    background: var(--client-primary);
    color: white;
    font-size: 0.72rem;
    font-weight: 700;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Body scrollable */
.properties-sidebar-body {
    flex: 1;
    min-height: 0;
    overflow-y: auto;
    padding: 1.2rem;
    scrollbar-width: thin;
    scrollbar-color: var(--client-border-light) transparent;
}

.properties-sidebar-body::-webkit-scrollbar {
    width: 4px;
}

.properties-sidebar-body::-webkit-scrollbar-thumb {
    background: var(--client-border-light);
    border-radius: 4px;
}

.properties-list {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
}

/* Card bien */
.property-card {
    background: var(--client-bg-card);
    border: 1px solid var(--client-border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.25s ease;
}

.property-card:hover {
    box-shadow: var(--client-shadow-md);
    transform: translateY(-2px);
    border-color: var(--client-primary);
}

.property-card.is-locataire {
    border-color: #10b981;
}

/* Bannière colorée */
.property-card-banner {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1rem;
    font-size: 0.78rem;
    font-weight: 600;
}

.banner-maison {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    color: #1d4ed8;
}

.banner-appart {
    background: linear-gradient(135deg, #ede9fe, #ddd6fe);
    color: #7c3aed;
}

.banner-icon {
    font-size: 0.95rem;
}

/* Corps de la card */
.property-card-body {
    padding: 0.85rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.property-card-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--client-text-primary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.property-card-address {
    font-size: 0.76rem;
    color: var(--client-text-secondary);
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.property-locataire-text {
    font-size: 0.82rem;
    font-weight: 600;
    color: #10b981;
    margin: 0.25rem 0 0 0;
}

/* Bouton accepter */
.btn-accepter {
    width: 100%;
    padding: 0.55rem;
    margin-top: 0.25rem;
    background: var(--client-primary);
    color: #ffffff !important;
    border: none;
    border-radius: var(--radius-md);
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-accepter:hover {
    filter: brightness(1.1);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.btn-accepter:disabled {
    background: #9ca3af;
    color: #ffffff !important;
    cursor: not-allowed;
    transform: none;
}

/* État vide */
.no-properties {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 3rem 1rem;
    text-align: center;
    color: var(--client-text-secondary);
}

.no-properties-icon {
    font-size: 2.5rem;
    opacity: 0.5;
}

.no-properties p {
    font-size: 0.85rem;
    margin: 0;
    line-height: 1.5;
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
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    margin: 0 0 0.5rem 0;
}

.empty-conversations p,
.empty-conversation p {
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
    border-radius: var(--radius-full);
    font-weight: 600;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
    gap: 0.75rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .messagerie-container {
        grid-template-columns: 300px 1fr;
        height: 600px; /* Ajustement pour tablette */
    }

    .conversation-split {
        flex-direction: column;
    }

    .conversation-messages {
        flex: none;
        height: 60%;
        border-right: none;
        border-bottom: 1px solid var(--proprio-border-light);
    }

    .conversation-properties {
        flex: none;
        height: 40%;
    }
}

@media (max-width: 768px) {
    .proprio-messagerie-page {
        padding: 1rem;
    }

    .messagerie-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .messagerie-container {
        grid-template-columns: 1fr;
        height: 500px; /* Ajustement pour mobile */
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

    .property-item {
        flex-direction: column;
    }

    .property-photo {
        width: 100%;
        height: 100px;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .messagerie-container,
[data-proprietaire-theme="dark"] .conversations-sidebar,
[data-proprietaire-theme="dark"] .sidebar-header,
[data-proprietaire-theme="dark"] .conversation-main,
[data-proprietaire-theme="dark"] .conversation-header,
[data-proprietaire-theme="dark"] .message-composer,
[data-proprietaire-theme="dark"] .message-bubble,
[data-proprietaire-theme="dark"] .property-item {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .conversations-sidebar {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .conversation-item {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .conversation-item:hover,
[data-proprietaire-theme="dark"] .conversation-item.active {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .search-box input {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .filter-tab {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .messages-area {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .date-divider span {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .message-sent .message-bubble {
    background: var(--proprio-dark-accent);
    border-color: #3b82f6;
}

[data-proprietaire-theme="dark"] .composer-wrapper {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .composer-wrapper textarea {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .conversation-properties {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .property-type {
    background: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .property-item.accepted {
    background: rgba(16, 185, 129, 0.1);
    border-color: #10b981;
}

[data-proprietaire-theme="dark"] .badge-success {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

/* Nouvelles classes dark - section biens */
[data-proprietaire-theme="dark"] .properties-sidebar-header {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .properties-sidebar-body {
    scrollbar-color: var(--proprio-dark-border) transparent;
}

[data-proprietaire-theme="dark"] .property-card {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .property-card:hover {
    border-color: var(--proprio-dark-accent);
}

[data-proprietaire-theme="dark"] .property-card.is-locataire {
    border-color: #10b981;
}

[data-proprietaire-theme="dark"] .banner-maison {
    background: rgba(59, 130, 246, 0.2);
    color: #93c5fd;
}

[data-proprietaire-theme="dark"] .banner-appart {
    background: rgba(139, 92, 246, 0.2);
    color: #c4b5fd;
}

[data-proprietaire-theme="dark"] .property-card-name {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .property-card-address {
    color: var(--proprio-dark-text);
    opacity: 0.7;
}

[data-proprietaire-theme="dark"] .btn-accepter {
    background: var(--proprio-dark-accent);
    color: white;
}

[data-proprietaire-theme="dark"] .btn-accepter:hover {
    filter: brightness(1.2);
}

[data-proprietaire-theme="dark"] .no-properties {
    color: var(--proprio-dark-text);
    opacity: 0.6;
}
</style>

<script>
// Auto-resize textarea
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

// Récupérer l'ID de conversation depuis les données PHP
const conversationActive = '{{ $conversationActive ?? '' }}';

// Charger une conversation
function loadConversation(interlocuteurId) {
    fetch(`/proprietaire/messagerie/conversation/${interlocuteurId}`)
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

function renderConversation(data) {
    const interlocuteur = data.interlocuteur;
    const biens = data.biens;

    let biensHtml = '';
    if (biens && biens.length > 0) {
        biensHtml = '<div class="properties-list">';
        biens.forEach(bien => {
            const estDejaLocataire = false;
            const isMaison = bien.type === 'maison';
            const bannerClass = isMaison ? 'banner-maison' : 'banner-appart';
            const bannerIcon = isMaison ? '🏠' : '🏢';
            const bannerLabel = isMaison ? 'Maison' : 'Appartement';

            biensHtml += `
                <div class="property-card ${estDejaLocataire ? 'is-locataire' : ''}"
                     data-bien-type="${bien.type}"
                     data-bien-id="${bien.id}"
                     data-client-id="${interlocuteur.id}">
                    <div class="property-card-banner ${bannerClass}">
                        <span class="banner-icon">${bannerIcon}</span>
                        <span class="banner-label">${bannerLabel}</span>
                    </div>
                    <div class="property-card-body">
                        <h4 class="property-card-name">${escapeHtml(bien.nom)}</h4>
                        <p class="property-card-address">📍 ${escapeHtml(bien.adresse)}</p>
                        ${estDejaLocataire
                            ? `<p class="property-locataire-text">✅ Déjà locataire</p>`
                            : `<button class="btn-accepter"
                                       onclick="accepterLocataire(${interlocuteur.id}, '${bien.type}', ${bien.id}, this)">
                                   Accepter comme locataire
                               </button>`
                        }
                    </div>
                </div>
            `;
        });
        biensHtml += '</div>';
    } else {
        biensHtml = `<div class="no-properties">
            <div class="no-properties-icon">🏚️</div>
            <p>Aucun bien discuté dans cette conversation</p>
        </div>`;
    }

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
        <div class="conversation-split">
            <div class="conversation-messages">
                <div class="conversation-header">
                    <div class="participant-info">
                        <div class="participant-avatar">
                            <img src="${interlocuteur.photo}" alt="Avatar">
                        </div>
                        <div class="participant-details">
                            <h2>${escapeHtml(interlocuteur.nom)}</h2>
                            ${biens && biens.length > 0 ? `
                                <div class="property-context">
                                    <span class="property-name">${escapeHtml(biens[0].nom)}</span>
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="current-destinataire" value="${interlocuteur.id}">
                        <input type="hidden" id="current-logement-type" value="${biens && biens.length > 0 ? biens[0].type : ''}">
                        <input type="hidden" id="current-logement-id" value="${biens && biens.length > 0 ? biens[0].id : ''}">
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
            </div>
            <div class="conversation-properties">
                <div class="properties-sidebar-header">
                    <span class="title-icon">🏠</span>
                    <span>Biens discutés</span>
                    ${biens && biens.length > 0 ? `<span class="biens-count">${biens.length}</span>` : ''}
                </div>
                <div class="properties-sidebar-body">
                    ${biensHtml}
                </div>
            </div>
        </div>
    `;

    document.getElementById('conversation-active').innerHTML = html;
    scrollToBottom();
}

function scrollToBottom() {
    const messagesArea = document.getElementById('messages-container');
    if (messagesArea) {
        messagesArea.scrollTop = messagesArea.scrollHeight;
    }
}

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

    fetch('/proprietaire/messages/envoyer', {
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

function updateConversationList(interlocuteurId, dernierMessage) {
    const conversationItem = document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`);

    if (conversationItem) {
        const preview = conversationItem.querySelector('.preview-text');
        if (preview) preview.textContent = dernierMessage.substring(0, 60) + (dernierMessage.length > 60 ? '...' : '');

        const time = conversationItem.querySelector('.conversation-time');
        if (time) time.textContent = 'À l\'instant';

        const list = document.getElementById('conversations-list');
        list.prepend(conversationItem);
    }
}

function markAsRead(expediteurId) {
    fetch('/proprietaire/messages/marquer-lu', {
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

function accepterLocataire(clientId, typeBien, bienId, button) {
    if (!confirm('Voulez-vous accepter ce client comme locataire pour ce bien ?')) return;

    const originalText = button.innerHTML;
    button.innerHTML = '<span class="btn-icon">⏳</span>...';
    button.disabled = true;

    fetch('/proprietaire/location/accepter', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            client_id: clientId,
            bien_type: typeBien,
            bien_id: bienId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const propertyCard = button.closest('.property-card');
            const locataireText = document.createElement('p');
            locataireText.className = 'property-locataire-text';
            locataireText.textContent = '✅ Déjà locataire';
            button.replaceWith(locataireText);
            propertyCard.classList.add('is-locataire');
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'acceptation', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        showNotification('Erreur de connexion', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function deleteConversation(interlocuteurId) {
    if (!confirm('Supprimer cette conversation ?')) return;

    fetch(`/proprietaire/messagerie/conversation/${interlocuteurId}`, {
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
                        <p>Vous n'avez pas encore échangé avec des locataires ou clients.</p>
                        <a href="{{ route('proprietaire.biens') }}" class="btn-primary">Voir mes biens</a>
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

document.getElementById('search-conversation')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.conversation-item').forEach(item => {
        const name = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const preview = item.querySelector('.preview-text')?.textContent.toLowerCase() || '';
        item.style.display = name.includes(searchTerm) || preview.includes(searchTerm) ? 'flex' : 'none';
    });
});

function getDisplayDate(dateStr) {
    const date = new Date(dateStr.split('/').reverse().join('-'));
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) return 'Aujourd\'hui';
    if (date.toDateString() === yesterday.toDateString()) return 'Hier';
    return dateStr;
}

function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function showNotification(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `proprio-toast proprio-toast-${type}`;
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

document.addEventListener('DOMContentLoaded', function() {
    if (conversationActive) {
        setTimeout(() => {
            loadConversation(conversationActive);
            updateActiveConversation(conversationActive);
        }, 300);
    }

    scrollToBottom();

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
});

document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', function() {
        loadConversation(this.dataset.conversationId);
    });
});
</script>
@endsection
