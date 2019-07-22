<?php
require_once("ress/register_globals.php");
require_once("ress/db_mysql.php");
$id=$_POST['id'];
$qt=$_POST['qt'];
$qt=str_replace (",",".",$qt);
$qt=round($qt*1,2);
if ($id) {
	$requete= new db_sql("
		update 
			besoin 
		set qt = $qt
			where 
		id_besoin = $id");
	echo "{id:".$id.",qt:".$qt."}";
}