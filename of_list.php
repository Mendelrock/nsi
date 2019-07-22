<?
include("ress/entete.php");
require_once("c_o_dataset.php");
/*------ Ecran du Requéteur-------*/
if (!is_array($_SESSION[type_doc])) $_SESSION[type_doc]=array(); 
?>

<body class="application">
	<form method="post" name="feuille" action="of_list_result.php?ACTE=1" target="resultat">
		<table class="cadre_application">
			<tr>
				<td class="cadre_application">
					<table class="menu_haut">
						<tr>
							<td class="menu_haut">Recherche des OF</td>
							<? if(droit_utilisateur("OF")) {
								echo "<td class='externe'><A class='externe' href=\"javascript:window.open('imprimer_ofs.php');void(0);\">Imprimer les OF affichés</A></td>";
							}
							?>
							<td class='externe'><A class='externe' href="javascript:window.open('xl.php?tableau=xl_of_list');void(0);">Export EXCEL</A>
						</tr>
					</table>
					<table class="requeteur">
						<tr>
							<td class="requet_right">Origine</td>
							<td class="requet_left">
								<? drop_down_droit("O", "", "origine_of_list", "", "", $_SESSION[origine_of_list],false, "feuille", "N", $_SESSION[origine][list_origine], $_SESSION[origine][list_origine_value]) ?>
							</td>
							<td class="requet_right">Type d'OF</td>
							<td class="requet_left">
								<? drop_down_droit("O", "", "operateur_type_doc", "", "", $_SESSION[operateur_type_doc],false, "feuille", "N", "|not", "=|<>") ?>
							</td>
							<td class="requet_left">
								<? drop_down_droit("O", "select distinct type_doc from doc where type_doc like 'Bon%' or type_doc like 'OF - %' ORDER BY type_doc ASC", "type_doc[]", "type_doc", "type_doc", $_SESSION[type_doc], false, "feuille","N","","") ?>
							<td class="requet_right">Statut</td>
							<td class="requet_left">
								<? 
									$statut = new champ('statut_of'); 
									$statut->valeurs = array_merge(array(""=>"[Tous]"), $statut->valeurs);
									if (!$_SESSION[_champ_statut_of]) $_SESSION[_champ_statut_of] = " ";
									$statut->set($_SESSION[_champ_statut_of]); 
									$statut->id_type_champ='lov';
									$statut->nonmodifiable=0; 
									echo $statut->html();
								?>
							</td>
						</tr>
						<tr>
							<td class="requet_right">N° de commande</td>
							<td class="requet_left">
								<? champ_ouvert_droit("O","NumCommande", $_SESSION[NumCommande],60, 30, "N"); ?>
							<td class="requet_right">Date de commande du</td>
							<td class="requet_left" colspan=2>
								<? champ_date_droit ("O", "Date_of_d", $_SESSION['Date_of_d'], "", "N") ?>au
								<? champ_date_droit ("O", "Date_of_f", $_SESSION['Date_of_f'], "", "N") ?>
							</td>
							<td class="requet_right">Affichage</td>
							<td class="requet_left">
								<? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
						<tr>
							<td class="requet_right">Agence</td>
							<td class="requet_left">
								<? drop_down_droit ("O",
                   	"SELECT nom FROM utilisateur u WHERE exists(SELECT 1 from utilisateur WHERE Id_responsable = u.Id_utilisateur)",
                   "nom_agence", "nom", "nom",$_SESSION[agence], false, "utilisateur", "N", "", " "); ?>
							</td>

							<td class="requet_right">Date d'import du</td>
							<td class="requet_left" colspan=2>
								<? champ_date_droit ("O", "Date_creation_d", $_SESSION['Date_creation_d'], "", "N") ?>au
								<? champ_date_droit ("O", "Date_creation_f", $_SESSION['Date_creation_f'], "", "N") ?>
							</td>
							
							<td class="requet_right">N° Feuille de cotes</td>
							<td class="requet_left">
								<? champ_ouvert_droit("O","num_of", $_SESSION[num_of],60,30,"N"); ?>
							</td>
						</tr>
						<tr>
							<td class="requet_right" colspan=7><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
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
		<?/*
		if ($_SESSION[feuille_liste_go]) {
		?>
			feuille.submit(); 
		<?
		}
		
		*/?>
	</script>
	<? include ("ress/enpied.php"); ?>