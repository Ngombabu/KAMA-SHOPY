<?php
require_once('db2.php');

// Récupérer toutes les marques de chaussures uniques avec leurs logos
$sqlMarquesChaussures = "SELECT DISTINCT marque, logo FROM chaussure ORDER BY marque";
$resultMarquesChaussures = $connecte->query($sqlMarquesChaussures);
$marquesChaussures = $resultMarquesChaussures->fetch_all(MYSQLI_ASSOC);

// Récupérer toutes les marques de sacs uniques avec leurs logos
$sqlMarquesSacs = "SELECT DISTINCT marque, logo FROM sac ORDER BY marque";
$resultMarquesSacs = $connecte->query($sqlMarquesSacs);
$marquesSacs = $resultMarquesSacs->fetch_all(MYSQLI_ASSOC);

// Numéro WhatsApp
$whatsappNumero = '+243971236595';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      :root {
    --primary-color: #ffffff;
    --secondary-color: #000000;
    --accent-color: #2563eb; /* Bleu plus moderne */
    --success-color: #10b981; /* Vert plus doux */
    --error-color: #ef4444;
    --text-primary: #1f2937; /* Gris foncé pour meilleure lisibilité */
    --text-secondary: #6b7280;
    --border-color: #e5e7eb;
    --background-light: #f9fafb;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-sm: 0.25rem;
    --radius-md: 0.5rem;
    --radius-lg: 0.75rem;
    --radius-full: 9999px;
}

body {
    font-family: 'Montserrat', sans-serif;
    background-color: var(--primary-color);
    margin: 0;
    padding: 0;
    color: var(--text-primary);
    line-height: 1.6;
    padding-bottom: 80px;
    overflow-x: hidden; /* Empêche le scroll horizontal */
}
// ...existing code...
html {
    overflow-x: hidden; /* Empêche le scroll horizontal */
}

@media (max-width: 600px) {
    header {
        padding: 0.5rem 0;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        z-index: 1000;
        box-shadow: var(--shadow-sm);
        background: var(--primary-color);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .logo-image {
        height: 28px;
        margin: 0 auto;
    }
    .container {
        margin-top: 60px;
        margin-bottom: 80px;
        padding: 0 6px;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100vw;
        background: var(--primary-color);
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        justify-content: space-around;
        padding: 0.5rem 0;
    }
    .nav-link {
        flex: 1 1 0;
        align-items: center;
        justify-content: center;
        padding: 0.25rem 0;
    }
    .nav-link i {
        font-size: 1.4rem;
        margin-bottom: 0;
    }
    .nav-link span {
        display: none;
    }
}
// ...existing code...
.container {
    max-width: 1200px;
    margin: 100px auto 30px;
    padding: 0 20px;
}

/* Header */
header {
    background-color: var(--primary-color);
    padding: 1rem 0;
    position: fixed;
    top: 0;
    width: 100%;
    z-index: 1000;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

header.scrolled {
    box-shadow: var(--shadow-md);
    padding: 0.75rem 0;
}

.logo-image {
    height: 32px;
    transition: var(--transition);
}

/* Barre de recherche */
.search-container {
    margin-bottom: 2rem;
    position: relative;
}

#search-input {
    width: 100%;
    padding: 0.875rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-full);
    font-size: 1rem;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

#search-input:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Sections */
.section-title {
    text-align: center;
    margin-bottom: 1.5rem;
    font-weight: 600;
    color: var(--text-primary);
    position: relative;
    padding-bottom: 0.5rem;
}

.section-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: var(--accent-color);
    border-radius: var(--radius-full);
}

/* Marques */
.brands-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.brand-card {
    background: var(--primary-color);
    border-radius: var(--radius-md);
    padding: 1.5rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--accent-color);
}

.brand-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 50%;
    margin: 0 auto;
    transition: var(--transition);
    border: 1px solid var(--border-color);
    padding: 0.5rem;
}

.brand-card:hover .brand-logo {
    transform: scale(1.05);
    border-color: var(--accent-color);
}

