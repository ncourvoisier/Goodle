<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

function l_contenu_me($err ){

	$bd = bd_connect();

	$errors=l_controle_piratage_ai();
	if (count($errors) != 0) {
		foreach ($errors as $code => $e) {
		echo '<p class="erreur">' . $e . '</p>';
		}

	} else {

	  $idEvent=$_GET['event'];
		$too_late = false;

	  $sql='SELECT * FROM Evenement, DateEvenement, Date WHERE Evenement.ID = DateEvenement.IDEvent AND DateEvenement.IDDate = Date.ID AND Evenement.ID = '.$idEvent.' ;';



	  $res = mysqli_query($bd, $sql);

	  $date_j = array();
	  $date_m = array();
	  $date_a = array();
	  $heure_h = array();
	  $heure_m = array();
	  $listIDDate = array();

	  $length = mysqli_num_rows($res);
	  $t = mysqli_fetch_assoc($res);

		$dateCloture=date_format(new DateTime($t['DateCloture']),'Y-m-d');
		$timestamp1=strtotime($dateCloture);
		$timestamp2= strtotime(date('Y-n-j')); // pour désactiver els boutons radio dans le cas d'un dépassement de la date cloture
		if( $timestamp1 < $timestamp2) {
			$too_late = true;
		}

	  $nameEvent = isset($_POST['NameEvent']) ? $_POST['NameEvent'] : $t['Nom'];
	  $lieuEvent = isset($_POST['LieuEvent']) ? $_POST['LieuEvent'] : $t['Lieu'];

	  $dateCloture_j = isset($_POST['DateCloture_j'])?$_POST['DateCloture_j']:date_format(new DateTime($t['DateCloture']),'d');
	  $dateCloture_m = isset($_POST['DateCloture_m'])?$_POST['DateCloture_m']:date_format(new DateTime($t['DateCloture']),'m');
	  $dateCloture_a = isset($_POST['DateCloture_a'])?$_POST['DateCloture_a']:date_format(new DateTime($t['DateCloture']),'Y');
	  $heureCloture_h = isset($_POST['DateCloture_hr'])?$_POST['DateCloture_hr']:date_format(new DateTime($t['DateCloture']),'H');
	  $heureCloture_m = isset($_POST['DateCloture_min'])?$_POST['DateCloture_min']:date_format(new DateTime($t['DateCloture']),'i');

	  for($i = 0; $i<$length;$i++){

		  array_push($date_j, $t['Jour']);
		  array_push($date_m,  $t['Mois']);
		  array_push($date_a,  $t['Annee']);
		  array_push($heure_h,  $t['Heure']);
		  array_push($heure_m,  $t['Minute']);
		  array_push($listIDDate,  $t['IDDate']);
		  $num=$i+1;

		  $date_j[$i] = isset($_POST['DateEvent'.$num .'_j'])?$_POST['DateEvent'.$num .'_j']:$t['Jour'];
		  $data_j[$i] = isset($_POST['DateEvent'.$num .'_m'])?$_POST['DateEvent'.$num .'_m']: $t['Mois'];
		  $date_a[$i] = isset($_POST['DateEvent'.$num .'_a'])?$_POST['DateEvent'.$num .'_a']:$t['Annee'];
		  $heure_h[$i] = isset($_POST['DateEvent'.$num .'_hr'])?$_POST['DateEvent'.$num .'_hr']:$t['Heure'];
		  $heure_m[$i] = isset($_POST['DateEvent'.$num .'_min'])?$_POST['DateEvent'.$num .'_min']:$t['Minute'];

		  $t=mysqli_fetch_assoc($res);

	  }
	  $listIDDate=serialize($listIDDate);
	  echo '<h1>Modification de l\'évènement '. $nameEvent.'</h1>';

	  if (count($err) > 0) {
		echo '<p class="erreur">Erreurs : ';
		foreach ($err as $v) {
		  echo '<br> - ', $v;
		}
		echo '</p>';
	  }

	  if (! isset($_SESSION['ID'])){ //Si non connecté, on demande à l'utilisateur de se connecter

		echo '<p>Vous n\'êtes pas connecté<p>',
		'<a id="lien_connect" href="./login.php" title="Se Connecter">Connexion</a>';

	} else if ($too_late) {
		echo '<p class="alert alert-danger" id="error_message_too_late">Vous avez depassé la date de cloture. (date de cloture :' . $dateCloture . ').</p>';
	} else {

		echo '<div id="ajout_evenement">';
		echo '<form method="POST" action="modif_evenement.php?event='.$idEvent.'">',
		'<table>',
		form_ligne('Nom de l\'évènement * :', form_input(Z_TEXT, 'NameEvent', $nameEvent, 60)),
		form_ligne('Lieu de l\'évènement * : ', form_input(Z_TEXT, 'LieuEvent', $lieuEvent, 60));
		for ($i=0;$i<$length;$i++){
				$num=$i+1;
				echo form_ligne('Date '.$num.'  :', form_date_and_hour('DateEvent'.$num, $date_j[$i], $date_m[$i], $date_a[$i], $heure_h[$i], $heure_m[$i]));
		}
		echo form_ligne('Entrez la date de clôture * : ', form_date_and_hour('DateCloture', $dateCloture_j, $dateCloture_m, $dateCloture_a, -1, -1)),
		'<tr><td colspan="2" style="padding-top: 10px;" class="centered">', form_input(Z_SUBMIT,'btnValiderEvent','Valider'), '</td></tr>',
		form_input(Z_HIDDEN,'lengthDate',$length),
		form_input(Z_HIDDEN,'iddate',$listIDDate),
		'</table>',
		'</form>';

		echo '</div>';
	  }
	}
}

