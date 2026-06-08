<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/css/nav.css">
</head>
<body>

<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
    <nav id="nav" class="hidden-until-loaded">
        <a href="/" class="logo-wrapper"><img class="logo" src="/img/APSI.png" alt="APSI BTP - Vos projets en toute sérénité"></a>

        <div class="menu">
            <ul>
                <li><a <?php if ($pagename == 'aboutUs.php') {echo ' class="active"';} ?> href="/aboutUs">Qui sommes-nous</a></li>
                <li><a <?php if ($pagename == 'professions.php') {echo ' class="active"';} ?> href="/professions">Nos métiers</a></li>
                <li><a <?php if ($pagename == 'references.php' || $pagename == 'reference.php') {echo ' class="active"';} ?>  href="/references">Nos références</a></li>
                <li><a <?php if ($pagename == 'clients.php') {echo ' class="active"';} ?> href="/clients">Nos clients</a></li>
                <li><a <?php if ($pagename == 'contact.php' || $pagename == 'mailSent.php') {echo ' class="active"';} ?> href="/contact">Nous contacter</a></li>
            </ul>
        </div>
        <a href="/" class="a-btn-menu"><img src="/img/menu.png" alt="Menu de navigation" class="btn-menu"></a>

    </nav>
</body>
</html>

