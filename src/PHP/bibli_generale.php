<?php
//require_once 'bibli_bookshop.php';

/*********************************************************
 *        Bibliothèque de fonctions génériques           *
 *********************************************************/

 // Paramètres pour accéder à la base de données

define('BS_SERVER', 'localhost');
//define('BS_DB', 'm2test3');
//define('BS_USER', 'root');
//define('BS_PASS', '');


define('BS_DB', 'm2test3');
define('BS_USER', 'm2test3');
define('BS_PASS', 'm2test3');

//---------------------------------------------------------------
// Définition des types de zones de saisies
//---------------------------------------------------------------
define('Z_TEXT', 'text');
define('Z_PASSWORD', 'password');
define('Z_DATE', 'date');
define('Z_SUBMIT', 'submit');
define('Z_HIDDEN', 'hidden');
define('Z_RADIO', 'radio');

define('NB_ANNEES_DATE_NAISSANCE', 120);

date_default_timezone_set('Europe/Paris');

/**
 *	Fonction affichant le début du code HTML d'une page.
 *
 *	@param 	String	$titre	Titre de la page
 *	@param 	String	$css	Chemin relatif vers la feuille de style CSS.
 */
function html_debut($titre, $css) {
	$css = ($css == '') ? '' : "<link rel=\"stylesheet\" type=\"text/css\" href=\"$css\">";
	echo
		'<!doctype html>',
		'<html lang="fr">',
			'<head>',
				'<title>', $titre, '</title>',
				'<meta charset="UTF-8">',
				'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">' .
			   	$css,
			'</head>',
			'<body>';
}


/**
 *	Fonction affichant la fin du code HTML d'une page.
 */
function html_fin() {
	echo '<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script></body></html>';
}



//____________________________________________________________________________
/**
 *	Ouverture de la connexion à la base de données
 *
 *	@return objet 	connecteur à la base de données
 */
function bd_connect() {
    $conn = mysqli_connect(BS_SERVER, BS_USER, BS_PASS, BS_DB);
    if ($conn !== FALSE) {
        //mysqli_set_charset() définit le jeu de caractères par défaut à utiliser lors de l'envoi
        //de données depuis et vers le serveur de base de données.
        mysqli_set_charset($conn, 'utf8')
        or bd_erreurExit('<h4>Erreur lors du chargement du jeu de caractères utf8</h4>');
        return $conn;     // ===> Sortie connexion OK
    }
    // Erreur de connexion
    // Collecte des informations facilitant le debugage
    $msg = '<h4>Erreur de connexion base MySQL</h4>'
            .'<div style="margin: 20px auto; width: 350px;">'
            .'BD_SERVER : '. BS_SERVER
            .'<br>BS_USER : '. BS_USER
            .'<br>BS_PASS : '. BS_PASS
            .'<br>BS_DB : '. BS_DB
            .'<p>Erreur MySQL numéro : '.mysqli_connect_errno()
            .'<br>'.htmlentities(mysqli_connect_error(), ENT_QUOTES, 'ISO-8859-1')
            //appel de htmlentities() pour que les éventuels accents s'affiche correctement
            .'</div>';
    bd_erreurExit($msg);
}

//____________________________________________________________________________
/**
 * Arrêt du script si erreur base de données
 *
 * Affichage d'un message d'erreur, puis arrêt du script
 * Fonction appelée quand une erreur 'base de données' se produit :
 * 		- lors de la phase de connexion au serveur MySQL
 *		- ou indirectement lorsque l'envoi d'une requête échoue
 *
 * @param string	$msg	Message d'erreur à afficher
 */
function bd_erreurExit($msg) {
    ob_end_clean();	// Supression de tout ce qui a pu être déja généré
    ob_start('ob_gzhandler'); // nécessaire sur saturnin quand compression avec ob_gzhandler
    echo    '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>',
            'Erreur base de données</title>',
            '<style>table{border-collapse: collapse;}td{border: 1px solid black;padding: 4px 10px;}</style>',
            '</head><body>',
            $msg,
            '</body></html>';
    exit(1);
}


//____________________________________________________________________________
/**
 * Gestion d'une erreur de requête à la base de données.
 *
 * A appeler impérativement quand un appel de mysqli_query() échoue
 * Appelle la fonction bd_erreurExit() qui affiche un message d'erreur puis termine le script
 *
 * @param objet		$bd		Connecteur sur la bd ouverte
 * @param string	$sql	requête SQL provoquant l'erreur
 */
