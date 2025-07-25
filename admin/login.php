<?php
//phpinfo();
require('./db.php');
session_start();
if (isset($_SESSION["session"]) && $_SESSION["session"] == "valide") {
    header("Location:admin.php");
    exit;
}

if (!empty($_POST['login']) && !empty($_POST['pass']))
{
    $login = $_POST['login'];
    $pass = $_POST['pass'];

    $sql = "SELECT * FROM admin WHERE login = :login";
    $stmt = $db->prepare($sql);
    $stmt->bindValue('login', $login);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($res) {
        $passwordHash = $res['pass'];
        if (password_verify($pass, $passwordHash)) {
            $_SESSION["session"] = "valide";
            header("Location:admin.php");
            exit;
        } else {
            $error_message = "Identifiants invalides";
            $_SESSION["session"] = "invalide";
        }
    } else {
        $error_message = "Identifiants invalides";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion Administration</title>
    <style>
        :root {
            --primary-color: #3498db;
            --primary-hover: #2980b9;
            --error-color: #e74c3c;
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
        
        .login-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--box-shadow);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        
        .login-header {
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #777;
            font-size: 16px;
        }
        
        .login-form .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        .login-form label {
            display: block;
            margin-bottom: 10px;
            color: #555;
            font-weight: 500;
            font-size: 16px;
        }
        
        .login-form input {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .login-form input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .login-form button {
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
        }
        
        .login-form button:hover {
            background-color: var(--primary-hover);
        }
        
        .error-message {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--error-color);
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 25px;
            font-size: 14px;
            border-left: 4px solid var(--error-color);
        }
        
        /* Pour les dispositifs mobiles */
        @media (max-width: 500px) {
            .login-container {
                padding: 30px 20px;
            }
        }
        
        /* Animation pour le bouton de soumission */
        .login-form button {
            position: relative;
            overflow: hidden;
        }
        
        .login-form button:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }
        
        .login-form button:focus:not(:active)::after {
            animation: ripple 1s ease-out;
        }
        
        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            20% {
                transform: scale(25, 25);
                opacity: 0.3;
            }
            100% {
                opacity: 0;
                transform: scale(40, 40);
            }
        }
    </style>
</head>
<body>
    <main class="login-container">
        <div class="login-header">
            <h1>Espace Administration</h1>
            <p>Connectez-vous pour accéder à votre espace</p>
        </div>
        
        <?php if(isset($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" class="login-form">
            <div class="form-group">
                <label for="login">Identifiant</label>
                <input type="text" id="login" name="login" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="pass">Mot de passe</label>
                <input type="password" id="pass" name="pass" required autocomplete="current-password">
            </div>
            <div class="form-group">
                <button type="submit">Se connecter</button>
            </div>
        </form>
    </main>
</body>
</html>