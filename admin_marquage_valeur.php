<?
/*-----------------PARAMETRES---------------------
/* Internes
/*  $ACTE
/*  $Id_marquage
/*  $Lb_marquage
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Valeurs de marquage</td>
         <td></td>
      </tr>
   </table>
   <table class="requeteur">
<?

/*--------------- TRAITEMENT de la maj_sectorisation--------*/
//Controle formulaire si envoye
if (($ACTE == 1) or ($ACTE == 2)) {
   if(!$Lb_marquage){
      $message = "Libellé manquant";
   }
}
if(!$message){
   //Ajouter une consigne
   if($ACTE==1){
      $requete="
          INSERT INTO
             marquage (
                Lb_marquage)
          VALUES (
             ".My_sql_format($Lb_marquage).")";
       $resultat= new db_sql("$requete");
   }
   //Modifier une consigne
   if($ACTE==2) {
       $requete="
       UPDATE
          marquage
       SET
          Lb_marquage    = ".My_sql_format($Lb_marquage)."
       WHERE
          Id_marquage = $Id_marquage";
       $resultat = new db_sql("$requete");
   }
   if ($ACTE==3) {
      $requete="
       DELETE from
          marquage
       where
          Id_marquage=$Id_marquage";
      $resultat = new db_sql("$requete");
      $ACTE=4;
   }
   if ($ACTE==4) {
      $requete="
       DELETE from
          client_marquage
       where
          Id_marquage=$Id_marquage";
      $resultat = new db_sql("$requete");
   }
}
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="
           SELECT
              Id_marquage,
              Lb_marquage
           FROM
              marquage
           ORDER BY
              Lb_marquage ASC";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
        <table class="resultat">
                <tr>
                <td class="resultat_tittle">Marquage</td>
                <td class="resultat_tittle">&nbsp;</td>
                </tr>
<?
        // Boucle de lecture
                while($resultat->n()){
                   echo "<tr>";
                   echo "<form method='post' name='consigne".$z."' action='admin_marquage_valeur.php'>\n";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_ouvert_droit("admin", "Lb_marquage", $resultat->f("Lb_marquage"), 60, 60, "marquage".$z, "O"),"</td>";
?>
                   <td bgcolor="<? echo alternat($z) ?>">
                      <input class="requeteur_button" type="submit" name="modifier" value="modifier"   onClick="return champ_oblig('consigne<? echo $z ?>')">
                      <input class="requeteur_button" type="submit" name="supprimer" value="supprimer" onClick="this.form.ACTE.value = '3'">
                      <input class="requeteur_button" type="submit" name = "vider" value="vider(<? echo req_sim('select count(1) compte from client_marquage where id_marquage = '.$resultat->f("Id_marquage"),'compte') ?>)" onClick="this.form.ACTE.value = '4'">
                      <input type="hidden" name="Id_marquage" value="<? echo $resultat->f("Id_marquage");?>">
                      <input type="hidden" name="ACTE" value="2">
                   </td>
                   </tr>
                   </form>
<?
                   $z++;
                }
?>
                    <form method="post" name="nouveau" action="admin_marquage_valeur.php?ACTE=1">
                     <tr>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_ouvert_droit("admin", "Lb_marquage", "", 60, 60, "nouveau", "N"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Ajouter" onClick="return champ_oblig('nouveau')"></td>
                      </tr>
      </form>
   </table>
<?
include ("ress/enpied.php");
?>