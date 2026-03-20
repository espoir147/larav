// home.js - Script principal pour ImmoLoc
document.addEventListener('DOMContentLoaded', function() {
    // ============ VARIABLES GLOBALES ============
    const body = document.body;
    let currentTheme = localStorage.getItem('theme') || 'light';

    // ============ INITIALISATION ============
    initTheme();
    initMenuBurger();
    initPropertyCards();
    initSearchForm();
    initModals();

    // ============ GESTION DU THÈME ============
    function initTheme() {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;

        // Appliquer le thème sauvegardé
        body.setAttribute('data-theme', currentTheme);
        updateThemeIcon();

        // Gérer le clic sur le bouton thème
        themeToggle.addEventListener('click', toggleTheme);
    }

    function toggleTheme() {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        body.setAttribute('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme);
        updateThemeIcon();
    }

    function updateThemeIcon() {
        const themeToggle = document.getElementById('themeToggle');
        if (!themeToggle) return;

        const icon = themeToggle.querySelector('i');
        if (icon) {
            icon.className = currentTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
        }
    }

    // ============ MENU BURGER ÉLÉGANT ============
    function initMenuBurger() {
        const menuBurgerBtn = document.getElementById('menuBurgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuOverlay = document.getElementById('menuOverlay');

        if (!menuBurgerBtn || !mobileMenu) return;

        // Ouvrir menu
        menuBurgerBtn.addEventListener('click', function() {
            mobileMenu.classList.add('active');
            menuOverlay.classList.add('active');
            body.style.overflow = 'hidden';
        });

        // Fermer menu
        function closeMobileMenu() {
            mobileMenu.classList.remove('active');
            menuOverlay.classList.remove('active');
            body.style.overflow = '';
        }

        if (closeMenuBtn) {
            closeMenuBtn.addEventListener('click', closeMobileMenu);
        }

        if (menuOverlay) {
            menuOverlay.addEventListener('click', closeMobileMenu);
        }

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            }
        });
    }

    // ============ CARTES DES BIENS ============
    function initPropertyCards() {
        const propertyCards = document.querySelectorAll('.property-card');

        propertyCards.forEach((card, index) => {
            // Animation d'apparition
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.animation = `fadeIn 0.5s ease ${index * 0.1}s forwards`;

            // Effet hover amélioré
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-10px) scale(1.02)';
                card.style.boxShadow = '0 20px 40px rgba(0,0,0,0.15)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
                card.style.boxShadow = '0 8px 25px rgba(0,0,0,0.08)';
            });

            // Version tactile pour mobile
            card.addEventListener('touchstart', () => {
                card.style.transform = 'translateY(-5px) scale(1.01)';
            });

            card.addEventListener('touchend', () => {
                setTimeout(() => {
                    card.style.transform = 'translateY(0) scale(1)';
                }, 300);
            });
        });
    }

    // ============ FORMULAIRE DE RECHERCHE ============
    function initSearchForm() {
        const searchForm = document.querySelector('.search-form');
        if (!searchForm) return;

        // Toggle pour mobile
        if (window.innerWidth < 768) {
            const searchToggle = document.createElement('button');
            searchToggle.innerHTML = '<i class="fas fa-search"></i> Rechercher des biens';
            searchToggle.classList.add('search-toggle');
            searchForm.parentElement.insertBefore(searchToggle, searchForm);

            searchToggle.addEventListener('click', () => {
                searchForm.classList.toggle('active');
                if (searchForm.classList.contains('active')) {
                    searchForm.querySelector('input').focus();
                }
            });

            // Cacher le formulaire par défaut sur mobile
            searchForm.classList.remove('active');
        }

        // Validation
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const prixMin = parseInt(formData.get('prix_min')) || 0;
            const prixMax = parseInt(formData.get('prix_max')) || Infinity;

            // Validation
            if (prixMin > prixMax) {
                showNotification('Le prix minimum ne peut pas être supérieur au prix maximum', 'error');
                return;
            }

            // Animation de chargement
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recherche...';
            submitBtn.disabled = true;

            // Soumettre le formulaire après délai
            setTimeout(() => {
                this.submit();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 1000);
        });
    }

    // ============ MODALS ============
    function initModals() {
        // Gérer les modals Bootstrap
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                // Ajouter des effets lors de l'ouverture
                this.style.backdropFilter = 'blur(5px)';
            });

            modal.addEventListener('hidden.bs.modal', function() {
                // Nettoyer après fermeture
                this.style.backdropFilter = '';
                // Réinitialiser le contenu du modal
                if (this.id === 'propertyModal') {
                    this.querySelector('#propertyInfoContent').innerHTML = '';
                    this.querySelector('#modalGalleryContent').innerHTML = '';
                    this.querySelector('#propertyFullAddress').textContent = 'Chargement...';
                    this.querySelector('#ownerInfoContent').innerHTML = '';
                }
            });
        });

        // Initialiser les carousels dans les modals
        const carousels = document.querySelectorAll('.carousel');
        carousels.forEach(carousel => {
            new bootstrap.Carousel(carousel, {
                interval: 5000,
                wrap: true
            });
        });
    }

    // ============ FONCTIONS UTILITAIRES ============
    function showNotification(message, type = 'success') {
        // Créer la notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        // Ajouter au body
        document.body.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Fermer la notification
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });

        // Auto-fermeture
        setTimeout(() => {
            if (notification.parentNode) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 5000);
    }

    // ============ GESTION DU REDIMENSIONNEMENT ============
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Reinitialiser le menu burger si nécessaire
            initMenuBurger();

            // Ajuster la grille des propriétés
            const propertiesGrid = document.querySelector('.properties-grid');
            if (propertiesGrid && window.innerWidth < 768) {
                propertiesGrid.style.gridTemplateColumns = '1fr';
            }
        }, 250);
    });

    // ============ EFFET PARALLAXE HERO ============
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            heroSection.style.transform = `translate3d(0, ${rate}px, 0)`;
        });
    }

    // ============ LAZY LOADING DES IMAGES ============
    const lazyImages = document.querySelectorAll('.property-image[data-src]');
    if (lazyImages.length > 0) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }
});

