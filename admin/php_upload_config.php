<?php
/**
 * Configuration PHP pour les uploads sur nginx
 * Ce fichier doit être inclus au début de add.php et mod.php
 */

// Configuration pour les uploads de gros fichiers
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_file_uploads', '20');
ini_set('max_execution_time', '300');
ini_set('memory_limit', '256M');
ini_set('max_input_time', '300');

// Configuration spécifique pour nginx
ini_set('file_uploads', '1');
ini_set('upload_tmp_dir', '/tmp');

// Configuration pour éviter les timeouts
set_time_limit(300);
ignore_user_abort(true);

// Headers pour éviter les problèmes de cache (optionnels)
if (!headers_sent()) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
}

// Fonction pour vérifier si l'upload est possible
function checkUploadCapability() {
    $errors = [];
    
    if (!is_uploaded_file($_FILES['images']['tmp_name'][0] ?? '')) {
        $errors[] = "Aucun fichier uploadé ou erreur d'upload";
    }
    
    if (empty($_FILES['images']['name'][0])) {
        $errors[] = "Aucun fichier sélectionné";
    }
    
    // Vérifier les erreurs d'upload
    if (isset($_FILES['images']['error'])) {
        foreach ($_FILES['images']['error'] as $error) {
            if ($error !== UPLOAD_ERR_OK) {
                switch ($error) {
                    case UPLOAD_ERR_INI_SIZE:
                        $errors[] = "Fichier trop volumineux (limite PHP)";
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        $errors[] = "Fichier trop volumineux (limite formulaire)";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errors[] = "Upload partiel";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errors[] = "Aucun fichier uploadé";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errors[] = "Dossier temporaire manquant";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errors[] = "Impossible d'écrire le fichier";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errors[] = "Extension PHP bloquée";
                        break;
                }
            }
        }
    }
    
    return $errors;
}

// Fonction pour valider les extensions de fichiers
function validateFileExtensions($files) {
    $validExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
    $errors = [];
    
    foreach ($files['name'] as $index => $filename) {
        if ($files['error'][$index] === UPLOAD_ERR_OK) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), array_map('strtolower', $validExtensions))) {
                $errors[] = "Extension non autorisée pour $filename: $extension";
            }
        }
    }
    
    return $errors;
}

// Fonction pour obtenir les informations de configuration
function getUploadConfig() {
    return [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'max_file_uploads' => ini_get('max_file_uploads'),
        'max_execution_time' => ini_get('max_execution_time'),
        'memory_limit' => ini_get('memory_limit'),
        'max_input_time' => ini_get('max_input_time'),
        'file_uploads' => ini_get('file_uploads'),
        'upload_tmp_dir' => ini_get('upload_tmp_dir')
    ];
}
?> 