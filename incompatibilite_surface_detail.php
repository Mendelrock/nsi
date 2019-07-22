<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE == 1) {
      $message = validateIncompatibiliteSurface($_POST);
      if(!$min)$min="NULL";
      if(!$max)$max="NULL";
  	  if (!$message) {
  	     	   	$min = str_replace(",",".",$min);$max = str_replace(",",".",$max);
  	     	   if($Id_incompatibilite){	
  	     	         new db_sql("update incompatibilites_surface set 
  	     	         	produit='$produit',
  	     	         	prop1='$prop1',
  	     	         	val1='$val1',
  	     	         	prop2='$prop2',
  	     	         	prop3='$prop3',
  	     	         	min=$min,
  	     	         	max=$max
  	     	         	where id_incompatibilite=$Id_incompatibilite");
					} else {
						new db_sql("insert incompatibilites_surface (produit,prop1,val1,prop2,prop3,min,max) values (
				     '$produit',
				     '$prop1',
				     '$val1',
				     '$prop2',
				     '$prop3',
				     $min,
				     $max)");  
				     $Id_incompatibilite = db_sql::last_id ();
			      }	  
  	  }
} else if($ACTE == 2 && $Id_incompatibilite) {
	$req = new db_sql(" DELETE FROM incompatibilites_surface WHERE id_incompatibilite = $Id_incompatibilite ");
}
$onglets_manquant_adresse = "";
$onglets_manquant_libelle = "Choisir";
$files = scandir("param_2_produits");
foreach ($files as $i => $fichier) {	
   //foreach ($GLOBALS[parms][produits] as $produit => $infos_produit) {
   	  if($fichier!="." && $fichier!=".."){
      	 $produit = substr($fichier,0,strlen($fichier)-4);
         $onglets_manquant_adresse .= "|".$produit;
         $onglets_manquant_libelle .= "|".$produit;
      }
   }
if ($message) {
} else if ($Id_incompatibilite) {
   $req="
      SELECT 
         * 
      FROM
         incompatibilites_surface
      WHERE 
	     id_incompatibilite = $Id_incompatibilite";
   $resultat = new db_sql($req);
   $resultat->n();
   $produit    = $resultat->f("produit");
   $prop1         = $resultat->f("prop1");
   $val1    = $resultat->f("val1");
   $prop2  = $resultat->f("prop2");
   $prop3  = $resultat->f("prop3");
   $min     = round($resultat->f("min"),4);
   $max     = round($resultat->f("max"),4);
}
$liste_enregistrement = array();
?>
<body class="application">
<FORM method=post name=formulaire action=incompatibilite_surface_detail.php?ACTE=1>
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Incompatibilité</td>
     <td class='externe'><A class='externe' href='incompatibilite_surface_list.php?liste_go=1'>Retour à la liste</A></td>
    </tr>
   </table> 
  </td>
 </tr>
</table> 
<table class="requeteur">
   <tr>
      <td class="requet_right">Produit</td>
      <td class="requet_left" ><? drop_down_droit ("L","","produit", "", "", $produit, false, "incompatibilites", $fg_oblig="N", $onglets_manquant_adresse, $onglets_manquant_libelle); ?></td>
      <td class="requet_right" ></td>
      <td class="requet_left"></td>
      <td class="requet_right"></td>
      <td class="requet_left"></td>
   </tr>
<tr>
      <td class="requet_right" >Propriété 1</td>
      <td class="requet_left"><? champ_ouvert_droit("O","prop1",$prop1,60, 35, "O"); ?></td>
      <td class="requet_right">Valeur 1</td>
      <td class="requet_left"><? champ_ouvert_droit("O","val1",$val1,60, 35, "O");?></td>
      <td class="requet_right"></td>
      <td class="requet_left"></td>
   </tr>
  </tr>
  <tr>
      <td class="requet_right">Champ hauteur</td>
      <td class="requet_left" ><? champ_ouvert_droit("O","prop2",$prop2,60, 35, "O"); ?></td>
      <td class="requet_right" ></td>
      <td class="requet_left"></td>
      <td class="requet_right"></td>
      <td class="requet_left"></td>
   </tr>
   <tr>
      <td class="requet_right" >Champ largeur</td>
      <td class="requet_left"><? champ_ouvert_droit("O","prop3",$prop3,60, 35, "O"); ?></td>
      <td class="requet_right" ></td>
      <td class="requet_left"></td>
      <td class="requet_right"></td>
      <td class="requet_left"></td>
   </tr>
   <tr>
      <td class="requet_right" >Surface mini (m2)</td>
      <td class="requet_left"><? champ_ouvert_droit("O","min",$min,60, 35, "O"); ?></td>
      <td class="requet_right" >Surface maxi (m2)</td>
      <td class="requet_left"><? champ_ouvert_droit("O","max",$max,60, 35, "O"); ?></td>
      <td class="requet_right"></td>
      <td class="requet_left"></td>
   </tr>
  
</table>
<?
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> 
<?
	if($Id_incompatibilite) {
		echo '<INPUT class=requeteur_button id="delete" value=Supprimer type=submit> ';
	}
	?>
	<INPUT class=requeteur_button onclick="window.close();" name=Annuler value=Annuler type=button>
<?
}
?>
<input type="hidden" name="ACTE" value="1">
<input type="hidden" name="Id_incompatibilite" value="<? echo $Id_incompatibilite; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<script>
$('#delete').on('click', function() {
	$('[name="ACTE"]').val(2);
	document.formulaire.submit();
})
</script>
<?
include ("ress/enpied.php");
?>