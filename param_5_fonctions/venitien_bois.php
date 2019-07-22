<?php

/*-------------------------------------------------
 Retourne Nombre de lames pour VENITIEN BOIS 50 mm
-------------------------------------------------*/

function get_nb_lames_of($parametres) {
	if(($parametres['largeur_venitienbois50'])>2700) {
  		return 0;
	} else {
  		return (round($parametres['hauteur_venitienbois50']/42)-3);
	}
}



/*-------------------------------------------------
 Retourne Nbre pour VENITIEN BOIS 50 mm
-------------------------------------------------*/

function get_nb_of($parametres) {
	if($parametres['largeur_venitienbois50']<1050)
		return 2;	
	else if($parametres['largeur_venitienbois50']<2700)
		return 3;
	else 
		return 0;
}



/*-------------------------------------------------
 Retourne Longueur pour VENITIEN BOIS 50 mm
-------------------------------------------------*/
function get_longueur_of($parametres) {
	if($parametres['largeur_venitienbois50']>2700)
		return 0;	
	else
		return ($parametres['largeur_venitienbois50']) + ($parametres['hauteur_venitienbois50']) + ($parametres['hauteur_cde_venitienbois50']);
}



/*-------------------------------------------------
 Retourne Orientat. pour VENITIEN BOIS 50 mm
-------------------------------------------------*/

function get_orientation_of($parametres) {
  return (2 * ($parametres['hauteur_cde_venitienbois50'])) + 800;
}



function get_bdc_of($parametres) {
  return $parametres['largeur_venitienbois50'];
}

/*
-------------------------------------------------
 Retourne Nbre d'Echelles pour VENITIEN BOIS 50 mm
------------------------------------------------*/

function get_nbechelles_of($parametres) {
	if($parametres['largeur_venitienbois50']<1050) return 2;	
	else if($parametres['largeur_venitienbois50']<1870) return 3;
	else if($parametres['largeur_venitienbois50']<2700)return 5;
	else return 0;
}



function get_typecommande_venitienbois50 ($parametres) {
	return($parametres['typecommande_venitienbois50']); 
}

function get_hauteur_venitienbois50 ($parametres) {
	return($parametres['hauteur_venitienbois50']*0.1);
}

function get_largeur_venitienbois50 ($parametres) {
	return($parametres['largeur_venitienbois50']*0.1);
}

function get_qte_venitienbois50 ($parametres) {
	return($parametres['qte'])*1; 
}

function get_cotecommande_venitienbois50 ($parametres) {
	return($parametres['cotecommande_venitienbois50']); 
}

function get_hauteur_cde_venitienbois50 ($parametres) {
	return($parametres['hauteur_cde_venitienbois50']*0.1);
}

function get_typepose_venitienbois50 ($parametres) {
	return($parametres['typepose_venitienbois50']); 
}

function get_gamme_venitienbois50 ($parametres) {
	return($parametres['gamme_venitienbois50']); 
}

function get_gamme_galonsvenitienbois50 ($parametres) {
	return($parametres['gamme_galonsvenitienbois50']); 
}

function get_ref_gamme_venitienbois50 ($parametres) {
  	$requete="
		SELECT 
			article_propriete.valeur  
		FROM  
			article_propriete , 
			article
		WHERE 
			article_propriete.id_article = article.id_article AND 
			article.selecteur = 'BANDEBOIS50' AND 
			article_propriete.propriete = 'referenceatelier' AND 
			article_propriete.id_article = ".get_gamme_venitienbois50($parametres); 
  	$resultat = new db_sql($requete);
  	while($resultat->n()) {
		return($resultat->f('valeur'));
	}
	return('inconnu');
}

function get_ref_gamme_galonsvenitienbois50 ($parametres) {
  	$requete="
   	   	SELECT 
   	   		reference  
		FROM  
			fournisseur_article , 
			article
		WHERE 
			fournisseur_article.id_article = article.id_article AND 
			selecteur = 'GALONSBOIS' AND 
			principal = 1 AND 
			fournisseur_article.id_article = ".get_gamme_galonsvenitienbois50($parametres); 
  	$resultat = new db_sql($requete);
  	while($resultat->n()) {
		return($resultat->f('reference'));
	}
	return('inconnu');
}



function get_cantoniere_venitienbois50($parametres) {
  return ($parametres['cantoniere_fdc']);
}





function get_premier_of($parametres) {
  if($parametres['largeur_venitienbois50']<500) return 100;	
  else if($parametres['largeur_venitienbois50']<700) return 120;	
  else if($parametres['largeur_venitienbois50']<2700) return 170;
  else return 0;	
}



function get_deuxieme_of($parametres) {
  if($parametres['largeur_venitienbois50']<500) return ($parametres['largeur_venitienbois50'])-100;	
  else if($parametres['largeur_venitienbois50']<700) return ($parametres['largeur_venitienbois50'])-120;	
  else if(get_nbechelles_of($parametres)==2) return ($parametres['largeur_venitienbois50'])-170;	
  else if(get_nbechelles_of($parametres)==3) return round($parametres['largeur_venitienbois50']/2,0, PHP_ROUND_HALF_UP);
  else if(get_nbechelles_of($parametres)==4) return round((($parametres['largeur_venitienbois50']-340)/3) + 170,0, PHP_ROUND_HALF_UP);
  else if(get_nbechelles_of($parametres)==5) return round((($parametres['largeur_venitienbois50']-340)/4) + 170,0, PHP_ROUND_HALF_UP);
  else return 0;	
}



function get_troisieme_of($parametres) {
  if(get_nbechelles_of($parametres)==2)return "";	
  else if(get_nbechelles_of($parametres)==3)return ($parametres['largeur_venitienbois50'])-170;
  else if(get_nbechelles_of($parametres)==4)return round(((($parametres['largeur_venitienbois50']-340)/3)*2) + 170,0, PHP_ROUND_HALF_UP);
  else if(get_nbechelles_of($parametres)==5)return round(($parametres['largeur_venitienbois50'])/2,0, PHP_ROUND_HALF_UP);	
  else return 0;
}



function get_quatrieme_of($parametres) {
  if(get_nbechelles_of($parametres)==2) return "";	
  else if(get_nbechelles_of($parametres)==3) return "";
  else if(get_nbechelles_of($parametres)==4) return ($parametres['largeur_venitienbois50'])-170;	
  else if(get_nbechelles_of($parametres)==5) return round((($parametres['largeur_venitienbois50']-340)*3/4) + 170,0, PHP_ROUND_HALF_UP);	
  else return 0;
}



function get_cinquieme_of($parametres) {
  if(get_nbechelles_of($parametres)==2) return "";	
  else if(get_nbechelles_of($parametres)==3) return "";
  else if(get_nbechelles_of($parametres)==4) return "";	
  else if(get_nbechelles_of($parametres)==5) return ($parametres['largeur_venitienbois50'])-170;	
  else return 0;
}



function get_coloris_venitien_of($parametres) {
  return $parametres['gamme_venitienbois50'];
}

function get_hauteur_galonsvenitienbois50 ($parametres) {
	return ($parametres['hauteur_venitienbois50']+250);
}

function get_metrage_lame_of($parametres) {	
	return $parametres['largeur_venitienbois50'] * $parametres['qte'] * get_nb_lames_of($parametres)/1000;	
}	
	
function get_metrage_bdc_of($parametres) {	
	return $parametres['largeur_venitienbois50'] * $parametres['qte'] /1000;	
}	
	
function get_metrage_cantoniere_of($parametres) {	
	return ($parametres['largeur_venitienbois50']+100) * $parametres['qte'] /1000;	
}

?>