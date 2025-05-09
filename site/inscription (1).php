<?php
// Essayer d'établir la connexion à la base de données
try {
    $conn = new mysqli("db", "root", "rootpassword", "my_body_tracker");

    // Vérifie si la connexion échoue
    if ($conn->connect_error) {
        throw new Exception("Connexion échouée: " . $conn->connect_error);
    }

    // Vérifie si la méthode est POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupère les données du formulaire
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $email = $_POST["email"];
        $mot_de_passe = password_hash($_POST["mot_de_passe"], PASSWORD_DEFAULT);
        $nom_utilisateur = $_POST["nom_utilisateur"];

        // Prépare la requête sans inclure id_utilisateur (il sera auto-incrémenté)
        if ($stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, nom_utilisateur) VALUES (?, ?, ?, ?, ?);")) {
            $stmt->bind_param("sssss", $nom, $prenom, $email, $mot_de_passe, $nom_utilisateur);

            // Exécute la requête
            if ($stmt->execute()) {
                echo "<script>alert('Inscription réussie !'); window.location.href='login.php';</script>";
            } else {
                throw new Exception("Erreur lors de l'exécution de la requête: " . $stmt->error);
            }

            // Ferme la requête
            $stmt->close();
        } else {
            throw new Exception("Erreur de préparation de la requête: " . $conn->error);
        }
    }
} catch (Exception $e) {
    // Capture les exceptions et affiche un message d'erreur
    echo "Erreur: " . $e->getMessage();
} finally {
    // Ferme la connexion à la base de données
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Body Tracker - Inscription</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
      background-image: url('connexion(1).jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      background-color: rgba(255, 255, 255, 0.9); /* semi-transparent */
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .logo img {
      width: 100px;
      margin-bottom: 20px;
    }

    .form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 300px;
    }

    .form input {
      padding: 12px;
      border: 1px solid #4a8b7d;
      border-radius: 5px;
      font-size: 14px;
    }

    .form button {
      padding: 12px;
      background-color: #4a8b7d;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form button:hover {
      background-color: #3c7468;
    }

    .login-link {
      margin-top: 10px;
      color: #4a8b7d;
      font-size: 14px;
      text-decoration: underline;
      text-align: center;
      display: block;
    }

    .login-link:hover {
      color: #2c3e91;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logo">
      <img src="logo.png" alt="My Body Tracker Logo" />
    </div>

    <form class="form" method="POST">
      <input type="text" name="nom" placeholder="Nom" required />
      <input type="text" name="prenom" placeholder="Prénom" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required />
      <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
      <button type="submit">S'inscrire</button>
      <a class="login-link" href="login.php">Déjà inscrit ? Se connecter</a>
    </form>
  </div>
</body>
</html>
