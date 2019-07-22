<?
include("ress/entete.php");
/*------ Ecran du Requêteur-------*/
set_time_limit (0);
function check_jointure($table_reference, $table_utilisatrice, $champ_reference = '', $champ_utilisateur = '') {
   global $z;
   $bgcolor = alternat($z++);
   if ($champ_reference == '') {
      $champ_reference = "Id_".$table_reference;
   }
   if ($champ_utilisateur == '') {
      $champ_utilisateur = "Id_".$table_reference;
   }
   $req = "
      select
         count(1) as compte
      from
         $table_utilisatrice as utilisatrice
         left join $table_reference as reference on reference.$champ_reference = utilisatrice.$champ_utilisateur
      where
         reference.$champ_reference is null and
         utilisatrice.$champ_utilisateur is not null";
?>
      <tr>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo $table_utilisatrice ?></td>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo $table_reference ?></td>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo req_sim($req,"compte") ?></td>
      </tr>
<?
}
function check_denor_client ($table) {
   global $z;
   $bgcolor = alternat($z++);
   $req = "
      select count(0) as compte from contact, $table where contact.Id_contact = $table.Id_contact and contact.Id_client <> $table.Id_client";
?>
      <tr>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>>Champ Id_client de <? echo $table ?></td>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo req_sim($req,"compte") ?></td>
      </tr>
<?
}
function check_table($table) {
   global $z;
   $bgcolor = alternat($z++);
   $req = "
      check table $table extended";
?>
      <tr>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo $table ?></td>
         <td class='resultat_list' bgcolor=<? echo $bgcolor ?>><? echo req_sim($req,"msg_text") ?></td>
      </tr>
<?
}
?>

<body class="application">
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Intégrité référentielle de la base</td>
       <td></td>
    </tr>
</table>
      <table class="resultat">
         <tr>
            <td class='resultat_tittle'>Table référençante</td>
            <td class='resultat_tittle'>Table référencée</td>
            <td class='resultat_tittle'>Nb d'incohérences</td>
         </tr>
<?
// Affaire
check_jointure ('utilisateur','affaire');
check_jointure ('transac','affaire');
check_jointure ('duree','affaire');
check_jointure ('statut','affaire');
check_jointure ('statut_adv','affaire');
check_jointure ('contact','affaire');
check_jointure ('client','affaire');
// Parc
check_jointure ('client','parc');
check_jointure ('produit','parc','id_produit','id_gamme');
check_jointure ('transac','parc');
check_jointure ('duree','parc');
// Affaire_detail
check_jointure ('affaire','affaire_detail');
check_jointure ('produit','affaire_detail');
// Autorisation
check_jointure ('profil','autorisation');
check_jointure ('droit','autorisation');
// Client
check_jointure ('code_naf','client', 'code_naf', 'code_naf');
check_jointure ('code_effectif','client', 'code_effectif', 'code_effectif');
check_jointure ('type','client', 'id_type');
// Client_commentaire
check_jointure ('client','client_commentaire', 'id_client');
// Client_marquage
check_jointure ('client','client_marquage');
check_jointure ('marquage','client_marquage');
// Contact
check_jointure ('client','contact');
check_jointure ('civilite','contact');
check_jointure ('decision','contact');
check_jointure ('fonction','contact');
// Interaction
check_jointure ('utilisateur','interaction');
check_jointure ('contact','interaction');
check_jointure ('teneur','interaction');
check_jointure ('affaire','interaction');
check_jointure ('media','interaction');
check_jointure ('client','interaction');
// Portefeuille
check_jointure ('utilisateur','portefeuille');
check_jointure ('client','portefeuille');
// Produit
check_jointure ('produit','produit','id_produit','id_produit_pere');
check_jointure ('niveau_produit','produit','niveau','niveau');
// sectorisation
check_jointure ('utilisateur','sectorisation');
// sectorisation_binome
check_jointure ('utilisateur','sectorisation_binome');
check_jointure ('utilisateur','sectorisation_binome','id_utilisateur','id_utilisateur_binome');
// teneur_profil
check_jointure ('profil','teneur_profil');
check_jointure ('teneur','teneur_profil');
// utilisateur
check_jointure ('utilisateur','utilisateur','','id_responsable');
check_jointure ('profil','utilisateur');
?>
      </table>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Intégrité fonctionnelle de la base</td>
       <td></td>
    </tr>
</table>
      <table class="resultat">
         <tr>
            <td class='resultat_tittle'>Dénormalisation</td>
            <td class='resultat_tittle'>Nb d'incohérences</td>
         </tr>
<?
// Affaire
check_denor_client ('interaction');
check_denor_client ('affaire');
?>
      </table>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Intégrité technique de la base</td>
       <td></td>
    </tr>
</table>
      <table class="resultat">
         <tr>
            <td class='resultat_tittle'>Table</td>
            <td class='resultat_tittle'>Statut</td>
         </tr>
<?
check_table ('affaire');
check_table ('affaire_detail');
check_table ('autorisation');
check_table ('civilite');
check_table ('client');
check_table ('code_naf');
check_table ('contact');
check_table ('decision');
check_table ('droit');
check_table ('duree');
check_table ('fonction');
check_table ('niveau_produit');
check_table ('histo_connect');
check_table ('interaction');
check_table ('media');
check_table ('portefeuille');
check_table ('produit');
check_table ('profil');
check_table ('sectorisation');
check_table ('statut');
check_table ('teneur');
check_table ('transac');
check_table ('utilisateur');
check_table ('parc');
check_table ('code_effectif');
check_table ('type');
check_table ('client_commentaire');
check_table ('champ_parametrable');
check_table ('client_marquage');
check_table ('marquage');
check_table ('sectorisation_binome');
check_table ('teneur_profil');
check_table ('statut_adv');

?>
      </table>
<?
include ("ress/enpied.php");
?>