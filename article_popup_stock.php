<?
include("ress/entete.php");
?>
<script type="text/javascript">
   function save(obj){
   	  if(obj.ope.value==1){	
   	     var l=obj.qtestock.value.length; 
         if(l==0)
            alert("Champ obligatoire non rempli");
         else {
      	    window.opener.document.getElementById(obj.idfrns.value+obj.idtoupdate.value+"_qtestock").innerHTML ="<div id="+obj.idtoupdate.value+"><a class='resultat' href='article_popup_stock.php?Id_article="+obj.idtoupdate.value+"&ope=1&qte="+ obj.qtestock.value+"&Id_frns="+ obj.idfrns.value+"' onclick='popupCenter(this.href, \"\",300,100); return false;'>"+ obj.qtestock.value+"</a></div>";
            updateStockArticle('updateStockArticle.php',obj.idtoupdate.value,obj.qtestock.value,obj.ope.value);
		    window.close();
         }
  	  }
  	  else if(obj.ope.value==2){	
  	  	 var lib_fournisseur = obj.id_fournisseur.options[obj.id_fournisseur.selectedIndex].text;
  	  	 var id_fournisseur = obj.id_fournisseur.options[obj.id_fournisseur.selectedIndex].value;
   	     window.opener.document.getElementById(obj.idtoupdate.value).innerHTML ="<div id="+id_fournisseur+obj.Id_article.value+"_id"+"><a class='resultat' href='article_popup_stock.php?Id_article="+obj.Id_article.value+"&Id_fournisseur="+ id_fournisseur+"&ope=2' onclick='popupCenter(this.href, \"\",300,100); return false;'>"+ lib_fournisseur+"</a></div>";
         updateStockArticle('updateStockArticle.php',obj.Id_article.value,id_fournisseur,obj.ope.value);
		 window.close();
      }
   }
</script>
<body class="application">
<form method="post" name ="form_update" action="article_popup_stock.php" onsubmit="save(this)">
<input type='hidden' name="ope" value="<? echo $ope ?>">
<input type='hidden' name="idfrns" value="<? echo $Id_frns ?>">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="requeteur">
            <?
               if($ope==1){  
           	?>
                  <tr>
                     <td class="requet_right">Stock</td>
                     <td class="requet_left" ><input type='hidden' name="idtoupdate" value="<? echo $Id_article ?>"><input type='text' name="qtestock" id="test" value="<? echo $qte ?>" onkeypress="validate(event,this.value)">
                  </tr>
                  <tr>
                     <td class="requet_button" align="left" colspan="2"><input class="requeteur_button" type="submit" name="Submit" value="Valider")"></td>
                  </tr>
            <?
               }
               else if($ope==2){  
               	
           	?>
                  <tr>
                     <td class="requet_right">Founisseur</td>
                     <td class="requet_left" ><input type='hidden' name="idtoupdate" value="<? echo $Id_fournisseur.$Id_article."_id" ?>"><input type='hidden' name="Id_article" value="<? echo $Id_article ?>"><? drop_down_droit("O","SELECT fournisseur.id_fournisseur,lb_fournisseur FROM fournisseur,fournisseur_article WHERE fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur AND id_article=" . $Id_article ." ORDER BY lb_fournisseur", "id_fournisseur", "id_fournisseur", "lb_fournisseur", $Id_fournisseur,false, "client","N", "", ""); ?>
                  </tr>
                  <tr>
                     <td class="requet_button" align="left" colspan="2"><input class="requeteur_button" type="submit" name="Submit" value="Valider")"></td>
                  </tr>
            <?
               }
            ?>
         </table>
         
      </td>
   </tr>
</table>
</form>
?>
