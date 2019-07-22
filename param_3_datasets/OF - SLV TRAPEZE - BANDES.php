<?php
$datasets["OF - SLV TRAPEZE - BANDES"][presentation] = "l";

$datasets['OF - SLV TRAPEZE - BANDES'][champs] [repere][id_champ] = 'repere';

$datasets["OF - SLV TRAPEZE - BANDES"][padding] = 3;
$datasets['OF - SLV TRAPEZE - BANDES'][nonmodifiable] = 1;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][petite_hauteur_slv][id_champ] = "Petite hauteur ";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][petite_hauteur_slv][pdf_fontsize_entete] = 3;
$champs[petite_hauteur_slv][libelle] = 'Petite hauteur';
$champs[petite_hauteur_slv][type] = 'ouvert';
//$champs[petite_hauteur_slv][obligatoire] = 1;
$champs[petite_hauteur_slv][title] = 'Saisir la petite hauteur';


$datasets["OF - SLV TRAPEZE - BANDES"][champs][grande_hauteur_slv][id_champ] = "Grande hauteur ";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][grande_hauteur_slv][pdf_fontsize_entete] = 3;
$champs[grande_hauteur_slv][libelle] = 'Grande hauteur';
$champs[grande_hauteur_slv][type] = 'ouvert';
//$champs[grande_hauteur_slv][obligatoire] = 1;
$champs[grande_hauteur_slv][title] = 'Saisir la grande hauteur';

$datasets["OF - SLV TRAPEZE - BANDES"][champs][largeur_slv][id_champ] = "Base ";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][largeur_slv][pdf_fontsize_entete] = 3;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][coloris_slv_rails][id_champ] = "COLORIS ";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][coloris_slv_rails][pdf_fontsize_entete] = 3;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][hauteurs_bandes_slv_of][lb_champ] = "HAUTEURS DES BANDES (MM)";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][hauteurs_bandes_slv_of][pdf_fontsize_entete] = 3;
$datasets["OF - SLV TRAPEZE - BANDES"][champs][hauteurs_bandes_slv_of][largeur] = 4;

$champs[hauteurs_bandes_slv_of][type] = "ouvert";

$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbbandes_slv_of][id_champ] = "NOMBRE DE BANDES";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbbandes_slv_of][pdf_fontsize_entete] = 3;
$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbbandes_slv_of][largeur] = 1;
//$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbbandes_slv_of][pdf_fontsize] = 2;
//$champs[nbbandes_slv_of][largeur] = 0;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][typepose_slv_of][id_champ] = "TYPE DE POSE - EQUERRES";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][typepose_slv_of][pdf_fontsize_entete] = 3;
$datasets["OF - SLV TRAPEZE - BANDES"][champs][typepose_slv_of][pdf_fontsize] = -1;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbsupports_slv_of][id_champ] = "NOMBRE DE SUPPORTS / EQUERRES";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][nbsupports_slv_of][pdf_fontsize_entete] = 1;

$datasets["OF - SLV TRAPEZE - BANDES"][champs][qte_slv_of][id_champ] = "QTE";
$datasets["OF - SLV TRAPEZE - BANDES"][champs][qte_slv_of][pdf_fontsize_entete] = 2;

//$datasets["OF - SLV - BANDES"][champs][conso][id_champ] = "CONSOMMATION";
//$datasets["OF - SLV - BANDES"][champs][conso][pdf_fontsize_entete] = 3;
//$datasets["OF - SLV - BANDES"][champs][conso][fonction_init] = "get_conso_slv_of";
//$datasets["OF - SLV - BANDES"][champs][conso][lb_champ] = 'Consommation';
//$champs[conso][type] = 'ouvert';
//$champs[conso][largeur] = 0.8;

?>