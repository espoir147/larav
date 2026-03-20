// resources/js/admin.js

// Activation des liens du menu
document.addEventListener('DOMContentLoaded', function() {
    // Highlight du lien actif
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.admin-nav-link');

    navLinks.forEach(link => {
        if (link.href.includes(currentPath)) {
            link.classList.add('active');
        }
    });

    // Confirmation pour les actions critiques
    const confirmActions = document.querySelectorAll('[data-confirm]');
    confirmActions.forEach(action => {
        action.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Gestion des modals admin
    const openModalButtons = document.querySelectorAll('[data-modal-target]');
    const closeModalButtons = document.querySelectorAll('[data-close-modal]');
    const overlay = document.getElementById('modal-overlay');

    openModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = document.getElementById(button.dataset.modalTarget);
            openModal(modal);
        });
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('.modal');
            closeModal(modal);
        });
    });

    function openModal(modal) {
        if (modal == null) return;
        modal.classList.add('active');
        overlay.classList.add('active');
    }

    function closeModal(modal) {
        if (modal == null) return;
        modal.classList.remove('active');
        overlay.classList.remove('active');
    }

    // Toast notifications
    window.showToast = function(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.textContent = message;

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
    };

    // Gestion des tabs internes
    const tabButtons = document.querySelectorAll('[data-tab-target]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.dataset.tabTarget);

            tabContents.forEach(tab => {
                tab.classList.remove('active');
            });

            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });

            button.classList.add('active');
            target.classList.add('active');
        });
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.admin-alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});

// Fonctions utilitaires admin
window.adminUtils = {
    // Confirmation de suppression
    confirmDelete: function(message = 'Êtes-vous sûr de vouloir supprimer ?') {
        return confirm(message);
    },

    // Toggle de statut
    toggleStatus: function(url, element) {
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Statut modifié avec succès', 'success');
                // Mettre à jour l'UI si nécessaire
            }
        })
        .catch(error => {
            showToast('Erreur lors de la modification', 'error');
        });
    },

    // Export des données
    exportData: function(format = 'csv') {
        // Implémentation de l'export
        console.log(`Export des données en ${format}`);
    }
};
