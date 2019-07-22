<?php

$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][presentation] = 'l';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][padding] = 3;
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][nonmodifiable] = 1;

$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][repere][startligne] = 1;
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][repere][lb_champ_pdf] = "Repère";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][repere][largeur] = "2";

// $datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris][id_champ] = 'COLORIS';
// $datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris][lb_champ_pdf] = "Coloris";
// $datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris][fonction_init] = "get_coloris";
// $datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris][largeur] = "2";

$champs[largeur_store][libelle] = 'Largeur store(en m)';
$champs[largeur_store][type] = 'ouvert';
$champs[largeur_store][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_store][id_champ] = 'LARGEUR STORE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_store][lb_champ_pdf] = "Largeur store(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_store][fonction_init] = "get_largeur_store";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_store][largeur] = "2";

$champs[hauteur_store][libelle] = 'Hauteur store(en m)';
$champs[hauteur_store][type] = 'ouvert';
$champs[hauteur_store][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_store][id_champ] = 'HAUTEUR STORE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_store][lb_champ_pdf] = "Hauteur store(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_store][fonction_init] = "get_hauteur_store";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_store][largeur] = "2";

$champs[cote_cmd][libelle] = 'Coté de commande';
$champs[cote_cmd][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][cote_cmd][id_champ] = 'COTE DE COMMANDE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][cote_cmd][lb_champ_pdf] = "Coté de commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][cote_cmd][fonction_init] = "get_cote_cmde";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][cote_cmd][largeur] = "2";

$champs[largeur_axe_enroulement][libelle] = 'Largeur axe enroulement(en m)';
$champs[largeur_axe_enroulement][type] = 'ouvert';
$champs[largeur_axe_enroulement][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_axe_enroulement][id_champ] = 'LARGEUR AXE ENROULEMENT';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_axe_enroulement][lb_champ_pdf] = "Largeur axe enroulement(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_axe_enroulement][fonction_init] = "get_largeur_axe_enroulement";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][largeur_axe_enroulement][largeur] = "2";

$champs[hauteur_cmde][libelle] = 'Hauteur commande';
$champs[hauteur_cmde][type] = 'ouvert';
$champs[hauteur_cmde][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_cmde][id_champ] = 'HAUTEUR COMMANDE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_cmde][lb_champ_pdf] = "Hauteur commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_cmde][fonction_init] = "get_hauteur_commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][hauteur_cmde][largeur] = "2";

$champs[type_pose][libelle] = 'Type de pose';
$champs[type_pose][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_pose][id_champ] = 'TYPE POSE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_pose][lb_champ_pdf] = "Type de pose";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_pose][fonction_init] = "get_type_pose";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_pose][largeur] = "2";

$champs[type_commande][libelle] = 'Type de commande';
$champs[type_commande][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_commande][id_champ] = 'TYPE COMMANDE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_commande][lb_champ_pdf] = "Type de commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_commande][fonction_init] = "get_type_commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][type_commande][largeur] = "2";

$champs[point_cmd][libelle] = 'Point de commande';
$champs[point_cmd][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][point_cmd][id_champ] = 'POINT COMMANDE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][point_cmd][lb_champ_pdf] = "Point de commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][point_cmd][fonction_init] = "get_point_commande";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][point_cmd][largeur] = "2";;
$champs[point_cmd][libelle] = 'Type de Commande Motorisée';
$champs[point_cmd][type] = 'lov';
$champs[point_cmd][tablecible] = ' select "-" as id_article, "Pas de commande" as lb_article union select id_article, lb_article from article where selecteur = "moto2" or selecteur = "moto4" ';
$champs[point_cmd][champstocke] = 'id_article';
$champs[point_cmd][champscible] = 'lb_article';


$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris_mecanisme][id_champ] = 'Coloris Mecanisme';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coloris_mecanisme][fonction_init] = "copie|coloris_mecanisme";
$champs[coloris_mecanisme][libelle] = 'Coloris Mecanisme';
$champs[coloris_mecanisme][type] = 'lov';

$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][auvent][id_champ] = 'Auvent';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][auvent][fonction_init] = "copie|auvent";
$champs[auvent][libelle] = 'Auvent';
$champs[auvent][type] = 'lov';

$champs[guidage][libelle] = 'Guidage';
$champs[guidage][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][guidage][id_champ] = 'GUIDAGE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][guidage][lb_champ_pdf] = "Guidage";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][guidage][fonction_init] = "get_guidage";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][guidage][largeur] = "2";

$champs[coupe_guidage][libelle] = 'Coupe guidage';
$champs[coupe_guidage][type] = 'ouvert';
$champs[coupe_guidage][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coupe_guidage][id_champ] = 'COUPE GUIDAGE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coupe_guidage][lb_champ_pdf] = "Coupe guidage";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coupe_guidage][fonction_init] = "get_coupe_guidage";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][coupe_guidage][largeur] = "2";

$champs[sens_enroulement][libelle] = 'Sens enroulement';
$champs[sens_enroulement][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][sens_enroulement][id_champ] = 'SENS ENROULEMENT';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][sens_enroulement][lb_champ_pdf] = "Sens enroulement";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][sens_enroulement][fonction_init] = "get_sens_enroulement";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][sens_enroulement][largeur] = "2";

$champs[barre_de_charge][libelle] = 'Barre de charge';
$champs[barre_de_charge][type] = 'ouvert';
$champs[barre_de_charge][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][barre_de_charge][id_champ] = 'BARRE DE CHARGE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][barre_de_charge][lb_champ_pdf] = "Barre de charge";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][barre_de_charge][fonction_init] = "get_barre_de_charge";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][barre_de_charge][largeur] = "2";

$champs[jonc_barre_de_charge][libelle] = 'Jonc barre de charge(en m)';
$champs[jonc_barre_de_charge][type] = 'ouvert';
$champs[jonc_barre_de_charge][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][jonc_barre_de_charge][id_champ] = 'JONC BARRE DE CHARGE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][jonc_barre_de_charge][lb_champ_pdf] = "Jonc barre de charge(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][jonc_barre_de_charge][fonction_init] = "get_jonc_barre_de_charge";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][jonc_barre_de_charge][largeur] = "2";

$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][quantite][id_champ] = 'QUANTITE';
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][quantite][lb_champ_pdf] = "Quantité";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][quantite][fonction_init] = "get_quantite";
$datasets['OF - ENROULEURS - EXTERIEURS - MECANISMES'][champs][quantite][largeur] = "2";

?>