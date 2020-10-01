<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();    // Lancement de la session

require_once './src/PHP/bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

html_debut('Goodle', './src/CSS/styles.css');

goodle_header('.');

l_contenu();

html_fin();

ob_end_flush();


function l_contenu() {

	echo
		'<h1>Bienvenue sur Goodle !</h1>',
		'<p>Connectez-vous ou inscrivez-vous et gérez pleinement les dates de vos événements ! </p>',
		'<p>Pas encore connecté ? C\'est par <a href="./src/PHP/login.php">ici</a>. </p>',
		'<p>Nouveau venu sur Goodle ? Consultez notre <a href="./src/PHP/inscription.php">page d\'inscription</a> !',
		'<p><a href="./src/PHP/ajout_evenement.php">Ajouter un évènement</a></p>';

}

?>
