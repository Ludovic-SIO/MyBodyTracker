<?php
session_start();
$id_utilisateur = $_SESSION['id_utilisateur']; // exemple : 1

$conn = new mysqli("172.25.192.6", "root", "rootpassword", "my_body_tracker");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$sql = "SELECT poids FROM utilisateur WHERE id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $poids = $row['poids'];
} else {
    $poids = "Non trouvé";
}



$sql = "SELECT poids, taille, age, prenom, nom FROM utilisateur WHERE id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_utilisateur);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $poids = $row['poids'];
    $taille = $row['taille'];
    $age = $row['age'];
    $prenom = $row['prenom'];
    $nom = $row['nom'];
} else {
    $poids = $taille = $age = $prenom = $nom = "Non trouvé";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les valeurs du formulaire
    $type_activité = isset($_POST['type_activité']) ? $_POST['type_activité'] : '';  // Nom du champ mis à jour
    $date_activité = isset($_POST['semaine']) ? $_POST['semaine'] : '';  // Utilisation de 'semaine' comme date_activité
    $durée_minutes = isset($_POST['durée_minutes']) ? $_POST['durée_minutes'] : '';  // Nom du champ mis à jour

    // Vérifier que toutes les valeurs sont présentes
    if (empty($type_activité) || empty($date_activité) || empty($durée_minutes)) {
        echo "<script>alert('Erreur: Tous les champs doivent être remplis!');</script>";
    } else {
        // Vérifier que l'id_utilisateur est défini dans la session
        if (!isset($_SESSION['id_utilisateur']) || empty($_SESSION['id_utilisateur'])) {
            die("Erreur : L'utilisateur n'est pas connecté.");
        }

        // Récupérer l'id_utilisateur de la session
        $id_utilisateur = $_SESSION['id_utilisateur'];

        // Convertir la semaine (format '2025-W15') en un format de date valide (par exemple, le premier jour de la semaine)
        // Convertir la semaine en date (lundi de cette semaine)
        if (preg_match('/^(\d{4})-W(\d{2})$/', $date_activité, $matches)) {
            $year = $matches[1];
            $week = $matches[2];

            // Utiliser la fonction strtotime pour obtenir la date du lundi de la semaine
            $date_activité = date('Y-m-d', strtotime("{$year}-W{$week}-1"));  // Le '1' signifie le lundi de la semaine
        } else {
            echo "<script>alert('Erreur: Le format de la semaine est incorrect!');</script>";
            exit;  // Sortir si le format de semaine n'est pas valide
        }

        // Préparer la requête d'insertion
        $query = "INSERT INTO activite_sportive (id_utilisateur, type_activité, date_activité, durée_minutes) 
                  VALUES (?, ?, ?, ?)";

        // Préparer la requête SQL
        $stmt = $conn->prepare($query);

        // Vérification si la préparation échoue
        if ($stmt === false) {
            die("Erreur de préparation SQL : " . $conn->error);
        }

        // Lier les paramètres à la requête préparée
        // 'i' pour entier (id_utilisateur, durée_minutes)
        // 's' pour chaîne de caractères (type_activité, date_activité)
        $stmt->bind_param("issi", $id_utilisateur, $type_activité, $date_activité, $durée_minutes);

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "<script>alert('Séance enregistrée avec succès!');</script>";
        } else {
            echo "<script>alert('Erreur lors de l\'enregistrement : " . $stmt->error . "');</script>";
        }
    }
}








$conn->close();
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Body Tracker</title>
    <link rel="stylesheet" href="profil.css">
</head>



<style>
    body {
    margin: 0;
    display: flex;
    background-color: #f8f9fb;
    font-family: 'Work Sans', sans-serif;
}

.sidebar {
    width: 80px;
    background-color: #ffffff;
    padding: 20px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: fixed;
    height: 100%;
}

.logo {
    writing-mode: vertical-rl;
    margin-bottom: 30px;
    font-weight: bold;
}
.logo-mbt {
    height: 79px;
}
.sidebar nav ul {
    list-style: none;
    padding: 0;
}

.box {
    width: 380px;
    height: 200px;
    background-color: #0C8E7F;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: 730px;
}

.box-profil {
    width: 335px;
    height: 69px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px;
}



.box-profil img {
    width: 55px;
    height: 57px;
    margin-top: 6px;
    margin-left: 10px;
}


.box-profil p {
    margin-top: -53px;
    margin-left: 90px;
    top: 6%;
    font-size: larger;
    font-weight: 600;
}

