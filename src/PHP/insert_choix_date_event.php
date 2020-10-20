<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)


//$errors = l_controle_piratage_ve();
html_debut('Goodle | Voir event', '../src/CSS/styles.css');

goodle_header();
if (isset($_SESSION['ID'])) {
	echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
}

$bd = bd_connect();

/*if (count($errors) != 0) {
    foreach ($errors as $code => $e) {
    echo '<p class="erreur">' . $e . '</p>';
    }
} else {*/

    //$sql = "UPDATE Evenement SET DateChoisie =  '$nom', Lieu= '$lieu', DateCloture = $aaaammjj WHERE ID = $idEvent ;";
    $sql = 'SELECT * FROM Date WHERE ID = '.$_GET['dateEvent'].';';
    $res = mysqli_query($bd, $sql);
    $t = mysqli_fetch_assoc($res);

    $date = $t['Annee'].'-'.$t['Mois'].'-'.$t['jour'].' '.$t['Heure'].':'.$t['Minute'].':00' ;

    $sql = 'UPDATE Evenement SET DateChoisie = '.$date.' WHERE ID = '.$_GET['event'].';';
    $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
    echo '<p> la date a bien été choisi </p>';

    
//}
mysqli_close($bd);
html_fin();
ob_end_flush();

?>