<?php
$datasets[fdcproduitfilmexterieur][presentation] = 'l';

$datasets[fdcproduitfilmexterieur][champs][repere][id_champ] = 'REPERE';
 
$datasets[fdcproduitfilmexterieur][champs][references_film_exterieur][id_champ] = 'Référence';
$champs[references_film_exterieur][libelle] = 'Référence';

$champs[references_film_exterieur][type]    = 'toile';
$champs[references_film_exterieur][checkvalidate]='controler_champ_toile';
$champs[references_film_exterieur][champstocke] = 'selecteur_film_exterieur';
$champs[references_film_exterieur][champscible] = 'lb_toile_atelier';
//$champs[references_film_exterieur][obligatoire] = 1;

$datasets[fdcproduitfilmexterieur][champs][largeur][id_champ] = 'Largeur en mm';
$champs[largeur][libelle] = 'Largeur en mm';
$champs[largeur][type] = 'ouvert';

$datasets[fdcproduitfilmexterieur][champs][hauteur][id_champ] = 'Hauteur en mm';
$champs[hauteur][libelle] = 'Hauteur en mm';
$champs[hauteur][type] = 'ouvert';

$datasets[fdcproduitfilmexterieur][champs][qte][id_champ] = 'qte';
$champs[qte][libelle] = 'Qte';
$champs[qte][type] = 'ouvert';

$datasets[fdcproduitfilmexterieur][checkvalidate]='checkvalidate';
?>