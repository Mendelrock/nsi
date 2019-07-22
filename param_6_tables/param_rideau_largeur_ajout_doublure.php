<?
$titre = "Rideaux : ajout en largeur de coupe en cas de doublure";
$champs = array ( 
	"id_toile" => array ( 
		"clee" => 1,
		"req" => "select id_toile as id, lb_toile_atelier as lb from toile"
	),
	"ajout_doublure" => array ( 
		"clee" => 0,
		"format" => "decimal"
	)
);
?>