<?php
$datasets[produitentetegenerique][presentation] = 3;
$lo_eg = array();
$lo_eg[commentaires_fabrication][id_champ] = 'commentaires_fabrication';
$lo_eg[hauteurpose][id_champ] = 'hauteurpose';
$lo_eg[materiaupose][id_champ] = 'materiaupose';
$lo_eg[moyens_levage][id_champ] = 'moyens_levage';
$lo_eg[deport][id_champ] = 'Déport';
$champs[deport][libelle] = 'Déport';
$champs[deport][type]    = 'lov';
$champs[deport][title] = 'Saisir le déport';
$lo_eg[acces_chantier][id_champ] = 'acces_chantier';
//$lo_eg[informationspose][id_champ] = 'informationspose';
$lo_eg[commentaires_pose][id_champ] = 'commentaires_pose';

$lo_eg[depose_prealable][id_champ] = 'depose_prealable';
$champs[depose_prealable][libelle] = 'Dépose Préalable';
$champs[depose_prealable][type]    = 'lov';
$datasets[produitentetegenerique][checkvalidate]='checkvalidate';
$datasets[produitentetegenerique][champs] = $lo_eg;
?>