function bd_erreur($bd, $sql) {
    $errNum = mysqli_errno($bd);
    $errTxt = mysqli_error($bd);

    // Collecte des informations facilitant le debugage
    $msg =  '<h4>Erreur de requête</h4>'
            ."<pre><b>Erreur mysql :</b> $errNum"
            ."<br> $errTxt"
            ."<br><br><b>Requête :</b><br> $sql"
            .'<br><br><b>Pile des appels de fonction</b></pre>';

    // Récupération de la pile des appels de fonction
    $msg .= '<table>'
            .'<tr><td>Fonction</td><td>Appelée ligne</td>'
            .'<td>Fichier</td></tr>';

    $appels = debug_backtrace();
    for ($i = 0, $iMax = count($appels); $i < $iMax; $i++) {
        $msg .= '<tr style="text-align: center;"><td>'
                .$appels[$i]['function'].'</td><td>'
                .$appels[$i]['line'].'</td><td>'
                .$appels[$i]['file'].'</td></tr>';
    }

    $msg .= '</table>';

    bd_erreurExit($msg);	// => ARRET DU SCRIPT
}


/**
 *	Protection des sorties (code HTML généré à destination du client).
 *
 *  Fonction à appeler pour toutes les chaines provenant de :
 *		- de saisies de l'utilisateur (formulaires)
 *		- de la bdD
 *	Permet de se protéger contre les attaques XSS (Cross site scripting)
 * 	Convertit tous les caractères éligibles en entités HTML, notamment :
 *		- les caractères ayant une signification spéciales en HTML (<, >, ...)
 *		- les caractères accentués
 *
 *	@param	string 	$text	la chaine à protéger
 * 	@return string 	la chaîne protégée
 */
function protect_sortie($str) {
	$str = trim($str);
	return htmlentities($str, ENT_QUOTES, 'UTF-8');
}

/*
 * Protection des chaînes avant insertion dans une requête SQL
 *
 * Avant insertion dans une requête SQL, toutes les chaines contenant certains caractères spéciaux (", ', ...)
 * doivent être protégées. En particulier, toutes les chaînes provenant de saisies de l'utilisateur doivent l'être.
 * Echappe les caractères spéciaux d'une chaîne (en particulier les guillemets)
 * Permet de se protéger contre les attaques de type injections SQL
 *
 * @param 	objet 		$bd 	La connexion à la base de données
 * @param 	string 		$str 	La chaîne à protéger
 * @return 	string 				La chaîne protégée
 */
function bd_protect($bd, $str) {
	$str = trim($str);
	return mysqli_real_escape_string($bd, $str);
}


/**
 * Redirige l'utilisateur sur une page
 *
 * @param string	$page		Page où l'utilisateur est redirigé
 */
function redirige($page) {
	header("Location: $page");
	exit();
}


/**
 * Arrête une session et effectue une redirection vers la page index.php
 *
 * Elle utilise :
 *   -   la fonction session_destroy() qui détruit la session existante
 *   -   la fonction session_unset() qui efface toutes les variables de session
 * Puis, le cookie de session est supprimé
 *
 */
function exit_session() {
	session_destroy();
	session_unset();
	$cookieParams = session_get_cookie_params();
	setcookie(session_name(),
			'',
			time() - 86400,
         	$cookieParams['path'],
         	$cookieParams['domain'],
         	$cookieParams['secure'],
         	$cookieParams['httponly']
    	);

	header('Location: ../../index.php');
	exit();
}

/**
 * Teste si une valeur est une valeur entière
 *
 * @param mixed     $x  valeur à tester
 * @return boolean  TRUE si entier, FALSE sinon
*/
function est_entier($x) {
    return is_numeric($x) && ($x == (int) $x);
}

//_______________________________________________________________
//
//		FONCTIONS UTILISEES DANS LES FORMULAIRES
//_______________________________________________________________

/**
* Génére le code d'une ligne de formulaire :
*
* @param string		$gauche		Contenu de la colonne de gauche
* @param string 	$droite		Contenu de la colonne de droite
*
* @return string 	Code HTML représentant une ligne de tableau
*/
function form_ligne($gauche, $droite) {
    $gauche =  protect_sortie($gauche);
    return "<tr><td>{$gauche}</td><td>{$droite}</td></tr>";
}

/**
* Génére le code d'une ligne de formulaire pour une date :
*
* @param string		$label		Contenu de la colonne de gauche qui est le label
* @param string 	$date		  Contenu de la premiere partie de la colonne de droite : le input date
* @param string   $heure    Contenu de la deuxième partie de la colonne de droite : le input time
*
* @return string 	Code HTML représentant une ligne de tableau
*/
function form_ligne_date($label, $date, $heure){
	return "<tr><td>{$label}</td><td>{$date} {$heure}</td></tr>";
}

