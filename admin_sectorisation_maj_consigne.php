<?
/*-----------------PARAMETRES---------------------
/* Internes
/*  $ACTE
/*  $Siret
/*  $Cp
/*  $Priorite
/*  $Id_utilisateur
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Consignes</td>
         <td class="interne"><A class='interne' href='admin_sectorisation_maj_consigne.php?ACTE=3'>Renumérotation</A></td>
         <td></td>
      </tr>
   </table>
   <table class="requeteur">
<?

/*--------------- TRAITEMENT de la maj_sectorisation--------*/
//Controle formulaire si envoye
if (($ACTE == 1) or ($ACTE == 2)) {
   if(!$Priorite){
      $message = "Priorite manquante";
   } else if(req_sim ("select count(1) as compte from sectorisation where Priorite = $Priorite and Id_sectorisation <> '$Id_sectorisation'", "compte")>0) {
         $message = "Priorité déjà utilisée";
   } else if(req_sim ("select count(1) as compte from sectorisation where Siret ".Egal_my_sql_format($Siret)." and Cp ".Egal_my_sql_format($Cp)." and Id_sectorisation <> '$Id_sectorisation'", "compte")>0 ){
         $message = "Couple Siret/CP déjà utilisé";
   }
}
if(!$message){
   //Ajouter une consigne
   if($ACTE==1){
      $requete="
          INSERT INTO
             sectorisation (
                Siret,
                Cp,
                Priorite,
                Id_utilisateur)
          VALUES (
             ".My_sql_format($Siret).",
             ".My_sql_format($Cp).",
             ".My_sql_format($Priorite).",
             ".My_sql_format($Id_utilisateur).")";
       $resultat= new db_sql("$requete");
   }
   //Modifier une consigne
   if($ACTE==2) {
       $requete="
       UPDATE
          sectorisation
       SET
          Siret          = ".My_sql_format($Siret).",
          Cp             = ".My_sql_format($Cp).",
          Priorite       ='$Priorite',
          Id_utilisateur ='$Id_utilisateur'
       WHERE
          Id_sectorisation=$Id_sectorisation";
       $resultat = new db_sql("$requete");
   }
   if ($ACTE==4) {
      $requete="
       DELETE from
          sectorisation
       where
          Id_sectorisation=$Id_sectorisation";
      $resultat = new db_sql("$requete");
   }
   //Modifier une consigne
   if($ACTE==3) {
      $compteur = (req_sim("select max(Priorite) as maxi from sectorisation","maxi")*10)+1;
      $requete="
       SELECT
          Id_sectorisation
       FROM
          sectorisation
       ORDER BY
          Priorite";
       $resultat = new db_sql("$requete");
      while ($resultat->n()) {
         $x = new db_sql("UPDATE sectorisation SET Priorite = ".($compteur++)." WHERE Id_sectorisation = ".$resultat->f('Id_sectorisation'));
      }
      $compteur = 1;
      $requete="
       SELECT
          Id_sectorisation
       FROM
          sectorisation
       ORDER BY
          Priorite";
       $resultat = new db_sql("$requete");
      while ($resultat->n()) {
         $x = new db_sql("UPDATE sectorisation SET Priorite = ".($compteur++*10)." WHERE Id_sectorisation = ".$resultat->f('Id_sectorisation'));
      }
   }
}
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="
           SELECT
              Id_sectorisation,
              Siret,
              Cp,
              Priorite,
              Id_utilisateur
           FROM
              sectorisation
           ORDER BY
              Priorite ASC";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
        <table class="resultat">
                <tr>
                <td class="resultat_tittle">Siret</td>
                <td class="resultat_tittle">Cp</td>
        <td class="resultat_tittle">Priorite</td>
                <td class="resultat_tittle">Id_utilisateur</td>
                <td class="resultat_tittle">&nbsp;</td>
                </tr>
<?
        // Boucle de lecture
                while($resultat->n()){
                   echo "<tr>";
                   echo "<form method='post' name='consigne".$z."' action='admin_sectorisation_maj_consigne.php'>\n";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_numeric_droit("admin", "Siret", $resultat->f("Siret"), 0, 14, "consigne".$z, "N"),"</td>";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_numeric_droit("admin", "Cp", $resultat->f("Cp"), 0, 5, "consigne".$z, "N"),"</td>";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_numeric_droit("admin", "Priorite", $resultat->f("Priorite"), 0, 8, "consigne".$z, "O"),"</td>\n";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",drop_down_droit("admin", "select Id_utilisateur, Nom from utilisateur", "Id_utilisateur", "Id_utilisateur", "Nom",$resultat->f("Id_utilisateur"), false, "consigne".$z, "O", "", " "),"</td>\n";
?>
                   <td bgcolor="<? echo alternat($z) ?>">
                      <input class="requeteur_button" type="submit" name="modifier" value="modifier"   onClick="return champ_oblig('consigne<? echo $z ?>')">
                      <input class="requeteur_button" type="submit" name="supprimer" value="supprimer" onClick="this.form.ACTE.value = '4'">
                      <input type="hidden" name="Id_sectorisation" value="<? echo $resultat->f("Id_sectorisation");?>">
                      <input type="hidden" name="ACTE" value="2">
                   </td>
                   </tr>
                   </form>
<?
                   $z++;
                }
?>
                    <form method="post" name="nouveau" action="admin_sectorisation_maj_consigne.php?ACTE=1">
                     <tr>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_numeric_droit("admin", "Siret", "", 0, 14, "nouveau", "N"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_numeric_droit("admin", "Cp", "", 0, 5, "nouveau", "N"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_numeric_droit("admin", "Priorite", "", 0, 8, "nouveau", "O"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit("admin", "select Id_utilisateur, Nom from utilisateur", "Id_utilisateur", "Id_utilisateur", "Nom","", false, "nouveau", "O", "", " "); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Ajouter" onClick="return champ_oblig('nouveau')"></td>
                      </tr>
      </form>
   </table>
<?
include ("ress/enpied.php");
?>