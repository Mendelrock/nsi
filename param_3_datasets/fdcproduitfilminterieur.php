<?php

$datasets[fdcproduitfilminterieur][presentation] = 'l';
$datasets[fdcproduitfilminterieur][champs][repere][id_champ] = 'repere';
$datasets[fdcproduitfilminterieur][champs][references_film_interieur][id_champ] = 'references';
$champs[references_film_interieur][libelle] = 'Référence';

$champs[references_film_interieur][type]    = 'toile';
$champs[references_film_interieur][checkvalidate]='controler_champ_toile';
$champs[references_film_interieur][champstocke] = 'selecteur_film_interieur';
$champs[references_film_interieur][champscible] = 'lb_toile_atelier';
//$champs[references_film_interieur][obligatoire] = 1;

$datasets[fdcproduitfilminterieur][champs][largeur_filmsinterieurs][id_champ] = 'largeur';
$datasets[fdcproduitfilminterieur][champs][hauteur_filmsinterieurs][id_champ] = 'hauteur';
$datasets[fdcproduitfilminterieur][champs][qte][id_champ] = 'qte';

$datasets[fdcproduitfilminterieur][checkvalidate]='checkvalidate';
?>