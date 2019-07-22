<?php
include("ress/entete.php");
require_once("c_o_dataset.php");
?>
<script type="text/javascript">
function showTextBox(id_fournisseur,id_article,qtestock,numligne) {
   document.getElementById(id_fournisseur.toString()+id_article.toString()+"_qtestock").innerHTML ="<div id="+id_fournisseur.toString()+id_article.toString()+"_qtestock"+"><input onblur='JavaScript:updateAacheter(\"updateStockArticle.php\","+id_article+","+id_fournisseur+",4,this.value,"+numligne+");' type='text' size='10' value='"+ qtestock+"'></div>";
   var divqtestock = document.getElementById(id_fournisseur.toString()+id_article.toString()+"_qtestock");
   divqtestock.getElementsByTagName('input')[0].focus();
}
</script>
<?
if ($ACTE==1) {
/*-----------------PARAMETRES---------------------
/*  $Libelle
/*  $Reference
/*  $id_fournisseur
/*
/*------------------------------------------------*/

$_SESSION[article_liste_libelle]        = $Libelle;
$_SESSION[article_liste_reference]      = $Reference;
$_SESSION[article_liste_id_fournisseur] = $id_fournisseur;
$_SESSION[article_liste_actif] 			 = $actif;
$_SESSION[article_list_id_famille]      = $id_famille;
$_SESSION[article_liste_go]             = 1;

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales


// Critere de recherche
if($id_fournisseur){
   $requete.="fournisseur.Id_fournisseur = '$id_fournisseur' AND ";
}

if($id_famille){
   $requete.="familles.Id_famille = '$id_famille' AND ";
}

if($Libelle){
   $requete.="lb_article_aff like '%".$Libelle."%' AND ";
}

if($Reference){
   $requete.="reference like '%".$Reference."%' AND ";
}

if($actif != '(0,1,2)'){
   $requete.="article.actif in $actif AND ";
}


$req="
   SELECT
      article.Id_article,
      article.lb_article_aff,
      actif,
      lb_fournisseur,
      reference,
      fournisseur.Id_fournisseur,
      fournisseur.lb_fournisseur,
	  familles.lb_famille,
      qt_mini,
      qt_max,
  	   quotite,
  	   prix,
  	   envoi_automatique
   FROM
      article
	  left join familles on article.Id_famille = familles.id_famille
      left join fournisseur_article on article.Id_article = fournisseur_article.Id_article AND principal = 1 
      left join fournisseur on fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur AND fournisseur.statut <> 'I'
   WHERE $requete
      lb_article != 'Aucun' and 
      lb_article != 'PAS DE GALON'       
   ORDER BY
      lb_article_aff";
      
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Libellé</td>
         <!--<td class="resultat_tittle">Référence</td>-->
         <td class="resultat_tittle">Fournisseur</td>
		 <td class="resultat_tittle">Famille</td>
         <td class="resultat_tittle">Prix unitaire</td>
         <td class="resultat_tittle"><div title="Valorisation du stock au Prix du Fournisseur Principal">Valorisation</div></td>
         <td class="resultat_tittle">Stock physique</td>
         <td class="resultat_tittle">Instance</td>
         <td class="resultat_tittle">Stock logique</td>
         <td class="resultat_tittle">Mois de S.L. disponible</td>
         <td style="display:none" class="resultat_tittle">Qté min</td>
         <td style="display:none" class="resultat_tittle">Qté max</td>
         <td style="display:none" class="resultat_tittle">Quotité</td>
         <td class="resultat_tittle">Qté à acheter</td>
		 <td class="resultat_tittle">Total</td>
      </tr>
<?
$_SESSION[liste_article]=array();
$_SESSION[etat_stock]=array();
$_SESSION[liste_idarticle_idfournisseur]=array();
$_SESSION[liste_idarticle_qtestock]=array();
$_SESSION[total_art_achete] = 0;
while( $z<$Affichage AND $resultat->n() ){
   $lo_qtestockt = get_stock_article($resultat->f('id_article'));
   $qtestock = $lo_qtestockt[logique];
   $qtestock_mois = $lo_qtestockt[logique_mois];
   $qtestockphysique = $lo_qtestockt[physique];
   $qtestockinstance = $lo_qtestockt[achete];

   $isvalid = ( $Aacheter != 1 || ($Aacheter == 1 && $resultat->f('qt_mini')>$qtestock && $resultat->f('actif') == 1) );	
	if($isvalid){
      $z++;
      //$nb_fournisseur = get_nb_fournisseur_by_article($resultat->f('id_article'));
      $tab_fournisseur = charge("SELECT fournisseur.Id_fournisseur,lb_fournisseur,principal,prix  FROM fournisseur,fournisseur_article WHERE fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur AND fournisseur.statut<>'I' AND id_article=" . $resultat->f('id_article'));
		?>
      <tr id="<? echo $z * 100?>">
      <?
		if (($resultat->f('qt_mini')>$qtestock) and ($resultat->f('actif')) == 1) {
         $bgcolor='#FF6347';	
         $quotite = $resultat->f('quotite');
			if ($quotite<=0) {
				$quotite = 1;
			}
			$qt_max = $resultat->f('qt_max')*1;
			if ($qt_max < ($resultat->f('qt_mini')*1)) $qt_max = $resultat->f('qt_mini')*1;
         $qte_a_acheter = $quotite*ceil(($qt_max-$qtestock)/$quotite);
		} else {
			$bgcolor=alternat($z);
			$qte_a_acheter = 0;
		}	
		$total_acheter = ($qte_a_acheter*$resultat->f('prix'));
		$_SESSION[total_art_achete] += $total_acheter;
        $_SESSION[etat_stock][]=array(
			"Libellé"=>$resultat->f('lb_article_aff'),
			"Référence"=>$resultat->f('reference'),
			"Fournisseur Principal"=>$resultat->f('lb_fournisseur'),
			"Prix unitaire"=>$resultat->f('prix'),
			"Valorisation"=>$qtestockphysique*$resultat->f('prix'),
			"Stock physique"=>$qtestockphysique,
			"Instance"=>$qtestockinstance,
			"Stock logique"=>$qtestock);
         if (($resultat->f('actif')) == 1) {
         	 $_SESSION[liste_article][]=array("id_article"=>$resultat->f('id_article'),"lb_article"=>$resultat->f('lb_article_aff'),"Id_fournisseur"=>$resultat->f('Id_fournisseur'),"lb_fournisseur"=>$resultat->f('lb_fournisseur'),"qtestock"=>$qtestock,"qt_max"=>$resultat->f('qt_max'),"qt_mini"=>$resultat->f('qt_mini'),"quotite"=>$resultat->f('quotite'),"prix"=>$resultat->f('prix'),"envoi_automatique"=>$resultat->f('envoi_automatique'),"Aacheter"=>$qte_a_acheter);	
         }
         ?>
               <td class='resultat_list' bgcolor='<? echo $bgcolor ?>'><a class='resultat' href='article_detail.php?Id_article=<? echo $resultat->f('id_article') ?>' target='droite'><? echo $resultat->f('lb_article_aff') ?></a></td>
               <!--<td class='resultat_list' bgcolor='<? echo $bgcolor ?>'><? echo substr($resultat->f('reference'),0,30) ?></td>-->
               <?
                  if(count($tab_fournisseur)>1){
               ?>
               <td class='resultat_list' bgcolor='<? echo $bgcolor ?>'><? drop_down_custom($tab_fournisseur , $resultat->f('Id_fournisseur').$resultat->f('id_article')."_id","id_fournisseur","lb_fournisseur"," onchange='JavaScript:getInfosFournisseur(\"updateStockArticle.php\",".$resultat->f('id_article').",this.options[this.selectedIndex].value,2,".+$resultat->f('Id_fournisseur').");'","principal","1") ?></td>
               <?
                  } else {
               ?>
               <td class='resultat_list' bgcolor='<? echo $bgcolor ?>'><? echo substr($resultat->f('lb_fournisseur'),0,30) ?></div></td>
               <?
                  }
               ?>
				<td class='resultat_list' bgcolor='<? echo $bgcolor ?>'><? echo substr($resultat->f('lb_famille'),0,30) ?></div></td>
			      <td class='resultat_list recup_id'  id='prix_art_<? echo $z ?>' align = right bgcolor='<? echo $bgcolor ?>'><? echo number_format($resultat->f('prix'),2,',','') ?></td>
			      <td class='resultat_list' align = right bgcolor='<? echo $bgcolor ?>'><? echo number_format($qtestockphysique*$resultat->f('prix'),2,'.','') ?></td>
			      <td class='resultat_list' align = right bgcolor='<? echo $bgcolor ?>'><? echo number_format($qtestockphysique,2,'.','') ?></td>
			      <td class='resultat_list' align = right bgcolor='<? echo $bgcolor ?>'><? echo number_format($qtestockinstance,2,'.','') ?></td>
			      <td class='resultat_list' align = right bgcolor='<? echo $bgcolor ?>'><div id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_qtestock"?>>
			      		<? if (($resultat->f('actif')) == 1) { ?>
			      			<a href="#" class='resultat' onclick='showTextBox(<? echo $resultat->f('Id_fournisseur')?>,<? echo $resultat->f('id_article')?>,<? echo $qtestock?>,<? echo $z*100?>)'><? echo number_format($qtestock,2,'.','')?></a>
			      		<? } else { 
			      			echo number_format($qtestock,2,'.','');
			      		   } ?>
			      </div></td>
				  <td class='resultat_list' align = right bgcolor='<? echo $bgcolor ?>'>
					  <div id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_qtestock_mois"?>>
							<? echo number_format($qtestock_mois,2,'.',''); ?>
					  </div>
				  </td>
               <td style="display:none" class='resultat_list' bgcolor='<? echo $bgcolor ?>'><div id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_qt_mini" ?>><? echo $resultat->f('qt_mini') ?></div></td>
               <td style="display:none"  class='resultat_list' bgcolor='<? echo $bgcolor ?>'><div id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_qt_max" ?>><? echo $resultat->f('qt_max') ?></div></td>
               <td style="display:none" class='resultat_list' bgcolor='<? echo $bgcolor ?>'><div id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_quotite" ?>><? echo $resultat->f('quotite') ?></div></td>
               <td class='resultat_list' bgcolor='<? echo $bgcolor ?>'> <? if (($resultat->f('actif')) == 1) { ?> <input align = right data-ident='<? echo $z ?>' class='qt_art' data-article='<? echo $resultat->f('id_article') ?>' onchange='JavaScript:updateStockArticle("updateStockArticle.php",<? echo $resultat->f('id_article') ?>,this.value,3);' size="8" type="text" id=<? echo $resultat->f('Id_fournisseur').$resultat->f('id_article')."_aacheter" ?> value="<? echo number_format($qte_a_acheter,2,'.','') ?>"><? } ?></td>
				<td class='resultat_list calcul_tot' data-price='<? echo $qte_a_acheter*$resultat->f('prix'); ?>' id='total_art_<? echo $z ?>' align = right bgcolor='<? echo $bgcolor ?>'><? echo number_format(($qte_a_acheter*$resultat->f('prix')),2,',','') ?></td>
	  </tr>
<?
   }
}
if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='7'>Cliquer sur le libellé pour ouvrir un article et sur la quantité \"stock logique\" pour la modifier</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
   ?><td class='resultat_footer' align='center'><input class="requeteur_button" type="button" value="Acheter" onclick="location.href='article_list_commande.php';"></td>
   <td class='resultat_footer' id='total_sf' align='right'><?php echo number_format($_SESSION[total_art_achete],2,',',''); ?></td>
	<?
}
echo "</tr></table>";
}
?>
<script>
		$('.qt_art').on('change', function() {
			var article = $(this).data('article');
			var id = $(this).data('ident');
			var qte = $(this).val();
			var prix = parseFloat($('#prix_art_'+id).html().replace(',', '.'));
			$('#total_art_'+id).html( (qte*prix).toLocaleString(undefined, {maximumFractionDigits:2}) );
			$('#total_art_'+id).data('price', (qte*prix).toFixed(2));
			
			majTotal();
			//updateStockArticle("updateStockArticle.php",article,qte,3);
		});
		
		function majTotal()
		{
			var total = 0;
			$('.calcul_tot').each(function() {
				total+= parseFloat($(this).data('price'));
			});
			$('#total_sf').html(total.toLocaleString(undefined, {maximumFractionDigits:2}));
		}
	</script>
<?php
        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
