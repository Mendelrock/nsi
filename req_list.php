<?
include("ress/entete.php");

/*------ Ecran du Requêteur-------*/
$defident="";
if(droit_utilisateur("secto")){
   $droit_drop_down = "N";
   $defident=$_SESSION[id_utilisateur];
} else {
$defident=$_SESSION[client_liste_id_utilisateur];
   $droit_drop_down = "O";
}

?>
<body class="application">
<form method="post" name ="req" action="req_list_result.php" target="XL">


<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Requêteur</td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_left" bgcolor="D3DCE3" colspan = 6><? champ_binaire_droit ("O","Client_affiche", "1", "") ?>Client</td>
            </tr>
            <tr>
               <td class="requet_right">Secteur</td>
               <td class="requet_left"><? drop_down_droit($droit_drop_down,"SELECT Id_utilisateur,Nom FROM utilisateur where id_responsable is not null ORDER BY Nom ASC", "Client_id_utilisateur", "id_utilisateur", "nom", $defident,false, "req","N", "", " "); ?> </td>
               <td class="requet_right">Statut</td>
			   <td class="requet_left"><? drop_down_droit("client","SELECT Id_statut, Lb_statut FROM client_statut", "Client_statut", "Id_statut", "Lb_statut", "" ,false, "client", "N", "", " "); ?></td>
               <td class="requet_right">Raison Sociale</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Client_raison_sociale", "",60, 50, "N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Siret</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Client_siret", "",14, 14,"N"); ?></td>
               <td class="requet_right">Code Naf</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Client_code_naf", "",5, 6,"N");?></td>
               <td class="requet_right">Ville</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Client_ville", "",60, 50, "N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Téléphone</td>
               <td class="requet_left"><? champ_numeric_droit("O","Client_telephone", "",0, 10, "req","N"); ?></td>
               <td class="requet_right">Code Postal</td>
               <td class="requet_left"><? champ_numeric_droit("O","Client_cp", "",0, 5, "req", "N"); ?></td>
               <td class="requet_right">Effectif</td>
               <td class="requet_left"><? drop_down_droit("O","", "Client_code_effectif_o", "", "", "",false, "req","N", "=|>=|<=", "=|>=|<=");?><? drop_down_droit("O","SELECT Code_effectif, Lb_effectif FROM code_effectif", "Client_code_effectif", "code_effectif", "lb_effectif", "",false, "req","N", "", " ");?></td>
            </tr>
            <tr>
               <td class="requet_right"rowspan = 2>Source fichier</td>
               <td class="requet_left" rowspan = 2><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Client_id_marquage[]\" multiple=\"yes", "Id_marquage", "Lb_marquage", "",false, "client","N", "", " "); ?></td>
               <td class="requet_right">Cible</td>
               <td class="requet_left" colspan = 3><? drop_down_droit("O","SELECT concat(' = \'',Id_type,'\'') as Id_type, Lb_type FROM type", "Client_id_type", "Id_type", "Lb_type", "",false, "req","N", "|is not null", "|Ciblé"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Activité</td>
               <td class="requet_left" colspan = 3><? drop_down_droit("O","SELECT Id_prio,Lb_prio FROM prio", "Client_prio", "Id_prio", "Lb_prio", "",false, "req","N", "", " ");?></td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_left" align="left" bgcolor="D3DCE3" colspan = 6><? champ_binaire_droit ("O","Contact_affiche", "1", "") ?>Interlocuteur</td>
            </tr>
            <tr>
               <td class="requet_right">Service</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_decision, Lb_decision FROM decision", "Contact_id_decision", "Id_decision", "Lb_decision", "",false, "req","N", "", " "); ?></td>
               <td class="requet_right">Fonction</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_fonction, Lb_fonction FROM fonction", "Contact_id_fonction", "Id_fonction", "Lb_fonction", "",false, "req","N", "", " "); ?></td>
               <td class="requet_right" colspan = 2></td>
            </tr>
         </table>
		 <table class="requeteur">
            <tr>
               <td class="requet_left" align="left" bgcolor="D3DCE3" colspan = 6><? champ_binaire_droit ("O","Commercial_affiche", "1", "") ?>Commercial</td>
            </tr>
            <tr>
			   <td class="requet_right" style="width: 10%">Commercial</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_utilisateur,Nom FROM utilisateur  where id_responsable is not null ORDER BY Nom ASC", "Commercial_id_utilisateur", "id_utilisateur", "nom", $defident,false, "req","N",""," ");?> </td>
			</tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_left" align="left" bgcolor="D3DCE3" colspan = 6><? champ_binaire_droit ("O","Interaction_affiche", "1", "") ?>Contact</td>
            </tr>
            <tr>
               <td class="requet_right">Date création du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Interaction_date_crea_d", "", "req", "N") ?>au<? champ_date_droit ("O", "Interaction_date_crea_f", "", "req", "N") ?></td>
               <td class="requet_right">Date prévision du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Interaction_date_prev_d", "", "req", "N") ?>au<? champ_date_droit ("O", "Interaction_date_prev_f", "", "req", "N") ?></td>
               <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit("O","", "Interaction_notes", "", "", "",false, "req","N", "|NOT NULL|NULL","|Fait|A faire"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Commercial</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_utilisateur,Nom FROM utilisateur  where id_responsable is not null ORDER BY Nom ASC", "Interaction_id_utilisateur", "id_utilisateur", "nom", $defident,false, "req","N",""," ");?> </td>
               <td class="requet_right">Type</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_teneur, Lb_teneur FROM teneur ORDER BY Lb_teneur ASC", "Interaction_id_teneur", "id_teneur", "lb_teneur", "",false, "req","N",""," ") ?></td>
               <td colspan = 2></td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_left" align="left" bgcolor="D3DCE3" colspan = 6><? champ_binaire_droit ("O","Affaire_affiche", "1", "") ?>Affaire</td>
            </tr>
            <tr>
               <td class="requet_right">Date création du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Affaire_date_crea_d", "", "affaire", "N") ?>au<? champ_date_droit ("O", "Affaire_date_crea_f", "", "req", "N") ?></td>
               <td class="requet_right">Date prévision du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Affaire_date_prev_d", "", "affaire", "N") ?>au<? champ_date_droit ("O", "Affaire_date_prev_f", "", "req", "N") ?></td>
               <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_statut, Lb_statut FROM statut ORDER BY Lb_statut ASC", "Affaire_id_statut", "Id_statut", "lb_statut", "",false, "req","N",""," ") ?></td>
            </tr>
            <tr>
               <td class="requet_right">Commercial</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_utilisateur,Nom FROM utilisateur where id_responsable is not null ORDER BY Nom ASC", "Affaire_id_utilisateur", "Id_utilisateur", "nom", $defident,false, "req","N","", " ");?>  </td>
               <td class="requet_right">N° de devis</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Affaire_id_transac", "",60, 50, "N"); ?></td>
               <td class="requet_right">Contient</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_produit,Lb_produit FROM produit ORDER BY Lb_produit ASC", "Affaire_id_produit", "Id_produit", "Lb_produit", $Affaire_id_produit ,false, "req","N","", " ");?>  </td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_button" align="Center"><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
            </tr>
         </table>
      </td>
   </tr>
</table>
</form>
<?
include ("ress/enpied.php");
?>
