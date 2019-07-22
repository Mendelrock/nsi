<?php

$table = 'consommation';
$titre = "Paramétrage Consommation";

$champs = array (
    "of" => array (
        "nom" => "OF",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "varchar",
		"order_by" => 1
    ),
	"article" => array( 
		"nom" => "Article",
		"clee" => 1,
		"obligatoire" => 1,
        "format" => "liste",
		"liste_valeurs" => "select id_article as id, lb_article_aff as lb from article where selecteur = 'support' group by lb_article",
		"order_by" => 2
	),
	"cond" => array (
		"nom" => "Condition",
		"clee" => 1,
		"format" => varchar,
		"obligatoire" => 1,
		"order_by" => 3
	),
	"quantite" => array (
		"nom" => "Quantite",
		"clee" => 0,
		"format" => varchar,
		"obligatoire" => 1,
		"order_by" => 4
	),
	"consommation" => array (
		"nom" => "Consommation",
		"clee" => 0,
		"format" => number
	),
	"affichage" => array (
		"nom" => "Affichage",
		"clee" => 0,
		"format" => number
	)
);

if(!$no_load)
	include "ress_parametrage_tables.php";

