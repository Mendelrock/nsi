<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
require_once("po_imprimer_include.php");
// Envoi par mail
if ($ACTE == 2) {
	   po_imprimer($Id_po,true) ;	
  	   new db_sql("update po set id_statut_po = 2 where id_po = ".$_GET[Id_po]);
}

if ($ACTE == 1) {
   $liste_enregistrement = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));
   if(is_array($liste_enregistrement)) {
      foreach ($liste_enregistrement as $i => $record) {
         $message = validateCommande($record);
         if ($message) {
            break;   
         }
		}
		if (!$message) {
			if($DELETE == 1){
				list($Id_po,$id_article) = explode("_",$_POST['id_ligne_to_delete']);
				new db_sql ("delete from po_ligne where id_po = $Id_po and id_article=$id_article");
			} else {		
  	        foreach ($liste_enregistrement as $i => $record) {
  	     	   $date_po = $_POST[$record[date_po]];
  	     	   $id_fournisseur = $_POST[$record[id_fournisseur]];
  	     	   $id_statut_po= $_POST[$record[id_statut_po]];
  	     	   if($record[type]=="entete"){
  	     	   	if($i)	
  	     	   		new db_sql("update po set date_po='$date_po',id_fournisseur='$id_fournisseur',id_statut_po='$id_statut_po' where id_po=$i");
  	     	   	else{
				 		new db_sql("insert po (date_po,id_fournisseur,id_statut_po,lb_po) values ('$date_po', $id_fournisseur, $id_statut_po, 'X')");  
				 		$Id_po = db_sql::last_id ();
				 		new db_sql("update po set lb_po = '".substr($date_po,0,4)."-".substr($date_po,5,2)."-SOD".substr("000000".$Id_po,-5)."' where Id_po = $Id_po");
			    	}	  
 		       } else {
					$id_article = $_POST[$record[id_article]];
					$qt = strtr($_POST[$record[qt]],",",".")*1;
					$pu = strtr($_POST[$record[pu]],",",".")*1;	
					$qt_solde = strtr($_POST[$record[qt_solde]],",",".")*1;
					$id_article_avant= $_POST[$record[id_article_avant]];
					new db_sql("insert po_ligne (id_po,id_article,qt,qt_solde,pu) values ($Id_po, $id_article,$qt,$qt,$pu) on duplicate key update qt=$qt,qt_solde=$qt_solde,pu=$pu ");  		
 		       }
 		    }   
  	     }	
  	  }
  	  else{
  	  	foreach ($liste_enregistrement as $i => $record) {
  	  		if($record[type]=="entete"){
  	  			$id_po=$i;
  	  			break;
  			}
 		}
  	  }
   }	
}
if ($Id_po) {
   if(req_sim("SELECT count(1) as id_po FROM po_ligne WHERE id_po=" . $Id_po,"id_po")>0) $enable_frns = "N";
   else $enable_frns = "O";
}
else
   $enable_frns = "O";
if ($message) {
} else if ($Id_po) {
   $req="
      SELECT 
			*
      FROM
         po
      WHERE 
	      id_po = $Id_po";
   $resultat = new db_sql($req);
   $resultat->n();
   $id_po=$resultat->f("id_po");
   $lb_po=$resultat->f("lb_po");
   $DateCom=$resultat->f("date_po");
   $id_fournisseur=$resultat->f("id_fournisseur");
   $Id_statut_po=$resultat->f("id_statut_po");
   
}
$liste_enregistrement = array();
?>
<body class="application">
<FORM method=post name=formulaire action=po_detail.php?ACTE=1>
<input type="hidden" name="ADD" value="">
<input type="hidden" name="DELETE" value="">
<input type="hidden" name="id_ligne_to_delete" value="">
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Commande</td>
     <?
     if($id_po){
     ?>
     <td class='externe'><A class='externe' href="javascript:window.open('po_imprimer.php?id_po=<? echo $id_po ?>');void(0);">Imprimer</A></td>
     <?
	     if($Id_statut_po == 1){
	     ?>
	     <td class='externe'><A class='externe' href="?Id_po=<? echo $Id_po ?>&ACTE=2')">Envoyer par mail</A></td>
	     <?
	     }
     }
     ?>
	</tr>
   </table>
  </td>
 </tr>
