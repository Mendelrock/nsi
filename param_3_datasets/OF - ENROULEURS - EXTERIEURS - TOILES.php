<?php

$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][presentation] = 'l';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][padding] = 3;
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][nonmodifiable] = 1;

$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][repere][startligne] = 1;
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][repere][lb_champ_pdf] = "Repère";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][repere][largeur] = "2";

$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][coloris][id_champ] = 'COLORIS';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][coloris][lb_champ_pdf] = "Coloris";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][coloris][fonction_init] = "get_coloris";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][coloris][largeur] = "2";

$champs[largeur_store][libelle] = 'Largeur store';
$champs[largeur_store][type] = 'ouvert';
$champs[largeur_store][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_store][id_champ] = 'LARGEUR STORE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_store][lb_champ_pdf] = "Largeur store(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_store][fonction_init] = "get_largeur_store";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_store][largeur] = "2";

$champs[hauteur_store][libelle] = 'Hauteur store';
$champs[hauteur_store][type] = 'ouvert';
$champs[hauteur_store][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_store][id_champ] = 'HAUTEUR STORE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_store][lb_champ_pdf] = "Hauteur store(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_store][fonction_init] = "get_hauteur_store";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_store][largeur] = "2";

$champs[largeur_coupe_toile][libelle] = 'Largeur coupe toile';
$champs[largeur_coupe_toile][type] = 'ouvert';
$champs[largeur_coupe_toile][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_coupe_toile][id_champ] = 'LARGEUR COUPE TOILE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_coupe_toile][lb_champ_pdf] = "Largeur coupe toile(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_coupe_toile][fonction_init] = "get_largeur_coupe_toile";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][largeur_coupe_toile][largeur] = "2";

$champs[hauteur_coupe_toile][libelle] = 'Hauteur coupe toile';
$champs[hauteur_coupe_toile][type] = 'ouvert';
$champs[hauteur_coupe_toile][fonction] = "formate_troiszeroapresvirgule";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_coupe_toile][id_champ] = 'HAUTEUR COUPE TOILE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_coupe_toile][lb_champ_pdf] = "Hauteur coupe toile(en m)";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_coupe_toile][fonction_init] = "get_hauteur_coupe_toile";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][hauteur_coupe_toile][largeur] = "2";

$champs[confection_haute][libelle] = 'Confection haute';
$champs[confection_haute][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_haute][id_champ] = 'CONFECTION HAUTE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_haute][lb_champ_pdf] = "Confection haute";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_haute][fonction_init] = "get_of_confection_haute";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_haute][largeur] = "2";

$champs[confection_basse][libelle] = 'Confection basse';
$champs[confection_basse][type] = 'ouvert';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_basse][id_champ] = 'CONFECTION BASSE';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_basse][lb_champ_pdf] = "Confection basse";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_basse][fonction_init] = "get_of_confection_basse";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][confection_basse][largeur] = "2";

$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][quantite][id_champ] = 'quantite';
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][quantite][lb_champ_pdf] = "Quantité";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][quantite][fonction_init] = "get_quantite";
$datasets['OF - ENROULEURS - EXTERIEURS - TOILES'][champs][quantite][largeur] = "2";

?>