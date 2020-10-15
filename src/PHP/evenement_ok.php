<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Evènement', '../CSS/style.css');

goodle_header();

$id_event = isset($_GET['event'])?$_GET['event']:0;

if ($id_event == 0){
  echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
} else {

  echo '<p>L\'évènement a bien été ajouté !</p>',
  '<p>Votre lien d\'invitation est : <a id="lien_invitation" href="./voir_event.php?event='.$id_event.'">Voir Evenement</a></p>',
  '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
}



html_fin();

ob_end_flush();


?>
