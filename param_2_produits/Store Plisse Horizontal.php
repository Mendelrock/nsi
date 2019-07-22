<?php
$produits['Store Plisse Horizontal'] = array (
	"libelle" => "Store Plisse Horizontal",
	"type_produit" => "produitfini",
	"type_dataset_ligne" => "fdcproduitstoreplissehorizontal",
	"type_dataset_groupe_ligne" => "produitentetegenerique",
	"fonction" => "param_5_fonctions/banne.php",
	"of" => array (
		1 => "COMMANDE - STORE - PLISSE - HORIZONTAL"
	)
);

$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][produit_bc] = "get_produit_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][repere] = "get_repere_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][largeur_bc] = "get_largeur_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][avancee_bc] = "get_avancee_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][type_commande_bc] = "get_type_commande_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][guidage] = "get_guidage_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][hauteur_perche_bc] = "get_hauteur_perche_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][toile_bc] = "get_toile_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][type_pose_bc] = "get_type_pose_bc";
$produits['Store Plisse Horizontal'][fonction_init]['COMMANDE - STORE - PLISSE - HORIZONTAL'][quantite_bc] = "get_quantite";


?>