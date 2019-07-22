<?php

$table = "param_tringle_supports";
$titre = "Param�trage R�gle - Nombre de Supports";

$champs = array (
    "nb_regle" => array (
        "nom" => "Num�ro de R�gle",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "liste",
		"liste_valeurs" => "select nb_regle as id, nb_regle as lb from param_tringle_regles group by nb_regle",
		"order_by" => 1
    ),
	"dimension_jusqua" => array( 
		"nom" => "Dimension Jusqu'�",
		"clee" => 1,
		"obligatoire" => 1,
		"format" => "number",
		"order_by" => 2
	),
    "nb_supports" => array (
        "nom" => "Nombre de Supports",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 3
    )
);

if(!$no_load)
	include "ress_parametrage_tables.php";

