<?php

$datasets['OF - VELUX - TOILE'][presentation] = 'l';

$datasets['OF - VELUX - TOILE'][nonmodifiable] = 1;

$datasets['OF - VELUX - TOILE'][champs][repere][startligne] = 1;

$datasets['OF - VELUX - TOILE'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - VELUX - TOILE'][champs][repere][fonction_init] = "get_repere";

$datasets['OF - VELUX - TOILE'][champs][of_coloris][lb_champ_pdf] = "COLORIS";
$datasets['OF - VELUX - TOILE'][champs][of_coloris][fonction_init] = "get_velux_coloris";

$datasets['OF - VELUX - TOILE'][champs][of_largeur][lb_champ] = "LARGEUR COUPE TOILE (en m)";
$datasets['OF - VELUX - TOILE'][champs][of_largeur][fonction_init] = "get_velux_largeur_coupe";
$champs[of_largeur][type] 	  = 'ouvert';

$datasets['OF - VELUX - TOILE'][champs][of_hauteur][lb_champ] = "HAUTEUR COUPE TOILE (en m)";
$datasets['OF - VELUX - TOILE'][champs][of_hauteur][fonction_init] = "get_velux_hauteur_coupe";
$champs[of_hauteur][type] 	  = 'ouvert';

$datasets['OF - VELUX - TOILE'][champs][of_qte][lb_champ] = "QUANTITE";
$datasets['OF - VELUX - TOILE'][champs][of_qte][fonction_init] = "get_velux_qte";
$champs[of_qte][type] 	  = 'ouvert';