.brand-name {
    margin-top: 1rem;
    font-weight: 500;
    color: var(--text-primary);
}

/* Sélection */
.selection-section {
    display: none;
    animation: fadeIn 0.3s ease-out;
}

.selected-brand {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background-color: var(--background-light);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
}

.selected-brand-logo {
    width: 80px;
    height: 80px;
    object-fit: contain;
    margin-right: 1.5rem;
    border-radius: 50%;
    border: 1px solid var(--border-color);
    padding: 0.5rem;
    flex-shrink: 0;
}

.selected-brand-info h2 {
    margin: 0;
    font-size: 1.5rem;
    color: var(--text-primary);
}

.selected-brand-info p {
    margin: 0.25rem 0 0;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Couleurs */
.colors-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.color-card {
    background: var(--primary-color);
    border-radius: var(--radius-md);
    overflow: hidden;
    cursor: pointer;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.color-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
    border-color: var(--accent-color);
}

.color-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    transition: var(--transition);
}

.color-card:hover .color-image {
    transform: scale(1.03);
}

.color-info {
    padding: 1rem;
    text-align: center;
    font-weight: 500;
}

/* Tailles */
.sizes-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    justify-content: center;
    margin-top: 1.5rem;
}

.size-button {
    padding: 0.75rem 1.25rem;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background-color: var(--primary-color);
    cursor: pointer;
    transition: var(--transition);
    font-weight: 500;
    min-width: 60px;
    text-align: center;
}

.size-button:hover {
    background-color: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
    transform: translateY(-2px);
}

.size-button.active {
    background-color: var(--accent-color);
    color: white;
    border-color: var(--accent-color);
}

/* Détails produit */
.product-details {
    margin-top: 2rem;
    padding: 2rem;
    background-color: var(--primary-color);
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.product-image-large {
    width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: var(--radius-md);
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow-sm);
}

.product-details h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--text-primary);
}

.product-details p {
    margin-bottom: 0.75rem;
    color: var(--text-secondary);
}

.product-details strong {
    color: var(--text-primary);
    font-weight: 600;
}

.whatsapp-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    background-color: var(--success-color);
    color: white;
    padding: 0.875rem 1.5rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    margin-top: 1.5rem;
    font-weight: 500;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.whatsapp-button:hover {
    background-color: #0ea371;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Navigation */
.bottom-nav {
    background-color: var(--primary-color);
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    display: flex;
    justify-content: space-around;
    padding: 0.75rem 0;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    z-index: 1000;
}

.nav-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--text-secondary);
    text-decoration: none;
    padding: 0.5rem;
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.nav-link i {
    font-size: 1.25rem;
    margin-bottom: 0.25rem;
    transition: var(--transition);
}

.nav-link span {
    font-size: 0.75rem;
    font-weight: 500;
}

.nav-link:hover, .nav-link.active {
    color: var(--accent-color);
    transform: translateY(-3px);
}

.nav-link.active i {
    transform: scale(1.1);
}

/* Boutons de navigation */
.nav-button {
    padding: 0.75rem 1.5rem;
    background-color: var(--accent-color);
    color: white;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    margin-top: 1.5rem;
    font-weight: 500;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-button:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.nav-button i {
    font-size: 1rem;
}

/* Loaders */
.loader {
    border: 4px solid rgba(0, 0, 0, 0.1);
    border-top: 4px solid var(--accent-color);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
    margin: 0 auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 120px;
}

.loading-text {
    text-align: center;
    margin-top: 1rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Messages d'erreur/empty states */
.empty-state {
    text-align: center;
    padding: 2rem;
    color: var(--text-secondary);
    background: var(--background-light);
    border-radius: var(--radius-md);
    margin: 2rem 0;
}

.empty-state i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--border-color);
}

