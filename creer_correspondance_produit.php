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
  	     	   $site_produit = $_POST[$record[site_produit]];
  	     	   $nsi_produit = $_POST[$record[nsi_produit]];
  	     	   if($site_produit_avant){	
  	              new db_sql("update correspondance_produit set site_produit='$site_produit',nsi_produit='$nsi_produit' where site_produit='$site_produit_avant'");
 	           }
  	     	   else{
			      new db_sql("insert correspondance_produit (site_produit,nsi_produit) values ('$site_produit', '$nsi_produit')");  
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
} else if ($site_produit) {
   $req="
      SELECT 
         * 
      FROM
         correspondance_produit
      WHERE 
	     site_produit = '$site_produit'";
   $resultat = new db_sql($req);
   $resultat->n();
   $site_produit=$resultat->f("site_produit");
   $nsi_produit=$resultat->f("nsi_produit");
}
$liste_enregistrement = array();
?>
<body class="application">
<FORM method=post name=formulaire action=creer_correspondance_produit.php?ACTE=1>
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
      <td class="requet_right">Site produit</td>
      <td class="requet_left" ><?echo("<textarea name=\"site_produit\" rows=6 cols=60>$site_produit</textarea><input type='hidden' name='h_site_produit' value=\"O\">")?></td>
   </tr>
   <tr>
      <td class="requet_right">NSI produit</td>
      <td class="requet_left" ><? champ_ouvert_droit("client","nsi_produit",$nsi_produit,60, 35, "O"); ?></td>
   </tr>
   <?
      $liste_enregistrement[$Id_toile]=array("site_produit"=>"site_produit","nsi_produit"=>"nsi_produit"); 
   ?>
</table>
<?
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> <INPUT class=requeteur_button onclick="window.close();" name=Annuler value=Annuler type=button>
<?
}
?>
<input type="hidden" name="site_produit_avant" value="<? echo $site_produit; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<?
include ("ress/enpied.php");
?>