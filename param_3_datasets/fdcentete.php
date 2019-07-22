<?php
$datasets[fdcentete][presentation] = 3;

// ATTENTION : Doit rester avant les champs qui dépendent de l'origine...
$datasets[fdcentete][champs][origine][id_champ] = 'Origine';
$datasets[fdcentete][champs][affaire][id_champ] = "Nom de l'affaire";
$datasets[fdcentete][champs][affaire][origines_invisibles] = "|1|2|4";
$datasets[fdcentete][champs][nomclient_fdc][id_champ] = 'nomclient_fdc';

// Departement
$datasets[fdcentete][champs][code_departement][id_champ] = 'code_departement';
$datasets[fdcentete][champs][code_departement][origines_invisibles]="|1|2|3|4";

// Prestation - Nom du client - Nom de l'affaire
$datasets[fdcentete][champs][prestation][id_champ] = 'Type de prestation';
$datasets[fdcentete][champs][prestation][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][numdevis][id_champ] = 'N° de devis';
$datasets[fdcentete][champs][numdevis][origines_invisibles]="|1|2|4";

// Nom chantier 
$datasets[fdcentete][champs][nomchantier][obligatoire] = true;
$datasets[fdcentete][champs][nomchantier][id_champ] = 'Nom du chantier';
$datasets[fdcentete][champs][nomchantier][origines_invisibles]="|1|2|4";

// Agence 
$datasets[fdcentete][champs][agence][id_champ] = 'Agence';

// Numero de commande
$datasets[fdcentete][champs][numcommande_fdc][id_champ] = 'Référence Commande Client';

// Adresse Chantier 
$datasets[fdcentete][champs][adresse][id_champ] = 'Adresse chantier';
$datasets[fdcentete][champs][adresse][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][commercial][id_champ] = 'Nom du commercial';
$datasets[fdcentete][champs][commercial][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][date_creation][id_champ] = 'Date de création';

// Interlocuteur 
$datasets[fdcentete][champs][interlocuteur][id_champ] = 'Interlocuteur';
$datasets[fdcentete][champs][interlocuteur][origines_invisibles]="|1|2|4";

//	Statut 
$datasets[fdcentete][champs][statut][id_champ] = 'Statut';
//$datasets[fdcentete][champs][statut][attribut_html] = 'onchange = "document.forms[0].submit();"';
$datasets[fdcentete][champs][statut][attribut_html] = 'onchange = "alert_statut_fdc(this);"';
$datasets[fdcentete][champs][statut][envoyer_mail][regles] = 'if(req_sim ("SELECT statut_commande as valeur FROM zdataset_commandeentete WHERE id_dataset = ".$doc[id_dataset_entete]." , "valeur") == 1 )';

// Date de validation de la feuille de cotes
$datasets[fdcentete][champs][date_vld_fdc][id_champ] = 'Date de validation';

$datasets[fdcentete][champs][date_vld_fdc][origines_invisibles] = "|1|2|4";

// Tel interlocuteur
$datasets[fdcentete][champs][telephone][id_champ] = 'Tel interlocuteur ';
$datasets[fdcentete][champs][telephone][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][num_fdc][id_champ] = 'Numéro de feuille de cotes';

// Date de commande
$datasets[fdcentete][champs][date_cde][id_champ] = 'Date de commande';

// Mob interlocuteur 
$datasets[fdcentete][champs][mobile][id_champ] = 'Mobile interlocuteur';
$datasets[fdcentete][champs][mobile][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][prix_ht][id_champ] = 'Montant HT';
$datasets[fdcentete][champs][prix_ht][origines_invisibles] = "|1|2|4";
$datasets[fdcentete][champs][prix_ht][obligatoire] = true;
$champs[prix_ht][type]     = 'ouvert';
$champs[prix_ht][fonction] = 'formate_deuxzeroapresvirgule';
$champs[prix_ht][isnumeric] = 1;
$champs[prix_ht][obligatoire] = 1;

// Date d'import
$datasets[fdcentete][champs][date_import][id_champ] = 'Date d import';
$champs[date_import][type] = 'date';
$champs[date_import][nonmodifiable] = 1;

// Fax interlocuteur
$datasets[fdcentete][champs][fax][id_champ] = 'Fax interlocuteur';
$datasets[fdcentete][champs][fax][origines_invisibles]="|1|2|4";

$datasets[fdcentete][champs][pose_ht][id_champ] = 'Dont pose HT';
$datasets[fdcentete][champs][pose_ht][origines_invisibles] = "|1|2|4";
//$datasets[fdcentete][champs][pose_ht][obligatoire] = true;
$champs[pose_ht][type]     = 'ouvert';
$champs[pose_ht][fonction] = 'formate_deuxzeroapresvirgule';
$champs[pose_ht][isnumeric] = 1;
//$champs[pose_ht][obligatoire] = 1;

$datasets[fdcentete][champs][date_exp][id_champ] = "Date d'expédition";
$champs[date_exp][nonmodifiable] = (($_SESSION[id_profil] == 1) or
												($_SESSION[id_profil] == 2) or
												($_SESSION[id_profil] == 4) or
												($_SESSION[id_profil] == 5));
// Visible que pour Camif
$champs[date_exp][type] = 'date';
$champs[date_exp][title] = 'Saisir la date';
$champs[date_exp][fonction] = 'dateenfrancais';
//$datasets[fdcentete][champs][date_exp][origines_invisibles]="|3";

// Mail interlocuteur - Num BCC - Statut
$datasets[fdcentete][champs][mailinterlocuteur][id_champ] = 'Mail interlocuteur';
//$datasets[fdcentete][champs][mailinterlocuteur][saute_avant][3] = 1;
$datasets[fdcentete][champs][mailinterlocuteur][origines_invisibles] = "|1|2|4";
$datasets[fdcentete][champs][mailinterlocuteur][obligatoire] = true;

$datasets[fdcentete][champs][phase][id_champ] = 'Phase';
$datasets[fdcentete][champs][phase][origines_invisibles] = "|1|2|4";
$champs[phase][type]     = 'ouvert';

$datasets[fdcentete][champs][reference][id_champ] = 'Référence';
$datasets[fdcentete][champs][reference][origines_invisibles] = "|1|2|4";
$champs[reference][type]     = 'ouvert';



$datasets[fdcentete][checkvalidate]='checkvalidate_fdcentete';

$datasets[fdcentete][groupelookup][id_lookup] = 'affaire';

?>