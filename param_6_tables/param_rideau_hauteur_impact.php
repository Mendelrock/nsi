<?
$titre = "Rideaux : ajout en hauteur de coupe en fonction de la confection, de la présence d'une doublure et du morceau à couper<br/>
Attention : en cas de rideau doublé on prend l'impact \"simple\" + l'impact \"doublure\"";
$champs = array ( 
	"id_toile" => array ( 
		"clee" => 1,
		"req" => "select id_toile as id, lb_toile_atelier as lb from toile"
	),
	"type" => array ( 
		"clee" => 1,
		"req" => "(select 'simple' as id, 'simple' as lb) union (select 'doublure' as id, 'doublure' as lb)"
	),
	"confection" => array ( 
		"clee" => 1,
		"req" => "(select valeur_stockee as id, valeur_affichee as lb from champ_lov_valeurs where field = 'confection_rideau') union (select 'PRE PLISSE 20%' as id, 'PRE PLISSE 20%' as lb) union (select 'Toute' as id, 'Toute' as lb)"
	),
	"morceau" => array ( 
		"clee" => 1,
		"req" => "(select 'total' as id, 'total' as lb) union (select 'tete' as id, 'tete' as lb) union (select 'corps' as id, 'corps' as lb)"
	),
	"impact" => array ( 
		"clee" => 0,
		"format" => "decimal"
	)
);
?>