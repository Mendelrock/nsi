<?php
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][libelle] = "Bon de Fabrication ENROULEURS EXTERIEURS ( MECANISMES)";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][delai] = 12;
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][type_dataset_entete] = "ofentete";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][type_dataset_ligne] = "OF - ENROULEURS - EXTERIEURS - MECANISMES";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][consommation] = "consommation_unitaire";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - ENROULEURS - EXTERIEURS - MECANISMES'][btn][imprimer][libelle] = "Imprimer";
?>