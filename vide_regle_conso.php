<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
echo "<body class=\"application\">";
$req = new db_sql();
if ($req->Database == "bdm_ageclair") {
	exit("Pas en prod !");
}

$req = new db_sql("truncate table consommation");

echo "OK</body>";
include ("ress/enpied.php");
?>
