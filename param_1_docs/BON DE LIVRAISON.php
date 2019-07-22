<?php

$type_docs['BON DE LIVRAISON'][libelle] = "Bon de livraison";
$type_docs['BON DE LIVRAISON'][orientation] = "L";
$type_docs['BON DE LIVRAISON'][type_dataset_entete] = "bonentete";
$type_docs['BON DE LIVRAISON'][type_dataset_ligne] = "OF - BON - DE - LIVRAISON";

$type_docs['BON DE LIVRAISON'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['BON DE LIVRAISON'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['BON DE LIVRAISON'][btn][imprimer][php]   = "javascript:window.open('imprimer_fdc.php?id_doc=. id_doc');void(0);";
$type_docs['BON DE LIVRAISON'][btn][imprimer][libelle] = "Imprimer le bon de livraison";

?>