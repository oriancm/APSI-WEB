<?php
session_start();

// Vérifier la session AVANT d'inclure les fichiers de configuration
if (!isset($_SESSION["session"]) || $_SESSION["session"] != "valide") {
    header("Location:login.php");
    exit;
}

// Configuration pour les uploads sur nginx
require_once('./php_upload_config.php');

require('./db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Validations
    $errorsValidation = [];
    if (empty($_POST['titre'])) {
        $errorsValidation['titre'] = 'Erreur : Le titre est requis';
    }
    if (empty($_POST['villeChoisieHidden'])) {
        $errorsValidation['commune'] = 'Erreur : La commune est requise';
    }
    
    // Validation des images avec diagnostic
    if (empty($_FILES['images']['name'][0]) || !isset($_POST['picOrder']) || empty($_POST['picOrder'])) {
        $errorsValidation['images'] = 'Erreur : Au moins une image est requise';
    } else {
        // Vérifier les erreurs d'upload
        $uploadErrors = checkUploadCapability();
        if (!empty($uploadErrors)) {
            $errorsValidation['images'] = 'Erreur upload : ' . implode(', ', $uploadErrors);
        }
        
        // Vérifier les extensions
        $extensionErrors = validateFileExtensions($_FILES['images']);
        if (!empty($extensionErrors)) {
            $errorsValidation['images'] = 'Erreur extension : ' . implode(', ', $extensionErrors);
        }
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

        $sql = "INSERT INTO reference (titre, commune, domaine, description, anneeD, anneeF, statut, moa, archi, eMoe, nbPhase, montant, nbE, duree_travaux_mois, nombre_lots)";
        $sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $db->prepare($sql);

        $insertSuccess = $stmt->execute([$titre, $commune, $domaine, $description, $anneeD, $anneeF, $statut, $moa, $archi, $eMoe, $nbPhase, $montant, $nbE, $duree_travaux_mois, $nombre_lots]);
        
        if ($insertSuccess) {
            $idRef = $db->lastInsertId();
            $imageErrors = [];
            
            // Gestion des images
            if (!empty($_FILES['images']) && isset($_POST['picOrder'])) {
                $files = $_FILES['images'];
                $orderPic = 1;
                
                // Récupérer l'ordre des images à partir de picOrder[]
                $picOrderList = isset($_POST['picOrder']) ? $_POST['picOrder'] : [];
                $fileMap = [];

                // Créer une map des fichiers par nom
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

                // Traiter chaque image dans l'ordre spécifié
                foreach ($picOrderList as $fileName) {
                    if (isset($fileMap[$fileName]) && $fileMap[$fileName]['error'] === 0) {
                        $pic = $fileMap[$fileName];
                        $extPic = pathinfo($pic['name'], PATHINFO_EXTENSION);
                        $extValid = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
                        $localPath = __DIR__ . '/../pic/';
                        // Vérifier si le dossier existe, sinon le créer
                        if (!is_dir($localPath)) {
                            mkdir($localPath, 0755, true);
                        }
                        
                        // Nettoyer et sécuriser le nom de fichier pour nginx
                        $original_name = basename($pic['name']);
                        
                        // Remplacer les caractères problématiques pour nginx
                        $safe_name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $original_name);
                        $safe_name = preg_replace('/_+/', '_', $safe_name); // Éviter les underscores multiples
                        $safe_name = trim($safe_name, '_'); // Enlever les underscores en début/fin
                        
                        // S'assurer que le nom n'est pas vide après nettoyage
                        if (empty($safe_name)) {
                            $safe_name = 'image_' . time();
                        }
                        
                        // Normaliser l'extension en minuscules pour la compatibilité nginx
                        $ext_normalized = strtolower($extPic);
                        $name_without_ext = pathinfo($safe_name, PATHINFO_FILENAME);
                        $final_name = $name_without_ext . '.' . $ext_normalized;
                        $uploadPath = $localPath . $final_name;
                        
                        // Vérifier si le fichier existe déjà et ajouter un suffixe si nécessaire
                        $counter = 1;
                        while (file_exists($uploadPath)) {
                            $final_name = $name_without_ext . '_' . $counter . '.' . $ext_normalized;
                            $uploadPath = $localPath . $final_name;
                            $counter++;
                        }
                        
                        $relativePath = 'pic/' . $final_name;

                        if (in_array(strtolower($extPic), array_map('strtolower', $extValid)) && move_uploaded_file($pic['tmp_name'], $uploadPath)) {
                            $sql = "INSERT INTO photo (titre, dir, idR, orderPic) VALUES (?, ?, ?, ?)";
                            $stmt = $db->prepare($sql);
                            if (!$stmt->execute([$final_name, $relativePath, $idRef, $orderPic])) {
                                $imageErrors[] = "Erreur BDD pour l'image " . htmlspecialchars($final_name);
                            }
                            $orderPic++;
                        } else {
                            $imageErrors[] = "Erreur lors du traitement de l'image " . htmlspecialchars($pic['name']);
                        }
                    }
                }
            }
            
            // Préparer le message de succès
            $successMessage = "Informations du chantier $idRef enregistrées avec succès";
            if (!empty($imageErrors)) {
                $successMessage .= " (avec " . count($imageErrors) . " erreur(s) d'image)";
            }
            $successMessage .= " - <a href='/reference/$idRef' class='text-blue-600 hover:underline'>Consulter cette référence</a>";
            
            header("Location: add.php?success=" . urlencode($successMessage));
            exit;
        } else {
            $errorsValidation['general'] = 'Erreur lors de l\'enregistrement du chantier';
        }
    }
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
        /* Mettre en évidence les champs requis */
        label:has(*):before {
            content: "";
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
            @apply text-red-500 text-sm mt-1 font-medium;
        }
        .submit-btn {
            @apply bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500;
        }
        .form-container {
            @apply bg-white p-6 rounded-lg shadow-md;
        }
        /* Styles pour les champs requis */
        .required-field {
            border-left: 3px solid #ef4444;
            padding-left: 12px;
        }
        .required-info {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <main class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            <a href="./admin.php" class="text-blue-600 hover:underline">Espace administrateur</a>
        </h1>
        <div class="mb-6 flex space-x-4">
            <a href="/references" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Consulter nos références
            </a>
        </div>
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Ajout d'une référence (Référence n°<?= (dernierChantier($db) + 1) ?>)</h2>

        <?php if (isset($message)) echo $message; ?>
        
        <?php if (isset($errorsValidation['general'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <?php echo $errorsValidation['general']; ?>
            </div>
        <?php endif; ?>

        <div class="required-info">
            <h3 class="text-lg font-medium text-red-700 mb-2">⚠️ Champs obligatoires</h3>
            <p class="text-sm text-red-600">Les champs marqués d'un astérisque (*) sont obligatoires :</p>
            <ul class="text-sm text-red-600 mt-2 list-disc list-inside">
                <li><strong>Titre</strong> : Nom du chantier/projet</li>
                <li><strong>Commune</strong> : Lieu du projet (sélectionner dans la liste)</li>
                <li><strong>Images</strong> : Au moins une image est requise (formats JPEG, PNG)</li>
            </ul>
        </div>

        <form method="POST" action="add.php" enctype="multipart/form-data" class="form-container space-y-6">
            <div class="mb-4">
                <label for="titre" class="text-sm font-medium text-gray-700 mb-1">Titre <span class="text-red-500">*</span></label>
                <input type="text" name="titre" id="titre" required class="border <?php echo isset($errorsValidation['titre']) ? 'border-red-500' : ''; ?>">
                <?php if (isset($errorsValidation['titre'])): ?>
                    <p class="error"><?php echo $errorsValidation['titre']; ?></p>
                <?php endif; ?>
            </div>
            <div class="mb-4">
                <label for="code" class="text-sm font-medium text-gray-700 mb-1">Commune <span class="text-red-500">*</span></label>
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
                    <option value="1">Résidences Universitaires</option>
                    <option value="2">Équipements sportifs</option>
                    <option value="3">Équipements culturels</option>
                    <option value="4">Groupes scolaires et collèges</option>
                    <option value="5">Aménagements urbains</option>
                    <option value="6">Bâtiments publics</option>
                    <option value="7">Crèches</option>
                    <option value="8">Centre d'Incendie et de Secours</option>
                    <option value="9">Santé</option>
                    <option value="10">Logements sociaux</option>
                    <option value="11">Monuments Historiques et Sites Patrimoniaux</option>
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
                <label for="image-upload" class="text-sm font-medium text-gray-700 mb-1">Images  <span class="text-red-500">*</span></label>
                <input type="file" id="image-upload" name="images[]" multiple accept="image/jpeg,image/png" required class="mt-1 block w-full <?php echo isset($errorsValidation['images']) ? 'border-red-500' : ''; ?>">
                <p class="text-sm text-gray-500">Formats acceptés : JPEG, PNG (JPG, jpg, PNG, png). Limite : 100MB par fichier, 400MB au total. Faites glisser les images pour changer l'ordre</p>
                <div id="image-container" class="image-container mb-4 flex flex-wrap mt-2 border border-gray-300 p-4 rounded">
                    <p id="no-images" class="text-gray-400 w-full text-center">Aucune image sélectionnée</p>
                </div>
                <div id="images-data"></div> <!-- hidden inputs générés dynamiquement ici -->
                <?php if (isset($errorsValidation['images'])): ?>
                    <p class="error"><?php echo $errorsValidation['images']; ?></p>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <a href="/references" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Consulter nos références
                </a>
                <button type="submit" name="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Valider</button>
            </div>
        </form>
    </main>

    <script src="/script/search-cities.js"></script>
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
        const maxFileSize = 100 * 1024 * 1024; // 100MB en bytes
        const maxTotalSize = 400 * 1024 * 1024; // 400MB total en bytes
        
        if (files.length > 0) {
            // Vérifier la taille totale des fichiers
            const totalSize = files.reduce((sum, file) => sum + file.size, 0);
            if (totalSize > maxTotalSize) {
                alert(`Erreur : La taille totale des fichiers (${(totalSize / 1024 / 1024).toFixed(1)}MB) dépasse la limite de ${maxTotalSize / 1024 / 1024}MB`);
                clearFileInput();
                return;
            }
            
            // Traiter tous les fichiers de manière synchrone
            let processedCount = 0;
            const totalFiles = files.length;
            let validFiles = [];
            
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
                
                // Vérifier le type MIME ET l'extension du fichier
                const fileName = file.name.toLowerCase();
                const isValidType = file.type.match('image/jpeg') || file.type.match('image/png');
                const isValidExtension = fileName.endsWith('.jpg') || fileName.endsWith('.jpeg') || fileName.endsWith('.png');
                
                if (isValidType || isValidExtension) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Vérifier si cette image existe déjà (par nom)
                        const exists = images.some(img => img.file.name === file.name);
                        if (!exists) {
                            images.push({ file, preview: e.target.result });
                        }
                        
                        processedCount++;
                        if (processedCount === totalFiles) {
                            displayImages();
                            // Mettre à jour le champ de fichier avec tous les fichiers
                            updateFileInput();
                        }
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert(`Erreur : Le fichier "${file.name}" n'est pas un format d'image valide (JPG, JPEG ou PNG uniquement)`);
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
        if (images.length > 0) {
            const dataTransfer = new DataTransfer();
            images.forEach(image => {
                dataTransfer.items.add(image.file);
            });
            imageUpload.files = dataTransfer.files;
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
                // Mettre à jour le champ de fichier après suppression
                updateFileInput();
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
            // Mettre à jour le champ de fichier après réorganisation
            updateFileInput();
        }
    }

    // Ajouter un écouteur d'événement au formulaire pour reconstruire le tableau de fichiers avant l'envoi
    document.querySelector('form').addEventListener('submit', function(e) {
        if (images.length === 0) {
            return; // Ne rien faire s'il n'y a pas d'images
        }

        // Créer un nouveau DataTransfer pour reconstruire le champ de fichier
        const dataTransfer = new DataTransfer();
        
        // Ajouter chaque fichier dans l'ordre actuel
        images.forEach((image, index) => {
            dataTransfer.items.add(image.file);
        });
        
        // Mettre à jour le champ de fichier avec les fichiers dans le bon ordre
        imageUpload.files = dataTransfer.files;
    });
});    </script>

</body>
</html>