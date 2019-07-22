<?php

$table = 'param_tringle_regles';
$titre = "Paramétrage Type Tringle - Règle";

$champs = array (
    "nb_regle" => array (
        "nom" => "Numéro de Règle",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 1
    ),
	"type_tringle" => array( 
		"nom" => "Type Tringle",
		"clee" => 1,
		"obligatoire" => 1,
        "format" => "liste",
		"liste_valeurs" => "select id_article as id, lb_article_aff as lb from article where selecteur = 'tringle' group by lb_article",
		"order_by" => 2
	)
);

if(!$no_load)
	include "ress_parametrage_tables.php";

