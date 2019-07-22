<?php
$champs[statut][libelle] = 'Statut';
//$champs[statut][origines_invisibles]="|1|2";
$champs[statut][type] = 'lov';$champs[statut][ordre]['En cours']='1';$champs[statut][ordre]['Validée']='2';$champs[statut][ordre]['A produire']='5';$champs[statut][ordre]['A poser / A livrer']='6';$champs[statut][ordre]['A facturer']='7';$champs[statut][ordre]['Facturée']='8';
$champs[statut][fieldrequired][fdcentete][1][1]='libelle';$champs[statut][fieldrequired][fdcentete][1][2]='date';$champs[statut][fieldrequired][fdcentete][1][3]='adresse';$champs[statut][fieldrequired][fdcentete][1][4]='mailinterlocuteur';$champs[statut][fieldrequired][fdcentete][1][5]='montantcommande';$champs[statut][fieldrequired][fdcentete][1][6]='nomchantier';$champs[statut][fieldrequired][fdcentete][1][7]='interlocuteur';$champs[statut][fieldrequired][fdcentete][1][8]='prestation';$champs[statut][fieldrequired][fdcentete][1][9]='telephone';$champs[statut][fieldrequired][fdcentete][1][10]='origine';
$champs[statut][fieldrequired][fdcentete][2][1]='moyens_levage';$champs[statut][fieldrequired][fdcentete][2][2]='cales';$champs[statut][fieldrequired][fdcentete][2][3]='acces_chantier';$champs[statut][fieldrequired][fdcentete][2][4]='etage_chantier';
$champs[statut][fieldrequired][fdcentete][2][1]='ncommande';$champs[statut][fieldrequired][fdcentete][2][2]='datepose';
$champs[statut][fieldrequired][fdcproduit53exterieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduit53interieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduit70exterieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduit70interieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduit95exterieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduit95interieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitcoussin][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitdroit][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitembrases][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitenrouleur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitenrouleurexterieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitfilmexterieur][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitfilmsinterieurs][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitmoustiquaire][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitparoi][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitrailslvmotorise][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitrideaubico][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitrideaumono][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitslv][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitslvlamesseules][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitslvrailsseuls][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitslvtrapeze][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstorebanne][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstorebannecoffrecompacto][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstorebannecoffreelisse][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstorebannemonobloc][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstorebateau][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstoreplissehorizontal][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitstoreplissevertical][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduittringle][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitvelux][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitvenitienalu25][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitvenitienbois50][2][1]='ALL';
$champs[statut][fieldrequired][fdcproduitrideaumono][2][1]='ALL';
$champs[statut][fieldrequired][produitentetegenerique][2][1]='ALL';
$champs[statut][fieldrequired][produitentetemoto2][2][1]='ALL';
$champs[statut][fieldrequired][produitentetemoto4][2][1]='ALL';

$champs[statut][exceptionfield][fdcproduitrideaumono][2][]='doublure_rideau';
// $champs[statut][exceptionfield][fdcproduitrideaumono][2][]='repere';
// $champs[statut][exceptionfield][fdcproduit95exterieur][2][]='sortiecablemoteur_95e';
// $champs[statut][exceptionfield][fdcproduit95interieur][2][]='sortiecablemoteur_95i';
// $champs[statut][exceptionfield][fdcproduit70exterieur][2][]='sortiecablemoteur_70e';
// $champs[statut][exceptionfield][fdcproduit70interieur][2][]='sortiecablemoteur_70i';

// $champs[statut][exceptionfield][fdcproduittringle][2][]='hauteur_cde_tringle';
// $champs[statut][exceptionfield][fdcproduitstorebannecoffreelisse][2][]='hauteur_manivelle_sbmo';
// $champs[statut][exceptionfield][fdcproduitstorebannecoffreelisse][2][]='hauteurmanivelle_sbcc';
// $champs[statut][exceptionfield][fdcproduit70exterieur][2][]='hauteurcommande';
// $champs[statut][exceptionfield][fdcproduitslv][2][]='hauteur_cde_slv';
// $champs[statut][exceptionfield][fdcproduitvenitienbois50][2][]='hauteur_cde_venitienbois50';
// $champs[statut][exceptionfield][fdcproduitvenitienalu25][2][]='hauteur_cde_venitienalu25';
// $champs[statut][exceptionfield][fdcproduitmoustiquaire][2][]='fdc_moustiquaire_hauteur_cordelette';
// $champs[statut][exceptionfield][fdcproduitvelux][2][]='fdc_velux_perche';
// $champs[statut][exceptionfield][fdcproduitenrouleurexterieur][2][]='automatisme_enex';
$champs[statut][checkvalidate] = 'controler_statut';
$champs[statut][checkload] = 'set_dataset_modifiable_fct_statut';
$champs[statut][filtrer_valeurs] = 'filtrer_statut_fdc';
$champs[statut][defaultvalue] = 'En cours';
$champs[statut][obligatoire] = 1;
$champs[statut][title] = 'Saisir le statut de la feuille de côte';
?>