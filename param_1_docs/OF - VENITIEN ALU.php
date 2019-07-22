<?
$type_docs['OF - VENITIEN ALU'][libelle] = "Bon de Fabrication STORE VENITIEN ALUMINIUM";
$type_docs['OF - VENITIEN ALU'][delai] = 9;
$type_docs['OF - VENITIEN ALU'][type_dataset_entete] = "ofentete";
$type_docs['OF - VENITIEN ALU'][type_dataset_ligne] = "OF - VENITIEN ALU";
$type_docs['OF - VENITIEN ALU'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - VENITIEN ALU'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - VENITIEN ALU'][consommation] = "consommation_unitaire";
$type_docs['OF - VENITIEN ALU'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - VENITIEN ALU'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - VENITIEN ALU'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - VENITIEN ALU'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - VENITIEN ALU'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - VENITIEN ALU'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - VENITIEN ALU'][btn][imprimer][libelle] = "Imprimer";
?>