<?
$type_docs['OF - COMMANDE TOILES SODICLAIR'][libelle] = "Commande Toiles SODICLAIR";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][delai] = 0;
$type_docs['OF - COMMANDE TOILES SODICLAIR'][type_dataset_entete] = "ofentete";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][type_dataset_empied] = "ofempied_conso";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][type_dataset_ligne] = "OF - COMMANDE TOILES SODICLAIR";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['OF - COMMANDE TOILES SODICLAIR'][btn][imprimer][libelle] = "Imprimer";
?>