<?php

$table = 'param_nb_lames_bilateral_slv';
$titre = "Paramétrage Lames Bilatéral - SLV";

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
    "nb_lames" => array (
        "nom" => "Nb Lames",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";
