<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)


$errors = l_controle_piratage_sde();
html_debut('Goodle | Supprimer Date Evenement', '../src/CSS/styles.css');

goodle_header();


$deja_supp = isset($_POST['btnValiderSupprDate']);

if($deja_supp){
  l_delete_date($errors);
}

l_contenu_sde($errors, $deja_supp);
html_fin();
ob_end_flush();

function l_controle_piratage_sde() {
	$err = array();
	if (!isset($_GET['dateEvent'])) {
		$err['date_event'] = "L'id de la date doit être renseignée.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['dateEvent'])) {
		$err['date_event'] = "L'identifiant de la date doit être un nombre.";
	}

  if (!isset($_GET['event'])) {
		$err['event'] = "L'id de l'évènement' être renseignée.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['dateEvent'])) {
		$err['event'] = "L'identifiant de l'évènement doit être un nombre.";
	}

	return $err;
}

function l_contenu_sde($errors, $deja_supp){
  $bd = bd_connect();
	if (count($errors) != 0) {
		foreach ($errors as $code => $e) {
		echo '<p class="erreur">' . $e . '</p>';
		}
	}

  $dateSupp = $_GET['dateEvent'];
  $eventID = $_GET['event'];

  if (!isset($_SESSION['ID'])) {
    echo '<p>Vous n\'êtes pas connecté, connectez vous : <a href="./login.php">Connexion</a>';
    return;
  }

  if($deja_supp){
    echo '<p>Cette date a bien été supprimée</p>';
    echo '<p><a href="./voir_event.php?event='.$eventID.'">Retour</a>';
    return;
  }

  //Verif que ce soit bien le créateur connecté

  $sql = "SELECT Referent FROM Evenement WHERE ID=$eventID;";

  $res = mysqli_query($bd, $sql);

  $tmpIDRef = mysqli_fetch_assoc($res);

  $idReferent = $tmpIDRef['Referent'];

  if ($idReferent != $_SESSION['ID']){
    echo '<p>Vous n\'êtes pas le créateur de l\'évènement, vous ne pouvez donc pas supprimer de date</p>
          <p><a href="../../index.php">Retour accueil</a></p>';

    return;
  }

  $sql = "SELECT IdDate FROM DateEvenement WHERE ID=$dateSupp;";

  $res = mysqli_query($bd, $sql);

  $tmpIdDate = mysqli_fetch_assoc($res);

  $idDate = $tmpIdDate['IdDate'];

  $sql = "SELECT Jour, Mois, Annee, Heure, Minute FROM Date WHERE ID=$dateSupp;";

  $res = mysqli_query($bd, $sql);

  $champsDateSupp = mysqli_fetch_assoc($res);

  echo '<h2>Suppression de la date '.$champsDateSupp['Jour'].' '.get_mois($champsDateSupp['Mois']).' '.$champsDateSupp['Annee'].' à '.ecrireHeure($champsDateSupp['Heure'],$champsDateSupp['Minute']).'</h2>';

  echo 'Etes vous sûr de bien vouloir supprimer cette date ?';

  echo '<form method=POST action="supprimer_date_event.php?dateEvent='.$dateSupp.'&event='.$_GET['event'].'">',
  form_input(Z_SUBMIT,'btnValiderSupprDate','Supprimer la date'),
  '</form>';

}

function l_delete_date($errors){
  if (count($errors) == 0){
    $bd = bd_connect();

    $dateSupp = $_GET['dateEvent'];
	
	//echo $dateSupp . ' ';
	

    $sql = "SELECT ID FROM DateEvenement WHERE IDDate=$dateSupp;";

    $res = mysqli_query($bd, $sql);

    $tmpIdDate = mysqli_fetch_assoc($res);

    $idDate = $tmpIdDate['ID'];

    $sql = "DELETE FROM DateEvenement WHERE ID=$idDate;";

    $res = mysqli_query($bd, $sql);

    $sql = "DELETE FROM Date WHERE ID=$dateSupp;";

    $res = mysqli_query($bd, $sql);
  }
}

?>
