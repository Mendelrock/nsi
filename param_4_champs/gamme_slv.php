<?
$champs[gamme_slv][libelle] = 'Gamme - Collection - Coloris';
$champs[gamme_slv][type]    = 'lov';
$champs[gamme_slv][tablecible] = 'select * from article where selecteur="slv"';
$champs[gamme_slv][champstocke] = 'id_article';
$champs[gamme_slv][champscible] = 'lb_article';
$champs[gamme_slv][fonction] = 'getColorisTringle';// Je l'ai remis obligatoire
$champs[gamme_slv][obligatoire] = 1;
$champs[gamme_slv][title] = 'Sélectionner la gamme';
?>