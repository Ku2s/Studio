<?php

$user = 'root';
$pass = '';
$bd = new mysqli('localhost', $user, $pass, 'bd_projet_php');

if ($bd->connect_error) {
    die("Erreur de connexion à la base de données: " . $bd->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_reçu =$_POST['nom'];
    $tel_reçu = $_POST['telephone'];
    $mail_reçu = $_POST['email'];
    $mdp_reçu = $_POST['password'];

   
    $requete_vérif = $bd->prepare('INSERT INTO clients(Nom,Email,Mot_de_passe,Telephone) VALUES (?,?,?,?)');
    $requete_vérif->bind_param("ssss",$nom_reçu, $mail_reçu, $mdp_reçu,$tel_reçu);
    $resultat =  $requete_vérif->execute();

    if ($resultat === true) {
        session_start();
        $_SESSION['client'] = ['nom' => $nom_reçu, 'email' => $mail_reçu];
        echo "<script>window.location.href = '../index.html';</script>";
        exit();
    } else {
        echo "<script> alert('Erreur lors de l'ajout du client. Veuillez réessayer.')</script>";
    }
    $requete_vérif->close();
}

$bd->close();
?>
