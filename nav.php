<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>APSI</title>
</head>
<body>

<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
    <nav id="nav">
        <a href="/index.php" class="logo-container"><img class="logo" src="/img/APSI.png" alt="Logo APSI"></a>

        <div class="menu" id="mobile-menu">
            <ul>
                <li><a <?php if ($pagename == 'aboutUs.php') {echo ' class="active"';} ?> href="/aboutUs.php">Qui sommes-nous</a></li>
                <li><a <?php if ($pagename == 'professions.php') {echo ' class="active"';} ?> href="/professions.php">Nos métiers</a></li>
                <li><a <?php if ($pagename == 'references.php' || $pagename == 'reference.php') {echo ' class="active"';} ?>  href="/references.php">Nos références</a></li>
                <li><a <?php if ($pagename == 'clients.php') {echo ' class="active"';} ?> href="/clients.php">Nos clients</a></li>
                <li><a <?php if ($pagename == 'contact.php' || $pagename == 'mailSent.php') {echo ' class="active"';} ?> href="/contact.php">Nous contacter</a></li>
            </ul>
        </div>
        
        <div class="menu-icon" id="menu-toggle">
            <img src="/img/menu.png" alt="Menu" class="btn-menu">
        </div>

    </nav>

    <script>
        // Mobile menu toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            
            // Initial state - menu hidden on mobile
            if (window.innerWidth <= 1200) {
                mobileMenu.classList.add('menu-hidden');
            }
            
            // Toggle menu visibility
            menuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('menu-hidden');
                mobileMenu.classList.toggle('menu-visible');
            });
            
            // Close menu when clicking on a link
            const menuLinks = document.querySelectorAll('.menu a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1200) {
                        mobileMenu.classList.add('menu-hidden');
                        mobileMenu.classList.remove('menu-visible');
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 1200) {
                    mobileMenu.classList.remove('menu-hidden');
                    mobileMenu.classList.remove('menu-visible');
                } else if (!mobileMenu.classList.contains('menu-visible')) {
                    mobileMenu.classList.add('menu-hidden');
                }
            });
        });
    </script>
</body>
</html>

