<?
include("ress/entete.php");
require_once("c_o_dataset.php");
require_once("ress/fpdf/fpdf.php");
require_once("po_imprimer_include.php");

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
<?
if ($ACTE == 1) {
/*
   class PDF extends FPDF
   {
      public $x1;//abcisse courante
      public $y1;//ordonnée courante
	  function PageWidth(){
         return (int) $this->w-$this->rMargin-$this->lMargin;
      }
      
      function PageHeight(){
         return (int) $this->h-$this->tMargin-$this->bMargin;
      }
   }
*/   	
   $listefournisseur_envoi_mail = array();	
   foreach ($_SESSION[liste_idarticle_commande] as $idlibelle => $tabarticle) {
      list($id_fournisseur,$lb_fournisseur) = explode("|",$idlibelle);
      $resultat = charge_un("select * from fournisseur where id_fournisseur=$id_fournisseur");
      $id_statut_po = get_Id_statut_po($resultat[envoi_automatique]);	
      new db_sql("insert into po(date_po,id_fournisseur,id_statut_po) values (NOW(), $id_fournisseur,$id_statut_po)");
      $id_po = db_sql::last_id ();
      new db_sql("update po set lb_po = '".getdate()[year]."-".substr("00".getdate()[mon],-2)."-SOD".substr("000000".$id_po,-5)."' where id_po = $id_po");
      if($resultat[envoi_automatique]==1 && $resultat[adresse_mail] && !array_key_exists($id_po, $listefournisseur_envoi_mail)){
         $listefournisseur_envoi_mail[$id_po]=$id_po;
      }
      foreach ($tabarticle as $i => $lignearticle) {
      	if(!$lignearticle[prix]) $lignearticle[prix] = 0;
      	new db_sql("insert into po_ligne(id_po,id_article,qt,qt_solde,pu) values ($id_po, $lignearticle[id_article],$lignearticle[qt],$lignearticle[qt],$lignearticle[prix])");	
		}
   }
   foreach ($listefournisseur_envoi_mail as $i => $id_po) {
      //send_commande_by_mail($id_po);
	   po_imprimer($id_po,true) ;	
   }
?>	
<table class="resultat">
      <tr>
         <td class="resultat_tittle">Toutes les commandes sont passées avec succés</td>
      </tr>
</table>
<?
} else {
   $_SESSION[liste_idarticle_commande]=array();
   foreach ($_SESSION[liste_article] as $i => $lignearticle) {
		if($lignearticle[Id_fournisseur]){	
	      $stock = $lignearticle[Aacheter];
	      if(array_key_exists($lignearticle[id_article]."_aacheter", $_SESSION[liste_idarticle_idfournisseur])) 
	         $stock=$_SESSION[liste_idarticle_idfournisseur][$lignearticle[id_article]."_aacheter"]; 
	      if($stock>0){	
	         $id_fournisseur = $lignearticle[Id_fournisseur];
	         $lb_fournisseur = $lignearticle[lb_fournisseur];
	         $quotite = $lignearticle[quotite];
	         $qt_max = $lignearticle[qt_max];
	         $qt_mini = $lignearticle[qt_mini];
	         $prix = $lignearticle[prix];
	         $envoi_automatique = $lignearticle[envoi_automatique];
	         $adresse_mail = $lignearticle[adresse_mail];
			 $total_achat = $prix*$stock;
	         if(array_key_exists($lignearticle[id_article], $_SESSION[liste_idarticle_idfournisseur])) {
	            $id_fournisseur=$_SESSION[liste_idarticle_idfournisseur][$lignearticle[id_article]];
	            $resultat = charge_un("select * from fournisseur,fournisseur_article where fournisseur.id_fournisseur=$id_fournisseur and fournisseur_article.id_article = $lignearticle[id_article] and fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur");
		         $lb_fournisseur =$resultat[lb_fournisseur];
		         $quotite = $resultat[quotite];
		         $prix = $resultat[prix];
		         $envoi_automatique = $resultat[envoi_automatique];
		         $adresse_mail = $resultat[adresse_mail];
	         } 
	         $_SESSION[liste_idarticle_commande][$id_fournisseur."|".$lb_fournisseur][]=array("id_article"=>$lignearticle[id_article],"lb_article"=>$lignearticle[lb_article],"id_fournisseur"=>$id_fournisseur,"qt"=>$stock,"prix"=>$prix,"envoi_automatique"=>$envoi_automatique,"adresse_mail"=>$adresse_mail,"total_achat"=>$total_achat);
	      }
		}    
   }  
?>	
<form name="formulaire" method="post" action="article_list_commande.php?ACTE=1">
<?
   foreach ($_SESSION[liste_idarticle_commande] as $idlibelle => $tabarticle) {
      list($id_fournisseur,$lb_fournisseur) = explode("|",$idlibelle);
?>   

   <BR>
   <table style="width:500;background-color:White;border :none;cellpadding:0px;cellspacing:0px;">
      <tr>
         <td class="resultat_tittle" colspan = 3><? echo $lb_fournisseur ?></td>
      </tr>
      <tr>
         <td class="resultat_tittle" width="400">Libellé</td>
         <td class="resultat_tittle" width="100">A acheter</td>
         <td class="resultat_tittle" width="100">Total</td>
      </tr>
<?
$total_commande = 0;
foreach ($tabarticle as $i => $lignearticle) {
	$total_commande += $lignearticle[total_achat];
?>
      <tr>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $lignearticle[lb_article] ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $lignearticle[qt]?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $lignearticle[total_achat]?></td>
	  </tr>
<?
   }
   ?>
   <tr>
	<td class="resultat_tittle" align="right" colspan = 3>Total de la commande : <? echo $total_commande ?></td>
   </tr>
   <?php
   echo ("</table>");
}
/*if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='5'>Cliquer sur le libellé pour ouvrir un article</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
}*/
echo "<BR><input class=\"requeteur_button\" type=\"submit\" name=\"Submit\" value=\"Confirmer\" OnClick=\"document.getElementsByName('Submit')[0].style.visibility='hidden';\">
</form>";

        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
}
?>
