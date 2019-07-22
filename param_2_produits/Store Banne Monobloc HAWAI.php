<?php
$produits['Store Banne Monobloc HAWAI'] = array (
	"libelle" => "Store Banne Monobloc HAWAI",
	"type_produit" => "produitfini",
	"type_dataset_ligne" => "fdcproduitstorebannemonobloc",
	"type_dataset_groupe_ligne" => "produitentetegenerique",
	"fonction" => "param_5_fonctions/banne.php",
	"of" => array (
		1 => "COMMANDE - STORE - BANNE - MONOBLOC - HAWAI",
		2 => "OF - PICKING - BANNE"
	)
);

$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][produit_bc] = "get_produit_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][repere] = "get_repere_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][largeur_bc] = "get_largeur_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][avancee_bc] = "get_avancee_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][type_commande_bc] = "get_type_commande_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][cote_commande_bc] = "get_cote_commande_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][hauteur_manivelle_bc] = "get_hauteur_manivelle_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][type_pose_bc] = "get_type_pose_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][lambrequin_bc] = "get_lambrequin_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][toile_bc] = "get_toile_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][auvent_bc] = "get_auvent_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][automatisme_bc] = "get_automatisme_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][coloris_armature_bc] = "get_coloris_armature_bc";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][quantite_bc] = "get_quantite";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['COMMANDE - STORE - BANNE - MONOBLOC - HAWAI'][typedecommande_sbmo] = "get_type_commande_bc";

$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][produit_banne] = "get_produit_banne";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][type_commande_banne_of_picking] = "get_type_commande_banne";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][automatisme_of_picking] = "get_automatisme";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][kit_pose_of_picking] = "get_kit_pose";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][largeur_storebanne_commande] = "get_largeur_storebanne";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][avancee_storebanne_commande] = "get_avancee_storebanne";
$produits['Store Banne Monobloc HAWAI'][fonction_init]['OF - PICKING - BANNE'][quantite_of_picking] = "get_quantite";
?>