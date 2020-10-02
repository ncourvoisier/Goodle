<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL);

/*if (isset($_SESSION['ID'])) {
  $sql = 'SELECT * FROM Personne WHERE id = $_SESSION["id"]';
  $res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
  $t = mysqli_fetch_assoc($res);
  mysqli_free_result($res);
  if (!$t['Admin']) {
    mysqli_close($bd)
    redirige('../../index.php');
  }*/
if (!isset($_SESSION['admin'])) {
  redirige('../../index.php');
} else if (!isset($_SESSION['ID'])){
  redirige('login.php');
}

html_debut('Goodle | Connexion', '../CSS/style.css');

goodle_header();

l_contenu_auv();

html_fin();

function l_contenu_auv() {
  $bd = bd_connect();
  echo '<h2>Gestion des utilisateurs</h2>' .
  '<ul>';

  $sql = 'SELECT ID FROM Personne;';
  $res = mysqli_query($bd, $sql);

  while ($t = mysqli_fetch_assoc($res)) {
    echo '<li><a href="admin_one_user_view.php?user=' . $t['ID'] . '"">' . $t['ID'] . '</a></li>';
  }
}

 ?>