// ============ FONCTIONS GLOBALES ============

// Fonction pour ouvrir la galerie photos
function openGallery(photos) {
    if (!photos || photos.length === 0) {
        photos = ['images/default.jpg'];
    }

    const carouselInner = document.getElementById('carousel-inner');
    carouselInner.innerHTML = '';

    photos.forEach((photo, index) => {
        const item = document.createElement('div');
        item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
        item.innerHTML = `
            <img src="${photo.trim()}"
                 class="d-block w-100"
                 alt="Photo du logement"
                 style="max-height: 70vh; object-fit: contain;">
        `;
        carouselInner.appendChild(item);
    });

    // Initialiser le modal
    const galleryModal = new bootstrap.Modal(document.getElementById('galleryModal'));
    galleryModal.show();
}

// Fonction pour contacter un propriétaire
async function envoyerMessageEtRediriger(event, button) {
    event.preventDefault();

    const proprietaireId = button.getAttribute('data-proprietaire-id');
    const logementId = button.getAttribute('data-logement-id');
    const typeLogement = button.getAttribute('data-type-logement');
    const whatsappLink = button.getAttribute('data-whatsapp');

    button.disabled = true;
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';

    try {
        const response = await fetch('/property/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                proprietaire_id: proprietaireId,
                logement_id: logementId,
                type_logement: typeLogement,
                message: "Bonjour, je suis intéressé par votre logement."
            })
        });

        const data = await response.json();

        if (data.success) {
            if (whatsappLink && whatsappLink !== '#') {
                window.open(whatsappLink, '_blank');
            }
            // Notification de succès
            if (typeof showNotification === 'function') {
                showNotification('Message envoyé avec succès', 'success');
            }
        } else {
            showNotification('Erreur: ' + (data.error || 'Échec de l\'envoi'), 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Une erreur est survenue lors de l\'envoi', 'error');
    } finally {
        button.disabled = false;
        button.innerHTML = originalHTML;
    }
}

