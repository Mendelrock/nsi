<?
$type_docs['OF - VELUX - MECANISME'][libelle] = "Bon de Fabrication STORE VELUX - MECANISMES";
$type_docs['OF - VELUX - MECANISME'][delai] = 7;
$type_docs['OF - VELUX - MECANISME'][type_dataset_entete] = "ofentete";
$type_docs['OF - VELUX - MECANISME'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - VELUX - MECANISME'][type_dataset_ligne] = "OF - VELUX - MECANISME";
$type_docs['OF - VELUX - MECANISME'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - VELUX - MECANISME'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - VELUX - MECANISME'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - VELUX - MECANISME'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - VELUX - MECANISME'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - VELUX - MECANISME'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - VELUX - MECANISME'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - VELUX - MECANISME'][btn][imprimer][libelle] = "Imprimer";
?>