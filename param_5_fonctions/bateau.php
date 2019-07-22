<?php
/*
-------------------------------------------------
 Retourne Coloris pour store bateau
-------------------------------------------------*/
function get_coloris_storebateauof($parametres) {
  return $parametres['toile_storebateau'];
}

/*
-------------------------------------------------
 Retourne largeur gaine pour store bateau
-------------------------------------------------*/
function get_lgaines_storebateau_of($parametres) {
  return get_largeur_storebateau_of($parametres);
}

/*
-------------------------------------------------
 Retourne Largeur coupe pour store bateau
-------------------------------------------------*/
function get_largeurcoupe_storebateau_of($parametres) {
  return get_largeur_storebateau_of($parametres)+0.06;
}

/*
-------------------------------------------------
 Retourne Hauteur coupe pour store bateau
-------------------------------------------------*/
function get_hauteurcoupe_storebateau_of($parametres) {
  //return round(get_hauteur_storebateau_of($parametres)+0.075,2,PHP_ROUND_HALF_EVEN);
  return get_hauteur_storebateau_of($parametres)+0.08;
}

/*
-------------------------------------------------
 Retourne Largeur store pour store bateau
-------------------------------------------------*/
function get_largeur_storebateau_of($parametres) {
  return $parametres['largeur_storebateau']/1000;
}

/*
-------------------------------------------------
 Retourne Largeur rail+velcro pour store bateau
-------------------------------------------------*/
function get_l_rail_velcro($parametres) {
  return get_largeur_storebateau_of($parametres) -0.005;
}

/*
-------------------------------------------------
 Retourne Nombre de cordons pour store bateau
-------------------------------------------------*/
function get_nbcordons_storebateau_of($parametres) {
  if(get_largeur_storebateau_of($parametres)>1.39)	
   return 4;
  else{
   if(get_largeur_storebateau_of($parametres)>0.99)
	   return 3;
	 else
	   return 2; 		
  }
}

/*
-------------------------------------------------
 Retourne Type de pose pour store bateau
-------------------------------------------------*/
function get_typepose_storebateau_of($parametres) {
	return $parametres['typepose_storebateau'];
}

/*
-------------------------------------------------
 Retourne Qté pour store bateau
-------------------------------------------------*/
function get_qte_storebateau_of($parametres) {
	return $parametres['qte']*1;
}

/*
-------------------------------------------------
 Retourne Largeur raidisseur
-------------------------------------------------*/
function get_largeur_raidisseurs($parametres) {
	return get_largeur_storebateau_of($parametres)-0.027;
}

/*
-------------------------------------------------
 Retourne Largeur lestage
-------------------------------------------------*/
function get_largeur_lestage($parametres) {
	return get_largeur_storebateau_of($parametres)-0.012;
}


/*
-------------------------------------------------
 Retourne HAUTEUR PLI REFERENCE
-------------------------------------------------*/
function get_h_pli_reference($parametres) {
	if(get_hauteur_storebateau_of($parametres)>1)	
		return 0.22;
	else
		return 0.16;
}

/*
-------------------------------------------------
 Retourne Position oeillet n°1
-------------------------------------------------*/
function get_poeillets1_storebateau_of($parametres) {
	return 0.1;
}

/*
-------------------------------------------------
 Retourne Position oeillet n°3
-------------------------------------------------*/
function get_poeillets3_storebateau_of($parametres) {
  if(get_nbcordons_storebateau_of($parametres)==2)	
   return "-";
  else{
   if(get_nbcordons_storebateau_of($parametres)==3)
	   return get_largeur_storebateau_of($parametres)-0.1;
	 else
	   return ((get_largeur_storebateau_of($parametres)-0.2)*2/3)+0.1;	
  }
}

/*
-------------------------------------------------
 Retourne Position oeillet n°4
-------------------------------------------------*/
function get_poeillets4_storebateau_of($parametres) {
  if(get_nbcordons_storebateau_of($parametres)==2)	
   return "-";
  else{
   if(get_nbcordons_storebateau_of($parametres)==3)
	   return "-";
	 else
	   return get_largeur_storebateau_of($parametres)-0.1;
  }
}

