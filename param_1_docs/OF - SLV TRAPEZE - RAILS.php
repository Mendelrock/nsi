<?
$type_docs['OF - SLV TRAPEZE - RAILS'][libelle] = "Bon de Fabrication SLV TRAPEZE - RAILS";
$type_docs['OF - SLV TRAPEZE - RAILS'][delai] = 10;
$type_docs['OF - SLV TRAPEZE - RAILS'][type_dataset_entete] = "ofentete";
$type_docs['OF - SLV TRAPEZE - RAILS'][type_dataset_ligne] = "OF - SLV TRAPEZE - RAILS";
$type_docs['OF - SLV TRAPEZE - RAILS'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - SLV TRAPEZE - RAILS'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - SLV TRAPEZE - RAILS'][btn][imprimer][libelle] = "Imprimer";
?>