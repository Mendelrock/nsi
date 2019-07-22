<?php
$produits['Store Banne'][libelle] = "Store Banne";
$produits['Store Banne'][type_produit] = "produitfini";
$produits['Store Banne'][type_dataset_ligne] = "fdcproduitstorebanne";
$produits['Store Banne'][type_dataset_groupe_ligne] = "produitentetegenerique";
$produits['Store Banne'][fonction] = "param_5_fonctions/banne.php";
$produits['Store Banne'][of][1] = "OF - PICKING - BANNE";
$produits['Store Banne'][of][2] = "COMMANDE - STORE - BANNE";

$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][produit_banne] = "get_produit_banne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][repere] = "get_repere_bc";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][typepose_banne_commande] = "get_typepose_banne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][cote_commande_banne_commande] = "get_cote_commande_banne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][largeur_storebanne_commande] = "get_largeur_storebanne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][avancee_storebanne_commande] = "get_avancee_storebanne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][type_commande_banne_commande] = "get_type_commande_banne";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][hauteur_manivelle] = "get_hauteur_manivelle";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][coloris_armature_commande] = "get_coloris_armature";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][reference_toile_commande] = "get_reference_toile";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][lambrequin_commande] = "get_lambrequin";
$produits['Store Banne'][fonction_init]['COMMANDE - STORE - BANNE'][quantite_commande] = "get_quantite";

$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][produit_banne] = "get_produit_banne";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][type_commande_banne_of_picking] = "get_type_commande_banne";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][automatisme_of_picking] = "get_automatisme";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][kit_pose_of_picking] = "get_kit_pose";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][largeur_storebanne_commande] = "get_largeur_storebanne";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][avancee_storebanne_commande] = "get_avancee_storebanne";
$produits['Store Banne'][fonction_init]['OF - PICKING - BANNE'][quantite_of_picking] = "get_quantite";
?>