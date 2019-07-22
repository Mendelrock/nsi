<?php
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][libelle] = "Bon de Fabrication ENROULEURS EXTERIEURS (TOILES)";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][delai] = 12;
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][type_dataset_entete] = "ofentete";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][type_dataset_ligne] = "OF - ENROULEURS - EXTERIEURS - TOILES";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - ENROULEURS - EXTERIEURS - TOILES'][btn][imprimer][libelle] = "Imprimer";
?>