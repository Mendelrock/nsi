<?php

function get_velux_modele($parametres) {
  return $parametres['fdc_velux_modele'];
}

function get_velux_coloris($parametres) {
  return $parametres['fdc_velux_toile'];
}

function get_velux_perche($parametres) {
  return $parametres['fdc_velux_perche'];
}

function get_velux_qte($parametres) {
  return $parametres['qte']*1;
}

function get_velux_largeur_coupe($parametres) {
   $val[H1]='0.375';
   $val[H2]='0.361';
   $val[H3]='0.361';
   $val[I1]='0.362';
   $val[I2]='0.375';
   $val[I3]='0.361';
   $val[A2]='0.592';
   $val[A3]='0.591';
   $val[B2]='0.591';
   $val[B3]='0.591';
   $val[C2]='0.591';
   $val[C3]='0.591';
   $val[D2]='0.951';
   $val[D3]='0.951';
   $val[E2]='0.951';
   $val[E3]='0.951';
   $val[F2]='1.151';
   $val[F3]='1.151';
   $val[G2]='1.151';
   $val[G3]='1.151';
   return $val[get_velux_modele($parametres)];
}

function get_velux_hauteur_coupe($parametres) {
   $val[H1]='0.725';
   $val[H2]='0.690';
   $val[H3]='0.745';
   $val[I1]='0.892';
   $val[I2]='0.925';
   $val[I3]='0.945';
   $val[A2]='0.892';
   $val[A3]='0.945';
   $val[B2]='1.090';
   $val[B3]='1.145';
   $val[C2]='1.310';
   $val[C3]='1.365';
   $val[D2]='1.090';
   $val[D3]='1.145';
   $val[E2]='1.310';
   $val[E3]='1.365';
   $val[F2]='0.890';
   $val[F3]='0.945';
   $val[G2]='1.310';
   $val[G3]='1.365';
   return $val[get_velux_modele($parametres)];
}

function get_velux_largeur_cache_alu($parametres) {
   $val[H1]='0.394';
   $val[H2]='0.380';
   $val[H3]='0.380';
   $val[I1]='0.381';
   $val[I2]='0.394';
   $val[I3]='0.380';
   $val[A2]='0.611';
   $val[A3]='0.610';
   $val[B2]='0.610';
   $val[B3]='0.610';
   $val[C2]='0.610';
   $val[C3]='0.610';
   $val[D2]='0.970';
   $val[D3]='0.970';
   $val[E2]='0.970';
   $val[E3]='0.970';
   $val[F2]='1.170';
   $val[F3]='1.170';
   $val[G2]='1.170';
   $val[G3]='1.170';
   return $val[get_velux_modele($parametres)];
}

function get_velux_largeur_tube_28($parametres) {
   $val[H1]='0.341';
   $val[H2]='0.327';
   $val[H3]='0.327';
   $val[I1]='0.328';
   $val[I2]='0.341';
   $val[I3]='0.327';
   $val[A2]='0.558';
   $val[A3]='0.557';
   $val[B2]='0.557';
   $val[B3]='0.557';
   $val[C2]='0.557';
   $val[C3]='0.557';
   $val[D2]='0.917';
   $val[D3]='0.917';
   $val[E2]='0.917';
   $val[E3]='0.917';
   $val[F2]='1.117';
   $val[F3]='1.117';
   $val[G2]='1.117';
   $val[G3]='1.117';
   return $val[get_velux_modele($parametres)];
}

function get_velux_type_ressort($parametres) {
   $val[H1]='S 180';
   $val[H2]='S 180';
   $val[H3]='S 180';
   $val[I1]='S 180';
   $val[I2]='S 180';
   $val[I3]='S 180';
   $val[A2]='S 260';
   $val[A3]='S 260';
   $val[B2]='S 260';
   $val[B3]='S 260';
   $val[C2]='S 360';
   $val[C3]='S 360';
   $val[D2]='S 360';
   $val[D3]='S 360';
   $val[E2]='S 360';
   $val[E3]='S 360';
   $val[F2]='S 360';
   $val[F3]='S 360';
   $val[G2]='S 360';
   $val[G3]='S 360';
   return $val[get_velux_modele($parametres)];
}

