<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Evènement', '../CSS/style.css');

goodle_header();

echo '<p>L\'évènement a bien été ajouté !</p>',
'<p><a href="../../index.php">Retour à la page d\'accueil</a><p>',
'<p>Visualiser l\'évènement';

html_fin();

ob_end_flush();


?>
