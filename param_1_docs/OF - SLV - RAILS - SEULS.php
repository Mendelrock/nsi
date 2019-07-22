<?
$type_docs['OF - SLV - RAILS - SEULS'][libelle] = "Bon de Fabrication RAILS SLV SEULS";
$type_docs['OF - SLV - RAILS - SEULS'][delai] = 10;
$type_docs['OF - SLV - RAILS - SEULS'][type_dataset_entete] = "ofentete";
$type_docs['OF - SLV - RAILS - SEULS'][type_dataset_ligne] = "OF - SLV - RAILS - SEULS";
$type_docs['OF - SLV - RAILS - SEULS'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - SLV - RAILS - SEULS'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - SLV - RAILS - SEULS'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - SLV - RAILS - SEULS'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - SLV - RAILS - SEULS'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - SLV - RAILS - SEULS'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - SLV - RAILS - SEULS'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - SLV - RAILS - SEULS'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - SLV - RAILS - SEULS'][btn][imprimer][libelle] = "Imprimer";
$type_docs['OF - SLV - RAILS - SEULS'][consommation] = "consommation_unitaire";
?>