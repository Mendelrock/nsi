<?php
$produits['Store Banne Coffre COMPACTO'] = array (
 "libelle" => "Store Banne Coffre COMPACTO",
 "type_produit" => "produitfini",
 "type_dataset_ligne" => "fdcproduitstorebannecoffrecompacto",
 "type_dataset_groupe_ligne" => "produitentetegenerique",
 "fonction" => "param_5_fonctions/banne.php",
 "of" => array (
  1 => "COMMANDE - STORE - BANNE - COFFRE - COMPACTO",
  2 => "OF - PICKING - BANNE"
 )
);

$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][produit_bc] = "get_produit_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][repere] = "get_repere_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][largeur_bc] = "get_largeur_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][avancee_bc] = "get_avancee_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][type_commande_bc] = "get_type_commande_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][cote_commande_bc] = "get_cote_commande_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][hauteur_manivelle_bc] = "get_hauteur_manivelle_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][type_pose_bc] = "get_type_pose_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][lambrequin_bc] = "get_lambrequin_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][toile_bc] = "get_toile_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][automatisme_bc] = "get_automatisme_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][coloris_armature_bc] = "get_coloris_armature_bc";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][quantite_bc] = "get_quantite";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['COMMANDE - STORE - BANNE - COFFRE - COMPACTO'][typedecommande_sbcc] = "get_type_commande_bc";

$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][produit_banne] = "get_produit_banne";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][type_commande_banne_of_picking] = "get_type_commande_banne";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][automatisme_of_picking] = "get_automatisme";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][kit_pose_of_picking] = "get_kit_pose";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][largeur_storebanne_commande] = "get_largeur_storebanne";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][avancee_storebanne_commande] = "get_avancee_storebanne";
$produits['Store Banne Coffre COMPACTO'][fonction_init]['OF - PICKING - BANNE'][quantite_of_picking] = "get_quantite";

?>