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

// si $_POST non vide
$err = ($_POST) ? l_traitement_connexion() : 0;

html_debut('Goodle | Connexion', '../src/CSS/styles.css');

l_contenu($err);

html_fin();

ob_end_flush();

// ----------  Fonctions locales au script ----------- //

/**
 *	Affichage du contenu de la page (formulaire de login + lien vers la page d'inscription).
 *
 *	@param 	int		$err 	erreur de connexion (0 pas d'erreur, -1 erreur)
 */
function l_contenu($err) {

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
		'<h1>Connexion à Goodle</h1>', 
		($err != 0) ? '<p class="erreur">Echec de l\'authentification</p>' : '',
        '<div id="bcInscription">',
            '<form action="login.php" method="post" class="bcFormulaireBoite">',
                '<p class="enteteBloc">Déjà inscrit ?</p>',
                form_input(Z_HIDDEN,'source', $source),
                '<table>',
                    form_ligne('Email :', form_input(Z_TEXT,'email','',20)),
                    form_ligne('Mot de passe :', form_input(Z_PASSWORD,'password','',20)),
                '</table>',
                form_input(Z_SUBMIT,'btnConnexion', 'Se connecter'),
            '</form>',

            '<form action="inscription.php" method="post" class="bcFormulaireBoite">',
                '<p class="enteteBloc">Pas encore inscrit ?</p>',
                '<input type="hidden" name="source" value="', $source,'">',
                '<p>L\'inscription est gratuite et ne prend que quelques secondes.</p>',
                form_input(Z_SUBMIT,'btnInscription', 'S\'inscrire'),
            '</form>',
        '</div>';
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
    (count($_POST) != 4 ||
	! isset($_POST['btnConnexion']) || $_POST['btnConnexion'] != 'Se connecter' ||
	! isset($_POST['email']) ||
    ! isset($_POST['password']) ||
	! isset($_POST['source']) ||
    strip_tags($_POST['source']) != $_POST['source'] ) && exit_session();
}



/**
 *	Traitement de la connexion :
 *		identification du couple email/password dans la base. Redirection vers la page
 *		d'origine si les identifiants de connexion sont corrects.
 *
 * @global  array     $_POST
 *
 *	@return		int 	-1 si la connexion a échouée (mauvaise combinaison login/password)
 */
function l_traitement_connexion() {

	// connexion à la base de données
	$bd = bd_connect();

	// sanitization des données postées
	$email = bd_protect($bd, $_POST['email']);
	$password = bd_protect($bd, md5($_POST['password']));

	// requête SQL
	$sql = "SELECT ID FROM Personne WHERE Email = '$email' AND motDePasse = '$password'";

	// execution de la requête
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);

	// test de l'existence d'un client ayant cette combinaison email/password
	if (mysqli_num_rows($res) != 1) {
		mysqli_free_result($res);
		mysqli_close($bd);
		return -1;	            // => ECHEC DE L'AUTHENTIFICATION
	}

	// récupération du numero client
	$t = mysqli_fetch_assoc($res);
	$id = $t['ID'];

	// mémorisation de l'ID du client dans une variable de session
    // cette variable de session permet de savoir si le client est authentifié
	$_SESSION['ID'] = $id;


	$sql = "SELECT admin FROM Personne WHERE ID ='$id'";
	// execution de la requête
	$res = mysqli_query($bd, $sql) or bd_erreur($bd, $sql);
	// test de l'existence d'un client ayant cette combinaison email/password
	if (mysqli_num_rows($res) != 1) {
		mysqli_free_result($res);
		mysqli_close($bd);
		return -1;	            // => ECHEC DE L'AUTHENTIFICATION
	}

	// récupération du numero client
	$t = mysqli_fetch_assoc($res);
	$admin = $t['admin'];
	// mémorisation de l'ID du client dans une variable de session
    // cette variable de session permet de savoir si le client est authentifié
	$_SESSION['admin'] = $admin;

	// fermeture des ressources et de la connexion à la base
	mysqli_free_result($res);
	mysqli_close($bd);

	// et redirection vers la page d'origine
	redirige($_POST['source']);

	// ne devrait pas arriver
	return 0;
}



?>
