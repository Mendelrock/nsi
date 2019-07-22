<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE == 1) {
   $liste_enregistrement = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));
   if(is_array($liste_enregistrement)) {
      foreach ($liste_enregistrement as $i => $record) {
         if ($message) {
            break;   
         }
  	  }
  	  if (!$message) {
  	  	 foreach ($liste_enregistrement as $i => $record) {
  	     	   $site_propriete = $_POST[$record[site_propriete]];
  	     	   $nsi_produit = $_POST[$record[nsi_produit]];
  	     	   $nsi_propriete = $_POST[$record[nsi_propriete]];
  	     	   if($site_propriete_avant){	
  	              new db_sql("update correspondance_propriete set site_propriete='$site_propriete',nsi_produit='$nsi_produit',nsi_propriete='$nsi_propriete' where site_propriete='$site_propriete_avant' and nsi_produit='$nsi_produit_avant'");
 	           }
  	     	   else{
			      new db_sql("insert correspondance_propriete (site_propriete,nsi_produit,nsi_propriete) values ('$site_propriete','$nsi_produit', '$nsi_propriete')");  
			      //$Id_correspondance = db_sql::last_id ();
			   }	
 		       ?>
 		       <SCRIPT LANGUAGE=Javascript>
 		           window.close();
               </SCRIPT>
               <?
 		 }   
  	  }
   }	
}
if ($message) {
} else if ($site_propriete) {
   $req="
      SELECT 
         * 
      FROM
         correspondance_propriete
      WHERE 
	     site_propriete='$site_propriete' and nsi_produit='$nsi_produit'";
   $resultat = new db_sql($req);
   $resultat->n();
   $site_propriete=$resultat->f("site_propriete");
   $nsi_produit=$resultat->f("nsi_produit");
   $nsi_propriete=$resultat->f("nsi_propriete");
}
$liste_enregistrement = array();
?>
<body class="application">
<FORM method=post name=formulaire action=creer_correspondance_propriete.php?ACTE=1>
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Correspondance</td>
    </tr>
   </table> 
  </td>
 </tr>
</table> 
<table class="requeteur">
   <tr>
      <td class="requet_right">Site propriété</td>
      <td class="requet_left" ><? champ_ouvert_droit("client","site_propriete",$site_propriete,60, 35, "O"); ?></td>
   </tr>
   <tr>
      <td class="requet_right">NSI produit</td>
      <td class="requet_left" ><? champ_ouvert_droit("client","nsi_produit",$nsi_produit,60, 35, "O"); ?></td>
   </tr>
   <tr>
      <td class="requet_right">NSI propriété</td>
      <td class="requet_left" ><? champ_ouvert_droit("client","nsi_propriete",$nsi_propriete,60, 35, "O"); ?></td>
   </tr>
   <?
      $liste_enregistrement[$Id_toile]=array("site_propriete"=>"site_propriete","nsi_produit"=>"nsi_produit","nsi_propriete"=>"nsi_propriete"); 
   ?>
</table>
<?
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> <INPUT class=requeteur_button onclick="window.close();" name=Annuler value=Annuler type=button>
<?
}
?>
<input type="hidden" name="site_propriete_avant" value="<? echo $site_propriete; ?>">
<input type="hidden" name="nsi_produit_avant" value="<? echo $nsi_produit; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<?
include ("ress/enpied.php");
?>