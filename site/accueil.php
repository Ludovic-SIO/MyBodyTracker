<?php
// accueil.php - Sprint 1 – Page d'accueil connectée – Projet My Body Tracker (SCRUM)

// Essayer d'établir la connexion à la base de données
try {
$conn = new mysqli("localhost", "root", "", "mb");


    // Vérifie si la connexion échoue
    if ($conn->connect_error) {
        throw new Exception("Connexion échouée : " . $conn->connect_error);
    }
    
    // Pas besoin de traitement de formulaire ici pour la page d'accueil
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
} finally {
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
  <title>My Body Tracker - Accueil</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Work_Sans", sans-serif;
      Font-weight: bold;
    }

    body {
      background-color: #ffffff;
      min-height: 100vh;
      position: relative;
      overflow-x: hidden;
    }

    .background-shape {
      position: absolute;
      z-index: -1;
      background-color: #4a8b7d;
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

    header {
      background-color: #4a8b7d;
      padding: 20px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: white;
    }

    header img {
      height: 50px;
    }

    nav ul {
    display: flex;
    list-style: none;
    gap: 100px;
    position: absolute;
    text-align: center;
    left: 30%;
    top: 55px;
    text-shadow: 10px 10px 11px black;

}



    nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
    }

    nav a:hover {
      text-decoration: underline;
    }

    .hero {
    text-align: center;
    margin-top: 50px;
    padding: 100px;
    background-image: url(images/sparringImage.jpg);
    margin-top: 30px;
    width: 100%;
    max-width: 700px;
    position: relative;
    border-radius: 20px;
    background-size: cover;
    height: 68%;
    left: 25%;
    box-shadow: 8px 17px 100px;  }

.titre1 {
    font-size: 60px;
    color: #4a8b7d;
    font-weight: 700;
    position: relative;
    left: -59%;
    top: 47%;
    text-shadow: 2px 2px 0 white, 0px -1px 0 white, 0px 1px 0 white, 1px -1px 0 white;

}

    .titre2{
    font-size: 60px;
    color: #4a8b7d;
    font-weight: 700;
    position: relative;
    left: -46%;
    top: 43%;
    text-shadow: 2px 2px 0 white, 0px -1px 0 white, 0px 1px 0 white, 1px -1px 0 white;
    }

    .hero span {
    font-size: 20px;
    color: #fff;
    position: absolute;
    background-color: #0c8e7f;
    top: 2%;
    font-weight: 200;
    background-size: 100%¨;
    left: 85%;
    width: 35%;
    padding: 0%;
    opacity: 65%;
    box-shadow: 10px 5px 5px black;
}

