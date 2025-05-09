<?php
// connexion.php - Page de connexion avec fond personnalisé

// Paramètres de connexion à la base de données
$host = '127.0.0.1';
$user = 'root';
$password = '';
$db = 'mybodytracker';

// Connexion à la base de données
$conn = new mysqli($host, $user, $password, $db);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Fermeture de la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - My Body Tracker</title>
    <style>
        body {
            background-image: url('mbo/image_co.jpg');
            background-size: cover; background-color: #f0f0f0;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Work Sans', sans-serif;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 16px;
            width: 400px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #4a8b7d;
        }

        .container form {
            display: flex;
            flex-direction: column;
        }

        .container input {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .container button {
            background-color: #4a8b7d;
            color: #fff;
            border: none;
            padding: 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .container button:hover {
            background-color: #3c7468;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Connexion</h2>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>
