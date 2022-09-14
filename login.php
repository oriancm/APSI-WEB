<?php

$user = 'root';
$pass = '';
$db = new PDO('mysql:host=locahost;dbname=apsi',$user,$pass);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <main>
        <h1>Login Form</h1>
        <form action="POST">
            <div>
                <label for="">username</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label for="">password</label>
                <input type="text" name="password" required>
            </div>
            <div>
                <input type="submit" value=""Login>
            </div>

        </form>
    </main>
</body>
</html>