function get_velux_nb_tours_tension($parametres) {
   $val[H1]='5';
   $val[H2]='5';
   $val[H3]='5';
   $val[I1]='5';
   $val[I2]='5';
   $val[I3]='5';
   $val[A2]='13';
   $val[A3]='13';
   $val[B2]='13';
   $val[B3]='13';
   $val[C2]='18';
   $val[C3]='18';
   $val[D2]='18';
   $val[D3]='18';
   $val[E2]='18';
   $val[E3]='18';
   $val[F2]='18';
   $val[F3]='18';
   $val[G2]='18';
   $val[G3]='18';
   return $val[get_velux_modele($parametres)];
}

function get_velux_nb_vis_support($parametres) {
   $val[H1]='4';
   $val[H2]='4';
   $val[H3]='4';
   $val[I1]='4';
   $val[I2]='4';
   $val[I3]='4';
   $val[A2]='4';
   $val[A3]='4';
   $val[B2]='4';
   $val[B3]='4';
   $val[C2]='4';
   $val[C3]='4';
   $val[D2]='4';
   $val[D3]='4';
   $val[E2]='4';
   $val[E3]='4';
   $val[F2]='4';
   $val[F3]='4';
   $val[G2]='4';
   $val[G3]='4';
   return $val[get_velux_modele($parametres)];
}

function get_velux_longueur_coulisse($parametres) {
   $val[H1]='0.520';
   $val[H2]='0.485';
   $val[H3]='0.540';
   $val[I1]='0.687';
   $val[I2]='0.720';
   $val[I3]='0.740';
   $val[A2]='0.687';
   $val[A3]='0.740';
   $val[B2]='0.885';
   $val[B3]='0.940';
   $val[C2]='1.105';
   $val[C3]='1.160';
   $val[D2]='0.885';
   $val[D3]='0.940';
   $val[E2]='1.105';
   $val[E3]='1.160';
   $val[F2]='0.685';
   $val[F3]='0.740';
   $val[G2]='1.105';
   $val[G3]='1.160';
   return $val[get_velux_modele($parametres)];
}

function get_velux_nb_percage($parametres) {
   $val[H1]='3';
   $val[H2]='2';
   $val[H3]='3';
   $val[I1]='3';
   $val[I2]='3';
   $val[I3]='3';
   $val[A2]='3';
   $val[A3]='3';
   $val[B2]='3';
   $val[B3]='4';
   $val[C2]='4';
   $val[C3]='4';
   $val[D2]='3';
   $val[D3]='4';
   $val[E2]='4';
   $val[E3]='4';
   $val[F2]='3';
   $val[F3]='3';
   $val[G2]='4';
   $val[G3]='4';
   return $val[get_velux_modele($parametres)];
}

function get_velux_distance_depuis_bas_percage($parametres) {
   $val[H1]='80';
   $val[H2]='80';
   $val[H3]='80';
   $val[I1]='80';
   $val[I2]='80';
   $val[I3]='80';
   $val[A2]='80';
   $val[A3]='80';
   $val[B2]='80';
   $val[B3]='80';
   $val[C2]='80';
   $val[C3]='80';
   $val[D2]='80';
   $val[D3]='80';
   $val[E2]='80';
   $val[E3]='80';
   $val[F2]='80';
   $val[F3]='80';
   $val[G2]='80';
   $val[G3]='80';
   return $val[get_velux_modele($parametres)];
}

function get_velux_distance_trou_suivant($parametres) {
   $val[H1]='0.180';
   $val[H2]='0.325';
   $val[H3]='0.190';
   $val[I1]='0.264';
   $val[I2]='0.280';
   $val[I3]='0.290';
   $val[A2]='0.264';
   $val[A3]='0.290';
   $val[B2]='0.363';
   $val[B3]='0.260';
   $val[C2]='0.315';
   $val[C3]='0.333';
   $val[D2]='0.363';
   $val[D3]='0.260';
   $val[E2]='0.315';
   $val[E3]='0.333';
   $val[F2]='0.263';
   $val[F3]='0.290';
   $val[G2]='0.315';
   $val[G3]='0.333';
   return $val[get_velux_modele($parametres)];
}

function get_velux_nb_vis_pose_coulisse($parametres) {
   $val[H1]='6';
   $val[H2]='4';
   $val[H3]='6';
   $val[I1]='6';
   $val[I2]='6';
   $val[I3]='6';
   $val[A2]='6';
   $val[A3]='6';
   $val[B2]='6';
   $val[B3]='8';
   $val[C2]='8';
   $val[C3]='8';
   $val[D2]='6';
   $val[D3]='8';
   $val[E2]='8';
   $val[E3]='8';
   $val[F2]='6';
   $val[F3]='6';
   $val[G2]='8';
   $val[G3]='8';
   return $val[get_velux_modele($parametres)];
}

