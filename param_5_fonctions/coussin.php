<?php
/*
-------------------------------------------------
 Retourne COLORIS pour coussin
-------------------------------------------------*/
function get_coloris_coussin_of($parametres) {
  return $parametres['gamme_coussin'];
}

/*
-------------------------------------------------
 Retourne largeur pour coussin
-------------------------------------------------*/
function get_largeur_coussin_fini_of($parametres) {
  return $parametres['largeur_coussin']/1000;
}

/*
-------------------------------------------------
 Retourne hauteur pour coussin
-------------------------------------------------*/
function get_hauteur_coussin_fini_of($parametres) {
  return $parametres['hauteur_coussin']/1000;
}

/*
-------------------------------------------------
 Retourne largeur coupe pour coussin
-------------------------------------------------*/
function get_largeur_coussin_of($parametres) {
  if(get_largeur_coussin_fini_of($parametres)==0.45)	
    return 0.44;
  else if(get_largeur_coussin_fini_of($parametres)==0.6)
    return 0.59;
  else if(get_largeur_coussin_fini_of($parametres)==0.7)
    return 1.7; 
  else
   return "ANOMALIE"; 
}

/*
-------------------------------------------------
 Retourne hauteur coupe pour coussin
-------------------------------------------------*/
function get_hauteur_coussin_of($parametres) {
  if(get_largeur_coussin_fini_of($parametres)==0.45)	
    return 1.1;
  else if(get_largeur_coussin_fini_of($parametres)==0.6)
    return 1.5;
  else if(get_largeur_coussin_fini_of($parametres)==0.7)
    return 0.44; 
  else
   return "ANOMALIE"; 
}

/*
-------------------------------------------------
 Retourne Qte pour coussin
-------------------------------------------------*/
function get_qte_coussin_of($parametres) {
  return $parametres['qte']*1;
}


?>