<?php
include("param_3_datasets/produitentetegenerique.php");
$datasets[produitentetemoto4] = $datasets[produitentetegenerique];


/*$datasets[produitentetemoto4][champs][moto4][id_champ] = 'Type de Commande Motorisée';
$champs[moto4][libelle] = 'Type de Commande Motorisée';
$champs[moto4][type] = 'lov';
$champs[moto4][tablecible] = ' select "-" as id_article, "Pas de commande" as lb_article union select id_article, lb_article from article where selecteur = "moto2" or selecteur = "moto4" ';
$champs[moto4][champstocke] = 'id_article';
$champs[moto4][champscible] = 'lb_article';
$datasets[produitentetemoto4][champs][quantite_e][id_champ] = 'Quantité';
$champs[quantite_e][libelle] = 'Qté';
$champs[quantite_e][type] = 'ouvert';
$champs[quantite_e][longueur] = 4;
$champs[quantite_e][largeur] = 2/5;
$champs[quantite_e][title] = 'Saisir la quantité'; */


$datasets[produitentetemoto4][champs][qte_inter_fil_unit][id_champ] = 'qte_inter_fil_unit';
$champs[qte_inter_fil_unit][libelle] = 'Qte Interrupteur Filaire Unitaire';
$champs[qte_inter_fil_unit][type] = 'ouvert';
$champs[qte_inter_fil_unit][longueur] = 4;
$champs[qte_inter_fil_unit][largeur] = 2/5;
$champs[qte_inter_fil_unit][title] = 'Saisir la quantité';
$datasets[produitentetemoto4][champs][qte_inter_fil_unit][saute_avant][1] = 1;
$datasets[produitentetemoto4][champs][qte_inter_fil_unit][saute_avant][2] = 1;
$datasets[produitentetemoto4][champs][qte_inter_fil_unit][saute_avant][3] = 1;

$datasets[produitentetemoto4][champs][qte_inter_radio_1canal][id_champ] = 'qte_inter_radio_1canal';
$champs[qte_inter_radio_1canal][libelle] = 'Qte Interrupteur Radio 1 Canal';
$champs[qte_inter_radio_1canal][type] = 'ouvert';
$champs[qte_inter_radio_1canal][longueur] = 4;
$champs[qte_inter_radio_1canal][largeur] = 2/5;
$champs[qte_inter_radio_1canal][title] = 'Saisir la quantité';
$datasets[produitentetemoto4][champs][qte_inter_radio_1canal][saute_avant][1] = 2;
$datasets[produitentetemoto4][champs][qte_inter_radio_1canal][saute_avant][2] = 2;
$datasets[produitentetemoto4][champs][qte_inter_radio_1canal][saute_avant][3] = 2;

$datasets[produitentetemoto4][champs][qte_telecmd_rad_1canal][id_champ] = 'qte_telecmd_rad_1canal';
$champs[qte_telecmd_rad_1canal][libelle] = 'Qte Télécommande Radio 1 Canal';
$champs[qte_telecmd_rad_1canal][type] = 'ouvert';
$champs[qte_telecmd_rad_1canal][longueur] = 4;
$champs[qte_telecmd_rad_1canal][largeur] = 2/5;
$champs[qte_telecmd_rad_1canal][title] = 'Saisir la quantité';
$datasets[produitentetemoto4][champs][qte_telecmd_rad_1canal][saute_avant][1] = 2;
$datasets[produitentetemoto4][champs][qte_telecmd_rad_1canal][saute_avant][2] = 2;
$datasets[produitentetemoto4][champs][qte_telecmd_rad_1canal][saute_avant][3] = 2;

$datasets[produitentetemoto4][champs][qte_telecmd_radio_4canaux][id_champ] = 'qte_telecmd_radio_4canaux';
$champs[qte_telecmd_radio_4canaux][libelle] = 'Qte Télécommande Radio 4 Canaux';
$champs[qte_telecmd_radio_4canaux][type] = 'ouvert';
$champs[qte_telecmd_radio_4canaux][longueur] = 4;
$champs[qte_telecmd_radio_4canaux][largeur] = 2/5;
$champs[qte_telecmd_radio_4canaux][title] = 'Saisir la quantité';
$datasets[produitentetemoto4][champs][qte_telecmd_radio_4canaux][saute_avant][1] = 2;
$datasets[produitentetemoto4][champs][qte_telecmd_radio_4canaux][saute_avant][2] = 2;
$datasets[produitentetemoto4][champs][qte_telecmd_radio_4canaux][saute_avant][3] = 2;



$datasets[produitentetemoto4][checkvalidate]='checkvalidate';
?>