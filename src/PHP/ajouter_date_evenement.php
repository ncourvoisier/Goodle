<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

$errorsPiratage = l_controle_piratage_event();
$errorsChamps = isset($_POST['btnValiderAjoutDate']) ? l_verify_ajout_date() : array();
html_debut('Goodle | Date Event', '../CSS/style.css');

goodle_header();

l_contenu($errorsPiratage, $errorsChamps);
html_fin();
ob_end_flush();

function l_controle_piratage_event() {
	$err = array();
	if (!isset($_GET['event'])) {
		$err['no_event'] = "Le numéro d'évènement doit être renseigné";
	} else if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;
}


function l_contenu($errorsPiratage, $errorsChamps){
  $bd = bd_connect();
	if (count($errorsPiratage) != 0) {
		foreach ($errorsPiratage as $code => $e) {
		echo '<p class="erreur">' . $e . '</p>';
		}
	} else {

    $event = $_GET['event'];

    if (!isset($_SESSION['ID'])) {
    	echo '<p>Vous n\'êtes pas connecté, connectez vous : <a href="./login.php">Connexion</a>';
      return;
    }


    $sql = "SELECT Referent FROM Evenement WHERE ID = $event";
    $res = mysqli_query($bd, $sql);

    if (mysqli_num_rows($res) != 1) {
      mysqli_free_result($res);
      mysqli_close($bd);
      echo '<p>Cet évènement n\'existe pas</p>';
      return;
    }

    $t = mysqli_fetch_assoc($res);
  	$idPersonne = $t['Referent'];

    if ($idPersonne != $_SESSION['ID']){
      echo '<p>Vous n\'êtes pas le créateur de cet évènement</p><p>Retour à la page d\'accueil : <a href="../../index.php">Accueil</a></p>';
      return;
    }

    if (count($errorsChamps) != 0) {
      echo '<p class="erreur">Erreurs : ';
      foreach ($errorsChamps as $v) {
        echo '<br> - ', $v;
      }
      echo '</p>';
    }

    $date_j = isset($_POST['DateEvent_j'])?$_POST['DateEvent_j']:1;
    $date_m = isset($_POST['DateEvent_m'])?$_POST['DateEvent_m']:1;
    $date_a = isset($_POST['DateEvent_a'])?$_POST['DateEvent_a']:2020;
    $heure_h = isset($_POST['DateEvent_hr'])?$_POST['DateEvent_hr']:0;
    $heure_m = isset($_POST['DateEvent_min'])?$_POST['DateEvent_min']:0;

    echo '<h2>Ajouter une date à l\'évènement ' . $event .'</h2>' . '<ul>';

    echo '<form method="POST" action="ajouter_date_evenement.php?event='.$event.'">',
    '<table>',
    form_ligne('Date * :', form_date_and_hour('DateEvent', $date_j, $date_m, $date_a, $heure_h, $heure_m)),
    '<tr><td colspan="2" style="padding-top: 10px;" class="centered">', form_input(Z_SUBMIT,'btnValiderAjoutDate','Valider'), '</td></tr>',
    '</table>',
    '</form>';

  }
}

function l_verify_ajout_date(){
  $err = array();
  /*
   * VERIFICATION DES DATES
   *
   */
   $date_j = isset($_POST['DateEvent_j'])?$_POST['DateEvent_j']:1;
   $date_m = isset($_POST['DateEvent_m'])?$_POST['DateEvent_m']:1;
   $date_a = isset($_POST['DateEvent_a'])?$_POST['DateEvent_a']:2020;
   $heure_h = isset($_POST['DateEvent_hr'])?$_POST['DateEvent_hr']:0;
   $heure_m = isset($_POST['DateEvent_min'])?$_POST['DateEvent_min']:0;

   $event = $_GET['event'];

   $res = compare_date($date_j, $date_m, $date_a, $heure_h, $heure_m);
   if ($res == 1){
     $err['Date'] = "[Date] Veuillez choisir une date future";
   } else if ($res == 2){
     $err['Date'] = "[Heure Evènement] Veuillez choisir un horaire futur";
   }

   $bd = bd_connect();
   $sql = "SELECT DateCloture FROM Evenement WHERE ID = $event";

   $res = mysqli_query($bd, $sql);

   if (mysqli_num_rows($res) != 1) {
     mysqli_free_result($res);
     mysqli_close($bd);
     $err['BD'] = "Un problème est survenu";
   } else {

     $t = mysqli_fetch_assoc($res);
     $dateCloture = $t['DateCloture'];

     $dateObj = date_create($dateCloture);

     $dateCloture_day = date_format($dateObj, "d");
     $dateCloture_month = date_format($dateObj, "m");
     $dateCloture_year = date_format($dateObj, "Y");
     $dateCloture_heure = date_format($dateObj, "h");
     $dateCloture_minute = date_format($dateObj, 'i');


     $resD = compare_deux_dates($dateCloture_day, $dateCloture_month, $dateCloture_year, $date_j, $date_m, $date_a);
     if ($resD != 0){
       $err['Date'] = "La date choisie doit être après la date de clotûre de l'évènement";
     }


     if (count($err)==0){

       $sql = "INSERT INTO Date (Jour, Mois, Annee, Heure, Minute) VALUES ($date_j, $date_m, $date_a, $heure_h, $heure_m)";

       mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

       $idDateDB = mysqli_insert_id($bd);

       $sql = "INSERT INTO DateEvenement (IDEvent, IDDate) VALUES ($event, $idDateDB)";

       mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

       redirige("voir_event.php?event=" . $event);
     }
     return $err;
    }
  }

?>
