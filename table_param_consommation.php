<?php

$table = 'consommation';
$titre = "Paramétrage Consommation";

set_time_limit(0);

$champs = array (
	"id" => array (
        "nom" => "Identifiant",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "number",
		"order_by" => 0
    ),
    "of" => array (
        "nom" => "OF",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "varchar",
		"order_by" => 1
    ),
	"article" => array( 
		"nom" => "Article",
		"clee" => 0,
		"obligatoire" => 1,
        "format" => "liste",
		"liste_valeurs" => "select id_article as id, lb_article_aff as lb from article order by  lb_article",
		"order_by" => 2
	),
	"cond" => array (
		"nom" => "Condition",
		"clee" => 0,
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

