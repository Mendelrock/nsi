<?

set_time_limit(0);
ini_set("memory_limit","-1");

function contre($sens) {
  if ($sens == "largeur") {
   return ("hauteur");
  } else {
   return ("largeur");
  }
}


function trace($x) {
	if ($_GET['debug']) {
		echo "<pre>";
		echo print_r($x);
		echo "</pre>";
		flush();
	}
}

function essaye_de_remplir($config, $niveau, $largeur, $hauteur, $situation, $senss, $alinea_trace="") {
	$alinea_trace .= "&nbsp;&nbsp;&nbsp;&nbsp;|";
	trace($alinea_trace."On me demande de remplir le rectangle de largeur $largeur et de hauteur $hauteur avec ".$situation[nb_acouper]." morceaux restants à couper");
	if (!$hauteur) {
		trace($alinea_trace."mais renvoie self pour cause de hauteur nulle");
		return array(0=>$situation);
	}
	if (!$largeur ) {
		trace($alinea_trace."mais renvoie self pour cause de largeur nulle");
		return array(0=>$situation);
	}
	if (!$situation[nb_acouper]) {
		trace($alinea_trace."mais renvoie vide pour cause de rien à couper");
		return array();
	}
	// Trouve le 1er morceau qui rentre dans le rectangle
	// si largeur = -1000, c'est une nouvelle coupe
	$combinaisons = array();
	$morceaux_considérés = array(); 
	foreach ($situation[acouper] as $nb_morceau => $morceau) {
		if (!$morceau[coupe] and !$morceaux_considérés[$morceau[hauteur]][$morceau[largeur]]) {
			$morceaux_considérés[$morceau[hauteur]][$morceau[largeur]] = 1;
			if (((($morceau[largeur]*1) <= ($largeur*1)) or ($largeur == -1000) ) and ($morceau[hauteur]*1 <= $hauteur*1) and ($senss[largeur])) {
				$combinaisons[] = array("nb_morceau" => $nb_morceau , "sens" => 'largeur');
				//trace($alinea_trace."morceau N° ".$morceau[n]." (l ".$morceau[largeur]." * h ".$morceau[hauteur].") dans le sens largeur");
			}
			if (((($morceau[hauteur]*1) <= ($largeur*1)) or ($largeur == -1000)) and ($morceau[largeur]*1 <= $hauteur*1) and ($senss[hauteur])) {
				$combinaisons[] = array("nb_morceau" => $nb_morceau , "sens" => 'hauteur');
				//trace($alinea_trace."morceau N° ".$morceau[n]." (l ".$morceau[largeur]." * h ".$morceau[hauteur].") dans le sens hauteur");
			}
			if (count($combinaisons) >= $GLOBALS[nb_combinaisons_max]) {
				break;
			}
		}
	}
	// $combinaisons contient les couples ($nb_morceau , $sens_possibles) possibles	
	// Si pas de morceau trouvé
	trace($alinea_trace."Je trouve ".count($combinaisons)." combinaisons (limite ".($GLOBALS[nb_combinaisons_max]).") : ");
	if (count($combinaisons) == 0) {
		trace($alinea_trace."renvoie self pour cause de pas de combinaison");
		return(array(0=>$situation));
	} else {
		// Sinon j'essaye de remplir les autres en prenant les hypothèses de sens
		$situation_resutat=array();
		foreach ($combinaisons as $combinaison) {
			//On initialise une nouvelle variante de la situation.
			$situationbis = $situation;
			$nb_morceau = $combinaison[nb_morceau];
			$sens = $combinaison[sens];
			$morceau = $situationbis[acouper][$nb_morceau];
         $situationbis[acouper][$nb_morceau][retrouve_le_dataset][sens] = $sens; //Pour retenir dans quel sens on coupe
         $situationbis[acouper][$nb_morceau][retrouve_le_dataset][coupe] = $situationbis[coupe_courante]; //Pour retenir le numéro de coupe			
			trace($alinea_trace."Je trouve le morceau N° ".$morceau[n]." (l ".$morceau[largeur]." * h ".$morceau[hauteur].") dans le sens $sens");
			$alinea_trace_b = $alinea_trace."&nbsp;&nbsp;.";

 			// Calcul de la largeur du rectangle
			// Si largeur = -1000, c'est une nouvelle coupe
			if ($largeur == -1000) {
				// Largeur courante = Largeur de la tentative (dans le sens courant)
				$largeur_courante = $morceau[$sens];
				// Initialisation des données de la coupe courante
				$situationbis[coupe][$situationbis[coupe_courante]][largeur] = $morceau[$sens];
				// dont la largeur consommée cumulée
				$situationbis[largeur_totale] += $largeur_courante;
			} else {
				// Largeur courante = Largeur de la tentative (dans le sens courant)
				$largeur_courante = $largeur;
			}
			
			// Le morceau n'est plus à couper
			$situationbis[acouper][$nb_morceau][coupe] = true;
			$situationbis[nb_acouper]--;
			trace ($alinea_trace_b."Largeur du rectangle : ".$largeur_courante.", reste ".$situationbis[nb_acouper]." à couper");

			// Je cumule la surface active
			$situationbis[surface_active] += $morceau[hauteur] * $morceau[largeur];
			// Petite redondance
			$situationbis[coupe][$situationbis[coupe_courante]][contenu][] = array("sens"=>$sens, "morceau"=>$morceau);
			
			//calcule de la signature
			$signature = "";
			$config = array();
			$tabdir = array();
			foreach ($situationbis[acouper] as $tnb_morceau => $tmorceau) {
				if ($tmorceau[coupe]) {
					$tabdir[$tmorceau[largeur]."|".$tmorceau[hauteur]."|".$tmorceau[retrouve_le_dataset][sens]] = 1;
					$config[$tmorceau[largeur]*$tmorceau[hauteur]][$tmorceau[largeur]][$tmorceau[hauteur]] += 1;
				}
			}
			ksort($config);
			foreach($config as $surf=>$tailles) {
			   ksort($tailles);
			   foreach($tailles as $thauteur=>$tlargeurs) {
			   	ksort($tlargeurs);
			   	foreach($tlargeurs as $tlargeur=>$n) {
			   		$signature .= $surf.'/'.$thauteur.'/'.$tlargeur.'/'.$n.'|';
			   	}
			   }
			}
			$situationbis[signature] = $signature;
			$situationbis[nb_direction] = count($tabdir);
			
			if ($situationbis[nb_acouper] == 0) {
				$situation_resutat[] = $situationbis;
			} else {
				trace ($alinea_trace_b."J'essaye de toutes les manières de remplir le reste ( l $largeur_courante * h $hauteur  ) :");
				
				trace($alinea_trace_b."d'une manière...");
				//trace ($morceau);
				$liste = essaye_de_remplir($config, $niveau+1, $largeur_courante-$morceau[$sens],$hauteur,$situationbis,$senss,$alinea_trace_b);
				foreach ($liste as $situationter) {
					if ($situationter[nb_acouper] != 0) {
						$situationquar = essaye_de_remplir($config, $niveau+1, $morceau[$sens],$hauteur-$morceau[contre($sens)],$situationter,$senss,$alinea_trace_b);
						$situation_resutat = array_merge($situationquar,$situation_resutat);
					}
				}	
				if ($largeur_courante-$morceau[$sens] and $hauteur-$morceau[contre($sens)]) {
					trace($alinea_trace_b."d'une autre manière...");
					$liste = essaye_de_remplir($config, $niveau+1, $largeur_courante,$hauteur-$morceau[contre($sens)],$situationbis,$senss,$alinea_trace_b);
					foreach ($liste as $situationter) {
						if ($situationter[nb_acouper] != 0) {
							$situationquar = essaye_de_remplir($config, $niveau+1, $largeur_courante-$morceau[$sens],$morceau[contre($sens)],$situationter,$senss,$alinea_trace_b);
					  		$situation_resutat = array_merge($situationquar,$situation_resutat);
						}
					}
				} else {
					trace($alinea_trace_b."pas le peine de l'autre manière...");
				}
			}
		}

		//Si je suis au niveau 0 et qu'il reste des morceaux, je dois dérouler à nouveau...
		if ($niveau==1) {
			trace($alinea_trace."Je suis au niveau 0 => je compare ".count($situation_resutat)." situations");
			foreach ($situation_resutat as $i => $situation_courante) {
				$valuation = $situation_courante[largeur_totale]*1000+$situation_courante[nb_direction];
				if ($GLOBALS[config_deja_essayé][$situation_courante[signature]] and ($GLOBALS[config_deja_essayé][$situation_courante[signature]] <= $valuation)) {
					trace($alinea_trace."J'en zappe une car déjà vu mieux ( $valuation )");
				} else {
					$GLOBALS[config_deja_essayé][$situation_courante[signature]] = $valuation;
					if ($situation_courante[nb_acouper] == 0) {
	     				$GLOBALS[config_a_prendre] = $situation_courante;
						trace($alinea_trace."Une meilleure ( $valuation )");
					} else {
						trace($alinea_trace."Une que je poursuis");
						$situation_courante[coupe_courante]++;
						$combs = essaye_de_remplir($config, 1,-1000,$hauteur,$situation_courante,$senss,$alinea_trace_b);
					}
				}
			}
			return(array());
		} else {
			trace($alinea_trace."Je ne suis pas au niveau 0, je renvoie ".count($situation_resutat)." situations");
			return ($situation_resutat);
		}
	}
}

