<?
$type_docs['OF - PICKING - BANNE'][libelle] = "Bon de PICKING - STORE - BANNE";
$type_docs['OF - PICKING - BANNE'][delai] = 12;
$type_docs['OF - PICKING - BANNE'][type_dataset_entete] = "ofentete";
$type_docs['OF - PICKING - BANNE'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - PICKING - BANNE'][type_dataset_ligne] = "OF - PICKING - BANNE";
$type_docs['OF - PICKING - BANNE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - PICKING - BANNE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - PICKING - BANNE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - PICKING - BANNE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - PICKING - BANNE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - PICKING - BANNE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - PICKING - BANNE'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - PICKING - BANNE'][btn][imprimer][libelle] = "Imprimer";
?>