<?
$type_docs['OF - COUSSIN'][libelle] = "Bon de Fabrication COUSSIN - COUPE + COUTURE";
$type_docs['OF - COUSSIN'][delai] = 9;
$type_docs['OF - COUSSIN'][type_dataset_entete] = "ofentete";
$type_docs['OF - COUSSIN'][directivecoupe] = 1;
$type_docs['OF - COUSSIN'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - COUSSIN'][type_dataset_ligne] = "OF - COUSSIN";
$type_docs['OF - COUSSIN'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - COUSSIN'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - COUSSIN'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - COUSSIN'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - COUSSIN'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - COUSSIN'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - COUSSIN'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - COUSSIN'][btn][imprimer][libelle] = "Imprimer";
?>