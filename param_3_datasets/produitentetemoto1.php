<?php

include("param_3_datasets/produitentetegenerique.php");

$datasets[produitentetemoto1] = $datasets[produitentetegenerique];


$datasets[produitentetemoto1][champs][qte_telecmd_1canal][id_champ] = 'qte_telecmd_1canal';

$champs[qte_telecmd_1canal][libelle] = 'Qte TELECOMMANDE 1 CANAL';

$champs[qte_telecmd_1canal][type] = 'ouvert';

$champs[qte_telecmd_1canal][longueur] = 4;

$champs[qte_telecmd_1canal][largeur] = 2/5;

$champs[qte_telecmd_1canal][title] = 'Saisir la quantité';

$datasets[produitentetemoto1][champs][qte_telecmd_1canal][saute_avant][1] = 2;

$datasets[produitentetemoto1][champs][qte_telecmd_1canal][saute_avant][2] = 2;

$datasets[produitentetemoto1][champs][qte_telecmd_1canal][saute_avant][3] = 2;


$datasets[produitentetemoto1][checkvalidate]='checkvalidate';

?>