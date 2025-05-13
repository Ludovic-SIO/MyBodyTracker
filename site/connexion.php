<?php
session_start();

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "mb");

$message = "";
// formulaire de connexion 

try {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur = ?");
    $stmt->bind_param("s", $nom_utilisateur);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Vérification du mot de passe haché
        if (password_verify($mot_de_passe, $row['Mot_de_passe'])) {
          $_SESSION['pseudoU'] = $nom_utilisateur;
          $_SESSION['id_utilisateur'] = $row['id_utilisateur']; // <- AJOUT ESSENTIEL
          $message = "<script>alert('Connexion réussie !'); window.location.href='accueil.php';</script>";
      }
       else {
            $message = "<script>alert('Mot de passe incorrect');</script>";
        }
    } else {
        $message = "<script>alert('Nom d\'utilisateur incorrect');</script>";
    }
    
}
}


catch(exeption $e){
  $message = "<script>alert('une erreur c'est produite veuillez réessayer plus tard !'); window.location.href='accueil.php';</script>";

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexion</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Segoe UI", sans-serif;
    }

    body {
    background-image: url(connexion.jpg);
          background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      position: relative;
      min-height: 100vh;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .background-shape {
      position: absolute;
      z-index: -1;
      background-color: #fff;
      border-radius: 50%;
    }

    .top-left {
      top: -100px;
      left: -100px;
      width: 500px;
      height: 500px;
    }

    .bottom-right {
      bottom: -150px;
      right: -150px;
      width: 800px;
      height: 800px;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    .logo img {
      width: 100px;
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .form {
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 20px;
      width: 320px;
    }

    .form input {
      padding: 15px;
      border: 1px solid #4a8b7d;
      background-color: #ffffff;
      border-radius: 5px;
      font-size: 14px;
    }

    .form button {
      padding: 15px;
      background-color: #4a8b7d;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      font-weight: bold;
      color: #ffffff;
      cursor: pointer;
      border-radius: 5px;
      transition: 0.3s ease;
    }

    .form button:hover {
      background-color: #3c7468;
    }

    .login-link {
      margin-top: 10px;
      color: #fff ;
      font-size: 14px;
      text-decoration: underline;
      cursor: pointer;
      transition: color 0.3s ease;
    }


    .login-link2 {
    margin-top: 185px;
    color: #fff;
    position: absolute;
    font-size: 14px;
    top: 543px;
    left: 50.3%;
    text-decoration: underline;
    cursor: pointer;
    transition: color 0.3s ease;
}

    .login-link:hover {
      color: #2c3e91;
    }

    .login-link2:hover{
      color: #2c3e91;
    }




  </style>
</head>
<body>


<?php if (!empty($message)) echo $message; ?>

<div class="container">
    <div class="logo">
      <img src="logo (2).png" alt="My Body Tracker Logo" />
    </div>

<form class="form" method="POST">
    <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required />
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" required />
    <button type="submit">Se connecter</button>
    <a class="login-link" href="login.php">mot de passe oublié ?  </a>  
    <a class="login-link2" href="inscription.php">Vous n'êtes pas inscrit ? </a>  
  </form>


</body>
</html>