.hero p {
    font-size: 20px;
    color: #4a8b7d;
    position: absolute;
    top: 85%;
    left: -35%;
}

    .hero button {
      margin-top: 20px;
      padding: 15px 30px;
      font-size: 16px;
      background-color: #4a8b7d;
      border: none;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .hero button:hover {
      background-color: #3c7468;
    }

    .hero .n1 {
    position: absolute;
    left: 85%;
    width: 35%;
    max-width: 700px;
    border-radius: 16px;
    top: 12%;
    }

 
  .hero .n2 {
    position: absolute;
    top: 50%;
    width: 35%;
    max-width: 700px;
    left: 85%;
    border-radius: 16px;
}

    .separator {
      margin: 60px 0;
      height: 50px;
      background-color: #4a8b7d;
      clip-path: polygon(0 0, 100% 100%, 0 100%);
    }

    .separator2 {
      
      margin: -110px 0;
      height: 50px;
      background-color: #4a8b7d;
      clip-path: polygon(100% 0, 100% 100%, 0 100%);
    }

    .about, .news, .contact {
      margin-top: 35%;
    text-align: center;
    }

    .about img {
    width: 500px;
    border-radius: 00;
    margin-bottom: 37px;
    top: 32%;
    position: absolute;
    left: 5%;
}

    .news {
         padding-top: 13%;

    }

    .news-items {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: center;
    }

    .news-item, .highlight {
      background-color: #f0f0f0;
      padding: 20px;
      border-radius: 10px;
      width: 300px;
    }



    .highlight {
      background-color: #e0e0e0;
    }

    .highlight h3 {
      color: #4a8b7d;
      margin-bottom: 10px;
    }


    .contact {
      margin-top: 10%;
    }


    .contact iframe {
      width: 100%;
      max-width: 600px;
      height: 300px;
      border: none;
      margin-top: 20px;
    }

    footer {
      background-color: #4a8b7d;
      color: white;
      padding: 40px 20px;
      text-align: center;
      margin-top: 40px;
    }

    footer .newsletter {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    footer input[type="email"] {
      padding: 10px;
      width: 250px;
      border: none;
      border-radius: 5px 0 0 5px;
    }

    footer button {
      padding: 10px 20px;
      border: 2px solid #ffffff;
      background-color: #4a8b7d;
      color: white;
      border-radius: 0 5px 5px 0;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    footer button:hover {
      background-color: #24317d;
    }

    .footer-links {
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: white;
      text-decoration: none;
      font-size: 14px;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    .about h2
    {
      height: 50px;
      top: 32%;
      position: absolute;
      left: 64%;


    }

    .about p {
    height: 50px;
    top: 36%;
    position: ABSOLUTE;
    left: 42%;
    font-size: 15px;
}

    
  </style>
</head>

<body>


<header>
  <img src="images/logo.png" alt="My Body Tracker Logo">
  <nav>
    <ul>
      <li><a href="#">Accueil</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="connexion.php">Connexion</a></li> 
    </ul>
  </nav>
</header>

<section class="hero">
  <span>nouveauté</span>
  <img class="n1" src="images/télécharger.jpg" alt="nouveauté1">
  <h1 class="titre1">MY BODY </h1>
  <h1 class="titre2">       Tracker</h1>
  <p>Le suivi sportif, le futur de votre bien être.</p>
</section>

<div class="separator"></div>

<div class="separator2"></div>


<section class="about">
  <img src="images/Image1COURIR.jpg" alt="Application en action">
  <h2>À PROPOS DE<br>L'APPLICATION</h2>
  <p>My Body Tracker est une application web qui permet aux utilisateurs de suivre leurs performances sportives (musculation, boxe, running), de visualiser leur progression via des graphiques, de se comparer à d'autres, et d'accéder à des articles santé et sport.</p>
</section>

<section class="news">
  <h2>Fil info</h2>
  <div class="news-items">
    <div class="news-item">
      <img src="images/RunningImage.jpg" alt="Actualité 1" style="width:100%; border-radius:10px;">
      <p>Chasse ultime pour l'équipe après la qualification exceptionnelle contre Valence !</p>
    </div>
    <div class="news-item">
      <img src="images/MuscuImage.jpg" alt="Actualité 2" style="width:100%; border-radius:10px;">
      <p>Chasse ultime pour l'équipe après la qualification exceptionnelle contre Valence !</p>
    </div>
    <div class="news-item">
      <img src="images/TennisImage.jpg" alt="Actualité 3" style="width:100%; border-radius:10px;">
      <p>Chasse ultime pour l'équipe après la qualification exceptionnelle contre Valence !</p>
    </div>
    <div class="highlight">
      <h3>La Dernière Actus</h3>
      <img src="images/BoxeImage.jpg" alt="Montres connectées" style="width:100%; border-radius:10px;">
      <p>Jeux Olympiques Paris 2024<br><b>Nouvelles Fonctionnalités Avec Les Montres Connectées</b></p>
    </div>
  </div>
</section>

<section class="contact">
  <h2>Contactez-nous</h2>
  <p>📍 Lycée Edouard Gand<br>80000, Amiens</p>
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2571.13095031125!2d2.291263576155411!3d49.87756737148843!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e7845e8c036f5b%3A0x3b2867de7bd2941c!2zTHljw6llIMOJZG91YXJkIEdhbmQ!5e0!3m2!1sfr!2sfr!4v1745919209518!5m2!1sfr!2sfr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></section>

<footer>
  <div class="newsletter">
    <input type="email" placeholder="Entrez votre mail">
    <button>S'abonner</button>
  </div>

  <div class="footer-links">
    <a href="#">Notre Histoire</a>
    <a href="#">Notre Newsletter</a>
    <a href="#">Fil d'info</a>
    <a href="#">À propos</a>
  </div>

  <p style="margin-top: 20px;">Mentions légales</p>
</footer>

</body>
</html>