</table> 
<table class="requeteur">
   <tr>
      <td class="requet_right">Numéro</td>
      <td class="requet_left" ><span style='border:solid 1px;'><? echo $lb_po ?></span></td>
      <td class="requet_right" >Date</td>
      <td class="requet_left" ><? champ_date_droit ("commande", "DateCom", $DateCom, "affaire", "N") ?></td>
      <td class="requet_right">Fournisseur</td>
      <td class="requet_left"><? drop_down_droit($enable_frns,"SELECT id_fournisseur,lb_fournisseur FROM fournisseur ORDER BY lb_fournisseur", "id_fournisseur", "id_fournisseur", "lb_fournisseur",$id_fournisseur,false, "commande","N", "", " "); ?></td>
   </tr>
   <tr>
      <td class="requet_right">Statut</td>
      <td class="requet_left"><? drop_down_droit("N","SELECT Id_statut_po,Lb_statut_po FROM po_statut ORDER BY Id_statut_po", "Id_statut_po", "Id_statut_po", "Lb_statut_po",$Id_statut_po,false, "commande","N", "", ""); ?></td>
   </tr>
</table>
<?
if ($Id_po) {
?>
<table style="width:100%;align:center;background-color : WhiteSmoke;border : 1px Solid Silver;cellpadding:0px;cellspacing:0px;">
   <tr>
      <td class=resultat_tittle>Article</td>
      <td class=resultat_tittle>Qté</td>
      <td class=resultat_tittle>Prix</td>
      <td class=resultat_tittle>Reliquat</td>
      <td class=resultat_tittle></td>
   </tr>
   <?
      $num_ligne = 0;
	  $liste_ligne = charge("select qt,pu,qt_solde,article.id_article as id,id_po,concat(reference,' (',lb_article_aff,')') as libelle from po_ligne, article,fournisseur_article where id_po = $Id_po and po_ligne.id_article=article.id_article and po_ligne.id_article=fournisseur_article.id_article and fournisseur_article.id_fournisseur=$id_fournisseur"); 
	  $qttot=0;
	  foreach ($liste_ligne as $i => $info) {
	     $num_ligne++;	
	     $id_article_val = $info[id];
	     $qt_val = $info[qt];
	     $qttot += $qt_val;
	     $pu_val = $info[pu];
	     $qt_solde_val = $info[qt_solde];
	     //S'il y a erreur lors de l'enregistrement
	     if ($ACTE==1 and $message ) {
	     	$liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
	        foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	           if($id_en_cours==$Id_po."_".$info[id]){
	              $id_article_val = $_POST[$record[id_article]];
  	     	      $qt_val = $_POST[$record[qt]];
  	     	      $pu_val= $_POST[$record[pu]];	
			      $qt_solde_val= $_POST[$record[qt_solde]];	
			      break;
			   }	
        	}
     	 }
     	 
	  ?>
	  <tr>
	     <input type="hidden" name="<? echo $Id_po."_"."id_article_avant".$num_ligne; ?>" value="<? echo $info[id]; ?>">
	     <input type="hidden" name="<? echo $Id_po."_"."id_article".$num_ligne; ?>" value="<? echo $info[id]; ?>">
<? 
	     if (droit_utilisateur("commande") and ($Id_statut_po==1))
	     	$modif_contenu = "O"; 
	     else 
	        $modif_contenu = "N";
?>
         <td width="60%" noWrap><? champ_ouvert_droit("N","",$info[libelle],100, 100, "N"); ?></td>
         <td width="10%" noWrap><? champ_ouvert_droit($modif_contenu,$Id_po."_"."id_article_qt".$num_ligne, $qt_val,10, 10, "N"); ?></td>
         <td width="10%" noWrap><? champ_ouvert_droit($modif_contenu,$Id_po."_"."id_article_pu".$num_ligne, $pu_val,10, 10, "N"); ?></td>
         <td width="10%" noWrap><input class='reliquat' type='text' name="<? echo $Id_po."_"."id_article_qt_solde".$num_ligne; ?>" value="<? echo $qt_solde_val; ?>" size=10 maxlength=10><input type='hidden' name="<? echo "h_".$Id_po."_"."id_article_qt_solde".$num_ligne; ?>"  value="N"></td>
		 <?if(droit_utilisateur("commande")){	  
     	 ?>
         <td><input class="requeteur_button" type="submit" name="Supprimer" value="Supprimer" onclick="
                                            if (confirm('Voulez vous supprimer cette ligne ?'))
                                            {
                                               document.forms['formulaire'].DELETE.value='1';
                                               document.forms['formulaire'].id_ligne_to_delete.value='<? echo $Id_po."_".$info[id]; ?>';
                                            }
                                            else
                                            {
                                               return false;
                                            } "></td>                                         
         <?
		 }
		 ?>                                                                                
      </tr>   
	  <?	
	     $liste_enregistrement[$Id_po."_".$info[id]]=array("id_article"=>$Id_po."_"."id_article".$num_ligne,"qt_solde"=>$Id_po."_"."id_article_qt_solde".$num_ligne,"qt"=>$Id_po."_"."id_article_qt".$num_ligne,"pu"=>$Id_po."_"."id_article_pu".$num_ligne,"type"=>"ligne","id_article_avant"=>$Id_po."_"."id_article_avant".$num_ligne); 
  	  }
  	  if ($ACTE==1 and $message){
  	  	 $liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
  	     foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	           if($id_en_cours=="nouvelleligne"){
	              $id_article = $_POST[$record[id_article]];
  	     	      $qt = $_POST[$record[qt]];
  	     	      $pu= $_POST[$record[pu]];		
	           
      ?>	
  	     <tr>
            <td noWrap><? drop_down_droit("commande","SELECT article.id_article,concat(reference,' (',lb_article_aff,')') as libelle FROM article,fournisseur_article where fournisseur_article.id_article=article.id_article and id_fournisseur=$id_fournisseur ORDER BY libelle", "id_article", "id_article", "libelle", $id_article,false, "commande","N", "", " "); ?></td>
            <td noWrap><? champ_ouvert_droit("commande","qt", $qt,10, 10, "N");?></td>
            <td noWrap><? champ_ouvert_droit("commande","pu", $pu,10, 10, "N");?></td>
            <td></td>
            <td></td>
         </tr> 
	  <?
	           $liste_enregistrement["nouvelleligne"]=array("id_article"=>"id_article","qt"=>"qt","pu"=>"pu","type"=>"ligne"); 
		       break;	
	        }	
       	 }	
  	  }
  	  else if ($ADD==1 and !$message){  
  	  ?>	
  	     <tr>
            <td noWrap><? drop_down_droit("commande","SELECT 
            												article.id_article, 
            												concat(reference,' (',article.lb_article,')') as libelle 
            										  FROM 
            										 	    fournisseur_article, 
            										 	    article
            										  left outer join po_ligne on (po_ligne.id_po = $Id_po and 
            										                               po_ligne.id_article = article.id_article) 
            										  where 
            										  		fournisseur_article.id_article = article.id_article and 
            										  		id_fournisseur = $id_fournisseur and 
            										  		po_ligne.id_article is null ORDER BY libelle", "id_article", "id_article", "libelle", "",false, "commande","N", "", " "); ?></td>
            <td noWrap><? champ_ouvert_droit("commande","qt", "",10, 10, "N"); ?></td>
            <td noWrap><? champ_ouvert_droit("commande","pu", "",10, 10, "N"); ?></td>
            <td></td>
            <td></td>
         </tr> 
	  <?
	     $liste_enregistrement["nouvelleligne"]=array("id_article"=>"id_article","qt"=>"qt","pu"=>"pu","type"=>"ligne"); 	
  	  }
   if(droit_utilisateur("commande")){	  
   ?>
   <tr>
      <td colspan = 3></td>
      <td><input class="requeteur_button" type="button" value="Solder" ></td>
      <td><input class="requeteur_button" onclick="document.forms['formulaire'].ADD.value='1';" type="submit" name="Submit" value="Ajouter" ></td>
   </tr>
   <?
   }
   ?>
</table>
<?
}
if(droit_utilisateur("commande")){	  
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit>
<INPUT class=requeteur_button onclick="document.location.href='po_detail.php?Id_po=<? echo $Id_po; ?>';" name=Annuler value=Annuler type=button>
<?
}
$liste_enregistrement[$Id_po]=array("date_po"=>"DateCom","id_fournisseur"=>"id_fournisseur","id_statut_po"=>"Id_statut_po","type"=>"entete"); 
?>
<input type="hidden" name="Id_po" value="<? echo $Id_po; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<script>
   function recalcule() {
		var total=0;
		$(".reliquat").each(function(){
			total=total+parseInt($(this).val());
		})
		if (total==0) {
			$('input[name=Id_statut_po]').val(4);
		} else if (total==<? echo $qttot+0; ?>) {
			$('input[name=Id_statut_po]').val(2);
		} else {
			$('input[name=Id_statut_po]').val(3);
		}   
   }
	$(".reliquat").change(function(){
		recalcule();
	})
	$(".reliquat").focus(function(){
		$(this).val(0);
		recalcule();
	})
	$("input[value=Solder]").click(function(){
		$(".reliquat").val(0);
		recalcule();
	})
	$("select[name=id_article]").change(function(){
		var requete = new Object;
      requete.id_article = $("select[name=id_article]").val();
      requete.id_fournisseur = '<? echo $id_fournisseur; ?>';
		$.getJSON(
			"po_detail_pu.php",
   		requete,
   	   function(json){
				if ((json.pu) != "") {
					$("input[name=pu]").val(json.pu);
            }
			}
   	)  
	});
</script>

<?
include ("ress/enpied.php");
?>