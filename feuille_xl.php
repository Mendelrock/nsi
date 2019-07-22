<?php
include ("ress/var_session.php");
require_once('ress/fpdf/fpdf.php');
require_once('ress/fpdf_js.php');
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");

// Critere de recherche
if($req = trim($_SESSION[fdc_requete])){

	$req = new db_sql($req);  
	while($req->n()){
		$docs[] = $req->f("id_doc");
	}
	$dataset = new dataset($type_dataset_groupe_ligne);
	foreach ($docs as $id_doc) {
		$liste_produits = charge("select produit, id_dataset from doc_ligne where id_doc = $id_doc");
		foreach ($liste_produits as $i => $produits) {
			require("param_2_produits/".$produits[produit].".php");
			$type_dataset = $produits[$produits[produit]][type_dataset_ligne]; 
			$dataset = new dataset($type_dataset);
			$dataset->load_from_database($produits[id_dataset]);
			$tableau = array();
			//var_dump($dataset);
			foreach ($dataset->ochamp as $id_champ => $champ) {
				$entete[$champ->lb_champ] = '';
				$tableau[$champ->lb_champ] = $champ->get();
				if ($champ->id_type_champ == "toile") {
					$tableau[$champ->lb_champ] = getColoris($champ);
				} else if ($champ->id_type_champ == "lov") {
					$tableau[$champ->lb_champ] = getValeurLov($champ);
				}
			}
			$tableau[PRODUIT] = $produits[produit];
			$extcontfeu[] = $tableau;
		}
	}
	array_unshift($extcontfeu, $entete);
	$_SESSION['extcontfeu'] = $extcontfeu;
	$tableau="extcontfeu";
	$nodecale=true;
	include("./ress/xl.php"); 											
}
?>