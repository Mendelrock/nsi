<?php
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");
if ($_GET['id_doc']) {    
	$list_id_doc = array();
	$list_id_doc[] = $_GET['id_doc'];
	include_once 'doc_generer.php';
	$pdf = generer_doc($list_id_doc);
	$pdf->Output();
}
?>