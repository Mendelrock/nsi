<?
include("ress/entete.php");
require_once("c_o_dataset.php");
require_once("fonction.php");

// Chargement des données
if ($ACTE==1) {
	$_SESSION[NumCommande]			= trim($_POST[NumCommande]);
	$_SESSION[Date_import_fdc]		= $_POST['Date_import'];
	$_SESSION[Date_import_f_fdc]	= $_POST['Date_import_f'];
	$_SESSION[Date_edition_fdc]	    = $_POST['Date_edition'];
	$_SESSION[Date_edition_f_fdc]	= $_POST['Date_edition_f'];
	$_SESSION[origine_impression]	= $_POST['origine_impression'];
}
$Affichage = $_POST['Affichage'];
if (!$Affichage) $Affichage = $Affichage_defaut;

/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="requeteur">
			<form method="post" name ="feuille" action="imprimer_adresse.php?ACTE=1" target="droite">
            <tr>
               
			   	 <td class="requet_right">Non encore éditée</td>
               <td class="requet_left"><input type="checkbox" name="NonEditee" value="1" >
			   	<td class="requet_right">Date d'import du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_import", $_SESSION[Date_import_fdc], "feuille", "N") ?>au
               	                     <? champ_date_droit ("O", "Date_import_f", $_SESSION[Date_import_f_fdc], "feuille", "N") ?></td>
                	<td class="requet_right">Date d'édition du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_edition", $_SESSION[Date_edition_fdc], "feuille", "N") ?>au
               	                     <? champ_date_droit ("O", "Date_edition_f", $_SESSION[Date_edition_f_fdc], "feuille", "N") ?></td>
               
            </tr>
            <tr>
               <td class="requet_right">Origine</td>
               <td class="requet_left">
			  		<? drop_down_droit("O", "", "origine_impression", "", "", $_SESSION[origine_impression],false, "feuille", "N", "|1|2|4|3", "[Tous]|Stores & Rideaux|Prosolair|Tende e Tende|Force de vente") ?>
			   	</td>
			   <td class="requet_right" colspan = 2><input class="requeteur_button" type="submit" name="Submit" value="Imprimer")"></td>
            </tr>
			</form>		
         </table>
      </td>
   </tr>
</table>
<?

include ("ress/enpied.php");
?>
