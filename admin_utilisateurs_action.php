<?
// error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);

include_once "ress/util.php";
include_once "ress/db_mysql.php";
include_once "ress/register_globals.php";
register_globals('gp');

$no_load = 1;
$message = "";

/*--------------- TRAITEMENT DES UTILISATEURS --------*/
If($ACTE){
	//Verification formulaire
	If( empty($Nom)){
		$message="Le nom est absent";
	}
	If( empty($Login)){
		$message="Le login est absent";
	}
	If( empty($Id_profil)){
		$message="Le profil est absent";
	}
	If( empty($Mail)){
		$message="Le mail est absent";
	}
}

if(!$message){
	if(!$Id_utilisateur){ //Ajouter un utilisateur
		//Verification du login
		if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login'","compte")>0){
			$message="Ce login existe déjà";
		}
		if(!$message){
			$requete="
				INSERT INTO
					utilisateur(
					Nom,
					Prenom,
					Login,
					Droit_connect,
					Id_responsable,
					Id_profil,
					Mail)
				VALUES (
					".My_sql_format($Nom).",
					".My_sql_format($Prenom).",
					".My_sql_format($Login).",
					'1',
					".My_sql_format($Id_responsable).",
					".My_sql_format($Id_profil).",
					".My_sql_format($Mail).")";
					// Execution
			$resultat= new db_sql("$requete");
		}
	} else { //Modifier un utilisateur
		//Verification du login
		if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login' AND
								Id_utilisateur <> '$Id_utilisateur'","compte")>0){
				$message="Ce login existe déjà";
		}
		if(!$message){
			$requete="
				 UPDATE
					utilisateur
				 SET
					Nom=".My_sql_format(utf8_decode($Nom)).",
					Prenom=".My_sql_format(utf8_decode($Prenom)).",
					Login=".My_sql_format($Login).",
					Mail=".My_sql_format($Mail).",
					Pwd=".($Pwd_ref?My_sql_format($Pwd_ref):"''")." ,
					Droit_connect=".($Droit_connect?$Droit_connect:0).",
					Id_responsable=".My_sql_format($Id_responsable).",
					Id_profil=".My_sql_format($Id_profil)."
				 WHERE
					Id_utilisateur='$Id_utilisateur'";
							// Execution
			$resultat = new db_sql("$requete");
			new db_sql('DELETE FROM gp_type_poste_utilisateur WHERE id_utilisateur='.My_sql_format($Id_utilisateur));
		}
	}
	if(!$message){
		foreach($ID_TYPE_POSTE as $id_poste){
			new db_sql("
				INSERT INTO
					gp_type_poste_utilisateur(
						id_utilisateur,
						id_type_poste,
						nb_efficacite,
						fg_principal
					)
				VALUES (
					".My_sql_format($Id_utilisateur).",
					".My_sql_format($id_poste).",
					".My_sql_format($NB_EFFICACITE[$id_poste]).",
					".($FG_PRINCIPAL[$id_poste]?1:0)."
				)
			");
		}
	}
}
echo(json_encode($message));
?>