<?
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);
	include('ress/entete.php');
	$req = "INSERT INTO gp_capacite_global (dt_jour, nb_heure) VALUES ";
	for($date = '2019-01-01'; strtotime($date)<=strtotime('2019-12-31'); $date = decale_date($date, 1)){
		if(date("w", strtotime($date))>0 && date("w", strtotime($date))<6){
			$req .= "('".$date."', 7),";
		}
	}
	$req = rtrim($req,",");
	echo $req;
	new db_sql($req);
	echo "FINI!";
	include('ress/enpied.php');
?>