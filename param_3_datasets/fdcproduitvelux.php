<?php
$datasets[fdcproduitvelux][presentation] = 'l';

$datasets[fdcproduitvelux][champs] [repere][id_champ] = 'repere';

$datasets[fdcproduitvelux][champs][fdc_velux_modele][id_champ] = 'Modele';
$champs[fdc_velux_modele][libelle] = 'Modele';
$champs[fdc_velux_modele][type] = 'lov';

$datasets[fdcproduitvelux][champs][fdc_velux_toile][id_champ] = 'Coloris';
$champs[fdc_velux_toile][libelle] = 'Coloris';
$champs[fdc_velux_toile][type] = 'toile';
$champs[fdc_velux_toile][checkvalidate]='controler_champ_toile';
$champs[fdc_velux_toile][champstocke] = 'selecteur_enrouleur_interieur';;
//$champs[fdc_velux_toile][obligatoire] = 1;
$champs[fdc_velux_toile][title] = 'Sélectionner la toile';

$datasets[fdcproduitvelux][champs][fdc_velux_perche][id_champ] = 'Perche';
$champs[fdc_velux_perche][libelle] = 'Perche';
$champs[fdc_velux_perche][type] = 'lov';

$datasets[fdcproduitvelux][champs][qte][id_champ] = 'qte';
$datasets[fdcproduitvelux][checkvalidate]='checkvalidate';
?>