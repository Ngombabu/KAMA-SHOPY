<?php
session_start();
include 'db2.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $stmt = $connecte->prepare("SELECT id, mdp FROM vendeur WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($mdp, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            header('Location: 2session.php');
            exit();
        } else {
            $error_message = "Mot de passe incorrect";
        }
    } else {
        $error_message = "Compte introuvable";
    }

    $stmt->close();
    $connecte->close();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="kama.ico" type="image/x-icon">
    <title>Connexion compte administrateur | KAMA</title>
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #3b82f6;
            --accent-color: #93c5fd;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --text-color: #334155;
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
        }

        header {
            background-color: white;
            padding: 15px 20px;
            box-shadow: var(--box-shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-image {
            height: 40px;
        }

        .home-icon {
            color: var(--primary-color);
            font-size: 24px;
            transition: var(--transition);
        }

        .home-icon:hover {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        .kama_connexion {
            max-width: 500px;
            margin: 50px auto;
            padding: 40px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .kama_connexion h2 {
            text-align: center;
            color: var(--dark-color);
            margin-bottom: 20px;
        }

        .inputConnexion {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
            width: 100%;
        }

        .inputConnexion:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .kama_connexion input[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .kama_connexion input[type="submit"]:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .error-message {
            color: #dc2626;
            background-color: #fee2e2;
            padding: 15px;
            border-radius: var(--border-radius);
            text-align: center;
            margin-bottom: 20px;
        }

        footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1e293b 100%);
            color: white;
            padding: 40px 20px;
            margin-top: auto;
        }

        .footer-social {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 25px;
        }

        .footer-social a {
            color: white;
            font-size: 24px;
            transition: var(--transition);
        }

        .footer-social a:hover {
            color: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-newsletter {
            max-width: 500px;
            margin: 0 auto 30px;
            width: 100%;
        }

        .footer-newsletter input {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            background-color: rgba(255, 255, 255, 0.9);
            transition: var(--transition);
        }

        .footer-newsletter input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(147, 197, 253, 0.5);
        }

        .footer-newsletter button {
            width: 100%;
            padding: 12px;
            background-color: white;
            color: var(--dark-color);
            border: none;
            border-radius: var(--border-radius);
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
        }

        .footer-newsletter button:hover {
            background-color: var(--accent-color);
            color: white;
        }

        .footer-copyright {
            text-align: center;
            color: #e2e8f0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .kama_connexion {
                margin: 30px 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <header id="header">
        <img src="kama2.ico" alt="KAMA SHOPPING Logo" class="logo-image">
          <!-- <marquee direction="right" style="color:red ;border:2px solid green;">Site en développement par Novilink pour un client...</marquee>-->
        <a href='index.php'><i class="fas fa-home home-icon"></i></a>
    </header>
    
    <form id="kama_connexion" class="kama_connexion" method="post">
        <h2>Connexion Administrateur</h2>
        
        <?php if(isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <input class="inputConnexion" type="email" name="email" placeholder="Email" required>
        <input class="inputConnexion" type="password" name="mdp" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
    </form>
    
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
</body>
</html>