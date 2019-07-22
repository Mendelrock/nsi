<?php

$datasets['OF - FILM - INTERIEUR'][presentation] = 'l';
$datasets['OF - FILM - INTERIEUR'][padding] = 3;
$datasets['OF - FILM - INTERIEUR'][nonmodifiable] = 1;

$datasets['OF - FILM - INTERIEUR'][champs][repere][startligne] = 1;
$datasets['OF - FILM - INTERIEUR'][champs][repere][lb_champ_pdf] = "Repère";
$datasets['OF - FILM - INTERIEUR'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - FILM - INTERIEUR'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - FILM - INTERIEUR'][champs][repere][largeur] = "2";

$champs[reference][libelle] = 'Référence';
$champs[reference][type] = 'ouvert';
$champs[reference][fonction] = 'getColorisMonocolore';
$champs[reference][champscible] = 'lb_toile_atelier';
$datasets['OF - FILM - INTERIEUR'][champs][reference][id_champ] = 'REFERENCE FILM';
$datasets['OF - FILM - INTERIEUR'][champs][reference][lb_champ_pdf] = "Référence";
$datasets['OF - FILM - INTERIEUR'][champs][reference][fonction_init] = "get_reference_film";
$datasets['OF - FILM - INTERIEUR'][champs][reference][largeur] = "2";


$champs[largeur_filmsinterieur][libelle] = 'Largeur film';
$champs[largeur_filmsinterieur][type] = 'ouvert';
$champs[largeur_filmsinterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - INTERIEUR'][champs][largeur_filmsinterieur][id_champ] = 'LARGEUR FILM';
$datasets['OF - FILM - INTERIEUR'][champs][largeur_filmsinterieur][lb_champ_pdf] = "Largeur film";
$datasets['OF - FILM - INTERIEUR'][champs][largeur_filmsinterieur][fonction_init] = "get_largeur_vitre";
$datasets['OF - FILM - INTERIEUR'][champs][largeur_filmsinterieur][largeur] = "2";


$champs[hauteur_filmsinterieur][libelle] = 'Hauteur film';
$champs[hauteur_filmsinterieur][type] = 'ouvert';
$champs[hauteur_filmsinterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_filmsinterieur][id_champ] = 'HAUTEUR FILM';
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_filmsinterieur][lb_champ_pdf] = "Hauteur film";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_filmsinterieur][fonction_init] = "get_hauteur_vitre";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_filmsinterieur][largeur] = "2";


$champs[largeur_coupe_filmsinterieur][libelle] = 'Largeur coupe film';
$champs[largeur_coupe_filmsinterieur][type] = 'ouvert';
$champs[largeur_coupe_filmsinterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - INTERIEUR'][champs][largeur_coupe_filmsinterieur][id_champ] = 'LARGEUR COUPE FILM';
$datasets['OF - FILM - INTERIEUR'][champs][largeur_coupe_filmsinterieur][fonction_init] = "get_largeur_coupe_vitre";
$datasets['OF - FILM - INTERIEUR'][champs][largeur_coupe_filmsinterieur][largeur] = "2";


$champs[hauteur_coupe_filmsinterieur][libelle] = 'Hauteur coupe film';
$champs[hauteur_coupe_filmsinterieur][type] = 'ouvert';
$champs[hauteur_coupe_filmsinterieur][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_coupe_filmsinterieur][id_champ] = 'HAUTEUR COUPE FILM';
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_coupe_filmsinterieur][lb_champ_pdf] = "Hauteur coupe film";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_coupe_filmsinterieur][fonction_init] = "get_hauteur_coupe_vitre";
$datasets['OF - FILM - INTERIEUR'][champs][hauteur_coupe_filmsinterieur][largeur] = "2";


$datasets['OF - FILM - INTERIEUR'][champs][quantite][id_champ] = 'QUANTITE';
$datasets['OF - FILM - INTERIEUR'][champs][quantite][lb_champ_pdf] = "Quantité";
$datasets['OF - FILM - INTERIEUR'][champs][quantite][fonction_init] = "get_quantite";
$datasets['OF - FILM - INTERIEUR'][champs][quantite][largeur] = "2";



?>