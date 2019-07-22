<?php
$datasets["OF - ENROULEUR - MECANISME"][presentation] = "l";

$datasets["OF - ENROULEUR - MECANISME"][padding] = 3;
$datasets['OF - ENROULEUR - MECANISME'][nonmodifiable] = 1;

$datasets['OF - ENROULEUR - MECANISME'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - ENROULEUR - MECANISME'][champs][repere][largeur] = 3;
$datasets["OF - ENROULEUR - MECANISME"][champs][repere][pdf_fontsize_entete] = 4;
$datasets["OF - ENROULEUR - MECANISME"][champs][coloris_enrouleurmeca_of][id_champ] = "COLORIS ";
$datasets["OF - ENROULEUR - MECANISME"][champs][coloris_enrouleurmeca_of][pdf_fontsize_entete] = 4;
$datasets["OF - ENROULEUR - MECANISME"][champs][largeur_enrouleurmeca_of][id_champ] = "LARGEUR STORE \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][hauteur_enrouleurmeca_of][id_champ] = "HAUTEUR STORE \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][cotecde_enrouleurmeca_of][id_champ] = "COTE DE COMMANDE";
$datasets["OF - ENROULEUR - MECANISME"][champs][laxe_enrouleurmeca_of][id_champ] = "LARGEUR AXE ENROULEMENT \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][lchainette_enrouleurmeca_of][id_champ] = "LONGUEUR CHAÎNETTE";
$datasets["OF - ENROULEUR - MECANISME"][champs][typepose_enrouleurmeca_of][id_champ] = "TYPE DE POSE - EQUERRES";
$datasets["OF - ENROULEUR - MECANISME"][champs][typepose_enrouleurmeca_of][largeur] = 2;
$datasets["OF - ENROULEUR - MECANISME"][champs][typecommande_enrouleur][id_champ] = 'TYPE DE COMMANDE';
$datasets["OF - ENROULEUR - MECANISME"][champs][typecommande_enrouleur][fonction_init] = 'get_typecommande_enrouleur_of';
$datasets["OF - ENROULEUR - MECANISME"][champs][coloris_mecanismes_enrouleur][id_champ] = "COLORIS MECANISMES";
$datasets["OF - ENROULEUR - MECANISME"][champs][coloris_mecanismes_enrouleur][fonction_init] = "get_coloris_mecanismes_enrouleur_of";
$datasets["OF - ENROULEUR - MECANISME"][champs][guidage_enrouleurmeca_of][id_champ] = "GUIDAGE ";
$datasets["OF - ENROULEUR - MECANISME"][champs][guidage_enrouleurmeca_of][pdf_fontsize_entete] = 2;
$datasets["OF - ENROULEUR - MECANISME"][champs][bcharge_enrouleurmeca_of][id_champ] = "BARRE\nDE\n CHARGE \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][parriere_enrouleurmeca_of][id_champ] = "PLAQUE ARRIERE \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][jonc_enrouleurmeca_of][id_champ] = "JONC BARRE DE CHARGE \n(en m)";
$datasets["OF - ENROULEUR - MECANISME"][champs][qtemeca_enrouleur_of][id_champ] = "QUANTITE";
$datasets["OF - ENROULEUR - MECANISME"][champs][enroulement_des_toile_of][id_champ] = "enroulement_des_toile_of";
$champs[enroulement_des_toile_of][libelle] = 'Enroulement des Toiles';
$champs[enroulement_des_toile_of][type] = 'ouvert';

?>