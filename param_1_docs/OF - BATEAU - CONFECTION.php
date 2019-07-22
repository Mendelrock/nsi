<?
$type_docs["OF - BATEAU - CONFECTION"][libelle] = "Bon de Fabrication STORE BATEAUX - CONFECTION TISSU";
$type_docs['OF - BATEAU - CONFECTION'][delai] = 13;
$type_docs['OF - BATEAU - CONFECTION'][type_dataset_entete] = "ofentete";
$type_docs['OF - BATEAU - CONFECTION'][directivecoupe] = 1;
$type_docs['OF - BATEAU - CONFECTION'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - BATEAU - CONFECTION'][type_dataset_ligne] = "OF - BATEAU - CONFECTION";
$type_docs['OF - BATEAU - CONFECTION'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - BATEAU - CONFECTION'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - BATEAU - CONFECTION'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - BATEAU - CONFECTION'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - BATEAU - CONFECTION'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - BATEAU - CONFECTION'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - BATEAU - CONFECTION'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - BATEAU - CONFECTION'][btn][imprimer][libelle] = "Imprimer";
?>