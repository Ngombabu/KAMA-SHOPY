<?php
ini_set('memory_limit', '256M');
require_once 'db2.php';

// Récupération des données pour les sacs
$sql_sacs = "SELECT *, image AS image_produit, couleur, marque FROM sac";
$result_sacs = $connecte->query($sql_sacs);
$sacs = $result_sacs->fetch_all(MYSQLI_ASSOC);

// Récupération des données pour les chaussures
$sql_chaussures = "SELECT *, image AS image_produit, couleur, marque FROM chaussure";
$result_chaussures = $connecte->query($sql_chaussures);
$chaussures = $result_chaussures->fetch_all(MYSQLI_ASSOC);

// Récupérer les tendances pour l'affichage des publicités
$result_tendances = $connecte->query("SELECT * FROM tendance");
$tendances = $result_tendances->fetch_all(MYSQLI_ASSOC);

// Récupérer le numéro WhatsApp du vendeur spécifique
$sql_vendeur = "SELECT numero FROM vendeur WHERE nom = 'Tshibamba' AND postnom = 'Mbaya' AND prenom = 'Sylvain'";
$result_vendeur = $connecte->query($sql_vendeur);
$whatsapp_number = "243971236595"; // Valeur par défaut

if ($result_vendeur && $result_vendeur->num_rows > 0) {
    $vendeur = $result_vendeur->fetch_assoc();
    $numero = $vendeur['numero'];
    if (strpos($numero, '243') !== 0) {
        $numero = '243' . ltrim($numero, '0');
    }
    $whatsapp_number = $numero;
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
    <title>KAMA SHOPPING</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="kamastyle2.css">
    <link rel="shortcut icon" href="kama.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Animation de chargement */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .section-title {
            text-align: center;
            font-size: 2rem;
            margin: 30px 0;
            color: #333;
            position: relative;
            padding-bottom: 10px;
        }
        .section-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #2563eb, #3b82f6, #93c5fd);
        }
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
            animation: fadeIn 0.8s ease-out;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 250px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .product-image-container {
            height: 200px;
            overflow: hidden;
            position: relative;
        }
        .product-image1 {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-image1 {
            transform: scale(1.05);
        }
        .product-info {
            padding: 15px;
        }
        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        .product-price {
            color: #2563eb;
            font-weight: bold;
        }
        /* Styles pour l'affichage des publicités */
        .pub-container {
            margin: 40px 0;
            padding: 20px 0;
            background: linear-gradient(135deg, #f5f7fa 0%, #f5f7fa 100%);
            animation: nouveauteFadeIn 1.2s cubic-bezier(.23,1.01,.32,1) both;
            box-shadow: 0 8px 32px rgba(37,99,235,0.08);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
        }
        @keyframes nouveauteFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(40px);}
            to { opacity: 1; transform: scale(1) translateY(0);}
        }
        .pub-title {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #333;
        }
        .pub {
            display: flex;
            gap: 20px;
            padding: 20px;
            scroll-behavior: smooth;
            animation: pubScroll 18s linear infinite;
            will-change: transform;
        }
        @keyframes pubScroll {
            0% { transform: translateX(0);}
            100% { transform: translateX(-50%);}
        }
        .pub:hover {
            animation-play-state: paused;
        }
        .pub img {
            width: 300px;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .pub img:hover {
            transform: scale(1.03);
        }
        /* Styles pour la localisation */
        .localisation {
            padding: 40px 20px;
            text-align: center;
            background-color: #f8fafc;
        }
        .localisation h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }
        .map {
            width: 100%;
            max-width: 800px;
            height: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        /* Footer styles */
        footer {
            background: linear-gradient(135deg, #1e293b 0%, #1e293b 100%);
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
            transition: 0.3s;
        }
        .footer-social a:hover {
            color: #93c5fd;
            transform: translateY(-3px);
        }
        .footer-copyright {
            text-align: center;
            color: #e2e8f0;
            font-size: 0.9rem;
        }
        /* Animation pour les sections de produits */
        @keyframes slideInLeft {
            from { transform: translateX(-50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .le_article1 { animation: slideInLeft 0.8s ease-out; }
        .le_article2 { animation: slideInRight 0.8s ease-out; }
        /* Styles pour la pop-up modale */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 500px;
            position: relative;
            animation: fadeInScale 0.3s ease-out forwards;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        .modal-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .modal-content h3 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 10px;
        }
        .modal-content p {
            font-size: 1.2rem;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .modal-content .product-detail-label {
            font-weight: normal;
            color: #555;
            font-size: 1rem;
            margin-right: 5px;
        }
        .modal-content .product-detail-value {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }
        .whatsapp-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #25D366;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .whatsapp-button:hover {
            background-color: #1DA851;
            transform: translateY(-2px);
        }
        .whatsapp-button i {
            margin-right: 8px;
            font-size: 1.2rem;
        }
        .close-button {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close-button:hover,
        .close-button:focus {
            color: #333;
            text-decoration: none;
            cursor: pointer;
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        @media (max-width: 600px) {
            .pub img { width: 180px; height: 120px; }
            .product-card { width: 95vw; }
            .product-image-container { height: 140px; }
        }
    </style>
</head>
<body class="index-page">
    <header>
        <div class="logo">
            <img src="kama2.ico" alt="KAMA SHOPPING Logo" class="logo-image">
        </div>
        <nav class="nav">
            <a href="index.php">
                <button class="nav-button">
                    <i class="fas fa-home" style="color:#2563eb;"></i>
                    <span class="sr-only">Accueil</span>
                </button>
            </a>
            <a href="recherche.php">
                <button class="nav-button">
                    <i class="fas fa-search"></i>
                    <span class="sr-only">Recherche</span>
                </button>
            </a>
            <a href="chaussure.php">
                <button class="nav-button">
                    <i class="fas fa-shoe-prints"></i>
                    <span class="sr-only">Chaussure</span>
                </button>
            </a>
            <a href="sac.php">
                <button class="nav-button">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="sr-only">Sac</span>
                </button>
            </a>
            <a href="3connexion.php">
                <button class="nav-button">
                    <i class="fas fa-user"></i>
                    <span class="sr-only">Connexion</span>
                </button>
            </a>
        </nav>
    </header>
    <main>
        <div class="background_video">
            <video src="nike.mp4" autoplay loop muted class="le_video"></video>
            <div class="video-overlay"></div>
        </div>
        <h2 class="section-title">Nos Sacs</h2>
        <div class="le_article1" id="leArticle1">
            <?php if (count($sacs) > 0): ?>
                <div class="product-container">
                    <?php foreach ($sacs as $sac): ?>
                        <div class="product-card"
                            data-name="<?php echo htmlspecialchars($sac['nom'] ?? 'Sac'); ?>"
                            data-price="<?php echo htmlspecialchars($sac['prix'] ?? '0'); ?>"
                            data-color="<?php echo htmlspecialchars($sac['couleur'] ?? 'N/A'); ?>"
                            data-brand="<?php echo htmlspecialchars($sac['marque'] ?? 'N/A'); ?>">
                            <div class="product-image-container">
                                <?php if (isset($sac['image_produit']) && $sac['image_produit']): ?>
                                    <img src="<?php echo $sac['image_produit']; ?>" 
                                            alt="<?php echo htmlspecialchars($sac['nom'] ?? 'Sac'); ?>" 
                                            class="product-image1">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300" alt="Image du sac" class="product-image1">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($sac['nom'] ?? 'Sac'); ?></div>
                                <div class="product-price"><?php echo htmlspecialchars($sac['prix'] ?? '0'); ?> $</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="loading">
                    <div class="loading-spinner"></div>
                </div>
                <p style="text-align: center;">Aucun sac disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        <hr>
        <h2 class="section-title">Nos Chaussures</h2>
        <div class="le_article2" id="leArticle2">
            <?php if (count($chaussures) > 0): ?>
                <div class="product-container">
                    <?php foreach ($chaussures as $chaussure): ?>
                        <div class="product-card"
                            data-name="<?php echo htmlspecialchars($chaussure['nom'] ?? 'Chaussure'); ?>"
                            data-price="<?php echo htmlspecialchars($chaussure['prix'] ?? '0'); ?>"
                            data-color="<?php echo htmlspecialchars($chaussure['couleur'] ?? 'N/A'); ?>"
                            data-brand="<?php echo htmlspecialchars($chaussure['marque'] ?? 'N/A'); ?>">
                            <div class="product-image-container">
                                <?php if (isset($chaussure['image_produit']) && $chaussure['image_produit']): ?>
                                    <img src="<?php echo $chaussure['image_produit']; ?>" 
                                            alt="<?php echo htmlspecialchars($chaussure['nom'] ?? 'Chaussure'); ?>" 
                                            class="product-image1">
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300" alt="Image de chaussure" class="product-image1">
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <div class="product-name"><?php echo htmlspecialchars($chaussure['nom'] ?? 'Chaussure'); ?></div>
                                <div class="product-price"><?php echo htmlspecialchars($chaussure['prix'] ?? '0'); ?> $</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="loading">
                    <div class="loading-spinner"></div>
                </div>
                <p style="text-align: center;">Aucune chaussure disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        <hr>
        <div class="pub-container" id="publicite_nouveau">
            <h2 class="pub-title">Nouveautés</h2>
            <div class="pub">
                <?php foreach ($tendances as $tendance): ?>
                    <?php for ($i = 0; $i <= 6; $i++): ?>
                        <?php
                        $imageKey = 'image' . ($i === 0 ? '' : $i);
                        if (isset($tendance[$imageKey]) && $tendance[$imageKey]):
                            ?>
                            <img src="<?php echo $tendance[$imageKey]; ?>" 
                                alt="Publicité <?= $tendance['id'] ?>-<?= $i ?>" 
                                class="pub-image">
                        <?php endif; ?>
                    <?php endfor; ?>
                <?php endforeach; ?>
                <!-- Duplication pour effet de boucle fluide -->
                <?php foreach ($tendances as $tendance): ?>
                    <?php for ($i = 0; $i <= 6; $i++): ?>
                        <?php
                        $imageKey = 'image' . ($i === 0 ? '' : $i);
                        if (isset($tendance[$imageKey]) && $tendance[$imageKey]):
                            ?>
                            <img src="<?php echo $tendance[$imageKey]; ?>" 
                                alt="Publicité <?= $tendance['id'] ?>-<?= $i ?>" 
                                class="pub-image">
                        <?php endif; ?>
                    <?php endfor; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <hr>
        <div class="localisation">
            <h2>Localisation</h2>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3978.5204417283617!2d15.309396174062819!3d-4.312827195661141!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a33f07cb1b763%3A0xefdd5b9387b3b6b2!2sAve%20De%20Kato%2C%20Kinshasa!5e0!3m2!1sfr!2scd!4v1747319271855!5m2!1sfr!2scd" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade" 
                    class="map"></iframe>
        </div>
    </main>
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
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close-button">×</span>
            <img id="modalProductImage" src="" alt="Image du produit">
            <h3 id="modalProductName"></h3>
            <p>
                <span class="product-detail-label">Prix:</span> 
                <span id="modalProductPrice" class="product-detail-value"></span>
            </p>
            <p>
                <span class="product-detail-label">Couleur:</span> 
                <span id="modalProductColor" class="product-detail-value"></span>
            </p>
            <p>
                <span class="product-detail-label">Marque:</span> 
                <span id="modalProductBrand" class="product-detail-value"></span>
            </p>
            <a id="whatsappButton" href="#" target="_blank" class="whatsapp-button">
                <i class="fab fa-whatsapp"></i> Commander sur whatsapp
            </a>
        </div>
    </div>
    <script>
        // Passe le numéro WhatsApp PHP à JS
        const whatsappNumber = '<?php echo $whatsapp_number; ?>';
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const loadingElements = document.querySelectorAll('.loading');
                loadingElements.forEach(el => {
                    el.style.display = 'none';
                });
                const productContainers = document.querySelectorAll('.product-container');
                productContainers.forEach(container => {
                    container.style.opacity = '1';
                });
            }, 1500);
            const productModal = document.getElementById('productModal');
            const closeButton = document.querySelector('.close-button');
            const modalProductImage = document.getElementById('modalProductImage');
            const modalProductName = document.getElementById('modalProductName');
            const modalProductPrice = document.getElementById('modalProductPrice');
            const modalProductColor = document.getElementById('modalProductColor');
            const modalProductBrand = document.getElementById('modalProductBrand');
            const whatsappButton = document.getElementById('whatsappButton');
            function openModal(imageSrc, name, price, color, brand) {
                modalProductImage.src = imageSrc;
                modalProductName.textContent = name;
                modalProductPrice.textContent = price + ' $';
                modalProductColor.textContent = color;
                modalProductBrand.textContent = brand;
                const message = encodeURIComponent(`Bonjour, je suis intéressé(e) par le produit "${name}" (Couleur: ${color}, Marque: ${brand}, Prix: ${price} $). Pourriez-vous me donner plus de détails ou confirmer la disponibilité ?`);
                whatsappButton.href = `https://wa.me/${whatsappNumber}?text=${message}`;
                productModal.style.display = 'flex';
            }
            function closeModal() {
                productModal.style.display = 'none';
            }
            closeButton.addEventListener('click', closeModal);
            window.addEventListener('click', function(event) {
                if (event.target == productModal) {
                    closeModal();
                }
            });
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('click', function() {
                    const imageElement = this.querySelector('.product-image1');
                    const name = this.dataset.name;
                    const price = this.dataset.price;
                    const color = this.dataset.color;
                    const brand = this.dataset.brand;
                    const imageSrc = imageElement.src;
                    openModal(imageSrc, name, price, color, brand);
                });
            });
        });
    </script>
</body>
</html>