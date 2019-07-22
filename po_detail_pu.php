<?php
require_once("ress/db_mysql.php");
require_once("c_o_dataset.php");

$res = charge_un("select prix from fournisseur_article where id_fournisseur = ".$_GET["id_fournisseur"]." and id_article = ".$_GET["id_article"]."");
echo "{\"pu\":\"".$res['prix']."\"}";
?>
