<?php
session_start();
include 'db2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: 3connexion.php");
    exit();
}

$vendeur_id = $_SESSION['user_id'];

// Traitement du formulaire de modification du mot de passe
if (isset($_POST['action']) && $_POST['action'] === 'modifier_mot_de_passe') {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    // Validation côté serveur du nouveau mot de passe
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';
    if (!preg_match($pattern, $nouveau_mot_de_passe)) {
        $_SESSION['error_message_mdp'] = "Le nouveau mot de passe ne respecte pas les critères de sécurité : au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        header("Location: edit_profile.php");
        exit();
    }

    $stmt = $connecte->prepare("SELECT mdp FROM vendeur WHERE id = ?");
    $stmt->bind_param("i", $vendeur_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($ancien_mot_de_passe, $row['mdp'])) {
        if ($nouveau_mot_de_passe === $confirmer_mot_de_passe) {
            $mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
            $stmt_update = $connecte->prepare("UPDATE vendeur SET mdp = ? WHERE id = ?");
            $stmt_update->bind_param("si", $mot_de_passe_hash, $vendeur_id);
            if ($stmt_update->execute()) {
                $_SESSION['success_message_mdp'] = "Mot de passe mis à jour avec succès !";
            } else {
                $_SESSION['error_message_mdp'] = "Erreur lors de la mise à jour du mot de passe.";
            }
            $stmt_update->close();
        } else {
            $_SESSION['error_message_mdp'] = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
        }
    } else {
        $_SESSION['error_message_mdp'] = "Ancien mot de passe incorrect.";
    }
    $stmt->close();
    header("Location: edit_profile.php");
    exit();
}

