<?php
$datasets["OF - SLV - RAILS - SEULS"][presentation] = "l";

$datasets["OF - SLV - RAILS - SEULS"][padding] = 3;
$datasets['OF - SLV - RAILS - SEULS'][nonmodifiable] = 1;

$datasets['OF - SLV - RAILS - SEULS'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - SLV - RAILS - SEULS'][champs][repere][largeur] = 2;
$datasets["OF - SLV - RAILS - SEULS"][champs][repere][pdf_fontsize_entete] = 2;
$datasets["OF - SLV - RAILS - SEULS"][champs][typecommande_slv][id_champ] = "COMMANDE";
$datasets["OF - SLV - RAILS - SEULS"][champs][typecommande_slv][largeur] = 4/3;
$datasets["OF - SLV - RAILS - SEULS"][champs][typecommande_slv][pdf_fontsize_entete] = 2;
$datasets["OF - SLV - RAILS - SEULS"][champs][cotecommande_slv][id_champ] = "COTE DE \n COMMANDE \n ou MOTEUR";
$datasets["OF - SLV - RAILS - SEULS"][champs][largeur_store_slv_of][id_champ] = "LARGEUR STORE \n (en m)";
$datasets["OF - SLV - RAILS - SEULS"][champs][longueur_rail_slv_of][id_champ] = "LONGUEUR RAIL \n (en m)";
$datasets["OF - SLV - RAILS - SEULS"][champs][longueur_axe_slv_of][id_champ] = "LONGUEUR AXE \n (en m)";
$datasets["OF - SLV - RAILS - SEULS"][champs][nbcharriots_slv_of][id_champ] = "NOMBRE \n DE \n CHARRIOTS";
$datasets["OF - SLV - RAILS - SEULS"][champs][typeouv_slv_of][id_champ] = "REFOULEMENT \n DES LAMES \n (type ouverture)";
$datasets["OF - SLV - RAILS - SEULS"][champs][long_chaine_trans][id_champ] = "LONGUEUR CHAINE TRANSMISSION";
$champs[long_chaine_trans][type] = 'ouvert';
$datasets["OF - SLV - RAILS - SEULS"][champs][longchainette_slv_of][id_champ] = "LONGUEUR CHAÎNETTE";
$datasets["OF - SLV - RAILS - SEULS"][champs][longcordon_slv_of][id_champ] = "LONGUEUR CORDON";
$datasets["OF - SLV - RAILS - SEULS"][champs][coloris_rail_slv][id_champ] = "COLORIS RAIL";
$datasets["OF - SLV - RAILS - SEULS"][champs][coloris_rail_slv][fonction_init] = "get_coloris_rail_slv";
$datasets["OF - SLV - RAILS - SEULS"][champs][typepose_slv_of][id_champ] = "TYPE DE POSE";
$datasets["OF - SLV - RAILS - SEULS"][champs][typepose_slv_of][largeur] = 3/2;
$datasets["OF - SLV - RAILS - SEULS"][champs][qte_slv_of][id_champ] = "QTE";
?>