/* Responsive */
@media (max-width: 1024px) {
    .brands-container, .colors-container {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}

@media (max-width: 768px) {
    .container {
        margin-top: 80px;
    }
    
    .brands-container, .colors-container {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .brand-card, .color-card {
        padding: 1rem 0.5rem;
    }
    
    .brand-logo, .selected-brand-logo {
        width: 60px;
        height: 60px;
    }
    
    .color-image {
        height: 120px;
    }
    
    .product-details {
        padding: 1.5rem;
    }
    
    .selected-brand {
        padding: 1rem;
    }
    
    .selected-brand-logo {
        width: 60px;
        height: 60px;
        margin-right: 1rem;
    }
}

@media (max-width: 480px) {
    .brands-container, .colors-container {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    }
    
    .brand-name, .color-info {
        font-size: 0.875rem;
    }
    
    .section-title {
        font-size: 1.25rem;
    }
    
    .bottom-nav {
        padding: 0.5rem 0;
    }
    
    .nav-link i {
        font-size: 1.1rem;
    }
    
    .nav-link span {
        display: none;
    }
}
    </style>
</head>
<body>
    <header>
        <img src="kama2.ico" alt="Logo KAMA" class="logo-image">
    </header>

    <div class="container">
        <!-- Barre de recherche -->
        <div class="search-container">
            <input type="text" id="search-input" placeholder="Rechercher une marque...">
        </div>

        <!-- Liste des marques -->
        <div id="brands-section">
            <h2 style="text-align: center;">Marques de Chaussures</h2>
            <div class="brands-container" id="chaussures-brands">
                <?php foreach ($marquesChaussures as $marque): ?>
                    <div class="brand-card" data-type="chaussure" data-marque="<?= htmlspecialchars($marque['marque']) ?>">
                        <?php if (!empty($marque['logo'])): ?>
                            <img src="<?= $marque['logo'] ?>" class="brand-logo">
                        <?php else: ?>
                            <div class="brand-logo" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shoe-prints" style="font-size: 40px; color: #666;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="brand-name"><?= htmlspecialchars($marque['marque']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2 style="text-align: center; margin-top: 40px;">Marques de Sacs</h2>
            <div class="brands-container" id="sacs-brands">
                <?php foreach ($marquesSacs as $marque): ?>
                    <div class="brand-card" data-type="sac" data-marque="<?= htmlspecialchars($marque['marque']) ?>">
                        <?php if (!empty($marque['logo'])): ?>
                            <img src="<?= $marque['logo'] ?>" class="brand-logo">
                        <?php else: ?>
                            <div class="brand-logo" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-shopping-bag" style="font-size: 40px; color: #666;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="brand-name"><?= htmlspecialchars($marque['marque']) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sélection de couleur -->
        <div id="color-selection" class="selection-section">
            <div class="selected-brand">
                <img id="selected-brand-logo" class="selected-brand-logo">
                <div>
                    <h2 id="selected-brand-name"></h2>
                    <p id="selected-brand-type"></p>
                </div>
            </div>
            <h3 style="text-align: center;">Choisissez une couleur</h3>
            <div class="colors-container" id="colors-container"></div>
            <button class="nav-button" onclick="backToBrands()">Retour aux marques</button>
        </div>

        <!-- Sélection de taille (chaussures seulement) -->
        <div id="size-selection" class="selection-section">
            <h3 style="text-align: center;">Choisissez une taille pour <span id="selected-color-name"></span></h3>
            <div class="sizes-container" id="sizes-container"></div>
            <button class="nav-button" onclick="backToColors()">Retour aux couleurs</button>
        </div>

        <!-- Détails du produit -->
        <div id="product-details" class="selection-section">
            <div class="product-details">
                <img id="product-detail-image" class="product-image-large">
                <h3 id="product-detail-name"></h3>
                <p><strong>Marque:</strong> <span id="product-detail-brand"></span></p>
                <p><strong>Couleur:</strong> <span id="product-detail-color"></span></p>
                <p id="product-detail-size-container"><strong>Taille:</strong> <span id="product-detail-size"></span></p>
                <p><strong>Prix:</strong> <span id="product-detail-price"></span></p>
                <a id="whatsapp-link" class="whatsapp-button" target="_blank">
                    <i class="fab fa-whatsapp"></i> Commander sur whatsapp
                </a>
            </div>
            <button class="nav-button" onclick="backToSizes()">Retour aux tailles</button>
            <button class="nav-button" onclick="backToColors()">Retour aux couleurs</button>
        </div>
    </div>

    <nav class="bottom-nav">
        <a href="index.php" class="nav-link">
            <i class="fas fa-home"></i>
        </a>
        <a href="recherche.php" class="nav-link active">
            <i class="fas fa-search"></i>
        </a>
        <a href="chaussure.php" class="nav-link">
            <i class="fas fa-shoe-prints"></i>
        </a>
        <a href="sac.php" class="nav-link">
            <i class="fas fa-shopping-bag"></i>
        </a>
        <a href="3connexion.php" class="nav-link">
            <i class="fas fa-user"></i>
        </a>
    </nav>
  <br><br>
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
// Variables globales
let currentMarque = null;
let currentType = null;
let currentColor = null;
let currentSize = null;
let currentProductImage = null;
let currentProductPrice = null;

// Éléments DOM
const searchInput = document.getElementById('search-input');
const brandsSection = document.getElementById('brands-section');
const colorSelection = document.getElementById('color-selection');
const sizeSelection = document.getElementById('size-selection');
const productDetails = document.getElementById('product-details');
const selectedBrandLogo = document.getElementById('selected-brand-logo');
const selectedBrandName = document.getElementById('selected-brand-name');
const selectedBrandType = document.getElementById('selected-brand-type');
const colorsContainer = document.getElementById('colors-container');
const selectedColorName = document.getElementById('selected-color-name');
const sizesContainer = document.getElementById('sizes-container');
const productDetailImage = document.getElementById('product-detail-image');
const productDetailName = document.getElementById('product-detail-name');
const productDetailBrand = document.getElementById('product-detail-brand');
const productDetailColor = document.getElementById('product-detail-color');
const productDetailSize = document.getElementById('product-detail-size');
const productDetailPrice = document.getElementById('product-detail-price');
const productDetailSizeContainer = document.getElementById('product-detail-size-container');
const whatsappLink = document.getElementById('whatsapp-link');

// Détection du scroll pour l'header
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 10) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});

