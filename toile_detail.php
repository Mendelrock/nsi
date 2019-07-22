<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE == 1) {
   $liste_enregistrement = unserialize(urldecode(stripslashes($_POST['liste_enregistrement'])));
   if(is_array($liste_enregistrement)) {
      foreach ($liste_enregistrement as $i => $record) {
         $message = validateToile($record);
         if ($message) {
            break;   
         }
  	  }
  	  if (!$message) {
  	  	 foreach ($liste_enregistrement as $i => $record) {
  	     	   $amotif_raccordable = $_POST[$record[amotif_raccordable]];
  	     	   if(!$amotif_raccordable) $amotif_raccordable = 0;
  	     	   $amotif_ajustable = $_POST[$record[amotif_ajustable]];
  	     	   if(!$amotif_ajustable) $amotif_ajustable = 0;
  	     	   $lb_toile_atelier = $_POST[$record[lb_toile_atelier]];
  	     	   $lb_toile_sr = $_POST[$record[lb_toile_sr]];
  	     	   $selecteur_rideau = $_POST[$record[selecteur_rideau]];
  	     	   if(!$selecteur_rideau) $selecteur_rideau = 0;
  	     	   $selecteur_doublure= $_POST[$record[selecteur_doublure]];
  	     	   if(!$selecteur_doublure) $selecteur_doublure = 0;
  	     	   $selecteur_store = $_POST[$record[selecteur_store]];
  	     	   if(!$selecteur_store) $selecteur_store = 0;
  	     	   $selecteur_enrouleur_exterieur = $_POST[$record[selecteur_enrouleur_exterieur]];
  	     	   if(!$selecteur_enrouleur_exterieur) $selecteur_enrouleur_exterieur = 0;
               $selecteur_enrouleur_interieur = $_POST[$record[selecteur_enrouleur_interieur]];
  	     	   if(!$selecteur_enrouleur_interieur) $selecteur_enrouleur_interieur = 0;
  	     	   $selecteur_coffre_exterieur = $_POST[$record[selecteur_coffre_exterieur]];
  	     	   if(!$selecteur_coffre_exterieur) $selecteur_coffre_exterieur = 0;
               $selecteur_coffre_interieur = $_POST[$record[selecteur_coffre_interieur]];
  	     	   if(!$selecteur_coffre_interieur) $selecteur_coffre_interieur = 0;

                $selecteur_film_exterieur = $_POST[$record[selecteur_film_exterieur]];
                if(!$selecteur_film_exterieur) $selecteur_film_exterieur = 0;
                $selecteur_film_interieur = $_POST[$record[selecteur_film_interieur]];
                if(!$selecteur_film_interieur) $selecteur_film_interieur = 0;
                $selecteur5 = $_POST[$record[selecteur5]];
                if(!$selecteur5) $selecteur5 = 0;
                $selecteur6 = $_POST[$record[selecteur6]];
                if(!$selecteur6) $selecteur6 = 0;
                $selecteur7 = $_POST[$record[selecteur7]];
                if(!$selecteur7) $selecteur7 = 0;
                $selecteur8 = $_POST[$record[selecteur8]];
                if(!$selecteur8) $selecteur8 = 0;
                $selecteur9 = $_POST[$record[selecteur9]];
                if(!$selecteur9) $selecteur9 = 0;

  	     	   $selecteur_coussin = $_POST[$record[selecteur_coussin]];
  	     	   if(!$selecteur_coussin) $selecteur_coussin = 0; 
  	     	   $selecteur_paroi = $_POST[$record[selecteur_paroi]];
  	     	   if(!$selecteur_paroi) $selecteur_paroi = 0;  	     	   //$reference_couleur= $_POST[$record[reference_couleur]];
  	     	   $couleur = $_POST[$record[couleur]];
  	     	   //$reference_gamme = $_POST[$record[reference_gamme]];
  	     	   $gamme= $_POST[$record[gamme]];
  	     	   $orientation = $_POST[$record[orientation]];
  	     	   $IN= $_POST[$record['IN']];  $FDV= $_POST[$record['FDV']];
  	     	   $IN = $IN ? "1" : "0";
  	     	   $FDV = $FDV ? "1" : "0";
				if($record[typerecord]=="entete"){
  	     	   	if($Id_toile){	
  	     	         new db_sql("update toile set 
  	     	         	lb_toile_atelier='$lb_toile_atelier',
  	     	         	lb_toile_sr='$lb_toile_sr',
  	     	         	selecteur_rideau='$selecteur_rideau',
  	     	         	selecteur_doublure='$selecteur_doublure',
  	     	         	selecteur_store=$selecteur_store,
  	     	         	selecteur_enrouleur_exterieur=$selecteur_enrouleur_exterieur,
  	     	         	selecteur_enrouleur_interieur=$selecteur_enrouleur_interieur,
  	     	         	selecteur_coffre_exterieur=$selecteur_coffre_exterieur,
  	     	         	selecteur_coffre_interieur=$selecteur_coffre_interieur,
  	     	         	selecteur_film_exterieur=$selecteur_film_exterieur,
  	     	         	selecteur_film_interieur=$selecteur_film_interieur,
  	     	         	selecteur5=$selecteur5,
  	     	         	selecteur6=$selecteur6,
  	     	         	selecteur7=$selecteur7,
  	     	         	selecteur8=$selecteur8,
  	     	         	selecteur9=$selecteur9,
  	     	         	selecteur_coussin=$selecteur_coussin,
  	     	         	selecteur_paroi=$selecteur_paroi,
  	     	         	amotif_raccordable=$amotif_raccordable,
  	     	         	amotif_ajustable=$amotif_ajustable,
  	     	         	couleur='$couleur',
  	     	         	gamme='$gamme',
  	     	         	orientation='$orientation',`IN`='$IN',FDV='$FDV' 
  	     	         	where id_toile=$Id_toile");
					} else {
						new db_sql("insert toile (lb_toile_atelier,lb_toile_sr,selecteur_rideau,selecteur_doublure,selecteur_store,
				   	    selecteur_enrouleur_exterieur,selecteur_enrouleur_interieur,
				   	    selecteur_coffre_exterieur,selecteur_coffre_interieur,selecteur_film_exterieur,selecteur_film_interieur,selecteur5,
				   	    selecteur6,selecteur7,selecteur8,selecteur9,
				   	    selecteur_coussin, selecteur_paroi,couleur,gamme,orientation,
				     	amotif_raccordable,
				     	amotif_ajustable,FDV, `IN`) values (
                             '$lb_toile_atelier',
                             '$lb_toile_sr',
                             '$selecteur_rideau',
                             '$selecteur_doublure',
                             $selecteur_store,
                             $selecteur_enrouleur_exterieur,
                             $selecteur_enrouleur_interieur,
                             $selecteur_coffre_exterieur,
                             $selecteur_coffre_interieur,
                             $selecteur_film_exterieur,
                             $selecteur_film_interieur,
                             $selecteur5,
                             $selecteur6,
                             $selecteur7,
                             $selecteur8,
                             $selecteur9,
                             $selecteur_coussin,$selecteur_paroi,'$couleur','$gamme','$orientation',
                             $amotif_raccordable,
                             $amotif_ajustable,'$FDV','$IN')");
                             $Id_toile = db_sql::last_id ();
			      }	  
 		       }
 		       $listetoile = charge("SELECT id_toile,lb_toile_atelier FROM toile ORDER BY lb_toile_atelier"); 
 		       ?>
 		       <SCRIPT LANGUAGE=Javascript>
 		           //var id_toile =window.opener.document.getElementById("id_toile").options[window.opener.document.getElementById("id_toile").selectedIndex].value;
      	           window.opener.document.getElementById("id_toile").options.length = 0;
 		           var i = 0;
 		           window.opener.document.getElementById("id_toile").options[i]=new Option('','');
 		           i++;
 		           <?
 		           foreach ($listetoile as $i=>$info) {
 		           ?>	
 		              window.opener.document.getElementById("id_toile").options[i]=new Option('<? echo $info[lb_toile_atelier] ?>','<? echo $info[id_toile] ?>');
                      //if(is_int(id_toile) && id_toile==<? echo $info[id_toile] ?>)window.opener.document.getElementById("id_toile").options[i].selected = true;
				      if(<? echo $Id_toile ?>==<? echo $info[id_toile] ?>)window.opener.document.getElementById("id_toile").options[i].selected = true;
				      i++;
                   <?
                   }
 		           ?>
 		           window.close();
               </SCRIPT>
               <?
 		 }   
  	  }
   }	
}
if ($message) {
} else if ($Id_toile) {
   $req="
      SELECT 
         * 
      FROM
         toile
      WHERE 
	     id_toile = $Id_toile";
   $resultat = new db_sql($req);
   $resultat->n();
   $lb_toile_atelier    = $resultat->f("lb_toile_atelier");
   $lb_toile_sr         = $resultat->f("lb_toile_sr");
   $selecteur_rideau    = $resultat->f("selecteur_rideau");
   $selecteur_doublure  = $resultat->f("selecteur_doublure");
   $selecteur_store     = $resultat->f("selecteur_store");
   $selecteur_enrouleur_exterieur = $resultat->f("selecteur_enrouleur_exterieur");
   $selecteur_enrouleur_interieur = $resultat->f("selecteur_enrouleur_interieur");
   $selecteur_coffre_interieur = $resultat->f("selecteur_coffre_interieur");
   $selecteur_coffre_exterieur = $resultat->f("selecteur_coffre_exterieur");
   $selecteur_film_exterieur = $resultat->f("selecteur_film_exterieur");
   $selecteur_film_interieur = $resultat->f("selecteur_film_interieur");
   $selecteur5 = $resultat->f("selecteur5");
   $selecteur6 = $resultat->f("selecteur6");
   $selecteur7 = $resultat->f("selecteur7");
   $selecteur8 = $resultat->f("selecteur8");
   $selecteur9 = $resultat->f("selecteur9");
   $selecteur_coussin   = $resultat->f("selecteur_coussin");
   $selecteur_paroi		= $resultat->f("selecteur_paroi");
   $amotif_ajustable		= $resultat->f("amotif_ajustable");
   $amotif_raccordable	= $resultat->f("amotif_raccordable");
   $IN    = $resultat->f("IN");
   $FDV     = $resultat->f("FDV");
   //$reference_couleur=$resultat->f("reference_couleur");
   $couleur=$resultat->f("couleur");
   //$reference_gamme=$resultat->f("reference_gamme");
   $gamme=$resultat->f("gamme");
   $orientation=$resultat->f("orientation");
}
$liste_enregistrement = array();
?>
<body class="application">
<FORM method=post name=formulaire action=toile_detail.php?ACTE=1>
<table class="cadre_application_auto">
 <tr>
  <td class="cadre_application">
   <table class="menu_haut">
    <tr>
     <td class="menu_haut">Toile</td>
     <td class='externe'><A class='externe' href='toile_list.php?liste_go=1'>Retour à la liste</A></td>
    </tr>
   </table> 
  </td>
 </tr>
</table> 
<table class="requeteur">
   <tr>
      <td class="requet_right">Libellé atelier</td>
      <td class="requet_left" ><? champ_ouvert_droit("article","lb_toile_atelier",$lb_toile_atelier,60, 35, "O"); ?></td>
      <td class="requet_right" >Sélecteur</td>
      <td class="requet_left"><input type="checkbox"  class='checkbox' name="selecteur_rideau" <? if($selecteur_rideau==1) echo "checked='yes'" ?> value="1">Rideaux</td>
      <td class="requet_right">Orientation</td>
      <td class="requet_left"><? drop_down_droit("O","", "orientation", "", "", $orientation,false, "article","N", "neutre|oppose|laize", "neutre|oppose|laize");?></td>
   </tr>

  </tr>
   <tr>
      <td class="requet_right">Libellé SR</td>
      <td class="requet_left" ><? champ_ouvert_droit("article","lb_toile_sr",$lb_toile_sr,60, 35, "O"); ?></td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_doublure" <? if($selecteur_doublure==1) echo "checked='yes'" ?> value="1">Doublure</td>
      <td class="requet_right" >A motif</td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="amotif_raccordable" <? if($amotif_raccordable==1) echo "checked='yes'" ?> value="1">raccordable</td>
   </tr>
   <tr>
      <td class="requet_right">Couleur</td>
      <td class="requet_left"><? champ_ouvert_droit("article","couleur",$couleur,60, 35, "O"); ?></td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_store" <? if($selecteur_store==1) echo "checked='yes'" ?> value="1">Store</td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="amotif_ajustable" <? if($amotif_ajustable==1) echo "checked='yes'" ?> value="1">ajustable</td>
   </tr>
   <tr>
      <td class="requet_right">Gamme</td>
      <td class="requet_left"><? champ_ouvert_droit("article","gamme",$gamme,60, 35, "O"); ?></td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_enrouleur_interieur" <? if($selecteur_enrouleur_interieur==1) echo "checked='yes'" ?> value="1">Enrouleur Intérieur</td>
      <td class="requet_right" ><? echo IN; ?></td>
      <td class="requet_left"><input name="IN" type="checkbox"  <? if ($IN) echo "checked" ?> /></td>
   </tr>

   <tr>
      <td></td>
      <td></td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_coussin" <? if($selecteur_coussin==1) echo "checked='yes'" ?> value="1">Coussin</td>
      <td class="requet_right"></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_paroi" <? if($selecteur_paroi==1) echo "checked='yes'" ?> value="1">Paroi</td>
   </tr>


   <tr>
      <td></td>
      <td></td>
      <td></td>
      <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_coffre_exterieur" <? if($selecteur_coffre_exterieur==1) echo "checked='yes'" ?> value="1">Selecteur Coffre Exterieur</td>
      <td class="requet_right" ><? echo FDV; ?></td>
      <td class="requet_left"><input name="FDV" type="checkbox"  <? if ($FDV) echo "checked" ?> /></td>
   </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_coffre_interieur" <? if($selecteur_coffre_interieur==1) echo "checked='yes'" ?> value="1">Selecteur Coffre Interieur</td>
        <td class="requet_right" ></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_film_exterieur" <? if($selecteur_film_exterieur==1) echo "checked='yes'" ?> value="1">Film Extérieur</td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur_film_interieur" <? if($selecteur_film_interieur==1) echo "checked='yes'" ?> value="1">Film Intérieur</td>
        <td class="requet_right" ></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur5" <? if($selecteur5==1) echo "checked='yes'" ?> value="1">Selecteur5</td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur6" <? if($selecteur6==1) echo "checked='yes'" ?> value="1">Selecteur6</td>
        <td class="requet_right" ></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur7" <? if($selecteur7==1) echo "checked='yes'" ?> value="1">Selecteur7</td>
    </tr>

    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur8" <? if($selecteur8==1) echo "checked='yes'" ?> value="1">Selecteur8</td>
        <td class="requet_right" ></td>
        <td class="requet_left"><input type="checkbox" class='checkbox' name="selecteur9" <? if($selecteur9==1) echo "checked='yes'" ?> value="1">Selecteur9</td>
    </tr>


   <?
      $liste_enregistrement[$Id_toile]=array("lb_toile_atelier"=>"lb_toile_atelier",
      										 "lb_toile_sr"=>"lb_toile_sr",
      										 "amotif_raccordable"=>"amotif_raccordable",
      										 "amotif_ajustable"=>"amotif_ajustable",
      										 "selecteur_rideau"=>"selecteur_rideau",
      										 "selecteur_doublure"=>"selecteur_doublure",
      										 "selecteur_store"=>"selecteur_store",
      										 "selecteur_enrouleur_exterieur"=>"selecteur_enrouleur_exterieur",
      										 "selecteur_enrouleur_interieur"=>"selecteur_enrouleur_interieur",
      										 "selecteur_coffre_exterieur"=>"selecteur_coffre_exterieur",
      										 "selecteur_coffre_interieur"=>"selecteur_coffre_interieur",
      										 "selecteur_film_exterieur"=>"selecteur_film_exterieur",
      										 "selecteur_film_interieur"=>"selecteur_film_interieur",
      										 "selecteur5"=>"selecteur5",
      										 "selecteur6"=>"selecteur6",
      										 "selecteur7"=>"selecteur7",
      										 "selecteur8"=>"selecteur8",
      										 "selecteur9"=>"selecteur9",
      										 "selecteur_coussin"=>"selecteur_coussin",
      										 "selecteur_paroi"=>"selecteur_paroi",
      										 "couleur"=>"couleur",
      										 "gamme"=>"gamme",
      										 "orientation"=>"orientation",
      										 "typerecord"=>"entete","IN"=>"IN","FDV"=>"FDV"); 
   ?>
</table>
<?
if(droit_utilisateur("article")){
?>   
<INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> <INPUT class=requeteur_button onclick="window.close();" name=Annuler value=Annuler type=button>
<?
}
?>
<input type="hidden" name="Id_toile" value="<? echo $Id_toile; ?>">
<input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<?
include ("ress/enpied.php");
?>