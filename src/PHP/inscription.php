<?php
ob_start('ob_gzhandler'); //démarre la bufferisation, compression du tampon si le client supporte gzip
session_start();

require_once 'bibli_generale.php';

error_reporting(E_ALL); // toutes les erreurs sont capturées (utile lors de la phase de développement)

// si $_POST non vide
($_POST) && l_control_piratage();

echo '<p><a href="../../index.php">Retour à la page d\'accueil</a><p>';


// si utilisateur déjà authentifié, on le redirige sur la page appelante, ou à défaut sur l'index
if (isset($_SESSION['ID'])){
    $page = '../../index.php';
    if (isset($_SERVER['HTTP_REFERER'])){
        $page = $_SERVER['HTTP_REFERER'];
        $nom_page = url_get_nom_fichier($page);
        // suppression des éventuelles boucles de redirection
        if (($nom_page == 'login.php') || ($nom_page == 'inscription.php')){
            $page = '../../index.php';
        } // si la page appelante n'appartient pas à notre site
	else if (! in_array($nom_page, get_pages_goodle())){
            $page = '../../index.php';
        }
    }
    redirige($page);
}

$err = isset($_POST['btnSInscrire']) ? l_inscription() : array();

html_debut('Goodle | Inscription', '../styles/bookshop.css');

goodle_header();

l_contenu($err);

//bookshop_pied();
html_fin();

ob_end_flush();


// ----------  Fonctions locales au script ----------- //

/**
 *	Affichage du contenu de la page (formulaire d'inscription)
 *	@param 	array	$err	tableau d'erreurs à afficher
 */
function l_contenu($err) {

	$email = isset($_POST['email']) ? $_POST['email'] : '';
	$nom = isset($_POST['nom']) ? $_POST['nom'] : '';
	$prenom = isset($_POST['prenom']) ? $_POST['prenom'] : '';
	$username = isset($_POST['username']) ? $_POST['username'] : '';
	$naiss_j = isset($_POST['naiss_j']) ? $_POST['naiss_j'] : 1;
	$naiss_m = isset($_POST['naiss_m']) ? $_POST['naiss_m'] : 1;
	$naiss_a = isset($_POST['naiss_a']) ? $_POST['naiss_a'] : 2000;

	echo
		'<H1>Inscription</H1>';
	if (count($err) > 0) {
		echo '<p id="error_inscription" class="erreur">Votre inscription n\'a pas pu être réalisée à cause des erreurs suivantes : ';
		foreach ($err as $v) {
			echo '<br> - ', $v;
		}
		echo '</p>';
	}

    if (isset($_POST['source'])){
        $source = $_POST['source'];
    }
    else if (isset($_SERVER['HTTP_REFERER'])){
        $source = $_SERVER['HTTP_REFERER'];
        $nom_source = url_get_nom_fichier($source);
        // si la page appelante n'appartient pas à notre site
        if (! in_array($nom_source, get_pages_goodle())){
            $source = '../../index.php';
        }
    }
    else{
        $source = '../../index.php';
    }

	echo
		'<form method="post" action="inscription.php">',
			form_input(Z_HIDDEN, 'source', $source),
			'<p>Pour vous inscrire, merci de fournir les informations suivantes. </p>',
			'<table>',
				form_ligne('Votre adresse email :', form_input(Z_TEXT, 'email', $email, 30)),
				form_ligne('Choisissez un mot de passe :', form_input(Z_PASSWORD, 'pass1', '', 30)),
				form_ligne('Répétez le mot de passe :', form_input(Z_PASSWORD, 'pass2', '', 30)),
				form_ligne('Nom :', form_input(Z_TEXT, 'nom', $nom, 30)),
				form_ligne('Prénom :', form_input(Z_TEXT, 'prenom', $prenom, 30)),
				form_ligne('Date de naissance :', form_date('naiss', NB_ANNEES_DATE_NAISSANCE, $naiss_j, $naiss_m, $naiss_a)),
				form_ligne('Nom d\'utilisateur :', form_input(Z_TEXT, 'username', $username, 30)),
				'<tr><td colspan="2" style="padding-top: 10px;" class="centered">', form_input(Z_SUBMIT,'btnSInscrire','Je m\'inscris !'), '</td></tr>',
			'</table>',
		'</form>';
}

