<?php

$table = 'incompatibilites_rapport';
$titre = "Paramétrage Rapport";

$champs = array (
    "Id_incompatibilite" => array (
        "nom" => "ID Incompatibilité",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
    "produit" => array (
        "nom" => "Produit",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
    "prop1" => array (
        "nom" => "Propriete 1",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
    "val1" => array (
        "nom" => "Valeur 1",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
    "prop2" => array (
        "nom" => "Propriete 2",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
    "prop3" => array (
        "nom" => "Propriete 3",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
	"x" => array (
        "nom" => "X",
        "clee" => 0,
        "format" => "number",
        "obligatoire" => 0
    ),
	"y" => array (
        "nom" => "Y",
        "clee" => 0,
        "format" => "number",
        "obligatoire" => 0
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
