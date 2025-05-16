<?php
require('./db.php');
session_start();

if ($_SESSION["session"] != "valide") {
    header("Location:login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validations
    $errorsValidation = [];
    if (empty($_POST['titre'])) {
        $errorsValidation['titre'] = 'Erreur : Le titre est requis';
    }
    if (empty($_POST['villeChoisieHidden'])) {
        $errorsValidation['commune'] = 'Erreur : La commune est requise';
    }
    // if (empty($_POST['domaine'])) {
    //     $errorsValidation['domaine'] = 'Erreur : Le domaine est requis';
    // }

    if (empty($errorsValidation)) {
        $titre = ($_POST['titre']);
        $commune = ($_POST['villeChoisieHidden']);
        $pics = normalizeFiles();
        $description = empty($_POST['description']) ? null : ($_POST['description']);
        $domaine = ($_POST['domaine']);
        $anneeD = empty($_POST['anneeD']) ? null : ($_POST['anneeD']);
        $anneeF = empty($_POST['anneeF']) ? null : ($_POST['anneeF']);
        $moa = empty($_POST['moa']) ? null : ($_POST['moa']);
        $statut = empty($_POST['statut']) ? null : ($_POST['statut']);
        $archi = empty($_POST['archi']) ? null : ($_POST['archi']);
        $eMoe = empty($_POST['eMoe']) ? null : ($_POST['eMoe']);
        $nbPhase = empty($_POST['nbPhase']) ? null : ($_POST['nbPhase']);
        $montant = empty($_POST['montant']) ? null : ($_POST['montant']);
        $nbE = empty($_POST['nbE']) ? null : ($_POST['nbE']);
        $duree_travaux_mois = empty($_POST['duree_travaux_mois']) ? null : ($_POST['duree_travaux_mois']);
        $nombre_lots = empty($_POST['nombre_lots']) ? null : ($_POST['nombre_lots']);

        $sql = "INSERT INTO reference (titre, commune, domaine, description, anneeD, anneeF, statut, moa, archi, eMoe, nbPhase, montant, nbE, duree_travaux_mois, nombre_lots)";
        $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$titre, $commune, $domaine, $description, $anneeD, $anneeF, $statut, $moa, $archi, $eMoe, $nbPhase, $montant, $nbE, $duree_travaux_mois, $nombre_lots])) {
        $idRef = $db->lastInsertId(); // Stocker l'ID maintenant
        $message = "<div class='...'>SUCCÈS: Informations du chantier $idRef enregistrées avec succès</div>";

        if (!empty($_FILES['images']) && isset($_POST['picOrder'])) {
            $files = $_FILES['images'];
            $orderPic = 1;  // Commencer par la position 1
            
            // Récupérer l'ordre des images à partir de picOrder[]
            $picOrderList = isset($_POST['picOrder']) ? $_POST['picOrder'] : [];
            $fileMap = [];

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === 0) {
                    $fileMap[$files['name'][$i]] = [
                        'name' => $files['name'][$i],
                        'tmp_name' => $files['tmp_name'][$i],
                        'type' => $files['type'][$i],
                        'error' => $files['error'][$i],
                        'size' => $files['size'][$i]
                    ];
                }
            }

            foreach ($picOrderList as $fileName) {
                if (isset($fileMap[$fileName]) && $fileMap[$fileName]['error'] === 0) {
                    $pic = $fileMap[$fileName];
                    $extPic = pathinfo($pic['name'], PATHINFO_EXTENSION);
                    $extValid = ['jpg', 'jpeg', 'png'];
                    $localPath = dirname(__DIR__);
                    $uploadDir = $localPath . '/pic/';
                    $uploadPath = $uploadDir . $pic['name'];

                    if (in_array(strtolower($extPic), $extValid) && move_uploaded_file($pic['tmp_name'], $uploadPath)) {
                        $sql = "INSERT INTO photo (titre, dir, idR, orderPic) VALUES (?, ?, ?, ?)";
                        $stmt = $db->prepare($sql);
                        if (!$stmt->execute([$pic['name'], $uploadPath, $idRef, $orderPic])) {
                            $message .= "<div class='...'>Erreur BDD pour l'image " . htmlspecialchars($pic['name']) . "</div>";
                        }
                        $orderPic++;
                    } else {
                        $message .= "<div class='...'>Erreur lors du traitement de l'image " . htmlspecialchars($pic['name']) . "</div>";
                    }
                }
            }
        }}}
    // ---- FIN gestion des images ----

    // Redirection une fois tout terminé
    header("Location: add.php?success=" . urlencode("Informations du chantier $idRef enregistrées avec succès"));
    exit;
}