.box-profil-user {
    width: 335px;
    height: 69px;
    background-color: #f5f5f5;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px;
}

.sidebar nav ul li {
    margin: 20px 0;
}

.sidebar nav ul li a {
    text-decoration: none;
    font-size: 24px;
}

.main-content {
    flex: 1;
    padding: 105px;
    background-color: #FAFAFA;
}

header h1 {
    margin: -77px 0 20px;
}

.stats {
    display: flex;
    gap: 110px;
    margin-bottom: 30px;
    height: 40%;
    width: 79%;
    margin-left: 6px;
}


.stats h3 {
    font-weight: 450;
    position: relative;
    top: -23%;
}

.stats img {
    position: relative;
    left: -85px;
    top: 4%;
    margin-left: -2px;
    width: 35px;
}



.card {
    flex: 1;
    background: #8fe6e6;
    color: #f8f9fb;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    font-weight: 500;
    height: 50%;
    box-shadow: 9px 16px 22px rgb(0 0 0 / 25%);
}

.card span {
    right: 7%;
    top: 0%;
    font-weight: 300;
    position: relative;
    font-size: xx-large;
}

.card p {
    position: absolute;
    margin-left: 134px;
    width: 20%;
    font-size: xxx-large;
    font-weight: 300;
    top: 115px;
    display: flex;
}


.card.highlighted {
    background: rgb(166, 238, 166);
}

.card.highlighted img {
    left: -76px;
    top: 4%;
}

.card.red {
    background: #FA5A7D;
}

.card.red img {
    margin-left: 25px;
    top: 5px;
    font-weight: 600;
    position: relative;
}


.card.purple {
    background: #8676FE;
}


.card.purple img {
    margin-left: 15px;
    top: 5px;
}

img .vector-image{
    left: -50%;
}

.activity-progress {
    display: flex;
    gap: 30px;
    margin-bottom: 45px;
    margin-left: 296px;
    left: -270px;
    position: relative;
}

.activity, .progression {
    flex: 1;
    background: white;
    border-radius: 10px;
    padding: 20px;
    height: 250px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
    margin-right: 90px;
    left: 17px;
    position: relative;
}

.comparison {
    background: white;
    border-radius: 10px;
    padding: 20px;
}

