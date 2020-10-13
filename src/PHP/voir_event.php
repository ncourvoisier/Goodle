<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)


$errors = l_controle_piratage_ve();
html_debut('Goodle | Voir event', '../src/CSS/styles.css');

goodle_header();
if (isset($_SESSION['ID'])) {
	echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
}

if (isset($_POST['btnSupprimerEventUtilisateur']) && count($errors)==0){
	$err = l_supprimer_event();
	if(count($err)){
		l_contenu_ve($err);
	}
} else {
	l_contenu_ve($errors);
}
html_fin();
ob_end_flush();

function l_contenu_ve($errors){
	$bd = bd_connect();
	if (count($errors) != 0) {
		foreach ($errors as $code => $e) {
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


		echo '<h2>Evenement ' . $event .'</h2>' . '<ul>';

		$sql = 'SELECT * FROM Evenement WHERE ID = ' . $_GET['event'] . ';';
		$res = mysqli_query($bd, $sql);

		$t = mysqli_fetch_assoc($res);
		if (!$t) {
		  echo '<p class="erreur">L\'evenement ' . $_GET['event'] . ' n\'existe pas.';
		} else {
			foreach ($t as $field => $value) {
				if ($field == "Referent") {
					$sql2 = 'SELECT * FROM Personne WHERE ID = ' . $value . ';';
					$res2 = mysqli_query($bd, $sql2);
					$t2 = mysqli_fetch_assoc($res2);
					echo $field . ' : ' . $t2['Prenom'] . ' ' . $t2['Nom'] . '. Username : ' . $t2['Username'] . ', Email : ' . $t2['Email'];
				} else if ($field == "DateCloture"){
					$dateF = explode(' ', $value);
					$partieDate = explode('-',$dateF[0]);
					$partieHeure = explode(':',$dateF[1]);
					echo "Date de clôture : ".$partieDate[2].' '.get_mois($partieDate[1]).' '.$partieDate[0].' à '.ecrireHeure($partieHeure[0], $partieHeure[1]);
				} else {
					echo $field . ' : ' . $value;
				}
				echo '</br>';
			}

			$order = '';
			$dir = 'ASC';

			if (isset($_GET['order']) && isset($_GET['dir']) && $_GET['dir'] == 'desc') {
				$dir = 'DESC';
			}
			if (isset($_GET['order']) && $_GET['order'] == 'date') {
				$order = 'ORDER BY Annee ' . $dir .', Mois ' . $dir . ' , Jour ' . $dir . ', Heure ' . $dir . ', Minute ' . $dir;
			}


			$sql3 = 'SELECT * FROM DateEvenement NATURAL JOIN Date WHERE IDEvent ='.$_GET['event'].' ' . $order . ';';
			$res3 = mysqli_query($bd, $sql3);

			echo '<ul>';

			$createurConnecte = ($idPersonne == $_SESSION['ID']);

			while ($t3 = mysqli_fetch_assoc($res3)) {
				echo '<li>Date : Le ' . $t3['Jour'] . ' ' . get_mois($t3['Mois']) . ' ' . $t3['Annee'] . ' à ' . ecrireHeure($t3['Heure'],$t3['Minute']);
				if ($createurConnecte){
					echo ' <a href="./supprimer_date_event?dateEvent='.$t3['ID'].'&event='.$_GET['event'].'">Supprimer</a>';
				}
				echo '</li>';
			}
			echo '</ul>';
			choose_order("./voir_event.php");

			if ($createurConnecte){
				echo '<p> Ajouter une date à cet évènement : <a href="./ajouter_date_evenement.php?event='.$event.'">Ajouter Date</a><br/>';
				echo 'Voir l\'état des votes de cet événement : <a href="./voir_etat_vote.php?event='.$event.'">Les votes</a></p>';
			}

			if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1) {
				echo '</br><a href="evenement.php?remove_event=' . $_GET['event'] . '"><button>Supprimer</button></a>';
			} else if ($createurConnecte){
					echo '<form method=POST action="voir_event.php?event='.$_GET['event'].'">',
					form_input(Z_SUBMIT,'btnSupprimerEventUtilisateur','Supprimer l\'évènement'),
					'</form>';
			}
		}
	}
}

function l_controle_piratage_ve() {
	$err = array();
	if (!isset($_GET['event'])) {
		$err['no_event'] = "L'evenement doit être renseigné.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;
}

function l_supprimer_event(){

	$errors=array();

	if (!isset($_GET['event'])) {
		$errors['event'] = "L'id de l'évènement' être renseignée.";
	}
	if (!preg_match('/^[0-9]*$/', $_GET['event'])) {
		$errors['event'] = "L'identifiant de l'évènement doit être un nombre.";
	}

	if (count($errors)!=0){
		return $errors;
	}

	$bd=bd_connect();

	$sql = 'SELECT * FROM Evenement WHERE ID = \'' . $_GET['event'] . '\';';
	$res = mysqli_query($bd, $sql);

	if (mysqli_num_rows($res) == 0) {
		$errors['event'] = "Cet évènement n'existe pas";
		return $errors;
	}

	$sql = 'DELETE date, dateevenement FROM date INNER JOIN dateevenement ON date.ID = dateevenement.IDDate WHERE dateevenement.IDEvent = '.$_GET['event'].';';
	$res = mysqli_query($bd, $sql);

	$sql = 'DELETE FROM Evenement WHERE ID = '.$_GET['event'].';';
	$res = mysqli_query($bd, $sql);

	echo '<p> L\'évènement a bien été supprimé</p>';

	echo '<p><a href="../../index.php">Retour accueil</a></p>';

	return $errors;
}

?>
