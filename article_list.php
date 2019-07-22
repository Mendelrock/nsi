<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
if (!$_SESSION[article_liste_go]) $_SESSION[article_liste_actif] = 1;
if (!$_SESSION[article_liste_actif]) $_SESSION[article_liste_actif] = "(1)";
?>
<body class="application">
<form method="post" name ="affaire" action="article_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Achats</td>
               <? 
               if(droit_utilisateur("article")){
                   echo "<td class='externe'><A class='externe' href='article_detail.php'>Créer un article</A></td>";
               }
                echo "<td class='externe'><A class='externe' href='ress_export_intermediaire.php?tableau=etat_stock'>Etat de stock XL</A></td>";
			
               ?>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_right">Libellé</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Libelle", $_SESSION[article_liste_libelle],60, 30, "N"); ?>
               <td class="requet_right">Référence</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Reference", $_SESSION[article_liste_reference],60, 30, "N"); ?></td>
               <td class="requet_right">Fournisseur principal</td>
               <td class="requet_left" colspan = 2><? drop_down_droit("O","SELECT distinct fournisseur.id_fournisseur, fournisseur.lb_fournisseur FROM fournisseur inner join fournisseur_article on (fournisseur_article.id_fournisseur = fournisseur.id_fournisseur) WHERE statut <> 'I' ORDER BY lb_fournisseur", "id_fournisseur", "id_fournisseur", "lb_fournisseur", $_SESSION[article_liste_id_fournisseur],false, "client","N", "", " "); ?></td>
            </tr>
            <tr>
               <td class="requet_right">A acheter</td>
               <td class="requet_left"><input type="checkbox" name="Aacheter" value="1" >
               <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit("O","", "actif", "", "",$_SESSION[article_liste_actif],false, "article","O", "(0,1,2)|(1)|(2)|(0)|(0,1)", "Tous|Actifs seuls|Fin de vie seuls|Inactifs seuls|Actifs + Fin de Vie"); ?></td>
               <td class="requet_right">Affichage</td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
               
            </tr>
			<tr>
				<td class="requet_right">Famille</td>
                <td class="requet_left" colspan = 4><? drop_down_droit("O","SELECT familles.id_famille, familles.lb_famille FROM familles order by familles.lb_famille", "id_famille", "id_famille", "lb_famille", $_SESSION[article_liste_id_famille],false, "client","N", "", " "); ?></td>
				 
				<td class="requet_right" ><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
			</tr>
         </table>
         <table class="requeteur">
                <tr>
                <td><iframe src="" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
                </tr>
         </table>
      </td>
   </tr>
</table>
</form>
<?
if ($_SESSION[article_liste_go]) {
?>
<script langage="javascript">affaire.submit();</script>
<?
}
include ("ress/enpied.php");
?>
