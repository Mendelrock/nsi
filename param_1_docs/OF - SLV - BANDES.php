<?
$type_docs['OF - SLV - BANDES'][libelle] = "Bon de Fabrication STORE LAMES VERTICALES - BANDES";
$type_docs['OF - SLV - BANDES'][delai] = 10;
$type_docs['OF - SLV - BANDES'][type_dataset_entete] = "ofentete";
//$type_docs['OF - SLV - BANDES'][directivecoupe] = 1;
$type_docs['OF - SLV - BANDES'][type_dataset_ligne] = "OF - SLV - BANDES";
$type_docs['OF - SLV - BANDES'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - SLV - BANDES'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - SLV - BANDES'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - SLV - BANDES'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - SLV - BANDES'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - SLV - BANDES'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - SLV - BANDES'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - SLV - BANDES'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - SLV - BANDES'][btn][imprimer][libelle] = "Imprimer";
?>