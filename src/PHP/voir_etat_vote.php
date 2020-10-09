<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Etat Votes', '../src/CSS/styles.css');

goodle_header();


//verifie si l'utilisateur est connecté

 if (!isset($_SESSION['ID'])){

    echo '<p>Vous n\'êtes pas connecté<p>',
    '<a id="lien_connect" href="./login.php" title="Se Connecter">Veuillez vous connecter</a>';

 }else{

	$bd = bd_connect();


	if(isset($_POST["btnValiderRep"])){



	}else{

		$idRef = $_SESSION["ID"];
		$sql='SELECT Date.*, Evenement.*, Reponse.Response, COUNT(Response) as cpt, Date.ID as IDDate, Evenement.ID as IDEvent
				   FROM DateEvenement, Evenement, Date, Reponse
				   WHERE DateEvenement.IDEvent = Evenement.ID
				   AND Date.ID = DateEvenement.IDDate
                   AND DateEvenement.ID = Reponse.IDDateEvent
				   AND Evenement.Referent = '.$idRef.'
                   GROUP BY Reponse.IDDateEvent,Response;';

		$res = mysqli_query($bd, $sql);

		if (mysqli_num_rows($res) < 1) {
			mysqli_free_result($res);
			mysqli_close($bd);
			echo '<p>Vous n\'avez aucun evenement référent ou vos evenement `n\'ont pas encore de vote</p>',
				 '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
			return;
		}

		$t = mysqli_fetch_assoc($res);
		echo '<h1> Etat des Votes </h1><ul>';
		$idDate=$t["IDDate"];
		$idEvent=$t["IDEvent"];
		$minu=$t['Minute']<=9?'0'.$t['Minute']:$t['Minute'];
		$date = 'Le '.$t['Jour'] . ' ' . get_mois($t['Mois']) . ' ' . $t['Annee'];
		$heure = 'à ' . $t['Heure'] . 'h' . $minu ;
			echo '<h2>',$t['Nom'],' - ',$t['Lieu'],'</h2><ul>';
			echo '<p><h3>',$date,' ',$heure,'</h3></p>';
		do{

			if($t["IDEvent"] != $idEvent )
				echo '</ul><h2>',$t['Nom'],' - ',$t['Lieu'],'</h2><ul>';

			if(	$t["IDDate"] != $idDate ){
				$minu=$t['Minute']<=9?'0'.$t['Minute']:$t['Minute'];
				$date = 'Le '.$t['Jour'] . ' ' . get_mois($t['Mois']) . ' ' . $t['Annee'];
				$heure = 'à ' . $t['Heure'] . 'h' . $minu ;
				echo '<h3>',$date,' ',$heure,'</h3>';
			}
				echo '<ul>',
					 '<li>Vote : ',$t["Response"],' (',$t["cpt"],')</li>',
					 '</ul>';

			$idDate=$t["IDDate"];
			$idEvent=$t["IDEvent"];
		}while($t = mysqli_fetch_assoc($res));
		echo '</ul>';
	}
}

html_fin();

ob_end_flush();
