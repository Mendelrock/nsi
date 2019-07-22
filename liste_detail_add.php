<?
session_start();
foreach($_POST as $champ => $valeur) {
	if ((substr($champ,0,7) == "client_") and ($valeur == 1)) {
	    $_SESSION[client_à_ajouter_a_la_liste][] = substr($champ,7);
	}
}
$_SESSION[liste_list_date] = "";
$_SESSION[liste_list_lb_liste] = "";
$_SESSION[liste_list_id_utilisateur] = $_SESSION[prochain_utilisateur_pour_affectation_liste];
include ('liste_list.php');
?>