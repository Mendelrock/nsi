<?
include("ress/entete.php");
require_once("c_o_dataset.php");
/*------ Ecran du Requéteur-------*/
if (!is_array($_SESSION[type_doc])) $_SESSION[type_doc]=array(); 
?>
<body class="application">
<form method="post" name ="feuille" action="commande_achat_sf_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
				<td class="menu_haut">Recherche de commandes d'achat SF</td>
				<td class='externe'><A class='externe' href="admin_export.php?REQ=1">Export EXCEL</A>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
                  <td class="requet_right">Date de commande du</td>
               <td class="requet_left" colspan=2><? champ_date_droit ("O", "Date_of_d", $_SESSION['Date_of_d'], "", "N") ?>au<? champ_date_droit ("O", "Date_of_f", $_SESSION['Date_of_f'], "", "N") ?></td>
                  <td class="requet_right">N° de commande</td>
               <td class="requet_left"><? champ_ouvert_droit("O","NumCommande", $_SESSION[NumCommande],60, 30, "N"); ?>
                  <td class="requet_right">Affichage</td>
	            <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
            </tr>
            <tr>
                  <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_statut_po,Lb_statut_po FROM po_statut ORDER BY id_statut_po", "id_statut_commande", "id_statut_po", "Lb_statut_po", $_SESSION[commande_liste_statut],false, "commande","N", "|5", "|Réceptionnée P/E"); ?></td>
               <td class="requet_left"></td>
               <td class="requet_right">Fournisseur</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT distinct fournisseur.id_fournisseur,fournisseur.lb_fournisseur FROM fournisseur inner join produit_origine on (produit_origine.id_fournisseur = fournisseur.id_fournisseur) where statut <> 'I' ORDER BY lb_fournisseur", "id_fournisseur_commande", "id_fournisseur", "lb_fournisseur", $_SESSION[commande_liste_fournisseur],false, "commande","N", "", " "); ?></td>
            </tr>
            <tr>
               <!--<td class="requet_left" colspan = 6 style="font-size:xx-small">* Pour permettre l'envoi de plusieurs commandes : Selectionner un fournisseur</td>-->
               <td colspan=7 class="requet_right" ><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
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
<script langage="javascript">
<?
if ($_SESSION[feuille_liste_go]) {
?>
feuille.submit();
<?
}
/*?>
$("select[multiple]").hide();
$(".dd").mouseover(function() {
	$("select[multiple]").show();	
})	
$(".dd").mouseout(function() {
	$("select[multiple]").hide();	
})	
<?*/
?>
</script>
<?
include ("ress/enpied.php");
?>
