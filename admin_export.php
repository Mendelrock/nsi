<?
include("ress/db_mysql.php");
session_start();

$table = $_GET['TABLE'];
$req = $_GET['REQ'];
if($req) {
	$extparm = $_SESSION['tableau_req_export'];
} else {
	include ("param_6_tables/".$table.".php");

	foreach($champs as $champ=>$proprietes) {
		if ($proprietes[req]) {
			$req = new db_sql($proprietes[req]);
			while($req->n()) {
				$champs[$champ]['valeurs'][$req->f('id')]	= $req->f('lb');
			}
		}
	}

	$req = new db_sql("
		select
			*
		from
			$table
		");
	while ($req->n()) {
		$rec = array();
		foreach($champs as $champ=>$proprietes) {
			$valeur = $req->f($champ);
			if ($proprietes[req]) {
				$valeur = $champs[$champ]['valeurs'][$valeur];
			}
			$rec[$champ]=$valeur;
		}
		$extparm[] = $rec;
	}

	if(!$extparm){
		$rec = array();
		foreach ($champs as $champ => $name){
			$rec[$champ]=" ";
		}
		$extparm[] = $rec;
	}
}
$_SESSION['extparm'] = $extparm;
$tableau="extparm";
$nodecale=true;
include "ress/xl.php";
?>