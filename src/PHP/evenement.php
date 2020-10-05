<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

goodle_header();

if (isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
  redirige('../../index.php');
}

// si $_POST non vide
$err = ($_POST) ? l_traitement_connexion() : 0;

$user_deleted = 0;
$deleting_error = 0;
$no_user = 0;

if (isset($_GET['remove_event'])) {
	if (!preg_match('/^[0-9]*$/', $_GET['remove_event'])) {
		$deleting_error = 1;
	}
	if ($deleting_error == 0) {
			$bd = bd_connect();

		$sql = 'SELECT * FROM Evenement WHERE ID = \'' . $_GET['remove_event'] . '\';';

		$res = mysqli_query($bd, $sql);

		if (mysqli_num_rows($res) == 0) {
		$no_user = 1;
		} else {
			$sql = 'DELETE FROM Dateevenement WHERE IDEvent = ' . $_GET['remove_event'] . ';';
			$result = mysqli_query($bd, $sql);
			if ($result) {
				$user_deleted = 1;
			} else {
				$deleting_error = 1;
			}
			
			$sql = 'DELETE FROM Evenement WHERE ID = ' . $_GET['remove_event'] . ';';
			$result = mysqli_query($bd, $sql);
			if ($result) {
				$user_deleted = 1;
			} else {
				$deleting_error = 1;
			}
		}
		if ($res) {
			mysqli_free_result($res);
		}

		mysqli_close($bd);
	}
}

html_debut('Goodle | Evenement', '../src/CSS/styles.css');

l_contenu_ve($err);
l_notifications_ve($user_deleted, $deleting_error, $no_user);

html_fin();

ob_end_flush();



function l_contenu_ve($err){
	$bd = bd_connect();
	$sql = "SELECT * FROM `evenement` ORDER BY ID DESC";
	$res = mysqli_query($bd,$sql) or bd_erreur($bd,$sql);
	echo '<h2>Liste des événements triés par ordre des plus récent : </h2><ul>';

	while ($t = mysqli_fetch_assoc($res)) {
		echo '<li>Nom : ', $t['Nom'], ' Lieu : ', $t['Lieu'], ' Date de cloture des votes : ', $t['DateCloture'], 
		' <a href="voir_event.php?event=' . $t['ID'] . '"">voir event </a></li>';
	}
}

function l_notifications_ve($user_deleted, $deleting_error, $no_user) {
	if ($user_deleted) {
		echo '<p class="success">L\'evenement ' . $_GET['remove_event'] . ' a bien été supprimé.</p>';
	} else if ($deleting_error) {
		echo '<p class="erreur">La suppression de l\'evenement ' . $_GET['remove_event'] . ' a rencontré un problème.</p>';
	} else if ($no_user) {
		echo '<p class="erreur">L\'evenement ' . $_GET['remove_event'] . ' n\'existe pas.</p>';
	}
}

?>