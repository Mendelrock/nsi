<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="liste" action="liste_list_result.php" target="resultat">
<table class="cadre_application"><tr><td class="cadre_application">
   <table class="menu_haut">
      <tr>
         <td class="menu_haut">Recherche de listes de prospection</td>
                <? if(droit_utilisateur("com") and !droit_utilisateur("secto")){
                   echo "<td class='externe'><A class='externe' href='liste_detail.php'>Créer une liste</A></td>";
                }
                ?>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class="requet_right">Secteur</td>
         <td class="requet_left"><?
                        $defident="";
                        if(droit_utilisateur("secto")){
                           $droit_drop_down = "N";
                           $defident=$_SESSION[id_utilisateur];
                        } else {
                           $defident = $_SESSION[liste_list_id_utilisateur];
                           $droit_drop_down = "O";
                        }
                        drop_down_droit($droit_drop_down,"SELECT 
                                                            Id_utilisateur,
                                                            Nom 
                                                          FROM 
                                                            utilisateur, 
                                                            autorisation, 
                                                            droit
                                                          WHERE 
                                                            autorisation.id_profil = utilisateur.id_profil and
                                                            autorisation.id_droit = droit.id_droit and
                                                            droit.code = 'secto'
                                                          ORDER BY 
                                                            Nom ASC", "liste_list_id_utilisateur", "id_utilisateur", "nom", $defident,false, "client","N", "", " ");
                ?> </td>
         <td class="requet_right">Nom de la liste</td>
         <td class="requet_left"><? champ_ouvert_droit("O","liste_list_lb_liste", $_SESSION[liste_list_lb_liste],60, 30, "N"); ?></td>
         <td class="requet_right">Date</td>
         <td class="requet_left"><? echo champ_date(liste_list_date, $_SESSION[liste_list_date]) ?></td>
      </tr>
      <tr>
      <tr>
         <td class="requet_right">Affichage</td>
         <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "client", "O"); ?> (Lignes)</td>
         <td class="requet_right" colspan = 4><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td><iframe src="" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
      </tr>
   </table>
</td></tr></table>
</form>
<script langage="javascript">liste.submit();</script>
<?
include ("ress/enpied.php");
?>
