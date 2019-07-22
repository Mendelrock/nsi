<?php

include("param_3_datasets/produitentetegenerique.php");

$datasets[produitentetemoto3] = $datasets[produitentetegenerique];


$datasets[produitentetemoto3][champs][qte_inver_fil_5pos][id_champ] = 'qte_inver_fil_5pos';

$champs[qte_inver_fil_5pos][libelle] = 'Qte INVERSEUR FILAIRE 5 POSITIONS';

$champs[qte_inver_fil_5pos][type] = 'ouvert';

$champs[qte_inver_fil_5pos][longueur] = 4;

$champs[qte_inver_fil_5pos][largeur] = 2/5;

$champs[qte_inver_fil_5pos][title] = 'Saisir la quantité';

$datasets[produitentetemoto3][champs][qte_inver_fil_5pos][saute_avant][1] = 1;

$datasets[produitentetemoto3][champs][qte_inver_fil_5pos][saute_avant][2] = 1;

$datasets[produitentetemoto3][champs][qte_inver_fil_5pos][saute_avant][3] = 1;


$datasets[produitentetemoto3][champs][qte_telecmd_1canal_ven][id_champ] = 'qte_telecmd_1canal_ven';

$champs[qte_telecmd_1canal_ven][libelle] = 'Qte TELECOMMANDE 1 CANAL';

$champs[qte_telecmd_1canal_ven][type] = 'ouvert';

$champs[qte_telecmd_1canal_ven][longueur] = 4;

$champs[qte_telecmd_1canal_ven][largeur] = 2/5;

$champs[qte_telecmd_1canal_ven][title] = 'Saisir la quantité';

$datasets[produitentetemoto3][champs][qte_telecmd_1canal_ven][saute_avant][1] = 1;

$datasets[produitentetemoto3][champs][qte_telecmd_1canal_ven][saute_avant][2] = 1;

$datasets[produitentetemoto3][champs][qte_telecmd_1canal_ven][saute_avant][3] = 1;


$datasets[produitentetemoto3][champs][qte_telecmd_5canaux_ven][id_champ] = 'qte_telecmd_5canaux_ven';

$champs[qte_telecmd_5canaux_ven][libelle] = 'Qte TELECOMMANDE 5 CANAUX';

$champs[qte_telecmd_5canaux_ven][type] = 'ouvert';

$champs[qte_telecmd_5canaux_ven][longueur] = 4;

$champs[qte_telecmd_5canaux_ven][largeur] = 2/5;

$champs[qte_telecmd_5canaux_ven][title] = 'Saisir la quantité';

$datasets[produitentetemoto3][champs][qte_telecmd_5canaux_ven][saute_avant][1] = 1;

$datasets[produitentetemoto3][champs][qte_telecmd_5canaux_ven][saute_avant][2] = 1;

$datasets[produitentetemoto3][champs][qte_telecmd_5canaux_ven][saute_avant][3] = 1;


$datasets[produitentetemoto3][checkvalidate]='checkvalidate';

?>