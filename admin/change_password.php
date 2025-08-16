<?php
require('./db.php');
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["session"]) || $_SESSION["session"] != "valide") {
    header("Location:login.php");
    exit;
}

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "Tous les champs sont obligatoires.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (strlen($new_password) < 6) {
        $error_message = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } else {
        // Vérifier le mot de passe actuel
        $sql = "SELECT * FROM admin WHERE login = 'admin'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($current_password, $admin['pass'])) {
            // Hasher le nouveau mot de passe
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Mettre à jour en base de données
            $update_sql = "UPDATE admin SET pass = :password WHERE login = 'admin'";
            $update_stmt = $db->prepare($update_sql);
            $update_stmt->bindValue('password', $new_password_hash);
            
            if ($update_stmt->execute()) {
                $success_message = "Mot de passe modifié avec succès !";
            } else {
                $error_message = "Erreur lors de la modification du mot de passe.";
            }
        } else {
            $error_message = "Mot de passe actuel incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changer le mot de passe - Administration</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --error-color: #e74c3c;
            --success-color: #27ae60;
            --background-light: #f9f9f9;
            --box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--background-light);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #777;
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: 500;
            font-size: 16px;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            width: 100%;
            padding: 15px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 15px;
        }
        
        .btn:hover {
            background-color: var(--primary-hover);
        }
        
        .btn-secondary {
            background-color: #95a5a6;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            border-left: 4px solid var(--error-color);
        }
        
        .success-message {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .password-requirements {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #6c757d;
        }
        
        .password-requirements h4 {
            margin-bottom: 10px;
            color: #495057;
        }
        
        .password-requirements ul {
            margin-left: 20px;
        }
        
        @media (max-width: 500px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Changer le mot de passe</h1>
            <p>Modifiez votre mot de passe administrateur</p>
        </div>
        
        <?php if($error_message): ?>
            <div class="message error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        
        <?php if($success_message): ?>
            <div class="message success-message">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        
        <div class="password-requirements">
            <h4>Exigences du mot de passe :</h4>
            <ul>
                <li>Au moins 6 caractères</li>
                <li>Utilisez des caractères variés pour plus de sécurité</li>
            </ul>
        </div>
        
        <form method="POST" action="change_password.php">
            <div class="form-group">
                <label for="current_password">Mot de passe actuel</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Changer le mot de passe</button>
            <a href="admin.php" class="btn btn-secondary" style="text-decoration: none; display: block; text-align: center;">Retour à l'administration</a>
        </form>
    </div>
</body>
</html> 