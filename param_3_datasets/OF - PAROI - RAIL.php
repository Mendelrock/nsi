<?php
$datasets['OF - PAROI - RAIL'][presentation] = 'l';

$datasets['OF - PAROI - RAIL'][padding] = 3;
$datasets['OF - PAROI - RAIL'][nonmodifiable] = 1;

$datasets['OF - PAROI - RAIL'][champs][repere][startligne] = 1;

$datasets['OF - PAROI - RAIL'][champs][repere][id_champ] = 'REPERE';
$datasets['OF - PAROI - RAIL'][champs][repere][fonction_init] = "get_repere";
$datasets['OF - PAROI - RAIL'][champs][repere][largeur] = "2";
$datasets['OF - PAROI - RAIL'][champs][repere][rowspan] = 2;

$datasets['OF - PAROI - RAIL'][champs][of_coloris][lb_champ_pdf] = "COLORIS";
$datasets['OF - PAROI - RAIL'][champs][of_coloris][fonction_init] = "get_of_pa_coloris";
$datasets['OF - PAROI - RAIL'][champs][of_coloris][rowspan] = 2;

$datasets['OF - PAROI - RAIL'][champs][of_largeur][lb_champ_pdf] = "LARGEUR RAIL (en m)";
$datasets['OF - PAROI - RAIL'][champs][of_largeur][fonction_init] = "get_of_pa_largeur_rail";
$datasets['OF - PAROI - RAIL'][champs][of_largeur][rowspan] = 2;

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span1][lb_champ] = "RAILS";
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span1][colspan] = 2;
$champs[of_pa_rail_span1][type] = 'span';

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span2][lb_champ] = 'PROFILS PANNEAUX et BDC';
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span2][colspan] = 3;
$champs[of_pa_rail_span2][type] = 'span';

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span3][lb_champ] = 'SUPPORTS DE POSE';
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_span3][colspan] = 2;
$champs[of_pa_rail_span3][type] = 'span';

$datasets['OF - PAROI - RAIL'][champs][of_qte][lb_champ_pdf] = "QUANTITé";
$datasets['OF - PAROI - RAIL'][champs][of_qte][fonction_init] = "get_of_pa_qte";
$datasets['OF - PAROI - RAIL'][champs][of_qte][rowspan] = 2;

// NOUVELLE LIGNE //

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_type][startligne] = 1;

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_type][lb_champ] = "Type";
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_type][fonction_init] = "get_of_pa_ra_rail_type";
$champs[of_pa_rail_type][type] 	  = 'ouvert';

$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_longueurcoupe][lb_champ] = 'Longueur Coupe';
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_longueurcoupe][lb_champ_pdf] = 'LONGUEUR COUPE (en m)';
$datasets['OF - PAROI - RAIL'][champs][of_pa_rail_longueurcoupe][fonction_init] = "get_of_pa_ra_rail_longueurcoupe";
$champs[of_pa_rail_longueurcoupe][type]    = 'ouvert';
$champs[of_pa_rail_longueurcoupe][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_typeprofils][lb_champ] = 'Types Profil';
$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_typeprofils][fonction_init] = "get_of_pa_ra_pan_typeprofils";
$champs[of_pa_pan_typeprofils][type]    = 'ouvert';

$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_longueurcoupe][lb_champ] = 'Longueur Coupe';
$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_longueurcoupe][lb_champ_pdf] = 'LONGUEUR COUPE (en m)';
$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_longueurcoupe][fonction_init] = "get_of_pa_ra_pan_longueurcoupe";
$champs[of_pa_pan_longueurcoupe][type]    = 'ouvert';
$champs[of_pa_pan_longueurcoupe][fonction] = "formate_troiszeroapresvirgule";

$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_nbpanneaux][lb_champ] = 'Nombre de panneaux';
$datasets['OF - PAROI - RAIL'][champs][of_pa_pan_nbpanneaux][fonction_init] = "get_of_pa_nb_panneaux";
$champs[of_pa_pan_nbpanneaux][type]    = 'ouvert';

$datasets['OF - PAROI - RAIL'][champs][of_pa_sup_typesupports][lb_champ] = 'Type Supports';
$datasets['OF - PAROI - RAIL'][champs][of_pa_sup_typesupports][fonction_init] = "get_of_pa_ra_sup_typesupports";
$champs[of_pa_sup_typesupports][type]    = 'ouvert';

$datasets['OF - PAROI - RAIL'][champs][of_pa_sup_qte][lb_champ] = 'Quantité';
$datasets['OF - PAROI - RAIL'][champs][of_pa_sup_qte][fonction_init] = "get_of_pa_sup_qte";
$champs[of_pa_sup_qte][type]    = 'ouvert';




?>