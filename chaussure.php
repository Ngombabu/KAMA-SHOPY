<?php
require_once 'db2.php';

// Récupérer toutes les chaussures avec image et logo
$sql_chaussures = "SELECT *, image as image_chaussure, logo as logo_marque FROM chaussure";
$result_chaussures = $connecte->query($sql_chaussures);
$chaussures = [];
if ($result_chaussures->num_rows > 0) {
    while ($row = $result_chaussures->fetch_assoc()) {
        $chaussures[] = $row;
    }
}

// Récupérer le numéro WhatsApp du vendeur spécifique
$sql_vendeur = "SELECT numero FROM vendeur WHERE nom = 'Tshibamba' AND postnom = 'Mbaya' AND prenom = 'Sylvain'";
$result_vendeur = $connecte->query($sql_vendeur);
$whatsapp_number = "243971236595"; // Valeur par défaut si le vendeur n'est pas trouvé

if ($result_vendeur->num_rows > 0) {
    $vendeur = $result_vendeur->fetch_assoc();
    // Ajoute le code pays si absent
    $numero = $vendeur['numero'];
    if (strpos($numero, '243') !== 0) {
        $numero = '243' . ltrim($numero, '0');
    }
    $whatsapp_number = $numero;
}

$connecte->close();
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
    <title>Chaussures - Kama Boutique</title>
    <link rel="icon" href="kama.ico" type="image/x-icon">
    <link rel="stylesheet" href="kamastyle2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        
        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            background-color: var(--light-color);
            color: var(--text-color);
            overflow-x: hidden;
        }
        nav{
            margin-right:2%;
        }
        
        /* Animation de chargement */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }
        
        .loading-spinner {
            width: 70px;
            height: 70px;
            border: 8px solid rgba(37, 99, 235, 0.2);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1.2s linear infinite;
            margin-bottom: 20px;
        }
        
        .loading-text {
            font-size: 1.2rem;
            color: var(--dark-color);
            font-weight: 500;
            animation: pulse 1.5s infinite alternate;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse {
            0% { opacity: 0.6; }
            100% { opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: white;
            z-index: 100;
            padding: 10px 0;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
        }
        
        .container {
            margin-top: 180px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px 0;
        }
        
        .product-item {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
            position: relative;
        }
        
        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        
        .product-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .brand-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            margin-right: 15px;
            border-radius: 50%;
            border: 1px solid #e2e8f0;
            background-color: white;
            padding: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .product-title h3 {
            margin: 0;
            color: var(--dark-color);
            font-size: 1.1rem;
        }
        
        .product-image {
            width: 100%;
            height: 250px;
            object-fit: contain;
            border-radius: 8px;
            margin: 10px 0;
            cursor: pointer;
            transition: var(--transition);
            background-color: #f8fafc;
            padding: 10px;
        }
        
        .product-image:hover {
            transform: scale(1.03);
        }
        
        .product-details {
            margin-top: 15px;
        }
        
        .product-details p {
            margin: 8px 0;
            font-size: 0.95rem;
            color: var(--text-color);
        }
        
        .product-details p strong {
            color: var(--dark-color);
        }
        
        .whatsapp-button {
            display: inline-block;
            background-color: #25D366;
            color: white;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-weight: 500;
            margin-top: 15px;
            transition: var(--transition);
            width: 100%;
            text-align: center;
        }
        
        .whatsapp-button:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
        }
        
        .whatsapp-button i {
            margin-right: 8px;
        }
        
        .no-produits-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 60vh;
            grid-column: 1 / -1;
            text-align: center;
        }
        
        .no-produits-image {
            max-width: 250px;
            height: auto;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        
        .no-produits-container p {
            font-size: 1.2rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        footer {
            background: linear-gradient(135deg, var(--dark-color) 0%, #1e293b 100%);
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
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
        
        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                margin-top: 140px;
                padding: 15px;
            }
            
            .product-grid {
                grid-template-columns: 1fr;
            }
            
            .product-item {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay de chargement -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Chargement des produits...</div>
    </div>

    <header>
        <div class="logo">
            <img src="kama2.ico" alt="KAMA SHOPPING Logo" class="logo-image">
        </div>
          <!-- <marquee direction="right" style="color:red ;border:2px solid green;">Site en développement par Novilink pour un client...</marquee>-->
        <nav class="nav">
            <a href="index.php">
                <button class="nav-button">
                    <i class="fas fa-home" id="accueil"></i>
                    <span class="sr-only">Accueil</span>
                </button>
            </a>
            <a href="recherche.php">
                <button class="nav-button">
                    <i class="fas fa-search" id="loop"></i>
                    <span class="sr-only">Recherche</span>
                </button>
            </a>
            <a href="chaussure.php">
                <button class="nav-button">
                    <i class="fas fa-shoe-prints" style="color:#2563eb;" id="chaussure"></i>
                    <span class="sr-only">Chaussure</span>
                </button>
            </a>
            <a href="sac.php">
                <button class="nav-button">
                    <i class="fas fa-shopping-bag" id="sac"></i>
                    <span class="sr-only">Sac</span>
                </button>
            </a>
            <a href="3connexion.php">
                <button class="nav-button">
                    <i class="fas fa-user" id="connexion"></i>
                    <span class="sr-only">Connexion</span>
                </button>
            </a>
        </nav>
    </header>

    <div class="container">
        <div class="product-grid">
            <?php if (empty($chaussures)): ?>
                <div class="no-produits-container">
                    <img src="aucun.webp" alt="Pas de chaussures disponibles" class="no-produits-image">
                    <p>Aucune chaussure disponible pour le moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($chaussures as $chaussure): ?>
                    <div class="product-item">
                        <div class="product-header">
                            <?php if (!empty($chaussure['logo_marque'])): ?>
                                <img src="<?= $chaussure['logo_marque'] ?>" 
                                     alt="Logo <?= htmlspecialchars($chaussure['marque']) ?>" 
                                     class="brand-logo">
                            <?php else: ?>
                                <div class="brand-logo" style="display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-shoe-prints" style="color: var(--primary-color); font-size: 1.5rem;"></i>
                                </div>
                            <?php endif; ?>
                            <div class="product-title">
                                <h3><?= htmlspecialchars($chaussure['marque']) ?></h3>
                            </div>
                        </div>
                        
                        <?php if (!empty($chaussure['image_chaussure'])): ?>
                            <img src="<?= $chaussure['image_chaussure'] ?>" 
                                 alt="Chaussure <?= htmlspecialchars($chaussure['marque']) ?>" 
                                 class="product-image" 
                                 ondblclick="zoomImage(this)">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300?text=Image+non+disponible" 
                                 alt="Image non disponible" 
                                 class="product-image">
                        <?php endif; ?>
                        
                        <div class="product-details">
                            <p><strong>Couleur:</strong> <?= htmlspecialchars($chaussure['couleur']) ?></p>
                            <p><strong>Taille:</strong> <?= htmlspecialchars($chaussure['taille']) ?></p>
                            <p><strong>Prix:</strong> <?= htmlspecialchars($chaussure['prix']) ?> $</p>
                        </div>
                        
                        <a href="https://wa.me/<?= $whatsapp_number ?>?text=Bonjour, je suis intéressé(e) par la chaussure de marque <?= urlencode(htmlspecialchars($chaussure['marque'])) ?> de couleur <?= urlencode(htmlspecialchars($chaussure['couleur'])) ?> et de taille <?= urlencode(htmlspecialchars($chaussure['taille'])) ?> au prix de <?= urlencode(htmlspecialchars($chaussure['prix'])) ?> $." 
                           class="whatsapp-button" 
                           target="_blank">
                            <i class="fab fa-whatsapp"></i> Commander sur WhatsApp
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
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
        // Masquer l'overlay de chargement une fois la page chargée
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loadingOverlay').style.opacity = '0';
                setTimeout(function() {
                    document.getElementById('loadingOverlay').style.display = 'none';
                }, 500);
            }, 1000);
        });
        
        // Fonction pour zoomer une image
        function zoomImage(img) {
            img.classList.toggle('zoomed');
        }
        
        // Animation au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const productItems = document.querySelectorAll('.product-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });
            
            productItems.forEach(item => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(item);
            });
        });
    </script>
</body>
</html>