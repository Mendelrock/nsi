<?php

/*
-------------------------------------------------
 Retourne LARGEUR RIDEAU FINI (en m)
-------------------------------------------------*/
function get_largeur_rideau($parametres) {
  return $parametres['larg_rideau_mono']/1000;
}

/*
-------------------------------------------------
 Retourne HAUTEUR RIDEAU FINI (en m)
-------------------------------------------------*/
function get_hauteur_rideau($parametres) {
  return $parametres['long_rideau_mono']/1000;
}

/*
-------------------------------------------------
 Retourne LARGEUR COUPE (en m)
-------------------------------------------------*/
function get_largeur_doublure_rideau_of($parametres) {
	if($parametres['doublure_rideau']!="|") {
	return get_largeur_rideau_of($parametres);}
	else return "-";
}

/*
-------------------------------------------------
 Retourne Nombre de Crochets (confection Ruflette)
-------------------------------------------------*/
function get_nombre_crochets($parametres) {
  if(startsWith($parametres['confection_rideau'],"PRE PLISSE") || startsWith($parametres['confection_rideau'],"TETE FLAMANDE")) 
   return floor(get_largeur_rideau($parametres)/0.1)+3;
  else return "";	
}

/*
-------------------------------------------------
 Retourne HAUTEUR COUPE (en m)
-------------------------------------------------*/
function get_hauteur_doublure_rideau_of($parametres) {
  if($parametres['doublure_rideau'] != "|") return get_hauteur_rideau($parametres)+0.07; //Steph le 24/02/2017 pour toutes les doublures
  //if($parametres['doublure_rideau'] == "168") return get_hauteur_rideau($parametres)+0.05; //Steph le 12/12/2013 pour les doublures simples
  //if($parametres['doublure_rideau'] != "|") return get_hauteur_rideau($parametres)+0.02;
  else return "-";	
}

/*
-------------------------------------------------
 Retourne COLORIS BICOLORE
-------------------------------------------------*/
function get_coloris_rideau_bico($parametres) {
  return $parametres['toile_rideau_bico_tete'].";".$parametres['toile_rideau_bico_corps'];
}

/*
-------------------------------------------------
 Retourne COLORIS TETE 
-------------------------------------------------*/
function get_toile_rideau_bico_tete($parametres) {
  return $parametres['toile_rideau_bico_tete'];
}

/*
-------------------------------------------------
 Retourne COLORIS CORPS 
-------------------------------------------------*/
function get_toile_rideau_bico_corps($parametres) {
  return $parametres['toile_rideau_bico_corps'];
}

