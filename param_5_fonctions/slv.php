<?php

/*
-------------------------------------------------
 Retourne Coloris pour slv
-------------------------------------------------*/
function get_coloris_slv_rails($parametres) {
  return $parametres['gamme_slv'];
}

function get_typecommande_slv_of($parametres) {
  return $parametres['typecommande_slv'];
}

function get_coloris_rail_slv($parametres) {
  return $parametres['coloris_rail_slv'];
}

/*
function get_conso_slv_of($parametres) {
  return (get_hauteur_store_slv_of($parametres)+0.06)*
         get_nbcharriots_slv_of($parametres)*
         get_qte_slv_of($parametres);
}
*/



/*
-------------------------------------------------
 Retourne HAUTEUR STORE pour slv
-------------------------------------------------*/
function get_hauteur_store_slv_of($parametres) {
  return $parametres['hauteur_slv']/1000;
}

function get_hauteur_lame_seule_slv($parametres) {
  return $parametres['hauteur_lame_slv']/1000;
}

function get_hauteur_slv_pour_lame_seule_slv($parametres) {
  return $parametres['hauteur_lame_slv']/1000 + 0.032;
}

function get_qte_lames_seules($parametres) {
  return $parametres['qte']*1;
}


/*
-------------------------------------------------
 Retourne LARGEUR STORE pour slv
-------------------------------------------------*/
function get_largeur_store_slv_of($parametres) {
  return $parametres['largeur_slv']/1000;
}

/*
-------------------------------------------------
 Retourne NBE LAMES OUVERTURE LATERALE pour slv
-------------------------------------------------*/
function get_nb_lames_ouverture_laterale($parametres) {
	$parametres['largeur_slv'] = $parametres['largeur_slv']/10;
	$lames = req_sim("select nb_lames from param_nb_lames_lateral_slv where ".$parametres['largeur_slv']." >= nb_l_min and ".$parametres['largeur_slv']." <= nb_l_max",'nb_lames');
	if($lames) {
	    return $lames;
	}
	return "";
}

/*
-------------------------------------------------
 Retourne NBE LAMES OUVERTURE BILATERALE pour slv
-------------------------------------------------*/
function get_nb_lames_ouverture_bilaterale($parametres) {
	$parametres['largeur_slv'] = $parametres['largeur_slv']/10;
	$lames = req_sim("select nb_lames from param_nb_lames_bilateral_slv where ".$parametres['largeur_slv']." >= nb_l_min and ".$parametres['largeur_slv']." <= nb_l_max",'nb_lames');
	if($lames) {
	    return $lames;
	}
	return "";
}

/*
-------------------------------------------------
 Retourne Qté pour slv
-------------------------------------------------*/
function get_qte_slv_of($parametres) {
  return $parametres['qte']*1;
}

/*
-------------------------------------------------
 Retourne NOMBRE DE SUPPORTS pour slv
-------------------------------------------------*/
function get_nbsupports_slv_of($parametres) {
	$parametres['largeur_slv'] = $parametres['largeur_slv']/10;	 
	$supports = req_sim("select nb_supports from param_nb_supports_slv where ".$parametres['largeur_slv']." >= nb_l_min and ".$parametres['largeur_slv']." <= nb_l_max",'nb_supports');
	if($supports) {
	    return $supports;
	}
}

/*
-------------------------------------------------
 Retourne TYPE POSE pour slv
-------------------------------------------------*/
function get_typepose_slv_of($parametres) {
  return $parametres['typepose_slv'];
}

/*
-------------------------------------------------
 Retourne le côté de commande pour slv
-------------------------------------------------*/
function get_cotecommande_slv_of($parametres) {
	return $parametres['cotecommande_slv'];
}


/*
-------------------------------------------------
 Retourne TYPE OUVERTURE pour slv
-------------------------------------------------*/
function get_typeouv_slv_of($parametres) {
   //$type_ouv[gauche] = "opposé";
   //$type_ouv[droite] = "droite";  
   return $parametres['refoulement_lames_slv'];
}

/*
-------------------------------------------------
 Retourne NOMBRE DE CHARRIOTS pour slv
-------------------------------------------------*/
function get_nbcharriots_slv_of($parametres) {
  if($parametres['refoulement_lames_slv']!="Gauche et Droite")
   return get_nb_lames_ouverture_laterale($parametres);
  else
   return get_nb_lames_ouverture_bilaterale($parametres);
}

/*
-------------------------------------------------
 Retourne LONGUEUR RAIL pour slv
-------------------------------------------------*/
function get_longueur_rail_slv_of($parametres) {
	if (substr(get_typecommande_slv_of($parametres),0,8) == "Motorisé") { // Motorisé
		return get_largeur_store_slv_of($parametres)-0.014;
	}
		
	return get_largeur_store_slv_of($parametres)-0.01;
}

/*
-------------------------------------------------
 Retourne LONGUEUR AXE pour slv
-------------------------------------------------*/
function get_longueur_axe_slv_of($parametres) {
	if (substr(get_typecommande_slv_of($parametres),0,8) == "Motorisé") { // Motorisé
		return get_largeur_store_slv_of($parametres)-0.009;
	}
	return get_largeur_store_slv_of($parametres)-0.012;
}

/*
-------------------------------------------------
Retourne Longueur cordon pour slv
-------------------------------------------------*/
function get_longcordon_slv_of($parametres) {
	if (substr(get_typecommande_slv_of($parametres),0,8) == "Motorisé") { // Motorisé
		return '';
	}
  return (($parametres['hauteur_cde_slv']*2)+(get_longueur_rail_slv_of($parametres)*1000*2)-1.1)/1000;
}

