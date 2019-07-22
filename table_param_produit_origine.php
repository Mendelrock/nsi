<?php

$table = 'produit_origine';
$titre = "Paramétrage Produits/Origine";

$champs = array (
    "produit" => array (
        "nom" => "Produit",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "varchar"
    ),
	"id_fournisseur" => array (
		"nom" => "Fournisseur", 
		"clee" => 0,
		"obligatoire" => 1,
		"format" => "liste",
		"liste_valeurs" => "select id_fournisseur as id , lb_fournisseur as lb from fournisseur",
		"nullval" => 0
	)
);
if(!$no_load)
	include "ress_parametrage_tables.php";