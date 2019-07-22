<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">


<table class="menu_haut_resultat">
    <tr>
        <td class="menu_haut">
            <a href="admin_export_collections.php" target="xl">Exporter </a>
        </td>
    </tr>
</table>



<form method="post" name ="toile" action="toile_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Recherche de toiles</td>
               <? if(droit_utilisateur("toile")){
                   echo "<td class='externe'><A class='externe' href='toile_detail.php'>Créer une toile</A></td>";
                  }
                ?>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_right">Libellé atelier</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Libelle_atelier", $_SESSION[toile_liste_libelle],60, 30, "N"); ?>
               <td class="requet_right">Libellé SR</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Libelle_sr", $_SESSION[toile_liste_libelle_sr],60, 30, "N"); ?></td>
               <td class="requet_right">Sélecteur</td>
               <td class="requet_left" colspan="2"><? drop_down_droit("O","", "Selecteur_toile", "", "", $_SESSION[toile_liste_Selecteur_toile],false, "toile","N", "|Rideaux|Doublure|Store|Enrouleur Exterieur|Enrouleur Interieur|Coffre Exterieur|Coffre Interieur|Coussin|Paroi", "|Rideaux|Doublure|Store bateau|Enrouleur Exterieur|Enrouleur Interieur|Coffre Exterieur|Coffre Interieur|Coussin|Paroi");?></td>
            </tr>
            <tr>
               <td class="requet_right">Orientation</td>
               <td class="requet_left"><? drop_down_droit("O","", "Orientation", "", "", $_SESSION[toile_liste_Orientation],false, "toile","N", "|neutre|oppose", "|neutre|oppose");?></td>
               <td class="requet_right">Couleur</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Couleur", $_SESSION[toile_liste_Couleur],60, 30, "N"); ?></td>
               <td class="requet_right">Gamme</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Gamme", $_SESSION[toile_liste_Gamme],60, 30, "N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Affichage</td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "toile", "O"); ?> (Lignes)</td>
               <td colspan = 2></td>
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
if ($liste_go) {
?>
<script langage="javascript">toile.submit();</script>
<?
}
include ("ress/enpied.php");
?>
