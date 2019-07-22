<?php
$datasets[commandeentete][presentation] = 2;
$datasets[commandeentete][nonmodifiable] = 1;

/*******************
 * Champs
 *******************/

$datasets[commandeentete][champs][fdc][id_champ] = 'fdc';

$datasets[commandeentete][champs][nomclient_fdc][id_champ] = 'nomclient_fdc';
$datasets[commandeentete][champs][nomclient_fdc][fonction_init] = 'get_nomclient_fdc';
$datasets[commandeentete][champs][nomclient_fdc][non_html] = 1;

$datasets[commandeentete][champs][numcommande_fdc][fonction_init] = 'get_num_commande_fdc';
$datasets[commandeentete][champs][numcommande_fdc_commande][fonction_init] = 'set_num_commande_fdc_commande';
$champs[numcommande_fdc_commande][libelle] = 'N° de Commande SODICLAIR';
$champs[numcommande_fdc_commande][type] = 'label';
$champs[numcommande_fdc_commande][longueur] = 20;
$champs[numcommande_fdc_commande][nonmodifiable] = 1;

$datasets[commandeentete][champs][date_cde][fonction_init] = 'aujourdhui';

$datasets[commandeentete][champs][date_creation][fonction_init] = 'aujourdhui';

$datasets[commandeentete][champs][statut_commande][fonction_init] = 'set_statut_commande';
$datasets[commandeentete][champs][statut_commande][attribut_html] =  'onchange = "document.forms[0].submit(); "';

$datasets[commandeentete][champs][fournisseur_commande][fonction_init] = 'set_fournisseur_commande';

$datasets[commandeentete][champs][commentaire_cmd_sf][id_champ] = 'Commentaire mail';
$champs[commentaire_cmd_sf][type] = 'textarea';
$champs[commentaire_cmd_sf][non_pdf] = 1;
$champs[commentaire_cmd_sf][rows] = 3;
$champs[commentaire_cmd_sf][cols] = 40;

$champs[commentaire_fabrication_sf][type] = 'textarea';
$champs[commentaire_fabrication_sf][rows] = 3;
$champs[commentaire_fabrication_sf][cols] = 100;
$champs[commentaire_fabrication_sf][colspan] = 7;
$datasets[commandeentete][champs][commentaire_fabrication_sf][id_champ] = 'Commentaire pour fabrication';
//$datasets[commandeentete][champs][commentaire_fabrication_sf][saute_avant] = 1;


$datasets[commandeentete][champs][origine][fonction_init] = 'get_origine';
$champs[origine][type] = 'ouvert';
$champs[origine][non_html] = 1;

$datasets[commandeentete][champs][agence][id_champ] = 'agence';
$champs[agence][type] = 'ouvert';
$champs[agence][non_html] = 1;

$datasets[commandeentete][champs][produit_entete_commande][id_champ] = 'produit_entete_commande';
$champs[produit_entete_commande][type] = 'ouvert';
$champs[produit_entete_commande][longueur] = 20;
$champs[produit_entete_commande][non_html] = 1;
$champs[produit_entete_commande][type] = "label";
?>