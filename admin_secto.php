<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Affectation</td>
         <td></td>
      </tr>
   </table>
<form method="post" name ="client" action="admin_secto_do.php" target="resultat">
   <table class="requeteur">
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
                                                            droit.code = 'secto'
                                                          ORDER BY 
                                                            Nom ASC", "Id_utilisateur", "id_utilisateur", "nom", "", false, "client","N", "", " ");
                ?> </td>
         <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Prio][libelle]; ?></td>
         <td class="requet_left"><? drop_down_droit("client","SELECT Id_prio, Lb_prio FROM prio", "Id_prio", "Id_prio", "Lb_prio", $Prio,false, "client",$_SESSION[champ_parametrable][client][Prio][obligatoire], "", " "); ?></td>
         <td class="requet_right">Raison Sociale</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Raison_sociale", $session_client_liste_raison_sociale,60, 30, "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Siret</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Siret", $session_client_liste_siret,14, 14,"N"); ?></td>
         <td class="requet_right">Code Naf</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Code_naf", $session_client_liste_code_naf,5, 6,"N");?></td>
         <td class="requet_right">Ville</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Ville", $session_client_liste_ville,60, 30, "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Téléphone</td>
         <td class="requet_left"><? champ_numeric_droit("O","Telephone", $session_client_liste_telephone,0, 10, "client","N"); ?></td>
         <td class="requet_right">Code Postal</td>
         <td class="requet_left"><? champ_numeric_droit("O","Cp", $session_client_liste_cp,0, 5, "client", "N"); ?></td>
         <td class="requet_right">Effectif</td>
         <td class="requet_left"><? drop_down_droit("O","", "Code_effectif_o", "", "", $session_client_liste_code_effectif_o,false, "client","N", "=|>=|<=", "=|>=|<=");?><? drop_down_droit("O","SELECT Code_effectif, Lb_effectif FROM code_effectif", "Code_effectif", "Code_effectif", "Lb_effectif", $session_client_liste_code_effectif,false, "client","N", "", " ");?></td>
      </tr>
      <tr>
         <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Id_type][libelle]; ?></td>
         <td class="requet_left"colspan="6"><? drop_down_droit("O","SELECT concat(' = \'',Id_type,'\'') as Id_type, Lb_type FROM type", "Id_type", "Id_type", "Lb_type", $session_client_liste_id_type,false, "client","N", "", " "); ?></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class="requet_left" colspan = 2><input class="requeteur_button" type="submit" name="Submit" value="Compter" onclick = "if (forms[0].go.checked) {forms[0].go.click()}; return true"></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class="requet_left" colspan = 2><input class="requeteur_button" type="submit" name="Submit" value="Affecter à" onclick = "if (forms[0].go.checked){return confirm('Confirmez-vous l affectation de cette cible ?')} else {return false}"> 
                <?
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
                                                            droit.code = 'secto'
                                                          ORDER BY 
                                                            Nom ASC", "Id_utilisateur_affect", "id_utilisateur", "nom", "", false, "client","N", "", " ");
                ?> Cochez la case par sécurité<input type="checkbox" name="go" value="1"></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td><iframe src="" width=100% height=300 name="resultat" align="middle" scrolling=no frameborder="0" allowtransparency="true"></iframe></td>
      </tr>
   </table>
</td></tr></table>
</form>


<?
include ("ress/enpied.php");
?>