// NOUVELLE FONCTION : Ouvrir modal de détails avec données réelles
async function openPropertyModal(type, id) {
    try {
        // Montrer un indicateur de chargement
        const modal = document.getElementById('propertyModal');
        const modalBody = modal.querySelector('.modal-body');
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="text-muted">Chargement des détails du bien...</p>
            </div>
        `;

        // Ouvrir le modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        // Appel API pour récupérer les vraies données
        const response = await fetch(`/property/${type}/${id}/details`);
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.error || 'Erreur de chargement');
        }

        // Remplir les onglets avec les vraies données
        updateModalContent(data);

        // Mettre à jour le bouton de contact
        updateContactButton(data);

    } catch (error) {
        console.error('Erreur:', error);
        const modalBody = document.querySelector('#propertyModal .modal-body');
        modalBody.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Impossible de charger les détails du bien : ${error.message}
            </div>
        `;
    }
}

// Fonction pour remplir le contenu du modal
function updateModalContent(data) {
    const property = data.property;
    const owner = data.owner;

    // Onglet Informations
    document.getElementById('propertyInfoContent').innerHTML = `
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3"><i class="fas fa-home text-primary me-2"></i>Caractéristiques</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-bed text-primary me-2"></i> ${property.nombre_chambres} Chambre(s)</li>
                    <li class="mb-2"><i class="fas fa-door-open text-primary me-2"></i> ${property.salon ? 'Avec salon' : 'Sans salon'}</li>
                    <li class="mb-2"><i class="fas fa-building text-primary me-2"></i> ${property.type_logement}</li>
                    <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> ${property.ville}</li>
                    <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> ${property.disponible ? 'Disponible' : 'Non disponible'}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5 class="mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Informations générales</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><strong>Nom :</strong> ${property.nom}</li>
                    <li class="mb-2"><strong>Adresse :</strong> ${property.adresse}</li>
                    <li class="mb-2"><strong>Statut :</strong> ${property.statut_publication === 'approuve' ? '✅ Approuvé' : '⏳ En attente'}</li>
                    <li class="mb-2"><strong>Type :</strong> ${property.type === 'maison' ? '🏠 Maison' : '🏢 Appartement'}</li>
                </ul>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3"><i class="fas fa-align-left text-primary me-2"></i>Description complète</h5>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">${property.description || 'Aucune description disponible.'}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <i class="fas fa-money-bill-wave me-2"></i>
                    <strong>Prix :</strong> ${Number(property.prix).toLocaleString('fr-FR')} FCFA / mois
                </div>
            </div>
        </div>
    `;

    // Onglet Galerie
    const galleryHtml = property.photos && property.photos.length > 0
        ? property.photos.map((photo, index) => `
            <div class="col-6 col-md-4 mb-3">
                <img src="${photo.trim()}"
                     class="img-fluid rounded shadow-sm"
                     alt="Photo ${index + 1}"
                     style="height: 150px; object-fit: cover; cursor: pointer;"
                     onclick="openGallery(${JSON.stringify(property.photos)})">
            </div>
        `).join('')
        : '<div class="col-12 text-center py-5"><i class="fas fa-images fa-3x text-muted mb-3"></i><p>Aucune photo disponible</p></div>';

    document.getElementById('modalGalleryContent').innerHTML = `
        <div class="row">
            ${galleryHtml}
        </div>
    `;

    // Onglet Localisation
    document.getElementById('propertyFullAddress').textContent =
        `${property.adresse}, ${property.ville}`;

    // Onglet Propriétaire
    document.getElementById('ownerInfoContent').innerHTML = owner
        ? `
            <div class="d-flex align-items-center mb-4">
                <div class="owner-avatar me-3">
                    <i class="fas fa-user-circle fa-3x text-primary"></i>
                </div>
                <div>
                    <h5 class="mb-1">${owner.prenom} ${owner.nom}</h5>
                    <p class="text-muted mb-0"><i class="fas fa-user-tie me-1"></i> Propriétaire</p>
                </div>
            </div>
            <div class="owner-contact">
                <h6 class="mb-3"><i class="fas fa-address-card me-2"></i>Coordonnées</h6>
                ${owner.telephone ? `<p class="mb-2"><i class="fas fa-phone me-2"></i> ${owner.telephone}</p>` : ''}
                ${owner.email ? `<p class="mb-2"><i class="fas fa-envelope me-2"></i> ${owner.email}</p>` : ''}
                ${owner.whatsapp_link && owner.whatsapp_link !== '#'
                    ? `<p class="mb-0"><i class="fab fa-whatsapp me-2 text-success"></i> Disponible sur WhatsApp</p>`
                    : ''}
            </div>
            <div class="mt-4">
                <div class="alert alert-success">
                    <i class="fas fa-shield-alt me-2"></i>
                    Votre contact sera envoyé directement au propriétaire
                </div>
            </div>
        `
        : `
            <div class="text-center py-5">
                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                <h5>Propriétaire non disponible</h5>
                <p class="text-muted">Les informations du propriétaire ne sont pas accessibles pour le moment.</p>
            </div>
        `;
}

