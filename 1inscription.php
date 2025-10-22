<?php
session_start();
include 'db2.php';

$error_messages = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $postnom = trim($_POST['postnom']);
    $prenom = trim($_POST['prenom']);
    $mdp = $_POST['mdp'];
    $email = trim($_POST['email']);
    $numero = trim($_POST['numero']);

    // Validation des données
    if (empty($nom) || empty($postnom) || empty($prenom) || empty($mdp) || empty($email) || empty($numero)) {
        $error_messages[] = "Tous les champs sont obligatoires";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = "Format d'email invalide";
    }

    if (!preg_match('/^[0-9]{10,15}$/', $numero)) {
        $error_messages[] = "Numéro de téléphone invalide";
    }

    if (strlen($mdp) < 8) {
        $error_messages[] = "Le mot de passe doit contenir au moins 8 caractères";
    }

    // Vérification si l'email existe déjà
    $stmt_check = $connecte->prepare("SELECT id FROM vendeur WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $error_messages[] = "Cet email est déjà utilisé";
    }
    $stmt_check->close();

    if (empty($error_messages)) {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $sql = "INSERT INTO vendeur (nom, postnom, prenom, mdp, email, numero) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connecte->prepare($sql);
        $stmt->bind_param("ssssss", $nom, $postnom, $prenom, $mdp_hash, $email, $numero);
        
        if ($stmt->execute()) {
            $_SESSION['inscription_success'] = true;
            header('Location: 3connexion.php');
            exit();
        } else {
            $error_messages[] = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
          <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-WM0VMRQ8D0"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-WM0VMRQ8D0');
</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="kama.ico" type="image/x-icon">
    <title>Inscription | KAMA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #93c5fd;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --text-color: #334155;
            --error-color: #dc2626;
            --success-color: #16a34a;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            line-height: 1.6;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(37, 99, 235, 0.2);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            box-shadow: var(--box-shadow);
            position: relative;
            z-index: 10;
            animation: fadeInDown 0.5s ease-out;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--dark-color);
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 38px;
            cursor: pointer;
            color: var(--dark-color);
            opacity: 0.7;
            transition: var(--transition);
        }

        .password-toggle:hover {
            opacity: 1;
        }

        .btn {
            display: inline-block;
            width: 100%;
            padding: 0.75rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-align: center;
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn i {
            margin-right: 0.5rem;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-color);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            position: relative;
        }

        .login-link a::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary-color);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease-out;
        }

        .login-link a:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.9rem;
            margin-top: 0.25rem;
            display: none;
        }

        .form-control.error {
            border-color: var(--error-color);
        }

        .form-control.success {
            border-color: var(--success-color);
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            animation: fadeIn 0.5s ease-out;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1e293b 100%);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: auto;
        }

        .copyright {
            font-size: 0.9rem;
        }

        .copyright a {
            color: var(--accent-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .copyright a:hover {
            color: white;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-container {
                margin: 1.5rem;
                padding: 1.5rem;
            }

            header {
                padding: 1.5rem 0;
            }

            .logo-container img {
                max-width: 150px;
            }
        }

        @media (max-width: 480px) {
            .form-container {
                margin: 1rem;
                padding: 1.25rem;
            }

            .form-title {
                font-size: 1.5rem;
            }

            .form-control {
                padding: 0.65rem 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <header>
        <div class="logo-container">
            <img src="kama2.ico" alt="Logo KAMA">
        </div>
        <h1>Créer un compte</h1>
    </header>

    <div class="form-container">
        <?php if (!empty($error_messages)): ?>
            <div class="alert alert-danger">
                <ul style="list-style: none;">
                    <?php foreach ($error_messages as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h2 class="form-title"><i class="fas fa-user-plus"></i> Inscription</h2>
        
        <form id="inscriptionForm" method="POST" action="">
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom</label>
                <input type="text" id="nom" name="nom" class="form-control" placeholder="Entrez votre nom" required>
                <small class="error-message">Veuillez entrer votre nom</small>
            </div>

            <div class="form-group">
                <label for="postnom"><i class="fas fa-user"></i> Postnom</label>
                <input type="text" id="postnom" name="postnom" class="form-control" placeholder="Entrez votre postnom" required>
                <small class="error-message">Veuillez entrer votre postnom</small>
            </div>

            <div class="form-group">
                <label for="prenom"><i class="fas fa-user"></i> Prénom</label>
                <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Entrez votre prénom" required>
                <small class="error-message">Veuillez entrer votre prénom</small>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Entrez votre email" required>
                <small class="error-message">Veuillez entrer un email valide</small>
            </div>

            <div class="form-group">
                <label for="numero"><i class="fas fa-phone"></i> Numéro de téléphone</label>
                <input type="tel" id="numero" name="numero" class="form-control" placeholder="Entrez votre numéro" required>
                <small class="error-message">Veuillez entrer un numéro valide</small>
            </div>

            <div class="form-group">
                <label for="mdp"><i class="fas fa-lock"></i> Mot de passe</label>
                <input type="password" id="mdp" name="mdp" class="form-control" placeholder="Créez un mot de passe" required>
                <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                <small class="error-message">Le mot de passe doit contenir au moins 8 caractères</small>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-user-plus"></i> S'inscrire
            </button>

            <div class="login-link">
                <p>Déjà un compte ? <a href="3connexion.php">Connectez-vous</a></p>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="footer-social">
            <a href="https://www.facebook.com/share/1BvhXg1Qki/?mibextid=wwXIfr" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
        </div>
        <div class="footer-copyright">
            &copy; <?php echo date("Y"); ?> Novielink. Tous droits réservés.
        </div>
        <br><br>
    </footer>

    <script>
        // Afficher/masquer le mot de passe
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('mdp');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye-slash');
        });

        // Validation en temps réel
        const form = document.getElementById('inscriptionForm');
        const inputs = form.querySelectorAll('.form-control');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateField(this);
            });
            
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });

        function validateField(field) {
            const errorMessage = field.nextElementSibling.nextElementSibling || field.nextElementSibling;
            
            if (field.value.trim() === '') {
                field.classList.add('error');
                field.classList.remove('success');
                errorMessage.style.display = 'block';
                return false;
            }
            
            // Validation spécifique pour chaque champ
            let isValid = true;
            
            if (field.id === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(field.value);
            } else if (field.id === 'numero') {
                const phoneRegex = /^[0-9]{10,15}$/;
                isValid = phoneRegex.test(field.value);
            } else if (field.id === 'mdp') {
                isValid = field.value.length >= 8;
            }
            
            if (isValid) {
                field.classList.remove('error');
                field.classList.add('success');
                errorMessage.style.display = 'none';
            } else {
                field.classList.add('error');
                field.classList.remove('success');
                errorMessage.style.display = 'block';
            }
            
            return isValid;
        }

        // Soumission du formulaire
        form.addEventListener('submit', function(e) {
            let formIsValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    formIsValid = false;
                }
            });
            
            if (!formIsValid) {
                e.preventDefault();
            } else {
                // Afficher le loader
                document.getElementById('loadingOverlay').classList.add('active');
            }
        });

        // Animation d'entrée
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>