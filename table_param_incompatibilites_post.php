<?php

$table = 'incompatibilites_post';
$titre = "Param�trage Val/Toile";

$champs = array (
    "id" => array (
        "nom" => "ID",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
    "produit" => array (
        "nom" => "Produit",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "varchar"
    ),
    "prop1" => array (
        "nom" => "Propriete 1",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "varchar"
    ),
    "val1" => array (
        "nom" => "Valeur 1",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "varchar"
    ),
    "prop2" => array (
        "nom" => "Propriete 2",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "varchar"
    ),
    "val2" => array (
        "nom" => "Valeur 2",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "varchar"
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
