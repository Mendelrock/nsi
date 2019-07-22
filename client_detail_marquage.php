<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Id_client
/*
/* Internes
/*  $ACTE
/*  $Id_client
/*  $Id_marquage
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PROCDUITS --------*/

//Ajouter un marquage
if ( $ACTE==1 ) {
   if (!$Id_client) {
      $message = "erreur technique : Client manquant";
   }
   if (!$Id_marquage) {
      $message = "erreur technique : Marquage inexistant";
   }
   if (!$message) {
      $requete = "
         INSERT INTO client_marquage (
            Id_client,
            Id_marquage)
         VALUES (
            ".My_sql_format($Id_client).",
            ".My_sql_format($Id_marquage).")";
      $resultat= new db_sql("$requete");
   }
}

//Supprimer des produits
if ( $ACTE==2 ) {
   $resultat= new db_sql("
   DELETE FROM
      client_marquage
   WHERE
      Id_client = $Id_client  and
      Id_marquage = $Id_marquage");
}

include 'client_detail_menugenerique.php'; echo get_menu(4);

$requete = "
         SELECT
            marquage.Lb_marquage,
            marquage.Id_marquage
         FROM
            client_marquage,
            marquage
         WHERE
            client_marquage.Id_client = $Id_client and
            client_marquage.Id_marquage = marquage.Id_marquage";
$resultat = new db_sql($requete); 
?>
<table class="resultat">
   <tr>
      <td class="resultat_tittle">Source fichier</td>
<?
if (droit_utilisateur("admin")) {
?>
      <td class="resultat_tittle" width="180">&nbsp;</td>
<?
}
?>
   </tr>
<?
// Boucle de lecture
while ($resultat->n()) {
   echo "<tr><td class='resultat_list' bgcolor='",alternat($z),"'>".$resultat->f('Lb_marquage')."</td>";
   if (droit_utilisateur("admin")) {
?>
   <form method="post" name="supprimer" action="client_detail_marquage.php?ACTE=2&Id_client=<? echo $Id_client; ?>&Id_marquage=<? echo $resultat->f('Id_marquage') ?>">
   <td bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Supprimer"></td>
   </form>
<?
   }
?>
   </tr>
<?
$z++;
}
?>
<?
if (droit_utilisateur("admin") and req_sim("SELECT
                                                count(1) as compte
                                             FROM
                                                marquage
                                             LEFT JOIN
                                                client_marquage
                                             ON
                                                client_marquage.Id_marquage = marquage.Id_marquage and
                                                client_marquage.Id_client = $Id_client
                                             WHERE
                                                client_marquage.Id_marquage IS NULL","compte")){
?>
   <form method="post" name="nouveau" action="client_detail_marquage.php?ACTE=1&Id_client=<? echo $Id_client; ?>">
      <tr>
         <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit("O","
                                                                          SELECT
                                                                             marquage.Lb_marquage,
                                                                             marquage.Id_marquage
                                                                          FROM
                                                                             marquage
                                                                          LEFT JOIN
                                                                             client_marquage
                                                                          ON
                                                                             client_marquage.Id_marquage = marquage.Id_marquage and
                                                                             client_marquage.Id_client = $Id_client
                                                                          WHERE
                                                                             client_marquage.Id_marquage IS NULL","Id_marquage", "Id_marquage", "Lb_marquage", "", false, "O","","",""); ?></td>
         <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Ajouter"></td>
      </tr>
   </form>
</table>
<?
}
include ("ress/enpied.php");
?>