if (isset($_GET['success'])) {
    $message = "<div class='bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4'>SUCCÈS: " . htmlspecialchars($_GET['success']) . "</div>";
}

function dernierChantier($db) {
    $sql = "SELECT MAX(id) FROM reference";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res['MAX(id)'] ?? 0;
}

function normalizeFiles(string $fieldName = 'images'): array
{
    $out = [];
    if (isset($_FILES[$fieldName]) && is_array($_FILES[$fieldName])) {
        foreach ($_FILES[$fieldName] as $key => $values) {
            foreach ($values as $index => $value) {
                $out[$index][$key] = $value;
            }
        }
    }
    return $out;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ajouter une Référence</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/css/add.css" rel="stylesheet">
    <style>
        label {
            @apply block text-sm font-medium text-gray-700 mb-1;
        }
        input, select, textarea {
            @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50;
        }
        input[type="radio"] {
            @apply mt-0 inline-block;
        }
        .radio-group {
            @apply flex space-x-4;
        }
        .error {
            @apply text-red-500 text-sm mt-1;
        }
        .submit-btn {
            @apply bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
        .form-container {
            @apply bg-white p-6 rounded-lg shadow-md;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <main class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <a href="./admin.php" class="text-blue-600 hover:underline">Espace administrateur</a>
        </h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ajout d'une référence (Référence n°<?= (dernierChantier($db) + 1) ?>)</h2>

        <?php if (isset($message)) echo $message; ?>

        <form method="POST" action="add.php" enctype="multipart/form-data" class="form-container space-y-6">
            <div class="mb-4">
                <label for="titre">Titre *</label>
                <input type="text" name="titre" id="titre" required class="border <?php echo isset($errorsValidation['titre']) ? 'border-red-500' : ''; ?>">
                <?php if (isset($errorsValidation['titre'])): ?>
                    <p class="error"><?php echo $errorsValidation['titre']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="code">Commune *</label>
                <div class="flex space-x-4">
                    <input data-search-code id="code" placeholder="Code postal" class="flex-1">
                    <input data-search-city id="ville" placeholder="Ville" class="flex-1">
                </div>
                <input type="hidden" id="villeChoisieHidden" name="villeChoisieHidden">
                <ul id="result-code-or-city" class="mt-2" style="display: none;"></ul>
                <?php if (isset($errorsValidation['commune'])): ?>
                    <p class="error"><?php echo $errorsValidation['commune']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="description">Description</label></br>
                <textarea name="description" id="description" cols="30" rows="5"></textarea>
            </div>
            <div class="mb-4">
                <label for="domaine">Domaine d'activités</label>
                <select name="domaine" id="domaine" class="border <?php echo isset($errorsValidation['domaine']) ? 'border-red-500' : ''; ?>">
                    <option value="">Choisir le domaine</option>
                    <option value="1">Résidence Universitaire</option>
                    <option value="2">Équipements sportifs</option>
                    <option value="3">Équipements culturels</option>
                    <option value="4">Groupes scolaires et collèges</option>
                    <option value="5">Aménagements urbains</option>
                    <option value="6">Bâtiments publics</option>
                    <option value="7">Crèches</option>
                    <option value="8">Centre d'Incendie et de Secours</option>
                    <option value="9">Hôpitaux publics</option>
                    <option value="10">Logements sociaux</option>
                    <option value="11">Monuments Historiques et bâtiments à caractère patrimonial</option>
                    <option value="12">Restructuration et réhabilitation en site occupé (phasage)</option>
                </select>
                <?php if (isset($errorsValidation['domaine'])): ?>
                    <p class="error"><?php echo $errorsValidation['domaine']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="statut">Statut</label>
                <div class="radio-group">
                    <div>
                        <input type="radio" name="statut" id="statut1" value="1">
                        <label for="statut1" class="inline-block ml-2">Opération livrée</label>
                    </div>
                    <div>
                        <input type="radio" name="statut" id="statut2" value="2">
                        <label for="statut2" class="inline-block ml-2">Travaux en cours</label>
                    </div>
                    <div>
                        <input type="radio" name="statut" id="statut3" value="3">
                        <label for="statut3" class="inline-block ml-2">Conception en cours</label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="moa">Maître d'ouvrage</label>
                <input type="text" name="moa" id="moa">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="anneeD">Année de Début</label>
                    <input type="number" name="anneeD" id="anneeD" placeholder="YYYY" min="1900" max="2099">
                </div>
                <div>
                    <label for="anneeF">Année de Fin</label>
                    <input type="number" name="anneeF" id="anneeF" placeholder="YYYY" min="1900" max="2099">
                </div>
                <div>
                    <label for="duree_travaux_mois">Durée des travaux (mois)</label>
                    <input type="number" name="duree_travaux_mois" id="duree_travaux_mois" min="0">
                </div>
                <div>
                    <label for="montant">Montant des travaux (€)</label>
                    <input type="number" name="montant" id="montant" step="0.01" min="0">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="nbE">Nombre d'entreprises</label>
                    <input type="number" name="nbE" id="nbE" min="0">
                </div>
                <div>
                    <label for="nombre_lots">Nombre de lots</label>
                    <input type="number" name="nombre_lots" id="nombre_lots" min="0">
                </div>
            </div>
            <div class="mb-4">
                <label for="image-upload">Images *</label>
                <input type="file" id="image-upload" name="images[]" multiple accept="image/jpeg,image/png" class="mt-1 block w-full">
                <p class="text-sm text-gray-500">Faites glisser les images pour changer l'ordre</p>
                <div id="image-container" class="image-container mb-4 flex flex-wrap mt-2 border border-gray-300 p-4 rounded">
                    <p id="no-images" class="text-gray-400 w-full text-center">Aucune image sélectionnée</p>
                </div>
                <div id="images-data"></div> <!-- hidden inputs générés dynamiquement ici -->
            </div>
            <div class="text-right">
                <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Valider</button>
            </div>
        </form>
    </main>

    <script src="../script/search-cities.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Les références aux éléments du DOM
    let imageUpload = document.getElementById('image-upload');
    const imageContainer = document.getElementById('image-container');
    const imagesData = document.getElementById('images-data');
    let images = [];
    let draggedItem = null;

    // Fonction pour vider complètement le champ de fichier
    function clearFileInput() {
        try {
            // Cloner le champ avec une copie vide
            const newInput = imageUpload.cloneNode(true);
            newInput.value = '';
            
            // Remplacer l'ancien champ par le nouveau
            imageUpload.parentNode.replaceChild(newInput, imageUpload);
            imageUpload = newInput;
            
            // Réattacher l'écouteur d'événement
            imageUpload.addEventListener('change', handleFileChange);
        } catch (e) {
            console.error("Erreur lors de la réinitialisation du champ de fichier:", e);
        }
    }

    // Fonction pour gérer la sélection de fichiers
    function handleFileChange(e) {
        const files = Array.from(e.target.files);
        
        if (files.length > 0) {
            files.forEach(file => {
                if (file.type.match('image/jpeg') || file.type.match('image/png')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Vérifier si cette image existe déjà (par nom)
                        const exists = images.some(img => img.file.name === file.name);
                        if (!exists) {
                            images.push({ file, preview: e.target.result });
                            displayImages();
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }

    // Fonction pour afficher les images
    function displayImages() {
        // Vider le conteneur d'images
        imageContainer.innerHTML = '';
        imagesData.innerHTML = '';

        // Si aucune image, afficher le texte "Aucune image sélectionnée"
        if (images.length === 0) {
            const noImages = document.createElement('p');
            noImages.id = 'no-images';
            noImages.className = 'text-gray-400 w-full text-center';
            noImages.textContent = 'Aucune image sélectionnée';
            imageContainer.appendChild(noImages);
            return;
        }

        // Afficher chaque image
        images.forEach((image, index) => {
            const imageItem = document.createElement('div');
            imageItem.className = 'image-item';
            imageItem.style.backgroundImage = `url(${image.preview})`;
            imageItem.setAttribute('data-index', index);

            // Numéro d'ordre
            const orderNumber = document.createElement('div');
            orderNumber.className = 'order-number';
            orderNumber.textContent = index + 1;
            imageItem.appendChild(orderNumber);

            // Bouton de suppression
            const deleteBtn = document.createElement('div');
            deleteBtn.className = 'delete-btn';
            deleteBtn.textContent = '×';
            deleteBtn.onclick = function(e) {
                e.stopPropagation();
                images.splice(index, 1);
                displayImages();
                // Réinitialiser le champ fichier après suppression
                clearFileInput();
            };
            imageItem.appendChild(deleteBtn);

            // Événements de glisser-déposer
            imageItem.draggable = true;
            imageItem.addEventListener('dragstart', handleDragStart);
            imageItem.addEventListener('dragend', handleDragEnd);
            imageItem.addEventListener('dragover', handleDragOver);
            imageItem.addEventListener('dragenter', handleDragEnter);
            imageItem.addEventListener('dragleave', handleDragLeave);
            imageItem.addEventListener('drop', handleDrop);

            imageContainer.appendChild(imageItem);

            // Créer un champ caché pour chaque image pour l'ordre
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `picOrder[]`;
            hiddenInput.value = image.file.name;
            imagesData.appendChild(hiddenInput);
        });
    }

    // Attacher l'écouteur d'événement au champ de fichier
    imageUpload.addEventListener('change', handleFileChange);

    // Fonctions pour le glisser-déposer
    function handleDragStart(e) {
        this.classList.add('dragging');
        draggedItem = this;
        e.dataTransfer.effectAllowed = 'move';
    }

    function handleDragEnd(e) {
        this.classList.remove('dragging');
        draggedItem = null;
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    function handleDragEnter(e) {
        this.classList.add('bg-blue-100');
    }

    function handleDragLeave(e) {
        this.classList.remove('bg-blue-100');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('bg-blue-100');
        
        if (draggedItem !== this) {
            const fromIndex = parseInt(draggedItem.getAttribute('data-index'));
            const toIndex = parseInt(this.getAttribute('data-index'));
            
            // Déplacer l'élément dans le tableau d'images
            const movedItem = images.splice(fromIndex, 1)[0];
            images.splice(toIndex, 0, movedItem);
            
            // Mettre à jour l'affichage
            displayImages();
        }
    }

    // Ajouter un écouteur d'événement au formulaire pour reconstruire le tableau de fichiers avant l'envoi
    document.querySelector('form').addEventListener('submit', function(e) {
        if (images.length === 0) {
            return; // Ne rien faire s'il n'y a pas d'images
        }

        // Créer un objet FormData temporaire pour reconstruire le fichier
        const formData = new FormData();
        
        // Ajouter chaque fichier dans l'ordre actuel
        images.forEach((image, index) => {
            formData.append('images[]', image.file);
        });
        
        // Note: Les fichiers seront envoyés avec le formulaire normal,
        // les champs picOrder[] indiquent l'ordre correct
    });
});    </script>

</body>
</html>