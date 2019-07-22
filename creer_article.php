<script type="text/javascript">
function showFenetreToile(ope){
   if(ope==1){	
      var id_toile =document.getElementById("id_toile").options[document.getElementById("id_toile").selectedIndex].value;
      if(is_int(id_toile)){
         var left = (screen.width/2)-(600/2);
         var top = (screen.height/2)-(600/2);
         var targetWin = window.open('creer_toile.php?Id_toile='+id_toile, 'Toile', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=530, height=330, top='+top+', left='+left);
      }
      else alert("Veuillez sélectionner une toile.");
   }
   else {
      var left = (screen.width/2)-(600/2);
      var top = (screen.height/2)-(600/2);
      var targetWin = window.open('creer_toile.php', 'Toile', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=530, height=330, top='+top+', left='+left);	
   }
}
</script>
<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE == 1) {
   $liste_enregistrement = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));
   if(is_array($liste_enregistrement)) {
      foreach ($liste_enregistrement as $i => $record) {
         $message = validateArticle($record);
         if ($message) {
            break;   
         }
  	  }
  	  if (!$message) {
  	  	 if($DELETE == 1){
  	  	    list($Id_article,$id_fournisseur) = explode("_",$_POST['id_ligne_to_delete']);
			new db_sql ("delete from fournisseur_article where id_fournisseur = $id_fournisseur and id_article=$Id_article");
 	  	 }
 	  	 else if($DELETEPROPRIETE== 1){
 	  	 	list($Id_article,$propriete) = explode("_",$_POST['id_ligne_to_delete']);
			new db_sql ("delete from article_propriete where propriete ='$propriete' and id_article=$Id_article");
 	  	 }
		 else{		
  	        foreach ($liste_enregistrement as $i => $record) {
  	     	   $Libelle = $_POST[$record[lb_article]];
  	     	   $Type = $_POST[$record[type]];
  	     	   $Selecteur= $_POST[$record[selecteur]];
  	     	   $Poids = $_POST[$record[poids]];
  	     	   $Laize = $_POST[$record[laize]];
  	     	   $Qtestock= $_POST[$record[qt_stock]];
  	     	   $Qtemini = $_POST[$record[qt_mini]];
  	     	   $Qtemax = $_POST[$record[qt_max]];
  	     	   $Id_toile= $_POST[$record[id_toile]];
  	     	   if(!$Id_toile) $Id_toile = "null";
  	     	   if(!$Poids) $Poids = "null";
  	     	   if(!$Laize) $Laize = "null";
  	     	   if($record[typerecord]=="entete"){
  	     	      if($i){	
  	     	      	 $qtestock=$Qtestock;
                     $qt_max=$Qtemax;
                     $qt_mini=$Qtemini;	
                     new db_sql("update article set lb_article='$Libelle',type='$Type',selecteur='$Selecteur',poids=$Poids,laize=$Laize,qt_stock=$Qtestock,qt_mini=$Qtemini,qt_max=$Qtemax,id_toile=$Id_toile where id_article=$i");
     	          }
  	     	      else{
				     new db_sql("insert article (lb_article,type,selecteur,poids,laize,qt_stock,qt_mini,qt_max,id_toile) values ('$Libelle', '$Type','$Selecteur',$Poids,$Laize,$Qtestock,$Qtemini,$Qtemax,$Id_toile)");  
				     $Id_article = db_sql::last_id ();
			      }	  
 		       }
 		       else if($record[typerecord]=="ligneprincipal" || $record[typerecord]=="lignesecondaire"){
 		       	  $id_frns_avant= $_POST[$record[id_frns_avant]];
 		          $id_fournisseur = $_POST[$record[id_fournisseur]];
  	     	      $reference = $_POST[$record[reference]];
  	     	      $quotite= $_POST[$record[quotite]];	
  	     	      $prix= $_POST[$record[prix]];
  	     	      if(!$quotite) $quotite = "null";
  	     	      if(!$prix) $prix = "null";
  	     	      if($record[typerecord]=="ligneprincipal") {
  	     	      	$principal=1;
  	     	      	if ($quotite<=0) 
                        $quotite = 1;
                     if($qt_mini>$qtestock){
                        $qte_stock = $quotite*ceil(($qt_max-$qtestock)/$quotite);
                     } else {
   	                    $qte_stock = 0;
                     }  
  	     	            $resultat = get_stock_article($Id_article);
                     $delta_stock = $qtestock - $resultat[logique];
                     new db_sql("update article set qt_stock = qt_stock + $delta_stock where id_article = $Id_article");
     	      	  }
		          else $principal=0;
 		          if(!$id_frns_avant)
 		             new db_sql("insert into fournisseur_article (id_article,id_fournisseur,principal,reference,quotite,prix) values ($Id_article, $id_fournisseur,$principal,'$reference',$quotite,$prix)");  	
 		          else
			         new db_sql("update fournisseur_article set id_fournisseur=$id_fournisseur,reference='$reference',quotite=$quotite,prix=$prix where id_fournisseur=$id_frns_avant and id_article=$Id_article");  		
 		       }
 		       else if($record[typerecord]=="lignepropriete"){
 		          $propriete= $_POST[$record[propriete]];
 		          $valeur = $_POST[$record[valeur]];
                  $id_propriete_avant= $_POST[$record[id_propriete_avant]];	
                  if(!$id_propriete_avant)
 		             new db_sql("insert into article_propriete (id_article,propriete,valeur) values ($Id_article,'$propriete','$valeur')");  	
 		          else
			         new db_sql("update article_propriete set propriete='$propriete',valeur='$valeur' where propriete='$id_propriete_avant' and id_article=$Id_article");
 		       }
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
} else if ($Id_article) {
   $req="
      SELECT 
         * 
      FROM
         article
      WHERE 
	     id_article = $Id_article";
   $resultat = new db_sql($req);
   $resultat->n();
   $Libelle=$resultat->f("lb_article");
   $Type=$resultat->f("type");
   $Selecteur=$resultat->f("selecteur");
   $Poids=$resultat->f("poids");
   $Laize=$resultat->f("laize");
   $Qtestock=$resultat->f("qt_stock");
   $Qtemini=$resultat->f("qt_mini");
   $Qtemax=$resultat->f("qt_max");
   $Id_toile=$resultat->f("id_toile");
   
   $lo_qtestockt = get_stock_article($Id_article);
   $Qtestock = $lo_qtestockt[logique];
}
$liste_enregistrement = array();
$disable="";
if(!droit_utilisateur("article"))
   $disable='disabled="true"';
?>
<body class="application">
<FORM method=post name=formulaire action=creer_article.php?ACTE=1>
<input type="hidden" name="ADD" value="">
<input type="hidden" name="DELETE" value="">
<input type="hidden" name="ADDPROPRIETE" value="">
<input type="hidden" name="DELETEPROPRIETE" value="">
<input type="hidden" name="id_ligne_to_delete" value="">
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Article</td>
    </tr>
   </table> 
  </td>
 </tr>
</table> 
<table class="requeteur">
   <tr>
      <td class="requet_right">Libellé</td>
      <td class="requet_left" ><? champ_ouvert_droit("article","Libelle",$Libelle,60, 35, "O"); ?></td>
      <td class="requet_right" >Type</td>
      <td class="requet_left"><? drop_down_droit("article","SELECT distinct type FROM article ORDER BY type", "Type", "type", "Type",$Type,false, "article","O", "", " "); ?></td>
      <td class="requet_right">Sélecteur</td>
      <td class="requet_left"><? drop_down_droit("article","SELECT distinct selecteur FROM article ORDER BY selecteur", "Selecteur", "selecteur", "Selecteur",$Selecteur,false, "article","O", "", " "); ?></td>
   </tr>
   <tr>
      <td class="requet_right">Poids</td>
      <td class="requet_left"><input type='text' <? echo $disable ?> name="Poids" value="<? echo $Poids ?>" size=10 onkeypress="validate(event,this.value)"></td>
      <td class="requet_right">Laize</td>
      <td class="requet_left"><input type='text' <? echo $disable ?> name="Laize" value="<? echo $Laize ?>" size=10 onkeypress="validate(event,this.value)"></td>
      <td class="requet_right">Toile</td>
      <td class="requet_left"><? drop_down_droit("article","SELECT id_toile,lb_toile_atelier FROM toile ORDER BY lb_toile_atelier", "id_toile", "id_toile", "lb_toile_atelier",$Id_toile,false, "article","O", "", " ","id_toile"); ?>
      <? if(droit_utilisateur("article")){?>
		 <input class="requeteur_button" name="Ouvrir" value="Ouvrir" onclick="showFenetreToile('1');">
		 <input class="requeteur_button" name="Nouveau" value="Nouveau" onclick="showFenetreToile('2');">
      <?}?> 
      </td>
   </tr>
   <tr>
      <td class="requet_right">Qté stock</td>
      <td class="requet_left"><input type='text' <? echo $disable ?> name="Qtestock" value="<? echo $Qtestock ?>" size=10 onkeypress="validate(event,this.value)"></td>
      <td class="requet_right">Qté mini</td>
      <td class="requet_left"><input type='text' <? echo $disable ?> name="Qtemini" value="<? echo $Qtemini ?>" size=10 onkeypress="validate(event,this.value)"></td>
      <td class="requet_right">Qté max</td>
      <td class="requet_left"><input type='text' <? echo $disable ?> name="Qtemax" value="<? echo $Qtemax ?>" size=10 onkeypress="validate(event,this.value)"></td>
   </tr>
   <?
      $liste_enregistrement[$Id_article]=array("lb_article"=>"Libelle","type"=>"Type","selecteur"=>"Selecteur","poids"=>"Poids","laize"=>"Laize","qt_stock"=>"Qtestock","qt_mini"=>"Qtemini","qt_max"=>"Qtemax","id_toile"=>"id_toile","typerecord"=>"entete"); 
   ?>
</table>
<?
if ($Id_article) {
?>
<table class=requeteur>
   <tr>
      <td></td>
      <td class=resultat_tittle>Fournisseur</td>
      <td class=resultat_tittle>Référence</td>
      <td class=resultat_tittle>Quotité</td>
      <td class=resultat_tittle>Prix</td>
   </tr>
   <?
      $info_principal = charge_un("select * from fournisseur_article where id_article = $Id_article and principal=1");
      $num_ligne = 1;
      $id_fournisseur_val= $info_principal[id_fournisseur];
      $reference_val = $info_principal[reference];
      $quotite_val = $info_principal[quotite];
      $prix_val = $info_principal[prix];
      if ($ACTE==1 and $message ) {
         $liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
	     foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	        if($id_en_cours==$Id_article.$info_principal[id_fournisseur]){
	           $id_fournisseur_val = $_POST[$record[id_fournisseur]];
     	       $reference_val = $_POST[$record[reference]];
     	       $quotite_val= $_POST[$record[quotite]];	
     	       $prix_val= $_POST[$record[prix]];		
            }	
     	 }
 	  }
   ?>   
      <tr>
	     <input type="hidden" name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne; ?>" value="<? echo $info_principal[id_fournisseur]; ?>">
	     <td style="height:21px;font-size:10px;font-weight : bold;border : 1px;white-space:nowrap;" width="20">Fournisseur principal</td>
         <td noWrap><? drop_down_droit("article","SELECT * FROM fournisseur ORDER BY lb_fournisseur", $Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne, "id_fournisseur", "lb_fournisseur", $id_fournisseur_val,false, "article","N", "", " "); ?></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne ?>" value="<? echo $reference_val ?>" size=10></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne ?>" value="<? echo $quotite_val ?>" size=10 onkeypress="validate(event,this.value)"></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne ?>" value="<? echo $prix_val ?>" size=10 onkeypress="validate(event,this.value)"></td>
         <? if(droit_utilisateur("article") && $info_principal[id_fournisseur]){?>
		    <td class="requet_button" nowrap ><input class="requeteur_button_custom" onclick="document.forms['formulaire'].ADD.value='1';" type="submit" name="Submit" size="20" value="Ajouter secondaire" ></td>
          <?}?>
	  </tr> 
      <?
      $liste_enregistrement[$Id_article.$info_principal[id_fournisseur]]=array("id_frns_avant"=>$Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne,"id_fournisseur"=>$Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne,"reference"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne,"typerecord"=>"ligneprincipal","quotite"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne,"prix"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne); 
      $liste_ligne = charge("select * from fournisseur_article where id_article = $Id_article and principal=0"); 
	  foreach ($liste_ligne as $i => $info_principal) {
	     $num_ligne++;	
	     $id_fournisseur_val= $info_principal[id_fournisseur];
         $reference_val = $info_principal[reference];
         $quotite_val = $info_principal[quotite];
         $prix_val = $info_principal[prix];
         if ($ACTE==1 and $message ) {
            $liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
	        foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	           if($id_en_cours==$Id_article.$info_principal[id_fournisseur]){
	              $id_fournisseur_val = $_POST[$record[id_fournisseur]];
     	          $reference_val = $_POST[$record[reference]];
     	          $quotite_val= $_POST[$record[quotite]];	
     	          $prix_val= $_POST[$record[prix]];		
               }	
     	    }
         }	    
 	  ?>
	  <tr>
	     <input type="hidden" name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne; ?>" value="<? echo $info_principal[id_fournisseur]; ?>">
	     <td style="height:21px;font-size:10px;font-weight : bold;border : 1px;white-space:nowrap;" width="20">Fournisseur secondaire</td>
         <td noWrap><? drop_down_droit("article","SELECT * FROM fournisseur ORDER BY lb_fournisseur", $Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne, "id_fournisseur", "lb_fournisseur", $id_fournisseur_val,false, "article","N", "", " "); ?></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne ?>" value="<? echo $reference_val ?>" size=10></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne ?>" value="<? echo $quotite_val ?>" size=10 onkeypress="validate(event,this.value)"></td>
         <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne ?>" value="<? echo $prix_val ?>" size=10 onkeypress="validate(event,this.value)"></td>
         <? if(droit_utilisateur("article")){?>
		 <td class="requet_button" nowrap><input class="requeteur_button" type="submit" name="Supprimer" value="Supprimer" onclick="
                                            if (confirm('Voulez vous supprimer cette ligne ?'))
                                            {
                                               document.forms['formulaire'].DELETE.value='1';
                                               document.forms['formulaire'].id_ligne_to_delete.value='<? echo $Id_article."_".$info_principal[id_fournisseur]; ?>';
                                            }
                                            else
                                            {
                                               return false;
                                            } "></td>
          <?}?>                                  
      </tr>   
	  <?	
	     $liste_enregistrement[$Id_article.$info_principal[id_fournisseur]]=array("id_frns_avant"=>$Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne,"id_fournisseur"=>$Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne,"reference"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne,"typerecord"=>"lignesecondaire","quotite"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne,"prix"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne);
  	  }
  	  if ($ACTE==1 and $message){
  	  	$num_ligne++;
		$liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
	    foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	       if($id_en_cours=="nouvelleligne_frns"){
	          $id_fournisseur_val_new = $_POST[$record[id_fournisseur]];
     	      $reference_val_new = $_POST[$record[reference]];
     	      $quotite_val_new= $_POST[$record[quotite]];	
     	      $prix_val_new= $_POST[$record[prix]];		
      ?>	
  	     <tr>
            <input type="hidden" name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne; ?>" value="">
	        <td style="height:21px;font-size:10px;font-weight : bold;border : 1px;white-space:nowrap;" width="20">Fournisseur secondaire</td>
            <td noWrap><? drop_down_droit("article","SELECT fournisseur.id_fournisseur as id_fournisseur,fournisseur.lb_fournisseur as lb_fournisseur FROM fournisseur left outer join fournisseur_article on (fournisseur_article.id_article = $Id_article and fournisseur_article.id_fournisseur = fournisseur.id_fournisseur) where fournisseur_article.id_fournisseur is null ORDER BY lb_fournisseur", $Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne, "id_fournisseur", "lb_fournisseur", $id_fournisseur_val_new,false, "article","N", "", " "); ?></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne ?>" value="<? echo $reference_val_new ?>" size=10></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne ?>" value="<? echo $quotite_val_new ?>" size=10 onkeypress="validate(event,this.value)"></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne ?>" value="<? echo $prix_val_new ?>" size=10 onkeypress="validate(event,this.value)"></td>
         </tr> 
	  <?
	          $liste_enregistrement["nouvelleligne_frns"]=array("id_frns_avant"=>$Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne,"id_fournisseur"=>$Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne,"reference"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne,"typerecord"=>"lignesecondaire","quotite"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne,"prix"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne);	
  	       }
	     }		 			
      }
  	  else if ($ADD==1 and !$message){  
  	  	$num_ligne++;	
  	  ?>	
  	     <tr>
            <input type="hidden" name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne; ?>" value="">
	        <td style="height:21px;font-size:10px;font-weight : bold;border : 1px;white-space:nowrap;" width="20">Fournisseur secondaire</td>
            <td noWrap><? drop_down_droit("article","SELECT fournisseur.id_fournisseur as id_fournisseur,fournisseur.lb_fournisseur as lb_fournisseur FROM fournisseur left outer join fournisseur_article on (fournisseur_article.id_article = $Id_article and fournisseur_article.id_fournisseur = fournisseur.id_fournisseur) where fournisseur_article.id_fournisseur is null ORDER BY lb_fournisseur", $Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne, "id_fournisseur", "lb_fournisseur", "",false, "article","N", "", " "); ?></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne ?>" value="" size=10></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne ?>" value="" size=10 onkeypress="validate(event,this.value)"></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne ?>" value="" size=10 onkeypress="validate(event,this.value)"></td>
         </tr> 
	  <?
	     $liste_enregistrement["nouvelleligne_frns"]=array("id_frns_avant"=>$Id_article.$info_principal[id_fournisseur]."_"."id_frns_avant".$num_ligne,"id_fournisseur"=>$Id_article.$info_principal[id_fournisseur]."_"."id_fournisseur".$num_ligne,"reference"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_reference".$num_ligne,"typerecord"=>"lignesecondaire","quotite"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_quotite".$num_ligne,"prix"=>$Id_article.$info_principal[id_fournisseur]."_"."id_article_prix".$num_ligne);	
  	  }
   ?>
</table>
<?
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button_custom name=Submit value="Ajouter propriété" type=submit onclick="document.forms['formulaire'].ADDPROPRIETE.value='1';"> 
<?
}
?>
<table style="width:300;align:center;background-color : WhiteSmoke;border : 1px Solid Silver;cellpadding:0px;cellspacing:0px;">
   <tr>
      <td style="font-size:10px;border :1px Solid Silver;background-color:InfoBackground;font-weight:bold;color: gray;" width="20">Propriété</td>
      <td style="font-size:10px;border :1px Solid Silver;background-color:InfoBackground;font-weight:bold;color: gray;" width="20">Valeur</td>
   </tr>
