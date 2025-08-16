<?php
// Configuration pour les uploads de gros fichiers
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('max_file_uploads', '20');
ini_set('max_execution_time', '300');
ini_set('memory_limit', '256M');
ini_set('max_input_time', '300');

echo "<h1>Test des limites d'upload PHP</h1>";
echo "<h2>Configuration actuelle :</h2>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_file_uploads: " . ini_get('max_file_uploads') . "</li>";
echo "<li>max_execution_time: " . ini_get('max_execution_time') . "</li>";
echo "<li>memory_limit: " . ini_get('memory_limit') . "</li>";
echo "<li>max_input_time: " . ini_get('max_input_time') . "</li>";
echo "</ul>";

echo "<h2>Test d'upload :</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    echo "<p>Fichier reçu : " . $file['name'] . "</p>";
    echo "<p>Taille : " . number_format($file['size'] / 1024 / 1024, 2) . " MB</p>";
    echo "<p>Type : " . $file['type'] . "</p>";
    echo "<p>Erreur : " . $file['error'] . "</p>";
    
    if ($file['error'] === 0) {
        echo "<p style='color: green;'>✅ Upload réussi !</p>";
    } else {
        echo "<p style='color: red;'>❌ Erreur d'upload : " . $file['error'] . "</p>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="test_file" required>
    <button type="submit">Tester l'upload</button>
</form> 