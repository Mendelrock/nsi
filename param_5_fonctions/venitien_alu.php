<?php
/*
-------------------------------------------------
 Retourne Hauteur commande pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_hcde_venalu25_of($parametres) {
  return $parametres['hauteur_cde_venitienalu25']/1000;
}

/*
-------------------------------------------------
 Retourne Côte commande pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_cotecde_venalu25_of($parametres) {
  return $parametres['cotecommande_venitienalu25'];
}

/*
-------------------------------------------------
 Retourne Type de pose pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_typepose_venalu25_of($parametres) {
  return $parametres['typepose_venitienalu25'];
}

/*
-------------------------------------------------
 Retourne METRAGE LAMES TOTAL pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_metragelame_venalu25_of($parametres) {
  return ceil($parametres['qte']*get_largeur_venalu25_of($parametres)*get_nblames_venalu25_of($parametres));
}

function get_typecommande_venalu25_of($parametres) {
  return $parametres['typecommande_venitienalu25'];
}

/*
-------------------------------------------------
 Retourne Qté pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_qte_venalu25_of($parametres) {
  return $parametres['qte']*1;
}

/*
-------------------------------------------------
 Retourne guidage pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_guidage_venalu25_of($parametres) {
  return $parametres['guidage_venitienalu25'];
}

/*
-------------------------------------------------
 Retourne NOMBRE DE LAMES pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_nblames_venalu25_of($parametres) {
  return round($parametres['hauteur_venitienalu25']/1000/0.0215,0);
}

/*
-------------------------------------------------
 Retourne HAUTEUR pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_hauteur_venalu25_of($parametres) {
  return $parametres['hauteur_venitienalu25']/1000;
}

/*
-------------------------------------------------
 Retourne Coloris pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_coloris_venalu25_of($parametres) {
  return $parametres['gamme_venitienalu25'];
}

/*
-------------------------------------------------
 Retourne LARGEUR pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_largeur_venalu25_of($parametres) {
  return $parametres['largeur_venitienalu25']/1000;
}

/*
-------------------------------------------------
 Retourne NBR_CORDONS pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_nbr_cordons_venalu25_of($parametres) {

    if(get_largeur_venalu25_of($parametres) < 1999 || get_largeur_venalu25_of($parametres) == 1999){
        return $parametres['nbr_cordons_of_venitien_alu']=2;
    }
    elseif (get_largeur_venalu25_of($parametres) > 1999 ){
        return $parametres['nbr_cordons_of_venitien_alu']=3;
    }
}

/*
-------------------------------------------------
 Retourne COUPE_CORDONS1&2 pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_coupe_cordon_of_venitien_alu_1_2_venalu25_of($parametres) {
   return ((get_hauteur_venalu25_of($parametres)+get_largeur_venalu25_of($parametres)+get_hcde_venalu25_of($parametres))+0.2);
}

/*
-------------------------------------------------
 Retourne COUPE_CORDONS3 pour VENITIEN ALU 25 mm
-------------------------------------------------*/
function get_coupe_cordon_of_venitien_alu_3_venalu25_of($parametres) {
	if($parametres['nbr_cordons_of_venitien_alu'] == 3){
		return ((get_hauteur_venalu25_of($parametres)+get_hcde_venalu25_of($parametres))+0.2);
	}else{
		return "-";
	}
}


?>