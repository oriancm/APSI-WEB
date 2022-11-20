<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
    <nav>
        <a href="/index.php"><img class="logo" src="img/APSI.png" alt=""></a>

        <div class="menu">
            <ul>
                <li ><a <?php if ($pagename == 'QuiSommesNous.php') {echo ' class="active"';} ?> href="./QuiSommesNous.php">Qui sommes-nous</a></li>
                <li><a <?php if ($pagename == 'NosMétiers.php') {echo ' class="active"';} ?> href="./NosMétiers.php">Nos métiers</a></li>
                <li><a <?php if ($pagename == 'NosRéférences.php') {echo ' class="active"';} ?>  href="./NosRéférences.php">Nos références</a></li>
                <li><a <?php if ($pagename == 'NosClients.php') {echo ' class="active"';} ?> href="./NosClients.php">Nos clients</a></li>
                <li><a <?php if ($pagename == 'NousContacter.php') {echo ' class="active"';} ?> href="./NousContacter.php">Nous contacter</a></li>
            </ul>
        </div>
        <img src="img/menu.png" alt="" class="btn-menu">
    </nav>

</body>
</html>