/**
 * Objectif : détecter les tentatives de piratage
 *
 * Si une telle tentative est détectée, la session est détruite et l'utilisateur est redirigée
 * vers la page d'accueil du site
 *
 * @global  array     $_POST
 *
 */
function l_control_piratage(){
    $nb = count($_POST);
    if ($nb == 2){
        (! isset($_POST['btnInscription']) || $_POST['btnInscription'] != 'S\'inscrire') && exit_session();
        //(! isset($_POST['source'])) && exit_session();
        (strip_tags($_POST['source']) != $_POST['source']) && exit_session();
        return;     // => ok, pas de problème détecté
    }
    if ($nb == 11){
        (! isset($_POST['btnSInscrire']) || $_POST['btnSInscrire'] != 'Je m\'inscris !') && exit_session();
        (! isset($_POST['source'])) && exit_session();
        (strip_tags($_POST['source']) != $_POST['source']) && exit_session();
        (! isset($_POST['email'])) && exit_session();
        (! isset($_POST['pass1'])) && exit_session();
        (! isset($_POST['pass2'])) && exit_session();
        (! isset($_POST['nom'])) && exit_session();
        (! isset($_POST['username'])) && exit_session();
        (! isset($_POST['prenom'])) && exit_session();
        (! isset($_POST['naiss_j'])) && exit_session();
        (! isset($_POST['naiss_m'])) && exit_session();
        (! isset($_POST['naiss_a'])) && exit_session();
        (!est_entier($_POST['naiss_a']) || !est_entier($_POST['naiss_m']) || !est_entier($_POST['naiss_j'])) && exit_session();
        $aa = date('Y');
        ($_POST['naiss_j'] < 1 || $_POST['naiss_j'] > 31 || $_POST['naiss_m'] < 1 || $_POST['naiss_m'] > 12 ||
        $_POST['naiss_a'] > $aa || $_POST['naiss_a'] <= $aa - NB_ANNEES_DATE_NAISSANCE) && exit_session();

        return;     // => ok, pas de problème détecté
    }
    exit_session();
}