// Fonction pour mettre à jour le bouton de contact
function updateContactButton(data) {
    const contactBtn = document.getElementById('contactOwnerBtn');
    if (contactBtn && data.owner) {
        contactBtn.onclick = function() {
            // Simuler le clic sur le bouton "Contacter" de la carte
            const property = data.property;
            const owner = data.owner;

            // Créer un bouton virtuel avec les données
            const virtualButton = document.createElement('button');
            virtualButton.setAttribute('data-proprietaire-id', owner.id);
            virtualButton.setAttribute('data-logement-id', property.id);
            virtualButton.setAttribute('data-type-logement', property.type);
            virtualButton.setAttribute('data-whatsapp', owner.whatsapp_link || '#');

            // Appeler la fonction de contact existante
            envoyerMessageEtRediriger({ preventDefault: () => {} }, virtualButton);
        };
    }
}

// ============ STYLES DYNAMIQUES ============
const dynamicStyles = document.createElement('style');
dynamicStyles.textContent = `
    /* Notifications */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        z-index: 9999;
        transform: translateX(120%);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        max-width: 350px;
    }

    [data-theme="dark"] .notification {
        background: #1e293b;
        color: #f1f5f9;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
    }

    .notification.show {
        transform: translateX(0);
    }

    .notification-success {
        border-left: 4px solid #10b981;
    }

    .notification-error {
        border-left: 4px solid #ef4444;
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex: 1;
    }

    .notification-content i {
        font-size: 1.25rem;
    }

    .notification-success .notification-content i {
        color: #10b981;
    }

    .notification-error .notification-content i {
        color: #ef4444;
    }

    .notification-close {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .notification-close:hover {
        color: #334155;
        background: #e2e8f0;
    }

    [data-theme="dark"] .notification-close:hover {
        color: #cbd5e1;
        background: #475569;
    }

    /* Search Toggle Mobile */
    .search-toggle {
        display: none;
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        margin-bottom: 1rem;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    [data-theme="dark"] .search-toggle {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
    }

    .search-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .search-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .search-form {
            display: none;
        }

        .search-form.active {
            display: block;
            animation: slideInDown 0.3s ease;
        }
    }

    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Loading spinner */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Owner avatar */
    .owner-avatar {
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    [data-theme="dark"] .owner-avatar {
        background: #334155;
    }

    /* Lazy loading images */
    .property-image {
        transition: opacity 0.3s ease;
    }

    .property-image:not(.loaded) {
        opacity: 0;
    }

    .property-image.loaded {
        opacity: 1;
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    [data-theme="dark"] ::-webkit-scrollbar-track {
        background: #1e293b;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 9999px;
    }

    [data-theme="dark"] ::-webkit-scrollbar-thumb {
        background: #475569;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    [data-theme="dark"] ::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Tooltips */
    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 0.25rem 0.5rem;
        background: #1e293b;
        color: white;
        font-size: 0.75rem;
        border-radius: 6px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        z-index: 1000;
        pointer-events: none;
    }

    [data-tooltip]:hover::before {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(-5px);
    }
`;

document.head.appendChild(dynamicStyles);
