<?php

$table = 'incompatibilites_surface';
$titre = "Paramétrage Surface";

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
    "min" => array (
        "nom" => "Min",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "number"
    ),
	"max" => array (
        "nom" => "Max",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "number"
    )
);
if(!$no_load)
	include "ress_parametrage_tables.php";
