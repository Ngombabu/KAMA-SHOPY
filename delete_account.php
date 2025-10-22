<?php
session_start();
include 'db2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: 3connexion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vendeur_id = $_SESSION['user_id'];
    
    // Supprimer le compte vendeur
    $stmt = $connecte->prepare("DELETE FROM vendeur WHERE id = ?");
    $stmt->bind_param("i", $vendeur_id);
    
    if ($stmt->execute()) {
        // Déconnecter l'utilisateur
        session_unset();
        session_destroy();
        
        $_SESSION['success_message'] = "Votre compte a été supprimé avec succès.";
        header("Location: 3connexion.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression du compte.";
        header("Location: administration.php");
        exit();
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
    <title>Supprimer le compte | KAMA</title>
    <style>
         body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        
        h1 {
            margin-bottom: 30px;
            color: #dc3545;
        }
        
        p {
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        
        .buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        button, a {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .confirm {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        
        .confirm:hover {
            background-color: #c82333;
        }
        
        .cancel {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        
        .cancel:hover {
            background-color: #5a6268;
        }/
    </style>
</head>
<body>
    <h1>Supprimer votre compte</h1>
    <p>Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.</p>
    <p>Note: Cette action ne supprimera pas les produits de la base de données.</p>
    
    <div class="buttons">
        <form method="POST" action="">
            <button type="submit" class="confirm">Confirmer la suppression</button>
        </form>
        <a href="administration.php" class="cancel">Annuler</a>
    </div>
</body>
</html>