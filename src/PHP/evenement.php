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

$event = l_control_get ();
html_debut('Goodle | Voir event', '../src/CSS/styles.css');
l_contenu_event($event);

ob_end_flush();

function l_contenu_event($event) {
	
	$bd = bd_connect();
	$event = bd_protect($bd, $event);
	$sql = "SELECT * FROM `Evenement` WHERE ID = $event";

	$res = mysqli_query($bd, $sql) or fd_bd_erreur($bd,$sql);
	
	while ($t = mysqli_fetch_assoc($res)) {
		echo 'Nom : ', $t['Nom'], ' Lieu : ', $t['Lieu'], ' Date de cloture des votes : ', $t['DateCloture'], '<br/>';
	}

	mysqli_free_result($res);
	mysqli_close($bd);
}

function l_control_get (){

	(count($_GET) != 1) && fd_exit_session();
	!isset($_GET['event']) && fd_exit_session();

    $valueQ = trim($_GET['event']);
    $notags = strip_tags($valueQ);

    (mb_strlen($notags, 'UTF-8') != mb_strlen($valueQ, 'UTF-8')) && fd_exit_session();
    
	return $valueQ;
}


?>
