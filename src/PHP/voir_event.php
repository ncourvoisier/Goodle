<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

// si $_GET et $_POST non vide
($_GET && $_POST) && l_control_piratage();

// si utilisateur déjà authentifié, on le redirige sur la page appelante, ou à défaut sur l'index
if (isset($_SESSION['ID'])){
    $page = '../../index.php';
    if (isset($_SERVER['HTTP_REFERER'])){
        $page = $_SERVER['HTTP_REFERER'];
        $nom_page = url_get_nom_fichier($page);
        // suppression des éventuelles boucles de redirection
        if (($nom_page == 'login.php') || ($nom_page == 'inscription.php')){
            $page = '../../index.php'; 
        } // si la page appelante n'appartient pas à notre site
        else if (! in_array($nom_page, get_pages_goodle())){
            $page = '../../index.php';
        }
    }
    redirige($page);
}

// si $_POST non vide
$err = ($_POST) ? l_traitement_connexion() : 0;

html_debut('Goodle | Connexion', '../src/CSS/styles.css');


if(isset($_GET['IDEvent']) {
	echo 'Salut1';
	l_contenu_event($_GET['IDEvent']);
} else {
	echo 'Salut2';
	l_contenu_ve($err);
}

html_fin();

ob_end_flush();


function l_contenu_event($IDEvent) {
	$bd = bd_connect();
	
	$sql = "SELECT $IDEvent FROM `Evenement`";
	
	$res = mysqli_query($bd,$sql) or bd_erreur($bd,$sql);
	
	echo 'Evenement : <br/>';
	
	$t = mysqli_fetch_assoc($res);
	echo 'Nom : ', $t['Nom'], ' Lieu : ', $t['Lieu'], ' Date de cloture des votes : ', $t['DateCloture'];
	echo '<br/>';
	
	mysqli_free_result($res);
    mysqli_close($bd);
}


function l_contenu_ve($err){

	$bd = bd_connect();
	
	$sql = "SELECT * FROM `Evenement` ORDER BY ID DESC";
	
	$res = mysqli_query($bd,$sql) or bd_erreur($bd,$sql);
	
	echo 'Liste des événements triés par ordre du plus récent : <br/>';
	
	while ($t = mysqli_fetch_assoc($res)) {
		echo 'Nom : ', $t['Nom'], ' Lieu : ', $t['Lieu'], ' Date de cloture des votes : ', $t['DateCloture'];
		echo '<br/>';
	}
	
	mysqli_free_result($res);
    mysqli_close($bd);
}

?>
