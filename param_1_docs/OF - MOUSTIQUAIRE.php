<?
$type_docs['OF - MOUSTIQUAIRE'][libelle] = "Bon de Fabrication MOUSTIQUAIRE";
$type_docs['OF - MOUSTIQUAIRE'][delai] = 7;
$type_docs['OF - MOUSTIQUAIRE'][type_dataset_entete] = "ofentete";
$type_docs['OF - MOUSTIQUAIRE'][directivecoupe] = 1;
$type_docs['OF - MOUSTIQUAIRE'][type_dataset_empied] = "ofempied_dircoup";
$type_docs['OF - MOUSTIQUAIRE'][type_dataset_ligne] = "OF - MOUSTIQUAIRE";
$type_docs['OF - MOUSTIQUAIRE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - MOUSTIQUAIRE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - MOUSTIQUAIRE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - MOUSTIQUAIRE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - MOUSTIQUAIRE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - MOUSTIQUAIRE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - MOUSTIQUAIRE'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - MOUSTIQUAIRE'][btn][imprimer][libelle] = "Imprimer";
?>