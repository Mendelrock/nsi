<?php

$table = 'param_rideau_largeur_ajout_total';
$titre = "Paramétrage Ajout Total";

$champs = array (
    "id_toile" => array (
        "nom" => "ID Toile",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
    "confection" => array (
        "nom" => "Confection",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
    "ajout_total" => array (
        "nom" => "Ajout Total",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "number"
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
