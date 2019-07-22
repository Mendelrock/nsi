<?
include("ress/entete.php");

/**********************************************************/
/* Critères
/* Externe
/* Id_utilisateur
/* Interne
/* $Date
/**********************************************************/
$requete = "
   SELECT
      DATE_FORMAT(Date,'%d/%m/%y') as Date_connect,
      Count(1) as Compte
   FROM
      histo_connect
   where
      Id_utilisateur='$Id_utilisateur'
   GROUP BY
      Date_connect
   ORDER BY
      Date DESC";
$resultat = new db_sql($requete);
?>
<body class="application">
   <table class="resultat">
      <tr>
          <td class="resultat_tittle" colspan=2><? echo $Nom; ?><br>Historique des connexions</td>
      </tr>
<?
// Boucle de lecture
while($resultat->n()){
// affichage
?>
      <tr>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f("Date_connect") ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f("Compte") ?></td>
      </tr>
<?
   $z++;
}
?>
   </table>

<?
include ("ress/enpied.php");
?>