// Traitement du formulaire de modification des informations personnelles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];

    // Gestion de l'image
    $imagePath = null;
    $uploadDir = "KAMA_IMAGE/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageName = uniqid('profil_') . '.' . $ext;
        $imagePath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        // Supprimer l'ancienne image si elle existe et n'est pas vide
        $stmt_old = $connecte->prepare("SELECT image FROM vendeur WHERE id=?");
        $stmt_old->bind_param("i", $vendeur_id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        if ($row_old = $result_old->fetch_assoc()) {
            if (!empty($row_old['image']) && file_exists($row_old['image'])) {
                unlink($row_old['image']);
            }
        }
        $stmt_old->close();
    }

    if ($imagePath) {
        $stmt = $connecte->prepare("UPDATE vendeur SET nom=?, postnom=?, prenom=?, email=?, numero=?, image=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nom, $postnom, $prenom, $email, $numero, $imagePath, $vendeur_id);
    } else {
        $stmt = $connecte->prepare("UPDATE vendeur SET nom=?, postnom=?, prenom=?, email=?, numero=? WHERE id=?");
        $stmt->bind_param("sssssi", $nom, $postnom, $prenom, $email, $numero, $vendeur_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profil mis à jour avec succès !";
        header("Location: edit_profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour du profil.";
    }
    $stmt->close();
}

// Récupérer les informations actuelles du vendeur
$stmt = $connecte->prepare("SELECT * FROM vendeur WHERE id = ?");
$stmt->bind_param("i", $vendeur_id);
$stmt->execute();
$result = $stmt->get_result();
$vendeur = $result->fetch_assoc();
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
    <title>Modifier le profil | KAMA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        body {
            background-color: var(--light-color);
            color: var(--text-color);
            min-height: 100vh;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        h1, h2 { text-align: center; margin-bottom: 30px; color: var(--dark-color); }
        h1 { margin-top: 60px; }
        .logo-header { text-align: center; margin-bottom: 20px; }
        .logo-header img { max-width: 200px; height: auto; transition: transform 0.3s ease; }
        .logo-header img:hover { transform: scale(1.05); }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);}
        input[type="text"], input[type="email"], input[type="password"], input[type="file"], input[type="tel"] {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius);
            font-size: 1rem; transition: var(--transition);
        }
        input:focus {
            outline: none; border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        button {
            background-color: var(--primary-color); color: white; padding: 12px 25px; border: none;
            border-radius: var(--border-radius); cursor: pointer; font-size: 1rem; font-weight: 600;
            transition: var(--transition); width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        button:hover { background-color: var(--secondary-color); transform: translateY(-2px);}
        .message { padding: 15px; margin-bottom: 20px; border-radius: var(--border-radius); text-align: center; animation: fadeIn 0.5s ease-out;}
        .success { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745;}
        .error { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;}
        .password-section { margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;}
        .error-password { color: #dc3545; margin-top: 5px; font-size: 0.9rem;}
        .profile-picture { display: flex; align-items: center; gap: 20px; margin-top: 15px;}
        .profile-picture img {
            width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
            border: 3px solid var(--primary-color); box-shadow: var(--box-shadow);
        }
        .retour-button {
            position: absolute; top: 20px; left: 20px; background-color: var(--primary-color);
            color: white; padding: 10px 15px; border-radius: var(--border-radius);
            text-decoration: none; font-weight: 500; transition: var(--transition);
            display: flex; align-items: center; gap: 8px;
        }
        .retour-button:hover { background-color: var(--secondary-color); transform: translateX(-5px);}
        .password-requirements {
            background-color: #f8f9fa; padding: 15px; border-radius: var(--border-radius);
            margin-bottom: 20px; border-left: 4px solid var(--primary-color);
        }
        .password-requirements ul { margin-left: 20px;}
        .password-requirements li { margin-bottom: 5px; font-size: 0.9rem;}
        @media (max-width: 768px) {
            body { padding: 15px;}
            h1 { margin-top: 80px; font-size: 1.8rem;}
            .form-container { padding: 20px;}
            .profile-picture { flex-direction: column; align-items: flex-start;}
        }
        @media (max-width: 480px) {
            .logo-header img { max-width: 150px;}
            .retour-button { top: 10px; left: 10px; padding: 8px 12px; font-size: 0.9rem;}
        }
    </style>
    <script>
        function validerMotDePasse() {
            const nouveauMotDePasse = document.getElementById('nouveau_mot_de_passe').value;
            const confirmerMotDePasse = document.getElementById('confirmer_mot_de_passe').value;
            const messageErreur = document.getElementById('erreur_mot_de_passe');
            const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

            if (!pattern.test(nouveauMotDePasse)) {
                messageErreur.textContent = "Le mot de passe ne respecte pas les critères requis.";
                return false;
            }

            if (nouveauMotDePasse !== confirmerMotDePasse) {
                messageErreur.textContent = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
                return false;
            }

            messageErreur.textContent = "";
            return true;
        }

        function previewImage(event) {
            const preview = document.getElementById('profile-preview');
            const file = event.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['error_message_mdp'])): ?>
                setTimeout(() => {
                    alert("<?php echo $_SESSION['error_message_mdp']; ?>");
                }, 300);
                <?php unset($_SESSION['error_message_mdp']); ?>
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <a href="2session.php" class="retour-button">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <div class="logo-header">
        <img src="kama2.ico" alt="KAMA SHOPPING Logo">
    </div>

    <h1><i class="fas fa-user-edit"></i> Modifier le profil</h1>

    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="message success">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="message error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($vendeur['nom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="postnom"><i class="fas fa-user"></i> Postnom :</label>
                <input type="text" id="postnom" name="postnom" value="<?php echo htmlspecialchars($vendeur['postnom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom"><i class="fas fa-user"></i> Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($vendeur['prenom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendeur['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="numero"><i class="fas fa-phone"></i> Numéro :</label>
                <input type="tel" id="numero" name="numero" value="<?php echo htmlspecialchars($vendeur['numero']); ?>" required>
            </div>

            <div class="form-group">
                <label for="image"><i class="fas fa-camera"></i> Photo de profil :</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                
                <div class="profile-picture">
                    <?php if(!empty($vendeur['image'])): ?>
                        <img src="<?= htmlspecialchars($vendeur['image']) ?>" alt="Photo de profil actuelle" id="profile-preview">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/100" alt="Aucune photo de profil" id="profile-preview" style="display: none;">
                    <?php endif; ?>
                    <span>Prévisualisation</span>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </form>
    </div>

    <div class="form-container password-section">
        <h2><i class="fas fa-lock"></i> Modifier le mot de passe</h2>
        
        <div class="password-requirements">
            <p><strong>Critères du mot de passe :</strong></p>
            <ul>
                <li>Au moins 6 caractères</li>
                <li>Une lettre majuscule</li>
                <li>Une lettre minuscule</li>
                <li>Un chiffre</li>
                <li>Un caractère spécial (@, $, !, %, *, ?, &)</li>
            </ul>
        </div>

        <?php if(isset($_SESSION['success_message_mdp'])): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message_mdp']; unset($_SESSION['success_message_mdp']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validerMotDePasse();">
            <input type="hidden" name="action" value="modifier_mot_de_passe">
            
            <div class="form-group">
                <label for="ancien_mot_de_passe"><i class="fas fa-key"></i> Ancien mot de passe :</label>
                <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="nouveau_mot_de_passe"><i class="fas fa-key"></i> Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="confirmer_mot_de_passe"><i class="fas fa-key"></i> Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>
            
            <p id="erreur_mot_de_passe" class="error-password"></p>
            
            <button type="submit">
                <i class="fas fa-sync-alt"></i> Changer le mot de passe
            </button>
        </form>
    </div>
</body>
</html><?php
session_start();
include 'db2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: 3connexion.php");
    exit();
}

$vendeur_id = $_SESSION['user_id'];

// Traitement du formulaire de modification du mot de passe
if (isset($_POST['action']) && $_POST['action'] === 'modifier_mot_de_passe') {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'];
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    // Validation côté serveur du nouveau mot de passe
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';
    if (!preg_match($pattern, $nouveau_mot_de_passe)) {
        $_SESSION['error_message_mdp'] = "Le nouveau mot de passe ne respecte pas les critères de sécurité : au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        header("Location: edit_profile.php");
        exit();
    }

    $stmt = $connecte->prepare("SELECT mdp FROM vendeur WHERE id = ?");
    $stmt->bind_param("i", $vendeur_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($ancien_mot_de_passe, $row['mdp'])) {
        if ($nouveau_mot_de_passe === $confirmer_mot_de_passe) {
            $mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
            $stmt_update = $connecte->prepare("UPDATE vendeur SET mdp = ? WHERE id = ?");
            $stmt_update->bind_param("si", $mot_de_passe_hash, $vendeur_id);
            if ($stmt_update->execute()) {
                $_SESSION['success_message_mdp'] = "Mot de passe mis à jour avec succès !";
            } else {
                $_SESSION['error_message_mdp'] = "Erreur lors de la mise à jour du mot de passe.";
            }
            $stmt_update->close();
        } else {
            $_SESSION['error_message_mdp'] = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
        }
    } else {
        $_SESSION['error_message_mdp'] = "Ancien mot de passe incorrect.";
    }
    $stmt->close();
    header("Location: edit_profile.php");
    exit();
}

// Traitement du formulaire de modification des informations personnelles
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];

    // Gestion de l'image
    $imagePath = null;
    $uploadDir = "KAMA_IMAGE/";

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!empty($_FILES['image']['tmp_name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $imageName = uniqid('profil_') . '.' . $ext;
        $imagePath = $uploadDir . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);

        // Supprimer l'ancienne image si elle existe et n'est pas vide
        $stmt_old = $connecte->prepare("SELECT image FROM vendeur WHERE id=?");
        $stmt_old->bind_param("i", $vendeur_id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        if ($row_old = $result_old->fetch_assoc()) {
            if (!empty($row_old['image']) && file_exists($row_old['image'])) {
                unlink($row_old['image']);
            }
        }
        $stmt_old->close();
    }

    if ($imagePath) {
        $stmt = $connecte->prepare("UPDATE vendeur SET nom=?, postnom=?, prenom=?, email=?, numero=?, image=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nom, $postnom, $prenom, $email, $numero, $imagePath, $vendeur_id);
    } else {
        $stmt = $connecte->prepare("UPDATE vendeur SET nom=?, postnom=?, prenom=?, email=?, numero=? WHERE id=?");
        $stmt->bind_param("sssssi", $nom, $postnom, $prenom, $email, $numero, $vendeur_id);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Profil mis à jour avec succès !";
        header("Location: edit_profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour du profil.";
    }
    $stmt->close();
}

// Récupérer les informations actuelles du vendeur
$stmt = $connecte->prepare("SELECT * FROM vendeur WHERE id = ?");
$stmt->bind_param("i", $vendeur_id);
$stmt->execute();
$result = $stmt->get_result();
$vendeur = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="kama.ico" type="image/x-icon">
    <title>Modifier le profil | KAMA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }
        body {
            background-color: var(--light-color);
            color: var(--text-color);
            min-height: 100vh;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        h1, h2 { text-align: center; margin-bottom: 30px; color: var(--dark-color); }
        h1 { margin-top: 60px; }
        .logo-header { text-align: center; margin-bottom: 20px; }
        .logo-header img { max-width: 200px; height: auto; transition: transform 0.3s ease; }
        .logo-header img:hover { transform: scale(1.05); }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; color: var(--dark-color);}
        input[type="text"], input[type="email"], input[type="password"], input[type="file"], input[type="tel"] {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: var(--border-radius);
            font-size: 1rem; transition: var(--transition);
        }
        input:focus {
            outline: none; border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        button {
            background-color: var(--primary-color); color: white; padding: 12px 25px; border: none;
            border-radius: var(--border-radius); cursor: pointer; font-size: 1rem; font-weight: 600;
            transition: var(--transition); width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        button:hover { background-color: var(--secondary-color); transform: translateY(-2px);}
        .message { padding: 15px; margin-bottom: 20px; border-radius: var(--border-radius); text-align: center; animation: fadeIn 0.5s ease-out;}
        .success { background-color: #d4edda; color: #155724; border-left: 4px solid #28a745;}
        .error { background-color: #f8d7da; color: #721c24; border-left: 4px solid #dc3545;}
        .password-section { margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;}
        .error-password { color: #dc3545; margin-top: 5px; font-size: 0.9rem;}
        .profile-picture { display: flex; align-items: center; gap: 20px; margin-top: 15px;}
        .profile-picture img {
            width: 100px; height: 100px; border-radius: 50%; object-fit: cover;
            border: 3px solid var(--primary-color); box-shadow: var(--box-shadow);
        }
        .retour-button {
            position: absolute; top: 20px; left: 20px; background-color: var(--primary-color);
            color: white; padding: 10px 15px; border-radius: var(--border-radius);
            text-decoration: none; font-weight: 500; transition: var(--transition);
            display: flex; align-items: center; gap: 8px;
        }
        .retour-button:hover { background-color: var(--secondary-color); transform: translateX(-5px);}
        .password-requirements {
            background-color: #f8f9fa; padding: 15px; border-radius: var(--border-radius);
            margin-bottom: 20px; border-left: 4px solid var(--primary-color);
        }
        .password-requirements ul { margin-left: 20px;}
        .password-requirements li { margin-bottom: 5px; font-size: 0.9rem;}
        @media (max-width: 768px) {
            body { padding: 15px;}
            h1 { margin-top: 80px; font-size: 1.8rem;}
            .form-container { padding: 20px;}
            .profile-picture { flex-direction: column; align-items: flex-start;}
        }
        @media (max-width: 480px) {
            .logo-header img { max-width: 150px;}
            .retour-button { top: 10px; left: 10px; padding: 8px 12px; font-size: 0.9rem;}
        }
    </style>
    <script>
        function validerMotDePasse() {
            const nouveauMotDePasse = document.getElementById('nouveau_mot_de_passe').value;
            const confirmerMotDePasse = document.getElementById('confirmer_mot_de_passe').value;
            const messageErreur = document.getElementById('erreur_mot_de_passe');
            const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;

            if (!pattern.test(nouveauMotDePasse)) {
                messageErreur.textContent = "Le mot de passe ne respecte pas les critères requis.";
                return false;
            }

            if (nouveauMotDePasse !== confirmerMotDePasse) {
                messageErreur.textContent = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
                return false;
            }

            messageErreur.textContent = "";
            return true;
        }

        function previewImage(event) {
            const preview = document.getElementById('profile-preview');
            const file = event.target.files[0];
            const reader = new FileReader();
            
            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }
            
            if (file) {
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['error_message_mdp'])): ?>
                setTimeout(() => {
                    alert("<?php echo $_SESSION['error_message_mdp']; ?>");
                }, 300);
                <?php unset($_SESSION['error_message_mdp']); ?>
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <a href="2session.php" class="retour-button">
        <i class="fas fa-arrow-left"></i> Retour
    </a>

    <div class="logo-header">
        <img src="kama2.ico" alt="KAMA SHOPPING Logo">
    </div>

    <h1><i class="fas fa-user-edit"></i> Modifier le profil</h1>

    <?php if(isset($_SESSION['success_message'])): ?>
        <div class="message success">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="message error">
            <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom"><i class="fas fa-user"></i> Nom :</label>
                <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($vendeur['nom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="postnom"><i class="fas fa-user"></i> Postnom :</label>
                <input type="text" id="postnom" name="postnom" value="<?php echo htmlspecialchars($vendeur['postnom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="prenom"><i class="fas fa-user"></i> Prénom :</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($vendeur['prenom']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email :</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendeur['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="numero"><i class="fas fa-phone"></i> Numéro :</label>
                <input type="tel" id="numero" name="numero" value="<?php echo htmlspecialchars($vendeur['numero']); ?>" required>
            </div>

            <div class="form-group">
                <label for="image"><i class="fas fa-camera"></i> Photo de profil :</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                
                <div class="profile-picture">
                    <?php if(!empty($vendeur['image'])): ?>
                        <img src="<?= htmlspecialchars($vendeur['image']) ?>" alt="Photo de profil actuelle" id="profile-preview">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/100" alt="Aucune photo de profil" id="profile-preview" style="display: none;">
                    <?php endif; ?>
                    <span>Prévisualisation</span>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-save"></i> Enregistrer les modifications
            </button>
        </form>
    </div>

    <div class="form-container password-section">
        <h2><i class="fas fa-lock"></i> Modifier le mot de passe</h2>
        
        <div class="password-requirements">
            <p><strong>Critères du mot de passe :</strong></p>
            <ul>
                <li>Au moins 6 caractères</li>
                <li>Une lettre majuscule</li>
                <li>Une lettre minuscule</li>
                <li>Un chiffre</li>
                <li>Un caractère spécial (@, $, !, %, *, ?, &)</li>
            </ul>
        </div>

        <?php if(isset($_SESSION['success_message_mdp'])): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message_mdp']; unset($_SESSION['success_message_mdp']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" onsubmit="return validerMotDePasse();">
            <input type="hidden" name="action" value="modifier_mot_de_passe">
            
            <div class="form-group">
                <label for="ancien_mot_de_passe"><i class="fas fa-key"></i> Ancien mot de passe :</label>
                <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="nouveau_mot_de_passe"><i class="fas fa-key"></i> Nouveau mot de passe :</label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" required>
            </div>

            <div class="form-group">
                <label for="confirmer_mot_de_passe"><i class="fas fa-key"></i> Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required>
            </div>
            
            <p id="erreur_mot_de_passe" class="error-password"></p>
            
            <button type="submit">
                <i class="fas fa-sync-alt"></i> Changer le mot de passe
            </button>
        </form>
    </div>
</body>
</html>