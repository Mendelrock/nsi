<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
function validateIncompatibiliteRapport($record){
   if(!$_POST[produit])	
     return "Le champ Produit doit être renseigné";
   else if(!$_POST[prop1])	
     return "Le champ Propriété 1 doit être renseigné";  
   else if(!$_POST[val1])	
     return "Le champ Valeur 1 doit être renseigné"; 
   else if(!$_POST[prop2])	
     return "Le champ Propriété 2 doit être renseigné";   
   else if(!$_POST[prop3])	
     return "Le champ Propriété 3 doit être renseigné";
   else if(!$_POST[x] && !$_POST[y])	
     return "Au moins un des champs x et y doit être renseigné";
}
if ($ACTE == 1) {
	$message = validateIncompatibiliteRapport($_POST);
	if (!$message) {
  	   $x = $x*1;
  	   $y = $y*1;
  	   if (!$x) $x = 'null';
  	   if (!$y) $y = 'null';
		if($Id_incompatibilite){	
			new db_sql("update incompatibilites_rapport set 
				produit='$produit',
				prop1='$prop1',
				val1='$val1',
				prop2='$prop2',
				prop3='$prop3',
				x=$x,
				y=$y
				where id_incompatibilite=$Id_incompatibilite");
		} else {
			new db_sql("insert incompatibilites_rapport (produit,prop1,val1,prop2,prop3,x,y) values (
				'$produit',
				'$prop1',
				'$val1',
				'$prop2',
				'$prop3',
				$x,
				$y)");  
				$Id_incompatibilite = db_sql::last_id ();
		}	  
	}
}  else if($ACTE == 2 && $Id_incompatibilite) {
	$req = new db_sql(" DELETE FROM incompatibilites_rapport WHERE id_incompatibilite = $Id_incompatibilite ");
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
         incompatibilites_rapport
      WHERE 
	     id_incompatibilite = $Id_incompatibilite";
   $resultat = new db_sql($req);
   $resultat->n();
   $produit    = $resultat->f("produit");
   $prop1         = $resultat->f("prop1");
   $val1    = $resultat->f("val1");
   $prop2  = $resultat->f("prop2");
   $prop3     = $resultat->f("prop3");
   $x     = $resultat->f("x");
   $y     = $resultat->f("y");
   
}
?>
<body class="application">
<FORM method=post name=formulaire action=incompatibilite_rapport_detail.php?ACTE=1>
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Rapport</td>
     <td class='externe'><A class='externe' href='incompatibilite_rapport_list.php?liste_go=1'>Retour à la liste</A></td>
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
      <td class="requet_right" >Si Propriété 1</td>
      <td class="requet_left"><? champ_ouvert_droit("O","prop1",$prop1,60, 35, "O"); ?></td>
      <td class="requet_right">vaut Valeur 1</td>
      <td class="requet_left"><? champ_ouvert_droit("O","val1",$val1,60, 35, "O");?></td>
      <td class="requet_right"></td>
      <td class="requet_left" ></td>
      
   </tr>
  
   <tr>
      <td class="requet_right" >alors Propriété 2</td>
      <td class="requet_left"><? champ_ouvert_droit("O","prop2",$prop2,60, 35, "O"); ?></td>
      <td class="requet_right"> > </td>
      <td class="requet_left" ><? champ_ouvert_droit("O","x",$x,3, 3, "O"); ?>%</td>      
      <td class="requet_right"> de Propriété 3</td>
      <td class="requet_left" ><? champ_ouvert_droit("O","prop3",$prop3,60, 35, "O"); ?></td>

   </tr>
   <tr>
      <td class="requet_right" >et Propriété 2</td>
      <td class="requet_left"></td>
      <td class="requet_right"> < </td>
      <td class="requet_left" ><? champ_ouvert_droit("O","y",$y,3, 3, "O"); ?>%</td>      
      <td class="requet_right"> de Propriété 3</td>
      <td class="requet_left" ></td>
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