function l_verify_event(){
  $err = array();

  $date_j = array();
  $date_m = array();
  $date_a = array();
  $heure_h = array();
  $heure_m = array();
  $listIDDate = unserialize($_POST['iddate']);
  //print_r($listIDDate);


  $length=$_POST['lengthDate'];

  for($i=0;$i<$length;$i++){
	 $num=$i+1;
	array_push($date_j, $_POST['DateEvent'.$num .'_j']);
	array_push($date_m,  $_POST['DateEvent'.$num .'_m']);
	array_push($date_a,  $_POST['DateEvent'.$num .'_a']);
	array_push($heure_h,  $_POST['DateEvent'.$num .'_hr']);
	array_push($heure_m,  $_POST['DateEvent'.$num .'_min']);
  }

  $dateCloture_j = isset($_POST['DateCloture_j'])?$_POST['DateCloture_j']:1;
  $dateCloture_m = isset($_POST['DateCloture_m'])?$_POST['DateCloture_m']:1;
  $dateCloture_a = isset($_POST['DateCloture_a'])?$_POST['DateCloture_a']:2020;
  $heureCloture_h = isset($_POST['DateCloture_hr'])?$_POST['DateCloture_hr']:0;
  $heureCloture_m = isset($_POST['DateCloture_min'])?$_POST['DateCloture_min']:0;

  /*
   * VERIFICATION QUE TOUS LES CHAMPS SOIENT BIEN REMPLIS
   *
   */
  if (!empty($_POST['NameEvent'])  && isset($_POST['NameEvent']) ){
    $nameEvent = $_POST['NameEvent'];
  } else {
    $err['NameEvent'] = "[Nom de l'évènement] Ce champ est obligatoire, il doit être rempli.";
  }
  if (!empty($_POST['LieuEvent']) && isset($_POST['LieuEvent'])){
    $lieuEvent = $_POST['LieuEvent'];
  } else {
    $err['LieuEvent'] = "[Lieu de l'évènement] Ce champ est obligatoire, il doit être rempli.";
  }

  /*
   * VERIFICATION QUE LES CHAMPS TEXTES NE CONTIENNENT PAS DE HTML
   *
   */
   $noTags = strip_tags($nameEvent);
   if ($noTags != $nameEvent){
     $err['NameEvent'] = "[Nom de l'évènement] Ce champ ne peut pas contenir de HTML !";
   }

   $noTags = strip_tags($lieuEvent);
   if ($noTags != $lieuEvent){
     $err['LieuEvent'] = "[Lieu de l'évènement] Ce champ ne peut pas contenir de HTML !";
   }

   /*
    * VERIFICATION DES DATES
    *
    */

	$res = compare_date($dateCloture_j, $dateCloture_m, $dateCloture_a, 0, 0);
    if ($res == 1){
      $err['DateCloture'] = "[Date clôture] Veuillez choisir une date future";
    }

	for ($i=0;$i<$length;$i++){
		 $num=$i+1;
		$res = compare_date($date_j[$i], $date_m[$i], $date_a[$i], $heure_h[$i], $heure_m[$i]);
		if ($res == 1){
		  $err['DateEvent'] = "[Date ".$num."] Veuillez choisir une date future";
		} else if ($res == 2){
		  $err['DateEvent'] = "[Heure Evènement ".$num."] Veuillez choisir un horaire futur";
		}
		$res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date_j[$i], $date_m[$i], $date_a[$i]);
		if ($res == 1){
		  $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
		} else if ($res == 2){
		  $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
		}
	}

    if (count($err) == 0){

      $bd = bd_connect();
	  $idEvent = $_GET['event'];
      $nom = bd_protect($bd, $nameEvent);
      $lieu = bd_protect($bd, $lieuEvent);
      $referent = $_SESSION['ID'];
      $aaaammjj = $dateCloture_a*10000  + $dateCloture_m*100 + $dateCloture_j;

      $sql = "UPDATE Evenement SET Nom =  '$nom', Lieu= '$lieu', DateCloture = $aaaammjj WHERE ID = $idEvent ;";
	  //print_r($sql);

      mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

		for($i=0;$i<$length;$i++){
			//print($listIDDate[$i]);
			update_db_date($bd, $date_j[$i], $date_m[$i], $date_a[$i], $heure_h[$i], $heure_m[$i],$listIDDate[$i]);

		}

			mysqli_close($bd);

			$_POST = array();

		redirige("evenement_ok.php");

    }

    return $err;
}

function update_db_date($bd, $jour, $mois, $annee, $heure, $minute, $id){

  $sql = "UPDATE Date SET  Jour=$jour, Mois=$mois, Annee=$annee, Heure=$heure, Minute=$minute WHERE ID = $id ";

  mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

}
function l_controle_piratage_ai() {
	$err = array();
	if (!isset($_GET['event'])) {
		$err['no_event'] = "L'evenement doit être renseigné.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;

}
//MAIN

$err = isset($_POST['btnValiderEvent']) ? l_verify_event() : array();

html_debut('Goodle | Evènement', '../CSS/style.css');

goodle_header();
if (isset($_SESSION['ID'])) {
	echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
	$pagePrec = /*$_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] :*/ '../../index/php';
	echo '<a href='.$pagePrec.'>Retour</a>';
}

l_contenu_me($err);

html_fin();

ob_end_flush();


?>
