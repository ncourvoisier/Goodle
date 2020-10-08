<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

html_debut('Goodle | Reponse invitation', '../CSS/style.css');

goodle_header();


//verifie si l'utilisateur est connecté
	
 if (!isset($_SESSION['ID'])){ 
	
    echo '<p>Vous n\'êtes pas connecté<p>',
    '<a id="lien_connect" href="./login.php" title="Se Connecter">Veuillez vous connecter</a>';

 }else{
	
	$bd = bd_connect();
	 

	if(isset($_POST["btnValiderRep"])){
			
			$IDevent = $_POST["IDevent"];
			$idInvite = insert_db_into_invite($bd, $IDevent, $_SESSION["ID"]);		
			$listeDateEvent = unserialize($_POST["listeDateEvent"]);
			$length = $_POST["length"];
			l_ajout_reponses($bd, $length, $idInvite,$listeDateEvent, $update);			
			mysqli_close($bd);
			redirige("reponse_ok.php");
	}	
	
	
	$errors=l_controle_piratage_ai();
	if (count($errors) != 0) {
		foreach ($errors as $code => $e) {
		echo '<p class="erreur">' . $e . '</p>';
		}
		
		
	} else {
		
			
			$IDevent = $_GET["IDEvent"];
			
			$sql = "SELECT * FROM Invite Where IDEvent=".$IDevent." AND IDPersonne=".$_SESSION["ID"].";";

			$res = mysqli_query($bd, $sql);
			$t = mysqli_fetch_assoc($res);

			// verifie s 'il y deja eu une première à l'invitation.
			if (mysqli_num_rows($res) > 0) {
			  mysqli_free_result($res);
			  mysqli_close($bd);
			  echo '<p>Vous avez déjà fait une première réponse à cette invitation.</p>',
			  '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
			  return;
			}

			$sql2='SELECT Date.*, Evenement.*, DateEvenement.id FROM DateEvenement, Evenement, Date WHERE DateEvenement.IDEvent = Evenement.ID AND Date.ID = DateEvenement.IDDate AND Evenement.ID =  ' . $IDevent . ';';
			$res2 = mysqli_query($bd, $sql2);
			
			$length=mysqli_num_rows($res2);
			
			$listeDateEvent=array();
			$t = mysqli_fetch_assoc($res2);
			
			//verification que si la date du jour a dépassé la date de cloture.
			$dateCloture=date_format(new DateTime($t['DateCloture']),'Y-m-d');
			$timestamp1=strtotime($dateCloture);
			$timestamp2= strtotime(date('Y-n-j'));
			if( $timestamp1 < $timestamp2) {
				echo '<p class="erreur">Vous avez depassé la date de cloture. (date de cloture :' . $dateCloture . ').</p>';
				mysqli_close($bd);
				echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';
				  return;
			}else{
		
				 echo '<form method="POST" action="repondre_invite.php">',
				'<h2>',$t['Nom'],' - ',$t['Lieu'],'</h2><table>';
				$i=1;
				$listeR="";
				do{
					$ListeR='reponse'.$i;
					array_push($listeDateEvent,$t['ID']);
					$minu=$t['Minute']<=9?'0'.$t['Minute']:$t['Minute'];
					$date = 'Le '.$t['Jour'] . ' ' . get_mois($t['Mois']) . ' ' . $t['Annee'];
					$heure = 'à ' . $t['Heure'] . 'h' . $minu ;
					echo '<tr><td>',$date,' ',$heure,'</td><td>',form_input(Z_RADIO, $ListeR,'Oui'),'Oui</td><td>',form_input(Z_RADIO, $ListeR,'Non'),'Non</td><td>',form_input(Z_RADIO, $ListeR, 'Peutetre', 0, 1),'Peut-être</td></tr>';
					$i++;
				}while($t = mysqli_fetch_assoc($res2));
					
					echo '<tr><td colspan="4" style="padding-top: 10px;" class="centered">', form_input(Z_SUBMIT,'btnValiderRep','Valider'), '</td></tr>';
					echo '<tr><td colspan="4" style="padding-top: 10px;" class="centered">', form_input(Z_HIDDEN,'length', $length), '</td></tr>';   //taille des reponse au date
					echo '<tr><td colspan="4" style="padding-top: 10px;" class="centered">', form_input(Z_HIDDEN,'IDevent', $IDevent), '</td></tr>'; //id de l'événement de l'invitation

					$listeAEnvoyer = serialize($listeDateEvent);
					echo '<tr><td colspan="4" style="padding-top: 10px;" class="centered">', form_input(Z_HIDDEN,'listeDateEvent', $listeAEnvoyer), '</td></tr></table>'; // le tableau de dateevenement
				
			}
			
	}
}
	
html_fin();

ob_end_flush();

function l_controle_piratage_ai() {
	$err = array();
	if (!isset($_GET['IDEvent'])) {
		$err['no_event'] = "L'evenement doit être renseigné.";
	} else if (!preg_match('/^[0-9]*$/', $_GET['IDEvent'])) {
		$err['event_format'] = "L'identifiant de l'evenement doit être un nombre.";
	}
	return $err;

}

function l_ajout_reponses($bd, $length,$idInvite, $listeDateEvent){
	
		
	for($i = 0;$i<$length;$i++){
		$reponse='reponse'.($i+1);
		$reponses=$_POST[$reponse];
		insert_db_into_reponse($bd, $reponses, $idInvite, $listeDateEvent[$i]);	
	}
}

function insert_db_into_reponse($bd, $reponses, $idInvite,  $idDateEvent){
	
   $sql = "INSERT INTO Reponse (IDDateEvent, IDInvite, response) VALUES ($idDateEvent,$idInvite, '$reponses')";

	mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
		
}

function insert_db_into_invite($bd, $iDEvent, $idPersonne){
	
   $sql = "INSERT INTO Invite (IDEvent, IDPersonne) VALUES ($iDEvent,$idPersonne)";

	mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	return mysqli_insert_id($bd);
		
}


	
	
