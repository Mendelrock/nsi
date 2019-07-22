<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="interaction" action="interaction_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
                <td class="menu_haut">Recherche d'actions</td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <!--
               <td class="requet_right">Date création du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_crea_d", $session_interaction_liste_date_crea_d, "interaction", "N") ?>au<? champ_date_droit ("O", "Date_crea_f", $session_interaction_liste_date_crea_f, "interaction", "N") ?></td>
               -->
               <td class="requet_right">Date prévision/réalisation du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_prev_d", $session_interaction_liste_date_prev_d, "interaction", "N") ?>au<? champ_date_droit ("O", "Date_prev_f", $session_interaction_liste_date_prev_f, "interaction", "N") ?></td>
               <td class="requet_right">Secteur</td>
               <td class="requet_left"><?
                     if(droit_utilisateur("secto")){
                         $droit_drop_down = "N";
                         $defident=$_SESSION[id_utilisateur];
                     } else {
                         $defident=$session_interaction_liste_id_secteur;
                         $droit_drop_down = "O";
                     }
                     drop_down_droit($droit_drop_down,"SELECT Id_utilisateur,Nom FROM utilisateur  where id_responsable is not null ORDER BY Nom ASC", "Id_secteur", "id_utilisateur", "nom", $defident,false, "client","N",""," ");
               ?>
            </tr>
            <tr>
               <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit("O","", "Notes", "", "", $session_interaction_liste_notes,false, "interaction","N", "|NOT NULL|NULL","|Fait|A faire"); ?></td>
               <td class="requet_right">Type</td>
               <td class="requet_left" colspan = 3><? drop_down_droit("O","SELECT Id_teneur, Lb_teneur FROM teneur ORDER BY Lb_teneur ASC", "Id_teneur", "id_teneur", "lb_teneur", $session_interaction_liste_id_teneur,false, "interaction","N",""," ") ?></td>
            </tr>
            <tr>
               <td class="requet_right">Commercial</td>
               <td class="requet_left"><?
                     drop_down_droit("O","SELECT 
                                                            Id_utilisateur,
                                                            Nom 
                                                          FROM 
                                                            utilisateur, 
                                                            autorisation, 
                                                            droit
                                                          WHERE 
                                                            autorisation.id_profil = utilisateur.id_profil and
                                                            autorisation.id_droit = droit.id_droit and
                                                            droit.code = 'com'
                                                          ORDER BY 
                                                            Nom ASC", "Id_utilisateur", "id_utilisateur", "nom", $session_interaction_liste_id_utilisateur,false, "client","N",""," ");
               ?>
               <td class="requet_right">Cible</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_type, Lb_type FROM type ORDER BY Lb_type", "Id_type", "Id_type", "Lb_type", $_SESSION[interaction_liste_id_type],false, "client","N", "", " "); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Nom de l'affaire</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Commentaire", $_SESSION[interaction_liste_commentaire],60, 30, "N"); ?>
               <td class="requet_right">Statut de l'affaire</td>
               <td class="requet_left" colspan = 3><? drop_down_droit("O","SELECT Id_statut, Lb_statut FROM statut ORDER BY Lb_statut ASC", "Id_statut", "id_statut", "lb_statut", $_SESSION[interaction_liste_id_statut],false, "interaction","N",""," ") ?></td>
            </tr>
<!--
            <tr>
               <td class="requet_right">Marquage</td>
               <td class="requet_left" rowspan = 2><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Id_marquage[]\" multiple=\"yes", "Id_marquage", "Lb_marquage", $session_interaction_liste_id_marquage,false, "client","N", "", " "); ?></td>
               <td class="requet_right">Secteur</td>
               <td class="requet_left"><?
                     if(droit_utilisateur("secto")){
                         $droit_drop_down = "N";
                         $defident=$_SESSION[id_utilisateur];
                     } else {
                         $defident=$session_interaction_liste_id_secteur;
                         $droit_drop_down = "O";
                     }
                     drop_down_droit($droit_drop_down,"SELECT Id_utilisateur,Nom FROM utilisateur  where id_responsable is not null ORDER BY Nom ASC", "Id_secteur", "id_utilisateur", "nom", $defident,false, "client","N",""," ");
               ?>
               <td colspan = 2></td>
            </tr>
-->
            <tr>
               <td class="requet_right">Contacts</td>
               <td class="requet_left" colspan = 3><? drop_down_droit("O","", "interaction_liste_argumente", "", "", $_SESSION[interaction_liste_argumente],false, "client","N", "|is not null|is null", "Tous|Argumentés|Non Argumentés"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Affichage </td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "interaction", "O"); ?> (Lignes)</td>
               <td class="requet_button" colspan="3" align="left"><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td><iframe src="" width=100% height=450 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
            </tr>
         </table>
       </td>
   </tr>
</table>
</form>
<?
if ($_SESSION[interaction_liste_go]) {
?>
<script langage="javascript">interaction.submit();</script>
<?
}
include ("ress/enpied.php");
?>