//_______________________________________________________________
/**
* Génére le code d'une zone input de formulaire (type input) :
*
* @param String		$type	Type de l'input ('text', 'hidden', ...).
* @param string		$name	Nom de la zone (attribut name).
* @param String		$value	Valeur par défaut (attribut value).
* @param integer	$size	Taille du champ (attribut size).
*
* @return string Code HTML de la zone de formulaire
*/
function form_input($type, $name, $value, $size=0, $checked=0) {
   $value =  protect_sortie($value);
   $size = ($size == 0) ? '' : "size='{$size}'";
   $checked = ($checked==0) ? '' : "checked";

   return "<input type='{$type}' name='{$name}' {$size} value='{$value}' {$checked}>";
}

/**
 * Renvoie le nom d'un mois.
 *
 * @param integer	$numero		Numéro du mois (entre 1 et 12)
 *
 * @return string 	Nom du mois correspondant
 */
function get_mois($numero) {
	$numero = (int) $numero;
	($numero < 1 || $numero > 12) && $numero = 0;

	$mois = array('Erreur', 'Janvier', 'F&eacute;vrier', 'Mars',
				'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t',
				'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');

	return $mois[$numero];
}


/**
 * Option dans les formulaires
 *
 * @param integer	$name		Nom du select
 * @param integer	$nbQuantite	nombre de champ
 *
 * @return string 	Retourne le code HTML associe
 */
function form_opt($name, $nbQuantite=0) {
	$nbQuantite=(int)$nbQuantite;
	$res = "<select name='{$name}'>";
	for ($i=1; $i <= 100 ; $i++){
        $selected = ($i == $nbQuantite) ? 'selected' : '';
		$res .= "<option value='$i' $selected>$i</option>";
	}
	$res .= '</select>';
	return $res;
}

/**
* Génére le code pour un ensemble de trois zones de sélection représentant une date : jours, mois et années
*
* @param string		$nom	    Préfixe pour les noms des zones
* @param integer    $nb_annees  Nombre d'années à afficher
* @param integer	$jour 	    Le jour sélectionné par défaut
* @param integer	$mois 	    Le mois sélectionné par défaut
* @param integer	$annee	    L'année sélectionnée par défaut
*
* @return string 	Le code HTML des 3 zones de liste
*/
function form_date($name, $nb_annees, $jsel=0, $msel=0, $asel=0){
	$jsel=(int)$jsel;
	$msel=(int)$msel;
	$asel=(int)$asel;
	$d = date('Y-m-d');
	list($aa, $mm, $jj) = explode('-', $d);
	($jsel==0) && $jsel = $jj;
	($msel==0) && $msel = $mm;
	($asel==0) && $asel = $aa;

	$res = "<select name='{$name}_j'>";
	for ($i=1; $i <= 31 ; $i++){
        $selected = ($i == $jsel) ? 'selected' : '';
		$res .= "<option value='$i' $selected>$i</option>";
	}
	$res .= "</select> <select name='{$name}_m'>";
	for ($i=1; $i <= 12 ; $i++){
		$selected = ($i == $msel)? 'selected' : '';
		$res .= "<option value='$i' $selected>".get_mois($i).'</option>';
	}
	$res .= "</select> <select name='{$name}_a'>";
	for ($i=$aa; $i > $aa - $nb_annees ; $i--){
		$selected = ($i == $asel) ? 'selected' : '';
		$res .= "<option value='$i' $selected>$i</option>";
	}
	$res .= '</select>';
	return $res;
}

function form_date_and_hour($name, $jour, $mois, $annee, $heure, $minutes){
	$res = "<select name='{$name}_j'>";
	for ($i = 1; $i <= 31; $i++){
		$selected = ($i == $jour)? 'selected':'';
		$res.="<option value='$i' $selected>$i</option>";
	}
	$res.="</select><select name='{$name}_m'>";
	for ($i = 1; $i <= 12; $i++){
		$selected = ($i == $mois)? 'selected':'';
		$res.="<option value='$i' $selected>".get_mois($i)."</option>";
	}
	$res.="</select><select name='{$name}_a'>";
	for ($i = 2020; $i <= 2050; $i++){
		$selected = ($i == $annee)? 'selected':'';
		$res.="<option value='$i' $selected>$i</option>";
	}

	if ($heure==-1 && $minutes==-1){
		$res.="</select>";
	} else {
		$res.="</select><span>Heure:</span><select name='{$name}_hr'>";
		for ($i = 0; $i < 24; $i++){
			$selected = ($i == $heure)? 'selected':'';
			$res.="<option value='$i' $selected>$i</option>";
		}
		$res.="</select><select name='{$name}_min'>";
		for ($i = 0; $i < 60; $i++){
			$selected = ($i == $minutes)? 'selected':'';
			$res.="<option value='$i' $selected>$i</option>";
		}
		$res.="</select>";
	}
	return $res;
}



