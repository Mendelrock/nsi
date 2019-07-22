<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
echo "<body class=\"application\">";
$req = new db_sql();
if ($req->Database == "bdm_ageclair") {
	exit("Pas en prod !");
}
/*$req = new db_sql("delete from doc_ligne");
$req = new db_sql("delete from doc_groupe_ligne");
$req = new db_sql("delete from doc");
$req = new db_sql("delete from dataset");
$req = new db_sql("update article set qt_stock = 0");
$req = new db_sql("delete from besoin");
$req = new db_sql("delete from po_ligne");
$req = new db_sql("delete from po");
$req = new db_sql("truncate table adresse_echantillon");*/

$req = new db_sql("truncate table besoin");
$req = new db_sql("truncate table doc_ligne");
$req = new db_sql("truncate table doc_groupe_ligne");
$req = new db_sql("delete from doc");
$req = new db_sql("delete from dataset");
$tab = charge("select concat('truncate table `', table_name, '`;') from information_schema.tables where table_name like 'zdataset_%'");
foreach($tab as $query){
	$req = new db_sql($query);
}
$req = new db_sql("update article set qt_stock = 0");
$req = new db_sql("truncate table po_ligne");
$req = new db_sql("truncate table po");
$req = new db_sql("truncate table adresse_echantillon");

echo "OK</body>";
include ("ress/enpied.php");
?>
