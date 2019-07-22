<?php

$table = 'param_rideau_largeur_ajout_doublure';
$titre = "Paramétrage Ajout Doublure";

$champs = array (
    "id_toile" => array (
        "nom" => "ID Toile",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
    "ajout_doublure" => array (
        "nom" => "Ajout Doublure",
        "clee" => 0,
        "obligatoire" => 0,
        "format" => "number"
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
