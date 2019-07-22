<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Id_client
/*
/* Internes
/*  $ACTE
/*  $commentaire
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PROCDUITS --------*/

//mise à jour du commentaire
if ( $ACTE==1 ) {
   if (!$Id_client) {
      $message = "erreur technique : Client manquant";
   }
   if (!$message) {
      if (req_sim ("select count(1) as compte from client_commentaire where id_client = $Id_client","compte")) {
         $requete = "
         UPDATE
            client_commentaire
         SET
            commentaire = ".My_sql_format($Commentaire)."
         WHERE
            id_client = ".My_sql_format($Id_client);
      } else {
         $requete = "
         INSERT INTO client_commentaire (
            id_client,
            commentaire)
         VALUES (
            ".My_sql_format($Id_client).",
            ".My_sql_format($Commentaire).")";
      }
      $resultat= new db_sql("$requete");
   }
}
include 'client_detail_menugenerique.php'; echo get_menu(6);

$requete = "
         SELECT
            commentaire
         FROM
            client_commentaire
         WHERE
            id_client = $Id_client";
$resultat = new db_sql($requete);
$resultat->n();
?>
<table class="requeteur">
   <form method="post" name = "commentaire" action="client_detail_commentaire.php">
        <tr>
           <td class="requet_left"><? text_area_droit ("O","Commentaire", 148, 20, 10000, $resultat->f('Commentaire'),"N"); ?></td>
        </tr>
        <tr>
           <td class="requet_right">
              <input type="hidden" name="ACTE" value="1">
              <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
              <input class="requeteur_button" type="submit" name="Submit" value="Enregistrer">
           </td>
        </tr>
   </form>
</table>
<?
include ("ress/enpied.php");
?>
