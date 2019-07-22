<?
include("ress/entete.php");
require_once("c_o_dataset.php");
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="affaire" action="affaire_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
                <td class="menu_haut">Recherche d'affaires</td>
                <td class='externe'><A class='externe' href="javascript:window.open('affaire_export_xl.php');void(0);">Export EXCEL</A>
            </tr>
         </table>

         <table class="requeteur">
            <tr>
               <td class="requet_right">Date création du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_crea_d", $session_affaire_liste_date_crea_d, "affaire", "N") ?>au<? champ_date_droit ("O", "Date_crea_f", $session_affaire_liste_date_crea_f, "affaire", "N") ?></td>
               <td class="requet_right">Date prévision/réalisation du</td>
               <td class="requet_left"><? champ_date_droit ("O", "Date_prev_d", $session_affaire_liste_date_prev_d, "affaire", "N") ?>au<? champ_date_droit ("O", "Date_prev_f", $session_affaire_liste_date_prev_f, "affaire", "N") ?></td>
            </tr>
            <tr>
               <td class="requet_right">Nom de l'affaire</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Commentaire", $_SESSION[affaire_liste_commentaire], 60, 30, "N"); ?>
               <td class="requet_right">Statut</td>
               <td class="requet_left"><? drop_down_droit(droit_utilisateur('Affaire_list.signe') ? "N" : "O", "select Id_statut, Lb_statut FROM statut ORDER BY Lb_statut ASC", "Id_statut", "Id_statut", "lb_statut", droit_utilisateur('Affaire_list.signe') ? "5" : $session_affaire_liste_id_statut,false, "affaire","N",""," ") ?></td>


            </tr>
            <tr>
               <td class="requet_right">Commercial</td>
               <td class="requet_left"><?
                   drop_down_droit("O","
						 SELECT distinct
							  Id_utilisateur,
							  Nom 
						 FROM 
							  utilisateur
						 WHERE 
							  utilisateur.id_profil in (1,5)
						 ORDER BY 
						 Nom ASC", "Id_utilisateur", "id_utilisateur", "nom", $session_affaire_liste_id_utilisateur,false, "client","N", "", " ");
               ?></td>
               <td class="requet_right">Cible</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_type, Lb_type FROM type ORDER BY Lb_type", "Id_type", "Id_type", "Lb_type", $_SESSION[affaire_liste_id_type],false, "client","N", "", " "); ?></td>
<!--           <td class="requet_right">Secteur</td>
               <td class="requet_left"><?
                        $defident="";
                        if(droit_utilisateur("secto")){
                           $droit_drop_down = "N";
                           $defident = $_SESSION[id_utilisateur];
                        } else {
                           $defident = $session_affaire_liste_id_secteur;
                           $droit_drop_down = "O";
                         }
                        drop_down_droit($droit_drop_down,"SELECT Id_utilisateur,Nom FROM utilisateur where id_responsable is not null ORDER BY Nom ASC", "Id_secteur", "Id_utilisateur", "nom", $defident,false, "client","N","", " ");
                        ?></td>
               <td class="requet_right">Numéro</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Id_affaire", $session_affaire_liste_id_affaire,8, 8, "N"); ?>
            </tr>
            <tr>
               <td class="requet_right">Marquage</td>
               <td class="requet_left" rowspan = 2><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Id_marquage[]\" multiple=\"yes", "Id_marquage", "Lb_marquage", $session_affaire_liste_id_marquage,false, "client","N", "", " "); ?></td>
               <td class="requet_right">Statut ADV</td>
               <td class="requet_left"><? drop_down_droit("O","SELECT Id_statut_adv, Lb_statut_adv FROM statut_adv ORDER BY Lb_statut_adv ASC", "Id_statut_adv", "Id_statut_adv", "lb_statut_adv", $session_affaire_liste_id_statut_adv,false, "affaire","N",""," ") ?></td>
-->
            </tr>
            <tr>
               <td class="requet_right">Secteur</td>
               <td class="requet_left"><?
                        $defident="";
                        if(droit_utilisateur("secto")){
                           $droit_drop_down = "N";
                           $defident = $_SESSION[id_utilisateur];
                        } else {
                           $defident = $session_affaire_liste_id_secteur;
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
                                                            Nom ASC", "Id_secteur", "Id_utilisateur", "nom", $defident,false, "client","N","", " ");
                        ?></td>
              <td class="requet_right">N° de devis</td>
              <td class="requet_left"><? champ_ouvert_droit("O","Id_transac",$_SESSION[affaire_liste_id_transac],30, 30,"N"); ?></td>

            </tr>
            <tr>
               <td class="requet_right">Nom de l'interlocuteur</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Interlocuteur", $_SESSION[affaire_liste_interlocuteur],60, 30, "N"); ?>
               <td class="requet_right">Raison sociale</td>
               <td class="requet_left"><? champ_ouvert_droit("O","Raison_sociale", $_SESSION[affaire_liste_raison_sociale],60, 30, "N"); ?>
            </tr>

            <tr>
               <td class="requet_right">Affichage</td>
               <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)
               <td class="requet_right" rowspan = 2>Statut FDC</td>
               <td class="requet_left" rowspan = 2>
                    <?
					drop_down_droit("O", "select '' as valeur_stockee, '[Tous]' as valeur_affichee from dual union select 'Aucune' as valeur_stockee, '[Aucune]' as valeur_affichee from dual union select valeur_stockee, valeur_affichee from champ_lov_valeurs where field = 'statut'", "id_statut_fdc[]", "valeur_stockee", "valeur_affichee", $_SESSION[statut_fdc] = [], false, "lb_statut_fdc","N","","");
					?>
               </td>
            </tr>
            <tr>
               <td class="requet_right">Exclusion des Affaires non NSI</td>
               <td class="requet_left"><? champ_binaire_droit("com","Pnne","1",$_SESSION[affaire_liste_pnne]); ?>
            </tr>

             <tr>
                 <td class="requet_right" colspan="12"><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
             </tr>

         </table>
         <table class="requeteur">
                <tr>
                <td><iframe src="" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
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
