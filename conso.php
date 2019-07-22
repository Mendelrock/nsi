<?
include("ress/entete.php");
require_once("c_o_dataset.php");
require_once("fonction.php");

// Chargement des données
if ($ACTE==1) {
	$_SESSION[id_fournisseur] = trim($_POST['id_fournisseur']);
	$_SESSION[id_famille] = trim($_POST['id_famille']);
	$_SESSION[dt_deb]   = trim($_POST['dt_deb']);
	$_SESSION[dt_fin] = trim($_POST['dt_fin']);	
}

/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<style>
	.qt{
		color:darkred
	}
	.qt:hover{
		color:red;
		text-decoration:overline underline
	}
</style>	
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Statistique de consommation</td>
               <td class='externe'><A class='externe' href='ress_export_intermediaire.php?tableau=stat_conso' target='xl'>Extraction XL</A></td>
            </tr>
         </table>
         <table class="requeteur">
			<form method="post" name ="conso" action="conso.php?ACTE=1" target="droite">
            <tr>
               <td class="requet_right">Fournisseur</td>
               <td class="requet_left"><? drop_down_droit(	"O", 
						               									"select id_fournisseur, lb_fournisseur FROM fournisseur ORDER BY lb_fournisseur ASC", 
						               									"id_fournisseur", 
						               									"id_fournisseur", 
						               									"lb_fournisseur", 
						               									$_SESSION[id_fournisseur],false, "conso","N",""," ") ?>
				<td class="requet_right">Famille</td>
               <td class="requet_left"><? drop_down_droit(	"O", 
						               									"select id_famille, lb_famille FROM familles ORDER BY lb_famille", 
						               									"id_famille", 
						               									"id_famille", 
						               									"lb_famille", 
						               									$_SESSION[id_famille],false, "conso","N",""," ") ?>
               <td class="requet_right">Date de </td>
               <td class="requet_left"><? champ_date_droit ('O', "dt_deb", $_SESSION[dt_deb], "conso", "O"); ?> à 
                                       <? champ_date_droit ('O', "dt_fin", $_SESSION[dt_fin], "conso", "O"); ?></td>
               <td class="requet_right"><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
            </tr>
			</form>		
         </table>
<?
if ($ACTE==1) {
	if($_SESSION[id_fournisseur]){
	   $requete.=" and fournisseur.id_fournisseur = ".$_SESSION[id_fournisseur]." ";
	}
	if($_SESSION[id_famille]){
	   $requete.=" and article.id_famille = ".$_SESSION[id_famille]." ";
	}
	if($_SESSION[dt_deb]){
	   $requete.=" and zdataset_ofentete.date_creation >= '".$_SESSION[dt_deb]."' ";
	}
	if($_SESSION[dt_fin]){
	   $requete.=" and zdataset_ofentete.date_creation <= '".$_SESSION[dt_fin]."' ";
	}
	
   $req="
   SELECT
		article.lb_article_aff,
	   fournisseur.lb_fournisseur,
	   familles.lb_famille,
      sum(besoin.qt) as qt
   FROM
      besoin
   inner join article on (besoin.id_article = article.id_article)	
   inner join fournisseur_article on (fournisseur_article.id_article = besoin.id_article and fournisseur_article.principal = 1)	
   inner join fournisseur on (fournisseur_article.id_fournisseur = fournisseur.id_fournisseur)	
   left outer join familles on (article.id_famille = familles.id_famille)	
   inner join doc on (besoin.of = doc.id_doc)
   inner join zdataset_ofentete on (doc.id_dataset_entete = zdataset_ofentete.id_dataset)
   WHERE 1=1
      $requete
	GROUP BY 
		article.lb_article_aff,
	   fournisseur.lb_fournisseur,
	   familles.lb_famille
	ORDER BY 
	   fournisseur.lb_fournisseur,
		article.lb_article_aff";
   //echo $req;
	/*----------- EXECUTION ---------*/
	$resultat = new db_sql($req);
	
	/*----------- AFFICHAGE ---------*/
	/* Affichage des en_têtes */
	
	?>
	
		   <table class="resultat">
		      <tr>
		         <td class="resultat_tittle">Fournisseur</td>
				 <td class="resultat_tittle">Famille</td>
		         <td class="resultat_tittle">Article</td>
		         <td class="resultat_tittle">Quantité</td>
		      </tr>
	<?
	$_SESSION[stat_conso] = array();
	while($resultat->n() ){
	   $z++;
	   $_SESSION[stat_conso][] = array('Fournisseur'=>$resultat->f('lb_fournisseur'),'Famille'=>$resultat->f('lb_famille'),'Article'=>$resultat->f('lb_article_aff'),'Quantité'=>$resultat->f('qt'));
	?>
		      <tr>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_fournisseur') ?></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_famille') ?></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_article_aff') ?></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('qt') ?></td>
		      </tr>
	<?
	}
	if($z){
	   $suiv_text="Fin de liste";
	   echo "<tr>";
	   echo"<td class='resultat_footer' align='center' colspan='4'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
	   echo "</tr>";
	}
	?>
			</table>
	<?
}
?>
      </td>
   </tr>
</table>
<script type="text/javascript">

	function lance(thiss) {
   	unbinde();
   	valeur = thiss.html();
   	id = thiss.attr('id');
   	thiss.html('<input type=text value='+valeur+'>');
   	//alert (thiss.children(":first").html());
   	thiss.children(":first").focus();
   	thiss.children(":first").blur(function(){
   		$(this).parent().html(valeur);
			binde();
   	});
   	thiss.children(":first").change(function() {
			$.ajax({
   			type: "POST",
   			url: "besoin_list_ajax.php",
   			data: "qt="+$(this).val()+"&id=" + $(this).parent().attr('id'),
   			success: function(msg){
     				if (msg == '') {
						alert("Erreur !");
     				} else {
					   rep = eval('('+msg+')');
						$("#"+rep.id).html(rep.qt);
     				}
     				binde();
   			}
 			});
   	})
   }
   function unbinde() {
   	$(".qt").unbind("click");
   }
   function binde() {
   	unbinde();
		$(".qt").click(function() {
			lance($(this));
		});
   }
   binde();

</script>


<?
include ("ress/enpied.php");
?>
