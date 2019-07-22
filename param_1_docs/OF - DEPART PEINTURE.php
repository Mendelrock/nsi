<?
$type_docs['OF - DEPART PEINTURE'][libelle] = "Départ PEINTURE";
$type_docs['OF - DEPART PEINTURE'][delai] = 0;
$type_docs['OF - DEPART PEINTURE'][type_dataset_entete] = "ofentete";
$type_docs['OF - DEPART PEINTURE'][type_dataset_empied] = "fdcentete";
$type_docs['OF - DEPART PEINTURE'][type_dataset_ligne] = "OF - DEPART PEINTURE";
$type_docs['OF - DEPART PEINTURE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['OF - DEPART PEINTURE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['OF - DEPART PEINTURE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['OF - DEPART PEINTURE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['OF - DEPART PEINTURE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['OF - DEPART PEINTURE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['OF - DEPART PEINTURE'][btn][imprimer][php]   = "javascript:window.open('imprimer_fdc.php?id_doc=. id_doc');void(0);";
$type_docs['OF - DEPART PEINTURE'][btn][imprimer][libelle] = "Imprimer";
?>