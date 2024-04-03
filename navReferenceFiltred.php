<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="css/navReferenceFiltred.css">
</head>
<body>

<?php $pagename = basename($_SERVER['PHP_SELF']); ?>
    <nav id="nav">

        <a href="/index.php"><img class="logo" src="img/APSI.png" alt=""></a>
        <div class="filtre">
            <a href="./NosRéférences.php">
                <svg style="margin: -5px" width="32px" height="32px" viewBox="0 0 25 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.7457 3.32851C20.3552 2.93798 19.722 2.93798 19.3315 3.32851L12.0371 10.6229L4.74275 3.32851C4.35223 2.93798 3.71906 2.93798 3.32854 3.32851C2.93801 3.71903 2.93801 4.3522 3.32854 4.74272L10.6229 12.0371L3.32856 19.3314C2.93803 19.722 2.93803 20.3551 3.32856 20.7457C3.71908 21.1362 4.35225 21.1362 4.74277 20.7457L12.0371 13.4513L19.3315 20.7457C19.722 21.1362 20.3552 21.1362 20.7457 20.7457C21.1362 20.3551 21.1362 19.722 20.7457 19.3315L13.4513 12.0371L20.7457 4.74272C21.1362 4.3522 21.1362 3.71903 20.7457 3.32851Z" fill="#0F0F0F"/>
                </svg>
            </a>
            <a style="margin-left: 10px" href="./NosRéférences.php">Filtrer</a>
        </div>

        <form action="" method="post" style="display: flex">

            <input type="text" name="commune" placeholder="Commune">

            <button type="submit" style="border: none; background: none;  cursor:pointer;">
            <svg style="margin-left: 20px" width="32px" height="32px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="Dribbble-Light-Preview" transform="translate(-179.000000, -280.000000)" fill="#000000">
                        <g id="icons" transform="translate(56.000000, 160.000000)">
                            <path d="M128.93985,132.929 L130.42455,134.343 L124.4847,140 L123,138.586 L128.93985,132.929 Z M136.65,132 C133.75515,132 131.4,129.757 131.4,127 C131.4,124.243 133.75515,122 136.65,122 C139.54485,122 141.9,124.243 141.9,127 C141.9,129.757 139.54485,132 136.65,132 L136.65,132 Z M136.65,120 C132.5907,120 129.3,123.134 129.3,127 C129.3,130.866 132.5907,134 136.65,134 C140.7093,134 144,130.866 144,127 C144,123.134 140.7093,120 136.65,120 L136.65,120 Z" id="search_right-[#1507]">
                            </path>
                        </g>
                    </g>
                </g>
            </svg>
            </button>
        </form>



        <a href="/index.php" class="a-btn-menu"><img src="img/menu.png" alt="" class="btn-menu"></a>

    </nav>
</body>
</html>

