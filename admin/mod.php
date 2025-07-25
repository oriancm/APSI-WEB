<?php
require('./db.php');
session_start();

if ($_SESSION["session"] != "valide") {
    header("Location:login");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de la référence
$sql = "SELECT * FROM reference WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);
$reference = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reference) {
    header("Location: admin?error=" . urlencode("La référence avec l'ID $id n'existe pas."));
    exit;
}

// Récupérer les photos associées à la référence
$sql = "SELECT * FROM photo WHERE idR = ? ORDER BY orderPic";
$stmt = $db->prepare($sql);
$stmt->execute([$id]);
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging: Output warning if no photos are found
if (empty($photos)) {
    $message = isset($message) ? $message . "<div style='background-color: #fefcbf; border-left: 4px solid #f6ad55; color: #d97706; padding: 1rem; margin-bottom: 1rem;'>AVERTISSEMENT: Aucune image existante trouvée pour la référence $id</div>" : "<div style='background-color: #fefcbf; border-left: 4px solid #f6ad55; color: #d97706; padding: 1rem; margin-bottom: 1rem;'>AVERTISSEMENT: Aucune image existante trouvée pour la référence $id</div>";
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

    if (empty($errorsValidation)) {
        $titre = ($_POST['titre']);
        $commune = ($_POST['villeChoisieHidden']);
        $description = empty($_POST['description']) ? null : ($_POST['description']);
        $domaine = empty($_POST['domaine']) ? null : (int)$_POST['domaine'];
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

        $sql = "UPDATE reference SET titre = ?, commune = ?, domaine = ?, description = ?, anneeD = ?, 
                anneeF = ?, statut = ?, moa = ?, archi = ?, eMoe = ?, nbPhase = ?, montant = ?, 
                nbE = ?, duree_travaux_mois = ?, nombre_lots = ? WHERE id = ?";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$titre, $commune, $domaine, $description, $anneeD, $anneeF, $statut, $moa, 
                          $archi, $eMoe, $nbPhase, $montant, $nbE, $duree_travaux_mois, $nombre_lots, $id])) {
            $message = "<div style='background-color: #dcfce7; border-left: 4px solid #22c55e; color: #15803d; padding: 1rem; margin-bottom: 1rem;'>SUCCÈS: Informations du chantier $id modifiées avec succès</div>";

            // Handle image deletions
            $delete_photos = $_POST['delete_photos'] ?? [];
            foreach ($delete_photos as $photo) {
                if (strpos($photo, 'new_') === 0) {
                    // New image deletion (handled client-side)
                    continue;
                }
                // Delete existing image
                $stmt = $db->prepare("SELECT dir FROM photo WHERE id = ? AND idR = ?");
                $stmt->execute([$photo, $id]);
                $photo_data = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($photo_data) {
                    $file_path = $_SERVER['DOCUMENT_ROOT'] . $photo_data['dir'];
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                    $stmt = $db->prepare("DELETE FROM photo WHERE id = ? AND idR = ?");
                    $stmt->execute([$photo, $id]);
                }
            }

            // Handle image order and new uploads
            $order = $_POST['order'] ?? [];
            $new_images = $_FILES['images']['name'] ?? [];
            $new_images_tmp = $_FILES['images']['tmp_name'] ?? [];
            $uploaded_files = [];

            // Process new images
            foreach ($new_images as $index => $name) {
                if ($name && $new_images_tmp[$index]) {
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $new_name = uniqid() . '.' . $ext;
                    $destination = $_SERVER['DOCUMENT_ROOT'] . '/pic/' . $new_name;
                    if (move_uploaded_file($new_images_tmp[$index], $destination)) {
                        $uploaded_files[$name] = '/pic/' . $new_name;
                    }
                }
            }

            // Update order and insert new images
            foreach ($order as $index => $item) {
                if (strpos($item, 'existing_') === 0) {
                    $photo_id = str_replace('existing_', '', $item);
                    $stmt = $db->prepare("UPDATE photo SET orderPic = ? WHERE id = ? AND idR = ?");
                    $stmt->execute([$index + 1, $photo_id, $id]);
                } elseif (strpos($item, 'new_') === 0) {
                    $file_name = str_replace('new_', '', $item);
                    if (isset($uploaded_files[$file_name])) {
                        $stmt = $db->prepare("INSERT INTO photo (idR, dir, titre, orderPic) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$id, $uploaded_files[$file_name], $file_name, $index + 1]);
                    }
                }
            }

            // Refresh data for display
            $sql = "SELECT * FROM reference WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            $reference = $stmt->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT * FROM photo WHERE idR = ? ORDER BY orderPic";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            $photos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $message = "<div style='background-color: #fee2e2; border-left: 4px solid #ef4444; color: #b91c1c; padding: 1rem; margin-bottom: 1rem;'>ERREUR: Impossible de modifier les informations du chantier</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Modifier une Référence</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/css/add.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <main class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <a href="./admin" class="text-blue-600 hover:underline">Espace administrateur</a>
        </h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Modification de la référence n°<?= $id ?></h2>

        <?php if (isset($message)) echo $message; ?>

        <form method="POST" action="mod.php?id=<?= $id ?>" enctype="multipart/form-data" class="form-container space-y-6">
            <div class="mb-4">
                <label for="titre">Titre *</label>
                <input type="text" name="titre" id="titre" required value="<?= htmlspecialchars($reference['titre'] ?? '') ?>" class="border <?php echo isset($errorsValidation['titre']) ? 'border-red-500' : ''; ?>">
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
                <input type="hidden" id="villeChoisieHidden" name="villeChoisieHidden" value="<?= htmlspecialchars($reference['commune'] ?? '') ?>">
                <p class="text-blue-600 mt-1">Commune actuelle : <?= htmlspecialchars($reference['commune'] ?? 'Non définie') ?></p>
                <ul id="result-code-or-city" class="mt-2" style="display: none;"></ul>
                <?php if (isset($errorsValidation['commune'])): ?>
                    <p class="error"><?php echo $errorsValidation['commune']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="description">Description</label>
                <textarea name="description" id="description" cols="30" rows="5"><?= htmlspecialchars($reference['description'] ?? '') ?></textarea>
            </div>
            <div class="mb-4">
                <label for="domaine">Domaine d'activités</label>
                <select name="domaine" id="domaine" class="border <?php echo isset($errorsValidation['domaine']) ? 'border-red-500' : ''; ?>">
                    <option value="">Choisir le domaine</option>
                    <option value="1" <?= ($reference['domaine'] ?? '') == '1' ? 'selected' : '' ?>>Résidence Universitaire</option>
                    <option value="2" <?= ($reference['domaine'] ?? '') == '2' ? 'selected' : '' ?>>Équipements sportifs</option>
                    <option value="3" <?= ($reference['domaine'] ?? '') == '3' ? 'selected' : '' ?>>Équipements culturels</option>
                    <option value="4" <?= ($reference['domaine'] ?? '') == '4' ? 'selected' : '' ?>>Groupes scolaires et collèges</option>
                    <option value="5" <?= ($reference['domaine'] ?? '') == '5' ? 'selected' : '' ?>>Aménagements urbains</option>
                    <option value="6" <?= ($reference['domaine'] ?? '') == '6' ? 'selected' : '' ?>>Bâtiments publics</option>
                    <option value="7" <?= ($reference['domaine'] ?? '') == '7' ? 'selected' : '' ?>>Crèches</option>
                    <option value="8" <?= ($reference['domaine'] ?? '') == '8' ? 'selected' : '' ?>>Centre d'Incendie et de Secours</option>
                    <option value="9" <?= ($reference['domaine'] ?? '') == '9' ? 'selected' : '' ?>>Hôpitaux publics</option>
                    <option value="10" <?= ($reference['domaine'] ?? '') == '10' ? 'selected' : '' ?>>Logements sociaux</option>
                    <option value="11" <?= ($reference['domaine'] ?? '') == '11' ? 'selected' : '' ?>>Monuments Historiques et Sites Patrimoniaux</option>
                    <option value="12" <?= ($reference['domaine'] ?? '') == '12' ? 'selected' : '' ?>>Restructuration et réhabilitation en site occupé (phasage)</option>
                </select>
                <?php if (isset($errorsValidation['domaine'])): ?>
                    <p class="error"><?php echo $errorsValidation['domaine']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="statut">Statut</label>
                <div class="radio-group">
                    <div>
                        <input type="radio" name="statut" id="statut1" value="1" <?= ($reference['statut'] ?? '') == '1' ? 'checked' : '' ?>>
                        <label for="statut1" class="inline-block ml-2">Opération livrée</label>
                    </div>
                    <div>
                        <input type="radio" name="statut" id="statut2" value="2" <?= ($reference['statut'] ?? '') == '2' ? 'checked' : '' ?>>
                        <label for="statut2" class="inline-block ml-2">Travaux en cours</label>
                    </div>
                    <div>
                        <input type="radio" name="statut" id="statut3" value="3" <?= ($reference['statut'] ?? '') == '3' ? 'checked' : '' ?>>
                        <label for="statut3" class="inline-block ml-2">Conception en cours</label>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="moa">Maître d'ouvrage</label>
                <input type="text" name="moa" id="moa" value="<?= htmlspecialchars($reference['moa'] ?? '') ?>">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="anneeD">Année de Début</label>
                    <input type="number" name="anneeD" id="anneeD" placeholder="YYYY" min="1900" max="2099" value="<?= htmlspecialchars($reference['anneeD'] ?? '') ?>">
                </div>
                <div>
                    <label for="anneeF">Année de Fin</label>
                    <input type="number" name="anneeF" id="anneeF" placeholder="YYYY" min="1900" max="2099" value="<?= htmlspecialchars($reference['anneeF'] ?? '') ?>">
                </div>
                <div>
                    <label for="duree_travaux_mois">Durée des travaux (mois)</label>
                    <input type="number" name="duree_travaux_mois" id="duree_travaux_mois" min="0" value="<?= htmlspecialchars($reference['duree_travaux_mois'] ?? '') ?>">
                </div>
                <div>
                    <label for="montant">Montant des travaux (€)</label>
                    <input type="number" name="montant" id="montant" step="0.01" min="0" value="<?= htmlspecialchars($reference['montant'] ?? '') ?>">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="archi">Architecte</label>
                    <input type="text" name="archi" id="archi" value="<?= htmlspecialchars($reference['archi'] ?? '') ?>">
                </div>
                <div>
                    <label for="eMoe">Équipe MOE</label>
                    <input type="text" name="eMoe" id="eMoe" value="<?= htmlspecialchars($reference['eMoe'] ?? '') ?>">
                </div>
                <div>
                    <label for="nbPhase">Nombre de phases</label>
                    <input type="number" name="nbPhase" id="nbPhase" min="0" value="<?= htmlspecialchars($reference['nbPhase'] ?? '') ?>">
                </div>
                <div>
                    <label for="nbE">Nombre d'entreprises</label>
                    <input type="number" name="nbE" id="nbE" min="0" value="<?= htmlspecialchars($reference['nbE'] ?? '') ?>">
                </div>
                <div>
                    <label for="nombre_lots">Nombre de lots</label>
                    <input type="number" name="nombre_lots" id="nombre_lots" min="0" value="<?= htmlspecialchars($reference['nombre_lots'] ?? '') ?>">
                </div>
            </div>

            <!-- Unified Images Section -->
            <div class="mb-4">
                <label for="image-upload">Images</label>
                <input type="file" id="image-upload" name="images[]" multiple accept="image/jpeg,image/png" class="mt-1 block w-full">
                <p class="text-sm text-gray-500">Faites glisser pour réorganiser, cochez pour supprimer, ou ajoutez de nouvelles images</p>
                <div id="image-container" class="image-container mb-4 flex flex-wrap mt-2">
                    <?php foreach ($photos as $index => $photo): ?>
                        <?php
                        $imagePath = '/pic/' . htmlspecialchars(basename($photo['dir']));
                        ?>
                        <div class="image-item" data-id="<?= $photo['id'] ?>" data-index="<?= $index ?>" data-type="existing" style="background-image: url('<?= $imagePath ?>'); width: 100px; height: 100px;" draggable="true">
                            <div class="order-number"><?= $index + 1 ?></div>
                            <label class="delete-checkbox-label">
                                <input type="checkbox" name="delete_photos[]" value="<?= $photo['id'] ?>" class="delete-checkbox">
                                Supprimer
                            </label>
                            <input type="hidden" name="order[]" value="existing_<?= $photo['id'] ?>">
                            <img src="<?= $imagePath ?>" style="display: none;" alt="Existing image">
                        </div>
                    <?php endforeach; ?>
                    <p id="no-images" class="text-gray-400 w-full text-center <?= !empty($photos) ? 'hidden' : '' ?>">Aucune image sélectionnée</p>
                </div>
                <div id="images-data"></div>
            </div>

            <div class="text-right">
                <a href="admin.php" class="mr-4 inline-block px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Annuler</a>
                <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Enregistrer les modifications</button>
            </div>
        </form>
    </main>

    <script src="../script/search-cities.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    let imageUpload = document.getElementById('image-upload');
    const imageContainer = document.getElementById('image-container');
    const imagesData = document.getElementById('images-data');
    const noImagesText = document.getElementById('no-images');
    let images = []; // Tracks new images
    let draggedItem = null;

    // Debugging: Log initial state
    const initialItems = imageContainer.querySelectorAll('.image-item');
    initialItems.forEach(item => {
        const bgImage = item.style.backgroundImage;
        const orderNumber = item.querySelector('.order-number');
        const computedStyle = window.getComputedStyle(orderNumber);
        console.log('Initial image:', {
            type: item.dataset.type,
            id: item.dataset.id || item.dataset.fileName,
            background: bgImage,
            orderNumberStyles: {
                position: computedStyle.position,
                width: computedStyle.width,
                height: computedStyle.height,
                fontSize: computedStyle.fontSize,
                zIndex: computedStyle.zIndex
            }
        });
    });
    const cssLoaded = Array.from(document.styleSheets).some(sheet => sheet.href && sheet.href.includes('add.css'));
    console.log('add.css loaded:', cssLoaded ? 'Yes' : 'No');

    // Initialize drag-and-drop for existing images
    updateIndices();
    attachDragListeners();

    // Handle new image uploads
    function handleFileChange(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            files.forEach(file => {
                if (file.type.match('image/jpeg') || file.type.match('image/png')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const exists = images.some(img => img.file.name === file.name);
                        if (!exists) {
                            images.push({ file, preview: e.target.result });
                            displayImages();
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
            clearFileInput();
        }
    }

    // Clear file input
    function clearFileInput() {
        try {
            const newInput = imageUpload.cloneNode(true);
            newInput.value = '';
            imageUpload.parentNode.replaceChild(newInput, imageUpload);
            imageUpload = newInput;
            imageUpload.addEventListener('change', handleFileChange);
        } catch (e) {
            console.error("Erreur lors de la réinitialisation du champ de fichier:", e);
        }
    }

    // Display all images
    function displayImages() {
        const existingItems = Array.from(imageContainer.querySelectorAll('.image-item[data-type="existing"]'));
        imageContainer.innerHTML = '';

        // Add existing images
        existingItems.forEach(item => imageContainer.appendChild(item));

        // Add new images
        images.forEach((image, index) => {
            const globalIndex = existingItems.length + index;
            const imageItem = document.createElement('div');
            imageItem.className = 'image-item';
            imageItem.style.backgroundImage = `url(${image.preview})`;
            imageItem.setAttribute('data-index', globalIndex);
            imageItem.setAttribute('data-type', 'new');
            imageItem.setAttribute('data-file-name', image.file.name);
            imageItem.draggable = true;

            const orderNumber = document.createElement('div');
            orderNumber.className = 'order-number';
            orderNumber.textContent = globalIndex + 1;
            imageItem.appendChild(orderNumber);

            const deleteLabel = document.createElement('label');
            deleteLabel.className = 'delete-checkbox-label';
            const deleteCheckbox = document.createElement('input');
            deleteCheckbox.type = 'checkbox';
            deleteCheckbox.name = 'delete_photos[]';
            deleteCheckbox.value = `new_${image.file.name}`;
            deleteCheckbox.className = 'delete-checkbox';
            deleteLabel.appendChild(deleteCheckbox);
            deleteLabel.appendChild(document.createTextNode('Supprimer'));
            imageItem.appendChild(deleteLabel);

            const orderInput = document.createElement('input');
            orderInput.type = 'hidden';
            orderInput.name = 'order[]';
            orderInput.value = `new_${image.file.name}`;
            imageItem.appendChild(orderInput);

            imageContainer.appendChild(imageItem);
        });

        // Update no-images text
        noImagesText.classList.toggle('hidden', imageContainer.children.length > 0);
        if (imageContainer.children.length === 0) {
            imageContainer.appendChild(noImagesText);
        }

        updateIndices();
        attachDragListeners();
    }

    // Update indices and order inputs
    function updateIndices() {
        const allItems = imageContainer.querySelectorAll('.image-item');
        allItems.forEach((item, index) => {
            item.setAttribute('data-index', index);
            item.querySelector('.order-number').textContent = index + 1;
            const orderInput = item.querySelector('input[name="order[]"]');
            if (orderInput) {
                orderInput.value = item.dataset.type === 'existing' ? `existing_${item.dataset.id}` : `new_${item.dataset.fileName}`;
            }
        });
    }

    // Attach drag-and-drop listeners
    function attachDragListeners() {
        const items = imageContainer.querySelectorAll('.image-item');
        items.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragenter', handleDragEnter);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
        });
    }

    // Drag-and-drop handlers
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
            const allItems = Array.from(imageContainer.querySelectorAll('.image-item'));
            const fromIndex = parseInt(draggedItem.getAttribute('data-index'));
            const toIndex = parseInt(this.getAttribute('data-index'));
            const movedItem = allItems.splice(fromIndex, 1)[0];
            allItems.splice(toIndex, 0, movedItem);

            imageContainer.innerHTML = '';
            allItems.forEach(item => imageContainer.appendChild(item));
            updateIndices();
            attachDragListeners();

            // Update new images array
            if (draggedItem.dataset.type === 'new' || this.dataset.type === 'new') {
                const newItems = allItems.filter(item => item.dataset.type === 'new');
                images = newItems.map(item => {
                    const fileName = item.dataset.fileName;
                    return images.find(img => img.file.name === fileName);
                }).filter(img => img);
            }
        }
    }

    // Handle file input
    if (imageUpload) {
        imageUpload.addEventListener('change', handleFileChange);
    }

    // Form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        imagesData.innerHTML = '';
        images.forEach((image, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `new_images[${index}]`;
            input.value = image.file.name;
            imagesData.appendChild(input);
        });
    });
});
    </script>
</body>
</html>
