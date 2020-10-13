<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Invitation', '../src/CSS/styles.css');

goodle_header();


//verifie si l'utilisateur est connecté

 if (!isset($_SESSION['ID'])){

    echo '<p>Vous n\'êtes pas connecté<p>',
    '<a id="lien_connect" href="./login.php" title="Se Connecter">Veuillez vous connecter</a>';

 }else{

	$bd = bd_connect();

	$idRef = $_SESSION["ID"];
	$sql='SELECT * FROM Evenement WHERE Referent ='.$idRef.' ;';
	//print_r($sql);
	$res = mysqli_query($bd, $sql);

	if (mysqli_num_rows($res) < 1) {
		mysqli_free_result($res);
		mysqli_close($bd);
		echo '<p>Vous n\'avez fait aucune invitation dans vos evenement </p>',
			 '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
		return;
	}

	echo '<h1>Invitation reçu</h1><ul>';
	$oldt=null;
	while($t = mysqli_fetch_assoc($res)){
			//print_r($t);
				echo 'Nom : ', $t['Nom'], ' Lieu : ', $t['Lieu'], ' Date de cloture des votes : ', date_format(new DateTime($t['DateCloture']),'Y-m-d'),'<ul>Utilisateurs qui ont repondu :<ul>';	
				 $sql2="SELECT * FROM Personne, Reponse, Invite wHERE Personne.ID = invite.IDPersonne AND reponse.IDInvite = invite.ID AND invite.IDEvent= ".$t['ID']." GROUP BY invite.ID";
				 //print_r($sql2);
				 //print_r($t);
				 $res2= mysqli_query($bd, $sql2);
				 if(mysqli_num_rows($res2) <1){
					 echo 'Personne n\'a repondu a vos invitation';
				 }else{
					
					 while($t2 = mysqli_fetch_assoc($res2)){
					 // print_r($t2);
						echo	'<li>' . $t2['Username'] . '</li>';
					 }
				 }
				 echo '</ul></ul>';
			$oldt=$t;
	}	
}


html_fin();

ob_end_flush();
