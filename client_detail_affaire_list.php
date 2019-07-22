<?
include("ress/entete.php");
?>
<body class="application">

<?
include 'client_detail_menugenerique.php'; echo get_menu(3);
$requete="
   SELECT
      utilisateur.Nom,
      affaire.Id_affaire,
      affaire.Id_statut,
      DATE_FORMAT(affaire.Date_crea,'%d/%m/%y') AS Date_crea,
      DATE_FORMAT(affaire.Date_prev,'%d/%m/%y') AS Date_prev,
      affaire.Prix,
      affaire.Commentaire,
      affaire.Id_transac,
      statut.Lb_statut,
      transac.lb_transac
   FROM
      utilisateur,
      affaire
   left outer join transac on (affaire.Id_transac = transac.Id_transac),
      statut
   WHERE
      affaire.Id_statut = statut.Id_statut AND
      affaire.Id_utilisateur = utilisateur.Id_utilisateur AND
      affaire.Id_client = $Id_client";
$resultat = new db_sql($requete);
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Nom affaire</td>
         <td class="resultat_tittle">N° de devis</td>
         <td class="resultat_tittle">Date de création</td>
         <td class="resultat_tittle">Date de prévision</td>
         <td class="resultat_tittle">Statut</td>
         <td class="resultat_tittle">Montant HT</td>
         <td class="resultat_tittle">Gamme produit</td>
         <td class="resultat_tittle">Commercial</td>
      </tr>
<?
$ca_signe = 0;
while ($resultat->n()) {
?>
      <tr>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='affaire_detail.php?Id_affaire=<? echo $resultat->f('id_affaire') ?>' target='droite'><? echo substr($resultat->f('Commentaire'),0,20) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('Id_transac'),0,14) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('date_crea'),0,14) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('date_prev'),0,14) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('lb_statut'),0,30) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('prix'),0,12) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('lb_transac'),0,30) ?></td>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr($resultat->f('nom'),0,30) ?></td>
      </tr>
<?
   if ( $resultat->f('Id_statut') == 2 ) {
      $ca_signe += $resultat->f('prix');
   }
   $z++;
}
?>
      <tr>
         <td class='resultat_footer' colspan='8'>Cliquer sur le nom de l'affaire pour l'ouvrir</td>
      </tr>
      <tr>
         <td class='resultat_footer' colspan='8'>Ce client a généré  <? echo $ca_signe ?> € de CA</td>
      </tr>
   </table>
<?
include ("ress/enpied.php");
?>
