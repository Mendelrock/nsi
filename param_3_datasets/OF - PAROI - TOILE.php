<?php
$datasets['OF - PAROI - TOILE'][presentation] = 'l';

$datasets['OF - PAROI - TOILE'][padding] = 3;
$datasets['OF - PAROI - TOILE'][nonmodifiable] = 1;

$datasets['OF - PAROI - TOILE'][champs][repere][startligne] = 1;

$datasets['OF - PAROI - TOILE'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - PAROI - TOILE'][champs][repere][largeur] = 2;
$datasets['OF - PAROI - TOILE'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - PAROI - TOILE'][champs][repere][rowspan] = 2;

$datasets['OF - PAROI - TOILE'][champs][of_coloris][lb_champ_pdf] = "COLORIS";
$datasets['OF - PAROI - TOILE'][champs][of_coloris][fonction_init] = "get_of_pa_coloris";
$datasets['OF - PAROI - TOILE'][champs][of_coloris][rowspan] = 2;

$datasets['OF - PAROI - TOILE'][champs][of_largeur][lb_champ_pdf] = "LARGEUR PAROI JAPONAISE (en m)";
$datasets['OF - PAROI - TOILE'][champs][of_largeur][fonction_init] = "get_of_pa_largeur_fenetre";
$datasets['OF - PAROI - TOILE'][champs][of_largeur][rowspan] = 2;

$datasets['OF - PAROI - TOILE'][champs][of_hauteur][lb_champ_pdf] = "HAUTEUR PAROI JAPONAISE (en m)";
$datasets['OF - PAROI - TOILE'][champs][of_hauteur][fonction_init] = "get_of_pa_hauteur";
$datasets['OF - PAROI - TOILE'][champs][of_hauteur][rowspan] = 2;

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_span1][lb_champ] = "COUPE TISSU PANNEAU";
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_span1][colspan] = 5;
$champs[of_pa_toile_span1][type] = 'span';

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_span2][lb_champ] = 'CONFECTION PANNEAUX';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_span2][colspan] = 2;
$champs[of_pa_toile_span2][type] = 'span';

$datasets['OF - PAROI - TOILE'][champs][of_qte][lb_champ_pdf] = "QUANTITE";
$datasets['OF - PAROI - TOILE'][champs][of_qte][fonction_init] = "get_of_pa_qte";
$datasets['OF - PAROI - TOILE'][champs][of_qte][rowspan] = 2;

// NOUVELLE LIGNE //

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeur][startligne] = 1;

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeurpanneau][lb_champ] = 'Largeur du Panneau';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeurpanneau][lb_champ_pdf] = 'LARGEUR PANNEAU (en m)';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeurpanneau][fonction_init] = "get_of_pa_ra_pan_longueurcoupe";
$champs[of_pa_toile_largeurpanneau][type]    = 'ouvert';

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeur][lb_champ] = "LARGEUR\nCOUPE\n(en m)";
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_largeur][fonction_init] = "get_of_pa_toile_largeur";
$champs[of_pa_toile_largeur][type] 	  = 'ouvert';
$champs[of_pa_toile_largeur][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_hauteur][lb_champ] = "HAUTEUR\nCOUPE\n(en m)";
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_hauteur][fonction_init] = "get_of_pa_toile_hauteur";
$champs[of_pa_toile_hauteur][type]    = 'ouvert';
$champs[of_pa_toile_hauteur][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_quantite][lb_champ] = 'Quantite';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_quantite][fonction_init] = "get_of_pa_nb_panneaux";
$champs[of_pa_toile_quantite][type]    = 'ouvert';

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_collaborateur][lb_champ] = "TYPE DE COUPE";
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_collaborateur][fonction_init] = "get_of_pa_toile_collaborateur";
$champs[of_pa_toile_collaborateur][type] = 'ouvert';

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_confection_haute][lb_champ] = 'CONFECTION HAUTE';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_confection_haute][fonction_init] = "get_of_toile_confection_haute";
$champs[of_pa_toile_confection_haute][type] = 'ouvert';

$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_confection_laterale][lb_champ] = 'CONFECTION LATERALE';
$datasets['OF - PAROI - TOILE'][champs][of_pa_toile_confection_laterale][fonction_init] = "get_of_toile_confection_laterale";
$champs[of_pa_toile_confection_laterale][type] = 'ouvert';

