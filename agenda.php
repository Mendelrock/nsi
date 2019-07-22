<?
include("ress/entete.php");
/*------ Ecran du Requ�teur-------*/
if (!$Debut_semaine) {
   $Debut_semaine = aujourdhui();
}
$Debut_semaine = decale_date($Debut_semaine,1-date("w",strtotime($Debut_semaine)));
$fin_semaine = decale_date($Debut_semaine,6);
$decale_droite_semaine = decale_date($Debut_semaine,6);
$decale_gauche_semaine = decale_date($Debut_semaine,1-date("w",strtotime($Debut_semaine)));
$Id_utilisateur = $_SESSION[id_utilisateur];
$jour_de_la_semaine[1] = 'Lundi';
$jour_de_la_semaine[2] = 'Mardi';
$jour_de_la_semaine[3] = 'Mercredi';
$jour_de_la_semaine[4] = 'Jeudi';
$jour_de_la_semaine[5] = 'Vendredi';
$jour_de_la_semaine[6] = 'Samedi';
$jour_de_la_semaine[7] = 'Dimanche';
?>

<body class="application">
<table class="menu_haut_resultat"><tr><td class="cadre_application">
   <table class="menu_haut">
      <form method="post" name ="agenda" action="agenda.php">
      <tr>
         <td class="menu_haut">Agenda de la semaine du <? champ_date_droit ("O", "Debut_semaine", $Debut_semaine, "agenda", "N"); ?>au<? champ_date_droit ("N", "", $fin_semaine, "agenda","N"); ?></td>
         <td>
            <input class="requet_button" type="submit" value="Appliquer" OnClick="return champ_oblig('agenda')"></td>
      </tr>
      </form >
   </table>
<?
$req = new db_sql("
   select
      client.Raison_sociale,
      teneur.Lb_teneur,
      interaction.Id_interaction,
      interaction.Notes,
      interaction.Date_prev,
      interaction.Heure
   from
      interaction,
      teneur,
      client
   where
      interaction.id_utilisateur = $Id_utilisateur and
      interaction.date_prev >= ".My_sql_format($Debut_semaine)." and
      interaction.date_prev < ".My_sql_format(decale_date($fin_semaine,1))." and
      interaction.id_teneur = teneur.id_teneur and
      interaction.id_client = client.id_client");
$nb_jour = 5;
$heure_min = "";
$heure_max = "";
$sans_heure = false;
while ($req->n()) {
   $heure = $req->f('Heure');
   if ($heure) {
      if (!$heure_min) $heure_min = $heure;
      if (!$heure_max) $heure_max = $heure;
      $heure_min = min ($heure_min,$heure);
      $heure_max = max ($heure_max,$heure);
   } else {
      $heure = 0;
      $sans_heure = true;
   }
   $jour = date("w",strtotime($req->f('Date_prev')));
   if ($jour==0) $jour = 7;
   $nb_jour = max ($jour,$nb_jour);
   $compteur[$jour][$heure]++;
   $item[$jour][$heure][($compteur[$jour][$heure])]['media'] = $req->f('lb_teneur');
   $item[$jour][$heure][($compteur[$jour][$heure])]['client'] = substr($req->f('raison_sociale'),0,20);
   $item[$jour][$heure][($compteur[$jour][$heure])]['id'] = $req->f('id_interaction');
   if ($req->f('notes')) {
      $color = "CCFFCC";
   } else {
      if ($req->f('Date_prev') < aujourdhui()) {
         $color = "FFCCCC";
      } else {
         $color = "CCCCFF";
      }
   }
   $item[$jour][$heure][($compteur[$jour][$heure])]['color'] = $color;
}
?>
   <table class="resultat">
      <tr>
<?
   if ($heure_max) {
?>
         <td class="resultat_tittle" width = 2%>H</td>
<?
   }
   for ($jour = 1; $jour <= $nb_jour; $jour++) {
?>
         <td class="resultat_tittle" width = 15%><? echo $jour_de_la_semaine[$jour] ?></td>
<?
   }
?>
      </tr>
<?
for ($heure = 0; $heure <= $heure_max; $heure++) {
if (  (($heure == 0) and ($sans_heure)) or ($heure_min <= $heure and $heure <= $heure_max)  ) {
   $z++;
?>
      <tr>
<?
   if ($heure_max) {
?>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>" valign = "Top">
            <? if ($heure) echo $heure.'h'; ?>
         </td>
<?
   }
   for ($jour = 1; $jour <= $nb_jour; $jour++) {
?>
         <td class="resultat_list" bgcolor="<? echo alternat($z) ?>" valign = "Top">
<?
      $table = $item[$jour][$heure];
      if (is_array($table)) {
         reset($table);
         while (list($i,$evt) = each($table)) {
?>
            <table><tr><td class="resultat_list" bgcolor="<? echo $evt['color'] ?>">
               <A class='resultat' href = "interaction_detail.php?Id_interaction=<? echo $evt['id'] ?>"><? echo $evt['media']; ?></A><BR><? echo $evt['client'] ?><BR>
            </td></tr></table>
<?
         }
      }
?>
         </td>
<?
   }
?>
      </tr>
<?
}
}
?>
   </table>
</td></tr></table>
<?
include ("ress/enpied.php");
?>
