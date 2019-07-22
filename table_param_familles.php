<?php

$table = 'familles';
$titre = "Paramétrage Familles";

$champs = array (
    "id_famille" => array (
        "nom" => "ID Famille",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number"
    ),
    "lb_famille" => array (
        "nom" => "LB Famille",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar",
		"orderby" => 1
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
