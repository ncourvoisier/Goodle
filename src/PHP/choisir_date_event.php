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

    $sql = 'SELECT * FROM Evenement WHERE ID = ' . $_GET['event'] . ';';
    $res = mysqli_query($bd, $sql);

    $t = mysqli_fetch_assoc($res);

    if (!$t) {
        echo '<p class="erreur">L\'evenement ' . $_GET['event'] . ' n\'existe pas.';
    } else {
        $idPersonne = $t['Referent'];

        $sql3 = 'SELECT * FROM DateEvenement INNER JOIN Date ON DateEvenement.IDDate = Date.ID AND DateEvenement.IDEvent ='.$_GET['event'].' ' . $order . ';';
        $res3 = mysqli_query($bd, $sql3);

        echo '<ul id="event_dates">';

        $createurConnecte = ($idPersonne == $_SESSION['ID']);

        while ($t3 = mysqli_fetch_assoc($res3)) {
            echo '<li>Date : Le ' . $t3['Jour'] . ' ' . get_mois($t3['Mois']) . ' ' . $t3['Annee'] . ' à ' . ecrireHeure($t3['Heure'],$t3['Minute']);
            if ($createurConnecte){
                //echo ' <a href="./supprimer_date_event?dateEvent='.$t3['ID'].'&event='.$_GET['event'].'">Supprimer</a>';
                echo ' <a href="./insert_choix_date_event?dateEvent='.$t3['ID'].'&event='.$_GET['event'].'">Choisir</a>';
            }
            echo '</li>';
        }
    }
    mysqli_close($bd);

//}

html_fin();
ob_end_flush();

/*function l_controle_piratage_ve() {
	$err = array();
	if (!isset($_GET['event'])) {
		$err['no_event'] = "L'evenement doit être renseigné.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;
}*/

?>