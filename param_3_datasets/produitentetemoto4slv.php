<?php
include("param_3_datasets/produitentetegenerique.php");
$datasets[produitentetemoto4slv] = $datasets[produitentetegenerique];


/*$datasets[produitentetemoto4slv][champs][moto4][id_champ] = 'Type de Commande Motorisée';
$champs[moto4][libelle] = 'Type de Commande Motorisée';
$champs[moto4][type] = 'lov';
$champs[moto4][tablecible] = ' select "-" as id_article, "Pas de commande" as lb_article union select id_article, lb_article from article where selecteur = "moto2" or selecteur = "moto4" ';
$champs[moto4][champstocke] = 'id_article';
$champs[moto4][champscible] = 'lb_article';
$datasets[produitentetemoto4slv][champs][quantite_e][id_champ] = 'Quantité';
$champs[quantite_e][libelle] = 'Qté';
$champs[quantite_e][type] = 'ouvert';
$champs[quantite_e][longueur] = 4;
$champs[quantite_e][largeur] = 2/5;
$champs[quantite_e][title] = 'Saisir la quantité'; */




$datasets[produitentetemoto4slv][champs][qte_inter_radio_1canal][id_champ] = 'qte_inter_radio_1canal';
$champs[qte_inter_radio_1canal][libelle] = 'Qte Interrupteur Radio 1 Canal';
$champs[qte_inter_radio_1canal][type] = 'ouvert';
$champs[qte_inter_radio_1canal][longueur] = 4;
$champs[qte_inter_radio_1canal][largeur] = 2/5;
$champs[qte_inter_radio_1canal][title] = 'Saisir la quantité';
$datasets[produitentetemoto4slv][champs][qte_inter_radio_1canal][saute_avant][1] = 1;
$datasets[produitentetemoto4slv][champs][qte_inter_radio_1canal][saute_avant][2] = 1;
$datasets[produitentetemoto4slv][champs][qte_inter_radio_1canal][saute_avant][3] = 1;

$datasets[produitentetemoto4slv][champs][qte_inter_radio_5canaux][id_champ] = 'qte_inter_radio_5canaux';
$champs[qte_inter_radio_5canaux][libelle] = 'Qte Interrupteur Radio 5 Canaux';
$champs[qte_inter_radio_5canaux][type] = 'ouvert';
$champs[qte_inter_radio_5canaux][longueur] = 4;
$champs[qte_inter_radio_5canaux][largeur] = 2/5;
$champs[qte_inter_radio_5canaux][title] = 'Saisir la quantité';
$datasets[produitentetemoto4slv][champs][qte_inter_radio_5canaux][saute_avant][1] = 2;
$datasets[produitentetemoto4slv][champs][qte_inter_radio_5canaux][saute_avant][2] = 2;
$datasets[produitentetemoto4slv][champs][qte_inter_radio_5canaux][saute_avant][3] = 2;

$datasets[produitentetemoto4slv][champs][qte_telecmd_rad_1canal][id_champ] = 'qte_telecmd_rad_1canal';
$champs[qte_telecmd_rad_1canal][libelle] = 'Qte Télécommande Radio 1 Canal';
$champs[qte_telecmd_rad_1canal][type] = 'ouvert';
$champs[qte_telecmd_rad_1canal][longueur] = 4;
$champs[qte_telecmd_rad_1canal][largeur] = 2/5;
$champs[qte_telecmd_rad_1canal][title] = 'Saisir la quantité';
$datasets[produitentetemoto4slv][champs][qte_telecmd_rad_1canal][saute_avant][1] = 2;
$datasets[produitentetemoto4slv][champs][qte_telecmd_rad_1canal][saute_avant][2] = 2;
$datasets[produitentetemoto4slv][champs][qte_telecmd_rad_1canal][saute_avant][3] = 2;

$datasets[produitentetemoto4slv][champs][qte_telecmd_radio_5canaux][id_champ] = 'qte_telecmd_radio_5canaux';
$champs[qte_telecmd_radio_5canaux][libelle] = 'Qte Télécommande Radio 5 Canaux';
$champs[qte_telecmd_radio_5canaux][type] = 'ouvert';
$champs[qte_telecmd_radio_5canaux][longueur] = 4;
$champs[qte_telecmd_radio_5canaux][largeur] = 2/5;
$champs[qte_telecmd_radio_5canaux][title] = 'Saisir la quantité';
$datasets[produitentetemoto4slv][champs][qte_telecmd_radio_5canaux][saute_avant][1] = 2;
$datasets[produitentetemoto4slv][champs][qte_telecmd_radio_5canaux][saute_avant][2] = 2;
$datasets[produitentetemoto4slv][champs][qte_telecmd_radio_5canaux][saute_avant][3] = 2;



$datasets[produitentetemoto4slv][checkvalidate]='checkvalidate';
?>