<?php 
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $heure = $_POST['heure'];

    //Ajouter 1 heure 
    $heure_datetime = new DateTime($heure);
    $heure_datetime->modify('+1 hour');
    $heure_plus_une_heure = $heure_datetime->format('H:i:s');


    $user = 'root';
    $pass = '';
    $bd = new mysqli('localhost', $user, $pass, 'bd_projet_php');

    if ($bd->connect_error) {
        die("Erreur de connexion à la base de données: " . $bd->connect_error);
    }

    $requete_disponibilite = $bd->prepare('SELECT * FROM reservations WHERE Date_reservation = ? AND (TIME(Heure_reservation) <= TIME(?) AND TIME(Heure_reservation) > TIME(?))');
    $requete_disponibilite->bind_param("sss", $date, $heure_plus_une_heure, $heure);
    $requete_disponibilite->execute();
    $resultat_disponibilite = $requete_disponibilite->get_result();

    if ($resultat_disponibilite->num_rows > 0) {
        echo "<script>alert('Désolé, cette heure est déjà réservée. Veuillez choisir une autre heure.')</script>";
    } else {
        $client_id = $_SESSION['client']['ID'];
        $requete_reservation = $bd->prepare('INSERT INTO reservations(Date_reservation, Heure_reservation,RefClient) VALUES (?, ?, ?)');
        $requete_reservation->bind_param("ssi", $date, $heure,$client_id);
        $resultat_reservation = $requete_reservation->execute();

        if ($resultat_reservation === true) {
            echo "Réservation effectuée avec succès pour le $date à $heure.";
        } else {
            echo "Erreur lors de l'enregistrement de la réservation. Veuillez réessayer.";
        }
    }

    $requete_disponibilite->close();
    $requete_reservation->close();
    $bd->close();
} else {
    if(!isset($_SESSION['client'])){
        echo "<script>window.location.href = '../connexion.html';</script>";
        exit();
    }else{
        echo "<script>window.location.href = '../reserver.html';</script>";
        exit();
    }
}

?>
