<?
include("ress/entete.php");
/*------ Ecran du Requ�teur-------*/
?>
<body class="application">
<form method="post" name ="affaire" action="correspondance_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Recherche de correspondances</td>
               <? if(droit_utilisateur("article")){?>
                   <td class='externe'><A class='externe' href='#' onclick="showFenetreCorrespondance('2');">Cr�er une correspondance</A></td>
                   <? }
                ?>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_right">Site produit</td>
               <td class="requet_left"><? champ_ouvert_droit("O","site_produit", $_SESSION[correspondance_liste_site_produit],60, 30, "N"); ?>
               <td class="requet_right">Site propri�t�</td>
               <td class="requet_left"><? champ_ouvert_droit("O","site_propriete", $_SESSION[correspondance_liste_site_propriete],60, 30, "N"); ?></td>
               <td class="requet_right">Site valeur</td>
               <td class="requet_left"><? champ_ouvert_droit("O","site_valeur", $_SESSION[correspondance_liste_site_valeur],60, 30, "N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">NSI produit</td>
               <td class="requet_left"><? champ_ouvert_droit("O","nsi_produit", $_SESSION[correspondance_liste_nsi_produit],60, 30, "N"); ?>
               <td class="requet_right">NSI propri�t�</td>
               <td class="requet_left"><? champ_ouvert_droit("O","nsi_propriete", $_SESSION[correspondance_liste_nsi_propriete],60, 30, "N"); ?></td>
               <td class="requet_right">NSI valeur</td>
               <td class="requet_left"><? champ_ouvert_droit("O","nsi_valeur", $_SESSION[correspondance_liste_nsi_valeur],60, 30, "N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right"></td>
               <td class="requet_left"></td>
               <td class="requet_right">Affichage</td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
               <td class="requet_right"></td>
               <td class="requet_button" align="left"><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
            </tr>
         </table>
         <table class="requeteur">
                <tr>
                <td><iframe src="" width=100% height=400 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
                </tr>
         </table>
      </td>
   </tr>
</table>
</form>
<?
if ($_SESSION[affaire_liste_go]) {
?>
<script langage="javascript">affaire.submit();</script>
<?
}
include ("ress/enpied.php");
?>
