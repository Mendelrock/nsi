<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Id_client
/*
/* Internes
/*  $ACTE
/*  $Id_utilisateur
/*  $Id_client
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PROCDUITS --------*/

//Ajouter une ligne d'affectation
if ( $ACTE==1 ) {
   if (!$Id_client) {
      $message = "erreur technique : Client manquant";
   }
   if (!$Id_utilisateur) {
      $message = "erreur technique : Utilisateur manquant";
   }
   if (!$message) {
      $requete = "
         INSERT INTO portefeuille (
            Id_utilisateur,
            Id_client)
         VALUES (
            ".My_sql_format($Id_utilisateur).",
            ".My_sql_format($Id_client).")";
      $resultat= new db_sql("$requete");
   }
}

//Supprimer une ligne d'affectation
if ( $ACTE==2 ) {
   $resultat= new db_sql("
   DELETE FROM
      portefeuille
   WHERE
      Id_client = $Id_client and
      Id_utilisateur = $Id_utilisateur");
}

include 'client_detail_menugenerique.php'; echo get_menu(5);

$requete = "
         SELECT
            utilisateur.Id_utilisateur,
            concat(utilisateur.Nom,' ',utilisateur.Prenom) as Lb_utilisateur
         FROM
            portefeuille,
            utilisateur
         WHERE
            portefeuille.Id_utilisateur = utilisateur.id_utilisateur and
            portefeuille.Id_client = $Id_client";
$resultat = new db_sql($requete);
?>
<table class="resultat">
   <tr>
      <td class="resultat_tittle">Commercial</td>
<?
if (droit_utilisateur("secma")) {
?>
      <td class="resultat_tittle">&nbsp;</td>
<?
}
?>
   </tr>
<?
while ($resultat->n()) {
?>
   <form method='post' action='client_detail_affectation.php?ACTE=2'>
   <tr>
      <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"'><? echo $resultat->f(Lb_utilisateur)?></td>
<?
if (droit_utilisateur("secma")) {
?>
      <td bgcolor="<? echo alternat($z) ?>">
      <input class="requeteur_button" type="submit" name="supprimer" value="supprimer" onClick="javascript: return (confirm('confirmez vous ?'))">
      <input type="hidden" name="Id_utilisateur" value=<? echo $resultat->f(Id_utilisateur)?>>
      <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
      </td>
   </tr>
<?
}
?>
   </form>
<?
   $z++;
}
if (droit_utilisateur("secma")) {
?>
   <form method='post' action='client_detail_affectation.php?ACTE=1'>
   <tr>
      <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit("O","SELECT
                                                                             concat(Nom,' ',Prenom) as Lb_utilisateur, utilisateur.Id_utilisateur
                                                                          FROM
                                                                             utilisateur
                                                                          LEFT JOIN
                                                                             portefeuille
                                                                          ON
                                                                             portefeuille.Id_utilisateur = utilisateur.id_utilisateur and
                                                                             portefeuille.Id_client = $Id_client
                                                                         WHERE
                                                                             portefeuille.Id_client IS NULL","Id_utilisateur", "Id_utilisateur", "Lb_utilisateur", $resultat->f('Id_utilisateur'), false, "","","",""); ?></td>
      <td bgcolor="<? echo alternat($z) ?>">
      <input class="requeteur_button" type="submit" name="ajouter" value="ajouter" onClick="javascript: return (confirm('confirmez vous ?'))">
      <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
      </td>
   </tr>
   </form>
<?
}
?>
</table>
<?
include ("ress/enpied.php");
?>
