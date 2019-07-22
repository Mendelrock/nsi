<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="ticket" action="ticket_list_result.php" target="resultat">
<table class="cadre_application"><tr><td class="cadre_application">
   <table class="menu_haut">
      <tr>
         <td class="menu_haut">Recherche de tickets</td>
                <? if(droit_utilisateur("ticket")){
                    echo "<td class='externe'><A class='externe' href='ticket_detail.php'>Créer un ticket</A></td>";
                }
                ?>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class="requet_right">Numéro</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Id_Ticket", $_SESSION[ticket_liste_Id_Ticket],60, 10, "N"); ?> </td>
         <td class="requet_right">Créateur</td>
         <td class="requet_left"><? drop_down_droit("O","SELECT Id_utilisateur, Nom FROM utilisateur u,autorisation a where u.Id_profil=a.Id_profil and Id_droit=29", "Id_utilisateur_createur", "Id_utilisateur", "Nom", $_SESSION[ticket_liste_Id_utilisateur_createur],false, "ticket","N", "", " "); ?></td>
         <td class="requet_right">Responsable</td>
         <td class="requet_left" colspan="2" ><? drop_down_droit("O","SELECT Id_utilisateur, Nom FROM utilisateur u,autorisation a where u.Id_profil=a.Id_profil and Id_droit=29", "Id_utilisateur_responsable", "Id_utilisateur", "Nom", $_SESSION[ticket_liste_Id_utilisateur_responsable],false, "ticket","N", "", " "); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Statut</td>
         <td class="requet_left"><? drop_down_droit("O","SELECT Id_statut, Lb_statut FROM ticket_statut order by Id_statut", "Id_statut", "Id_statut", "Lb_statut", $_SESSION[ticket_liste_Id_statut],false, "ticket","N", "", " "); ?></td>

          <td class="requet_right">Nom</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Nom", $_SESSION[ticket_liste_Nom],60, 35,"N");?></td>
         <td class="requet_right">Téléphone</td>
         <td class="requet_left" colspan="2"><? champ_ouvert_droit("O","Telephone", $_SESSION[ticket_liste_Telephone],60, 10, "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Mail</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Mail", $_SESSION[ticket_liste_Mail],60, 30, "N"); ?></td>
         <td class="requet_right">Contenu</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Texte", $_SESSION[ticket_liste_Texte],60, 35,"N");?></td>
         <td class="requet_right">Origine</td>
         <td class="requet_left" colspan="2" ><? drop_down_droit("O","SELECT lb_origine, id_origine FROM ticket_origine ORDER BY id_origine", "Id_origine", "Id_origine", "lb_origine", $_SESSION[ticket_liste_Id_origine],false, "ticket","N", "", " "); ?></td>
      </tr>
      
      <tr>
         <td class="requet_right" colspan = 6><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
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
if ($_SESSION[ticket_liste_go]) {
?>
<script langage="javascript">ticket.submit();</script>
<?
} 
include ("ress/enpied.php");
?>
