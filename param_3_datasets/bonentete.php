<?php

$datasets[bonentete][presentation] = 2;
$datasets[bonentete][nonmodifiable] = 1;

$datasets[bonentete][champs][origine][id_champ]   = 'origine';
$champs[origine][non_html] = 1;

$datasets[bonentete][presentation] = 3;

// LIGNE 1

$datasets[bonentete][champs][prestation][id_champ] = 'Type de prestation';

$datasets[bonentete][champs][nomclient_fdc][id_champ] = 'nomclient_fdc';

// Interlocuteur
$datasets[bonentete][champs][interlocuteur][id_champ] = 'Interlocuteur';

// LIGNE 2

//Commercial
$datasets[bonentete][champs][commercial][id_champ] = 'Nom du commercial';

// Nom chantier
$datasets[bonentete][champs][nomchantier][id_champ] = 'Nom du chantier';

// Tel interlocuteur
$datasets[bonentete][champs][telephone][id_champ] = 'Tel interlocuteur ';

// LIGNE 3

// Agence
$datasets[bonentete][champs][agence][id_champ] = 'Agence';

// Adresse Chantier
$datasets[bonentete][champs][adresse][id_champ] = 'Adresse chantier';

// Mob interlocuteur
$datasets[bonentete][champs][mobile][id_champ] = 'Mobile interlocuteur';

// LIGNE 4

//$datasets[bonentete][champs][numdevis][id_champ] = 'N° de devis';

$datasets[bonentete][champs][phase][id_champ] = 'Phase';
$champs[phase][type]     = 'ouvert';

$datasets[bonentete][champs][reference][id_champ] = 'Référence';
$champs[reference][type]     = 'ouvert';


// Fax interlocuteur
$datasets[bonentete][champs][fax][id_champ] = 'Fax interlocuteur';
//$datasets[bonentete][champs][fax][saute_avant][3] = 2;

$datasets[bonentete][champs][fdc][id_champ] = 'fdc';

// Mail interlocuteur - Num BCC - Statut
$datasets[bonentete][champs][mailinterlocuteur][id_champ] = 'Mail interlocuteur';
$datasets[bonentete][champs][mailinterlocuteur][saute_avant][3] = 1;

$datasets[bonentete][champs][affaire][id_champ] = "Nom de l'affaire";
$champs[affaire][non_html] = 1;


$champs[synthese_produit][non_pdf] = 1;
$champs[synthese_produit][non_html] = 1;
$datasets[bonentete][champs][synthese_produit][id_champ] = "Synthese";

$datasets[bonentete][groupelookup][id_lookup] = 'affaire';

$datasets[bonentete][champs][date_cde][id_champ] = 'date_cde';
$champs[date_cde][non_html] = 1;

$datasets[bonentete][champs][numcommande_fdc_of][id_champ] = 'numcommande_fdc_of';
$champs[numcommande_fdc_of][type] = "ouvert";
$champs[numcommande_fdc_of][non_html] = 1;

$datasets[bonentete][champs][statut_of][id_champ] = 'statut_of';
$champs[statut_of][non_html] = 1;

$datasets[bonentete][champs][date_creation][id_champ]    = 'date_creation';
$datasets[bonentete][champs][date_creation][fonction_init] = 'aujourdhui';
$champs[date_creation][non_html] = 1;

?>