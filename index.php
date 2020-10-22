<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();    // Lancement de la session

require_once './src/PHP/bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

html_debut('Goodle', './src/CSS/styles.css');

goodle_header('.');

l_contenu();

html_fin();

ob_end_flush();


function l_contenu() {
	if (isset($_SESSION['ID'])){
			echo
			'<h1>Bienvenue sur Goodle !</h1>',
			'<p><a href="./src/PHP/ajout_evenement.php">Ajouter un évènement</a></p>',
			'<p><a href="./src/PHP/invitation.php">Voir les invitations</a><p>';
			if (isset($_SESSION['admin']) && $_SESSION['admin'] == 1 ){
				echo '<p>Voir les événements : <a href="./src/PHP/evenement.php">event</a>. </p>';
			}

			// recherche des notifications : jusqu'a 10 après la date de cloture on affiche la date choisi si elle est défini pour chaque événement où on est invité
			$bd = bd_connect();
			$sql = 'SELECT IDEvent FROM Invite WHERE IDPersonne = '.$_SESSION['ID'].';';
			$res = mysqli_query($bd, $sql);

			if(mysqli_num_rows($res) >0)
			{
				//echo '<h2> Vos notifications : '.mysqli_num_rows($res).' </h2>';
			}
			while($t = mysqli_fetch_assoc($res))
			{
				$sql2 = 'SELECT * FROM Evenement WHERE ID = '.$t['IDEvent'].';';
				$res2 = mysqli_query($bd, $sql2);
				$t2 = mysqli_fetch_assoc($res2);

				if($t2['DateChoisie'] != null)
				{
					echo '<h2> Vos notifications : '.mysqli_num_rows($res).' </h2>';
					//echo' la date choisie : '.$t2['DateChoisie'];
					/*$dateCloture=new DateTime($t2['DateCloture']);
					$today = new DateTime(); // voir pour la timeZone mais comme c'est arbitraire les 10 jours c'est pas urgent
					$diff = $today->diff($dateChoisie)->format("%a");*/
					$dateCloture=date_format(new DateTime($t2['DateCloture']),'Y-m-d');
					$timestamp1=strtotime($dateCloture);
					$timestamp2 = strtotime(date('Y-n-j'));
					$dif = ceil(abs($fin - $debut) / 86400);
					if($dif >=0 && $dif <10)
					{
						echo 'L\'événement '.$t2['Nom'].' à '.$t2['Lieu'].' à pour date choisi '.$t2['DateChoisie'];
					}
					
				}
			}
			

		} else {

	echo
		'<h1>Bienvenue sur Goodle !</h1>',
		'<p>Connectez-vous ou inscrivez-vous et gérez pleinement les dates de vos événements ! </p>',
		'<p>Pas encore connecté ? C\'est par <a href="./src/PHP/login.php" id="connectionLink">ici</a>. </p>',
		'<p>Nouveau venu sur Goodle ? Consultez notre <a href="./src/PHP/inscription.php">page d\'inscription</a> !',
		'<p><a href="./src/PHP/ajout_evenement.php">Ajouter un évènement</a></p>';

}
}

?>
