<?
$titre = "Rideaux : ampleur en fonction de la confection";
$champs = array ( 
	"id_toile" => array ( 
		"clee" => 1,
		"req" => "select id_toile as id, lb_toile_atelier as lb from toile"
	),
	"confection" => array ( 
		"clee" => 1,
		"req" => "(select valeur_stockee as id, valeur_affichee as lb from champ_lov_valeurs where field = 'confection_rideau') union (select 'PRE PLISSE 20%' as id, 'PRE PLISSE 20%' as lb)"
	),
 	"origine" => array (
		"clee" => 0,
		"format" => "decimal"
	),	
	"ampleur" => array ( 
		"clee" => 0,
		"format" => "decimal"
	)

);
?>