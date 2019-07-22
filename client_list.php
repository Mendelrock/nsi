<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="client" action="client_list_result.php" target="resultat">
<table class="cadre_application"><tr><td class="cadre_application">
   <table class="menu_haut">
      <tr>
         <td class="menu_haut">Recherche de clients</td>
                <? if(droit_utilisateur("client")){
                   echo "<td class='externe'><A class='externe' href='client_detail.php'>Créer un client</A></td>";
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
                           $defident=$session_client_liste_id_utilisateur;
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
                ?> </td>
         <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Prio][libelle]; ?></td>
         <td class="requet_left"><? drop_down_droit("client","SELECT Id_prio, Lb_prio FROM prio ORDER BY lb_prio", "Prio", "Id_prio", "Lb_prio", $session_client_liste_prio,false, "client",$_SESSION[champ_parametrable][client][Prio][obligatoire], "", " "); ?></td>
         <td class="requet_right">Raison Sociale</td>
         <td class="requet_left" colspan="2" ><? champ_ouvert_droit("O","Raison_sociale", $session_client_liste_raison_sociale,60, 30, "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Siret</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Siret", $session_client_liste_siret,14, 14,"N"); ?></td>
         <td class="requet_right">Code Naf</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Code_naf", $session_client_liste_code_naf,5, 6,"N");?></td>
         <td class="requet_right">Ville</td>
         <td class="requet_left" colspan="2"><? champ_ouvert_droit("O","Ville", $session_client_liste_ville,60, 30, "N"); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Statut</td>
         <td class="requet_left"><? drop_down_droit("client","SELECT Id_statut, Lb_statut FROM client_statut order by Lb_statut", "Id_statut", "Id_statut", "Lb_statut", $session_client_liste_id_statut,false, "client","N", "", " "); ?></td>
         <td class="requet_right">Groupe</td>
         <td class="requet_left"><? champ_ouvert_droit("O","Groupe", $session_client_liste_groupe,60, 35,"N");?></td>
         <td class="requet_right">Origine</td>
         <td class="requet_left"><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Id_marquage", "Id_marquage", "Lb_marquage", $session_client_liste_id_marquage,false, "client","N", "", " "); ?></td>
      </tr>
      <tr>
         <td class="requet_right">Téléphone</td>
         <td class="requet_left"><? champ_numeric_droit("O","Telephone", $session_client_liste_telephone,0, 10, "client","N"); ?></td>
         <td class="requet_right">Code Postal</td>
         <td class="requet_left"><? champ_numeric_droit("O","Cp", $session_client_liste_cp,0, 5, "client", "N"); ?></td>
         <td class="requet_right">Effectif</td>
         <td class="requet_left" colspan="2"><? drop_down_droit("O","", "Code_effectif_o", "", "", $session_client_liste_code_effectif_o,false, "client","N", "=|>=|<=", "=|>=|<=");?><? drop_down_droit("O","SELECT Code_effectif, Lb_effectif FROM code_effectif", "Code_effectif", "Code_effectif", "Lb_effectif", $session_client_liste_code_effectif,false, "client","N", "", " ");?></td>
      </tr>
<!--
      <tr>
         <td class="requet_right">Marquage</td>
         <td class="requet_left" rowspan = 2><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Id_marquage[]\" multiple=\"yes", "Id_marquage", "Lb_marquage", $session_client_liste_id_marquage,false, "client","N", "", " "); ?></td>
         <td class="requet_right">Parc concurrent</td>
         <td class="requet_left"><? drop_down_droit("O","SELECT Id_produit, Lb_produit FROM produit WHERE niveau = 2", "Id_gamme", "Id_produit", "Lb_produit", $session_client_liste_id_gamme,false, "client","N", "", " "); ?></td>
      </tr>
-->
      <tr>
         <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Id_type][libelle]; ?></td>
         <td class="requet_left"><? drop_down_droit("O","SELECT concat(' = \'',Id_type,'\'') as Id_type, Lb_type FROM type order by Lb_type", "Id_type", "Id_type", "Lb_type", $session_client_liste_id_type,false, "client","N", "", " "); ?></td>
		 <td class="requet_right">Date de 1ère signature</td>
		 <td class="requet_left"><? champ_date_droit ("O", "client_liste_date_prem_sign_d", $_SESSION[client_liste_date_prem_sign_d], "client", "N") ?>au<? champ_date_droit ("O", "client_liste_date_prem_sign_f", $_SESSION[client_liste_date_prem_sign_f], "client", "N") ?></td>
         <td class="requet_right">Affichage</td>
         <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "client", "O"); ?> (Lignes)</td>
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
if ($_SESSION[client_liste_go]) {
?>
<script langage="javascript">client.submit();</script>
<?
} 
include ("ress/enpied.php");
?>
