<?php

$table = 'gp_poste';
$titre = "Paramétrage Postes";

$champs = array (
	"id_poste" => array (
		"nom" => "",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number"
	),
	"id_type_poste" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "liste",
		"liste_valeurs" =>  "SELECT id_type_poste as id, lb_type_poste as lb FROM gp_type_poste"
	),
	"lb_poste" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
	),
	"lb_of" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
	),
	"nb_heure" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "number"
	),
	"nb_ordre" => array (
		"nom" => "",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "number"
	)
);

if(!$no_load)
	include "ress_parametrage_tables.php";
?>