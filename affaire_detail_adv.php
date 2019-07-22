<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/* Id_affaire
/*
/* Internes
/*  $ACTE
/*  $Id_statut_adv
/*  $Commentaire
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PROCDUITS --------*/
        //Controle formulaire si envoye
if( $ACTE == 1) {
   $requete="
      UPDATE
         affaire
      SET
         Commentaire   = ".My_sql_format($Commentaire).",
         Id_statut_adv = ".My_sql_format($Id_statut_adv).",
         Prix_adv = ".($Prix_adv/1000)."
      WHERE
         Id_affaire = '$Id_affaire'";
   $resultat = new db_sql("$requete");
}
?>
<table class="menu_haut_resultat">
   <tr>
      <td class="interne"><a class="interne" href="affaire_detail_produit_list.php?Id_affaire=<? echo $Id_affaire; ?>">Segments</A></td>
      <td class="interne"><a class="interne" href="affaire_detail_interaction_list.php?Id_affaire=<? echo $Id_affaire; ?>">Contacts liées</A></td>
      <td class="interne_actif">Adv</td>
      <td width="467"></td>
   </tr>
</table>
<?

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales
$resultat = new db_sql("
   SELECT
      affaire.Id_statut_adv,
      affaire.Commentaire,
      affaire.Prix_adv
   FROM
      affaire
   WHERE
      affaire.Id_affaire = $Id_affaire");
/*----------- EXECUTION ---------*/
$resultat->n();
$Id_statut_adv = $resultat->f("Id_statut_adv");
$Commentaire = $resultat->f("Commentaire");
$Prix_adv = $resultat->f("Prix_adv");

?>
<body class="application">
   <form method="post" name ="adv" action="affaire_detail_adv.php?ACTE=1">
      <table class="requeteur">
         <tr>
            <td class="requet_right">Statut</td>
            <td class="requet_left"><? drop_down_droit("adv","SELECT statut_adv.Id_statut_adv, statut_adv.Lb_statut_adv FROM statut_adv", "Id_statut_adv", "Id_statut_adv", "Lb_statut_adv", $Id_statut_adv,false, "adv","O", "", " "); ?></td>
            <td class="requet_right">Montant Saisi (€)</td>
            <td class="requet_left"><? champ_numeric_droit("adv", "Prix_adv", $Prix_adv*1000, 0, 10, "Adv", "N")?></td>
           </tr>
           <tr>
              <td class="requet_right" valign = top>Commentaire</td>
              <td class="requet_left" colspan="3"><? text_area_droit ("adv","Commentaire", 80, 4, 255, $Commentaire,'N'); ?></td>
           </tr>
<?
if (droit_utilisateur("adv") == "O") {
?>
              <td class="requet_button" colspan="4">
                 <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
                 <input type="hidden" name="Id_affaire" value="<? echo $Id_affaire; ?>">
                 <input class="requeteur_button" type="submit" name="Submit" value="Enregistrer" OnClick="return champ_oblig('adv')">
              </td>
<?
}
?>

      </table>
      <? stop_refresh_form () ?>
   </form>
<?
include ("ress/enpied.php");
?>