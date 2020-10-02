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

$errors = l_controle_piratage_aouv();

html_debut('Goodle | Connexion', '../CSS/style.css');

goodle_header();

l_contenu_aouv($errors);
//l_contenu_aouv([]);

html_fin();

function l_contenu_aouv($errors) {
  $bd = bd_connect();
  if (count($errors) != 0) {
    foreach ($errors as $code => $e) {
      echo '<p class="erreur">' . $e . '</p>';
    }
  } else {
    echo '<h2>Utilisateur ' . $_GET['user'] .'</h2>' .
    '<ul>';

    $sql = 'SELECT * FROM Personne WHERE ID = ' . $_GET['user'] . ';';
    $res = mysqli_query($bd, $sql);

    $t = mysqli_fetch_assoc($res);
    if (!$t) {
      echo '<p class="erreur">L\'utilisateur ' . $_GET['user'] . ' n\'existe pas.';
    } else {
      foreach ($t as $field => $value) {
        echo '<p>' . $field . ' : ' . $value . '</p>';
      }
      echo '<a href="admin_user_view.php?remove_user=' . $_GET['user'] . '"><button>Supprimer</button></a>';
    }
  }

}

function l_controle_piratage_aouv() {
  $err = array();
  if (! isset($_GET['user'])) {
    $err['no_user'] = "L'utilisateur doit être renseigné.";
  } else if (!preg_match('/^[0-9]*$/', $_GET['user'])) {
    $err['user_format'] = "L'identifiant de l'utilisateur doit être un nombre.";
  }
  return $err;
}
 ?>