/*
-------------------------------------------------
 Retourne Longueur chaînette pour slv
-------------------------------------------------*/
function get_longchainette_slv_of($parametres) {
	if (substr(get_typecommande_slv_of($parametres),0,8) == "Motorisé") { // Motorisé
		return '';
	}
  return $parametres['hauteur_cde_slv']*2/1000;
}


function get_long_chaine_trans($parametres) {
	if (substr(get_typecommande_slv_of($parametres),0,8) == "Motorisé") { // Motorisé
		return (get_largeur_store_slv_of($parametres)*2)+0.2;
	}
	return '';
}

/*-------------------------------------------------
 Fonctions pour les SLV trapezes
-------------------------------------------------*/

function get_slv_angle($parametres) {
	return atan(($parametres[grande_hauteur_slv]-$parametres[petite_hauteur_slv])/$parametres[largeur_slv]);
}

function get_slv_longueur_rail($parametres) {
	$angle = get_slv_angle($parametres);
	if ( ($angle/pi()*180) > 35 ) {
	   return 0.001 * (( ( (10*$parametres[largeur_slv])-4-sin($angle-35/180*pi()*17) ) / cos($angle) )
	                 - (tan($angle)*32));
   } else {
	   return  ( ( (0.01*$parametres[largeur_slv]) -0.004 ) / cos($angle) )
	            - (tan($angle)*0.032);   
   }
}

function get_slv_nbsupports($parametres) {
  if(get_slv_longueur_rail($parametres)>=0.8 && get_slv_longueur_rail($parametres)<1.5)	
   return 2;
  else if(get_slv_longueur_rail($parametres)>=1.5 && get_slv_longueur_rail($parametres)<2.5)	
   return 3;
  else if(get_slv_longueur_rail($parametres)>=2.5 && get_slv_longueur_rail($parametres)<3)	
   return 4;
  else if(get_slv_longueur_rail($parametres)>=3 && get_slv_longueur_rail($parametres)<4)	
   return 5;
  else if(get_slv_longueur_rail($parametres)>=4)	
   return 6;
}


function get_slv_longueur_coupe($parametres) {
	return get_slv_longueur_rail($parametres)-0.011;
}


function get_slv_nb_lame($parametres) {
	return floor (((10*$parametres[largeur_slv])-4-13-127)/114)+2;
}

function get_slv_ecarteur($parametres) {
	$angle = get_slv_angle($parametres);
	//echo 0.001 * ((10*$parametres[largeur_slv])-4-13-127)/(cos($angle)*(get_slv_nb_lame($parametres)-1));
	//echo "<BR>";
	return 0.001 * ((10*$parametres[largeur_slv])-4-13-127)/(cos($angle)*(get_slv_nb_lame($parametres)-1));
}

function get_slv_dimension_crochet($parametres) {
	$angle = get_slv_angle($parametres);
	$angle = $angle/pi()*180;
	if ($angle <= 22)
		return 23;
	else if ($angle <= 34) 
	   return 40;
	else if ($angle <= 48) 
		return 65;
	else if ($angle <= 55) 
		return 90;
}

function get_slv_numero_crochet($parametres) {
	$dimension = get_slv_dimension_crochet($parametres);
	if ($dimension == 23)
		return "N°0";
	else if ($dimension == 40) 
	   return "N°1";
	else if ($dimension == 65) 
		return "N°2";
	else if ($dimension == 90) 
		return "N°3";
}

function get_slv_longueurs_lames($parametres) {
	$angle = get_slv_angle($parametres);
   $nb_lames = get_slv_nb_lame($parametres);
   $ecarteur = get_slv_ecarteur($parametres)*1000; // Repassage en mm
	$longueur = 0.001 * ((10*$parametres[grande_hauteur_slv]) 
					         - (((127/2)+13+2)*tan($angle)) - (33/cos($angle)) 
					         - get_slv_dimension_crochet($parametres)
					         - 3);
	$GLOBALS[slv_conso] = 0;
   for($i=1; $i<=$nb_lames   ;$i++) {
   	$GLOBALS[slv_conso] += $longueur;
      $longueurs = $longueurs.(0.001*floor($longueur*1000))."|";
      $longueur = $longueur - ($ecarteur/1000)*sin($angle);
   }
   return substr($longueurs,0,-1);
}


function get_slv_conso($parametres) {
	return (0.001*floor($GLOBALS[slv_conso]*1000));
}


function get_slv_deviation($parametres) {
	$angle = get_slv_angle($parametres);
	return 0.001*((  ( (127/2)+2-(sin($angle)*33) ) / cos($angle)  ) + 14.7);
}

function get_slv_manoeuvre($parametres) { // Tous les calculs sont en metre directeur à l'inverse des précédents en mm
	return get_slv_longueur_rail($parametres)-0.0155-get_slv_deviation($parametres)-( (get_slv_nb_lame($parametres)-1)*get_slv_ecarteur($parametres) );
}

/*
-------------------------------------------------
 Retourne Longueur cordon pour slv
-------------------------------------------------*/
function get_slv_longcordon($parametres) {
  return ( ($parametres['hauteur_cde_slv']*2/1000)+(get_slv_longueur_rail($parametres)*2) )-0.2;
}

/*
-------------------------------------------------
 Retourne Longueur chaînette pour slv
-------------------------------------------------*/
function get_slv_longchainette($parametres) {
	return ($parametres['hauteur_cde_slv']*2/1000)-0.1;
}


function get_slv_grande_hauteur_slv($parametres) {
	return ($parametres['grande_hauteur_slv']/1000);
}

function get_slv_petite_hauteur_slv($parametres) {
	return ($parametres['petite_hauteur_slv']/1000);
}

?>