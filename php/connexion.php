<?php

$user = 'root';
$pass = '';
$bd = new mysqli('localhost', $user, $pass, 'bd_projet_php');

if ($bd->connect_error) {
    die("Erreur de connexion à la base de données: " . $bd->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail_reçu = $_POST['email'];
    $mdp_reçu = $_POST['password'];

   
    $requete_vérif = $bd->prepare('SELECT ID, nom, email FROM clients WHERE email=? AND mot_de_passe=?');
    $requete_vérif->bind_param("ss", $mail_reçu, $mdp_reçu);
    $requete_vérif->execute();
    $resultat = $requete_vérif->get_result();


    if ($resultat->num_rows > 0) {
       
        $user = $resultat->fetch_assoc();

        session_start();
        $_SESSION['client'] = $user;
        echo "<script>window.location.href = '../index.html';</script>";
        exit();
    } else {
        echo "<script> alert('Identifiants incorrects. Veuillez réessayer.')</script> ";
    }

    $requete_vérif->close();
}

$bd->close();
?>
