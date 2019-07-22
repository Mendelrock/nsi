<?
include("ress/entete.php");
require_once("c_o_dataset.php");
require_once("fonction.php");

// Chargement des données
if ($ACTE==1) {
	$_SESSION[id_article] = trim($_POST['id_article']);
	$_SESSION[id_besoin]   = trim($_POST['id_besoin']);
	$_SESSION[of] = trim($_POST['of']);	
	$_SESSION[lb_fdc] = trim($_POST['lb_fdc']);	
}
if ($ACTE==2) {
	$_SESSION[id_article] = "";
	$_SESSION[id_besoin]   = "";
	$_SESSION[of] = trim($_GET['of']);	
	$_SESSION[lb_fdc] = "";	
	$ACTE=1;
}

$Affichage = $_POST['Affichage'];
if (!$Affichage) $Affichage = $Affichage_defaut;

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
               <td class="menu_haut">Recherche des consommations</td>
			   <td class='externe'><A class='externe' href='ress_export_intermediaire.php?tableau=besoin' target='xl'>Extraction XL</A></td>
            </tr>
         </table>
         <table class="requeteur">
			<form method="post" name ="besoin" action="besoin_list.php?ACTE=1" target="droite">
            <tr>
               <td class="requet_right">N° de besoin</td>
               <td class="requet_left"><? champ_ouvert_droit("O","id_besoin", $_SESSION[id_besoin],10, 10, "N"); ?>
               <td class="requet_right">N° d'OF</td>
               <td class="requet_left"><? champ_ouvert_droit("O","of", $_SESSION[of],10, 10, "N"); ?>
               <td class="requet_right">Commande</td>
               <td class="requet_left"><? champ_ouvert_droit("O","lb_fdc", $_SESSION[lb_fdc],20, 20, "N"); ?>
            </tr>
            <tr>
               <td class="requet_right">Article</td>
               <td class="requet_left"><? drop_down_droit(	"O", 
						               									"select id_article, lb_article_aff FROM article ORDER BY lb_article_aff ASC",
						               									"id_article", 
						               									"id_article", 
						               									"lb_article_aff", 
						               									$_SESSION[$id_article],false, "besoin","N",""," ") ?></td>
			   	<td class="requet_right">Affichage</td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage, 0, 5, "affaire", "O"); ?> (Lignes)</td>
               <td class="requet_right" colspan = 2><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
            </tr>
			</form>		
         </table>
<?
if ($ACTE==1) {
	if($_SESSION[id_article]){
	   $requete.=" and  besoin.id_article  = ".$_SESSION[id_article]." ";
	}
	if($_SESSION[id_besoin]){
	   $requete.=" and  besoin.id_besoin  = ".$_SESSION[id_besoin]." ";
	}
	if($_SESSION[of]){
	   $requete.=" and  besoin.of  = ".$_SESSION[of]." ";
	}
	if($_SESSION[lb_fdc]){
	   $requete.=" and  zdataset_ofentete.numcommande_fdc_of like  '%".$_SESSION[lb_fdc]."%' ";
	}
	
   $req="
	SELECT
		besoin.id_besoin,
		besoin.of,
		lb_article_aff,
		besoin.qt,
		zdataset_ofentete.fdc,
		zdataset_ofentete.numcommande_fdc_of as lb_fdc     
	FROM
		besoin
	LEFT JOIN
		article on (besoin.id_article = article.id_article)
	LEFT JOIN
		doc on (besoin.of = doc.id_doc)
	LEFT JOIN 
		zdataset_ofentete on (zdataset_ofentete.id_dataset = doc.id_dataset_entete)
	WHERE 1=1
		$requete
	ORDER BY
		besoin.id_besoin DESC
	LIMIT 0, ".($Affichage+2);

	/*----------- EXECUTION ---------*/
	$resultat = new db_sql($req);
	
	/*----------- AFFICHAGE ---------*/
	/* Affichage des en_têtes */
	
	?>
	
		   <table class="resultat">
		      <tr>
		         <td class="resultat_tittle">Besoin</td>
		         <td class="resultat_tittle">OF</td>
		         <td class="resultat_tittle">Commande</td>
		         <td class="resultat_tittle">Article</td>
		         <td class="resultat_tittle">Quantité</td>
		      </tr>
	<?
	$_SESSION[besoin] = array();
	while($z<$Affichage AND $resultat->n() ){
		$_SESSION[besoin][] = array(
			'Besoin'=>$resultat->f('id_besoin'),
			'OF'=>$resultat->f('of'),
			'Commande'=>$resultat->f('lb_fdc'),
			'Article'=>$resultat->f('lb_article_aff'),
			'Quantité'=>$resultat->f('qt'));
	   $z++
	?>
		      <tr>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('id_besoin') ?></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='doc.php?id_doc=<? echo $resultat->f('of') ?>' target='droite'><? echo $resultat->f('of') ?></a></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='doc.php?id_doc=<? echo $resultat->f('fdc') ?>' target='droite'><? echo $resultat->f('lb_fdc') ?></a></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_article_aff') ?></td>
		         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' align = 'right'><div class='qt' id='<? echo $resultat->f('id_besoin')  ?>'><? echo $resultat->f('qt') ?></div></td>
		      </tr>
	<?
	}
	if($z){
	   $suiv_text="Fin de liste";
	   if($resultat->n()){
	      $suiv_text="Liste non terminée";
	   }
	   echo "<tr>";
	   echo"<td class='resultat_footer' colspan='3'>Cliquer sur statut pour ouvrir une commande</td>";
	   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
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
