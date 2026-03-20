<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte bloqué - ImmoLoc</title>
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
            border-top: 5px solid #DC2626;
            transition: all 0.3s ease;
        }

        .status-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        h1 {
            color: #DC2626;
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
            background-color: #DC2626;
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
            background-color: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }

        .message {
            background-color: #FEE2E2;
            border-left: 4px solid #DC2626;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: left;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .contact-info {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: left;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Thème sombre */
        [data-theme="dark"] body {
            background-color: #0f172a;
        }

        [data-theme="dark"] .status-container {
            background-color: #1e293b;
            border-top: 5px solid #ef4444;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        [data-theme="dark"] h1 {
            color: #ef4444;
        }

        [data-theme="dark"] p {
            color: #cbd5e1;
        }

        [data-theme="dark"] .message {
            background-color: #7f1d1d;
            border-left-color: #ef4444;
            color: #fecaca;
        }

        [data-theme="dark"] .contact-info {
            background-color: #78350f;
            border-left-color: #f59e0b;
            color: #fde68a;
        }

        [data-theme="dark"] .btn {
            background-color: #ef4444;
        }

        [data-theme="dark"] .btn:hover {
            background-color: #dc2626;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
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
        <div class="status-icon">🚫</div>
        <h1>Compte bloqué</h1>

        <?php if(session('message')): ?>
            <div class="message">
                <?php echo e(session('message')); ?>

            </div>
        <?php endif; ?>

        <p>Votre compte a été temporairement bloqué par l'administrateur.</p>
        <p>Ce blocage peut être dû à des activités suspectes, à des violations de nos conditions d'utilisation ou à une demande de votre part.</p>

        <div class="contact-info">
            <strong>Pour débloquer votre compte :</strong>
            <ul style="margin: 0.5rem 0 0 1rem; padding-left: 1rem;">
                <li>Contactez notre service client</li>
                <li>Email : support@immoloc.com</li>
                <li>Téléphone : +33 1 23 45 67 89</li>
            </ul>
        </div>

        <a href="<?php echo e(route('login')); ?>" class="btn">Retour à la connexion</a>
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
<?php /**PATH /home/espoir/larav/resources/views/auth/bloque.blade.php ENDPATH**/ ?>