/**
* Extrait et renvoie le nom du fichier cible contenu dans une URL
*
* Exemple : si la fonction reçoit l'URL
*    http://localhost/bookshop/php/page1.php?nom=valeur&name=value
* elle renvoie 'page1.php'
*
* @param string		$url        URL à traiter
*
* @return string 	Le nom du fichier cible
*/
function url_get_nom_fichier($url){
    $nom = basename($url);
    $pos = mb_strpos($nom, '?', 0, 'UTF-8');
    if ($pos !== false){
        $nom = mb_substr($nom, 0, $pos, 'UTF-8');
    }
    return $nom;
}

/**
 *	Renvoie un tableau contenant les pages du site Goodle
 *
 * 	@return array pages du site
 */
function get_pages_goodle() {
	return array('index.php', 'login.php', 'inscription.php', 'deconnexion.php', 'ajout_evenement.php', 'voir_event.php', 'evenement_ok.php', 'admin_user_view.php');
}

/**
* Retourne sur la page précédente
*/
function page_precedente(){
    if(isset($_SERVER['HTTP_REFERER'])){
        $source=$_SERVER['HTTP_REFERER'];
        $nom_source = url_get_nom_fichier($source);
        // si la page appelante n'appartient pas à notre site
        if (! in_array($nom_source, get_pages_goodle())){
            $source = '../../index.php';
        }
    }
    else{
        $source='../../index.php';
    }
    redirige($source);
}

/**
 * Compare la date passée dans les paramètres à la date actuelle
 * @return {0,1,2} -> 0 : la date en paramètre est dans le futur (OK) / (KO) 1 : la date en paramètre est dans le passé / 2 : la date en paramètre est la même que aujourdhui mais l'heure est dans le passé
 */
function compare_date($jour, $mois, $annee, $heure, $minute){
	$res = compare_deux_dates(date("d"), date("m"), date("Y"), $jour, $mois, $annee);

	if ($res == 2){
		$res_hr = compare_deux_heures($heure, $minute, date('H'), date('i'));
		if ($res_hr == 1 || $res_hr == 2){
			return 2;
		} else {
			return 0;
		}
	}
	return $res;
}

/**
 * Compare les deux dates passées en paramètre
 * @return {0,1,2} -> 0 : la première est antérieure à la seconde / 1 : la seconde est antérieure à la première / 2 : les deux sont égales
 */
function compare_deux_dates($jour1, $mois1, $annee1, $jour2, $mois2, $annee2){
	if ($jour1 == $jour2 && $mois1==$mois2 && $annee1 == $annee2){
		return 2;
	}
	if ($annee1 == $annee2){
		if ($mois1 == $mois2){
			if ($jour1 > $jour2){
				return 1;
			} else {
				return 0;
			}
		} else if ($mois1 > $mois2){
			return 1;
		} else {
			return 0;
		}
	} else if ($annee1 > $annee2){
		return 1;
	} else {
		return 0;
	}
}

/**
 * Compare les heures passées en paramètre
 * @return {0, 1} -> 0: l'heure 1 est dans le futur / 1: l'heure 2 est dans le futur / 2: les heures sont les mêmes
 */
function compare_deux_heures($heure1, $minute1, $heure2, $minute2){

	if ($heure1 == $heure2){
		if ($minute1 > $minute2){
			return 0;
		} else if ($minute1 == $minute2){
			return 2;
		} else {
			return 1;
		}
	} else if ($heure1 > $heure2) {
		return 0;
	} else {
		return 1;
	}
	return 1;
}

function ecrireHeure($heure, $minute){
	$res = "$heure h ";
	if ($minute == 0){
		$res=$res.'00';
	}else{
		$res=$res."$minute";
	}
	return $res;
}

function goodle_header($pathToRoot = '../../') {
    if (isset($_SESSION['ID'])) {
        echo '<a href="', $pathToRoot,'/src/PHP/deconnexion.php"><button type="button" class="btn btn-danger">Déconnexion</button></a>';
    }
}

function choose_order($path, $event_param = 'event') {
	echo '<div class="btn-group" role="group" aria-label="Basic example">
		<a href="'. $path . '?' . $event_param . '=' . $_GET[$event_param] . '&order=date&dir=asc"><button type="button" class="btn btn-secondary">Date croissante</button></a>
		<a href="'. $path .'?' . $event_param . '=' . $_GET[$event_param] . '&order=date&dir=desc"><button type="button" class="btn btn-secondary">Date décroissante</button></a>
		<a href="'. $path . '?' . $event_param .'=' . $_GET[$event_param] . '"><button type="button" class="btn btn-secondary">Pas de tri</button></a>
	</div>';
}
?>
