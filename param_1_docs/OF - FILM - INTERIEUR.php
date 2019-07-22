<?php
$type_docs['OF - FILM - INTERIEUR'][libelle] = "Bon de Fabrication FILM INTERIEUR";
$type_docs['OF - FILM - INTERIEUR'][delai] = 12;
$type_docs['OF - FILM - INTERIEUR'][type_dataset_entete] = "ofentete";
$type_docs['OF - FILM - INTERIEUR'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - FILM - INTERIEUR'][type_dataset_ligne] = "OF - FILM - INTERIEUR";
$type_docs['OF - FILM - INTERIEUR'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - FILM - INTERIEUR'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - FILM - INTERIEUR'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - FILM - INTERIEUR'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - FILM - INTERIEUR'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - FILM - INTERIEUR'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - FILM - INTERIEUR'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - FILM - INTERIEUR'][btn][imprimer][libelle] = "Imprimer";
?>