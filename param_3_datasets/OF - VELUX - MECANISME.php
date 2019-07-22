<?php
$datasets['OF - VELUX - MECANISME'][presentation] = 'l';

//$datasets['OF - VELUX - MECANISME'][padding] = 3;
$datasets['OF - VELUX - MECANISME'][nonmodifiable] = 1;
$datasets['OF - VELUX - MECANISME'][fulltable] = 1;

$datasets['OF - VELUX - MECANISME'][champs][repere][startligne] = 1;

$datasets['OF - VELUX - MECANISME'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - VELUX - MECANISME'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - VELUX - MECANISME'][champs][repere][rowspan] = 3;
$datasets['OF - VELUX - MECANISME'][champs][repere][largeur] = 3;
$datasets['OF - VELUX - MECANISME'][champs][repere][hauteur] = 0.5;
$datasets['OF - VELUX - MECANISME'][champs][repere][pdf_fontsize] = -2 ;

$datasets['OF - VELUX - MECANISME'][champs][of_coloris][lb_champ] = "COLORIS";
$datasets['OF - VELUX - MECANISME'][champs][of_coloris][fonction_init] = "get_velux_coloris";
$datasets['OF - VELUX - MECANISME'][champs][of_coloris][rowspan] = 3;
$datasets['OF - VELUX - MECANISME'][champs][of_coloris][largeur] = 2;
$datasets['OF - VELUX - MECANISME'][champs][of_coloris][hauteur] = 0.5;

$datasets['OF - VELUX - MECANISME'][champs][of_velux_modele][lb_champ] = "CODE MODELE GROUPE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_modele][fonction_init] = "get_velux_modele";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_modele][rowspan] = 3;
$champs[of_velux_modele][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_cache_alu][lb_champ] = "LARGEUR CACHE ALU (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_cache_alu][fonction_init] = "get_velux_largeur_cache_alu";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_cache_alu][rowspan] = 2;
$champs[of_velux_largeur_cache_alu][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_tube_28][lb_champ] = "LARGEUR TUBE 28 (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_tube_28][fonction_init] = "get_velux_largeur_tube_28";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_largeur_tube_28][rowspan] = 2;
$champs[of_velux_largeur_tube_28][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_type_ressort][lb_champ] = "TYPE RESSORT";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_type_ressort][fonction_init] = "get_velux_type_ressort";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_type_ressort][rowspan] = 2;
$champs[of_velux_type_ressort][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_tours_tension][lb_champ] = "NOMBRE TOURS TENSION";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_tours_tension][fonction_init] = "get_velux_nb_tours_tension";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_tours_tension][rowspan] = 3;
$champs[of_velux_nb_tours_tension][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_support][lb_champ] = "NOMBRE DE VIS SUPPORT";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_support][fonction_init] = "get_velux_nb_vis_support";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_support][rowspan] = 2;
$champs[of_velux_nb_vis_support][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_coulisse][lb_champ] = "COULISSES";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_coulisse][colspan] = 5;
$champs[of_velux_coulisse][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_barre_de_charge][lb_champ] = "BARRE DE CHARGE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_barre_de_charge][colspan] = 2;
$champs[of_velux_barre_de_charge][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_toile][lb_champ] = "TOILE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_toile][colspan] = 4;
$champs[of_velux_toile][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_perche][lb_champ] = "Perche";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_perche][fonction_init] = "get_velux_perche";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_perche][rowspan] = 3;
$champs[of_velux_perche][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_qte][lb_champ] = "Quantité";
$datasets['OF - VELUX - MECANISME'][champs][of_qte][fonction_init] = "get_velux_qte";
$datasets['OF - VELUX - MECANISME'][champs][of_qte][rowspan] = 3;

// NOUVELLE LIGNE //

$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_coulisse][startligne] = 1;

