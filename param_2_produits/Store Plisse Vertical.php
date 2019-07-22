<?php
$produits['Store Plisse Vertical'] = array (
"libelle" => "Store Plisse Vertical",
"type_produit" => "produitfini",
"type_dataset_ligne" => "fdcproduitstoreplissevertical",
"type_dataset_groupe_ligne" => "produitentetegenerique",
"fonction" => "param_5_fonctions/banne.php",
"of" => array (
    1 => "COMMANDE - STORE - PLISSE - VERTICAL"
)
);

$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][produit_bc] = "get_produit_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][repere] = "get_repere_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][largeur_bc] = "get_largeur_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][hauteur_bc] = "get_hauteur_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][type_commande_bc] = "get_type_commande_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][guidage] = "get_guidage_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][cote_commande_bc] = "get_cote_commande_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][hauteur_commande_bc] = "get_hauteur_commande_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][toile_bc] = "get_toile_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][type_pose_bc] = "get_type_pose_bc";
$produits['Store Plisse Vertical'][fonction_init]['COMMANDE - STORE - PLISSE - VERTICAL'][quantite_bc] = "get_quantite";
?>