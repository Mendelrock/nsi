<?php

$table = 'gp_capacite_global';
$titre = "Paramétrage Disponibilité Globale";

$champs = array (
	"dt_jour" => array (
		"nom" => "Jour",
        "clee" => 1,
        "obligatoire" => 1,
        "format" => "date"
	),
	"nb_heure" => array (
		"nom" => "Heures Travaillées",
        "clee" => 0,
        "obligatoire" => 1,
        "format" => "number"
	)
);

if(!$no_load)
	include "ress_parametrage_tables.php";
?>