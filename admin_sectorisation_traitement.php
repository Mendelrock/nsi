<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------*/
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Traitement</td>
         <td></td>
      </tr>
   </table>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Traitement sectorisation en cours...</td>
      </tr>
   </table>
<?
//Vidange de la table
$temp = new db_sql("TRUNCATE TABLE portefeuille");

//Recupération des critere de recherches
$resultat = new db_sql("
   SELECT
      Siret,
      Cp,
      Id_utilisateur
   FROM
      sectorisation
   ORDER BY
      Priorite");

   $req = new db_sql("
      CREATE TEMPORARY TABLE portefeuille_temp (
         Id_utilisateur mediumint(8) unsigned NOT NULL,
         Id_client mediumint(8) unsigned NOT NULL)");

while ($resultat->n()) {

   $Siret_rch      = $resultat->f("Siret");
   $Cp_rch         = $resultat->f("Cp");
   $Id_utilisateur = $resultat->f("Id_utilisateur");

   //Affectation

   $req->q("
      INSERT INTO portefeuille_temp (
         Id_client,
         Id_utilisateur)
      SELECT
         client.Id_client,
         $Id_utilisateur
      FROM
         client left join portefeuille as port on (port.id_client = client.id_client)
      WHERE
         client.Siret like '$Siret_rch%' AND
         client.Cp like '$Cp_rch%' AND
         port.id_utilisateur is null");

   $req->q("
      INSERT INTO portefeuille (
         Id_client,
         Id_utilisateur)
      SELECT
         Id_client,
         Id_utilisateur
      FROM
         portefeuille_temp");

   $req->q("
      INSERT INTO portefeuille (
         Id_client,
         Id_utilisateur)
      SELECT
         portefeuille_temp.Id_client,
         sectorisation_binome.Id_utilisateur_binome
      FROM
         portefeuille_temp,
         sectorisation_binome
      WHERE
         portefeuille_temp.Id_utilisateur = sectorisation_binome.Id_utilisateur");


   $req->q("TRUNCATE TABLE portefeuille_temp");

}
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Traitement de remise en cohérence des contacts en cours...</td>
      </tr>
   </table>
<?
//Mise à jour des interlocuteurs

$resultat = new db_sql("
   SELECT
      interaction.Id_interaction,
      portefeuille.Id_utilisateur
   FROM
      interaction,
      portefeuille,
      utilisateur a,
      utilisateur b
   WHERE
      interaction.Id_client = portefeuille.Id_client and
      interaction.Id_utilisateur <> portefeuille.Id_utilisateur and
      a.id_utilisateur = interaction.id_utilisateur and
      b.id_utilisateur = portefeuille.id_utilisateur and
      a.id_profil = b.id_profil");

while ($resultat->n()) {
   $Id_utilisateur = $resultat->f("Id_utilisateur");
   $Id_interaction = $resultat->f("Id_interaction");
   $resultat2 = new db_sql("
      UPDATE
         interaction
      SET
         Id_utilisateur = $Id_utilisateur
      WHERE
         Id_interaction = $Id_interaction");
}
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Traitement de remise en cohérence des affaires en cours...</td>
      </tr>
   </table>
<?
//Mise à jour des affaires

$resultat = new db_sql("
   SELECT
      affaire.Id_affaire,
      portefeuille.Id_utilisateur
   FROM
      affaire,
      portefeuille,
      utilisateur a,
      utilisateur b
   WHERE
      affaire.Id_client = portefeuille.Id_client and
      affaire.Id_utilisateur <> portefeuille.Id_utilisateur and
      a.id_utilisateur = affaire.id_utilisateur and
      b.id_utilisateur = portefeuille.id_utilisateur and
      a.id_profil = b.id_profil");

while ($resultat->n()) {
   $Id_utilisateur = $resultat->f("Id_utilisateur");
   $Id_affaire     = $resultat->f("Id_affaire");
   $resultat2 = new db_sql("
      UPDATE
         affaire
      SET
         Id_utilisateur=$Id_utilisateur
      WHERE
         Id_affaire = $Id_affaire");
}
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Traitements terminés</td>
      </tr>
   </table>
<?
include ("ress/enpied.php");
?>