function l_verify_data($email, $pass1, $pass2, $nom, $prenom, $username, $naiss_j, $naiss_m, $naiss_a) {
    $err = array();

    /*$email = trim($_POST['email']);
	$pass1 = trim($_POST['pass1']);
	$pass2 = trim($_POST['pass2']);
	$nom = trim($_POST['nom']);
	$prenom = trim($_POST['prenom']);
	$username = trim($_POST['username']);
	$naiss_j = (int)$_POST['naiss_j'];
	$naiss_m = (int)$_POST['naiss_m'];
	$naiss_a = (int)$_POST['naiss_a'];*/

	// vérification email
    $noTags = strip_tags($email);
    if ($noTags != $email){
        $err['email'] = 'L\'email ne peut pas contenir de code HTML.';
    }
    else {
        /*$i = mb_strpos($email, '@', 0, 'UTF-8');
        $j = mb_strpos($email, '.', 0, 'UTF-8');
        if ($i === FALSE || $j === FALSE || $i >= j){
            $err['email'] = 'L\'adresse email ne respecte pas le bon format.';
        }
        // le test suivant rend inutile celui qui précède
        else */if (! filter_var($email, FILTER_VALIDATE_EMAIL)){
            $err['email'] = 'L\'adresse email ne respecte pas le bon format.';
        }
    }

	// vérification des mots de passe
	if ($pass1 != $pass2) {
		$err['pass1'] = 'Les mots de passe doivent être identiques.';
	}
	else {
		$nb = mb_strlen($pass1, 'UTF-8');
        $noTags = strip_tags($pass1);
        if (mb_strlen($noTags, 'UTF-8') != $nb) {
            $err['pass1'] = 'La zone Mot de passe ne peut pas contenir de code HTML.';
		}
        else if ($nb < 8 || $nb > 20){
            $err['pass1'] = 'Le mot de passe doit être constitué de 8 à 20 caractères.';
        }



        //vérification que le mot de passe est composé d'au moin une majuscule, un chiffre et un caractére spécial
        $maj = false;
        $digit = false;
        $spec = false;
        for ($i=0 ; $i<$nb ; $i++)
        {
            $letter = $pass1[$i];

            if($letter >= '0' && $letter <='9')
            {
                $digit = true;
            }

            if($letter >= 'A' && $letter <='Z')
            {
                $maj = true;
            }

            if(strpos($letter,"*.!@$%^&(){}[]:;<>,.?/~_+-=|\\") < strlen("*.!@$%^&(){}[]:;<>,.?/~_+-=|\\)"))
            {
                $spec =true;
            }
        }

        if($maj == false || $digit == false || $spec == false)
        {
            $err['pass1'] = 'Le mot de passe doit contenir au moin une majuscule, un chiffre et un caractère spécial.';
        }

        /*
        $pattern = "^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$";
        if(preg_match($pattern,$pass1) == 0)
        {
            $err['pass1'] = 'Le mot de passe doit contenir au moin une majuscule, un chiffre et un caractère spécial.';
        }
        */

	}

	// vérification des noms et prenoms
	$noTags = strip_tags($prenom);
    if ($noTags != $prenom){
        $err['prenom'] = 'Le prénom ne peut pas contenir de code HTML.';
    }
    else if (empty($prenom)) {
		$err['prenom'] = 'Le prénom doit être renseigné.';
    }
    /*elseif (! preg_match("/^[[:alpha:]][[:alpha:]\- ']{1,99}$/", $nomprenom)) { // ne fct pas avec les accents
        $err['nomprenom'] = 'Le nom et le prénom ne sont pas valides.';
    }*/
    elseif (mb_regex_encoding ('UTF-8') && ! mb_ereg_match("^[[:alpha:]][[:alpha:]\- ']{1,99}$", $prenom)) {
        $err['prenom'] = 'Le prénom n\'est pas valide.';
    }

    $noTags = strip_tags($nom);
    if ($noTags != $nom){
        $err['nom'] = 'Le nom ne peut pas contenir de code HTML.';
    }
    else if (empty($nom)) {
		$err['nom'] = 'Le nom doit être renseigné.';
    }
    /*elseif (! preg_match("/^[[:alpha:]][[:alpha:]\- ']{1,99}$/", $nomprenom)) { // ne fct pas avec les accents
        $err['nomprenom'] = 'Le nom et le prénom ne sont pas valides.';
    }*/
    elseif (mb_regex_encoding ('UTF-8') && ! mb_ereg_match("^[[:alpha:]][[:alpha:]\- ']{1,99}$", $nom)) {
        $err['nom'] = 'Le nom n\'est pas valide.';
    }

	$noTags = strip_tags($username);
    if ($noTags != $username){
        $err['username'] = 'Le nom d\'utilisateur ne peut pas contenir de code HTML.';
    }
    else if (empty($username)) {
		$err['username'] = 'Le nom d\'utilisateur doit être renseigné.';
    }
    /*elseif (! preg_match("/^[[:alpha:]][[:alpha:]\- ']{1,99}$/", $nomprenom)) { // ne fct pas avec les accents
        $err['nomprenom'] = 'Le nom et le prénom ne sont pas valides.';
    }*/
    elseif (mb_regex_encoding ('UTF-8') && ! mb_ereg_match("^[[:alpha:]][[:alpha:]\-']{1,99}$", $username)) {
        $err['username'] = 'Le nom d\'utilisateur n\'est pas valide.';
    }

	// vérification de la date de naissance
	if (! checkdate($naiss_m, $naiss_j, $naiss_a)) {
		$err['date'] = 'La date de naissance est incorrecte.';
	}
	else {
		$dateDuJour = getDate();
		if (($naiss_a < $dateDuJour['year'] - 120) ||
            ($naiss_a == $dateDuJour['year'] - 120 && $naiss_m < $dateDuJour['mon']) ||
            ($naiss_a == $dateDuJour['year'] - 120 && $naiss_m == $dateDuJour['mon'] && $naiss_j <= $dateDuJour['mday'])) {
			$err['date'] = 'Votre date de naissance indique que vous avez plus de 120 ans.';
		}
		else if (($naiss_a > $dateDuJour['year'] - 12) ||
				 ($naiss_a == $dateDuJour['year'] - 12 && $naiss_m > $dateDuJour['mon']) ||
				 ($naiss_a == $dateDuJour['year'] - 12 && $naiss_m == $dateDuJour['mon'] && $naiss_j > $dateDuJour['mday'])) {
			$err['date'] = 'Votre date de naissance indique que vous avez moins de 12 ans.';
		}
	}

	if (count($err) == 0) {
		// vérification de l'unicité de l'adresse email
		// (uniquement si pas d'autres erreurs, parce que ça coûte un bras)
		$bd = bd_connect();

		// pas utile, car l'adresse a déjà été vérifiée, mais tellement plus sécurisant...
		$email = bd_protect($bd, $email);
		//TODO requete sql
		$sql = "SELECT ID FROM Personne WHERE Email = '$email'";

		$res = mysqli_query($bd,$sql) or bd_erreur($bd,$sql);

		if (mysqli_num_rows($res) != 0) {
			$err['email'] = 'L\'adresse email spécifiée existe déjà.';
            // libération des ressources
            mysqli_free_result($res);
		}
        else{
            // libération des ressources
            mysqli_free_result($res);
        }

            mysqli_close($bd);
	}
	return $err;
}

