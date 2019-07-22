<?php

$table = 'param_rideau_hauteur_impact';
$titre = "Paramétrage Hauteur Impact";

$champs = array (
    "id_toile" => array (
        "nom" => "ID Toile",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
	"type" => array( 
		"nom" => "Type",
		"clee" => 1,
		"format" => "liste",
		"liste_valeurs" => "select 'simple' as id, 'simple' as lb from dual union select 'doublure' as id, 'doublure' as lb from dual"
	),
    "confection" => array (
        "nom" => "Confection",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
	"morceau" => array(
		"nom" => "Morceau",
		"clee" => 1,
		"format" => "liste",
		"liste_valeurs" => "select 'total' as id, 'total' as lb from dual union select 'tete' as id, 'tete' as lb from dual union select 'corps' as id, 'corps' as lb from dual"
	),
    "impact" => array (
        "nom" => "Impact",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "number"
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";

