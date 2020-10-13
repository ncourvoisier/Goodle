<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Reponse invitation', '../CSS/style.css');

goodle_header();

echo '<p>Votre réponse a bien été enregistrée !</p>',
'<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';

html_fin();

ob_end_flush();


?>