.comparaison-profil {
    position: relative;
    width: 400px;
    height: 200px;
    left: 25%;
    background-color: #0C8E7F; /* bleu */
    border-radius: 15px;
    margin: 50px auto;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.votre-profil {
    position: absolute;
    top: 25px;
    width: 300px;
    left: 35px;
    height: 45px;
    background-color: white;
    padding: 10px 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.autres-profil {
    position: absolute;
    width: 300px;
    top: 110px;
    left: 35px;
    height: 45px;
    background-color: white;  
    padding: 10px 15px;  
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.votre-profil img {
    width: 40px;
    height: 40px;
    left: 20px;
    top: 13px;
    position: absolute;
    border-radius: 50px;
}

.votre-profil p {
    margin: 5px 0 0;
    font-size: 14px;
}

.profile-sidebar {
    width: 300px;
    background: #F4F5F5;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    position: fixed;
    height: 100%;
    margin-left: 79%;
}

.user-info {
    text-align: center;
}

.user-info img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    right: 255px;
    position: absolute;
    object-fit: cover;
}

.info {
    position: relative;
    width: 315px;
    height: 85px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: -25px;
}

.info-poids {
    margin-left: -50%;
    font-weight: 600;
}

.info-taille {
    margin-left: 0%;
    font-weight: 600;
}

.info-age{
    margin-left: 50%;
    font-weight: 600;
}

.info-poids span {
    top: 51px;
    font-weight: 545;
    position: relative;
    left: -15px;
    font-size: larger;
    color: #6B6E7B;

}


.info-taille span {
    top: -46px;
    font-weight: 545;
    position: relative;
    left: 2px;
    font-size: larger;
    color: #6B6E7B;
}

.info-age span {
    top: -143px;
    font-weight: 545;
    position: relative;
    left: 25px;
    font-size: larger;
    color: #6B6E7B;
}

.info-poids p {
    margin-left: -30px;
    font-weight: 550;
    top: -25px;
    color: #6B6E7B;
    position: relative;
    font-size: x-large;
}

.info-taille p {
    margin-left: 5px;
    font-weight: 550;
    top: -123px;
    color: #6B6E7B;
    position: relative;
    font-size: x-large;
}

.info-age p {
    margin-left: 60px;
    font-weight: 550;
    top: -220px;
    color: #6B6E7B;
    position: relative;
    font-size: x-large;
}



.objectives-course, .monthly-progress, .scheduled {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
}

.objectives-poids, .monthly-progress, .scheduled {
    background: #fff;
    padding: 15px;
    border-radius: 10px;
}
.Course {
    position: relative;
    width: 315px;
    height: 85px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: -25px;
}

.Poids{
    position: relative;
    width: 315px;
    height: 85px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: -25px;
}
.Course p {
    margin-left: 25%;
    font-weight: 600;
}

.Poids p {
    margin-left: 20%;
    font-weight: 600;
}

.Course img {
    position: absolute;
    top: 15px;
    right: 255px;
}

.Poids img {
    position: absolute;
    top: 25px;
    right: 269px;
}

.scheduled ul {
    list-style: none;
    padding: 0;
}











.activity input[type="week"] {
    color: transparent;
    text-shadow: 0 0 0 #000;
    width: 145px;
    height: 35px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 17px;
    padding: 5px 25px;
    margin-left: 525px;
    top: 12%;
    position: absolute;
}






.chart {
    display: flex;
    align-items: flex-end;
    gap: 35px;
    height: 150px;
    padding: 20px;
}

  .bar {
    width: 30px;
    background-color: #e6effb;
    border-radius: 4px;
    position: relative;
  }

  .bar span {
    position: absolute;
    bottom: -20px;
    width: 100%;
    text-align: center;
    font-size: 14px;
    color: #666;
  }


  .bar:hover {
    background-color: #2f7d78;
  }


  .bar:hover::before {
    content: "";
    position: absolute;
    top: -30px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #1e2d45;
    color: white;
    font-size: 12px;
    padding: 3px 6px;
    border-radius: 5px;
  }

  /* Exemple de hauteurs personnalisées */
  .h20 { height: 40px; }
  .h40 { height: 80px; }
  .h60 { height: 120px; }
  .h80 { height: 160px; }
  .h100 { height: 160px; }
  



  .Course-activite {
    width: 385px;
    height: 60px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: 60%;
    top: -105%;
    transition: transform 250ms;
}


.Course-activite:hover {
    transform: translateY(-10px);}


.Muscu-activite {
    width: 385px;
    height: 60px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: 60%;
    top: -105%;
    transition: transform 250ms;
}


.Muscu-activite:hover {
    transform: translateY(-10px);}


.Nat-activite {
    width: 385px;
    height: 60px;
    background-color: #fff;
    border: 2px solid #ccc;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 16px;
    position: relative;
    left: 60%;
    top: -105%;
    transition: transform 250ms;
}

.Nat-activite:hover {
    transform: translateY(-10px);}

.Course-activite p{
    margin-left: 25%;
    font-weight: 600;
}


.Muscu-activite p {
    margin-left: 25%;
    font-weight: 600;
}

.Nat-activite p {
    margin-left: 25%;
    font-weight: 600;
}

.Course-activite img {
    position: absolute;
    top: 8px;
    right: 320px;
}

.Muscu-activite img {
    position: absolute;
    top: 10px;
    right: 327px;
    width: 34px;
}


.Nat-activite img {
    position: absolute;
    top: 10px;
    right: 327px;
    width: 34px;
}






</style>
<body>
<aside class="sidebar">
        <div class="logo"><img class="logo-mbt" src="logo (2).png" alt="logo"></div>
        <nav>
            <ul>
                <li><a href="accueil.php" ><img src="Frame 6 (1).png" alt="Accueil-img"></a></li>
                <li><a href="#"><img src="Two-user.png" alt="communauté"></a></li>
                <li><a href="#"><img src="profil.png" alt="profil-img"></a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header>
            <h1>BON RETOUR </h1>
        </header>

        <section class="stats">
            <div class="card"><img src="wallet.png" alt="running-img"> <span>POIDS</span><p><?php echo htmlspecialchars($poids); ?>     kg</p></div>
            <div class="card highlighted"><img src="wallet.png" alt="running-img"> <span>TAILLE</span><p><?php echo htmlspecialchars($taille); ?>   cm</p></div>
            <div class="card purple"><img src="Water.png" alt="coeur-img"><span>AGE</span><p><?php echo htmlspecialchars($age); ?>   ans</p> </div>
        </section>

<section class="activity-progress">
    <div class="activity">
        <h2>Votre Activité</h2>

        <!-- Sélection de la semaine -->
        <input type="week" id="semaine" name="semaine" required>

        <div class="chart">
            <!-- Graphique pour les jours de la semaine -->
            <div class="bar h20" data-jour="Lundi" data-activity="Running"><span>Lun</span></div>
            <div class="bar h60" data-jour="Mardi" data-activity="Musculation"><span>Mar</span></div>
            <div class="bar h40" data-jour="Mercredi" data-activity="Natation"><span>Mer</span></div>
            <div class="bar h60" data-jour="Jeudi" data-activity="Running"><span>Jeu</span></div>
            <div class="bar h100 highlight" data-jour="Vendredi" data-activity="Musculation"><span>Ven</span></div>
            <div class="bar h60" data-jour="Samedi" data-activity="Natation"><span>Sam</span></div>
            <div class="bar h40" data-jour="Dimanche" data-activity="Running"><span>Dim</span></div>
        </div>

        <!-- Formulaire caché pour envoyer les données à la base -->
        <form id="activityForm" method="POST" style="display: none;">
            <!-- Champs cachés mis à jour -->
            <input type="hidden" name="type_activité" id="activityType">
            <input type="hidden" name="semaine" id="semaineInput"> <!-- Ce sera notre "date_activité" -->
            <input type="hidden" name="durée_minutes" id="hours">
            <button type="submit">Enregistrer la séance</button>
        </form>

    </div>
</section>

<script>
    const bars = document.querySelectorAll('.bar');
    const form = document.getElementById('activityForm');
    const selectedDay = document.getElementById('selectedDay');
    const activityType = document.getElementById('activityType');
    const hoursInput = document.getElementById('hours');
    const semaineInput = document.getElementById('semaineInput');

    bars.forEach(bar => {
        bar.addEventListener('click', () => {
            const jour = bar.getAttribute('data-jour');
            const activity = bar.getAttribute('data-activity');

            // Demander à l'utilisateur combien d'heures il a fait pour cette activité
            const hours = prompt(`Entrez le nombre d'heures pour ${jour} (${activity}):`, '1');
            
            if (hours !== null && !isNaN(hours) && hours >= 0) {
                // Limiter la hauteur à 130px maximum
                const newHeight = Math.min(130, hours * 130); // 1h = 130px max
                bar.style.height = `${newHeight}px`;

                // Remplir les champs cachés du formulaire
                activityType.value = activity;
                hoursInput.value = hours;
                semaineInput.value = document.getElementById('semaine').value; // Semaine = date_activité

                // Afficher les valeurs dans la console pour débogage
                console.log("Type d'activité: " + activityType.value);
                console.log("Semaine (date_activité): " + semaineInput.value);
                console.log("Heures: " + hoursInput.value);

                // Soumettre le formulaire
                form.submit();
            } else {
                alert("Veuillez entrer un nombre valide d'heures.");
            }
        });
    });
</script>




        <section class="comparison">
            <h2>Profil de Comparaison </h2>
            <div class="comparison-graph">
                <div class="box">
                    <div class="box-profil">
                        <img src="images.png" alt="Thomas Fletcher">
                        <p>Votre Profil</p>
                    </div>
                    <div class="box-profil-user"></div>
                </div>
            </div>
        </section>
    </main>

    <aside class="profile-sidebar">
        <div class="user-info">
            <img src="images.png" alt="Thomas Fletcher">
            <h3><?php echo htmlspecialchars($prenom); ?>  <?php echo htmlspecialchars($nom); ?></h3>
            <p>France</p>
            <div class="info">
            <div class="info-poids"><span>Poids</span><p><?php echo htmlspecialchars($poids); ?> kg</p></div>
            <div class="info-taille"><span>Taille</span><p><?php echo htmlspecialchars($taille); ?> cm</p></div>
            <div class="info-age"><span>Age</span><p><?php echo htmlspecialchars($age); ?> ans</p></div>
        </div>
        </div>

        <div class="objectives">
            <h3>Votre Objectif</h3>
            <div class="Course"><img src="Image (1).png" alt="course-img"><p>Course </p></div>
           <div class="Poids"><img src="image 9 (1).png" alt="feu-img"><p>Poids</p></div>
        </div>



        <h3>Progression par Mois</h3>
        <div class="monthly-progress">
            
        </div>
      
        <h3>Programmé</h3>
        <div class="scheduled">
            
            <ul>
                <li>Entraînement - Fractionné (22 Mar)</li>
                <li>Entraînement - Pecs/Dos (22 Mar)</li>
            </ul>
        </div>
    </aside>
</body>
</html>

