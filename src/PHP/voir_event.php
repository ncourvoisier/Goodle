<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)


$errors = l_controle_piratage_ve();
html_debut('Goodle | Voir event', '../src/CSS/styles.css');

goodle_header();

l_contenu_ve($errors);
html_fin();
ob_end_flush();

function l_contenu_ve($errors){
	$bd = bd_connect();
	if (count($errors) != 0) {
		foreach ($errors as $code => $e) {
		echo '<p class="erreur">' . $e . '</p>';
		}
	} else {

    $event = $_GET['event'];

    if (!isset($_SESSION['ID'])) {
    	echo '<p>Vous n\'êtes pas connecté, connectez vous : <a href="./login.php">Connexion</a>';
      return;
    }

    $sql = "SELECT Referent FROM Evenement WHERE ID = $event";
    $res = mysqli_query($bd, $sql);

    if (mysqli_num_rows($res) != 1) {
      mysqli_free_result($res);
      mysqli_close($bd);
      echo '<p>Cet évènement n\'existe pas</p>';
      return;
    }

    $t = mysqli_fetch_assoc($res);
  	$idPersonne = $t['Referent'];

    if ($idPersonne != $_SESSION['ID']){
      echo '<p>Vous n\'êtes pas le créateur de cet évènement</p><p>Retour à la page d\'accueil : <a href="../../index.php">Accueil</a></p>';
      return;
    }
		echo '<h2>Evenement ' . $event .'</h2>' . '<ul>';

		$sql = 'SELECT * FROM Evenement WHERE ID = ' . $_GET['event'] . ';';
		$res = mysqli_query($bd, $sql);

		$t = mysqli_fetch_assoc($res);
		if (!$t) {
		  echo '<p class="erreur">L\'evenement ' . $_GET['event'] . ' n\'existe pas.';
		} else {
			foreach ($t as $field => $value) {
				if ($field == "Referent") {
					$sql2 = 'SELECT * FROM Personne WHERE ID = ' . $value . ';';
					$res2 = mysqli_query($bd, $sql2);
					$t2 = mysqli_fetch_assoc($res2);
					echo $field . ' : ' . $t2['Prenom'] . ' ' . $t2['Nom'] . '. Username : ' . $t2['Username'] . ', Email : ' . $t2['Email'];
				} else {
					echo $field . ' : ' . $value;
				}
				echo '</br>';
			}

			$sql3 = 'SELECT * FROM Dateevenement NATURAL JOIN Date WHERE IDEvent = 4';
			$res3 = mysqli_query($bd, $sql3);

			echo '<ul>';
			while ($t3 = mysqli_fetch_assoc($res3)) {
				echo '<li>Date : Le ' . $t3['Jour'] . ' ' . get_mois($t3['Mois']) . ' ' . $t3['Annee'] . ' à ' . $t3['Heure'] . 'h' . $t3['Minute'] . '</li>';
			}
			echo '</ul>';

      echo '<p> Ajouter une date à cet évènement : <a href="./ajouter_date_evenement?event='.$event.'">Ajouter Date</a></p>';

			if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
				echo '</br><a href="evenement.php?remove_event=' . $_GET['event'] . '"><button>Supprimer</button></a>';
			}
		}
	}
}

function l_controle_piratage_ve() {
	$err = array();
	if (!isset($_GET['event'])) {
		$err['no_event'] = "L'evenement doit être renseigné.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;
}

?>
