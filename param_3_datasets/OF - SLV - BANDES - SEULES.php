<?php
$datasets["OF - SLV - BANDES - SEULES"][presentation] = "l";

$datasets["OF - SLV - BANDES - SEULES"][padding] = 3;
$datasets["OF - SLV - BANDES - SEULES"][nonmodifiable] = 1;

$datasets['OF - SLV - BANDES - SEULES'][champs][repere][id_champ] = 'REPERE';
$datasets["OF - SLV - BANDES - SEULES"][champs][repere][pdf_fontsize_entete] = 3;
$datasets["OF - SLV - BANDES - SEULES"][champs][repere][fonction_init] = "get_repere";

$datasets["OF - SLV - BANDES - SEULES"][champs][coloris_slv_rails][id_champ] = "COLORIS ";
$datasets["OF - SLV - BANDES - SEULES"][champs][coloris_slv_rails][pdf_fontsize_entete] = 3;
$datasets["OF - SLV - BANDES - SEULES"][champs][coloris_slv_rails][fonction_init] = "get_coloris_slv_rails";

$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_lame_slv][id_champ] = "hauteur_lame_slv";
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_lame_slv][pdf_fontsize_entete] = 3;
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_lame_slv][fonction_init] = "get_hauteur_lame_seule_slv";
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_lame_slv][lb_champ] = 'HAUTEUR LAME (en m)';
$champs[hauteur_lame_slv][type] = 'ouvert';
//$champs[hauteur_lame_slv][obligatoire] = '1';

$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_store_slv_of][id_champ] = "hauteur_store_slv_of";
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_store_slv_of][pdf_fontsize_entete] = 3;
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_store_slv_of][fonction_init] = "get_hauteur_slv_pour_lame_seule_slv";
$datasets["OF - SLV - BANDES - SEULES"][champs][hauteur_store_slv_of][lb_champ] = 'HAUTEUR STORE (CALCULE) (en m)';

$datasets["OF - SLV - BANDES - SEULES"][champs][qte][id_champ] = "qte";
$datasets["OF - SLV - BANDES - SEULES"][champs][qte][pdf_fontsize_entete] = 3;
$datasets["OF - SLV - BANDES - SEULES"][champs][qte][fonction_init] = "get_qte_lames_seules";
$datasets["OF - SLV - BANDES - SEULES"][champs][qte][lb_champ] = "NOMBRE DE BANDES";

$champs[qte][type] = 'ouvert';
?>