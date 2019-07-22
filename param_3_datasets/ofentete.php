<?php
$datasets[ofentete][presentation] = 2;
$datasets[ofentete][nonmodifiable] = 1;

/*******************
Champs
*******************/

$datasets[ofentete][champs][fdc][id_champ] = 'fdc';

$datasets[ofentete][champs][nomclient_fdc][id_champ] = 'nomclient_fdc';

$datasets[ofentete][champs][numcommande_fdc_of][id_champ] = 'numcommande_fdc_of';

$datasets[ofentete][champs][date_cde][id_champ]        = 'date_cde';
$datasets[ofentete][champs][date_cde][fonction_init] = 'get_date_cde';

$datasets[ofentete][champs][statut_of][id_champ]          = 'statut_of';
$datasets[ofentete][champs][statut_of][attribut_html] =  'onchange = "document.forms[0].submit(); "';


$datasets[ofentete][champs][date_exp][id_champ]    = 'date_exp';
$datasets[ofentete][champs][date_exp][fonction_init] = 'get_date_exp';

$datasets[ofentete][champs][date_creation][id_champ]    = 'date_creation';
$datasets[ofentete][champs][date_creation][fonction_init] = 'aujourdhui';

$datasets[ofentete][champs][origine][id_champ]         = 'origine';

$datasets[ofentete][champs][agence][id_champ] = 'agence';

$datasets[ofentete][champs][prestation][id_champ] = 'Type de prestation';

$datasets[ofentete][champs][nomchantier][id_champ] = 'Nom du chantier';

$datasets[ofentete][champs][depchantier][id_champ] = 'Département Chantier';
$datasets[ofentete][champs][depchantier][fonction_init] = 'get_departement';
$champs[depchantier][type] = 'ouvert';
$champs[depchantier][longueur] = 20;

$datasets[ofentete][champs][phase][id_champ] = 'Phase';
$champs[phase][type]     = 'ouvert';

$datasets[ofentete][champs][reference][id_champ] = 'Référence';
$champs[reference][type]     = 'ouvert';

$datasets[ofentete][champs][commentaires_fabrication][id_champ] = 'commentaires_fabrication';

$datasets[ofentete][champs][produit_entete_of][id_champ]  = 'produit_entete_of';
$champs[produit_entete_of][type] = 'ouvert';
$champs[produit_entete_of][longueur] = 20;
$champs[produit_entete_of][non_html] = 1;


?>