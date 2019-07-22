<?
$type_docs['OF - RIDEAU'][libelle] = "Bon de Fabrication RIDEAU - COUPE + COUTURE";
$type_docs['OF - RIDEAU'][delai] = 12;
$type_docs['OF - RIDEAU'][type_dataset_entete] = "ofentete";
$type_docs['OF - RIDEAU'][directivecoupe] = 1;
$type_docs['OF - RIDEAU'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - RIDEAU'][type_dataset_ligne] = "OF - RIDEAU";
$type_docs['OF - RIDEAU'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - RIDEAU'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - RIDEAU'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - RIDEAU'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - RIDEAU'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - RIDEAU'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - RIDEAU'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - RIDEAU'][btn][imprimer][libelle] = "Imprimer";
?>