<?php
$datasets[fdcproduitparoi][presentation] = 'l';

$datasets[fdcproduitparoi][champs][repere][id_champ] = 'REPERE';
 
$datasets[fdcproduitparoi][champs][largeurrail][id_champ] = 'Largeur rail en mm';
$champs[largeurrail][libelle] = 'Largeur rail en mm';
$champs[largeurrail][formule_import] = 1;
$champs[largeurrail][type] = 'ouvert';

$datasets[fdcproduitparoi][champs][largeur][id_champ] = 'Largeur fenêtre en mm';
$champs[largeur][libelle] = 'Largeur fenêtre en mm';
$champs[largeur][type] = 'ouvert';
$champs[largeur][longueur] = 5;
$champs[largeur][formule_import] = 1;
$champs[largeur][largeur] = 3/5;
$champs[largeur][title] = 'Saisir la largeur en mm';

//$datasets[fdcproduitparoi][champs][largeurPanneau][id_champ] = 'Largeur panneau en mm';
//$champs[largeurPanneau][libelle] = 'Largeur panneau en cm';
//$champs[largeurPanneau][type] = 'ouvert';

$datasets[fdcproduitparoi][champs][hauteur_cm][id_champ] = 'Hauteur en mm';

$datasets[fdcproduitparoi][champs][qte][id_champ] = 'qte';

$datasets[fdcproduitparoi][champs][fdc_pa_toile][id_champ] = 'Coloris';

$datasets[fdcproduitparoi][champs][fdc_pa_nb_panneaux][id_champ] = 'Nombre de panneaux';
$champs[fdc_pa_nb_panneaux][libelle] = 'Nombre de panneaux';
$champs[fdc_pa_nb_panneaux][type] = 'ouvert';

$datasets[fdcproduitparoi][champs][fdc_pa_type_pose][id_champ] = 'Type de pose';
$champs[fdc_pa_type_pose][libelle] = 'Type de pose';
$champs[fdc_pa_type_pose][type] = 'lov';
//$champs[fdc_pa_type_pose][obligatoire] = 1;
$champs[fdc_pa_type_pose][title] = 'S?lectionner le type de pose';

$champs[fdc_pa_toile][libelle] = 'Coloris';
$champs[fdc_pa_toile][type] = 'toile';
$champs[fdc_pa_toile][checkvalidate]='controler_champ_toile';
$champs[fdc_pa_toile][champstocke] = 'selecteur_paroi';
//$champs[fdc_pa_toile][obligatoire] = 1;
$champs[fdc_pa_toile][title] = 'Sélectionner la toile';

$datasets[fdcproduitparoi][checkvalidate]='checkvalidate';
?>