<?php
include ("ress/var_session.php");
require_once("ress/register_globals.php");
register_globals('gp');
require_once("ress/db_mysql.php");
require_once("c_o_dataset.php");
$ope=$_POST['ope'];


if ($ope==1) {
   $id_article=$_POST['id_article'];
   $qtestock=$_POST['qtestock'];
   $resultat = get_stock_article($id_article);
   $delta_stock = $qtestock - $resultat[logique];
   $requete= new db_sql();
   $requete->q("update article set qt_stock = qt_stock + $delta_stock where id_article = $id_article");
   $_SESSION[liste_idarticle_qtestock][$id_article]=$qtestock;
   echo $id_article.'[SEPVAL]'.$qtestock;
} else if($ope==2) {
   $id_article=$_POST['id_article'];	
   $id_fournisseur=$_POST['id_fournisseur'];	
   $id_fournisseurold=$_POST['id_fournisseurold'];	
   //On calcule la quantité à acheter
   $qtestock=$_POST['qtestock'];
   $qt_max=$_POST['qt_max'];
   $qt_mini=$_POST['qt_mini'];
   $_SESSION[liste_idarticle_idfournisseur][$id_article]=$id_fournisseur;
   $res= charge_un("SELECT fournisseur.Id_fournisseur,id_article,quotite  FROM fournisseur,fournisseur_article WHERE fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur AND id_article=" . $id_article . " AND fournisseur_article.Id_fournisseur=".$id_fournisseur);
   $quotite = $res[quotite];
   if ($quotite<=0) 
      $quotite = 1;
   if($qt_mini>$qtestock){
      $qte_stock = $quotite*ceil(($qt_max-$qtestock)/$quotite);
   } else	{
      $qte_stock = 0;
   }	
   $_SESSION[liste_idarticle_idfournisseur][$id_article."_aacheter"]=$qte_stock;
   echo $id_fournisseurold.'[SEPVAL]'.$id_article.'[SEPVAL]'.$res[quotite].'[SEPVAL]'.$qte_stock;
} else if ($ope==3) {
   $_SESSION[liste_idarticle_idfournisseur][$_POST['id_article']."_aacheter"]=$_POST['qtestock'];
} else if($ope==4) {
   $id_article=$_POST['id_article'];	
   $id_fournisseur=$_POST['id_fournisseur'];	
   $quotite=$_POST['quotite'];
   $qtestock=$_POST['qtestock'];
   $qt_max=$_POST['qt_max'];
   $qt_mini=$_POST['qt_mini'];	
   if ($quotite<=0) 
      $quotite = 1;
   if($qt_mini>$qtestock){
      $qte_stock = $quotite*ceil(($qt_max-$qtestock)/$quotite);
   } else {
   	  $qte_stock = 0;
   }  
   
   $resultat = get_stock_article($id_article);
   $delta_stock = $qtestock - $resultat[logique];
   $requete= new db_sql();
   $requete->q("update article set qt_stock = qt_stock + $delta_stock where id_article = $id_article");
   $_SESSION[liste_idarticle_qtestock][$id_article]=$qtestock;
   $_SESSION[liste_idarticle_idfournisseur][$id_article."_aacheter"]=$qte_stock;
   echo $id_fournisseur.'[SEPVAL]'.$id_article.'[SEPVAL]'.$qte_stock.'[SEPVAL]'.$_POST['numligne'];
} else if($ope==5) {
   $site_propriete=$_POST['site_propriete'];	
   $site_valeur=$_POST['site_valeur'];
   $nsi_propriete=$_POST['nsi_propriete'];
   $nsi_valeur=$_POST['nsi_valeur'];
   $commande=$_POST['commande'];
   $requete="SELECT * FROM correspondance_valeur_temp WHERE 
		site_propriete = '$site_propriete' AND 
		site_valeur = '$site_valeur' AND 
		nsi_propriete = '$nsi_propriete' AND 
		site_propriete = '$site_propriete' AND 
		commande = '$commande'";   
   $resultat = new db_sql($requete);
   if($resultat->num_rows()>=1) {
   	  $requete= new db_sql();
   	  $req="update correspondance_valeur_temp set nsi_valeur = '$nsi_valeur' WHERE 
		site_propriete = '$site_propriete' AND 
		site_valeur = '$site_valeur' AND 
		nsi_propriete = '$nsi_propriete' AND 
		site_propriete = '$site_propriete' AND 
		commande = '$commande'"; 
	  $requete->q("SET NAMES 'utf8'");
	  $requete->q($req);
   } else {
   	  $requete= new db_sql();
   	  $req="insert into correspondance_valeur_temp 
		(commande,site_propriete,site_valeur,nsi_propriete,nsi_valeur) values 
		('$commande','$site_propriete','$site_valeur','$nsi_propriete','$nsi_valeur')";
	  $requete->q("SET NAMES 'utf8'");
      $requete->q($req);
   }
} else if($ope==6) {
	$gamme=$_POST['gamme'];	
    $couleur=$_POST['couleur'];	
    $site_propriete=$_POST['site_propriete'];	
    $site_valeur=$_POST['site_valeur'];	
    $nsi_produit=$_POST['nsi_produit'];
    $nsi_propriete=$_POST['nsi_propriete'];
    $nsi_valeur=$_POST['nsi_valeur'];
    $requete="SELECT * FROM toile_ancien where gamme='$gamme' and couleur='$couleur'";   
    $req = new db_sql($requete);
    while ($req->n()) {
       $lb_toile_sr = $req->f('lb_toile_sr');
    }
    if($lb_toile_sr){
      $requete= new db_sql();
      $req="insert into correspondance_valeur_temp (commande, site_propriete,site_valeur,nsi_produit,";
   	  if($nsi_propriete=="gammetringle" || $nsi_propriete=="embouts_tringle") $req.="nsi_propriete,";
	  $req.="nsi_valeur) values ('$commande', '$site_propriete','$site_valeur','$nsi_produit',";
	  if($nsi_propriete=="gammetringle" || $nsi_propriete=="embouts_tringle") $req.="'$nsi_propriete',";
	  $req.="'$lb_toile_sr')";
	  $requete->q("SET NAMES 'utf8'");
      $requete->q($req);
    }
    //echo $requete;
} else if ($ope==7) {
   $produits_nsi=$_POST['produits_nsi'];
   $id_champ=$_POST['id_champ'];
   $val=$_POST['val'];
   $requete="SELECT * FROM correspondance_propriete WHERE nsi_propriete = '$id_champ' AND nsi_produit = '$produits_nsi'";   
   $resultat = new db_sql($requete);
   if($resultat->num_rows()>=1){
   	$requete= new db_sql();
   	$req="update correspondance_propriete set site_propriete ='$val' WHERE nsi_propriete = '$id_champ' AND nsi_produit = '$produits_nsi'";
	  	$requete->q("SET NAMES 'utf8'");
	  	$requete->q($req);
   } else {
   	$requete= new db_sql();
   	$req="insert into correspondance_propriete (nsi_propriete,nsi_produit,site_propriete) values ('$id_champ','$produits_nsi','$val')";
	   $requete->q("SET NAMES 'utf8'");
      $requete->q($req);
   }
} else if ($ope==8) {
   $commande=$_POST['commande'];	
   $nomproduit=$_POST['nomproduit'];
   $val=$_POST['val'];
   $requete="SELECT * FROM correspondance_produit_temp WHERE site_produit = '$nomproduit' AND commande = '$commande'";   
   $resultat = new db_sql($requete);
   if($resultat->num_rows()>=1){
   	$requete= new db_sql();
   	$req="update correspondance_produit_temp set nsi_produit ='$val' WHERE site_produit = '$nomproduit' AND commande = '$commande'";
	  	$requete->q("SET NAMES 'utf8'");
	  	$requete->q($req);
   } else {
   	$requete= new db_sql();
   	$req="insert into correspondance_produit_temp (commande,nsi_produit,site_produit) values ('$commande','$val','$nomproduit')";
	   $requete->q("SET NAMES 'utf8'");
      $requete->q($req);
   }
}
?>
