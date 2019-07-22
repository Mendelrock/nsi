<?php

function get_moutiquaire_largeur($parametres) {
  return $parametres['fdc_largeur_moustiquaire']/1000;
}

function get_moutiquaire_hauteur($parametres) {
  return $parametres['fdc_hauteur_moustiquaire']/1000;
}

function get_moustiquaire_coloris_profil($parametres) {
  return $parametres['fdc_moustiquaire_coloris_profil'];
}

function get_moustiquaire_coloris_toile($parametres) {
  return $parametres['fdc_moustiquaire_coloris_toile'];
}

function get_moustiquaire_longueur_coupe($parametres) {
  return get_moutiquaire_largeur($parametres) - 0.017;
}

function get_moustiquaire_longueur_coulisse($parametres) {
  return get_moutiquaire_hauteur($parametres) - 0.049;
}

function get_moustiquaire_nb_tours($parametres) {
  $largeur = get_moutiquaire_largeur($parametres);
  $hauteur = get_moutiquaire_hauteur($parametres);
  
  if ($largeur <= 0.65) $valeur=16;
  elseif ($largeur <= 0.75) $valeur=17;
  elseif ($largeur <= 0.95) $valeur=18;
  elseif ($largeur <= 1.15) $valeur=19;
  elseif ($largeur <= 1.3)  $valeur=20;
  elseif ($largeur <= 1.45) $valeur=21;
  elseif ($largeur <= 1.6)  $valeur=22;
  elseif ($largeur <= 1.75) $valeur=24;
  elseif ($largeur <= 1.9)  $valeur=26;
  else $valeur=28;

  if($valeur){
    if ($hauteur <= 1)    return $valeur;
    if ($hauteur <= 1.5 ) return $valeur + 2;
    if ($hauteur <= 2 )   return $valeur + 3;
    else  return $valeur + 5;
  }
}

function get_moustiquaire_qte($parametres) {
  return $parametres['qte']*1;
}

function get_moustiquaire_cordelette($parametres) {
  return $parametres['fdc_moustiquaire_cordelette'];
}

function get_moustiquaire_hauteur_cordelette($parametres) {
  return $parametres['fdc_moustiquaire_hauteur_cordelette']/1000;
}

?>