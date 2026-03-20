<?php $__env->startSection('proprietaire-content'); ?>
<div class="proprio-annonces-page">
    <!-- En-tête -->
    <div class="page-header">
        <h1>
            <span class="header-icon">📢</span>
            Gérer mes annonces
        </h1>
        <p>Publiez de nouvelles annonces de location</p>
    </div>

    <!-- Messages d'erreur généraux -->
    <?php if($errors->any()): ?>
    <div class="alert alert-error">
        <span class="alert-icon">❌</span>
        <div class="alert-content">
            <strong>Erreurs de validation :</strong>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <!-- Messages de succès -->
    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <span class="alert-icon">✅</span>
        <span><?php echo e(session('success')); ?></span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    </div>
    <?php endif; ?>

    <!-- Boutons de sélection -->
    <div class="action-cards">
        <div class="action-card" id="btn-maison">
            <div class="action-icon">🏠</div>
            <div class="action-content">
                <h3>Publier une maison</h3>
                <p>Ajoutez une nouvelle maison à louer</p>
            </div>
            <div class="action-arrow">→</div>
        </div>

        <div class="action-card" id="btn-appartement">
            <div class="action-icon">🏢</div>
            <div class="action-content">
                <h3>Publier un appartement</h3>
                <p>Ajoutez un nouvel appartement à louer</p>
            </div>
            <div class="action-arrow">→</div>
        </div>
    </div>

    <!-- Formulaire Maison -->
    <div id="form-maison" class="form-section" style="display: none;">
        <div class="form-header">
            <h2>
                <span class="header-icon">🏠</span>
                Publier une maison à louer
            </h2>
        </div>

        <form action="<?php echo e(route('proprietaire.maison.store')); ?>" method="POST" enctype="multipart/form-data" class="property-form">
            <?php echo csrf_field(); ?>

            <div class="form-grid">
                <!-- Nom de la maison -->
                <div class="form-group">
                    <label for="nom" class="form-label">
                        <span class="label-icon">🏷️</span>
                        Nom de la maison
                    </label>
                    <input type="text"
                           id="nom"
                           name="nom"
                           class="form-input <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nom')); ?>"
                           placeholder="Ex: Villa Cocody, Maison Marcory..."
                           required>
                    <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="ville" class="form-label">
                        <span class="label-icon">🏙️</span>
                        Ville
                    </label>
                    <input type="text"
                           id="ville"
                           name="ville"
                           class="form-input <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('ville')); ?>"
                           placeholder="Ex: Abidjan, Yamoussoukro..."
                           required>
                    <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Prix mensuel -->
                <div class="form-group">
                    <label for="prix" class="form-label">
                        <span class="label-icon">💰</span>
                        Prix mensuel (FCFA)
                    </label>
                    <input type="number"
                           id="prix"
                           name="prix"
                           class="form-input <?php $__errorArgs = ['prix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('prix')); ?>"
                           min="0"
                           step="1000"
                           placeholder="Ex: 150000"
                           required>
                    <?php $__errorArgs = ['prix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Nombre de chambres -->
                <div class="form-group">
                    <label for="nombre_chambres" class="form-label">
                        <span class="label-icon">🛏️</span>
                        Nombre de chambres
                    </label>
                    <input type="number"
                           id="nombre_chambres"
                           name="nombre_chambres"
                           class="form-input <?php $__errorArgs = ['nombre_chambres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nombre_chambres')); ?>"
                           min="1"
                           placeholder="Ex: 3"
                           required>
                    <?php $__errorArgs = ['nombre_chambres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Salon -->
                <div class="form-group">
                    <label for="salon" class="form-label">
                        <span class="label-icon">🛋️</span>
                        Salon inclus ?
                    </label>
                    <select id="salon"
                            name="salon"
                            class="form-select <?php $__errorArgs = ['salon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="1" <?php echo e(old('salon', '1') == '1' ? 'selected' : ''); ?>>Oui</option>
                        <option value="0" <?php echo e(old('salon') == '0' ? 'selected' : ''); ?>>Non</option>
                    </select>
                    <?php $__errorArgs = ['salon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Type de maison -->
                <div class="form-group">
                    <label for="type_maison" class="form-label">
                        <span class="label-icon">🏠</span>
                        Type de maison
                    </label>
                    <select id="type_maison"
                            name="type"
                            class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="simple" <?php echo e(old('type', 'simple') == 'simple' ? 'selected' : ''); ?>>Simple</option>
                        <option value="sanitaires" <?php echo e(old('type') == 'sanitaires' ? 'selected' : ''); ?>>Avec sanitaires</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Adresse (pleine largeur) -->
                <div class="form-group full-width">
                    <label for="adresse_maison" class="form-label">
                        <span class="label-icon">📍</span>
                        Adresse complète
                    </label>
                    <textarea id="adresse_maison"
                              name="adresse"
                              class="form-input <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="3"
                              placeholder="Ex: Rue des Jardins, Cocody, Angré..."
                              required><?php echo e(old('adresse')); ?></textarea>
                    <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Localisation sur carte -->
                <div class="form-group full-width">
                    <label class="form-label">
                        <span class="label-icon">🗺️</span>
                        Localisation sur la carte
                    </label>
                    <div class="map-container">
                        <div id="map-maison" class="map"></div>
                    </div>
                    <div class="coordinates-grid">
                        <div class="coordinate-field">
                            <label for="latitude_maison">Latitude</label>
                            <input type="text"
                                   id="latitude_maison"
                                   name="latitude"
                                   class="form-input readonly <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('latitude', '6.372300')); ?>"
                                   readonly>
                            <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">❌ <?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="coordinate-field">
                            <label for="longitude_maison">Longitude</label>
                            <input type="text"
                                   id="longitude_maison"
                                   name="longitude"
                                   class="form-input readonly <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('longitude', '2.364700')); ?>"
                                   readonly>
                            <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">❌ <?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <small class="field-hint">
                        <span class="hint-icon">📍</span>
                        Déplacez le marqueur sur la carte pour préciser l'emplacement exact
                    </small>
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label for="description_maison" class="form-label">
                        <span class="label-icon">📝</span>
                        Description
                    </label>
                    <textarea id="description_maison"
                              name="description"
                              class="form-input <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="5"
                              placeholder="Décrivez votre maison en détail (superficie, équipements, environnement...)"
                              required><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Photos -->
                <div class="form-group full-width">
                    <label for="photos_maison" class="form-label">
                        <span class="label-icon">📸</span>
                        Photos
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file"
                               id="photos_maison"
                               name="photos[]"
                               class="file-input <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               multiple
                               accept="image/*"
                               required>
                        <div class="file-input-placeholder">
                            <span class="upload-icon">📤</span>
                            <span>Cliquez pour ajouter des photos</span>
                            <small>ou glissez-déposez</small>
                        </div>
                    </div>
                    <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="field-hint">
                        <span class="hint-icon">ℹ️</span>
                        Formats: JPEG, PNG, JPG, GIF. Taille max: 2MB par image
                    </small>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="cacherFormulaires()">
                    <span class="btn-icon">✕</span>
                    Annuler
                </button>
                <button type="submit" class="btn-primary">
                    <span class="btn-icon">✅</span>
                    Publier la maison
                </button>
            </div>
        </form>
    </div>

    <!-- Formulaire Appartement -->
    <div id="form-appartement" class="form-section" style="display: none;">
        <div class="form-header">
            <h2>
                <span class="header-icon">🏢</span>
                Publier un appartement à louer
            </h2>
        </div>

        <form action="<?php echo e(route('proprietaire.appartement.store')); ?>" method="POST" enctype="multipart/form-data" class="property-form">
            <?php echo csrf_field(); ?>

            <div class="form-grid">
                <!-- Numéro d'appartement -->
                <div class="form-group">
                    <label for="numero_appartement" class="form-label">
                        <span class="label-icon">🔢</span>
                        Numéro ou nom de l'appartement
                    </label>
                    <input type="text"
                           id="numero_appartement"
                           name="numero_appartement"
                           class="form-input <?php $__errorArgs = ['numero_appartement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('numero_appartement')); ?>"
                           placeholder="Ex: Appartement 101, Résidence A..."
                           required>
                    <?php $__errorArgs = ['numero_appartement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Ville -->
                <div class="form-group">
                    <label for="ville_appartement" class="form-label">
                        <span class="label-icon">🏙️</span>
                        Ville
                    </label>
                    <input type="text"
                           id="ville_appartement"
                           name="ville"
                           class="form-input <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('ville')); ?>"
                           placeholder="Ex: Abidjan, Yamoussoukro..."
                           required>
                    <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Prix mensuel -->
                <div class="form-group">
                    <label for="prix_mensuel" class="form-label">
                        <span class="label-icon">💰</span>
                        Prix mensuel (FCFA)
                    </label>
                    <input type="number"
                           id="prix_mensuel"
                           name="prix_mensuel"
                           class="form-input <?php $__errorArgs = ['prix_mensuel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('prix_mensuel')); ?>"
                           min="0"
                           step="1000"
                           placeholder="Ex: 120000"
                           required>
                    <?php $__errorArgs = ['prix_mensuel'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Nombre de chambres -->
                <div class="form-group">
                    <label for="nombre_chambres_appartement" class="form-label">
                        <span class="label-icon">🛏️</span>
                        Nombre de chambres
                    </label>
                    <input type="number"
                           id="nombre_chambres_appartement"
                           name="nombre_chambres"
                           class="form-input <?php $__errorArgs = ['nombre_chambres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('nombre_chambres')); ?>"
                           min="1"
                           placeholder="Ex: 2"
                           required>
                    <?php $__errorArgs = ['nombre_chambres'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Salon -->
                <div class="form-group">
                    <label for="salon_appartement" class="form-label">
                        <span class="label-icon">🛋️</span>
                        Salon inclus ?
                    </label>
                    <select id="salon_appartement"
                            name="salon"
                            class="form-select <?php $__errorArgs = ['salon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="1" <?php echo e(old('salon', '1') == '1' ? 'selected' : ''); ?>>Oui</option>
                        <option value="0" <?php echo e(old('salon') == '0' ? 'selected' : ''); ?>>Non</option>
                    </select>
                    <?php $__errorArgs = ['salon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Type d'appartement -->
                <div class="form-group">
                    <label for="type_appartement" class="form-label">
                        <span class="label-icon">🏢</span>
                        Type d'appartement
                    </label>
                    <select id="type_appartement"
                            name="type"
                            class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            required>
                        <option value="simple" <?php echo e(old('type', 'simple') == 'simple' ? 'selected' : ''); ?>>Simple</option>
                        <option value="sanitaires" <?php echo e(old('type') == 'sanitaires' ? 'selected' : ''); ?>>Avec sanitaires</option>
                    </select>
                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Adresse (pleine largeur) -->
                <div class="form-group full-width">
                    <label for="adresse_appartement" class="form-label">
                        <span class="label-icon">📍</span>
                        Adresse complète
                    </label>
                    <textarea id="adresse_appartement"
                              name="adresse"
                              class="form-input <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="3"
                              placeholder="Ex: Rue des Jardins, Cocody, Angré..."
                              required><?php echo e(old('adresse')); ?></textarea>
                    <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Localisation sur carte -->
                <div class="form-group full-width">
                    <label class="form-label">
                        <span class="label-icon">🗺️</span>
                        Localisation sur la carte
                    </label>
                    <div class="map-container">
                        <div id="map-appartement" class="map"></div>
                    </div>
                    <div class="coordinates-grid">
                        <div class="coordinate-field">
                            <label for="latitude_appartement">Latitude</label>
                            <input type="text"
                                   id="latitude_appartement"
                                   name="latitude"
                                   class="form-input readonly <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('latitude', '6.372300')); ?>"
                                   readonly>
                            <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">❌ <?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="coordinate-field">
                            <label for="longitude_appartement">Longitude</label>
                            <input type="text"
                                   id="longitude_appartement"
                                   name="longitude"
                                   class="form-input readonly <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('longitude', '2.364700')); ?>"
                                   readonly>
                            <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message">❌ <?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <small class="field-hint">
                        <span class="hint-icon">📍</span>
                        Déplacez le marqueur sur la carte pour préciser l'emplacement exact
                    </small>
                </div>

                <!-- Description -->
                <div class="form-group full-width">
                    <label for="description_appartement" class="form-label">
                        <span class="label-icon">📝</span>
                        Description
                    </label>
                    <textarea id="description_appartement"
                              name="description"
                              class="form-input <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="5"
                              placeholder="Décrivez votre appartement en détail (étage, équipements, environnement...)"
                              required><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Photos -->
                <div class="form-group full-width">
                    <label for="photos_appartement" class="form-label">
                        <span class="label-icon">📸</span>
                        Photos
                    </label>
                    <div class="file-input-wrapper">
                        <input type="file"
                               id="photos_appartement"
                               name="photos[]"
                               class="file-input <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               multiple
                               accept="image/*"
                               required>
                        <div class="file-input-placeholder">
                            <span class="upload-icon">📤</span>
                            <span>Cliquez pour ajouter des photos</span>
                            <small>ou glissez-déposez</small>
                        </div>
                    </div>
                    <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message">❌ <?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="field-hint">
                        <span class="hint-icon">ℹ️</span>
                        Formats: JPEG, PNG, JPG, GIF. Taille max: 2MB par image
                    </small>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="cacherFormulaires()">
                    <span class="btn-icon">✕</span>
                    Annuler
                </button>
                <button type="submit" class="btn-primary">
                    <span class="btn-icon">✅</span>
                    Publier l'appartement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Inclure Leaflet CSS et JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
/* ===== PAGE ANNONCES PROPRIETAIRE AMÉLIORÉE ===== */
.proprio-annonces-page {
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

/* ===== ALERTES ===== */
.alert {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-radius: var(--radius-lg);
    margin-bottom: 2rem;
    position: relative;
    animation: slideIn 0.3s ease;
    border: 1px solid transparent;
}

.alert-success {
    background: var(--proprio-primary-light);
    color: var(--proprio-primary);
    border-color: var(--proprio-primary);
}

.alert-error {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fecaca;
}

.alert-icon {
    font-size: 1.5rem;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

.alert-content ul {
    margin: 0.5rem 0 0 1.5rem;
}

.alert-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: inherit;
    cursor: pointer;
    opacity: 0.5;
    transition: opacity 0.3s;
    padding: 0;
    line-height: 1;
}

.alert-close:hover {
    opacity: 1;
}

/* ===== CARTES D'ACTION ===== */
.action-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.action-card {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: var(--proprio-shadow-sm);
}

.action-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--proprio-shadow-lg);
    border-color: var(--proprio-primary);
}

.action-card.active {
    background: var(--proprio-primary);
    border-color: var(--proprio-primary);
}

.action-card.active .action-icon,
.action-card.active .action-content h3,
.action-card.active .action-content p,
.action-card.active .action-arrow {
    color: white;
}

.action-icon {
    font-size: 3rem;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--proprio-primary-light);
    border-radius: var(--radius-lg);
    color: var(--proprio-primary);
    transition: all 0.3s;
}

.action-card:hover .action-icon {
    transform: scale(1.1);
}

.action-card.active .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.action-content {
    flex: 1;
}

.action-content h3 {
    color: var(--proprio-text-primary);
    font-size: 1.2rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
}

.action-content p {
    color: var(--proprio-text-secondary);
    font-size: 0.85rem;
    margin: 0;
}

.action-arrow {
    font-size: 1.5rem;
    color: var(--proprio-text-tertiary);
    transition: all 0.3s;
}

.action-card:hover .action-arrow {
    transform: translateX(5px);
    color: var(--proprio-primary);
}

.action-card.active .action-arrow {
    color: white;
}

/* ===== SECTION FORMULAIRE ===== */
.form-section {
    background: var(--proprio-bg-card);
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-xl);
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: var(--proprio-shadow-lg);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-header {
    padding: 1.5rem 2rem;
    background: var(--proprio-bg-secondary);
    border-bottom: 1px solid var(--proprio-border-light);
}

.form-header h2 {
    color: var(--proprio-text-primary);
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.property-form {
    padding: 2rem;
}

/* ===== GRILLE FORMULAIRE ===== */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--proprio-text-primary);
    font-size: 0.9rem;
}

.label-icon {
    font-size: 1rem;
}

.form-input,
.form-select {
    padding: 0.9rem 1rem;
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    font-size: 0.95rem;
    transition: all 0.3s;
    background: var(--proprio-bg-primary);
    color: var(--proprio-text-primary);
    width: 100%;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: var(--proprio-primary);
    box-shadow: 0 0 0 3px var(--proprio-primary-light);
}

.form-input.readonly {
    background: var(--proprio-bg-secondary);
    cursor: not-allowed;
}

.form-input.error,
.form-select.error {
    border-color: #dc2626;
}

.form-input.error:focus,
.form-select.error:focus {
    border-color: #dc2626;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

textarea.form-input {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

/* ===== CHAMP FICHIER ===== */
.file-input-wrapper {
    position: relative;
}

.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.file-input-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 2rem;
    background: var(--proprio-bg-secondary);
    border: 2px dashed var(--proprio-border-light);
    border-radius: var(--radius-lg);
    transition: all 0.3s;
    text-align: center;
}

.file-input-wrapper:hover .file-input-placeholder {
    border-color: var(--proprio-primary);
    background: var(--proprio-primary-light);
}

.upload-icon {
    font-size: 2.5rem;
    opacity: 0.5;
}

.file-input-placeholder span {
    color: var(--proprio-text-primary);
    font-weight: 500;
}

.file-input-placeholder small {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
}

/* ===== CARTE ===== */
.map-container {
    border: 1px solid var(--proprio-border-light);
    border-radius: var(--radius-lg);
    overflow: hidden;
    margin-bottom: 1rem;
}

.map {
    height: 300px;
    width: 100%;
}

.coordinates-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 0.5rem;
}

.coordinate-field {
    display: flex;
    flex-direction: column;
}

.coordinate-field label {
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

/* ===== HINTS ET ERREURS ===== */
.field-hint {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--proprio-text-tertiary);
    font-size: 0.8rem;
    margin-top: 0.5rem;
}

.hint-icon {
    font-size: 0.9rem;
}

.error-message {
    color: #dc2626;
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* ===== ACTIONS ===== */
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--proprio-border-light);
}

.btn-primary,
.btn-secondary {
    flex: 1;
    padding: 1rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
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
    box-shadow: var(--proprio-shadow-md);
}

.btn-secondary {
    background: var(--proprio-bg-secondary);
    color: var(--proprio-text-primary);
    border: 1px solid var(--proprio-border-light);
}

.btn-secondary:hover {
    background: var(--proprio-border-light);
    transform: translateY(-2px);
}

.btn-icon {
    font-size: 1rem;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .proprio-annonces-page {
        padding: 1rem;
    }

    .action-cards {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column;
    }

    .coordinates-grid {
        grid-template-columns: 1fr;
    }

    .map {
        height: 250px;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 1.8rem;
    }

    .action-card {
        flex-direction: column;
        text-align: center;
    }

    .action-content {
        text-align: center;
    }
}

/* ===== THÈME SOMBRE ===== */
[data-proprietaire-theme="dark"] .action-card,
[data-proprietaire-theme="dark"] .form-section {
    background: var(--proprio-dark-card);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .action-icon {
    background: var(--proprio-dark-bg);
    color: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .action-card.active {
    background: var(--proprio-primary);
}

[data-proprietaire-theme="dark"] .action-card.active .action-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

[data-proprietaire-theme="dark"] .form-header {
    background: var(--proprio-dark-bg);
    border-bottom-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .form-input,
[data-proprietaire-theme="dark"] .form-select {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .form-input.readonly {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .file-input-placeholder {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
}

[data-proprietaire-theme="dark"] .file-input-wrapper:hover .file-input-placeholder {
    background: var(--proprio-dark-card);
}

[data-proprietaire-theme="dark"] .btn-secondary {
    background: var(--proprio-dark-bg);
    border-color: var(--proprio-dark-border);
    color: var(--proprio-dark-text);
}

[data-proprietaire-theme="dark"] .btn-secondary:hover {
    background: var(--proprio-dark-border);
}
</style>

<script>
// Variables globales pour les cartes et marqueurs
let mapMaison, mapAppartement;
let markerMaison, markerAppartement;

// Gestion de l'affichage des formulaires
document.addEventListener('DOMContentLoaded', function() {
    const btnMaison = document.getElementById('btn-maison');
    const btnAppartement = document.getElementById('btn-appartement');
    const formMaison = document.getElementById('form-maison');
    const formAppartement = document.getElementById('form-appartement');

    // Vérifier s'il y a des erreurs et ouvrir le bon formulaire
    const hasErrors = document.querySelector('.alert-error');
    const maisonHasErrors = document.querySelectorAll('#form-maison .error').length > 0;
    const appartHasErrors = document.querySelectorAll('#form-appartement .error').length > 0;

    if (hasErrors) {
        if (maisonHasErrors) {
            ouvrirFormulaire('maison');
            setTimeout(() => {
                const firstError = document.querySelector('#form-maison .error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }, 300);
        } else if (appartHasErrors) {
            ouvrirFormulaire('appartement');
            setTimeout(() => {
                const firstError = document.querySelector('#form-appartement .error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }, 300);
        }
    }

    // Afficher formulaire maison
    btnMaison.addEventListener('click', function() {
        ouvrirFormulaire('maison');
    });

    // Afficher formulaire appartement
    btnAppartement.addEventListener('click', function() {
        ouvrirFormulaire('appartement');
    });
});

function ouvrirFormulaire(type) {
    const btnMaison = document.getElementById('btn-maison');
    const btnAppartement = document.getElementById('btn-appartement');
    const formMaison = document.getElementById('form-maison');
    const formAppartement = document.getElementById('form-appartement');

    // Cacher tous les formulaires
    formMaison.style.display = 'none';
    formAppartement.style.display = 'none';

    // Réinitialiser les boutons
    btnMaison.classList.remove('active');
    btnAppartement.classList.remove('active');

    if (type === 'maison') {
        formMaison.style.display = 'block';
        btnMaison.classList.add('active');

        setTimeout(() => {
            if (!mapMaison) {
                initMapMaison();
            } else {
                setTimeout(() => mapMaison.invalidateSize(), 100);
            }
        }, 100);
    } else {
        formAppartement.style.display = 'block';
        btnAppartement.classList.add('active');

        setTimeout(() => {
            if (!mapAppartement) {
                initMapAppartement();
            } else {
                setTimeout(() => mapAppartement.invalidateSize(), 100);
            }
        }, 100);
    }
}

// Initialiser la carte pour les maisons
function initMapMaison() {
    const oldLat = parseFloat(document.getElementById('latitude_maison')?.value) || 6.3723;
    const oldLng = parseFloat(document.getElementById('longitude_maison')?.value) || 2.3647;

    mapMaison = L.map('map-maison').setView([oldLat, oldLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapMaison);

    markerMaison = L.marker([oldLat, oldLng], {
        draggable: true
    }).addTo(mapMaison);

    markerMaison.on('dragend', function(e) {
        const pos = markerMaison.getLatLng();
        document.getElementById('latitude_maison').value = pos.lat.toFixed(6);
        document.getElementById('longitude_maison').value = pos.lng.toFixed(6);
    });

    document.getElementById('latitude_maison').value = oldLat.toFixed(6);
    document.getElementById('longitude_maison').value = oldLng.toFixed(6);

    mapMaison.on('click', function(e) {
        markerMaison.setLatLng(e.latlng);
        document.getElementById('latitude_maison').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude_maison').value = e.latlng.lng.toFixed(6);
    });
}

// Initialiser la carte pour les appartements
function initMapAppartement() {
    const oldLat = parseFloat(document.getElementById('latitude_appartement')?.value) || 6.3723;
    const oldLng = parseFloat(document.getElementById('longitude_appartement')?.value) || 2.3647;

    mapAppartement = L.map('map-appartement').setView([oldLat, oldLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mapAppartement);

    markerAppartement = L.marker([oldLat, oldLng], {
        draggable: true
    }).addTo(mapAppartement);

    markerAppartement.on('dragend', function(e) {
        const pos = markerAppartement.getLatLng();
        document.getElementById('latitude_appartement').value = pos.lat.toFixed(6);
        document.getElementById('longitude_appartement').value = pos.lng.toFixed(6);
    });

    document.getElementById('latitude_appartement').value = oldLat.toFixed(6);
    document.getElementById('longitude_appartement').value = oldLng.toFixed(6);

    mapAppartement.on('click', function(e) {
        markerAppartement.setLatLng(e.latlng);
        document.getElementById('latitude_appartement').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude_appartement').value = e.latlng.lng.toFixed(6);
    });
}

// Cacher tous les formulaires
function cacherFormulaires() {
    document.getElementById('form-maison').style.display = 'none';
    document.getElementById('form-appartement').style.display = 'none';

    document.getElementById('btn-maison').classList.remove('active');
    document.getElementById('btn-appartement').classList.remove('active');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.proprietaire', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/espoir/larav/resources/views/proprietaire/annonces.blade.php ENDPATH**/ ?>