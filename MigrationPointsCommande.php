<?
	require_once("ress/db_mysql.php");

	$entete = 'zdataset_produitentete';
	
	$table_traduction = [
		'moto2' 	=>	'moto3',
		'moto4' 	=>	'moto5',
		'moto4slv'	=>	'moto1'
	];
	$champs_traduction = [
		'moto1'	=>	[
			'qte_telecmd_rad_1canal'	=>	' qte_telecmd_1canal,',
			'qte_inter_radio_1canal'	=>	'',
			'qte_inter_radio_5canaux'	=>	'',
			'qte_telecmd_radio_5canaux'	=>	''
		],
		'moto3'	=>	[
			'qte_telecmd_rad_1canal'	=>	' qte_telecmd_1canal_ven,',
			'qte_telecmd_radio_4canaux'	=>	' qte_telecmd_5canaux_ven,'
		],
		'moto5'	=>	[
			'qte_telecmd_rad_1canal'	=>	' qte_telecmd_1canal,',
			'qte_inter_fil_unit'		=>	' qte_inver_fil,',
			'qte_inter_radio_1canal'	=>	' qte_inver_rad_fixe_1canal,',
			'qte_telecmd_radio_4canaux'	=>	' qte_telecmd_5canaux,'
		]
	];
	
	function format_val($val){
		if($val){
			if(is_numeric($val)){
				return ' '.$val.',';
			} else {
				return ' "'.str_replace('"', '""', $val).'",';
			}
		} else {
			return ' NULL,';
		}
	}
	foreach($table_traduction as $old => $new){
		echo ($old." - ".$new."<br/>");
		$rows = [];
		$res = new db_sql('SELECT * FROM '.$entete.$old);
		while($res->n()){
			$rows[] = $res->Record;
		}
		$compteur = 0;
		$max = count($rows);
		$pourcentage = 0;
		foreach($rows as $row){
			$compteur++;
			while(round($compteur*100/$max,0)>$pourcentage){
				echo "|";
				ob_flush();
				flush();
				$pourcentage++;
			}
			$champs_trad  = '';
			$val_trad = '';
			foreach($row as $champs => $valeur){
				if(array_key_exists($champs, $champs_traduction[$new])){
					if($champs_traduction[$new][$champs]){
						$champs_trad .= $champs_traduction[$new][$champs];
						$val_trad .= format_val($valeur);
					}
				} else {
					$champs_trad .= ' '.$champs.',';
					$val_trad .= format_val($valeur);
				}
			}
			$champs_trad = rtrim($champs_trad, ',');
			$val_trad = rtrim($val_trad, ',');
			new db_sql('REPLACE INTO '.$entete.$new.' ( '.$champs_trad.' ) VALUES ( '.$val_trad.' )');
		}
		echo utf8_decode(" ".$compteur." entrées traitées<br/>");
	}
	echo utf8_decode("Migration terminée");
?>
