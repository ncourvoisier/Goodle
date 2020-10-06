<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

function l_contenu_ae($err){

  $nameEvent = isset($_POST['NameEvent']) ? $_POST['NameEvent'] : '';
  $lieuEvent = isset($_POST['LieuEvent']) ? $_POST['LieuEvent'] : '';
  $date1_j = isset($_POST['DateEvent1_j'])?$_POST['DateEvent1_j']:1;
  $date1_m = isset($_POST['DateEvent1_m'])?$_POST['DateEvent1_m']:1;
  $date1_a = isset($_POST['DateEvent1_a'])?$_POST['DateEvent1_a']:2020;
  $heure1_h = isset($_POST['DateEvent1_hr'])?$_POST['DateEvent1_hr']:0;
  $heure1_m = isset($_POST['DateEvent1_min'])?$_POST['DateEvent1_min']:0;

  $date2_j = isset($_POST['DateEvent2_j'])?$_POST['DateEvent2_j']:1;
  $date2_m = isset($_POST['DateEvent2_m'])?$_POST['DateEvent2_m']:1;
  $date2_a = isset($_POST['DateEvent2_a'])?$_POST['DateEvent2_a']:2020;
  $heure2_h = isset($_POST['DateEvent2_hr'])?$_POST['DateEvent2_hr']:0;
  $heure2_m = isset($_POST['DateEvent2_min'])?$_POST['DateEvent2_min']:0;

  $date3_j = isset($_POST['DateEvent3_j'])?$_POST['DateEvent3_j']:1;
  $date3_m = isset($_POST['DateEvent3_m'])?$_POST['DateEvent3_m']:1;
  $date3_a = isset($_POST['DateEvent3_a'])?$_POST['DateEvent3_a']:2020;
  $heure3_h = isset($_POST['DateEvent3_hr'])?$_POST['DateEvent3_hr']:0;
  $heure3_m = isset($_POST['DateEvent3_min'])?$_POST['DateEvent3_min']:0;

  $date4_j = isset($_POST['DateEvent4_j'])?$_POST['DateEvent4_j']:1;
  $date4_m = isset($_POST['DateEvent4_m'])?$_POST['DateEvent4_m']:1;
  $date4_a = isset($_POST['DateEvent4_a'])?$_POST['DateEvent4_a']:2020;
  $heure4_h = isset($_POST['DateEvent4_hr'])?$_POST['DateEvent4_hr']:0;
  $heure4_m = isset($_POST['DateEvent4_min'])?$_POST['DateEvent4_min']:0;

  $date5_j = isset($_POST['DateEvent5_j'])?$_POST['DateEvent5_j']:1;
  $date5_m = isset($_POST['DateEvent5_m'])?$_POST['DateEvent5_m']:1;
  $date5_a = isset($_POST['DateEvent5_a'])?$_POST['DateEvent5_a']:2020;
  $heure5_h = isset($_POST['DateEvent5_hr'])?$_POST['DateEvent5_hr']:0;
  $heure5_m = isset($_POST['DateEvent5_min'])?$_POST['DateEvent5_min']:0;

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
    '<a id="lien_connect" href="./login.php" title="Se Connecter">Connexion</a>';

  } else {
    echo $_SESSION['ID'];

    echo '<div id="ajout_evenement">';

    echo '<form method="POST" action="ajout_evenement.php">',
    '<table>',
    form_ligne('Nom de l\'évènement * :', form_input(Z_TEXT, 'NameEvent', $nameEvent, 60)),
    form_ligne('Lieu de l\'évènement * : ', form_input(Z_TEXT, 'LieuEvent', $lieuEvent, 60)),
    form_ligne('Date 1 * :', form_date_and_hour('DateEvent1', $date1_j, $date1_m, $date1_a, $heure1_h, $heure1_m)),
    form_ligne('Date 2 :', form_date_and_hour('DateEvent2', $date2_j, $date2_m, $date2_a, $heure2_h, $heure2_m)),
    form_ligne('Date 3 :', form_date_and_hour('DateEvent3', $date3_j, $date3_m, $date3_a, $heure3_h, $heure3_m)),
    form_ligne('Date 4 :', form_date_and_hour('DateEvent4', $date4_j, $date4_m, $date4_a, $heure4_h, $heure4_m)),
    form_ligne('Date 5 :', form_date_and_hour('DateEvent5', $date5_j, $date5_m, $date5_a, $heure5_h, $heure5_m)),
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
  $date1_j = isset($_POST['DateEvent1_j'])?$_POST['DateEvent1_j']:1;
  $date1_m = isset($_POST['DateEvent1_m'])?$_POST['DateEvent1_m']:1;
  $date1_a = isset($_POST['DateEvent1_a'])?$_POST['DateEvent1_a']:2020;
  $heure1_h = isset($_POST['DateEvent1_hr'])?$_POST['DateEvent1_hr']:0;
  $heure1_m = isset($_POST['DateEvent1_min'])?$_POST['DateEvent1_min']:0;

  $date2_j = isset($_POST['DateEvent2_j'])?$_POST['DateEvent2_j']:1;
  $date2_m = isset($_POST['DateEvent2_m'])?$_POST['DateEvent2_m']:1;
  $date2_a = isset($_POST['DateEvent2_a'])?$_POST['DateEvent2_a']:2020;
  $heure2_h = isset($_POST['DateEvent2_hr'])?$_POST['DateEvent2_hr']:0;
  $heure2_m = isset($_POST['DateEvent2_min'])?$_POST['DateEvent2_min']:0;

  $date3_j = isset($_POST['DateEvent3_j'])?$_POST['DateEvent3_j']:1;
  $date3_m = isset($_POST['DateEvent3_m'])?$_POST['DateEvent3_m']:1;
  $date3_a = isset($_POST['DateEvent3_a'])?$_POST['DateEvent3_a']:2020;
  $heure3_h = isset($_POST['DateEvent3_hr'])?$_POST['DateEvent3_hr']:0;
  $heure3_m = isset($_POST['DateEvent3_min'])?$_POST['DateEvent3_min']:0;

  $date4_j = isset($_POST['DateEvent4_j'])?$_POST['DateEvent4_j']:1;
  $date4_m = isset($_POST['DateEvent4_m'])?$_POST['DateEvent4_m']:1;
  $date4_a = isset($_POST['DateEvent4_a'])?$_POST['DateEvent4_a']:2020;
  $heure4_h = isset($_POST['DateEvent4_hr'])?$_POST['DateEvent4_hr']:0;
  $heure4_m = isset($_POST['DateEvent4_min'])?$_POST['DateEvent4_min']:0;

  $date5_j = isset($_POST['DateEvent5_j'])?$_POST['DateEvent5_j']:1;
  $date5_m = isset($_POST['DateEvent5_m'])?$_POST['DateEvent5_m']:1;
  $date5_a = isset($_POST['DateEvent5_a'])?$_POST['DateEvent5_a']:2020;
  $heure5_h = isset($_POST['DateEvent5_hr'])?$_POST['DateEvent5_hr']:0;
  $heure5_m = isset($_POST['DateEvent5_min'])?$_POST['DateEvent5_min']:0;

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
      $err['DateEvent'] = "[Date 1] Veuillez choisir une date future";
    } else if ($res == 2){
      $err['DateEvent'] = "[Heure Evènement 1] Veuillez choisir un horaire futur";
    }

    $res = compare_date($date2_j, $date2_m, $date2_a, $heure2_h, $heure2_m);
    $date2prise = false;
    if ($res == 0){
      $date2prise = true;
    }

    $res = compare_date($date3_j, $date3_m, $date3_a, $heure3_h, $heure3_m);
    $date3prise = false;
    if ($res == 0){
      $date3prise = true;
    }

    $res = compare_date($date4_j, $date4_m, $date4_a, $heure4_h, $heure4_m);
    $date4prise = false;
    if ($res == 0){
      $date4prise = true;
    }

    $res = compare_date($date5_j, $date5_m, $date5_a, $heure5_h, $heure5_m);
    $date5prise = false;
    if ($res == 0){
      $date5prise = true;
    }

    $res = compare_date($dateCloture_j, $dateCloture_m, $dateCloture_a, 0, 0);
    if ($res == 1){
      $err['DateCloture'] = "[Date clôture] Veuillez choisir une date future";
    }

    $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date1_j, $date1_m, $date1_a);
    if ($res == 1){
      $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
    } else if ($res == 2){
      $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
    }

    if ($date2prise){
      $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date2_j, $date2_m, $date2_a);
      if ($res == 1){
        $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
      } else if ($res == 2){
        $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
      }
    }

    if ($date3prise){
      $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date3_j, $date3_m, $date3_a);
      if ($res == 1){
        $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
      } else if ($res == 2){
        $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
      }
    }

    if ($date4prise){
      $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date4_j, $date4_m, $date4_a);
      if ($res == 1){
        $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
      } else if ($res == 2){
        $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
      }
    }

    if ($date5prise){
      $res = compare_deux_dates($dateCloture_j, $dateCloture_m, $dateCloture_a, $date5_j, $date5_m, $date5_a);
      if ($res == 1){
        $err['DateCloture'] = "[Date clôture] Cette date doit être antérieure à toutes les dates proposées";
      } else if ($res == 2){
        $err['DateCloture'] = "[Date clôture] La date de clôture ne peut pas être la même qu'une date proposée";
      }
    }

    if (count($err) == 0){

      $bd = bd_connect();

      $nom = bd_protect($bd, $nameEvent);
      $lieu = bd_protect($bd, $lieuEvent);
      $referent = $_SESSION['ID'];
      $aaaammjj = $dateCloture_a*10000  + $dateCloture_m*100 + $dateCloture_j;

      $sql = "INSERT INTO Evenement (Nom, Lieu, Referent, DateCloture) VALUES ('$nom', '$lieu', $referent, '$aaaammjj')";

      mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

    	$id_event = mysqli_insert_id($bd);

    	$id_date = insert_db_into_date($bd, $date1_j, $date1_m, $date1_a, $heure1_h, $heure1_m);

      insert_db_into_dateEvenement($bd, $id_event, $id_date);

      if ($date2prise){
        $id = insert_db_into_date($bd, $date2_j, $date2_m, $date2_a, $heure2_h, $heure2_m);
        insert_db_into_dateEvenement($bd, $id_event, $id);
      }

      if ($date3prise){
        $id = insert_db_into_date($bd, $date3_j, $date3_m, $date3_a, $heure3_h, $heure3_m);
        insert_db_into_dateEvenement($bd, $id_event, $id);
      }

      if ($date4prise){
        $id = insert_db_into_date($bd, $date4_j, $date4_m, $date4_a, $heure4_h, $heure4_m);
        insert_db_into_dateEvenement($bd, $id_event, $id);
      }

      if ($date5prise){
        $id = insert_db_into_date($bd, $date5_j, $date5_m, $date5_a, $heure5_h, $heure5_m);
        insert_db_into_dateEvenement($bd, $id_event, $id);
      }

    	mysqli_close($bd);

      $_POST = array();

      redirige("evenement_ok.php");

    }

    return $err;
}

function insert_db_into_date($bd, $jour, $mois, $annee, $heure, $minute){

  $sql = "INSERT INTO Date (Jour, Mois, Annee, Heure, Minute) VALUES ($jour, $mois, $annee, $heure, $minute)";

  mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

  return mysqli_insert_id($bd);
}

function insert_db_into_dateEvenement($bd, $id_event, $id_date){
  $sql = "INSERT INTO DateEvenement (IDEvent, IDDate) VALUES ($id_event, $id_date)";

  mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
}

//MAIN

$err = isset($_POST['btnValiderEvent']) ? l_verify_event() : array();

html_debut('Goodle | Evènement', '../CSS/style.css');

goodle_header();

l_contenu_ae($err);

html_fin();

ob_end_flush();


?>
