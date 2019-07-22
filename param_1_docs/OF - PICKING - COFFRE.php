<?
$type_docs['OF - PICKING - COFFRE'][libelle] = "Bon de PICKING - STORE - COFFRE";
$type_docs['OF - PICKING - COFFRE'][delai] = 12;
$type_docs['OF - PICKING - COFFRE'][type_dataset_entete] = "ofentete";
$type_docs['OF - PICKING - COFFRE'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - PICKING - COFFRE'][type_dataset_ligne] = "OF - PICKING - COFFRE";
$type_docs['OF - PICKING - COFFRE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - PICKING - COFFRE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - PICKING - COFFRE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - PICKING - COFFRE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - PICKING - COFFRE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - PICKING - COFFRE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - PICKING - COFFRE'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - PICKING - COFFRE'][btn][imprimer][libelle] = "Imprimer";
?>