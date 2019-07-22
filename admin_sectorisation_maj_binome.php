<?
/*-----------------PARAMETRES---------------------
/* Internes
/*  $id_utilisateur
/*  $id_utilisateur_binôme
/*  $ACTE
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Binômes</td>
         <td></td>
      </tr>
   </table>
   <table class="requeteur">
<?

/*--------------- TRAITEMENT de la maj_sectorisation--------*/
if(!$message){
   //Ajouter une consigne
   if($ACTE==1){
      $requete="
          INSERT INTO
             sectorisation_binome (
                id_utilisateur,
                id_utilisateur_binome)
          VALUES (
             ".My_sql_format($Id_utilisateur).",
             ".My_sql_format($Id_utilisateur_binome).")";
       $resultat= new db_sql("$requete");
   }
   //Suprimer une consigne
   if($ACTE==2) {
      $requete="
       DELETE from
          sectorisation_binome
       where
          Id_utilisateur=$Id_utilisateur";
      $resultat = new db_sql("$requete");
   }
}

/*---------------------------------------------*/
$resultat = new db_sql("
    SELECT
       sectorisation_binome.Id_utilisateur,
       utilisateur.Nom,
       utilisateur_binome.Nom as nom_binome
    FROM
       sectorisation_binome,
       utilisateur,
       utilisateur as utilisateur_binome
    WHERE
       sectorisation_binome.Id_utilisateur = utilisateur.Id_utilisateur AND
       sectorisation_binome.Id_utilisateur_binome = utilisateur_binome.Id_utilisateur
    ORDER BY
       Nom ASC");
?>
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Consultant</td>
         <td class="resultat_tittle">Téléconsultant</td>
         <td class="resultat_tittle">&nbsp;</td>
      </tr>
<?
        // Boucle de lecture
while($resultat->n()){
   echo "<tr>";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f("Nom"),"</td>";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f("Nom_binome"),"</td>";
?>
       <td bgcolor="<? echo alternat($z) ?>">
           <a href = "admin_sectorisation_maj_binome.php?ACTE=2&Id_utilisateur=<? echo $resultat->f("Id_utilisateur") ?>" class="resultat">Supprimer</A>
       </td>
   </tr>
<?
   $z++;
}
?>
   <form method="post" name="nouveau" action="admin_sectorisation_maj_binome.php?ACTE=1">
      <tr>
         <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit("admin", "select utilisateur.Id_utilisateur, utilisateur.Nom from utilisateur left join sectorisation_binome on (sectorisation_binome.id_utilisateur = utilisateur.id_utilisateur) where Id_profil = 1 and sectorisation_binome.id_utilisateur is null", "Id_utilisateur", "Id_utilisateur", "Nom","", false, "nouveau", "O", "", " "); ?></td>
         <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit("admin", "select utilisateur.Id_utilisateur, utilisateur.Nom from utilisateur where Id_profil = 4", "Id_utilisateur_binome", "Id_utilisateur", "Nom","", false, "nouveau", "O", "", " "); ?></td>
         <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Ajouter" onClick="return champ_oblig('nouveau')"></td>
      </tr>
   </form>
</table>
<?
include ("ress/enpied.php");
?>