<?php

$champs[moto4][libelle] = 'Type de Commande Motorisée';
$champs[moto4][type] = 'lov';
$champs[moto4][tablecible] = ' select "-" as id_article, "Pas de commande" as lb_article union select id_article, lb_article from article where selecteur = "moto2" or selecteur = "moto4" ';
$champs[moto4][champstocke] = 'id_article';
$champs[moto4][champscible] = 'lb_article';

?>

