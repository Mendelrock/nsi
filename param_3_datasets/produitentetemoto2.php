<?php
include("param_3_datasets/produitentetegenerique.php");
$datasets[produitentetemoto2] = $datasets[produitentetegenerique];

/*$datasets[produitentetemoto2][champs][moto2][id_champ] = 'Type de Commande Motoris�e';
$champs[moto2][libelle] = 'Type de Commande Motoris�e';
$champs[moto2][type] = 'lov';
$champs[moto2][tablecible] = ' select "-" as id_article, "Pas de commande" as lb_article union select id_article, lb_article from article where selecteur = "moto2" ';
$champs[moto2][champstocke] = 'id_article';
$champs[moto2][champscible] = 'lb_article';*/

/*$datasets[produitentetemoto2][champs][quantite_e][id_champ] = 'Quantit�';
$champs[quantite_e][libelle] = 'Qt�';
$champs[quantite_e][type] = 'ouvert';
$champs[quantite_e][longueur] = 4;
$champs[quantite_e][largeur] = 2/5;
$champs[quantite_e][title] = 'Saisir la quantit�';*/


$datasets[produitentetemoto2][champs][qte_telecmd_rad_1canal][id_champ] = 'qte_telecmd_rad_1canal';
$champs[qte_telecmd_rad_1canal][libelle] = 'Qte T�l�commande Radio 1 Canal';
$champs[qte_telecmd_rad_1canal][type] = 'ouvert';
$champs[qte_telecmd_rad_1canal][longueur] = 4;
$champs[qte_telecmd_rad_1canal][largeur] = 2/5;
$champs[qte_telecmd_rad_1canal][title] = 'Saisir la quantit�';
$datasets[produitentetemoto2][champs][qte_telecmd_rad_1canal][saute_avant][1] = 1;
$datasets[produitentetemoto2][champs][qte_telecmd_rad_1canal][saute_avant][2] = 1;
$datasets[produitentetemoto2][champs][qte_telecmd_rad_1canal][saute_avant][3] = 1;

$datasets[produitentetemoto2][champs][qte_telecmd_radio_4canaux][id_champ] = 'qte_telecmd_radio_4canaux';
$champs[qte_telecmd_radio_4canaux][libelle] = 'Qte T�l�commande Radio 4 Canaux';
$champs[qte_telecmd_radio_4canaux][type] = 'ouvert';
$champs[qte_telecmd_radio_4canaux][longueur] = 4;
$champs[qte_telecmd_radio_4canaux][largeur] = 2/5;
$champs[qte_telecmd_radio_4canaux][title] = 'Saisir la quantit�';
$datasets[produitentetemoto2][champs][qte_telecmd_radio_4canaux][saute_avant][1] = 2;
$datasets[produitentetemoto2][champs][qte_telecmd_radio_4canaux][saute_avant][2] = 2;
$datasets[produitentetemoto2][champs][qte_telecmd_radio_4canaux][saute_avant][3] = 2;

$datasets[produitentetemoto2][checkvalidate]='checkvalidate';
?>