function decoupe($morceau, $sens_des_soudures, $taille_max, &$situation, &$acouper, &$laizebicolore) {
	
	if ($morceau['pere_bicolore']) {
		$laizebicolore[$taille_max/10][$morceau['pere_bicolore']] = $taille_max;
	}
	$sens_des_soudures = contre($sens_des_soudures);
	$asouder = array();
	
	/*$largeur_soudure = 2;
	$taille_mini_bande = 10;
	$taille_min_dernier_morceau = 30;*/
	
	$largeur_soudure = 20;
	$taille_mini_bande = 100;
	$taille_min_dernier_morceau = 300;
		
	for ($taille = 0 ; $morceau[$sens_des_soudures]-$taille > $taille_max ; "") {
		
		//$morceau[$sens_des_soudures] += $largeur_soudure;
		
		if ($morceau['remarque_conso_raccordable']) {
			$GLOBALS['remarque_conso_raccordable'] = true;
		}
		
		// On ne veut pas de bande de moins de 10 cm		
		if (($morceau[$sens_des_soudures] - $taille_mini_bande) <= $taille_max + $taille) {
			if (($_GET[forcer]==1) or ($GLOBALS[origine]==3)) {
			} else if ($_GET[forcer]==2) {
				$morceau[$sens_des_soudures] = $taille_max + $taille;
				$lo_stop = true;
			} else {
				$GLOBALS['bande_5'] += 1;
			}
		}
		
		if (!$lo_stop) {
			$morceau[$sens_des_soudures] += $largeur_soudure;
			$GLOBALS['ssoudure'] += 1;
			
			//On veut qu'il reste au minimum 30 cm sur le dernier morceau
			if (($morceau[$sens_des_soudures]-$taille) < ($taille_max+$taille_min_dernier_morceau)) {
				$taille_du_morceau_courant = $morceau[$sens_des_soudures]-$taille-$taille_min_dernier_morceau;
				//$taille_du_morceau_courant = $taille_max;
			} else {
				$taille_du_morceau_courant = $taille_max;
			}
			
			$new_morceau = $morceau;
			$new_morceau[$sens_des_soudures] = $taille_du_morceau_courant;
			$acouper[] = $new_morceau;
			$asouder[] = count($acouper) - 1;
			$situation[nb_soudures]++;
			$taille = $taille+$taille_du_morceau_courant;
		}
		$lo_stop = false;
	}
	$new_morceau = $morceau;
	$new_morceau[$sens_des_soudures] = $morceau[$sens_des_soudures]-$taille;
	$acouper[] = $new_morceau;
	$asouder[] = count($acouper) - 1;
	$situation['asouder'][] = $asouder;
	$situation[nb_soudures]++;
	$situation[nb_pieces_soudees]++;

  // Découpe en morceaux de même taille...

  //$sens_des_soudures = contre($sens_des_soudures);
  //$nb_morceaux = ceil( $morceau[$sens_des_soudures] / $laize );
  //$new_morceau = $morceau;        
  //$new_morceau[$sens_des_soudures] = $new_morceau[$sens_des_soudures]/$nb_morceaux;
  //$asouder = array();             
  //for ($i=0 ; $i < $nb_morceaux ; $i++) {
  // $acouper[] = $new_morceau;     
  // $asouder[] = count($acouper) - 1;
  //}                               
  //$situation['asouder'][] = $asouder;
  //$situation[nb_soudures] = $situation[nb_soudures] + $nb_morceaux-1;
  //$situation[nb_pieces_soudees]++;
}
/*
-------------------------------------------------
 Calcule les besoins matière
-------------------------------------------------*/
function calcul_besoin_matiere($acouper, $orientation, $sens_des_soudures, $laizes, &$laizebicolore) {
	$laizebicolore = array();
	$situation = array();
	$situation[acouper] = $acouper;
	
	//Déclinaison du tableau des sens
	//*******************************
	
	$sens = array();
	switch($orientation) {
	case "neutre" :
	$senss[hauteur] = "hauteur";
	$senss[largeur] = "largeur";
	break;
	case "laize" :
	$senss[largeur] = "largeur";
	break;
	case "oppose" :
	$senss[hauteur] = "hauteur";
	break;
	}  
	
	//Multiplication des morceaux
	//***************************
	$acouper_multiplie = array();
	foreach ($situation['acouper'] as $i => $morceau) {
		for ($j = 1 ; $j <= $morceau[nombre] ; $j++) {
		  	$acouper_multiplie[] = $morceau; 
		}
	}
	$situation['acouper'] = $acouper_multiplie;
	
	$situationb = $situation;
	$parm_result = array();
	
	//Itération sur les laizes
	//************************
	$nb_laize_avec_bande_5 = 0;
	$nb_laize_sans_soudure = 0;
	foreach ($laizes as $i => $laize) {
		$GLOBALS[bande_5] = 0;
		$GLOBALS[ssoudure] = 0;
		$tableau_res = array();
		$tableau_res["laize"]=$laize;  
		
		//Découpe les soudures / Coutures
		//*******************************
		
		$erreur = false;
		$acouper_morcele = array();
		$situation[nb_soudures] = 0;
		$situation[nb_pieces_soudees] = 0;
		$situation = $situationb;
		foreach ($situation['acouper'] as $i => $morceau) {
			$coupe_de_force = false;
			if ($morceau[fils_bicolore]) {
				if ($GLOBALS[laizebicolore][$morceau[fils_bicolore]]) {
					if ($GLOBALS[laizebicolore][$morceau[fils_bicolore]] > $laize*10) {
						$erreur = true;
					} else {
						$taille_max = $GLOBALS[laizebicolore][$morceau[fils_bicolore]];
						$coupe_de_force = true;
					}
				} else {
					$taille_max = $laize*10;
				}
			} else {
				$taille_max = $laize*10;
			}
			if ($orientation == "laize") {
		   	if ($morceau[hauteur] > $taille_max) {
					if ($sens_des_soudures == "hauteur") {
		      		$erreur = true;
		     		} else {
		      		decoupe($morceau, $sens_des_soudures, $taille_max, $situation, $acouper_morcele, $laizebicolore);
		     		}
		   	} else {
		   		$acouper_morcele[] = $morceau;
		   	}
		   }
			if ($orientation == "oppose") {
				if ($morceau[largeur] > $taille_max) {
	     			if ($sens_des_soudures == "largeur") {
	      			$erreur = true;
	     			} else {
	      			decoupe($morceau, $sens_des_soudures, $taille_max, $situation, $acouper_morcele, $laizebicolore);
	     			}
	   		} else {
	     			$acouper_morcele[] = $morceau;
	   		}
	   	}     
			if ($orientation == "neutre") {
				if ( (($morceau[largeur] <= $taille_max) or ($morceau[hauteur] <= $taille_max)) and !$coupe_de_force ) {
					$acouper_morcele[] = $morceau;
				} else {
					decoupe($morceau, $sens_des_soudures, $taille_max, $situation, $acouper_morcele, $laizebicolore);
				}
			}
		}
		$situation['acouper'] = $acouper_morcele;
		if (!$erreur) {
		  
		  //Classement des morceaux par ordre de taille croissant
		  //*****************************************************
		  
		  $acouper_trie = array();
		  $acouper = $situation['acouper'];
		  while (count($acouper_trie)<count($acouper)) {
		   $plus_grande_taille = 0;
		   foreach ($acouper as $i => $morceau) {
		     if (!$morceau[trie]) {
		     	$taille = max ( ($senss[hauteur] ? $morceau[hauteur] : 0),($senss[largeur] ? $morceau[largeur] : 0) );
		     	// remplace $taille = ($morceau[hauteur]*$morceau[largeur]);
		      if ($taille > $plus_grande_taille) {
		        $plus_grande_taille = $taille;
		        $j = $i;
		      }
		     }
		   }
		   $acouper[$j][n] = $j;
		   $acouper_trie[] = $acouper[$j];
		   $acouper[$j][trie] = true;        
		  }
		  $situation['acouper'] = $acouper_trie;
		  
		  if ($erreur) {
		   //echo $erreur;
		  }
		  //Initialisation de la récurrence
		  //********************************
		  $situation[nb_acouper] = count($situation[acouper]);
		  $situation[coupe_courante] = 0;
		  $situation[surface_active] = 0;
		  $situation[largeur_totale] = 0;
		  
		  $GLOBALS[config_deja_essayé] = array();
		  $GLOBALS[config_a_prendre] = "";
		  $GLOBALS[nb_combinaisons_max] = floor(20/$situation[nb_acouper]) + 1;
		  //$GLOBALS[nb_combinaisons_max] = floor(2/$situation[nb_acouper]) + 1;
		  essaye_de_remplir("", 1,-1000,$laize*10,$situation,$senss,"");
		  $situation = $GLOBALS[config_a_prendre];
		  trace($situation);
		  // Calcul des résultats
		  //*********************
		  $situation[nb_acouper] = count($situation[acouper]);
		  $situation[resultat]["Tx de pieces à souder/coudre"] = floor($situation[nb_pieces_soudees]/$situation[nb_acouper]*100)." %";
		  $situation[resultat]["Tx de soudure/couture"] = floor($situation[nb_soudures]/$situation[nb_acouper]*100)." %";
		  $situation[resultat]["nb_soudures"] = $situation["nb_soudures"];
		  $situation[resultat]["Largeur totale coupée"] = $situation[largeur_totale];
		  $situation[resultat]["Surface utilisée"] = $situation[largeur_totale]*$laize*10;
		  $situation[resultat]["Surface utile"] = $situation[surface_active];
		  $situation[resultat]["Chute"] = $situation[resultat]["Surface utilisée"] - $situation[resultat]["Surface utile"];
		  $situation[resultat]["Tx de chute"] = floor($situation[resultat]["Chute"] / $situation[resultat]["Surface utilisée"] * 100)." %";
		  $situation[resultat][acouper] = $situation[acouper];
		  $situation[resultat][coupe] = $situation[coupe];
		  		  
		  $directive ="<BR>&nbsp;&nbsp;Coupes <BR>";
		  $sit = $situation['coupe'];
		  foreach ($sit as $i => $coupe) {
		   //echo $i;
		   $directive .="&nbsp;&nbsp;&nbsp;&nbsp;Dérouler ".$coupe[largeur]." mm";
		   $directive .="<BR>";
		   foreach ($coupe["contenu"] as $i => $morc) {
		     $directive .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Morceau N° ";
		     $directive .=$morc["morceau"]["n"];
		     $directive .=" (ligne ";
		     $directive .=$morc["morceau"]["repere"];
		     $directive .=") L (".$morc["morceau"][largeur];
		     $directive .=") x";
		     $directive .=" H (".$morc["morceau"][hauteur];
		     $directive .=") dans la ".$morc[sens];
		     $directive .="<BR>";
			}
		  }
		  $sit = $situation['asouder'];
		  if (is_array($sit)) {
		   $directive .="&nbsp;&nbsp;Assemblages  <BR>";
		   foreach ($sit as $i => $coupe) {
		     $directive .="&nbsp;&nbsp;&nbsp;&nbsp;Assembler les pieces ";
		     foreach ($coupe as $x => $y) {
		      $directive .="&nbsp;$y";
		     }
		     $directive .="<BR>";
		   }
		  }  
		  //$directive .= "&nbsp;&nbsp;Conclusions : <BR>";
		  $sit = $situation['resultat'];
		  foreach ($sit as $i => $j) {
			   //$directive .= "&nbsp;&nbsp;&nbsp;&nbsp; $i : $j ";
			   //$directive .= "<BR>";
			   $j = str_replace ("%","",$j);
			   //$parm_result.='"'.$i.'"=>'.$j.','; 
			   $tableau_res[$i]=$j;  
		  }
		  //$parm_result = substr($parm_result,0,strlen($parm_result)-1);
		  //$parm_result.=')';
		  //$parm_result.=',"Directive"=>"'.$directive.'")';
		  $tableau_res["Directive"]=$directive; 
		  $parm_result[] =$tableau_res;
		  if ($GLOBALS[bande_5] > 0) $nb_laize_avec_bande_5 += 1;
		  if ($GLOBALS[ssoudure]==0) $nb_laize_sans_soudure += 1;
		}
	}
	if (!$nb_laize_sans_soudure and $nb_laize_avec_bande_5) {
		$GLOBALS[pb_bande_5] = 1;
	}
	return $parm_result;
}
?>