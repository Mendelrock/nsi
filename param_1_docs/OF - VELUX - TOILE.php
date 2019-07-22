<?
$type_docs['OF - VELUX - TOILE'][libelle] = "Bon de Fabrication STORE VELUX - TOILES";
$type_docs['OF - VELUX - TOILE'][delai] = 7;
$type_docs['OF - VELUX - TOILE'][type_dataset_entete] = "ofentete";
$type_docs['OF - VELUX - TOILE'][directivecoupe] = 1;
$type_docs['OF - VELUX - TOILE'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - VELUX - TOILE'][type_dataset_ligne] = "OF - VELUX - TOILE";
$type_docs['OF - VELUX - TOILE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - VELUX - TOILE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - VELUX - TOILE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - VELUX - TOILE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - VELUX - TOILE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - VELUX - TOILE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - VELUX - TOILE'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - VELUX - TOILE'][btn][imprimer][libelle] = "Imprimer";
?>