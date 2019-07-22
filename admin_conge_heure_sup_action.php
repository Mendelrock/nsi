<?
include_once "ress/util.php";
include_once "ress/db_mysql.php";
include_once "ress/register_globals.php";
register_globals('gp');

$no_load = 1;
$message = "";

foreach($data as $id_user => $val){
	foreach($val as $date => $type){
		new db_sql("
			DELETE FROM
				gp_conge_heure_sup
			WHERE
				dt_date = ".my_sql_format(date('Y-m-d', strtotime($date)))." AND
				id_utilisateur = ".my_sql_format($id_user)
		);
		foreach($type as $lb => $valeur){
			if($lb=="C"){
				switch($valeur){
					case 1 : $valeur = "Congé"; break;
					case 2 : $valeur = "Maladie"; break;
					case 3 : $valeur = "Injustifié"; break;
					case 4 : $valeur = "Formation"; break;
					case 5 : $valeur = "Autre"; break;
					default : unset($valeur); break;
				}
				$champ = "lb_motif_absence";
			} else {
				$champ = "nb_heure_sup";
			}
			if(isset($valeur)){
				new db_sql("
					INSERT INTO
						gp_conge_heure_sup(
							id_utilisateur,
							dt_date,
							$champ
						)
					VALUES(
						".my_sql_format($id_user).",
						".my_sql_format($date).",
						".my_sql_format($valeur)."
					)
				");
				if ($champ == "nb_heure_sup" and $valeur) 
					$sup = '+'.$valeur; 
				else
					$sup = "*0";
				new db_sql("
					REPLACE INTO gp_capacite_personne (id_utilisateur, dt_date, nb_heure)
					SELECT
						$id_user,
						gp_capacite_global.dt_date,
						gp_capacite_global.nb_heure $sup
					FROM
						gp_capacite_global
					WHERE
						gp_capacite_global.dt_date = ".my_sql_format(date('Y-m-d', strtotime($date)))."
				");
			}
		}
	}
}

echo json_encode($_POST);
?>