<?
   $liste_ligne = charge("select * from article_propriete where id_article = $Id_article"); 
   foreach ($liste_ligne as $i => $info_principal) {
      $num_ligne++;	
	  $propriete_val= $info_principal[propriete];
      $valeur_val = $info_principal[valeur];
      if ($ACTE==1 and $message ) {
         $liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
         foreach ($liste_enregistrement_save as $id_en_cours => $record) {
            if($id_en_cours==$Id_article.$info_principal[propriete]){
               $propriete_val = $_POST[$record[propriete]];
     	       $valeur_val = $_POST[$record[valeur]];
     	    }	
     	 }
      }
	  ?>	
  	     <tr>
            <input type="hidden" name="<? echo $Id_article."_"."id_propriete_avant".$num_ligne; ?>" value="<?echo $info_principal[propriete]?>">
	        <td noWrap><? drop_down_droit("article","SELECT distinct propriete FROM article_propriete ORDER BY propriete", $Id_article."_"."id_article_propriete".$num_ligne, "propriete", "propriete",$propriete_val,false, "article","O", "", " "); ?></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article."_"."id_article_valeur".$num_ligne ?>" value="<?echo $valeur_val?>" size=10 ></td>
            <? if(droit_utilisateur("article")){?>
		       <td class="requet_button" nowrap><input class="requeteur_button" type="submit" name="Supprimer" value="Supprimer" onclick="
                                            if (confirm('Voulez vous supprimer cette ligne ?'))
                                            {
                                               document.forms['formulaire'].DELETEPROPRIETE.value='1';
                                               document.forms['formulaire'].id_ligne_to_delete.value='<? echo $Id_article."_".$info_principal[propriete]; ?>';
                                            }
                                            else
                                            {
                                               return false;
                                            } "></td>
            <?}?>  
		 </tr> 
	  <?
	     $liste_enregistrement[$Id_article.$info_principal[propriete]]=array("id_propriete_avant"=>$Id_article."_"."id_propriete_avant".$num_ligne,"propriete"=>$Id_article."_"."id_article_propriete".$num_ligne,"valeur"=>$Id_article."_"."id_article_valeur".$num_ligne,"typerecord"=>"lignepropriete");		 
   }
   if ($ACTE==1 and $message){
      $num_ligne++;
      $liste_enregistrement_save = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));	
      foreach ($liste_enregistrement_save as $id_en_cours => $record) {
	     if($id_en_cours=="nouvelleligne_propriete"){
	        $propriete_val_new = $_POST[$record[propriete]];
     	    $valeur_val_new = $_POST[$record[valeur]];
     	    ?>	
  	        <tr>
               <input type="hidden" name="<? echo $Id_article."_"."id_propriete_avant".$num_ligne; ?>" value="">
	           <td noWrap><? drop_down_droit("article","SELECT distinct propriete FROM article_propriete ORDER BY propriete", $Id_article."_"."id_article_propriete".$num_ligne, "propriete", "propriete",$propriete_val_new,false, "article","O", "", " "); ?></td>
               <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article."_"."id_article_valeur".$num_ligne ?>" value="<?echo $valeur_val_new?>" size=10 ></td>
            </tr> 
	        <?
	        $liste_enregistrement["nouvelleligne_propriete"]=array("id_propriete_avant"=>$Id_article."_"."id_propriete_avant".$num_ligne,"propriete"=>$Id_article."_"."id_article_propriete".$num_ligne,"valeur"=>$Id_article."_"."id_article_valeur".$num_ligne,"typerecord"=>"lignepropriete");
  	     } 
      }     
   }
   if ($ADDPROPRIETE==1 and !$message){  
      $num_ligne++;	
  	  ?>	
  	     <tr>
            <input type="hidden" name="<? echo $Id_article."_"."id_propriete_avant".$num_ligne; ?>" value="">
	        <td noWrap><? drop_down_droit("article","SELECT distinct propriete FROM article_propriete ORDER BY propriete", $Id_article."_"."id_article_propriete".$num_ligne, "propriete", "propriete","",false, "article","O", "", " "); ?></td>
            <td noWrap><input type='text' <? echo $disable ?> name="<? echo $Id_article."_"."id_article_valeur".$num_ligne ?>" value="" size=10 ></td>
         </tr> 
	  <?
	     $liste_enregistrement["nouvelleligne_propriete"]=array("id_propriete_avant"=>$Id_article."_"."id_propriete_avant".$num_ligne,"propriete"=>$Id_article."_"."id_article_propriete".$num_ligne,"valeur"=>$Id_article."_"."id_article_valeur".$num_ligne,"typerecord"=>"lignepropriete");	
   }
?>   
</table>
<?
}
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> <INPUT class=requeteur_button onclick="document.location.href='creer_article.php?Id_article=<? echo $Id_article; ?>';" name=Annuler value=Annuler type=button>
<?
}
?>
<input type="hidden" name="Id_article" value="<? echo $Id_article; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<?
include ("ress/enpied.php");
?>