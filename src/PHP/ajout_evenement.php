<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

function l_contenu($err){

  $nameEvent = isset($_POST['NameEvent']) ? $_POST['NameEvent'] : '';
  $lieuEvent = isset($_POST['LieuEvent']) ? $_POST['LieuEvent'] : '';
  $date1_j = isset($_POST['DateEvent_j'])?$_POST['DateEvent_j']:1;
  $date1_m = isset($_POST['DateEvent_m'])?$_POST['DateEvent_m']:1;
  $date1_a = isset($_POST['DateEvent_a'])?$_POST['DateEvent_a']:2020;
  $heure1_h = isset($_POST['DateEvent_hr'])?$_POST['DateEvent_hr']:0;
  $heure1_m = isset($_POST['DateEvent_min'])?$_POST['DateEvent_min']:0;

  $dateCloture_j = isset($_POST['DateCloture_j'])?$_POST['DateCloture_j']:1;
  $dateCloture_m = isset($_POST['DateCloture_m'])?$_POST['DateCloture_m']:1;
  $dateCloture_a = isset($_POST['DateCloture_a'])?$_POST['DateCloture_a']:2020;
  $heureCloture_h = isset($_POST['DateCloture_hr'])?$_POST['DateCloture_hr']:0;
  $heureCloture_m = isset($_POST['DateCloture_min'])?$_POST['DateCloture_min']:0;

  echo '<h1>Ajout d\'un évènement</h1>';

  if (count($err) > 0) {
    echo '<p class="erreur">Erreurs : ';
    foreach ($err as $v) {
      echo '<br> - ', $v;
    }
    echo '</p>';
  }

  if (! isset($_SESSION['ID'])){ //Si non connecté, on demande à l'utilisateur de se connecter

    echo '<p>Vous n\'êtes pas connecté<p>',
    '<a id="lien_connect" href="./login.php" title="Se Connecter">Connection</a>';

  } else {

    echo '<div id="ajout_evenement">';

    echo '<form method="POST" action="ajout_evenement.php">',
    '<table>',
    form_ligne('Nom de l\'évènement * :', form_input(Z_TEXT, 'NameEvent', $nameEvent, 60)),
    form_ligne('Lieu de l\'évènement * : ', form_input(Z_TEXT, 'LieuEvent', $lieuEvent, 60)),
    form_ligne('Entrez une première date pour l\'évènement * :', form_date_and_hour('DateEvent', $date1_j, $date1_m, $date1_a, $heure1_h, $heure1_m)),
    form_ligne('Entrez la date de clôture * : ', form_date_and_hour('DateCloture', $dateCloture_j, $dateCloture_m, $dateCloture_a, -1, -1)),
    '<tr><td colspan="2" style="padding-top: 10px;" class="centered">', form_input(Z_SUBMIT,'btnValiderEvent','Valider'), '</td></tr>',
    '</table>',
    '</form>';

    echo '</div>';
  }
}

function l_verify_event(){
  $err = array();

  $nameEvent = '';
  $lieuEvent = '';
  $date1_j = isset($_POST['DateEvent_j'])?$_POST['DateEvent_j']:1;
  $date1_m = isset($_POST['DateEvent_m'])?$_POST['DateEvent_m']:1;
  $date1_a = isset($_POST['DateEvent_a'])?$_POST['DateEvent_a']:2020;
  $heure1_h = isset($_POST['DateEvent_hr'])?$_POST['DateEvent_hr']:0;
  $heure1_m = isset($_POST['DateEvent_min'])?$_POST['DateEvent_min']:0;

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

    $res = compare_date($date1_j, $date1_m, $date1_a, $heure1_h, $heure1_m);
    if ($res == 1){
      $err['DateEvent'] = "[Date Evènement] Veuillez choisir une date future";
    } else if ($res == 2){
      $err['DateEvent'] = "[Heure Evènement] Veuillez choisir un horaire futur";
    }

    $res = compare_date($dateCloture_j, $dateCloture_m, $dateCloture_a, 0, 0);
    if ($res == 1){
      $err['DateCloture'] = "[Date clôture] Veuillez choisir une date future";
    }

    $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date1_j, $date1_m, $date1_a);
    if ($res == 1){
      $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à la première date d'un évènement proposé";
    } else if ($res == 2){
      $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée pour l'évènement";
    }

    if (count($err) == 0){

      $bd = bd_connect();

      $nom = bd_protect($bd, $nameEvent);
      $lieu = bd_protect($bd, $lieuEvent);
      $referent = $_SESSION['ID'];
      $aaaammjj = $dateCloture_a*10000  + $dateCloture_m*100 + $dateCloture_j;

      $sql = "INSERT INTO EVENEMENT (Nom, Lieu, Referent, DateCloture) VALUES ('$nom', '$lieu', $referent, $aaaammjj)";

      mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

    	$id_event = mysqli_insert_id($bd);

      $sql2 = "INSERT INTO DATE (Jour, Mois, Annee, Heure, Minute) VALUES ($date1_j, $date1_m, $date1_a, $heure1_h, $heure1_m)";

      mysqli_query($bd, $sql2) or bd_erreur($bd, $sql2);

    	$id_date = mysqli_insert_id($bd);

      $sql3 = "INSERT INTO DATEEVENEMENT (IDEvent, IDDate) VALUES ($id_event, $id_date)";

      mysqli_query($bd, $sql3) or bd_erreur($bd, $sql3);

    	mysqli_close($bd);

      $_POST = array();

      redirige("evenement_ok.php"); 

    }

    return $err;
}

//MAIN

$err = isset($_POST['btnValiderEvent']) ? l_verify_event() : array();

html_debut('Goodle | Evènement', '../CSS/style.css');

goodle_header();

l_contenu($err);

html_fin();

ob_end_flush();


?>
