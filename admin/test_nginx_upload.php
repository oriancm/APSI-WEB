<?php
// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('./php_upload_config.php');

echo "<h1>Test d'upload sur nginx</h1>";

// Afficher la configuration
echo "<h2>Configuration PHP :</h2>";
$config = getUploadConfig();
echo "<ul>";
foreach ($config as $key => $value) {
    echo "<li><strong>$key:</strong> $value</li>";
}
echo "</ul>";

// Afficher les informations du serveur
echo "<h2>Informations serveur :</h2>";
echo "<ul>";
echo "<li><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li><strong>PHP Version:</strong> " . phpversion() . "</li>";
echo "<li><strong>Upload Directory:</strong> " . ini_get('upload_tmp_dir') . "</li>";
echo "<li><strong>Max POST Size:</strong> " . ini_get('post_max_size') . "</li>";
echo "<li><strong>Max Upload Size:</strong> " . ini_get('upload_max_filesize') . "</li>";
echo "</ul>";

// Test d'upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h2>Résultat du test d'upload :</h2>";
    
    $file = $_FILES['test_file'];
    echo "<p><strong>Nom du fichier :</strong> " . $file['name'] . "</p>";
    echo "<p><strong>Taille :</strong> " . number_format($file['size'] / 1024 / 1024, 2) . " MB</p>";
    echo "<p><strong>Type MIME :</strong> " . $file['type'] . "</p>";
    echo "<p><strong>Code d'erreur :</strong> " . $file['error'] . "</p>";
    
    // Vérifier l'extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    echo "<p><strong>Extension détectée :</strong> $extension</p>";
    
    if ($file['error'] === 0) {
        echo "<p style='color: green;'>✅ Upload réussi !</p>";
        
        // Tester le déplacement du fichier
        $testDir = __DIR__ . '/../pic/test/';
        if (!is_dir($testDir)) {
            mkdir($testDir, 0755, true);
        }
        
        $testFile = $testDir . 'test_' . time() . '.' . $extension;
        if (move_uploaded_file($file['tmp_name'], $testFile)) {
            echo "<p style='color: green;'>✅ Fichier déplacé avec succès vers : $testFile</p>";
        } else {
            echo "<p style='color: red;'>❌ Erreur lors du déplacement du fichier</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Erreur d'upload : ";
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
                echo "Fichier trop volumineux (limite PHP)";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                echo "Fichier trop volumineux (limite formulaire)";
                break;
            case UPLOAD_ERR_PARTIAL:
                echo "Upload partiel";
                break;
            case UPLOAD_ERR_NO_FILE:
                echo "Aucun fichier uploadé";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                echo "Dossier temporaire manquant";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                echo "Impossible d'écrire le fichier";
                break;
            case UPLOAD_ERR_EXTENSION:
                echo "Extension PHP bloquée";
                break;
            default:
                echo "Erreur inconnue";
        }
        echo "</p>";
    }
}

// Test des permissions
echo "<h2>Test des permissions :</h2>";
$picDir = __DIR__ . '/../pic/';
echo "<p><strong>Dossier pic :</strong> $picDir</p>";
echo "<p><strong>Existe :</strong> " . (is_dir($picDir) ? 'Oui' : 'Non') . "</p>";
echo "<p><strong>Écriture :</strong> " . (is_writable($picDir) ? 'Oui' : 'Non') . "</p>";
echo "<p><strong>Permissions :</strong> " . substr(sprintf('%o', fileperms($picDir)), -4) . "</p>";

// Test du dossier temporaire
$tmpDir = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
echo "<p><strong>Dossier temporaire :</strong> $tmpDir</p>";
echo "<p><strong>Existe :</strong> " . (is_dir($tmpDir) ? 'Oui' : 'Non') . "</p>";
echo "<p><strong>Écriture :</strong> " . (is_writable($tmpDir) ? 'Oui' : 'Non') . "</p>";
?>

<form method="POST" enctype="multipart/form-data">
    <p><strong>Sélectionnez un fichier JPG ou PNG pour tester :</strong></p>
    <input type="file" name="test_file" accept="image/jpeg,image/png" required>
    <button type="submit">Tester l'upload</button>
</form>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
form { margin: 20px 0; padding: 20px; border: 1px solid #ccc; }
input[type="file"] { margin: 10px 0; display: block; }
button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
</style> 