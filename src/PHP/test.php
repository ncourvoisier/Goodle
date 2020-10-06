<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)


if (isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
  redirige('../../index.php');
}

goodle_header();
html_debut('Goodle | Voir event', '../src/CSS/styles.css');
l_contenu_ve();
html_fin();
ob_end_flush();

function l_contenu_ve() {
	$bd = bd_connect();
	$sql3 = 'SELECT * FROM Dateevenement NATURAL JOIN Date WHERE IDEvent = 4';
	$res3 = mysqli_query($bd, $sql3);
	
	while ($t3 = mysqli_fetch_assoc($res3)) {
		echo print_r($t3);
		echo '<br/>';
	}
	
}

?>