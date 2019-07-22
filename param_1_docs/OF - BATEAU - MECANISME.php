<?
$type_docs['OF - BATEAU - MECANISME'][libelle] = "Bon de Fabrication STORE BATEAUX - MECANISMES";
$type_docs['OF - BATEAU - MECANISME'][delai] = 13;
$type_docs['OF - BATEAU - MECANISME'][type_dataset_entete] = "ofentete";
$type_docs['OF - BATEAU - MECANISME'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - BATEAU - MECANISME'][type_dataset_ligne] = "OF - BATEAU - MECANISME";
$type_docs['OF - BATEAU - MECANISME'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - BATEAU - MECANISME'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - BATEAU - MECANISME'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - BATEAU - MECANISME'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - BATEAU - MECANISME'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - BATEAU - MECANISME'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - BATEAU - MECANISME'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - BATEAU - MECANISME'][btn][imprimer][libelle] = "Imprimer";
?>