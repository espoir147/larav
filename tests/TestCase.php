<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //
}




@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprietaire-messagerie">
    <div class="messagerie-container">
        <!-- En-tête avec titre et compteur non lus -->
        <div class="messagerie-header">
            <div class="header-title-wrapper">
                <h1>Messagerie</h1>
                <p>Échangez avec vos locataires et clients</p>
            </div>
            <div class="header-right">
                <span class="unread-badge-large">
                    <span class="unread-count">{{ $nonLus ?? 0 }}</span> messages non lus
                </span>
            </div>
        </div>

        <!-- Container principal -->
        <div class="messagerie-main">
            <!-- COLONNE GAUCHE : Liste des conversations -->
            <div class="conversations-list">
                <div class="conversations-header">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="search-conversation" placeholder="Rechercher une conversation...">
                    </div>
                    <div class="filter-tabs">
                        <button class="filter-tab active" data-filter="all">Toutes</button>
                        <button class="filter-tab" data-filter="unread">Non lues</button>
                    </div>
                </div>

                <div class="conversations" id="conversations-list">
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
                                <span class="online-indicator" style="display: none;"></span>
                            </div>
                            <div class="conversation-info">
                                <div class="conversation-header">
                                    <h4>{{ $interlocuteur->nom }}</h4>
                                    <span class="conversation-time">{{ $dernierMessage->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="conversation-property">
                                    @if($nbBiens > 1)
                                        {{ $nbBiens }} biens discutés
                                    @elseif($nbBiens == 1)
                                        1 bien discuté
                                    @endif
                                </div>
                                <div class="conversation-preview">
                                    <span class="preview-text">{{ Str::limit($dernierMessage->contenu, 60) }}</span>
                                    @if($nonLusConv > 0)
                                        <span class="unread-badge">{{ $nonLusConv }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="no-conversations">
                            <div class="no-conversations-icon">✉️</div>
                            <h3>Aucune conversation</h3>
                            <p>Vous n'avez pas encore échangé avec des locataires ou clients.</p>
                            <a href="{{ route('proprietaire.biens') }}" class="btn-explore">Voir mes biens</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- COLONNE DROITE : Conversation active -->
            <div class="conversation-active" id="conversation-active">
                @if(!empty($conversations))
                    @php
                        $firstConv = reset($conversations);
                        $interlocuteur = $firstConv['interlocuteur'];
                        $biens = $firstConv['biens'] ?? [];
                    @endphp

                    <div class="conversation-split">
                        <!-- Zone messages (plus large) -->
                        <div class="conversation-messages">
                            <div class="conversation-header-detail">
                                <div class="interlocuteur-info">
                                    <div class="interlocuteur-avatar">
                                        <img src="{{ $interlocuteur->photo_profil ? asset('storage/' . $interlocuteur->photo_profil) : asset('images/default-avatar.png') }}" alt="Avatar">
                                        <span class="online-indicator" style="display: none;"></span>
                                    </div>
                                    <div class="interlocuteur-details">
                                        <h2>{{ $interlocuteur->nom }}</h2>
                                        <div class="interlocuteur-meta">
                                            <span class="meta-label">Client</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="conversation-actions">
                                    <button class="action-btn" title="Marquer comme lu" onclick="markAsRead({{ $interlocuteur->id }})">
                                        <span class="icon">✅</span>
                                    </button>
                                    <button class="action-btn" title="Supprimer la conversation" onclick="deleteConversation({{ $interlocuteur->id }})">
                                        <span class="icon">🗑️</span>
                                    </button>
                                </div>
                            </div>

                            <div class="messages-container" id="messages-container">
                                @foreach($firstConv['messages'] ?? [] as $message)
                                    @php
                                        $bien = $message->maison ?? $message->appartement;
                                        $isMoi = $message->expediteur_id == Auth::id();
                                        $messageDate = $message->created_at->format('d/m/Y');
                                        $previousDate = $loop->iteration > 1 ? $firstConv['messages'][$loop->index - 1]->created_at->format('d/m/Y') : null;
                                    @endphp

                                    @if($previousDate !== $messageDate)
                                        <div class="date-separator">
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

                                    <div class="message {{ $isMoi ? 'sent' : 'received' }}">
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
                                                    <span class="message-status {{ $message->lu ? 'read' : '' }}">{{ $message->lu ? '✓✓' : '✓' }}</span>
                                                @endif
                                            </div>
                                            <div class="message-content">{{ $message->contenu }}</div>
                                            @if($bien)
                                                <div class="message-property">
                                                    Concernant : {{ $bien->nom ?? 'Appartement ' . ($bien->numero_appartement ?? '') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="message-input-area">
                                <form id="send-message-form" onsubmit="sendMessage(event)">
                                    @csrf
                                    <input type="hidden" id="current-destinataire" value="{{ $interlocuteur->id }}">
                                    @php
                                        $premierBien = count($biens) > 0 ? reset($biens) : null;
                                    @endphp
                                    <input type="hidden" id="current-logement-type" value="{{ $premierBien['type'] ?? '' }}">
                                    <input type="hidden" id="current-logement-id" value="{{ $premierBien['id'] ?? '' }}">
                                    <div class="input-wrapper">
                                        <button type="button" class="attach-btn" title="Joindre un fichier" disabled>
                                            <span class="icon">📎</span>
                                        </button>
                                        <textarea
                                            id="message-content"
                                            placeholder="Écrivez votre message..."
                                            rows="1"
                                            oninput="autoResize(this)"
                                        ></textarea>
                                        <button type="submit" class="send-btn" id="send-btn">
                                            <span class="icon">📤</span>
                                            <span>Envoyer</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Zone biens discutés -->
                        <div class="conversation-biens">
                            <div class="biens-sidebar">
                                <h3 class="biens-title">
                                    <span class="title-icon">🏠</span>
                                    Biens discutés
                                </h3>

                                @if(!empty($biens))
                                    <div class="biens-list" id="biens-list-{{ $interlocuteur->id }}">
                                        @foreach($biens as $bien)
                                            @php
                                                $estDejaLocataire = false; // À remplacer par vraie logique
                                            @endphp
                                            <div class="bien-sidebar-item {{ $estDejaLocataire ? 'accepted' : '' }}"
                                                 data-bien-type="{{ $bien['type'] }}"
                                                 data-bien-id="{{ $bien['id'] }}"
                                                 data-client-id="{{ $interlocuteur->id }}">
                                                <div class="bien-sidebar-photo">
                                                    <img src="{{ asset('images/default-property.jpg') }}" alt="{{ $bien['nom'] }}">
                                                </div>
                                                <div class="bien-sidebar-details">
                                                    <h4 class="bien-sidebar-nom">{{ $bien['nom'] }}</h4>
                                                    <div class="bien-sidebar-meta">
                                                        <span class="bien-sidebar-type">{{ ucfirst($bien['type']) }}</span>
                                                        <span class="bien-sidebar-adresse">{{ Str::limit($bien['adresse'], 30) }}</span>
                                                    </div>

                                                    @if($estDejaLocataire)
                                                        <div class="bien-sidebar-status">
                                                            <span class="status-badge success">✅ Locataire</span>
                                                        </div>
                                                    @else
                                                        <div class="bien-sidebar-action">
                                                            <button class="btn-accepter-sidebar" onclick="accepterLocataire({{ $interlocuteur->id }}, '{{ $bien['type'] }}', {{ $bien['id'] }}, this)">
                                                                <span class="btn-icon">✓</span>
                                                                Accepter
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="no-biens-sidebar">
                                        <p>Aucun bien discuté</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="no-conversation-selected">
                        <div class="no-selected-icon">💬</div>
                        <h3>Aucune conversation sélectionnée</h3>
                        <p>Choisissez une conversation dans la liste pour commencer à discuter.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* ========== MESSAGERIE PROPRIETAIRE ========== */
.proprietaire-messagerie {
    padding: 2rem;
    background: #f8fafc;
    min-height: calc(100vh - 80px);
}

.messagerie-container {
    max-width: 1400px;
    margin: 0 auto;
}

/* En-tête - Titre et sous-titre superposés */
.messagerie-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.header-title-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.header-title-wrapper h1 {
    color: #1e3a8a;
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
}

.header-title-wrapper p {
    color: #64748b;
    font-size: 1.1rem;
    margin: 0;
    font-weight: 400;
}

.header-right {
    display: flex;
    align-items: center;
}

.unread-badge-large {
    background: white;
    border: 2px solid #dbeafe;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    color: #1e3a8a;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.unread-count {
    background: #dc2626;
    color: white;
    padding: 0.2rem 0.6rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
}

/* Container principal 2 colonnes */
.messagerie-main {
    display: grid;
    grid-template-columns: 380px 1fr;
    gap: 1.5rem;
    background: white;
    border: 2px solid #dbeafe;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    height: 700px;
}

/* ========== COLONNE GAUCHE - LISTE CONVERSATIONS ========== */
.conversations-list {
    background: white;
    border-right: 2px solid #dbeafe;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
}

.conversations-header {
    padding: 1.5rem;
    border-bottom: 2px solid #dbeafe;
    background: white;
    flex-shrink: 0;
}

.search-box {
    position: relative;
    margin-bottom: 1rem;
    width: 100%;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 1rem;
}

.search-box input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.8rem;
    border: 2px solid #dbeafe;
    border-radius: 30px;
    font-size: 0.95rem;
    transition: all 0.3s;
    background: white;
    box-sizing: border-box;
}

.search-box input:focus {
    outline: none;
    border-color: #1e40af;
    box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
}

.filter-tabs {
    display: flex;
    gap: 0.5rem;
    width: 100%;
}

.filter-tab {
    flex: 1;
    padding: 0.6rem 0.5rem;
    background: white;
    border: 2px solid #dbeafe;
    border-radius: 30px;
    color: #1e3a8a;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
}

.filter-tab:hover {
    background: #dbeafe;
}

.filter-tab.active {
    background: #1e40af;
    color: white;
    border-color: #1e40af;
}

/* Liste des conversations - SCROLLABLE */
.conversations {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.conversation-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 16px;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.conversation-item:hover {
    background: #f0f9ff;
}

.conversation-item.active {
    background: #dbeafe;
    border-left: 4px solid #1e40af;
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
    border: 2px solid #dbeafe;
}

.online-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2px solid white;
    border-radius: 50%;
}

.conversation-info {
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
    color: #1e3a8a;
    font-size: 1rem;
    font-weight: 700;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-time {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
}

.conversation-property {
    color: #0891b2;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.conversation-preview {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-text {
    color: #64748b;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}

.unread-badge {
    background: #1e40af;
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.2rem 0.5rem;
    border-radius: 20px;
    margin-left: 0.5rem;
    flex-shrink: 0;
}

/* État vide conversations */
.no-conversations {
    text-align: center;
    padding: 3rem 1.5rem;
}

.no-conversations-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.no-conversations h3 {
    color: #1e3a8a;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

.no-conversations p {
    color: #64748b;
    margin-bottom: 1.5rem;
}

.btn-explore {
    display: inline-block;
    padding: 0.8rem 1.8rem;
    background: linear-gradient(135deg, #1e40af, #1e3a8a);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.btn-explore:hover {
    background: linear-gradient(135deg, #1e3a8a, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 64, 175, 0.3);
}

/* ========== COLONNE DROITE - CONVERSATION ACTIVE ========== */
.conversation-active {
    height: 100%;
    background: white;
    overflow: hidden;
}

/* Container split vertical */
.conversation-split {
    display: flex;
    height: 100%;
    width: 100%;
}

/* Partie gauche - Messages (70%) */
.conversation-messages {
    flex: 0 0 70%;
    display: flex;
    flex-direction: column;
    height: 100%;
    border-right: 2px solid #dbeafe;
    overflow: hidden;
}

/* Partie droite - Biens (30%) */
.conversation-biens {
    flex: 0 0 30%;
    height: 100%;
    overflow-y: auto;
    background: #f8fafc;
}

/* En-tête conversation */
.conversation-header-detail {
    padding: 1.5rem;
    border-bottom: 2px solid #dbeafe;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
    background: white;
}

.interlocuteur-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.interlocuteur-avatar {
    position: relative;
    width: 60px;
    height: 60px;
}

.interlocuteur-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #dbeafe;
}

.interlocuteur-details h2 {
    color: #1e3a8a;
    font-size: 1.3rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
}

.interlocuteur-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.9rem;
    flex-wrap: wrap;
}

.meta-label {
    color: #64748b;
    font-weight: 500;
}

.conversation-actions {
    display: flex;
    gap: 0.5rem;
    flex-shrink: 0;
}

.action-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #dbeafe;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: #fee2e2;
    border-color: #dc2626;
    color: #dc2626;
    transform: scale(1.1);
}

/* Zone des messages */
.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    background: #f8fafc;
}

.date-separator {
    display: flex;
    justify-content: center;
    margin: 0.5rem 0;
}

.date-separator span {
    background: white;
    padding: 0.4rem 1.2rem;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    border: 1px solid #dbeafe;
}

.message {
    display: flex;
    gap: 1rem;
    max-width: 80%;
}

.message.sent {
    align-self: flex-end;
    flex-direction: row-reverse;
}

.message-avatar {
    width: 40px;
    height: 40px;
    flex-shrink: 0;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.message-bubble {
    background: white;
    padding: 1rem;
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: relative;
    border: 1px solid #dbeafe;
}

.message.sent .message-bubble {
    background: #1e40af;
    color: white;
    border-color: #1e3a8a;
}

.message-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
    flex-wrap: wrap;
}

.message.sent .message-header {
    justify-content: flex-end;
}

.message-sender {
    font-weight: 700;
    color: #1e3a8a;
}

.message.sent .message-sender,
.message.sent .message-time,
.message.sent .message-status {
    color: rgba(255, 255, 255, 0.9);
}

.message-time {
    color: #94a3b8;
}

.message-status {
    color: #94a3b8;
    font-size: 0.8rem;
}

.message-status.read {
    color: #1e40af;
}

.message.sent .message-status.read {
    color: rgba(255, 255, 255, 0.9);
}

.message-content {
    line-height: 1.5;
    word-break: break-word;
}

.message.sent .message-content {
    color: white;
}

.message-property {
    font-size: 0.75rem;
    color: #0891b2;
    background: #f0f9ff;
    padding: 0.3rem 0.8rem;
    border-radius: 20px;
    display: inline-block;
    margin-top: 0.5rem;
}

.message.sent .message-property {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

/* Zone de saisie */
.message-input-area {
    padding: 1.5rem;
    border-top: 2px solid #dbeafe;
    background: white;
    flex-shrink: 0;
}

.input-wrapper {
    display: flex;
    align-items: flex-end;
    gap: 0.5rem;
    background: white;
    border: 2px solid #dbeafe;
    border-radius: 24px;
    padding: 0.5rem;
    transition: all 0.3s;
}

.input-wrapper:focus-within {
    border-color: #1e40af;
    box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
}

.attach-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: none;
    background: white;
    color: #64748b;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.attach-btn:hover:not(:disabled) {
    background: #f1f5f9;
    color: #1e40af;
    transform: scale(1.1);
}

.attach-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.message-input-area textarea {
    flex: 1;
    border: none;
    padding: 0.5rem;
    font-size: 0.95rem;
    resize: none;
    max-height: 120px;
    outline: none;
    font-family: inherit;
}

.send-btn {
    padding: 0.5rem 1.5rem;
    background: #1e40af;
    color: white;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s;
    flex-shrink: 0;
}

.send-btn:hover {
    background: #1e3a8a;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
}

/* ===== SIDEBAR BIENS ===== */
.biens-sidebar {
    padding: 1.5rem;
    height: 100%;
    overflow-y: auto;
}

.biens-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #1e3a8a;
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 1.5rem 0;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #dbeafe;
}

.title-icon {
    font-size: 1.2rem;
}

.biens-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.bien-sidebar-item {
    display: flex;
    gap: 0.75rem;
    background: white;
    border: 2px solid #dbeafe;
    border-radius: 12px;
    padding: 0.75rem;
    transition: all 0.3s;
}

.bien-sidebar-item:hover {
    border-color: #1e40af;
    box-shadow: 0 4px 8px rgba(30, 64, 175, 0.1);
}

.bien-sidebar-item.accepted {
    background: #f0f9ff;
    border-color: #10b981;
}

.bien-sidebar-photo {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid #dbeafe;
}

.bien-sidebar-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.bien-sidebar-details {
    flex: 1;
    min-width: 0;
}

.bien-sidebar-nom {
    color: #1e3a8a;
    font-size: 0.9rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bien-sidebar-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
}

.bien-sidebar-type {
    background: #dbeafe;
    color: #1e3a8a;
    padding: 0.15rem 0.4rem;
    border-radius: 4px;
    font-weight: 600;
    display: inline-block;
    width: fit-content;
}

.bien-sidebar-adresse {
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bien-sidebar-status {
    margin-top: 0.25rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.success {
    background: #d1fae5;
    color: #059669;
}

.btn-accepter-sidebar {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
    padding: 0.3rem 0.75rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.7rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    transition: all 0.3s;
    width: 100%;
    justify-content: center;
}

.btn-accepter-sidebar:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.btn-icon {
    font-size: 0.8rem;
}

.no-biens-sidebar {
    text-align: center;
    padding: 2rem 0;
    color: #64748b;
    font-style: italic;
}

/* Aucune conversation sélectionnée */
.no-conversation-selected {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    padding: 2rem;
}

.no-selected-icon {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    opacity: 0.5;
}

.no-conversation-selected h3 {
    color: #1e3a8a;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.no-conversation-selected p {
    color: #64748b;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
    .messagerie-main {
        grid-template-columns: 320px 1fr;
    }
}

@media (max-width: 768px) {
    .proprietaire-messagerie {
        padding: 1rem;
    }

    .messagerie-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .header-right {
        width: 100%;
    }

    .unread-badge-large {
        width: 100%;
        justify-content: center;
    }

    .messagerie-main {
        grid-template-columns: 1fr;
        height: auto;
        min-height: 600px;
    }

    .conversations-list {
        height: 400px;
        border-right: none;
        border-bottom: 2px solid #dbeafe;
    }

    .conversation-active {
        height: 500px;
    }

    .conversation-split {
        flex-direction: column;
    }

    .conversation-messages {
        flex: none;
        height: 60%;
        border-right: none;
        border-bottom: 2px solid #dbeafe;
    }

    .conversation-biens {
        flex: none;
        height: 40%;
    }

    .message {
        max-width: 90%;
    }

    .interlocuteur-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

@media (max-width: 480px) {
    .header-title-wrapper h1 {
        font-size: 1.8rem;
    }

    .header-title-wrapper p {
        font-size: 1rem;
    }

    .conversation-header-detail {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .interlocuteur-info {
        width: 100%;
    }

    .conversation-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .message {
        max-width: 100%;
    }

    .input-wrapper {
        flex-wrap: wrap;
    }

    .send-btn {
        width: 100%;
        justify-content: center;
    }
}

/* ========== THÈME SOMBRE ========== */
[data-proprietaire-theme="dark"] .proprietaire-messagerie {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .messagerie-main,
[data-proprietaire-theme="dark"] .conversations-list,
[data-proprietaire-theme="dark"] .conversation-active,
[data-proprietaire-theme="dark"] .message-bubble,
[data-proprietaire-theme="dark"] .date-separator span,
[data-proprietaire-theme="dark"] .conversations-header,
[data-proprietaire-theme="dark"] .conversation-header-detail,
[data-proprietaire-theme="dark"] .message-input-area,
[data-proprietaire-theme="dark"] .unread-badge-large,
[data-proprietaire-theme="dark"] .conversation-messages,
[data-proprietaire-theme="dark"] .conversation-biens,
[data-proprietaire-theme="dark"] .bien-sidebar-item {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .conversation-item:hover {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .conversation-item.active {
    background: var(--proprio-dark-bg);
    border-left-color: var(--proprio-dark-accent);
}

[data-proprietaire-theme="dark"] .conversation-header h4,
[data-proprietaire-theme="dark"] .interlocuteur-details h2,
[data-proprietaire-theme="dark"] .no-conversation-selected h3,
[data-proprietaire-theme="dark"] .header-title-wrapper h1,
[data-proprietaire-theme="dark"] .unread-badge-large,
[data-proprietaire-theme="dark"] .biens-title,
[data-proprietaire-theme="dark"] .bien-sidebar-nom {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .preview-text,
[data-proprietaire-theme="dark"] .conversation-time,
[data-proprietaire-theme="dark"] .message-time,
[data-proprietaire-theme="dark"] .no-conversation-selected p,
[data-proprietaire-theme="dark"] .header-title-wrapper p,
[data-proprietaire-theme="dark"] .interlocuteur-meta,
[data-proprietaire-theme="dark"] .bien-sidebar-adresse {
    color: #94a3b8;
}

[data-proprietaire-theme="dark"] .message.sent .message-bubble {
    background: var(--proprio-dark-accent);
    border-color: #2563eb;
}

[data-proprietaire-theme="dark"] .input-wrapper {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .input-wrapper textarea {
    background: var(--proprio-dark-bg);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .attach-btn {
    background: var(--proprio-dark-bg);
    color: #94a3b8;
}

[data-proprietaire-theme="dark"] .search-box input {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .filter-tab {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .messages-container {
    background: var(--proprio-dark-bg);
}

[data-proprietaire-theme="dark"] .bien-sidebar-type {
    background: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .bien-sidebar-item.accepted {
    background: rgba(16, 185, 129, 0.1);
    border-color: #10b981;
}

.proprietaire-messagerie {
    padding: 2.5rem 1.5rem;
    background: #f9fafb;
    min-height: calc(100vh - 80px);
}

.messagerie-container {
    max-width: 1480px;
    margin: 0 auto;
}

/* En-tête */
.messagerie-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.25rem;
}

.header-title-wrapper h1 {
    font-size: 2.25rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
}

.header-title-wrapper p {
    color: #64748b;
    font-size: 1.05rem;
    margin-top: 0.25rem;
}

.unread-badge-large {
    background: white;
    border: 2px solid #e2e8f0;
    padding: 0.85rem 1.75rem;
    border-radius: 9999px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

/* Grille principale */
.messagerie-main {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 1.75rem;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #e2e8f0;
    height: 760px;
}

/* Liste conversations */
.conversations-list {
    background: white;
    border-right: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
}

.conversations-header {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}

.search-box input {
    padding-left: 3.2rem;
    border-radius: 9999px;
    border: 1px solid #cbd5e1;
    transition: all 0.2s ease;
}

.search-box input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}

.filter-tabs {
    gap: 0.6rem;
}

.filter-tab {
    padding: 0.65rem 1rem;
    border-radius: 9999px;
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: white;
    transition: all 0.2s;
}

.filter-tab.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.conversation-item {
    padding: 1.1rem 1.25rem;
    border-radius: 14px;
    margin: 0.35rem 0.5rem;
    transition: all 0.18s ease;
}

.conversation-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.conversation-item.active {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
}

/* Split conversation active */
.conversation-split {
    display: flex;
    height: 100%;
}

.conversation-messages {
    flex: 0 0 62%;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #e2e8f0;
}

.conversation-biens {
    flex: 0 0 38%;
    background: #f8fafc;
    overflow-y: auto;
}

.biens-sidebar {
    padding: 1.5rem 1.25rem;
}

.biens-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.bien-sidebar-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    border-radius: 14px;
    background: white;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
    transition: all 0.2s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

.bien-sidebar-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    border-color: #cbd5e1;
}

.btn-accepter-sidebar {
    background: #10b981;
    color: white;
    border: none;
    padding: 0.65rem 1.2rem;
    border-radius: 9999px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.btn-accepter-sidebar:hover {
    background: #059669;
    transform: translateY(-1px);
}

/* Responsive */
@media (max-width: 1024px) {
    .messagerie-main {
        grid-template-columns: 300px 1fr;
    }
    .conversation-split {
        flex-direction: column;
    }
    .conversation-messages,
    .conversation-biens {
        flex: none;
        height: 50%;
    }
    .conversation-messages {
        border-bottom: 1px solid #e2e8f0;
        border-right: none;
    }
}

@media (max-width: 768px) {
    .messagerie-main {
        grid-template-columns: 1fr;
        height: auto;
        min-height: 80vh;
    }
    .conversations-list {
        border-right: none;
        border-bottom: 1px solid #e2e8f0;
        max-height: 45vh;
    }
}
</style>

<!-- Le script reste identique à ton original -->
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
    document.getElementById('conversation-active').innerHTML = `
        <div class="no-conversation-selected">
            <div class="loading">Chargement de la conversation...</div>
        </div>
    `;

    fetch(`/proprietaire/messagerie/conversation/${interlocuteurId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderConversation(data);

                document.querySelectorAll('.conversation-item').forEach(i => {
                    i.classList.remove('active');
                });
                document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`).classList.add('active');

                const unreadBadge = document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"] .unread-badge`);
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

// Afficher la conversation
function renderConversation(data) {
    const interlocuteur = data.interlocuteur;
    const biens = data.biens;

    let biensHtml = '';
    if (biens && biens.length > 0) {
        biensHtml = '<div class="biens-list">';
        biens.forEach(bien => {
            // Vérifier si déjà locataire (à adapter avec vraie donnée)
            const estDejaLocataire = false;

            biensHtml += `
                <div class="bien-sidebar-item ${estDejaLocataire ? 'accepted' : ''}"
                     data-bien-type="${bien.type}"
                     data-bien-id="${bien.id}"
                     data-client-id="${interlocuteur.id}">
                    <div class="bien-sidebar-photo">
                        <img src="/images/default-property.jpg" alt="${escapeHtml(bien.nom)}">
                    </div>
                    <div class="bien-sidebar-details">
                        <h4 class="bien-sidebar-nom">${escapeHtml(bien.nom)}</h4>
                        <div class="bien-sidebar-meta">
                            <span class="bien-sidebar-type">${bien.type === 'maison' ? 'Maison' : 'Appartement'}</span>
                            <span class="bien-sidebar-adresse">${escapeHtml(bien.adresse)}</span>
                        </div>
                        ${estDejaLocataire
                            ? `<div class="bien-sidebar-status"><span class="status-badge success">✅ Locataire</span></div>`
                            : `<div class="bien-sidebar-action">
                                <button class="btn-accepter-sidebar" onclick="accepterLocataire(${interlocuteur.id}, '${bien.type}', ${bien.id}, this)">
                                    <span class="btn-icon">✓</span> Accepter
                                </button>
                               </div>`
                        }
                    </div>
                </div>
            `;
        });
        biensHtml += '</div>';
    } else {
        biensHtml = '<div class="no-biens-sidebar"><p>Aucun bien discuté</p></div>';
    }

    let messagesHtml = '';
    let lastDate = '';

    data.messages.forEach(message => {
        const messageDate = message.date;
        const displayDate = getDisplayDate(messageDate);

        if (messageDate !== lastDate) {
            messagesHtml += `
                <div class="date-separator">
                    <span>${displayDate}</span>
                </div>
            `;
            lastDate = messageDate;
        }

        if (message.est_moi) {
            messagesHtml += `
                <div class="message sent">
                    <div class="message-bubble">
                        <div class="message-header">
                            <span class="message-time">${message.heure}</span>
                            <span class="message-status ${message.lu ? 'read' : ''}">${message.lu ? '✓✓' : '✓'}</span>
                        </div>
                        <div class="message-content">${escapeHtml(message.contenu)}</div>
                        <div class="message-property">
                            Concernant : ${escapeHtml(message.bien_nom)}
                        </div>
                    </div>
                </div>
            `;
        } else {
            messagesHtml += `
                <div class="message received">
                    <div class="message-avatar">
                        <img src="${interlocuteur.photo}" alt="Avatar">
                    </div>
                    <div class="message-bubble">
                        <div class="message-header">
                            <span class="message-sender">${escapeHtml(interlocuteur.nom)}</span>
                            <span class="message-time">${message.heure}</span>
                        </div>
                        <div class="message-content">${escapeHtml(message.contenu)}</div>
                        <div class="message-property">
                            Concernant : ${escapeHtml(message.bien_nom)}
                        </div>
                    </div>
                </div>
            `;
        }
    });

    const html = `
        <div class="conversation-split">
            <div class="conversation-messages">
                <div class="conversation-header-detail">
                    <div class="interlocuteur-info">
                        <div class="interlocuteur-avatar">
                            <img src="${interlocuteur.photo}" alt="Avatar">
                            <span class="online-indicator" style="display: none;"></span>
                        </div>
                        <div class="interlocuteur-details">
                            <h2>${escapeHtml(interlocuteur.nom)}</h2>
                            <div class="interlocuteur-meta">
                                <span class="meta-label">Client</span>
                            </div>
                        </div>
                    </div>
                    <div class="conversation-actions">
                        <button class="action-btn" title="Marquer comme lu" onclick="markAsRead(${interlocuteur.id})">
                            <span class="icon">✅</span>
                        </button>
                        <button class="action-btn" title="Supprimer la conversation" onclick="deleteConversation(${interlocuteur.id})">
                            <span class="icon">🗑️</span>
                        </button>
                    </div>
                </div>
                <div class="messages-container" id="messages-container">
                    ${messagesHtml}
                </div>
                <div class="message-input-area">
                    <form id="send-message-form" onsubmit="sendMessage(event)">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="current-destinataire" value="${interlocuteur.id}">
                        <input type="hidden" id="current-logement-type" value="${biens && biens.length > 0 ? biens[0].type : ''}">
                        <input type="hidden" id="current-logement-id" value="${biens && biens.length > 0 ? biens[0].id : ''}">
                        <div class="input-wrapper">
                            <button type="button" class="attach-btn" title="Joindre un fichier" disabled>
                                <span class="icon">📎</span>
                            </button>
                            <textarea
                                id="message-content"
                                placeholder="Écrivez votre message..."
                                rows="1"
                                oninput="autoResize(this)"
                            ></textarea>
                            <button type="submit" class="send-btn" id="send-btn">
                                <span class="icon">📤</span>
                                <span>Envoyer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="conversation-biens">
                <div class="biens-sidebar">
                    <h3 class="biens-title">
                        <span class="title-icon">🏠</span>
                        Biens discutés
                    </h3>
                    ${biensHtml}
                </div>
            </div>
        </div>
    `;

    document.getElementById('conversation-active').innerHTML = html;

    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Envoyer un message
function sendMessage(event) {
    event.preventDefault();

    const textarea = document.getElementById('message-content');
    const contenu = textarea.value.trim();
    const destinataireId = document.getElementById('current-destinataire')?.value;
    const logementType = document.getElementById('current-logement-type')?.value;
    const logementId = document.getElementById('current-logement-id')?.value;

    if (!contenu || !destinataireId || !logementType || !logementId) {
        showNotification('Impossible d\'envoyer le message', 'error');
        return;
    }

    const formData = new FormData();
    formData.append('destinataire_id', destinataireId);
    formData.append('contenu', contenu);
    formData.append('logement_type', logementType);
    formData.append('logement_id', logementId);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    const sendBtn = document.getElementById('send-btn');
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<span class="icon">⏳</span><span>Envoi...</span>';

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
            showNotification(data.message || 'Erreur lors de l\'envoi', 'error');
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<span class="icon">📤</span><span>Envoyer</span>';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur de connexion', 'error');
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<span class="icon">📤</span><span>Envoyer</span>';
    });
}

// Ajouter un message à l'UI
function addMessageToUI(message) {
    const messagesContainer = document.getElementById('messages-container');
    const now = new Date();
    const today = now.toLocaleDateString('fr-FR');

    const lastSeparator = messagesContainer.querySelector('.date-separator:last-child');
    const lastSeparatorText = lastSeparator?.querySelector('span')?.textContent;

    if (lastSeparatorText !== 'Aujourd\'hui' && lastSeparatorText !== getDisplayDate(today)) {
        const separator = document.createElement('div');
        separator.className = 'date-separator';
        separator.innerHTML = '<span>Aujourd\'hui</span>';
        messagesContainer.appendChild(separator);
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = 'message sent';
    messageDiv.innerHTML = `
        <div class="message-bubble">
            <div class="message-header">
                <span class="message-time">${message.heure}</span>
                <span class="message-status">✓</span>
            </div>
            <div class="message-content">${escapeHtml(message.contenu)}</div>
            <div class="message-property">
                Concernant : ${escapeHtml(message.bien_nom)}
            </div>
        </div>
    `;

    messagesContainer.appendChild(messageDiv);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Mettre à jour la liste des conversations
function updateConversationList(interlocuteurId, dernierMessage) {
    const conversationItem = document.querySelector(`.conversation-item[data-conversation-id="${interlocuteurId}"]`);

    if (conversationItem) {
        const preview = conversationItem.querySelector('.preview-text');
        if (preview) preview.textContent = dernierMessage.substring(0, 60) + (dernierMessage.length > 60 ? '...' : '');

        const time = conversationItem.querySelector('.conversation-time');
        if (time) time.textContent = 'À l\'instant';

        const conversationsList = document.getElementById('conversations-list');
        conversationsList.prepend(conversationItem);
    }
}

// Marquer comme lu
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
            document.querySelectorAll(`.conversation-item[data-conversation-id="${expediteurId}"] .unread-badge`).forEach(b => b.remove());
            document.querySelectorAll('.message.sent .message-status').forEach(status => {
                status.textContent = '✓✓';
                status.classList.add('read');
            });
            showNotification('Messages marqués comme lus', 'success');
        }
    });
}

// Accepter un client comme locataire
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
            const bienItem = button.closest('.bien-sidebar-item');
            const actionDiv = button.closest('.bien-sidebar-action');
            const statusDiv = document.createElement('div');
            statusDiv.className = 'bien-sidebar-status';
            statusDiv.innerHTML = '<span class="status-badge success">✅ Locataire</span>';
            actionDiv.replaceWith(statusDiv);
            bienItem.classList.add('accepted');
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Erreur lors de l\'acceptation', 'error');
            button.innerHTML = originalText;
            button.disabled = false;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Erreur de connexion', 'error');
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Supprimer une conversation
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
                    <div class="no-conversations">
                        <div class="no-conversations-icon">✉️</div>
                        <h3>Aucune conversation</h3>
                        <p>Vous n'avez pas encore échangé avec des locataires ou clients.</p>
                        <a href="{{ route('proprietaire.biens') }}" class="btn-explore">Voir mes biens</a>
                    </div>
                `;
            }

            document.getElementById('conversation-active').innerHTML = `
                <div class="no-conversation-selected">
                    <div class="no-selected-icon">💬</div>
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
        const conversations = document.querySelectorAll('.conversation-item');

        conversations.forEach(conv => {
            if (filter === 'all') {
                conv.style.display = 'flex';
            } else if (filter === 'unread') {
                const isUnread = conv.dataset.unread === 'true';
                conv.style.display = isUnread ? 'flex' : 'none';
            }
        });
    });
});

// Recherche
document.getElementById('search-conversation')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const conversations = document.querySelectorAll('.conversation-item');

    conversations.forEach(conv => {
        const name = conv.querySelector('h4')?.textContent.toLowerCase() || '';
        const property = conv.querySelector('.conversation-property')?.textContent.toLowerCase() || '';
        const preview = conv.querySelector('.preview-text')?.textContent.toLowerCase() || '';

        if (name.includes(searchTerm) || property.includes(searchTerm) || preview.includes(searchTerm)) {
            conv.style.display = 'flex';
        } else {
            conv.style.display = 'none';
        }
    });
});

// Obtenir le libellé de date
function getDisplayDate(dateStr) {
    const date = new Date(dateStr.split('/').reverse().join('-'));
    const today = new Date();
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) {
        return 'Aujourd\'hui';
    } else if (date.toDateString() === yesterday.toDateString()) {
        return 'Hier';
    } else {
        return dateStr;
    }
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
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-icon">${type === 'success' ? '✅' : '❌'}</div>
        <div class="notification-content">${message}</div>
        <button class="notification-close" onclick="this.parentElement.remove()">×</button>
    `;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#1e40af' : '#dc2626'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (conversationActive) {
        setTimeout(() => {
            loadConversation(conversationActive);
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.classList.remove('active');
                if (item.dataset.conversationId == conversationActive) {
                    item.classList.add('active');
                }
            });
        }, 300);
    }

    const textarea = document.getElementById('message-content');
    if (textarea) {
        autoResize(textarea);
    }

    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .loading {
            text-align: center;
            padding: 2rem;
            color: #64748b;
            font-style: italic;
        }
    `;
    document.head.appendChild(style);
});

// Changer de conversation
document.querySelectorAll('.conversation-item').forEach(item => {
    item.addEventListener('click', function() {
        const conversationId = this.dataset.conversationId;
        loadConversation(conversationId);
    });
});
</script>
@endsection
