/*
-------------------------------------------------
 Retourne Position oeillet n°2
-------------------------------------------------*/
function get_poeillets2_storebateau_of($parametres) {
	if(get_nbcordons_storebateau_of($parametres)==2)	
		return get_largeur_storebateau_of($parametres) -0.1;
	else{
		if(get_nbcordons_storebateau_of($parametres)==3)
			return get_largeur_storebateau_of($parametres)/2;
		else
			return ((get_largeur_storebateau_of($parametres)-0.2)/3)+0.1;	
		}
}

/*
-------------------------------------------------
 Retourne Partie decimale de NBE DE PLIS
-------------------------------------------------*/
function get_dec_nb_plis_calcule($parametres) {
  return get_nb_plis_calcule($parametres)-floor(get_nb_plis_calcule($parametres));
}

/*
-------------------------------------------------
 Retourne HAUTEUR A AJOUTER A CHAQUE PLI
-------------------------------------------------*/
function get_hauteur_ajout_plis($parametres) {
  return get_dec_nb_plis_calcule($parametres)*get_h_pli_reference($parametres)/floor(get_nb_plis_calcule($parametres));
}

/*
-------------------------------------------------
 Retourne Distance 1ere gaine
-------------------------------------------------*/
function get_dpremgaines_storebateau_of($parametres) {
	return (get_hauteur_storebateau_of($parametres)-(floor(get_hauteur_storebateau_of($parametres)/get_hauteur_plis($parametres))*get_hauteur_plis($parametres)))*100;
}

/*
-------------------------------------------------
 Retourne Distance entre gaines
-------------------------------------------------*/
function get_dentregaines_storebateau_of($parametres) {
   return round(get_hauteur_plis($parametres)*100,1);
}

/*
-------------------------------------------------
 Retourne HAUTEUR PLI
-------------------------------------------------*/
function get_hauteur_plis($parametres) {
	return 0.25;
	//return get_h_pli_reference($parametres)+get_hauteur_ajout_plis($parametres);
}

/*
-------------------------------------------------
 Retourne NBE DE PLIS
-------------------------------------------------*/
function get_nb_plis_calcule($parametres) {
  return get_hauteur_storebateau_of($parametres)/get_hauteur_plis($parametres);
}

/*
-------------------------------------------------
 Retourne Côte commande pour store bateau
-------------------------------------------------*/
function get_cotecde_storebateau_of($parametres) {
  return $parametres['cotecommande_storebateau'];
}

/*
-------------------------------------------------
 Retourne Hauteur commande pour store bateau
-------------------------------------------------*/
function get_hcde_storebateau_of($parametres) {
  return $parametres['hauteur_cde_storebateau']/1000;
}

/*
-------------------------------------------------
 Retourne Hauteur store pour store bateau
-------------------------------------------------*/
function get_hauteur_storebateau_of($parametres) {
  return $parametres['hauteur_storebateau']/1000;
}

/*
-------------------------------------------------
 Retourne Nombre Raidisseurs
-------------------------------------------------*/
function get_nombre_raidisseurs($parametres) {
        //return $parametres['nombre_raidisseurs']/1000;
        return floor(get_nb_plis_calcule($parametres))-1;
}

/*
-------------------------------------------------
 Retourne Nombre Cordons Total
-------------------------------------------------*/
function get_nbcordons_storebateau_total_of($parametres) {
    return get_qte_storebateau_of($parametres)*get_nbcordons_storebateau_of($parametres);
}

/*
-------------------------------------------------
 Retourne Nombre raidisseurs total
-------------------------------------------------*/
function get_nombre_raidisseurs_total($parametres) {
    return get_nombre_raidisseurs($parametres)*get_qte_storebateau_of($parametres);
}

/*
-------------------------------------------------
 Retourne COUPE_CORDON
-------------------------------------------------*/
function get_coupe_cordon($parametres) {
    return ((get_hauteur_storebateau_of($parametres)+get_largeur_storebateau_of($parametres)+get_hcde_storebateau_of($parametres))+0.2);
}


?>