$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_coulisse][lb_champ] = "LONGUEUR COULISSE (X2) (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_coulisse][fonction_init] = "get_velux_longueur_coulisse";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_coulisse][rowspan] = 1;
$champs[of_velux_longueur_coulisse][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_percage][lb_champ] = "NOMBRE PERAÇE PAR COULISSE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_percage][fonction_init] = "get_velux_nb_percage";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_percage][rowspan] = 2;
$champs[of_velux_nb_percage][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_percage][lb_champ] = "DISTANCE DEPUIS BAS 1° PERÇAGE (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_percage][fonction_init] = "get_velux_distance_depuis_bas_percage";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_percage][rowspan] = 2;
$champs[of_velux_distance_depuis_bas_percage][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_trou_suivant][lb_champ] = "DISTANCE AVEC TROUS SUIVANTS (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_trou_suivant][fonction_init] = "get_velux_distance_trou_suivant";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_trou_suivant][rowspan] = 2;
$champs[of_velux_distance_trou_suivant][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_pose_coulisse][lb_champ] = "NOMBRE DE VIS POSE COULISSE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_pose_coulisse][fonction_init] = "get_velux_nb_vis_pose_coulisse";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_vis_pose_coulisse][rowspan] = 1;
$champs[of_velux_nb_vis_pose_coulisse][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_bdc][lb_champ] = "LONGUEUR BDC (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_bdc][fonction_init] = "get_velux_longueur_bdc";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_bdc][rowspan] = 1;
$champs[of_velux_longueur_bdc][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_balai_bdc][lb_champ] = "LONGUEUR BALAI BDC (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_balai_bdc][fonction_init] = "get_velux_longueur_balai_bdc";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_balai_bdc][rowspan] = 1;
$champs[of_velux_longueur_balai_bdc][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_jonc_toile][lb_champ] = "LONGUEUR JONC TOILE \n(en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_jonc_toile][fonction_init] = "get_velux_longueur_jonc_toile";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_longueur_jonc_toile][rowspan] = 1;
$champs[of_velux_longueur_jonc_toile][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_opercules][lb_champ] = "NOMBRE OPERCULE\n par COTE";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_opercules][fonction_init] = "get_velux_nb_opercules";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_nb_opercules][rowspan] = 1;
$champs[of_velux_nb_opercules][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_opercile][lb_champ] = "DISTANCE DEPUIS BAS 1° OPERCULE (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_opercile][fonction_init] = "get_velux_distance_depuis_bas_opercile";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_depuis_bas_opercile][rowspan] = 2;
$champs[of_velux_distance_depuis_bas_opercile][type] 	  = 'ouvert';

$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_deuxieme_opercule][lb_champ] = "DISTANCE AVEC 2° OPERCULE (en m)";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_deuxieme_opercule][fonction_init] = "get_velux_distance_deuxieme_opercule";
$datasets['OF - VELUX - MECANISME'][champs][of_velux_distance_deuxieme_opercule][rowspan] = 2;
$champs[of_velux_distance_deuxieme_opercule][type] 	  = 'ouvert';

// NOUVELLE LIGNE //

$datasets['OF - VELUX - MECANISME'][champs][fil1][startligne] = 1;

$datasets['OF - VELUX - MECANISME'][champs][fil1][lb_champ] = "SL01-GY";
$datasets['OF - VELUX - MECANISME'][champs][fil1][hauteur] = 0.5;

$champs[fil1][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil2][lb_champ] = "SL 02";
$champs[fil2][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil3][lb_champ] = "SL 04 XXX";
$champs[fil3][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil4][lb_champ] = "SL 15";
$champs[fil4][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil5][lb_champ] = "SL 11V-GY";
$champs[fil5][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil6][lb_champ] = "SL 16";
$champs[fil6][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil7][lb_champ] = "SL 03-GY";
$champs[fil7][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil8][lb_champ] = "SL 09";
$champs[fil8][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil9][lb_champ] = "SL 08";
$champs[fil9][type] = 'span';

$datasets['OF - VELUX - MECANISME'][champs][fil10][lb_champ] = "SL 14";
$champs[fil10][type] = 'span';

?>