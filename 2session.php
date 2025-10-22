<?php
session_start();
include 'db2.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: 3connexion.php');
    exit();
}

$id_vendeur = $_SESSION['user_id'];

// Récupérer les informations du vendeur
$stmt = $connecte->prepare("SELECT * FROM vendeur WHERE id = ?");
$stmt->bind_param('i', $id_vendeur);
$stmt->execute();
$vendeur = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Gestion des publicités (table "tendance")
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_pub'])) {
    $action_pub = $_POST['action_pub'];
    $uploadDir = "KAMA_IMAGE/";

    if ($action_pub === 'add_pub') {
        if (!empty($_FILES['image_pub']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['image_pub']['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('pub_') . '.' . $ext;
            $imagePath = $uploadDir . $imageName;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            move_uploaded_file($_FILES['image_pub']['tmp_name'], $imagePath);
            $sql_pub = "INSERT INTO tendance (id_vendeur, image) VALUES (?, ?)";
            $stmt_pub = $connecte->prepare($sql_pub);
            $stmt_pub->bind_param('is', $id_vendeur, $imagePath);
            $stmt_pub->execute();
            $stmt_pub->close();
        }
    } elseif ($action_pub === 'delete_pub') {
        $id_pub = $_POST['id_pub'];
        $res = $connecte->query("SELECT image FROM tendance WHERE id = $id_pub");
        if ($res && $row = $res->fetch_assoc()) {
            if (!empty($row['image']) && file_exists($row['image'])) {
                unlink($row['image']);
            }
        }
        $sql_pub = "DELETE FROM tendance WHERE id = ?";
        $stmt_pub = $connecte->prepare($sql_pub);
        $stmt_pub->bind_param('i', $id_pub);
        $stmt_pub->execute();
        $stmt_pub->close();
    } elseif ($action_pub === 'edit_pub') {
        $id_pub = $_POST['id_pub'];
        if (!empty($_FILES['image_pub']['tmp_name'])) {
            $ext = strtolower(pathinfo($_FILES['image_pub']['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('pub_') . '.' . $ext;
            $imagePath = $uploadDir . $imageName;
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            move_uploaded_file($_FILES['image_pub']['tmp_name'], $imagePath);
            $res = $connecte->query("SELECT image FROM tendance WHERE id = $id_pub");
            if ($res && $row = $res->fetch_assoc()) {
                if (!empty($row['image']) && file_exists($row['image'])) {
                    unlink($row['image']);
                }
            }
            $sql_pub = "UPDATE tendance SET image = ? WHERE id = ?";
            $stmt_pub = $connecte->prepare($sql_pub);
            $stmt_pub->bind_param('si', $imagePath, $id_pub);
            $stmt_pub->execute();
            $stmt_pub->close();
        }
    }
}

// Gestion des produits
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $table = $_POST['table'];
    $uploadDir = "KAMA_IMAGE/";

    if ($action === 'add') {
        $marque = $_POST['marque'];
        $couleur = $_POST['couleur'];
        $prix = $_POST['prix'];
        $imagePath = null;
        $logoPath = null;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Image principale
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('img_') . '.' . $ext;
            $imagePath = $uploadDir . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        // Logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $logoName = uniqid('logo_') . '.' . $ext;
            $logoPath = $uploadDir . $logoName;
            move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
        }

        if ($table === 'chaussure') {
            $taille = $_POST['taille'];
            $sql = "INSERT INTO chaussure (id_vendeur, marque, couleur, taille, prix, image, logo) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connecte->prepare($sql);
            $stmt->bind_param('isssdss', $id_vendeur, $marque, $couleur, $taille, $prix, $imagePath, $logoPath);
        } elseif ($table === 'sac') {
            $sql = "INSERT INTO sac (id_vendeur, marque, couleur, prix, image, logo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $connecte->prepare($sql);
            $stmt->bind_param('issdss', $id_vendeur, $marque, $couleur, $prix, $imagePath, $logoPath);
        }

        if ($stmt && $stmt->execute()) {
            header("Location: 2session.php");
            exit();
        } else {
            echo "Erreur lors de l'ajout du produit: " . $connecte->error;
        }
        if ($stmt) $stmt->close();

    } elseif ($action === 'edit') {
        $id = $_POST['id'];
        $marque = $_POST['marque'];
        $couleur = $_POST['couleur'];
        $prix = $_POST['prix'];
        $imagePath = null;
        $logoPath = null;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Image principale
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $imageName = uniqid('img_') . '.' . $ext;
            $imagePath = $uploadDir . $imageName;
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        }

        // Logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $logoName = uniqid('logo_') . '.' . $ext;
            $logoPath = $uploadDir . $logoName;
            move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
        }

        $sql = "";
        $types = "";
        $params = [];

        if ($table === 'chaussure') {
            $taille = $_POST['taille'];
            $sql = "UPDATE chaussure SET marque = ?, couleur = ?, taille = ?, prix = ?";
            $types = "sssd";
            $params = [$marque, $couleur, $taille, $prix];
            if ($imagePath) {
                $res = $connecte->query("SELECT image FROM chaussure WHERE id = $id");
                if ($res && $row = $res->fetch_assoc()) {
                    if (!empty($row['image']) && file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                }
                $sql .= ", image = ?";
                $types .= "s";
                $params[] = $imagePath;
            }
            if ($logoPath) {
                $res = $connecte->query("SELECT logo FROM chaussure WHERE id = $id");
                if ($res && $row = $res->fetch_assoc()) {
                    if (!empty($row['logo']) && file_exists($row['logo'])) {
                        unlink($row['logo']);
                    }
                }
                $sql .= ", logo = ?";
                $types .= "s";
                $params[] = $logoPath;
            }
            $sql .= " WHERE id = ?";
            $types .= "i";
            $params[] = $id;
        } elseif ($table === 'sac') {
            $sql = "UPDATE sac SET marque = ?, couleur = ?, prix = ?";
            $types = "ssd";
            $params = [$marque, $couleur, $prix];
            if ($imagePath) {
                $res = $connecte->query("SELECT image FROM sac WHERE id = $id");
                if ($res && $row = $res->fetch_assoc()) {
                    if (!empty($row['image']) && file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                }
                $sql .= ", image = ?";
                $types .= "s";
                $params[] = $imagePath;
            }
            if ($logoPath) {
                $res = $connecte->query("SELECT logo FROM sac WHERE id = $id");
                if ($res && $row = $res->fetch_assoc()) {
                    if (!empty($row['logo']) && file_exists($row['logo'])) {
                        unlink($row['logo']);
                    }
                }
                $sql .= ", logo = ?";
                $types .= "s";
                $params[] = $logoPath;
            }
            $sql .= " WHERE id = ?";
            $types .= "i";
            $params[] = $id;
        }

        $stmt = $connecte->prepare($sql);
        if ($stmt) {
            $stmt->bind_param($types, ...$params);
            if ($stmt->execute()) {
                header("Location: 2session.php");
                exit();
            } else {
                echo "Erreur lors de la modification du produit.";
            }
            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête.";
        }

    } elseif ($action === 'delete') {
        $id = $_POST['id'];
        $res = $connecte->query("SELECT image, logo FROM $table WHERE id = $id");
        if ($res && $row = $res->fetch_assoc()) {
            if (!empty($row['image']) && file_exists($row['image'])) {
                unlink($row['image']);
            }
            if (!empty($row['logo']) && file_exists($row['logo'])) {
                unlink($row['logo']);
            }
        }
        $sql = "DELETE FROM $table WHERE id = ?";
        $stmt = $connecte->prepare($sql);
        $stmt->bind_param('i', $id);
        if ($stmt && $stmt->execute()) {
            header("Location: 2session.php");
            exit();
        } else {
            echo "Erreur lors de la suppression du produit.";
        }
        if ($stmt) $stmt->close();
    }
}

// Récupérer les données pour l'affichage
$result_pub = $connecte->query("SELECT * FROM tendance");
$pubs = $result_pub->fetch_all(MYSQLI_ASSOC);

$sql_sacs = "SELECT * FROM sac WHERE id_vendeur = $id_vendeur";
$result_sacs = $connecte->query($sql_sacs);
$sacs = $result_sacs->fetch_all(MYSQLI_ASSOC);

$sql_chaussures = "SELECT * FROM chaussure WHERE id_vendeur = $id_vendeur";
$result_chaussures = $connecte->query($sql_chaussures);
$chaussures = $result_chaussures->fetch_all(MYSQLI_ASSOC);
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
    <title>Administration | KAMA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
            padding: 20px;
        }

        /* Header styles */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--primary-color);
            animation: fadeIn 0.5s ease-out;
        }

        .admin-header h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            margin: 0;
        }

        .logo-image {
            height: 50px;
        }

        /* Admin info section */
        .admin-info {
            background-color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out;
        }

        .admin-info h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .admin-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .admin-details p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .admin-details strong {
            color: var(--dark-color);
        }

        .admin-photo {
            display: flex;
            justify-content: center;
            margin: 15px 0;
        }

        .admin-photo img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
        }

        .admin-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .admin-actions a {
            padding: 10px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .edit-profile {
            background-color: var(--primary-color);
            color: white;
        }

        .edit-profile:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .delete-account {
            background-color: #dc3545;
            color: white;
        }

        .delete-account:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .logout {
            background-color: #6c757d;
            color: white;
        }

        .logout:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        /* Form styles */
        .form-container {
            background-color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out;
        }

        .form-container h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        button, input[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: var(--transition);
            width: 100%;
            margin-top: 10px;
        }

        button:hover, input[type="submit"]:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        /* Pub styles */
        .pub-container {
            background-color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
            animation: fadeInUp 0.5s ease-out;
        }

        .pub-container h2, .pub-container h3 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .pub-image-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .pub-image-item {
            position: relative;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            height: 200px;
        }

        .pub-image-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .pub-image-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pub-image-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .pub-image-actions button {
            width: auto;
            padding: 8px 12px;
            margin: 0;
            font-size: 0.8rem;
            border-radius: 20px;
        }

        .pub-image-actions button.edit-btn {
            background-color: var(--primary-color);
        }

        .pub-image-actions button.delete-btn {
            background-color: #dc3545;
        }

        /* Table styles */
        .table-container {
            overflow-x: auto;
            margin-bottom: 40px;
            animation: fadeIn 0.5s ease-out;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .logo-image-small {
            width: 60px;
            height: 60px;
            object-fit: contain;
            border-radius: 50%;
            border: 1px solid #ddd;
        }

        .edit-form {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            align-items: center;
        }

        .edit-form input, .edit-form select {
            margin-bottom: 0;
            padding: 8px;
        }

        .edit-form button {
            margin-top: 0;
            padding: 8px 12px;
        }

        .delete-form button {
            background-color: #dc3545;
        }

        .delete-form button:hover {
            background-color: #c82333;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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

        /* Responsive design */
        @media (max-width: 768px) {
            .admin-header h1 {
                font-size: 1.8rem;
            }

            .admin-details {
                grid-template-columns: 1fr;
            }

            .pub-image-list {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }

            .edit-form {
                grid-template-columns: 1fr 1fr;
            }

            th, td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .admin-actions {
                flex-direction: column;
            }

            .admin-actions a {
                width: 100%;
                justify-content: center;
            }

            .pub-image-list {
                grid-template-columns: 1fr;
            }

            .edit-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('table');
            const chaussureFields = document.getElementById('chaussure-fields');
            function toggleTailleVisibility() {
                chaussureFields.style.display = categorySelect.value === 'chaussure' ? 'block' : 'none';
            }
            categorySelect.addEventListener('change', toggleTailleVisibility);
            toggleTailleVisibility();

            // Confirmation pour suppression
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="admin-header">
        <h1><i class="fas fa-cog"></i> Administration KAMA</h1>
        <img src="kama2.ico" alt="KAMA SHOPPING Logo" class="logo-image">
    </div>

    <div class="admin-info">
        <h2><i class="fas fa-user-shield"></i> Informations Administrateur</h2>
        <div class="admin-details">
            <div>
                <p><strong>Nom:</strong> <?= htmlspecialchars($vendeur['nom']) ?></p>
                <p><strong>Postnom:</strong> <?= htmlspecialchars($vendeur['postnom']) ?></p>
                <p><strong>Prénom:</strong> <?= htmlspecialchars($vendeur['prenom']) ?></p>
            </div>
            <div>
                <p><strong>Email:</strong> <?= htmlspecialchars($vendeur['email']) ?></p>
                <p><strong>Téléphone:</strong> +243<?= htmlspecialchars($vendeur['numero']) ?></p>
            </div>
        </div>
        <?php if(!empty($vendeur['image'])): ?>
            <div class="admin-photo">
                <img src="<?= htmlspecialchars($vendeur['image']) ?>" alt="Photo de profil">
            </div>
        <?php endif; ?>
        <div class="admin-actions">
            <a href="edit_profile.php" class="edit-profile">
                <i class="fas fa-user-edit"></i> Modifier profil
            </a>
            <a href="delete_account.php" class="delete-account" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte?');">
                <i class="fas fa-user-times"></i> Supprimer compte
            </a>
            <a href="logout.php" class="logout">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>

    <div class="pub-container">
        <h2><i class="fas fa-bullhorn"></i> Gestion des Nouveautés</h2>
        <form method="POST" action="" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="action_pub" value="add_pub">
            <label for="image_pub"><i class="fas fa-image"></i> Nouvelle image Nouveautés:</label>
            <input type="file" name="image_pub" id="image_pub" accept="image/*" required>
            <button type="submit"><i class="fas fa-plus-circle"></i> Ajouter Nouveautés</button>
        </form>
        <h3><i class="fas fa-images"></i> Nouveautés Actuelles</h3>
        <div class="pub-image-list">
            <?php if (!empty($pubs)): ?>
                <?php foreach ($pubs as $pub): ?>
                    <div class="pub-image-item">
                        <?php if (!empty($pub['image'])): ?>
                            <img src="<?= htmlspecialchars($pub['image']) ?>" alt="Publicité <?= $pub['id'] ?>">
                            <div class="pub-image-actions">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="action_pub" value="edit_pub">
                                    <input type="hidden" name="id_pub" value="<?= $pub['id'] ?>">
                                    <input type="file" name="image_pub" id="edit_image_pub_<?= $pub['id'] ?>" accept="image/*" style="display: none;">
                                    <label for="edit_image_pub_<?= $pub['id'] ?>" class="edit-btn"><i class="fas fa-edit"></i></label>
                                    <button type="submit" class="edit-btn" style="display: none;">OK</button>
                                </form>
                                <form method="POST" action="" class="delete-form">
                                    <input type="hidden" name="action_pub" value="delete_pub">
                                    <input type="hidden" name="id_pub" value="<?= $pub['id'] ?>">
                                    <button type="submit" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        <?php else: ?>
                            <p>Pas d'image principale pour cette Nouveautés (ID: <?= $pub['id'] ?>)</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune Nouveautés enregistrée.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-container">
        <h2><i class="fas fa-plus-circle"></i> Ajouter un Produit</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add">
            <label for="table"><i class="fas fa-tags"></i> Catégorie:</label>
            <select name="table" id="table" required>
                <option value="chaussure">Chaussure</option>
                <option value="sac">Sac</option>
            </select>
            <label for="marque"><i class="fas fa-tag"></i> Marque:</label>
            <input type="text" name="marque" id="marque" required>
            <label for="couleur"><i class="fas fa-palette"></i> Couleur:</label>
            <input type="text" name="couleur" id="couleur" required>
            <div id="chaussure-fields">
                <label for="taille"><i class="fas fa-ruler"></i> Pointure:</label>
                <input type="text" name="taille" id="taille">
            </div>
            <label for="prix"><i class="fas fa-dollar-sign"></i> Prix ($):</label>
            <input type="number" name="prix" id="prix" step="0.01" min="0" required>
            <label for="image"><i class="fas fa-camera"></i> Image du produit:</label>
            <input type="file" name="image" id="image" accept="image/*" required>
            <label for="logo"><i class="fas fa-image"></i> Logo de la marque:</label>
            <input type="file" name="logo" id="logo" accept="image/*" required>
            <button type="submit"><i class="fas fa-save"></i> Ajouter le Produit</button>
        </form>
    </div>

    <div class="table-container">
        <h2><i class="fas fa-shoe-prints"></i> Produits - Chaussures</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Couleur</th>
                    <th>Pointure</th>
                    <th>Prix</th>
                    <th>Image</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($chaussures as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['marque']) ?></td>
                        <td><?= htmlspecialchars($row['couleur']) ?></td>
                        <td><?= htmlspecialchars($row['taille']) ?></td>
                        <td><?= number_format($row['prix'], 2) ?> $</td>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Chaussure <?= htmlspecialchars($row['marque']) ?>" class="product-image"></td>
                        <td><img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo <?= htmlspecialchars($row['marque']) ?>" class="logo-image-small"></td>
                        <td>
                            <form method="POST" action="" enctype="multipart/form-data" class="edit-form" style="margin-bottom:5px;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="table" value="chaussure">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="marque" value="<?= htmlspecialchars($row['marque']) ?>" placeholder="Marque" required>
                                <input type="text" name="couleur" value="<?= htmlspecialchars($row['couleur']) ?>" placeholder="Couleur" required>
                                <input type="text" name="taille" value="<?= htmlspecialchars($row['taille']) ?>" placeholder="Pointure">
                                <input type="number" name="prix" value="<?= $row['prix'] ?>" step="0.01" placeholder="Prix">
                                <input type="file" name="image" accept="image/*">
                                <input type="file" name="logo" accept="image/*">
                                <button type="submit"><i class="fas fa-edit"></i> Modifier</button>
                            </form>
                            <form method="POST" action="" class="delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="table" value="chaussure">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h2><i class="fas fa-shopping-bag"></i> Produits - Sacs</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marque</th>
                    <th>Couleur</th>
                    <th>Prix</th>
                    <th>Image</th>
                    <th>Logo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sacs as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['marque']) ?></td>
                        <td><?= htmlspecialchars($row['couleur']) ?></td>
                        <td><?= number_format($row['prix'], 2) ?> $</td>
                        <td><img src="<?= htmlspecialchars($row['image']) ?>" alt="Sac <?= htmlspecialchars($row['marque']) ?>" class="product-image"></td>
                        <td><img src="<?= htmlspecialchars($row['logo']) ?>" alt="Logo <?= htmlspecialchars($row['marque']) ?>" class="logo-image-small"></td>
                        <td>
                            <form method="POST" action="" enctype="multipart/form-data" class="edit-form" style="margin-bottom:5px;">
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="table" value="sac">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <input type="text" name="marque" value="<?= htmlspecialchars($row['marque']) ?>" placeholder="Marque" required>
                                <input type="text" name="couleur" value="<?= htmlspecialchars($row['couleur']) ?>" placeholder="Couleur" required>
                                <input type="number" name="prix" value="<?= $row['prix'] ?>" step="0.01" placeholder="Prix">
                                <input type="file" name="image" accept="image/*">
                                <input type="file" name="logo" accept="image/*">
                                <button type="submit"><i class="fas fa-edit"></i> Modifier</button>
                            </form>
                            <form method="POST" action="" class="delete-form">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="table" value="sac">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit"><i class="fas fa-trash-alt"></i> Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>