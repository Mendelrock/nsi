<?php

$datasets['OF - FILM - EXTERIEUR'][presentation] = 'l';
$datasets['OF - FILM - EXTERIEUR'][padding] = 3;
$datasets['OF - FILM - EXTERIEUR'][nonmodifiable] = 1;

$datasets['OF - FILM - EXTERIEUR'][champs][repere][startligne] = 1;
$datasets['OF - FILM - EXTERIEUR'][champs][repere][lb_champ_pdf] = "Repère";
$datasets['OF - FILM - EXTERIEUR'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - FILM - EXTERIEUR'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - FILM - EXTERIEUR'][champs][repere][largeur] = "2";

$champs[reference][libelle] = 'Référence';
$champs[reference][type] = 'ouvert';
$champs[reference][fonction] = 'getColorisMonocolore';
$champs[reference][champscible] = 'lb_toile_atelier';
$datasets['OF - FILM - EXTERIEUR'][champs][reference][id_champ] = 'REFERENCE FILM';
$datasets['OF - FILM - EXTERIEUR'][champs][reference][lb_champ_pdf] = "Référence";
$datasets['OF - FILM - EXTERIEUR'][champs][reference][fonction_init] = "get_reference_film";
$datasets['OF - FILM - EXTERIEUR'][champs][reference][largeur] = "2";

$champs[largeur_filmsexterieur][libelle] = 'Largeur film';
$champs[largeur_filmsexterieur][type] = 'ouvert';
$champs[largeur_filmsexterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_filmsexterieur][id_champ] = 'LARGEUR FILM';
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_filmsexterieur][lb_champ_pdf] = "Largeur film";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_filmsexterieur][fonction_init] = "get_largeur_vitre";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_filmsexterieur][largeur] = "2";


$champs[hauteur_filmsexterieur][libelle] = 'Hauteur film';
$champs[hauteur_filmsexterieur][type] = 'ouvert';
$champs[hauteur_filmsexterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_filmsexterieur][id_champ] = 'HAUTEUR FILM';
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_filmsexterieur][lb_champ_pdf] = "Hauteur film";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_filmsexterieur][fonction_init] = "get_hauteur_vitre";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_filmsexterieur][largeur] = "2";


$champs[largeur_coupe_filmsexterieur][libelle] = 'Largeur coupe film';
$champs[largeur_coupe_filmsexterieur][type] = 'ouvert';
$champs[largeur_coupe_filmsexterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_coupe_filmsexterieur][id_champ] = 'LARGEUR COUPE FILM';
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_coupe_filmsexterieur][lb_champ_pdf] = "Largeur coupe film";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_coupe_filmsexterieur][fonction_init] = "get_largeur_coupe_vitre";
$datasets['OF - FILM - EXTERIEUR'][champs][largeur_coupe_filmsexterieur][largeur] = "2";


$champs[hauteur_coupe_filmsexterieur][libelle] = 'Hauteur coupe film';
$champs[hauteur_coupe_filmsexterieur][type] = 'ouvert';
$champs[hauteur_coupe_filmsexterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_coupe_filmsexterieur][id_champ] = 'HAUTEUR COUPE FILM';
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_coupe_filmsexterieur][lb_champ_pdf] = "Hauteur coupe film";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_coupe_filmsexterieur][fonction_init] = "get_hauteur_coupe_vitre";
$datasets['OF - FILM - EXTERIEUR'][champs][hauteur_coupe_filmsexterieur][largeur] = "2";


$datasets['OF - FILM - EXTERIEUR'][champs][quantite][id_champ] = 'QUANTITE';
$datasets['OF - FILM - EXTERIEUR'][champs][quantite][lb_champ_pdf] = "Quantité";
$datasets['OF - FILM - EXTERIEUR'][champs][quantite][fonction_init] = "get_quantite";
$datasets['OF - FILM - EXTERIEUR'][champs][quantite][largeur] = "2";


?>