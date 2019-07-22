<?
$type_docs['OF - PAROI - RAIL'][libelle] = "Bon de Fabrication PAROIS JAPONAISES - RAILS";
$type_docs['OF - PAROI - RAIL'][delai] = 12;
$type_docs['OF - PAROI - RAIL'][type_dataset_entete] = "ofentete";
$type_docs['OF - PAROI - RAIL'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - PAROI - RAIL'][type_dataset_ligne] = "OF - PAROI - RAIL";
$type_docs['OF - PAROI - RAIL'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - PAROI - RAIL'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - PAROI - RAIL'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - PAROI - RAIL'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - PAROI - RAIL'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - PAROI - RAIL'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - PAROI - RAIL'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - PAROI - RAIL'][btn][imprimer][libelle] = "Imprimer";
?>