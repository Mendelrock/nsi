<?
include("ress/entete.php");
?>
<body class="application">
<form method="post" name ="affaire" action="po_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
	<tr>
		<td class="cadre_application">
			<table class="menu_haut">
				<tr>
					<td class="menu_haut">Recherche de commandes d'achat MP</td>
					<? if(droit_utilisateur("commande")){
						 echo "<td class='externe'><A class='externe' href='po_detail.php'>Créer une commande</A></td>";
						}
					?>
					<td class='externe'><A class='externe' href='ress_export_intermediaire.php?tableau=po'>Export XL</A></td>
				</tr>
			</table>
			<table class="requeteur">
				<tr>
					<td class="requet_right">Numéro</td>
					<td class="requet_left"><? champ_ouvert_droit("O","Numero", $_SESSION[commande_liste_numero],60, 30, "N"); ?>
					<td class="requet_right">Statut</td>
				    <td class="requet_left"><? drop_down_droit("O", "SELECT Id_statut_po,Lb_statut_po FROM po_statut ORDER BY Id_statut_po", "Id_statut_po[]", "Id_statut_po", "Lb_statut_po", $_SESSION[commande_liste_statut] = [], false,"commande", "N", "|2,3", "|Ouvertes"); ?></td>
					<td class="requet_right">Fournisseur</td>
					<td class="requet_left"><? drop_down_droit("O","SELECT id_fournisseur,lb_fournisseur FROM fournisseur ORDER BY lb_fournisseur", "id_fournisseur", "id_fournisseur", "lb_fournisseur", $_SESSION[commande_liste_fournisseur],false, "commande","N", "", " "); ?></td>
				</tr>
				<tr>
					<td class="requet_right">Article</td>
					<td class="requet_left"><? champ_ouvert_droit("O","lb_article", $_SESSION[commande_liste_article],60, 30, "N"); ?>
					<td class="requet_right">Date du</td>
					<td class="requet_left"><? champ_date_droit ("O", "DateCom_d", $session_commande_liste_date_d, "affaire", "N") ?>au<? champ_date_droit ("O", "DateCom_f", $session_commande_liste_date_f, "affaire", "N") ?></td>
					<td class="requet_right">Affichage</td>
					<td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
				</tr>
				<tr>
					<td class="requet_right">Mode Détaillé</td>
					<td class="requet_left"><? champ_binaire("mode_detail", 1, 0) ?> Reliquat <? champ_binaire("mode_detail_reliquat", 1, 0) ?> Echu <? champ_binaire("mode_detail_echu", 1, 0) ?></td>
				</tr>
				<tr>
					<td class="requet_right" colspan = 6><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
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

$("[name='mode_detail']").on('change', function() {
	if(!($("[name='mode_detail']").prop('checked'))) {
		$("[name='mode_detail_echu']").prop('checked', false);
		$("[name='mode_detail_reliquat']").prop('checked', false);
	}
});

$("[name='mode_detail_reliquat']").on('change', function() {
	if($("[name='mode_detail_reliquat']").prop('checked')) {
		$("[name='mode_detail']").prop('checked', true);
	} else {
		$("[name='mode_detail_echu']").prop('checked', false);
	}
});

$("[name='mode_detail_echu']").on('change', function() {
	if($("[name='mode_detail_echu']").prop('checked')) {
		$("[name='mode_detail_reliquat']").prop('checked', true);
		$("[name='mode_detail']").prop('checked', true);
	}
}); 
	
 	
	
</script>
<?
if ($_SESSION[po_liste_go]) {
?>
<script langage="javascript">affaire.submit();</script>
<?
}
include ("ress/enpied.php");
?>
