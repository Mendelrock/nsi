<html>
	<?php
		header( 'Content-type: text/html; charset=utf-8' );
		include("ress/db_mysql.php");
		set_time_limit(0);
		$dir_dataset = "./param_3_datasets/";
		$fichiers_scannes = scandir($dir_dataset);
		$time_start = microtime(true);
		//CREATION DES TABLES
		
		
		echo "Création des tables : <br/>";
		ob_flush();
		flush();
		
		$datasets;
		
		foreach($fichiers_scannes as $type_dataset){
			include $dir_dataset.$type_dataset;
			//echo $type_dataset."<br/>";
		}
		$req="";
		$keys = array_keys($datasets);
		$compteur = 0;
		$max = count($keys);
		$pourcentage = 0;
		foreach($keys as $key_type){
			$compteur++;
			while(round($compteur*100/$max,0)>$pourcentage){
				echo "|";
				ob_flush();
				flush();
				$pourcentage++;
			}
			$req = "
				CREATE TABLE IF NOT EXISTS
					`zdataset_".$key_type."`
					(id_dataset mediumint(9)
				";
			$champs = array_keys($datasets[$key_type][champs]);
			foreach($champs as $champ){
				$req .=", `".$champ."` mediumtext";
			}
			$req .= ",
					PRIMARY KEY (id_dataset)
					);";
			new db_sql($req);
		}
		echo " ".$compteur." tables créées<br/>";
		
		
		//MIGRATION DES DONNEES
		
		echo "Migration des données :<br/>";
		ob_flush();
		flush();
		
		//PREPARATION DES DONNEES
		
		foreach($keys as $key_type){
		if ($key_type<>"ofentete") continue;
			echo $key_type." : <br/>";
			ob_flush();
			flush();
			
			$query = "truncate table `zdataset_".$key_type."`;";
			new db_sql($query);

			
			//RECUPERATION DES ID_DATASET			
			$req = "
					SELECT DISTINCT
						id_dataset
					FROM
						dataset
					WHERE
						type_dataset = '".$key_type."';
					";
			$result_id = new db_sql($req);
			$compteur = 0;
			$max = $result_id->num_rows();
			$pourcentage = 0;
			while($result_id->n()){
				
				$compteur++;
				while(round($compteur*100/$max,0)>$pourcentage){
					echo "|";
					ob_flush();
					flush();
					$pourcentage++;
				}
				
				//RECUPERATION DES CHAMPS CORRESPONDANTS
				
				$result_fields = new db_sql("SELECT champ, valeur FROM dataset_valeur WHERE id_dataset = ".$result_id->f('id_dataset').";");
				$champs = array_keys($datasets[$key_type][champs]);
				
				//GENERATION DE LA REQUETE
				
				$champs_query = "id_dataset";
				$valeurs_query = $result_id->f('id_dataset');
				
				while($result_fields->n()){
					$champ_req = ((in_array($result_fields->f(champ),$champs))?$result_fields->f(champ):((in_array($result_fields->f(champ)."_bc",$champs))?$result_fields->f(champ)."_bc":null));
					if(isset($champ_req)){
						
						//GENERATION CHAMPS
						
						$champs_query .= ", `".$champ_req."`";
						
						//GENERATION VALEURS
						
						$valeurs_query .= ", '".preg_quote($result_fields->f(valeur),"'")."'";
					}
				}
				
				//REQUETE FINALE
				
				$query = "
					INSERT IGNORE INTO 
						`zdataset_".$key_type."`
						(".$champs_query.")
					VALUES
						(".$valeurs_query.")
					;";
				$query = preg_replace('/\s+/', ' ', $query);
				
				//LANCEMENT DE LA REQUETE
		
				new db_sql($query);
			}
			echo(" ".$compteur." requêtes traitées<br/>");
			ob_flush();
			flush();
		}
		$time_end = microtime(true);
		$time = $time_end - $time_start;
		$H = floor($time / 3600);
		$i = ($time / 60) % 60;
		$s = $time % 60;
		echo "Migration terminée en ".sprintf("%02d:%02d:%02d", $H, $i, $s);
	?>
</html>
