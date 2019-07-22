<?php
$datasets["OF - SLV - RAILS"][presentation] = "l";

//$datasets['OF - SLV - RAILS'][champs] [repere][id_champ] = 'repere';

$datasets["OF - SLV - RAILS"][padding] = 3;
$datasets['OF - SLV - RAILS'][nonmodifiable] = 1;
$datasets['OF - SLV - RAILS'][champs][repere][id_champ] = 'REPERE';
$datasets["OF - SLV - RAILS"][champs][repere][pdf_fontsize_entete] = 2;
$datasets['OF - SLV - RAILS'][champs][repere][largeur] = 2;
$datasets["OF - SLV - RAILS"][champs][coloris_slv_rails][id_champ] = "COLORIS";
$datasets["OF - SLV - RAILS"][champs][coloris_slv_rails][pdf_fontsize_entete] = 2;
$datasets["OF - SLV - RAILS"][champs][typecommande_slv][id_champ] = "COMMANDE";
$datasets["OF - SLV - RAILS"][champs][typecommande_slv][pdf_fontsize_entete] = 2/3;
$datasets["OF - SLV - RAILS"][champs][cotecommande_slv][id_champ] = "COTE DE COMMANDE \n ou MOTEUR";
$datasets["OF - SLV - RAILS"][champs][largeur_store_slv_of][id_champ] = "LARGEUR STORE \n (en m)";
$datasets["OF - SLV - RAILS"][champs][longueur_rail_slv_of][id_champ] = "LONGUEUR RAIL \n (en m)";
$datasets["OF - SLV - RAILS"][champs][longueur_axe_slv_of][id_champ] = "LONGUEUR AXE \n (en m)";
$datasets["OF - SLV - RAILS"][champs][nbcharriots_slv_of][id_champ] = "NOMBRE \n DE \n CHARRIOTS";
$datasets["OF - SLV - RAILS"][champs][typeouv_slv_of][id_champ] = "REFOULEMENT DES LAMES (type ouverture)";
$datasets["OF - SLV - RAILS"][champs][long_chaine_trans][id_champ] = "LONGUEUR CHAINE TRANSMISSION";
$champs[long_chaine_trans][type] = 'ouvert';
$champs[long_chaine_trans][fonction] = "formate_troiszeroapresvirgule";
$datasets["OF - SLV - RAILS"][champs][longchainette_slv_of][id_champ] = "LONGUEUR CHAÎNETTE";
$datasets["OF - SLV - RAILS"][champs][longcordon_slv_of][id_champ] = "LONGUEUR CORDON";
$datasets["OF - SLV - RAILS"][champs][coloris_rail_slv][id_champ] = "COLORIS RAIL";
$datasets["OF - SLV - RAILS"][champs][coloris_rail_slv][fonction_init] = "get_coloris_rail_slv";
$datasets["OF - SLV - RAILS"][champs][typepose_slv_of][id_champ] = "TYPE DE POSE";
$datasets["OF - SLV - RAILS"][champs][typepose_slv_of][pdf_fontsize] = -2;
$datasets["OF - SLV - RAILS"][champs][qte_slv_of][id_champ] = "QTE";
?>
