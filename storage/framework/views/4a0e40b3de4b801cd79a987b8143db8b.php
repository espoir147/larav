<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte rejeté - ImmoLoc</title>
    <style>
        /* Styles communs */
        body {
            background-color: rgb(164, 203, 248);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            transition: background-color 0.3s ease;
        }

        .status-container {
            background: white;
            padding: 3rem;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
            border-top: 5px solid #6B7280;
            transition: all 0.3s ease;
        }

        .status-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        h1 {
            color: #6B7280;
            margin-bottom: 1rem;
            font-size: 2rem;
            transition: color 0.3s ease;
        }

        p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .btn {
            display: inline-block;
            padding: 0.9rem 2.5rem;
            background-color: #6B7280;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #4B5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .btn-secondary {
            display: inline-block;
            padding: 0.9rem 2.5rem;
            background-color: transparent;
            color: #6B7280;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 2px solid #6B7280;
            cursor: pointer;
            margin-top: 1rem;
            margin-left: 1rem;
        }

        .btn-secondary:hover {
            background-color: #6B7280;
            color: white;
        }

        .message {
            background-color: #F3F4F6;
            border-left: 4px solid #6B7280;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: left;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        /* Thème sombre */
        [data-theme="dark"] body {
            background-color: #0f172a;
        }

        [data-theme="dark"] .status-container {
            background-color: #1e293b;
            border-top: 5px solid #9ca3af;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] h1 {
            color: #9ca3af;
        }

        [data-theme="dark"] p {
            color: #cbd5e1;
        }

        [data-theme="dark"] .message {
            background-color: #374151;
            border-left-color: #9ca3af;
            color: #d1d5db;
        }

        [data-theme="dark"] .btn {
            background-color: #6B7280;
        }

        [data-theme="dark"] .btn:hover {
            background-color: #4B5563;
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        [data-theme="dark"] .btn-secondary {
            color: #9ca3af;
            border-color: #9ca3af;
        }

        [data-theme="dark"] .btn-secondary:hover {
            background-color: #9ca3af;
            color: #1e293b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .status-container {
                padding: 2rem;
                margin: 1rem;
            }

            .status-icon {
                font-size: 4rem;
            }

            h1 {
                font-size: 1.7rem;
            }

            p {
                font-size: 1rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-secondary {
                margin-left: 0;
            }
        }

        @media (max-width: 480px) {
            .status-container {
                padding: 1.5rem;
            }

            .status-icon {
                font-size: 3.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="status-container">
        <div class="status-icon">❌</div>
        <h1>Compte rejeté</h1>

        <?php if(session('message')): ?>
            <div class="message">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

        <p>Votre demande d'inscription a été rejetée par notre équipe d'administration.</p>
        <p>Cette décision peut être due à des informations incomplètes ou incorrectes, ou parce que vous ne remplissez pas les critères requis pour utiliser notre plateforme.</p>

        <div class="action-buttons">
            <a href="<?php echo e(route('login')); ?>" class="btn">Retour à la connexion</a>
            <a href="<?php echo e(route('register')); ?>" class="btn-secondary">Nouvelle inscription</a>
        </div>
    </div>

    <script>
        // Gestion du thème (identique à login.blade.php)
        document.addEventListener('DOMContentLoaded', () => {
            // Création du conteneur du bouton
            const themeContainer = document.createElement('div');
            themeContainer.className = 'theme-container';
            themeContainer.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1000;
                display: flex;
                gap: 10px;
                align-items: center;
            `;

            // Création du bouton
            const themeToggle = document.createElement('button');
            themeToggle.className = 'theme-toggle';
            themeToggle.innerHTML = '🌙';
            themeToggle.setAttribute('aria-label', 'Changer le thème');
            themeToggle.style.cssText = `
                background: rgba(255, 255, 255, 0.2);
                border: none;
                border-radius: 50%;
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                backdrop-filter: blur(5px);
            `;

            // Ajout au DOM
            themeContainer.appendChild(themeToggle);
            document.body.appendChild(themeContainer);

            // Gestion du thème
            let currentTheme = localStorage.getItem('theme') || 'light';

            const applyTheme = () => {
                document.body.setAttribute('data-theme', currentTheme);
                localStorage.setItem('theme', currentTheme);

                // Animation et icône
                themeToggle.innerHTML = currentTheme === 'light' ? '🌙' : '☀️';
                themeToggle.style.backgroundColor = currentTheme === 'light'
                    ? 'rgba(255, 255, 255, 0.2)'
                    : 'rgba(0, 0, 0, 0.2)';
            };

            // Écouteur d'événement
            themeToggle.addEventListener('click', () => {
                currentTheme = currentTheme === 'light' ? 'dark' : 'light';
                themeToggle.style.transform = 'scale(0.9)';
                setTimeout(() => {
                    themeToggle.style.transform = 'scale(1)';
                    applyTheme();
                }, 200);
            });

            // Application initiale
            applyTheme();
        });
    </script>
</body>
</html>
<?php /**PATH /home/espoir/larav/resources/views/auth/rejete.blade.php ENDPATH**/ ?>