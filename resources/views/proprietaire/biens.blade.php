@extends('layouts.proprietaire')

@section('proprietaire-content')
<div class="proprio-biens-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">🏠</span>
            Mes Biens Immobiliers
        </h1>
        <p>Gérez vos maisons et appartements en location</p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🏠</div>
            <div class="stat-content">
                <h3>Maisons</h3>
                <p class="stat-number">{{ $maisons->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">✅ {{ $maisons->where('disponible', true)->count() }} disponibles</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🏢</div>
            <div class="stat-content">
                <h3>Appartements</h3>
                <p class="stat-number">{{ $appartements->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">✅ {{ $appartements->where('disponible', true)->count() }} disponibles</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-content">
                <h3>En attente</h3>
                <p class="stat-number">{{ $maisons->where('statut_publication', 'en_attente')->count() + $appartements->where('statut_publication', 'en_attente')->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">En validation</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3>Approuvés</h3>
                <p class="stat-number">{{ $maisons->where('statut_publication', 'approuve')->count() + $appartements->where('statut_publication', 'approuve')->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">Visibles sur le site</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">❌</div>
            <div class="stat-content">
                <h3>Rejetés</h3>
                <p class="stat-number">{{ $maisons->where('statut_publication', 'rejete')->count() + $appartements->where('statut_publication', 'rejete')->count() }}</p>
                <div class="stat-details">
                    <span class="detail-item">Non approuvés</span>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📍</div>
            <div class="stat-content">
                <h3>Locations Actives</h3>
                <p class="stat-number">{{ $maisons->sum('locations_actives') + $appartements->sum('locations_actives') }}</p>
                <div class="stat-details">
                    <span class="detail-item">Biens actuellement loués</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bannière d'information -->
    <div class="info-banner">
        <div class="info-banner-content">
            <span class="info-icon">ℹ️</span>
            <div>
                <strong>Processus de validation</strong>
                <p>
                    Les nouveaux biens sont <span class="badge badge-warning">en attente</span> jusqu'à validation par l'administrateur.
                    Une fois <span class="badge badge-success">approuvés</span>, ils apparaissent sur le site.
                    Les biens <span class="badge badge-danger">rejetés</span> peuvent être modifiés et resoumis.
                </p>
            </div>
        </div>
    </div>

    <!-- Section Maisons -->
    <div class="section-card">
        <div class="section-header">
            <h2>
                <span class="section-icon">🏠</span>
                Mes Maisons ({{ $maisons->count() }})
            </h2>
            <a href="{{ route('proprietaire.annonces') }}" class="btn-add">
                <span class="btn-icon">➕</span>
                <span>Ajouter une maison</span>
            </a>
        </div>

        @if($maisons->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🏠</div>
            <h3>Aucune maison enregistrée</h3>
            <p>Vous n'avez pas encore de maison enregistrée.</p>
            <a href="{{ route('proprietaire.annonces') }}" class="btn-primary">
                <span>Publier votre première maison</span>
                <span class="arrow">→</span>
            </a>
        </div>
        @else
        <div class="properties-grid">
            @foreach($maisons as $maison)
            <div class="property-card" data-statut="{{ $maison->statut_publication }}">
                <!-- Photo -->
                <div class="property-photo" onclick="openGallery({{ json_encode($maison->photos_array) }})">
                    <img src="{{ $maison->first_photo }}"
                         alt="{{ $maison->nom }}"
                         onerror="this.src='/images/default-property.jpg'">
                    <div class="photo-hover">
                        <span class="photo-hover-text">👁️ Voir les photos</span>
                    </div>
                </div>

                <!-- Badges -->
                <div class="property-badges">
                    @if($maison->statut_publication === 'en_attente')
                    <span class="badge badge-warning">⏳ En attente</span>
                    @elseif($maison->statut_publication === 'approuve')
                    <span class="badge badge-success">✅ Approuvé</span>
                    @elseif($maison->statut_publication === 'rejete')
                    <span class="badge badge-danger">❌ Rejeté</span>
                    @endif

                    @if($maison->disponible)
                    <span class="badge badge-success">✅ Disponible</span>
                    @else
                    <span class="badge badge-secondary">⛔ Indisponible</span>
                    @endif

                    @if($maison->locations_actives > 0)
                    <span class="badge badge-info">📍 {{ $maison->locations_actives }} location(s)</span>
                    @endif
                </div>

                <!-- Informations -->
                <div class="property-info">
                    <h4>{{ $maison->nom }}</h4>
                    <div class="property-meta">
                        <div class="meta-item">
                            <span class="meta-icon">🏙️</span>
                            <span>{{ $maison->ville }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">💰</span>
                            <span>{{ number_format($maison->prix, 0, ',', ' ') }} FCFA/mois</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🛏️</span>
                            <span>{{ $maison->nombre_chambres }} chambre(s)</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🏠</span>
                            <span>{{ ucfirst($maison->type) }}{{ $maison->salon ? ' avec salon' : '' }}</span>
                        </div>
                    </div>
                    <div class="property-address">
                        <span class="address-icon">📍</span>
                        <span>{{ Str::limit($maison->adresse, 60) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="property-actions">
                    <button type="button" class="btn-edit" onclick="toggleModification('maison', {{ $maison->id }})">
                        <span class="btn-icon">✏️</span>
                        <span>Modifier</span>
                    </button>

                    <form action="{{ route('proprietaire.maison.toggle', $maison->id) }}" method="POST" class="action-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-toggle {{ $maison->disponible ? 'btn-inactive' : 'btn-active' }}">
                            <span class="btn-icon">{{ $maison->disponible ? '⏸️' : '▶️' }}</span>
                            <span>{{ $maison->disponible ? 'Désactiver' : 'Activer' }}</span>
                        </button>
                    </form>

                    <form action="{{ route('proprietaire.maison.delete', $maison->id) }}" method="POST" class="action-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Supprimer définitivement cette maison ?')">
                            <span class="btn-icon">🗑️</span>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>

                <!-- Messages contextuels -->
                @if($maison->statut_publication === 'en_attente')
                <div class="property-message warning">
                    <span class="msg-icon">⏳</span>
                    <span>En attente de validation par l'administrateur.</span>
                </div>
                @endif

                @if($maison->statut_publication === 'rejete')
                <div class="property-message danger">
                    <span class="msg-icon">❌</span>
                    <span>Rejeté. Modifiez et soumettez à nouveau pour validation.</span>
                </div>
                @endif

                <!-- Formulaire de modification -->
                <div id="form-modif-maison-{{ $maison->id }}" class="modification-form">
                    <h4>✏️ Modifier la maison</h4>
                    <form action="{{ route('proprietaire.maison.update', $maison->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Nom</label>
                                <input type="text" name="nom" value="{{ $maison->nom }}" required>
                            </div>

                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="ville" value="{{ $maison->ville }}" required>
                            </div>

                            <div class="form-group">
                                <label>Prix (FCFA)</label>
                                <input type="number" name="prix" value="{{ $maison->prix }}" required>
                            </div>

                            <div class="form-group">
                                <label>Chambres</label>
                                <input type="number" name="nombre_chambres" value="{{ $maison->nombre_chambres }}" required>
                            </div>

                            <div class="form-group">
                                <label>Salon</label>
                                <select name="salon" required>
                                    <option value="1" {{ $maison->salon ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ !$maison->salon ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" required>
                                    <option value="simple" {{ $maison->type == 'simple' ? 'selected' : '' }}>Simple</option>
                                    <option value="sanitaires" {{ $maison->type == 'sanitaires' ? 'selected' : '' }}>Sanitaires</option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <label>Adresse</label>
                                <textarea name="adresse" required>{{ $maison->adresse }}</textarea>
                            </div>

                            <div class="form-group full-width">
                                <label>Description</label>
                                <textarea name="description">{{ $maison->description }}</textarea>
                            </div>

                            <div class="form-group full-width">
                                <label>Photos (laisser vide pour garder les actuelles)</label>
                                <input type="file" name="photos[]" multiple accept="image/*">
                                <small>{{ $maison->photos ? count(explode(',', $maison->photos)) : 0 }} photo(s) actuelle(s)</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="toggleModification('maison', {{ $maison->id }})">
                                Annuler
                            </button>
                            <button type="submit" class="btn-save">
                                💾 Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Section Appartements -->
    <div class="section-card">
        <div class="section-header">
            <h2>
                <span class="section-icon">🏢</span>
                Mes Appartements ({{ $appartements->count() }})
            </h2>
            <a href="{{ route('proprietaire.annonces') }}" class="btn-add">
                <span class="btn-icon">➕</span>
                <span>Ajouter un appartement</span>
            </a>
        </div>

        @if($appartements->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🏢</div>
            <h3>Aucun appartement enregistré</h3>
            <p>Vous n'avez pas encore d'appartement enregistré.</p>
            <a href="{{ route('proprietaire.annonces') }}" class="btn-primary">
                <span>Publier votre premier appartement</span>
                <span class="arrow">→</span>
            </a>
        </div>
        @else
        <div class="properties-grid">
            @foreach($appartements as $appartement)
            <div class="property-card" data-statut="{{ $appartement->statut_publication }}">
                <!-- Photo -->
                <div class="property-photo" onclick="openGallery({{ json_encode($appartement->photos_array) }})">
                    <img src="{{ $appartement->first_photo }}"
                         alt="{{ $appartement->numero_appartement }}"
                         onerror="this.src='/images/default-property.jpg'">
                    <div class="photo-hover">
                        <span class="photo-hover-text">👁️ Voir les photos</span>
                    </div>
                </div>

                <!-- Badges -->
                <div class="property-badges">
                    @if($appartement->statut_publication === 'en_attente')
                    <span class="badge badge-warning">⏳ En attente</span>
                    @elseif($appartement->statut_publication === 'approuve')
                    <span class="badge badge-success">✅ Approuvé</span>
                    @elseif($appartement->statut_publication === 'rejete')
                    <span class="badge badge-danger">❌ Rejeté</span>
                    @endif

                    @if($appartement->disponible)
                    <span class="badge badge-success">✅ Disponible</span>
                    @else
                    <span class="badge badge-secondary">⛔ Indisponible</span>
                    @endif

                    @if($appartement->locations_actives > 0)
                    <span class="badge badge-info">📍 {{ $appartement->locations_actives }} location(s)</span>
                    @endif
                </div>

                <!-- Informations -->
                <div class="property-info">
                    <h4>{{ $appartement->numero_appartement }}</h4>
                    <div class="property-meta">
                        <div class="meta-item">
                            <span class="meta-icon">🏙️</span>
                            <span>{{ $appartement->ville }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">💰</span>
                            <span>{{ number_format($appartement->prix_mensuel, 0, ',', ' ') }} FCFA/mois</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🛏️</span>
                            <span>{{ $appartement->nombre_chambres }} chambre(s)</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🏠</span>
                            <span>{{ ucfirst($appartement->type) }}{{ $appartement->salon ? ' avec salon' : '' }}</span>
                        </div>
                    </div>
                    <div class="property-address">
                        <span class="address-icon">📍</span>
                        <span>{{ Str::limit($appartement->adresse, 60) }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="property-actions">
                    <button type="button" class="btn-edit" onclick="toggleModification('appartement', {{ $appartement->id }})">
                        <span class="btn-icon">✏️</span>
                        <span>Modifier</span>
                    </button>

                    <form action="{{ route('proprietaire.appartement.toggle', $appartement->id) }}" method="POST" class="action-form">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-toggle {{ $appartement->disponible ? 'btn-inactive' : 'btn-active' }}">
                            <span class="btn-icon">{{ $appartement->disponible ? '⏸️' : '▶️' }}</span>
                            <span>{{ $appartement->disponible ? 'Désactiver' : 'Activer' }}</span>
                        </button>
                    </form>

                    <form action="{{ route('proprietaire.appartement.delete', $appartement->id) }}" method="POST" class="action-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete" onclick="return confirm('Supprimer définitivement cet appartement ?')">
                            <span class="btn-icon">🗑️</span>
                            <span>Supprimer</span>
                        </button>
                    </form>
                </div>

                <!-- Messages contextuels -->
                @if($appartement->statut_publication === 'en_attente')
                <div class="property-message warning">
                    <span class="msg-icon">⏳</span>
                    <span>En attente de validation par l'administrateur.</span>
                </div>
                @endif

                @if($appartement->statut_publication === 'rejete')
                <div class="property-message danger">
                    <span class="msg-icon">❌</span>
                    <span>Rejeté. Modifiez et soumettez à nouveau pour validation.</span>
                </div>
                @endif

                <!-- Formulaire de modification -->
                <div id="form-modif-appartement-{{ $appartement->id }}" class="modification-form">
                    <h4>✏️ Modifier l'appartement</h4>
                    <form action="{{ route('proprietaire.appartement.update', $appartement->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Numéro/Nom</label>
                                <input type="text" name="numero_appartement" value="{{ $appartement->numero_appartement }}" required>
                            </div>

                            <div class="form-group">
                                <label>Ville</label>
                                <input type="text" name="ville" value="{{ $appartement->ville }}" required>
                            </div>

                            <div class="form-group">
                                <label>Prix (FCFA)</label>
                                <input type="number" name="prix_mensuel" value="{{ $appartement->prix_mensuel }}" required>
                            </div>

                            <div class="form-group">
                                <label>Chambres</label>
                                <input type="number" name="nombre_chambres" value="{{ $appartement->nombre_chambres }}" required>
                            </div>

                            <div class="form-group">
                                <label>Salon</label>
                                <select name="salon" required>
                                    <option value="1" {{ $appartement->salon ? 'selected' : '' }}>Oui</option>
                                    <option value="0" {{ !$appartement->salon ? 'selected' : '' }}>Non</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Type</label>
                                <select name="type" required>
                                    <option value="simple" {{ $appartement->type == 'simple' ? 'selected' : '' }}>Simple</option>
                                    <option value="sanitaires" {{ $appartement->type == 'sanitaires' ? 'selected' : '' }}>Sanitaires</option>
                                </select>
                            </div>

                            <div class="form-group full-width">
                                <label>Adresse</label>
                                <textarea name="adresse" required>{{ $appartement->adresse }}</textarea>
                            </div>

                            <div class="form-group full-width">
                                <label>Description</label>
                                <textarea name="description">{{ $appartement->descriptin }}</textarea>
                            </div>

                            <div class="form-group full-width">
                                <label>Photos (laisser vide pour garder les actuelles)</label>
                                <input type="file" name="photos[]" multiple accept="image/*">
                                <small>{{ $appartement->photos ? count(explode(',', $appartement->photos)) : 0 }} photo(s) actuelle(s)</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-cancel" onclick="toggleModification('appartement', {{ $appartement->id }})">
                                Annuler
                            </button>
                            <button type="submit" class="btn-save">
                                💾 Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- Modal Galerie -->
<div class="gallery-modal" id="galleryModal">
    <div class="gallery-modal-content">
        <div class="gallery-modal-header">
            <h3>📸 Galerie photos</h3>
            <button class="gallery-modal-close" onclick="closeGallery()">&times;</button>
        </div>
        <div class="gallery-modal-body">
            <div class="gallery-carousel" id="galleryCarousel">
                <div class="gallery-carousel-inner" id="carousel-inner"></div>
                <button class="gallery-carousel-prev" onclick="prevSlide()">❮</button>
                <button class="gallery-carousel-next" onclick="nextSlide()">❯</button>
            </div>
            <div class="gallery-dots" id="gallery-dots"></div>
        </div>
    </div>
</div>

<style>
/* ===== PAGE BIENS PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-biens-page {
    padding: 1.5rem;
    max-width: 1600px;
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
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== BANNIÈRE INFO ===== */
.info-banner {
    background: var(--proprio-primary-light);
    border-left: 4px solid var(--proprio-primary);
    border-radius: var(--radius-lg);
    padding: 1rem;
    margin-bottom: 2rem;
}

.info-banner-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.info-icon {
    font-size: 1.5rem;
}

.info-banner-content strong {
    color: var(--proprio-primary);
    display: block;
    margin-bottom: 0.25rem;
}

.info-banner-content p {
    margin: 0;
    color: var(--proprio-text-secondary);
    font-size: 0.9rem;
}

/* ===== SECTION CARD ===== */
.section-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--proprio-shadow-sm);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
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

.btn-add {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: var(--proprio-primary);
    color: white;
    border-radius: var(--radius-lg);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-add:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
}

/* ===== GRILLE PROPRIÉTÉS ===== */
.properties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 1.5rem;
}

/* Carte propriété */
.property-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
    box-shadow: var(--proprio-shadow-sm);
}

.property-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.property-card[data-statut="en_attente"] {
    border-left: 4px solid #fbbf24;
}

.property-card[data-statut="rejete"] {
    border-left: 4px solid #ef4444;
    opacity: 0.9;
}

/* Photo */
.property-photo {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    cursor: pointer;
}

.property-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s;
}

.property-photo:hover img {
    transform: scale(1.08);
}

.photo-hover {
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

.property-photo:hover .photo-hover {
    opacity: 1;
}

.photo-hover-text {
    color: white;
    font-weight: 600;
    padding: 0.5rem 1rem;
    background: var(--proprio-primary);
    border-radius: var(--radius-full);
    font-size: 0.85rem;
}

/* Badges */
.property-badges {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 1rem;
    border-bottom: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
}

.badge {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    line-height: 1.4;
}

.badge-warning {
    background: #fbbf24;
    color: #92400e;
}

.badge-success {
    background: var(--proprio-success);
    color: white;
}

.badge-danger {
    background: var(--proprio-danger);
    color: white;
}

.badge-info {
    background: var(--proprio-secondary);
    color: white;
}

.badge-secondary {
    background: #9ca3af;
    color: white;
}

/* Informations */
.property-info {
    padding: 1rem;
}

.property-info h4 {
    margin: 0 0 0.75rem 0;
    color: var(--proprio-text-primary);
    font-size: 1.1rem;
    font-weight: 600;
}

.property-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--proprio-text-secondary);
}

.meta-icon {
    font-size: 0.9rem;
    opacity: 0.7;
}

.property-address {
    display: flex;
    align-items: flex-start;
    gap: 0.5rem;
    padding: 0.5rem;
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-md);
    font-size: 0.8rem;
    color: var(--proprio-text-tertiary);
}

.address-icon {
    flex-shrink: 0;
}

/* Actions */
.property-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem;
    border-top: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
}

.action-form {
    flex: 1;
}

.property-actions button {
    width: 100%;
    padding: 0.6rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.8rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.btn-edit {
    background: var(--proprio-primary);
    color: white;
}

.btn-edit:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
}

.btn-toggle {
    background: var(--proprio-success);
    color: white;
}

.btn-inactive {
    background: #ef4444;
}

.btn-inactive:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.btn-active:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-delete {
    background: #6b7280;
    color: white;
}

.btn-delete:hover {
    background: #4b5563;
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 1rem;
}

/* Messages contextuels */
.property-message {
    padding: 0.75rem 1rem;
    margin: 0 1rem 1rem;
    border-radius: var(--radius-md);
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.property-message.warning {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fbbf24;
}

.property-message.danger {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #ef4444;
}

.msg-icon {
    font-size: 1rem;
}

/* Formulaire de modification */
.modification-form {
    display: none;
    padding: 1.5rem;
    border-top: 2px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
}

.modification-form h4 {
    margin: 0 0 1rem 0;
    color: var(--proprio-text-primary);
    font-size: 1rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 600;
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
    color: var(--proprio-text-secondary);
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 0.6rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-md);
    font-size: 0.85rem;
    background: var(--proprio-bg-card);
    color: var(--proprio-text-primary);
}

.form-group textarea {
    min-height: 80px;
    resize: vertical;
}

.form-group small {
    font-size: 0.7rem;
    color: var(--proprio-text-tertiary);
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--proprio-border-light);
}

.btn-cancel, .btn-save {
    flex: 1;
    padding: 0.75rem;
    border: none;
    border-radius: var(--radius-md);
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-cancel {
    background: #9ca3af;
    color: white;
}

.btn-cancel:hover {
    background: #6b7280;
    transform: translateY(-2px);
}

.btn-save {
    background: var(--proprio-primary);
    color: white;
}

.btn-save:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
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
}

.btn-primary:hover {
    background: var(--proprio-primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--proprio-shadow-md);
    gap: 0.75rem;
}

/* ===== MODAL GALERIE ===== */
.gallery-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 9999;
    overflow: auto;
    backdrop-filter: blur(4px);
}

.gallery-modal-content {
    position: relative;
    background: var(--proprio-bg-card);
    margin: 50px auto;
    width: 90%;
    max-width: 900px;
    border-radius: var(--radius-xl);
    box-shadow: var(--proprio-shadow-xl);
    border: 1px solid var(--proprio-border-light);
    animation: modalFadeIn 0.3s;
}

@keyframes modalFadeIn {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
}

.gallery-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--proprio-border-light);
    background: var(--proprio-bg-secondary);
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
}

.gallery-modal-header h3 {
    margin: 0;
    color: var(--proprio-text-primary);
    font-size: 1.2rem;
    font-weight: 600;
}

.gallery-modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    cursor: pointer;
    color: var(--proprio-text-tertiary);
    transition: all 0.3s;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.gallery-modal-close:hover {
    background: var(--proprio-danger);
    color: white;
    transform: rotate(90deg);
}

.gallery-modal-body {
    padding: 1.5rem;
}

.gallery-carousel {
    position: relative;
    width: 100%;
    overflow: hidden;
    border-radius: var(--radius-lg);
}

.gallery-carousel-inner {
    display: flex;
    transition: transform 0.3s ease;
    will-change: transform;
}

.gallery-carousel-inner img {
    min-width: 100%;
    width: 100%;
    height: 460px;
    object-fit: cover;
    object-position: center;
    flex-shrink: 0;
    border-radius: var(--radius-lg);
}

.gallery-carousel-prev,
.gallery-carousel-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(30, 64, 175, 0.7);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s;
    z-index: 10;
}

.gallery-carousel-prev {
    left: 15px;
}

.gallery-carousel-next {
    right: 15px;
}

.gallery-carousel-prev:hover,
.gallery-carousel-next:hover {
    background: var(--proprio-primary);
    transform: translateY(-50%) scale(1.1);
}

.gallery-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    flex-wrap: wrap;
}

.gallery-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: var(--proprio-border-light);
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    padding: 0;
}

.gallery-dot.active {
    background: var(--proprio-primary);
    transform: scale(1.2);
}

.gallery-dot:hover {
    background: var(--proprio-secondary);
}

/* ===== ANIMATIONS ===== */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stat-card,
.section-card {
    animation: fadeIn 0.5s ease-out forwards;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .proprio-biens-page {
        padding: 1rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .btn-add {
        width: 100%;
        justify-content: center;
    }

    .properties-grid {
        grid-template-columns: 1fr;
    }

    .property-actions {
        flex-direction: column;
    }

    .action-form {
        width: 100%;
    }

    .form-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .property-meta {
        grid-template-columns: 1fr;
    }

    .property-badges {
        flex-direction: column;
        align-items: flex-start;
    }

    .badge {
        width: fit-content;
    }

    .gallery-carousel-prev,
    .gallery-carousel-next {
        width: 35px;
        height: 35px;
        font-size: 1.2rem;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .stat-card,
[data-proprietaire-theme="dark"] .section-card,
[data-proprietaire-theme="dark"] .property-card,
[data-proprietaire-theme="dark"] .gallery-modal-content {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .stat-icon {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .property-badges {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .property-info h4 {
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .meta-item {
    color: #94a3b8;
}

[data-proprietaire-theme="dark"] .property-address {
    background: var(--proprio-dark-bg);
    color: #94a3b8;
}

[data-proprietaire-theme="dark"] .property-actions {
    background: var(--proprio-dark-bg);
    border-top-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .badge-secondary {
    background: #4b5563;
}

[data-proprietaire-theme="dark"] .badge-warning {
    background: #fbbf24;
    color: #000;
}

[data-proprietaire-theme="dark"] .modification-form {
    background: var(--proprio-dark-bg);
    border-top-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .form-group input,
[data-proprietaire-theme="dark"] .form-group select,
[data-proprietaire-theme="dark"] .form-group textarea {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .gallery-modal-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .gallery-dot {
    background: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .gallery-dot.active {
    background: var(--proprio-primary);
}
</style>

<script>
// ==================== VARIABLES GALERIE ====================
let currentSlide = 0;
let slides = [];

// ==================== FONCTIONS GALERIE ====================
function openGallery(photos) {
    if (!photos || photos.length === 0) {
        photos = ['/images/default-property.jpg'];
    }

    slides = photos;
    currentSlide = 0;

    const carouselInner = document.getElementById('carousel-inner');
    const dotsContainer = document.getElementById('gallery-dots');

    carouselInner.innerHTML = '';
    dotsContainer.innerHTML = '';

    photos.forEach((photo, index) => {
        const img = document.createElement('img');
        img.src = photo.trim();
        img.alt = 'Photo du bien';
        img.onerror = function() { this.src = '/images/default-property.jpg'; };
        carouselInner.appendChild(img);

        const dot = document.createElement('button');
        dot.className = `gallery-dot ${index === 0 ? 'active' : ''}`;
        dot.setAttribute('onclick', `goToSlide(${index})`);
        dotsContainer.appendChild(dot);
    });

    document.getElementById('galleryModal').style.display = 'block';
    document.body.style.overflow = 'hidden';

    updateCarousel();
}

function closeGallery() {
    document.getElementById('galleryModal').style.display = 'none';
    document.body.style.overflow = '';
}

function nextSlide() {
    currentSlide = currentSlide < slides.length - 1 ? currentSlide + 1 : 0;
    updateCarousel();
}

function prevSlide() {
    currentSlide = currentSlide > 0 ? currentSlide - 1 : slides.length - 1;
    updateCarousel();
}

function goToSlide(index) {
    currentSlide = index;
    updateCarousel();
}

function updateCarousel() {
    const carouselInner = document.getElementById('carousel-inner');
    const dots = document.querySelectorAll('.gallery-dot');

    carouselInner.style.transform = `translateX(-${currentSlide * 100}%)`;

    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

// ==================== GESTION DES FORMULAIRES ====================
let formulaireOuvert = null;

function toggleModification(type, id) {
    const formulaireId = `form-modif-${type}-${id}`;
    const formulaire = document.getElementById(formulaireId);

    if (formulaireOuvert && formulaireOuvert !== formulaire) {
        formulaireOuvert.style.display = 'none';
    }

    if (formulaire.style.display === 'block') {
        formulaire.style.display = 'none';
        formulaireOuvert = null;
    } else {
        formulaire.style.display = 'block';
        formulaireOuvert = formulaire;
        formulaire.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
}

// ==================== FERMETURE MODAL ====================
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGallery();
});

document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) closeGallery();
});

// ==================== INITIALISATION ====================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modification-form').forEach(form => {
        form.style.display = 'none';
    });
});

// Confirmation suppression
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce bien ? Cette action est irréversible.')) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
