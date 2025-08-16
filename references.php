<?php

require('admin/db.php');
require('functions/htmlPrint.php');

function getAllRef($db) {
    $sql = "SELECT * FROM reference";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllPic($db) {
    $sql = "SELECT * FROM photo ORDER BY orderPic ASC";
    $stmt= $db->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$refTab = getAllRef($db);
$picTab = getAllPic($db);

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <title>Nos Références - APSI BTP | Projets BTP en Provence-Alpes-Côte d'Azur</title>
    <meta name="description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX). Plus de 100 projets BTP réalisés en PACA.">
    <meta name="keywords" content="références BTP, projets construction, OPC, maîtrise d'œuvre, Provence, PACA, chantier, réhabilitation">
    <meta name="author" content="APSI BTP">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://apsi-btp.fr/references">
    <meta property="og:title" content="Nos Références - APSI BTP">
    <meta property="og:description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX).">
    <meta property="og:image" content="/img/APSI.png">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://apsi-btp.fr/references">
    <meta property="twitter:title" content="Nos Références - APSI BTP">
    <meta property="twitter:description" content="Découvrez nos références en Ordonnancement Pilotage Coordination (OPC) et Maîtrise d'Œuvre d'Exécution (MOEX).">
    <meta property="twitter:image" content="/img/APSI.png">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <link rel="stylesheet" href="/css/references.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <!-- Iconscout Link For Icons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
      .hidden-until-loaded {
        opacity: 0;
        transition: opacity 0.3s;
      }
      .show-after-load {
        opacity: 1 !important;
      }
    </style>
</head>
<body>

    <?php include "./nav.php"; ?>
    
    <main id="main" class="scrolled hidden-until-loaded">
        <section class="flex-column">
        <div class="references-container">
            <!-- Fixed Filter Button -->
            <div class="fixed-filter">
                <div class="filter-trigger">
                    <i class="uil uil-filter"></i>
                </div>
                <div class="filter-panel">
                    <div class="filter-header">
                        <span>Filtrer par domaine</span>
                        <i class="uil uil-times close-filter"></i>
                    </div>
                    <ul class="filter-options"></ul>
                </div>
            </div>
            
            <h1 id="references-title">Nos Références</h1>
                
                <!-- Message affiché quand aucune référence n'est trouvée -->
                <div class="no-references-message" id="no-references-message">
                    Aucune référence trouvée pour ce domaine.
                </div>
                
                <section id="references-section">
                <?php foreach($refTab as $ref): ?>
                    <article data-domain="<?= isset($ref['domaine']) ? $ref['domaine'] : '0' ?>">
                    <a class="card-link" href="/reference/<?= $ref['id'] ?>"></a>
                        <div>
                            <?php
                                foreach($picTab as $pic) {
                                    if($pic['idR'] == $ref['id'] && $pic['orderPic'] == 1) {
                                        $picOfRef = $pic;
                                    }
                                }
                            ?>
                            <div>
                                <img class="image-ref" src="/pic/<?= $picOfRef["titre"]; ?>" alt="">
                            </div>
                            <div class="desc-ref">
                                <div class="text-wrapper"><p><?= $ref["titre"]; ?></p></div>
                                <div class="text-wrapper"><p class="commune"><?= $ref["commune"]; ?></p></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach ?>
                </section>
                </div>
                <div class="pb">
                    <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
                </div>
        </section>                
                
        
        
    </main>
</body>
<link rel="stylesheet" href="/css/styleGlobalNotIndex.css">
</html>

<script>

    const article = document.querySelector('article');

    addEventListener('load', (event) => {
        var navElement = document.getElementById('nav');
        var navHeight = navElement.offsetHeight;
        // console.warn(navHeight);

        var mainElement = document.getElementById('main');
        mainElement.style.marginTop = navHeight + 'px';
    });

    document.addEventListener('DOMContentLoaded', function() {
    function updateTitleWidth() {
        const section = document.getElementById('references-section');
        const title = document.getElementById('references-title');
        
        // Get all articles (reference items)
        const articles = section.querySelectorAll('article');
        
        if (articles.length > 0) {
            // Find the leftmost and rightmost positions
            let minLeft = Infinity;
            let maxRight = 0;
            
            articles.forEach(article => {
                const rect = article.getBoundingClientRect();
                minLeft = Math.min(minLeft, rect.left);
                maxRight = Math.max(maxRight, rect.right);
            });
            
            // Calculate the total width of the displayed references
            const totalWidth = maxRight - minLeft;
            
            // Set the title width and center it
            title.style.width = totalWidth + 'px';
            title.style.marginLeft = 'auto';
            title.style.marginRight = 'auto';
        }
    }
    
    // Initial update
    // Add a slight delay to ensure layout is complete
    setTimeout(() => {
        updateTitleWidth();
    }, 100);
    
    // Update on resize
    window.addEventListener('resize', function() {
        setTimeout(() => {
            updateTitleWidth();
        }, 100);
    });
});

// Custom Select for Domain Filter
const fixedFilter = document.querySelector(".fixed-filter"),
filterTrigger = fixedFilter.querySelector(".filter-trigger"),
filterPanel = fixedFilter.querySelector(".filter-panel"),
closeFilter = fixedFilter.querySelector(".close-filter"),
filterOptions = fixedFilter.querySelector(".filter-options");

let domains = [
    "Tous les domaines",
    "Résidences Universitaires", 
    "Équipements sportifs", 
    "Équipements culturels", 
    "Groupes scolaires et collèges", 
    "Aménagements urbains", 
    "Bâtiments publics", 
    "Crèches", 
    "Centre d'Incendie et de Secours", 
    "Santé", 
    "Logements sociaux", 
    "Monuments Historiques", 
    "Restructuration et réhabilitation"
];

// Mapping between domain names and their numeric values in database
const domainMapping = {
    "Tous les domaines": "all",
    "Résidences Universitaires": "1", 
    "Équipements sportifs": "2", 
    "Équipements culturels": "3", 
    "Groupes scolaires et collèges": "4", 
    "Aménagements urbains": "5", 
    "Bâtiments publics": "6", 
    "Crèches": "7", 
    "Centre d'Incendie et de Secours": "8", 
    "Santé": "9", 
    "Logements sociaux": "10", 
    "Monuments Historiques": "11", 
    "Restructuration et réhabilitation": "12"
};

function addDomain(selectedDomain) {
    filterOptions.innerHTML = "";
    domains.forEach(domain => {
        let isSelected = domain == selectedDomain ? "selected" : "";
        let li = `<li onclick="updateDomainName(this)" class="${isSelected}">${domain}</li>`;
        filterOptions.insertAdjacentHTML("beforeend", li);
    });
}
addDomain();

function updateDomainName(selectedLi) {
    addDomain(selectedLi.innerText);
    fixedFilter.classList.remove("active");
    
    // Filter references based on selected domain
    filterReferences(selectedLi.innerText);
}

function filterReferences(selectedDomain) {
    const articles = document.querySelectorAll('#references-section article');
    const domainValue = domainMapping[selectedDomain];
    const noReferencesMessage = document.getElementById('no-references-message');
    let visibleArticles = 0;
    
    articles.forEach(article => {
        const articleDomain = article.getAttribute('data-domain');
        
        if (domainValue === "all") {
            // Show all articles
            article.style.display = 'block';
            visibleArticles++;
        } else {
            // Show only articles matching the selected domain
            if (articleDomain === domainValue) {
                article.style.display = 'block';
                visibleArticles++;
            } else {
                article.style.display = 'none';
            }
        }
    });
    
    // Afficher/masquer le message "aucune référence"
    if (visibleArticles === 0) {
        noReferencesMessage.classList.add('show');
    } else {
        noReferencesMessage.classList.remove('show');
    }
    
    // Update title width after filtering
    setTimeout(() => {
        updateTitleWidth();
    }, 100);
}

// Event listeners for opening and closing the filter
filterTrigger.addEventListener("click", () => {
    fixedFilter.classList.toggle("active");
});

closeFilter.addEventListener("click", () => {
    fixedFilter.classList.remove("active");
});

// Close filter when clicking outside
document.addEventListener("click", (e) => {
    if (!fixedFilter.contains(e.target)) {
        fixedFilter.classList.remove("active");
    }
});

window.addEventListener('load', function() {
    var navElement = document.getElementById('nav');
    var navHeight = navElement ? navElement.offsetHeight : 0;
    var mainElement = document.getElementById('main');
    if (mainElement) {
        mainElement.style.marginTop = navHeight + 'px';
        mainElement.classList.remove('hidden-until-loaded');
        mainElement.classList.add('show-after-load');
    }
    if (navElement) {
        navElement.classList.add('show-after-load');
    }
});
</script>