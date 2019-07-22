<?php

$table = 'param_nb_supports_slv';
$titre = "Paramétrage Supports - SLV";

$champs = array (
    "nb_l_min" => array (
        "nom" => "Largeur MIN",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
    ),
    "nb_l_max" => array (
        "nom" => "Largeur MAX",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number"
    ),
    "nb_supports" => array (
        "nom" => "Nb Supports",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
