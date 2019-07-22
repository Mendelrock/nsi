<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="contact" action="contact_list_result.php" target="resultat">
<table class="cadre_application"><tr><td class="cadre_application">
   <table class="menu_haut">
      <tr>
         <td class="menu_haut">Recherche d'interlocuteurs</td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class="requet_right">Raison Sociale</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Raison_sociale", $session_contact_liste_raison_sociale,60, 30, "N"); ?></td>
         <td class="requet_right">Ville</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Ville", $session_contact_liste_ville,60, 30, "N"); ?></td>
         <td class="requet_right">Code Postal</td>
         <td class="requet_left"><? champ_numeric_droit("O","Cp", $session_contact_liste_cp,0, 5, "client", "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Civilité</td>
         <td class="requet_left"><? drop_down_droit ("O", "SELECT Id_civilite, Lb_civilite FROM civilite", "Id_civilite", "id_civilite", "lb_civilite", $session_contact_liste_id_civilite, false, "contact", "O", "", " "); ?></td>
         <td class="requet_right">Nom</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Nom",$session_contact_liste_nom,30, 30,"O"); ?></td>
         <td class="requet_right">Téléphone</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Telephone",$session_contact_liste_telephone,10, 12,"N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Cible</td>
         <td class="requet_left"><? drop_down_droit("O","SELECT Id_type, Lb_type FROM type ORDER BY Lb_type", "Id_type", "Id_type", "Lb_type", $session_contact_liste_id_type,false, "client","N", "", " "); ?></td>
         <td class="requet_right">Prénom</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Prenom",$session_contact_liste_prenom,30, 30,"O"); ?></td>
         <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Decideur][libelle]; ?></td>
         <td class="requet_left"><?drop_down_droit("O","", "Id_decision", "", "", $Id_decision,false, "","", "|O|N", "|OUI|NON");?></td>
      </tr>
      <tr>
         <td class="requet_right"></td>
         <td class="requet_right"></td>
         <td class="requet_right">Mail</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Mail",$session_contact_liste_mail,60, 30,"N"); ?></td>
         <td class="requet_right" >Secteur</td>
         <td class="requet_left"><?
                        $defident="";
                        if(droit_utilisateur("secto")){
                           $droit_drop_down = "N";
                           $defident=$_SESSION[id_utilisateur];
                        } else {
                           $defident=$session_contact_liste_id_utilisateur;
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
                                                            Nom ASC", "Id_utilisateur", "id_utilisateur", "nom", $defident,false, "client","N", "", " ");
                ?>
         </td>
      </tr>
      <tr>
         <td class="requet_right">Affichage</td>
         <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "client", "O"); ?> (Lignes)</td>
         <td class="requet_right" >Fonction</td>
         <td class="requet_left"><? drop_down_droit ("O", "SELECT Id_fonction, Lb_fonction FROM fonction", "Id_fonction", "id_fonction", "lb_fonction", $session_contact_liste_id_fonction, false, "contact", "O", "", " "); ?></td>
         <td class="requet_right" colspan=2 ><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td><iframe src="" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
      </tr>
   </table>
</td></tr></table>
</form>
<?
if ($_SESSION[contact_liste_go]) {
?>
<script langage="javascript">contact.submit();</script>
<?
}
include ("ress/enpied.php");
?>