/**
 *	Traitement de l'inscription
 *
 *		Etape 1. vérification de la validité des données
 *					-> return des erreurs si on en trouve
 *		Etape 2. enregistrement du nouvel inscrit dans la base
 *		Etape 3. ouverture de la session et redirection vers la page appelante.
 *
 * @global  array     $_POST
 *
 * @return array 	tableau assosiatif contenant les erreurs
 */
function l_inscription() {

	//$err = array();


    $bd = bd_connect();


	$email = trim($_POST['email']);
	$pass1 = trim($_POST['pass1']);
	$pass2 = trim($_POST['pass2']);
	$nom = trim($_POST['nom']);
	$prenom = trim($_POST['prenom']);
	$username = trim($_POST['username']);
	$naiss_j = (int)$_POST['naiss_j'];
	$naiss_m = (int)$_POST['naiss_m'];
	$naiss_a = (int)$_POST['naiss_a'];

	$err = l_verify_data($email, $pass1, $pass2, $nom, $prenom, $username, $naiss_j, $naiss_m, $naiss_a);

	// s'il y a des erreurs ==> on retourne le tableau d'erreurs
	if (count($err) > 0) {
		return $err;
	}

	// pas d'erreurs ==> enregistrement de l'utilisateur
	$email = bd_protect($bd, $email);
	$nom = bd_protect($bd, $nom);
	$prenom = bd_protect($bd, $prenom);
	$username = bd_protect($bd, $username);
	$pass = bd_protect($bd, md5($pass1));
	$aaaammjj = $naiss_a*10000  + $naiss_m*100 + $naiss_j;

    // les champs adresse, code postal, ville et pays doivent être spécifiés
    // (Contrainte NON NULL dans la table 'client' sans indiquer de valeur par défaut)
    //$invalid = INVALID_STRING;
    //$code_postal = INVALID_CODE_POSTAL;

	$sql = "INSERT INTO Personne(Nom, Prenom, Admin, Username, Email, EmailVerifie, motDePasse, DateNaissance)
			VALUES ('$nom', '$prenom', false, '$username', '$email', false, '$pass', '$aaaammjj')";


	mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	$id = mysqli_insert_id($bd);

	// libération des ressources
	mysqli_close($bd);

	// mémorisation de l'ID dans une variable de session
    // cette variable de session permet de savoir si le client est authentifié
	$_SESSION['ID'] = $id;

    // redirection vers la page d'origine
    redirige($_POST['source']);

	return $err;
}



?>
