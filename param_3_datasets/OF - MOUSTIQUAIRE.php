<?php

$datasets['OF - MOUSTIQUAIRE'][presentation] = 'l';
$datasets['OF - MOUSTIQUAIRE'][nonmodifiable] = 1;

$datasets['OF - MOUSTIQUAIRE'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - MOUSTIQUAIRE'][champs][repere][largeur] = 2;
$datasets['OF - MOUSTIQUAIRE'][champs][repere][fonction_init] = "get_repere";

$datasets['OF - MOUSTIQUAIRE'][champs][of_largeur][lb_champ] = "LARGEUR MOUSTIQUAIRE (en m)";
$datasets['OF - MOUSTIQUAIRE'][champs][of_largeur][fonction_init] = "get_moutiquaire_largeur";
$champs[of_largeur][type] 	  = 'ouvert';
$champs[of_largeur][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - MOUSTIQUAIRE'][champs][of_hauteur][lb_champ] = "HAUTEUR MOUSTIQUAIRE (en m)";
$datasets['OF - MOUSTIQUAIRE'][champs][of_hauteur][fonction_init] = "get_moutiquaire_hauteur";
$champs[of_hauteur][type] 	  = 'ouvert';
$champs[of_hauteur][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_coloris_profil][lb_champ] = "COLORIS PROFILS et PIECES MECANISME";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_coloris_profil][fonction_init] = "get_moustiquaire_coloris_profil";
$champs[of_moustiquaire_coloris_profil][type] = 'ouvert';

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_cordelette][lb_champ] = "CORDELETTE DE TIRAGE";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_cordelette][fonction_init] = "get_moustiquaire_cordelette";
$champs[of_moustiquaire_cordelette][type] = 'ouvert';

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_hauteur_cordelette][lb_champ] = "HAUTEUR CORDELETTE DE TIRAGE (en m)";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_hauteur_cordelette][fonction_init] = "get_moustiquaire_hauteur_cordelette";
$champs[of_moustiquaire_hauteur_cordelette][type] = 'ouvert';
$champs[of_moustiquaire_hauteur_cordelette][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_coloris_toile][lb_champ] = "COLORIS TOILE";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_coloris_toile][fonction_init] = "get_moustiquaire_coloris_toile";
$champs[of_moustiquaire_coloris_toile][type] = 'ouvert';

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_longueur_coupe][lb_champ] = "LONGEUR COUPE AXE - COFFRE - BDC (en m)";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_longueur_coupe][fonction_init] = "get_moustiquaire_longueur_coupe";
$champs[of_moustiquaire_longueur_coupe][type] = 'ouvert';
$champs[of_moustiquaire_longueur_coupe][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_longueur_coulisse][lb_champ] = "LONGEUR COUPE COULISSES (en m)";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_longueur_coulisse][fonction_init] = "get_moustiquaire_longueur_coulisse";
$champs[of_moustiquaire_longueur_coulisse][type] = 'ouvert';
$champs[of_moustiquaire_longueur_coulisse][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_nb_tours][lb_champ] = "NOMBRE DE TOURS RESSORT";
$datasets['OF - MOUSTIQUAIRE'][champs][of_moustiquaire_nb_tours][fonction_init] = "get_moustiquaire_nb_tours";
$champs[of_moustiquaire_nb_tours][type] = 'ouvert';

$datasets['OF - MOUSTIQUAIRE'][champs][of_qte][lb_champ] = "QUANTITE";
$datasets['OF - MOUSTIQUAIRE'][champs][of_qte][fonction_init] = "get_moustiquaire_qte";
$champs[of_qte][type] 	  = 'ouvert';
