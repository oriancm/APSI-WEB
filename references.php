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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/references.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:bold">
</head>
<body>

    <?php include "./nav.php"; ?>
    
    <main id="main" class="scrolled">
        <div class="references-container">
        <h1 id="references-title">Nos Références</h1>
                <section id="references-section">
                <?php foreach($refTab as $ref): ?>
                    <article>
                    <a class="card-link" href="reference.php?id=<?= $ref['id'] ?>"></a>
                        <div>
                            <?php
                                foreach($picTab as $pic) {
                                    if($pic['idR'] == $ref['id'] && $pic['orderPic'] == 1) {
                                        $picOfRef = $pic;
                                    }
                                }
                            ?>
                            <div>
                                <img class="image-ref" src="pic/<?= $picOfRef["titre"]; ?>" alt="">
                            </div>
                            <div class="desc-ref">
                                <div class="text-wrapper"><p><?= $ref["titre"]; ?></p></div>
                                <div class="text-wrapper"><p class="commune"><?= $ref["commune"]; ?></p></div>
                            </div>
                        </div>
                    </article>
                <?php endforeach ?>
                </section>
                <div class="pb">
                    <p class="slogan">APSI BTP, vos projets en toute sérénité…</p>
                </div>
                
        </div>
        
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
    setTimeout(updateTitleWidth, 100);
    
    // Update on resize
    window.addEventListener('resize', function() {
        setTimeout(updateTitleWidth, 100);
    });
});
</script>