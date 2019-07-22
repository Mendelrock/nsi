<?
$type_docs['OF - SLV - BANDES - SEULES'][libelle] = "Bon de Fabrication LAMES VERTICALES SEULES";
$type_docs['OF - SLV - BANDES - SEULES'][delai] = 10;
$type_docs['OF - SLV - BANDES - SEULES'][type_dataset_entete] = "ofentete";
$type_docs['OF - SLV - BANDES - SEULES'][type_dataset_ligne] = "OF - SLV - BANDES - SEULES";
$type_docs['OF - SLV - BANDES - SEULES'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - SLV - BANDES - SEULES'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - SLV - BANDES - SEULES'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - SLV - BANDES - SEULES'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - SLV - BANDES - SEULES'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - SLV - BANDES - SEULES'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - SLV - BANDES - SEULES'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - SLV - BANDES - SEULES'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - SLV - BANDES - SEULES'][btn][imprimer][libelle] = "Imprimer";
?>