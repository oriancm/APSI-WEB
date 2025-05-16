<?php
require('./db.php');
session_start();

if ($_SESSION["session"] != "valide") {
    header("Location:login.php");
    exit;
}

// Handle deletion
if (isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    
    // Start transaction to ensure data consistency
    $db->beginTransaction();
    try {
        // Fetch the title of the project for the success message
        $sql = "SELECT titre FROM reference WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        $ref = $stmt->fetch(PDO::FETCH_ASSOC);
        $titre = $ref ? $ref['titre'] : 'Inconnu';

        // Delete associated photos first (if any)
        $sql = "DELETE FROM photo WHERE idR = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        // Delete the reference
        $sql = "DELETE FROM reference WHERE id = ?";
        $stmt = $db->prepare($sql);
        if ($stmt->execute([$id])) {
            $message = "<div class='message success'>SUCCÈS: Le chantier \"" . htmlspecialchars($titre) . "\" avec l'ID $id a été supprimé.</div>";
        } else {
            $message = "<div class='message error'>ERREUR: Impossible de supprimer le chantier avec l'ID $id.</div>";
        }
        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        $message = "<div class='message error'>ERREUR: Une erreur s'est produite lors de la suppression : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}

// Fetch all references with error handling
$sql = "SELECT id, titre FROM reference ORDER BY titre";
$stmt = $db->prepare($sql);
if (!$stmt) {
    $message = "<div class='message error'>ERREUR: Impossible de préparer la requête pour récupérer les références.</div>";
} else {
    $stmt->execute();
    $references = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($stmt->errorCode() !== '00000') {
        $message = "<div class='message error'>ERREUR: Erreur lors de l'exécution de la requête : " . implode(', ', $stmt->errorInfo()) . "</div>";
    }
}

// Debug: Log number of references
error_log("Number of references fetched: " . count($references));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Espace Administrateur</title>
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <main>
        <div class="admin-header">
            <h1>Espace administrateur</h1>
        </div>

        <?php if (isset($message)) echo $message; ?>

        <a href="./add.php" class="add-btn">+ Créer une référence</a>

        <div>
            <button onclick="window.location.reload();" class="refresh-btn">Actualiser la liste</button>
            <p class="refresh-note">Il peut être nécessaire d'actualiser la liste si une référence n'apparaît pas.</p>
        </div>

        <div class="reference-list">
            <?php if (empty($references)): ?>
                <p class="no-references">Aucune référence trouvée dans la base de données.</p>
            <?php else: ?>
                <?php foreach ($references as $ref): ?>
                    <?php
                        // Sanitize title for JavaScript
                        $jsTitle = htmlspecialchars($ref['titre'] ?: 'Inconnu', ENT_QUOTES, 'UTF-8');
                        $jsTitle = preg_replace('/[\x00-\x1F\x7F]/u', '', $jsTitle); // Remove control characters
                    ?>
                    <div class="reference-item">
                        <span><?php echo htmlspecialchars($ref['titre'] ?: 'Titre inconnu'); ?> (ID: <?php echo $ref['id']; ?>)</span>
                        <div class="action-buttons">
                            <a href="mod.php?id=<?php echo $ref['id']; ?>" class="edit-btn">Modifier</a>
                            <form method="POST" action="admin.php" onsubmit="return confirmDelete('<?php echo $jsTitle; ?>', <?php echo $ref['id']; ?>);" style="display: inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $ref['id']; ?>">
                                <button type="submit" class="delete-btn">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <a href="./logout.php" class="logout-link">Se déconnecter</a>
    </main>

    <script>
        function confirmDelete(title, id) {
            try {
                console.log('Attempting confirm for title: "' + title + '", ID: ' + id);
                // Simplified confirm message to avoid any string issues
                var confirmed = confirm('Confirmer la suppression du chantier "' + title + '" (ID: ' + id + ') ?');
                console.log('Confirm result: ' + confirmed);
                return confirmed;
            } catch (error) {
                console.error('Confirm error for title "' + title + '", ID: ' + id + ': ', error);
                // Log to server
                fetch('/log-error.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        error: 'confirmDelete failed',
                        message: error.message,
                        title: title,
                        id: id,
                        timestamp: new Date().toISOString()
                    })
                }).catch(fetchError => console.error('Failed to log error: ', fetchError));
                alert('Erreur : Confirmation échouée. Suppression annulée.');
                return false; // Prevent submission
            }
        }
    </script>
</body>
</html>