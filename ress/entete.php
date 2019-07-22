<?
/*-------- Variables de session -------*/
include ("register_globals.php");
register_globals('gp');
include ("var_session.php");
ini_set("auto_detect_line_endings", true);
define('FORBIDDEN',"Vous n'avez pas le droit de modifier avec ce statut");
define('SR',"Stores & Rideaux");
define('PS',"Prosolair");
define('TT',"Tende e Tende");
define('FDV',"Force de vente");
define('IN',"Internet");

if(!$_SESSION[id_utilisateur]){
?>
        <script langage="javascript">
        //top.location = "index.html";
        </script>
<?
	exit;
}
/*------- Bibliotheque ---------*/
include "ress/util.php";
include "ress/db_mysql.php";
function loadIncompatibilite () {
	if($GLOBALS[origine]) {
		unset($_SESSION[incompatibilites]);
		unset($_SESSION[incompatibilites_limites]);
		unset($_SESSION[incompatibilites_liste]);
		unset($_SESSION[incompatibilites_surface]);
		unset($_SESSION[incompatibilites_rapport]);
		$g_req = new db_sql("select * from incompatibilites order by produit");
		while ($g_req->n()) {
			$_SESSION[incompatibilite][] = array(utf8_encode($g_req->f('produit')),utf8_encode($g_req->f('prop1')),utf8_encode($g_req->f('val1')),utf8_encode($g_req->f('prop2')),utf8_encode($g_req->f('val2')));
	  	}
	  	$g_req = new db_sql("select * from incompatibilites_limites order by produit");
		while ($g_req->n()) {
			$_SESSION[incompatibilites_limites][] = array(utf8_encode($g_req->f('produit')),utf8_encode($g_req->f('prop1')),utf8_encode($g_req->f('val1')),utf8_encode($g_req->f('prop2')),utf8_encode($g_req->f('min')),utf8_encode($g_req->f('max')));
	  	}
	  	$g_req = new db_sql("select * from incompatibilites_liste order by produit");
		while ($g_req->n()) {
			$_SESSION[incompatibilites_liste][] = array(utf8_encode($g_req->f('produit')),utf8_encode($g_req->f('prop1')),utf8_encode($g_req->f('val1')),utf8_encode($g_req->f('prop2')),utf8_encode($g_req->f('val2')));
	  	}
	  	$g_req = new db_sql("select * from incompatibilites_surface order by produit");
		while ($g_req->n()) {
			$_SESSION[incompatibilites_surface][] = array(utf8_encode($g_req->f('produit')),utf8_encode($g_req->f('prop1')),utf8_encode($g_req->f('val1')),utf8_encode($g_req->f('prop2')),utf8_encode($g_req->f('prop3')),utf8_encode($g_req->f('min')),utf8_encode($g_req->f('max')));
	  	}
	  	$g_req = new db_sql("select * from incompatibilites_rapport order by produit");
		while ($g_req->n()) {
			$_SESSION[incompatibilites_rapport][] = array(utf8_encode($g_req->f('produit')),utf8_encode($g_req->f('prop1')),utf8_encode($g_req->f('val1')),utf8_encode($g_req->f('prop2')),utf8_encode($g_req->f('prop3')),utf8_encode($g_req->f('x')),utf8_encode($g_req->f('y')));
	  	}
	}
}
$Affichage_defaut = 500;
/*-------------------------------------*/
/*-------- Stop Refresh ---------------*/
function stop_refresh_form (){
   global $SC;
   ?><input type="hidden" name="SC" value="<? echo ($SC) ?>"><?
}

function stop_refresh_url (){
   global $SC;
   echo "SC=$SC";
}

function set_refresh ($consigne){
   global $SC_courant;
   if ($SC_courant) {
      $_SESSION[compteur_tableau][$SC_courant]=$consigne;
   }
}

if ($SC and $_SESSION[compteur_tableau][$SC]) {
   $consigne = explode("|",$_SESSION[compteur_tableau][$SC]);
   ${$consigne[0]} = $consigne[1];
   $ACTE = '';
}

$SC_courant = $SC;
$SC=$_SESSION[compteur]++;
$pass_test = 1;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="client/style.css" type="text/css">
<link rel="stylesheet" href="ress/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="ress/jquery-ui/jquery-ui.css">
<link rel="stylesheet" href="ress/datatables/jquery.dataTables.min.css">
<script src="ress/jquery.js"></script>
<script src="ress/jquery-ui/jquery-ui.js"></script>
<SCRIPT src="ress/util.js"></SCRIPT>
<SCRIPT src="ress/js/bootstrap.min.js"></SCRIPT>
<script src="ress/datatables/jquery.dataTables.min.js"></script>
</head>