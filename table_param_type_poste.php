<?php

$table = 'gp_type_poste';
$titre = "Paramétrage Type Poste";

$champs = array (
	"id_type_poste" => array (
		"nom" => "",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number"
	),
	"lb_type_poste" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
	)
);

if(!$no_load)
	include "ress_parametrage_tables.php";
?>