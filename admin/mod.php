<?php
// Configuration pour les uploads de gros fichiers
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_file_uploads', '20');
ini_set('max_execution_time', '300');
ini_set('memory_limit', '256M');
ini_set('max_input_time', '300');

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
            $files = $_FILES['images'] ?? [];
            $uploaded_files = [];

            // Process new images if any
            if (!empty($files['name'][0])) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === 0) {
                        $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                        $extValid = ['jpg', 'jpeg', 'png'];
                        
                        if (in_array($ext, $extValid)) {
                            $new_name = uniqid() . '.' . $ext;
                            $destination = $_SERVER['DOCUMENT_ROOT'] . '/pic/' . $new_name;
                            if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
                                $uploaded_files[$files['name'][$i]] = [
                                    'dir' => 'pic/' . $new_name,
                                    'titre' => $new_name
                                ];
                            }
                        }
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
                        $stmt->execute([$id, $uploaded_files[$file_name]['dir'], $uploaded_files[$file_name]['titre'], $index + 1]);
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

            <!-- Images Section -->
            <div class="mb-4">
                <label for="image-upload" class="text-sm font-medium text-gray-700 mb-1">Images</label>
                <input type="file" id="image-upload" name="images[]" multiple accept="image/jpeg,image/png" class="mt-1 block w-full">
                <p class="text-sm text-gray-500">Formats acceptés : JPEG, PNG (JPG, jpg, PNG, png). Limite : 100MB par fichier, 400MB au total. Faites glisser les images pour changer l'ordre</p>
                <div id="image-container" class="image-container mb-4 flex flex-wrap mt-2 border border-gray-300 p-4 rounded">
                    <?php foreach ($photos as $index => $photo): ?>
                        <div class="image-item existing-image" data-id="<?= $photo['id'] ?>" data-index="<?= $index ?>" data-type="existing" style="background-image: url('/pic/<?= htmlspecialchars($photo['titre']) ?>');" draggable="true">
                            <div class="order-number"><?= $index + 1 ?></div>
                            <div class="delete-btn" onclick="deleteExistingImage(<?= $photo['id'] ?>)">×</div>
                            <input type="hidden" name="order[]" value="existing_<?= $photo['id'] ?>">
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
    // Les références aux éléments du DOM
    let imageUpload = document.getElementById('image-upload');
    const imageContainer = document.getElementById('image-container');
    const imagesData = document.getElementById('images-data');
    let newImages = []; // Tracks new images
    let draggedItem = null;

    // Fonction pour réinitialiser le champ de fichier
    function clearFileInput() {
        imageUpload.value = '';
    }

    // Initialize drag-and-drop for existing images
    updateIndices();
    attachDragListeners();

    // Fonction pour gérer la sélection de fichiers
    function handleFileChange(e) {
        const files = Array.from(e.target.files);
        const maxFileSize = 100 * 1024 * 1024; // 100MB en bytes
        const maxTotalSize = 400 * 1024 * 1024; // 400MB total en bytes
        
        if (files.length > 0) {
            // Vérifier la taille totale des fichiers
            const totalSize = files.reduce((sum, file) => sum + file.size, 0);
            if (totalSize > maxTotalSize) {
                alert(`Erreur : La taille totale des fichiers (${(totalSize / 1024 / 1024).toFixed(1)}MB) dépasse la limite de ${maxTotalSize / 1024 / 1024}MB`);
                imageUpload.value = '';
                return;
            }
            
            // Traiter tous les fichiers de manière synchrone
            let processedCount = 0;
            const totalFiles = files.length;
            
            files.forEach(file => {
                // Vérifier la taille individuelle de chaque fichier
                if (file.size > maxFileSize) {
                    alert(`Erreur : Le fichier "${file.name}" (${(file.size / 1024 / 1024).toFixed(1)}MB) dépasse la limite de ${maxFileSize / 1024 / 1024}MB par fichier`);
                    processedCount++;
                    if (processedCount === totalFiles) {
                        displayImages();
                        updateFileInput();
                    }
                    return;
                }
                
                if (file.type.match('image/jpeg') || file.type.match('image/png')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Vérifier si cette image existe déjà (par nom)
                        const exists = newImages.some(img => img.file.name === file.name);
                        if (!exists) {
                            newImages.push({ file, preview: e.target.result });
                        }
                        
                        processedCount++;
                        if (processedCount === totalFiles) {
                            displayImages();
                            updateFileInput();
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert(`Erreur : Le fichier "${file.name}" n'est pas un format d'image valide (JPEG ou PNG uniquement)`);
                    processedCount++;
                    if (processedCount === totalFiles) {
                        displayImages();
                        updateFileInput();
                    }
                }
            });
        }
    }

    // Fonction pour mettre à jour le champ de fichier avec tous les fichiers
    function updateFileInput() {
        if (newImages.length > 0) {
            const dataTransfer = new DataTransfer();
            newImages.forEach(image => {
                dataTransfer.items.add(image.file);
            });
            imageUpload.files = dataTransfer.files;
        }
    }

    // Fonction pour afficher les images
    function displayImages() {
        // Récupérer les images existantes
        const existingItems = Array.from(imageContainer.querySelectorAll('.image-item[data-type="existing"]'));
        
        // Vider le conteneur d'images
        imageContainer.innerHTML = '';
        imagesData.innerHTML = '';

        // Ajouter les images existantes
        existingItems.forEach(item => imageContainer.appendChild(item));

        // Si aucune image (ni existante ni nouvelle), afficher le texte "Aucune image sélectionnée"
        if (newImages.length === 0 && existingItems.length === 0) {
            const noImages = document.createElement('p');
            noImages.id = 'no-images';
            noImages.className = 'text-gray-400 w-full text-center';
            noImages.textContent = 'Aucune image sélectionnée';
            imageContainer.appendChild(noImages);
            return;
        }

        // Afficher chaque nouvelle image
        newImages.forEach((image, index) => {
            const imageItem = document.createElement('div');
            imageItem.className = 'image-item';
            imageItem.style.backgroundImage = `url(${image.preview})`;
            imageItem.setAttribute('data-type', 'new');
            imageItem.setAttribute('data-file-name', image.file.name);

            // Numéro d'ordre
            const orderNumber = document.createElement('div');
            orderNumber.className = 'order-number';
            imageItem.appendChild(orderNumber);

            // Bouton de suppression
            const deleteBtn = document.createElement('div');
            deleteBtn.className = 'delete-btn';
            deleteBtn.textContent = '×';
            deleteBtn.onclick = function(e) {
                e.stopPropagation();
                const imageIndex = newImages.findIndex(img => img.file.name === image.file.name);
                if (imageIndex !== -1) {
                    newImages.splice(imageIndex, 1);
                    displayImages();
                    updateFileInput();
                }
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
        });

        updateIndices();
        attachDragListeners();
    }

    // Update indices and order inputs
    function updateIndices() {
        const allItems = imageContainer.querySelectorAll('.image-item');
        allItems.forEach((item, index) => {
            item.setAttribute('data-index', index);
            const orderNumber = item.querySelector('.order-number');
            if (orderNumber) {
                orderNumber.textContent = index + 1;
            }
            
            // Créer ou mettre à jour le champ caché pour l'ordre
            let orderInput = item.querySelector('input[name="order[]"]');
            if (!orderInput) {
                orderInput = document.createElement('input');
                orderInput.type = 'hidden';
                orderInput.name = 'order[]';
                item.appendChild(orderInput);
            }
            
            if (item.dataset.type === 'existing') {
                orderInput.value = `existing_${item.dataset.id}`;
            } else {
                orderInput.value = `new_${item.dataset.fileName}`;
            }
        });
    }

    // Attach drag-and-drop listeners
    function attachDragListeners() {
        const items = imageContainer.querySelectorAll('.image-item');
        items.forEach(item => {
            // Remove existing listeners to avoid duplicates
            item.removeEventListener('dragstart', handleDragStart);
            item.removeEventListener('dragend', handleDragEnd);
            item.removeEventListener('dragover', handleDragOver);
            item.removeEventListener('dragenter', handleDragEnter);
            item.removeEventListener('dragleave', handleDragLeave);
            item.removeEventListener('drop', handleDrop);
            
            // Add new listeners
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
            item.addEventListener('dragover', handleDragOver);
            item.addEventListener('dragenter', handleDragEnter);
            item.addEventListener('dragleave', handleDragLeave);
            item.addEventListener('drop', handleDrop);
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
        
        if (draggedItem !== this && draggedItem !== null) {
            const allItems = Array.from(imageContainer.querySelectorAll('.image-item'));
            const fromIndex = parseInt(draggedItem.getAttribute('data-index'));
            const toIndex = parseInt(this.getAttribute('data-index'));
            
            // Vérifier que les indices sont valides
            if (isNaN(fromIndex) || isNaN(toIndex) || fromIndex < 0 || toIndex < 0 || fromIndex >= allItems.length || toIndex >= allItems.length) {
                return;
            }
            
            // Réorganiser visuellement
            const movedItem = allItems.splice(fromIndex, 1)[0];
            allItems.splice(toIndex, 0, movedItem);

            // Vider et reconstruire le conteneur
            imageContainer.innerHTML = '';
            allItems.forEach(item => imageContainer.appendChild(item));
            
            // Mettre à jour les indices et les écouteurs
            updateIndices();
            attachDragListeners();
        }
    }

    // Ajouter un écouteur d'événement au formulaire pour reconstruire le tableau de fichiers avant l'envoi
    document.querySelector('form').addEventListener('submit', function(e) {
        if (newImages.length === 0) {
            return; // Ne rien faire s'il n'y a pas d'images
        }

        // Créer un nouveau DataTransfer pour reconstruire le champ de fichier
        const dataTransfer = new DataTransfer();
        
        // Récupérer l'ordre actuel des images
        const allItems = Array.from(imageContainer.querySelectorAll('.image-item'));
        const newImageOrder = [];
        
        // Collecter l'ordre des nouvelles images selon leur position actuelle
        allItems.forEach(item => {
            if (item.dataset.type === 'new') {
                const fileName = item.dataset.fileName;
                const imageData = newImages.find(img => img.file.name === fileName);
                if (imageData) {
                    newImageOrder.push(imageData);
                }
            }
        });
        
        // Ajouter chaque fichier dans l'ordre actuel
        newImageOrder.forEach((image) => {
            dataTransfer.items.add(image.file);
        });
        
        // Mettre à jour le champ de fichier avec les fichiers dans le bon ordre
        imageUpload.files = dataTransfer.files;
    });
});

// Fonction globale pour supprimer une image existante
function deleteExistingImage(photoId) {
    const imageItem = document.querySelector(`[data-id="${photoId}"][data-type="existing"]`);
    if (imageItem) {
        // Créer un champ caché pour marquer l'image comme supprimée
        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'delete_photos[]';
        deleteInput.value = photoId;
        document.querySelector('form').appendChild(deleteInput);
        
        // Supprimer visuellement l'image
        imageItem.remove();
        
        // Mettre à jour les indices
        const allItems = document.querySelectorAll('.image-item');
        allItems.forEach((item, index) => {
            item.setAttribute('data-index', index);
            const orderNumber = item.querySelector('.order-number');
            if (orderNumber) {
                orderNumber.textContent = index + 1;
            }
            
            // Mettre à jour le champ caché pour l'ordre
            let orderInput = item.querySelector('input[name="order[]"]');
            if (orderInput) {
                if (item.dataset.type === 'existing') {
                    orderInput.value = `existing_${item.dataset.id}`;
                } else {
                    orderInput.value = `new_${item.dataset.fileName}`;
                }
            }
        });
        
        // Vérifier s'il reste des images
        const remainingImages = document.querySelectorAll('.image-item');
        const noImagesText = document.getElementById('no-images');
        if (remainingImages.length === 0) {
            if (!noImagesText) {
                const noImages = document.createElement('p');
                noImages.id = 'no-images';
                noImages.className = 'text-gray-400 w-full text-center';
                noImages.textContent = 'Aucune image sélectionnée';
                document.getElementById('image-container').appendChild(noImages);
            }
        } else {
            if (noImagesText) {
                noImagesText.remove();
            }
        }
    }
}
    </script>
</body>
</html>
