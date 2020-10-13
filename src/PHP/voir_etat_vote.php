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

	$idRef = $_SESSION["ID"];
	$sql='SELECT * FROM Evenement WHERE Referent='.$idRef.' ;';

	$res = mysqli_query($bd, $sql);

	if (mysqli_num_rows($res) < 1) {
		mysqli_free_result($res);
		mysqli_close($bd);
		echo '<p>Vous n\'avez aucun événement référent </p>',
			 '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
		return;
	}

	echo '<h1> Etat des Votes </h1><ul>';

	while($t = mysqli_fetch_assoc($res)){

		$sql2='SELECT * , COUNT(Response) as cpt FROM DateEvenement, Date, Reponse
			   WHERE Date.ID = DateEvenement.IDDate
			   AND DateEvenement.ID = Reponse.IDDateEvent
			   AND DateEvenement.IDevent = '.$t['ID'].'
			   GROUP BY IDDateEvent, response ;';

		//print_r($sql2);
		echo '<h2>',$t['Nom'],' - ',$t['Lieu'],'</h2><ul>';
		$res2 = mysqli_query($bd, $sql2);
		$t2= mysqli_fetch_assoc($res2);
		$oldt2=$t2;
		$VPeutetre=0;
		$VOui=0;
		$VNon=0;
		do{

			if($t2["IDDate"] != $oldt2["IDDate"]){

				l_affiche_vote($oldt2['Heure'], $oldt2['Minute'], $oldt2['Jour'], $oldt2['Mois'], $oldt2['Annee'],$VPeutetre,$VOui,$VNon);

				$VPeutetre=0;
				$VOui=0;
				$VNon=0;

			}

			if(strcmp($t2["Response"],'Peutetre')==0)
				$VPeutetre=	$t2["cpt"];
			if(strcmp($t2["Response"],'Oui')==0)
				$VOui=	$t2["cpt"];
			if(strcmp($t2["Response"],'Non')==0)
				$VNon=	$t2["cpt"];

			$oldt2=$t2;
		}while($t2= mysqli_fetch_assoc($res2));

		l_affiche_vote($oldt2['Heure'], $oldt2['Minute'], $oldt2['Jour'], $oldt2['Mois'], $oldt2['Annee'],$VPeutetre,$VOui,$VNon);

		echo '</ul>';
	}
	echo '</ul>';

}


html_fin();

ob_end_flush();


function l_affiche_vote( $heur, $min, $jour, $mois, $annee,  $voteP, $voteO, $voteN){

	$minu=$min<=9?'0'.$min:$min;
	$date = 'Le '.$jour . ' ' . get_mois($mois) . ' ' . $annee;
	$heure = 'à ' . ecrireHeure($heur,$minu) ;
	echo '<h3>',$date,' ',$heure,'</h3>',
	 '<ul>';
	echo '<li>Vote : Peut être (',$voteP,')</li>';
	echo '<li>Vote : Oui (',$voteO,')</li>';
	echo '<li>Vote : Non (',$voteN,')</li></ul>';
}
