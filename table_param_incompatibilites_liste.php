<?php

$table = 'incompatibilites_liste';
$titre = "Paramétrage Liste";

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
    "val2" => array (
        "nom" => "Valeur 2",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar"
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
