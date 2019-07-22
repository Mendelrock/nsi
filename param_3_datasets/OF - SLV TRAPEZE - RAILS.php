<?php
$datasets["OF - SLV TRAPEZE - RAILS"][presentation] = "l";

$datasets['OF - SLV TRAPEZE - RAILS'][champs] [repere][id_champ] = 'repere';

$datasets["OF - SLV TRAPEZE - RAILS"][padding] = 3;
$datasets['OF - SLV TRAPEZE - RAILS'][nonmodifiable] = 1;

$datasets["OF - SLV TRAPEZE - RAILS"][champs][petite_hauteur_slv][id_champ] = "Petite hauteur";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][petite_hauteur_slv][pdf_fontsize_entete] = 2;
$champs[petite_hauteur_slv][libelle] = ' Petite hauteur ';
$champs[petite_hauteur_slv][type] = 'ouvert';
//$champs[petite_hauteur_slv][obligatoire] = 1;
$champs[petite_hauteur_slv][title] = 'Saisir la petite hauteur';

$datasets["OF - SLV TRAPEZE - RAILS"][champs][grande_hauteur_slv][id_champ] = "Grande hauteur";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][grande_hauteur_slv][pdf_fontsize_entete] = 2;
$champs[grande_hauteur_slv][libelle] = 'Grande hauteur';
$champs[grande_hauteur_slv][type] = 'ouvert';
// $champs[grande_hauteur_slv][obligatoire] = 1;
$champs[grande_hauteur_slv][title] = 'Saisir la grande hauteur';

$datasets["OF - SLV TRAPEZE - RAILS"][champs][largeur_slv][id_champ] = "Base";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][largeur_slv][pdf_fontsize_entete] = 3;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][largeur_slv][largeur] = 1;

$datasets["OF - SLV TRAPEZE - RAILS"][champs][coloris_slv_rails][id_champ] = "COLORIS";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][coloris_slv_rails][pdf_fontsize_entete] = 2;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][coloris_slv_rails][pdf_fontsize] = -2;

$datasets["OF - SLV TRAPEZE - RAILS"][champs][typecommande_slv][id_champ] = "COMMANDE";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][typecommande_slv][pdf_fontsize_entete] = 2;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][typecommande_slv][largeur] = 2;

//$datasets["OF - SLV TRAPEZE - RAILS"][champs][cotecommande_slv][id_champ] = "COTE DE COMMANDE";
//$datasets["OF - SLV TRAPEZE - RAILS"][champs][largeur_store_slv_of][id_champ] = "LARGEUR STORE (en m)";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][longueur_rail_slv_of][id_champ] = "LONGUEUR RAIL (en m)";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][longueur_coupe_slv_of][id_champ] = "LONGUEUR COUPE RAIL ET AXE (en m)";
$champs[longueur_coupe_slv_of][type] = 'ouvert';
$champs[longueur_coupe_slv_of][fonction] = "formate_troiszeroapresvirgule";
$champs[longueur_coupe_slv_of][libelle] = "LONGUEUR COUPE RAIL ET AXE";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][nbcharriots_slv_of][id_champ] = "NOMBRE DE CHARRIOTS";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][ref_crochet_slv_of][lb_champ] = "GRIFFE";
$champs[ref_crochet_slv_of][type] = 'ouvert';

$datasets["OF - SLV TRAPEZE - RAILS"][champs][dim_ecarteur_slv_of][lb_champ] = "Dimension ecarteurs (en m)";
$champs[dim_ecarteur_slv_of][type] = 'ouvert';
$champs[dim_ecarteur_slv_of][fonction] = "formate_troiszeroapresvirgule";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][deviation_slv_of][lb_champ] = "JONC OPPOSE";
$champs[deviation_slv_of][type] = 'ouvert';
$champs[deviation_slv_of][fonction] = "formate_troiszeroapresvirgule";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][manoeuvre_slv_of][lb_champ] = "JONC CDE";
$champs[manoeuvre_slv_of][type] = 'ouvert';
$champs[manoeuvre_slv_of][fonction] = "formate_troiszeroapresvirgule";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][typeouv_slv_of][id_champ] = "REFOULEMENT DES LAMES (type ouverture)";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][typeouv_slv_of][largeur] = 1.2;

$datasets["OF - SLV TRAPEZE - RAILS"][champs][longchainette_slv_of][id_champ] = "LONGUEUR CHA�NETTE";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][longcordon_slv_of][id_champ] = "LONGUEUR CORDON";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][longcordon_slv_of][largeur] = 1.2;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][longcordon_slv_of][fonction] = "formate_troiszeroapresvirgule";

$datasets["OF - SLV TRAPEZE - RAILS"][champs][typepose_slv_of][id_champ] = "Type de pose";
$datasets["OF - SLV TRAPEZE - RAILS"][champs][typepose_slv_of][pdf_fontsize_entete] = 3;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][typepose_slv_of][largeur] = 2;

$datasets["OF - SLV TRAPEZE - RAILS"][champs][typepose_slv_of][pdf_fontsize] = -2;
$datasets["OF - SLV TRAPEZE - RAILS"][champs][qte_slv_of][id_champ] = "QTE";
?>
