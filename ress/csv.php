<?
/* module générique de sortie excel d'un tableau */

/* paramètre : $tableau */

if (!is_array($_SESSION)) {
	include ("../ress/var_session.php");
}
include ("util.php");
include ("register_globals.php");
register_globals('gp');
set_time_limit(0);
if (!count($_SESSION[$tableau])) {
?>
Pas de donnees a afficher.
<?
   exit();
}
if ($nodecale) flux_ouvre('temp/temp.csv'); 
else flux_ouvre('temp/temp.csv');
foreach ($_SESSION[$tableau][0] as $titre => $valeur) {
   flux_ecrit($titre.";");
}
flux_ecrit("
");
foreach ($_SESSION[$tableau] as $ligne) {
	foreach ($ligne as $valeur) {
		flux_ecrit($valeur.";");	
	}
	flux_ecrit("
");
}
flux_ferme();
?>