function get_velux_longueur_bdc($parametres) {
   $val[H1]='0.326';
   $val[H2]='0.312';
   $val[H3]='0.312';
   $val[I1]='0.313';
   $val[I2]='0.326';
   $val[I3]='0.312';
   $val[A2]='0.543';
   $val[A3]='0.542';
   $val[B2]='0.542';
   $val[B3]='0.542';
   $val[C2]='0.542';
   $val[C3]='0.542';
   $val[D2]='0.902';
   $val[D3]='0.902';
   $val[E2]='0.902';
   $val[E3]='0.902';
   $val[F2]='1.102';
   $val[F3]='1.102';
   $val[G2]='1.102';
   $val[G3]='1.102';
   return $val[get_velux_modele($parametres)];
}

function get_velux_longueur_balai_bdc($parametres) {
   $val[H1]='0.325';
   $val[H2]='0.311';
   $val[H3]='0.311';
   $val[I1]='0.312';
   $val[I2]='0.325';
   $val[I3]='0.311';
   $val[A2]='0.542';
   $val[A3]='0.541';
   $val[B2]='0.541';
   $val[B3]='0.541';
   $val[C2]='0.541';
   $val[C3]='0.541';
   $val[D2]='0.901';
   $val[D3]='0.901';
   $val[E2]='0.901';
   $val[E3]='0.901';
   $val[F2]='1.101';
   $val[F3]='1.101';
   $val[G2]='1.101';
   $val[G3]='1.101';
   return $val[get_velux_modele($parametres)];
}

function get_velux_longueur_jonc_toile($parametres) {
   $val[H1]='0.375';
   $val[H2]='0.361';
   $val[H3]='0.361';
   $val[I1]='0.362';
   $val[I2]='0.375';
   $val[I3]='0.361';
   $val[A2]='0.592';
   $val[A3]='0.591';
   $val[B2]='0.591';
   $val[B3]='0.591';
   $val[C2]='0.591';
   $val[C3]='0.591';
   $val[D2]='0.951';
   $val[D3]='0.951';
   $val[E2]='0.951';
   $val[E3]='0.951';
   $val[F2]='1.151';
   $val[F3]='1.151';
   $val[G2]='1.151';
   $val[G3]='1.151';
   return $val[get_velux_modele($parametres)];
}

function get_velux_nb_opercules($parametres) {
   $val[H1]='2';
   $val[H2]='2';
   $val[H3]='2';
   $val[I1]='2';
   $val[I2]='2';
   $val[I3]='2';
   $val[A2]='2';
   $val[A3]='2';
   $val[B2]='2';
   $val[B3]='2';
   $val[C2]='2';
   $val[C3]='2';
   $val[D2]='2';
   $val[D3]='2';
   $val[E2]='2';
   $val[E3]='2';
   $val[F2]='2';
   $val[F3]='2';
   $val[G2]='2';
   $val[G3]='2';
   return $val[get_velux_modele($parametres)];
}

function get_velux_distance_depuis_bas_opercile($parametres) {
   $val[H1]='0.242';
   $val[H2]='0.230';
   $val[H3]='0.248';
   $val[I1]='0.297';
   $val[I2]='0.308';
   $val[I3]='0.315';
   $val[A2]='0.297';
   $val[A3]='0.315';
   $val[B2]='0.363';
   $val[B3]='0.382';
   $val[C2]='0.437';
   $val[C3]='0.455';
   $val[D2]='0.363';
   $val[D3]='0.382';
   $val[E2]='0.437';
   $val[E3]='0.455';
   $val[F2]='0.297';
   $val[F3]='0.315';
   $val[G2]='0.437';
   $val[G3]='0.455';
   return $val[get_velux_modele($parametres)];
}

function get_velux_distance_deuxieme_opercule($parametres) {
   $val[H1]='0.242';
   $val[H2]='0.230';
   $val[H3]='0.248';
   $val[I1]='0.297';
   $val[I2]='0.308';
   $val[I3]='0.315';
   $val[A2]='0.297';
   $val[A3]='0.315';
   $val[B2]='0.363';
   $val[B3]='0.382';
   $val[C2]='0.437';
   $val[C3]='0.455';
   $val[D2]='0.363';
   $val[D3]='0.382';
   $val[E2]='0.437';
   $val[E3]='0.455';
   $val[F2]='0.297';
   $val[F3]='0.315';
   $val[G2]='0.437';
   $val[G3]='0.455';
   return $val[get_velux_modele($parametres)];
}


?>