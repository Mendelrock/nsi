<?
include("ress/entete.php");
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Actions</td>
         <td class="interne">      <a class="interne" href="contact_detail_affaire_list.php?Id_client=<? echo $Id_client; ?>&Id_contact=<? echo $Id_contact ?>">Affaires</A></td>
         <td width"467"></td>
      </tr>
   </table>
<?
$requete="
        SELECT
           interaction.Id_interaction,
           interaction.Id_contact,
           contact.Id_contact,
           contact.Id_client,
           contact.nom as nom_intl,
           interaction.Id_teneur,
           interaction.Id_media,
           interaction.Notes,
           affaire.commentaire,
           utilisateur.Nom,
           DATE_FORMAT(interaction.Date_crea,'%d/%m/%y') AS Date_crea,
           DATE_FORMAT(interaction.Date_prev,'%d/%m/%y') AS Date_prev,
           teneur.Lb_teneur
        FROM
           interaction left outer join affaire on (interaction.id_affaire = affaire.id_affaire),
           contact,
           teneur,
           utilisateur
        WHERE
           interaction.Id_teneur = teneur.Id_teneur AND
           interaction.Id_contact = contact.Id_contact AND
           interaction.Id_utilisateur = utilisateur.Id_utilisateur AND
           interaction.Id_client = $Id_client and
           interaction.Id_contact = $Id_contact ";

$resultat = new db_sql($requete);

?>
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Date de réalisation</td>
         <td class="resultat_tittle">Nom interlocuteur</td>
         <td class="resultat_tittle">Type action</td>
         <td class="resultat_tittle">Statut</td>
         <td class="resultat_tittle">Nom affaire</td>
         <td class="resultat_tittle">Commercial</td>
      </tr>
<?
$nb_fait = 0;
while($resultat->n()){
?>
      <tr>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='interaction_detail.php?Id_interaction=<? echo $resultat->f('id_interaction') ?>' target='droite'><? echo substr($resultat->f('date_prev'),0,8) ?></a></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('nom_intl'),0,30) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('lb_teneur'),0,30) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? if ($resultat->f('notes')) { echo "Fait"; $nb_fait++;} else { echo "A faire";} ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('commentaire'),0,30) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('nom'),0,30) ?></td>
      </tr>
<?
   $z++;
}
?>
      <tr>
         <td class='resultat_footer' colspan='6'>Cliquer sur la date de création pour ouvrir un contact</td>
      </tr>
      <tr>
         <td class='resultat_footer' colspan='6'><? echo $nb_fait ?> contacts ont été réalisées auprès de ce client</td>
      </tr>
   </table>
<?
include ("ress/enpied.php");
?>
