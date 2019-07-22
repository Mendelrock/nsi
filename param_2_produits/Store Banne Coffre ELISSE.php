<?php
$produits['Store Banne Coffre ELISSE'] = array (
 "libelle" => "Store Banne Coffre ELISSE",
 "type_produit" => "produitfini",
 "type_dataset_ligne" => "fdcproduitstorebannecoffreelisse",
 "type_dataset_groupe_ligne" => "produitentetegenerique",
 "fonction" => "param_5_fonctions/banne.php",
 "of" => array (
  1 => "COMMANDE - STORE - BANNE - COFFRE - ELISSE",
  2 => "OF - PICKING - BANNE"
 )
);

$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][produit_bc] = "get_produit_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][repere] = "get_repere_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][largeur_bc] = "get_largeur_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][avancee_bc] = "get_avancee_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][type_commande_bc] = "get_type_commande_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][cote_commande_bc] = "get_cote_commande_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][hauteur_manivelle_bc] = "get_hauteur_manivelle_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][type_pose_bc] = "get_type_pose_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][lambrequin_bc] = "get_lambrequin_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][toile_bc] = "get_toile_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][automatisme_bc] = "get_automatisme_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][coloris_armature_bc] = "get_coloris_armature_bc";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][quantite_bc] = "get_quantite";
$produits['Store Banne Coffre ELISSE'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][typedecommande_sbcl] = "get_type_commande_bc";

$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][produit_banne] = "get_produit_banne";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][type_commande_banne_of_picking] = "get_type_commande_banne";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][automatisme_of_picking] = "get_automatisme";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][kit_pose_of_picking] = "get_kit_pose";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][largeur_storebanne_commande] = "get_largeur_storebanne";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][avancee_storebanne_commande] = "get_avancee_storebanne";
$produits['Store Banne Coffre ELISSE'][fonction_init]['OF - PICKING - BANNE'][quantite_of_picking] = "get_quantite";


?>