// Filtrer les marques
searchInput.addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    document.querySelectorAll('.brand-card').forEach(card => {
        const brandName = card.querySelector('.brand-name').textContent.toLowerCase();
        card.style.display = brandName.includes(searchTerm) ? 'block' : 'none';
    });
});

// Sélection d'une marque
document.querySelectorAll('.brand-card').forEach(card => {
    card.addEventListener('click', function() {
        currentMarque = this.dataset.marque;
        currentType = this.dataset.type;
        
        // Afficher la marque sélectionnée
        const logoImg = this.querySelector('img');
        if (logoImg) {
            selectedBrandLogo.src = logoImg.src;
            selectedBrandLogo.style.backgroundColor = 'transparent';
            selectedBrandLogo.innerHTML = '';
        } else {
            selectedBrandLogo.src = '';
            selectedBrandLogo.style.backgroundColor = '#f0f0f0';
            selectedBrandLogo.innerHTML = currentType === 'chaussure' ? 
                '<i class="fas fa-shoe-prints" style="font-size: 40px; color: #666;"></i>' : 
                '<i class="fas fa-shopping-bag" style="font-size: 40px; color: #666;"></i>';
        }
        
        selectedBrandName.textContent = currentMarque;
        selectedBrandType.textContent = currentType === 'chaussure' ? 'Chaussures' : 'Sacs';
        
        // Charger les couleurs
        loadColors();
        
        // Afficher la section des couleurs
        brandsSection.style.display = 'none';
        colorSelection.style.display = 'block';
        
        // Scroll vers le haut pour une meilleure UX
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

// Charger les couleurs disponibles
function loadColors() {
    // Afficher le loader
    colorsContainer.innerHTML = `
        <div class="loader-container">
            <div class="loader"></div>
        </div>
        <p class="loading-text">Chargement des couleurs...</p>
    `;
    
    // Encoder les paramètres pour l'URL
    const encodedMarque = encodeURIComponent(currentMarque);
    const encodedType = encodeURIComponent(currentType);
    
    fetch(`get_colors_with_images.php?marque=${encodedMarque}&type=${encodedType}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data); // Debug
            
            // Vérifier si la réponse contient une erreur
            if (data.error) {
                throw new Error(data.error);
            }
            
            colorsContainer.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(color => {
                    const colorCard = document.createElement('div');
                    colorCard.className = 'color-card';
                    colorCard.dataset.color = color.couleur;
                    
                    // Gestion des images base64
                    let imgSrc = '';
                    if (color.image) {
                        if (color.image.startsWith('data:image')) {
                            imgSrc = color.image;
                        } else {
                            // Si le préfixe manque, l'ajouter
                            imgSrc = `${color.image}`;
                        }
                    } else {
                        // Image par défaut si manquante
                        imgSrc = 'https://via.placeholder.com/150?text=Image+manquante';
                    }
                    
                    colorCard.innerHTML = `
                        <img src="${imgSrc}" alt="${color.couleur}" class="color-image" 
                             onerror="this.onerror=null;this.src='https://via.placeholder.com/150?text=Erreur+image'">
                        <div class="color-info">${color.couleur}</div>
                    `;
                    
                    colorCard.addEventListener('click', function() {
                        currentColor = color.couleur;
                        currentProductImage = imgSrc;
                        currentProductPrice = color.prix || 'Prix non disponible';
                        
                        if (currentType === 'chaussure') {
                            selectedColorName.textContent = color.couleur;
                            loadSizes();
                            colorSelection.style.display = 'none';
                            sizeSelection.style.display = 'block';
                        } else {
                            showProductDetails();
                            colorSelection.style.display = 'none';
                            productDetails.style.display = 'block';
                        }
                        
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                    
                    colorsContainer.appendChild(colorCard);
                });
            } else {
                showEmptyState(colorsContainer, 'Aucune couleur disponible', 'fa-palette');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showErrorState(colorsContainer, 'Erreur de chargement des couleurs', error.message);
        });
}

// Charger les tailles disponibles (chaussures seulement)
function loadSizes() {
    // Afficher le loader
    sizesContainer.innerHTML = `
        <div class="loader-container">
            <div class="loader"></div>
        </div>
        <p class="loading-text">Chargement des tailles...</p>
    `;
    
    const encodedMarque = encodeURIComponent(currentMarque);
    const encodedColor = encodeURIComponent(currentColor);
    
    fetch(`get_sizes.php?marque=${encodedMarque}&couleur=${encodedColor}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            sizesContainer.innerHTML = '';
            
            if (data && data.length > 0) {
                data.forEach(size => {
                    const sizeButton = document.createElement('button');
                    sizeButton.className = 'size-button';
                    sizeButton.textContent = size.taille;
                    
                    sizeButton.addEventListener('click', function() {
                        // Retirer la classe active de tous les boutons
                        document.querySelectorAll('.size-button').forEach(btn => {
                            btn.classList.remove('active');
                        });
                        
                        // Ajouter la classe active au bouton cliqué
                        this.classList.add('active');
                        
                        currentSize = size.taille;
                        showProductDetails();
                        sizeSelection.style.display = 'none';
                        productDetails.style.display = 'block';
                        
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                    
                    sizesContainer.appendChild(sizeButton);
                });
            } else {
                showEmptyState(sizesContainer, 'Aucune taille disponible', 'fa-ruler');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showErrorState(sizesContainer, 'Erreur de chargement des tailles', error.message);
        });
}

// Afficher les détails du produit
function showProductDetails() {
    // Afficher le loader pendant le chargement
    productDetails.innerHTML = `
        <div class="product-details">
            <div class="loader-container" style="height: 300px;">
                <div class="loader"></div>
            </div>
            <p class="loading-text">Chargement des détails du produit...</p>
        </div>
        <button class="nav-button" onclick="backToSizes()">Retour aux tailles</button>
        <button class="nav-button" onclick="backToColors()">Retour aux couleurs</button>
    `;
    
    // Simuler un léger délai pour le chargement (peut être supprimé en production)
    setTimeout(() => {
        const productType = currentType === 'chaussure' ? 'Chaussures' : 'Sac';
        const sizeInfo = currentType === 'chaussure' ? 
            `<p id="product-detail-size-container"><strong>Taille:</strong> <span id="product-detail-size">${currentSize}</span></p>` : 
            '<p id="product-detail-size-container" style="display: none;"></p>';
        
        const whatsappMessage = `Bonjour, je suis intéressé par ce produit:\n\n` +
            `*Marque:* ${currentMarque}\n` +
            `*Type:* ${productType}\n` +
            `*Couleur:* ${currentColor}\n` +
            (currentSize ? `*Taille:* ${currentSize}\n` : '') +
            (currentProductPrice ? `*Prix:* ${currentProductPrice}$\n` : '');
        
        productDetails.innerHTML = `
            <div class="product-details">
                <img id="product-detail-image" class="product-image-large" 
                     src="${currentProductImage}" 
                     onerror="this.onerror=null;this.src='https://via.placeholder.com/400?text=Image+non+disponible'">
                <h3 id="product-detail-name">${currentMarque} ${productType}</h3>
                <p><strong>Marque:</strong> <span id="product-detail-brand">${currentMarque}</span></p>
                <p><strong>Couleur:</strong> <span id="product-detail-color">${currentColor}</span></p>
                ${sizeInfo}
                <p><strong>Prix:</strong> <span id="product-detail-price">${currentProductPrice}</span></p>
                <a id="whatsapp-link" class="whatsapp-button" target="_blank" 
                   href="https://wa.me/<?= $whatsappNumero ?>?text=${encodeURIComponent(whatsappMessage)}">
                    <i class="fab fa-whatsapp"></i> Commander sur whatsapp
                </a>
            </div>
            <button class="nav-button" onclick="backToSizes()">Retour aux tailles</button>
            <button class="nav-button" onclick="backToColors()">Retour aux couleurs</button>
        `;
    }, 300);
}

// Fonction pour afficher un état vide
function showEmptyState(container, message, icon) {
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas ${icon}"></i>
            <p>${message}</p>
            <button onclick="history.back()" class="nav-button">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
        </div>
    `;
}

// Fonction pour afficher un état d'erreur
function showErrorState(container, title, errorDetail) {
    console.error(title + ':', errorDetail);
    container.innerHTML = `
        <div class="empty-state">
            <i class="fas fa-exclamation-triangle"></i>
            <p>${title}</p>
            <small>${errorDetail}</small>
            <div style="margin-top: 1rem;">
                <button onclick="window.location.reload()" class="nav-button">
                    <i class="fas fa-sync-alt"></i> Recharger
                </button>
                <button onclick="backToBrands()" class="nav-button" style="margin-left: 0.5rem;">
                    <i class="fas fa-home"></i> Accueil
                </button>
            </div>
        </div>
    `;
}

// Navigation
function backToBrands() {
    brandsSection.style.display = 'block';
    colorSelection.style.display = 'none';
    sizeSelection.style.display = 'none';
    productDetails.style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function backToColors() {
    colorSelection.style.display = 'block';
    sizeSelection.style.display = 'none';
    productDetails.style.display = 'none';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function backToSizes() {
    if (currentType === 'chaussure') {
        sizeSelection.style.display = 'block';
        productDetails.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } else {
        backToColors();
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Vérifier si des paramètres sont présents dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const marque = urlParams.get('marque');
    const type = urlParams.get('type');
    
    if (marque && type) {
        // Simuler un clic sur la marque correspondante
        const brandCard = document.querySelector(`.brand-card[data-marque="${marque}"][data-type="${type}"]`);
        if (brandCard) {
            brandCard.click();
        }
    }
});
</script>
</body>
</html>