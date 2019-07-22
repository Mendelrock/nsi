<?php
session_start();
include("ress/db_mysql.php");

$table = $_GET['table'];
$no_load = true;
include "table_param_".$table.".php";

foreach($champs as $champ=>$proprietes) {
    $entete[] = $champ;
    if ($proprietes[liste_valeurs]) {
        $req = new db_sql($proprietes[liste_valeurs]);
        while($req->n()) {
            $champs[$champ]['valeurs'][$req->f('id')] = $req->f('id'); // id à la place de 'lb'
        }
    }
}

if(isset($_SESSION['clause_orderby'])){
	$clause = $_SESSION['clause_orderby'];
} else {
	header("Location: table_param_".$table.".php");
	exit();
}

if(!$erreur){
	$req = new db_sql("
		select
			*
		from
			$table
		$clause
		");

	while ($req->n()) {
		$rec = array();
		foreach($champs as $champ=>$proprietes) {
			$valeur = $req->f($champ);
			if ($proprietes[liste_valeurs]) {
				//$valeur = $champs[$champ]['valeurs'][$valeur];
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

	$_SESSION['export_table']['entete'] = $entete;
	$_SESSION['extparm'] = $extparm;
	$tableau="extparm";
	$nodecale=true;

	include "ress/export_table_xl.php";
}

?>