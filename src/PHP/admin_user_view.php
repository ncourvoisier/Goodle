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
if (isset($_SESSION['admin']) && $_SESSION['admin'] == 0) {
  redirige('../../index.php');
} else if (!isset($_SESSION['ID'])){
  redirige('login.php');
}

$user_deleted = 0;
$deleting_error = 0;
$no_user = 0;

if (isset($_GET['remove_user'])) {
  if (!preg_match('/^[0-9]*$/', $_GET['remove_user'])) {
    $deleting_error = 1;
  }
  if ($deleting_error == 0) {
    $bd = bd_connect();

    $sql = 'SELECT * FROM Personne WHERE ID = \'' . $_GET['remove_user'] . '\';';

    $res = mysqli_query($bd, $sql);

    if (mysqli_num_rows($res) == 0) {
      $no_user = 1;
    } else if (mysqli_fetch_assoc($res)['Admin']) {
      $deleting_error = 1;
    } else {
      $sql = 'DELETE FROM Personne WHERE ID = ' . $_GET['remove_user'] . ';';

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

html_debut('Goodle | Connexion', '../CSS/style.css');

goodle_header();

l_notifications_auv($user_deleted, $deleting_error, $no_user);

l_contenu_auv();

html_fin();

function l_notifications_auv($user_deleted, $deleting_error, $no_user) {
  if ($user_deleted) {
    echo '<p class="alert alert-success">L\'utilisateur ' . $_GET['remove_user'] . ' a bien été supprimé.';
  } else if ($deleting_error) {
    echo '<p class="alert alert-danger">La suppression de l\'utilisateur ' . $_GET['remove_user'] . ' a rencontré un problème.';
  } else if ($no_user) {
    echo '<p class="alert alert-danger">L\'utilisateur ' . $_GET['remove_user'] . ' n\'existe pas.';
  }
}

function l_contenu_auv() {
  $bd = bd_connect();
  echo '<h2>Gestion des utilisateurs</h2>' .
  '<ul>';

  $sql = 'SELECT ID, Username FROM Personne;';
  $res = mysqli_query($bd, $sql);

  while ($t = mysqli_fetch_assoc($res)) {
    echo '<li><a href="admin_one_user_view.php?user=' . $t['ID'] . '"">' . $t['Username'] . '</a></li>';
  }
}

 ?>