function get_largeur_rideau_brute($parametres) {
	$toile = get_coloris_rideau_mono($parametres)+get_toile_rideau_bico_corps($parametres);
	$ampleur = 0;
	$confection = get_confection_rideau_of($parametres);	
	$ampleur = req_sim("
		select 
			ampleur 
		from 
			param_rideau_largeur_ampleur
		where
			((id_toile = ".$toile.") or (id_toile = 0)) and
			((origine = ".$GLOBALS[origine].") or (origine = 0)) and
			confection = '".get_confection_rideau_of($parametres)."' 
	","ampleur");
	 
	return (1+ $ampleur ) * get_largeur_rideau($parametres);
}

/*-------------------------------------------------
 Retourne LARGEUR COUPE TETE (en m)
-------------------------------------------------*/
function get_largeur_rideau_of($parametres) {
	$toile = get_coloris_rideau_mono($parametres)+get_toile_rideau_bico_corps($parametres);
	$confection = get_confection_rideau_of($parametres);	
	$ajout_total = 0;
  	$ajout_total = req_sim("
		select 
			sum(ajout_total) as ajout_total 
		from 
			param_rideau_largeur_ajout_total
		where
			((id_toile = ".$toile.") or (id_toile = 0)) and
			confection = '".get_confection_rideau_of($parametres)."' 
	","ajout_total");
  
  	$ajout_doublure = 0; 
	if ($parametres['doublure_rideau']!="|" and $parametres['doublure_rideau']!="") {
		$ajout_doublure = req_sim("
		select 
			sum(ajout_doublure) as ajout_doublure 
		from 
			param_rideau_largeur_ajout_doublure
		where
			((id_toile = ".$toile.") or (id_toile = 0)) 
	","ajout_doublure");
	}  
	return get_largeur_rideau_brute($parametres) + $ajout_total + $ajout_doublure;
	//return (1+ $ampleur ) * get_largeur_rideau($parametres) + $ajout_total/10 + $ajout_doublure/10;
}


/*-------------------------------------------------
 Retourne Ajout HAUTEUR anyway
-------------------------------------------------*/
function get_ajout_hauteur($parametres,$partie) {
	$ajout = get_ajout_hauteur_primaire("simple",$parametres,$partie); 
	if ($parametres['doublure_rideau']!="|" and $parametres['doublure_rideau']) {
		$ajout += get_ajout_hauteur_primaire("doublure",$parametres,$partie);
	}
	return ($ajout);
}

function get_ajout_hauteur_primaire($type,$parametres,$partie) {
	$toile = get_coloris_rideau_mono($parametres)+get_toile_rideau_bico_corps($parametres);
	return req_sim("
		select 
			sum(impact) as impact 
		from 
			param_rideau_hauteur_impact
		where
			((id_toile = ".$toile.") or (id_toile = 0)) and
			type = '".$type."' and
			(confection = '".get_confection_rideau_of($parametres)."' or 
			confection = 'Toute') and
			morceau = '".$partie."' 
	","impact");
}

/*-------------------------------------------------
 Retourne HAUTEUR COUPE MONOCOLORE (en m)
-------------------------------------------------*/
function get_hauteur_rideau_of($parametres) {
	return (get_hauteur_rideau($parametres)+
			  get_ajout_hauteur($parametres,'total')/100);
}	 

/*-------------------------------------------------
 Retourne HAUTEUR COUPE TETE (en m)
-------------------------------------------------*/
function get_hauteur_tete_rideau_of_bi($parametres) {
	return (get_ajout_hauteur($parametres,'tete')/100+
			  0.4);
}

/*-------------------------------------------------
 Retourne HAUTEUR COUPE BICOLORE (en m)
-------------------------------------------------*/
function get_hauteur_corps_rideau_of_bi($parametres) {
	return (get_hauteur_rideau($parametres)+
			  get_ajout_hauteur($parametres,'corps')/100
			  -0.4);
}


/* -------------------------------------------------
 Retourne COLORIS
-------------------------------------------------*/
function get_coloris_rideau_mono($parametres) {
  return $parametres['toile_rideau_mono'];
}

/* -------------------------------------------------
 Nombre d'Oeuillets et distance entre les oeuillet
------------------------------------------------- */
function get_nombre_oeillet($parametres) {
	if (startsWith($parametres['confection_rideau'],"Oeillets")) {
		$largeur_rideau = get_largeur_rideau_brute($parametres)*100;
		$nb = round($largeur_rideau/20);
		if (($nb/2) != floor($nb/2)) $nb++;
		if ($nb<4) $nb=4;
		return $nb;
	} else return ("");
}

function get_distance_axe ($parametres) {
	if (startsWith($parametres['confection_rideau'],"Oeillets")) {
		$nb_oeillets = get_nombre_oeillet($parametres);
		if ($nb_oeillets)
			return (get_largeur_rideau_brute($parametres) / $nb_oeillets * 100);
	} 
	return ("");
}

function get_distance_bord ($parametres) {
	if (startsWith($parametres['confection_rideau'],"Oeillets")) {
		$distanceaxe = get_distance_axe($parametres);
		if ($distanceaxe)
			return ($distanceaxe / 2);
	} 
	return ("");
}	



?>