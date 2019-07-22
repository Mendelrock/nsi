<?
include("ress/entete.php");

recupere($Id_type);

$Raison_sociale = addslashes($Raison_sociale);
$Ville          = addslashes($Ville);
$Code_naf       = addslashes($Code_naf);

if($Id_utilisateur){
   $clauses .=" portefeuille.Id_utilisateur = '$Id_utilisateur' AND ";
}

if($Id_marquage){
   reset ($Id_marquage);
   $liste = -1;
   while (list($i,$j) = each($Id_marquage)) {
      if ($j) $liste .= ",".$j;
   }
   if ($liste <> "-1") {
      $tables  .=", client_marquage ";
      $clauses .=" client_marquage.Id_marquage in (".$liste.") AND";
      $clauses .=" client_marquage.Id_client = client.Id_client AND ";
   }
}

if($Id_gamme){
   $tables  .=", parc ";
   $clauses .="parc.id_client = client.id_client AND ";
   $clauses .="parc.id_gamme = $Id_gamme AND ";
}

if($Code_effectif){
   $clauses .="Code_effectif $Code_effectif_o '$Code_effectif' AND ";
}

if($Siret){
   $clauses .="Siret LIKE '$Siret%' AND ";
}

if($Raison_sociale){
   $clauses .="Raison_Sociale LIKE '$Raison_sociale%' AND ";
}

if($Code_naf){
   $clauses .="Code_naf LIKE '$Code_naf%' AND ";
}

if($Cp){
   $clauses .="Cp LIKE '$Cp%' AND ";
}

if($Ville){
   $clauses .="Ville LIKE '$Ville%' AND ";
}

if($Telephone){
   $clauses .="Telephone LIKE '$Telephone%' AND ";
}

if($Prio<>""){
   $clauses .="Prio = '$Prio' AND ";
}

if($Id_type){
   $clauses .="client.Id_type ".stripslashes($Id_type)." AND ";
}

$requete_primaire = "
   FROM
      client
      left outer join prio on (prio.id_prio = client.prio)
      left outer join type on (type.Id_type = client.Id_type)
      , portefeuille
      , utilisateur
      $tables
    WHERE
      $clauses
      client.Id_client = portefeuille.Id_client AND 
      portefeuille.Id_utilisateur = utilisateur.Id_utilisateur";

$requete = "
   SELECT 
      count(distinct client.id_client) as compte,
      utilisateur.Nom
   $requete_primaire
   GROUP BY 
      utilisateur.Nom";

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des en_tˆtes
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Commercial</td>
         <td class="resultat_tittle">Nombre de clients</td>
      </tr>
<?
// Boucle de lecture
while ( $resultat->n() ) {
?>
      <tr>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo substr($resultat->f('Nom'),0,20) ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo substr($resultat->f('compte'),0,20) ?></td>
      </tr>
<?
$total += $resultat->f('compte');
$z++;
}
?>
      <tr>
         <td class='resultat_footer' colspan='2'>Fin de la liste (<? echo $total; ?> clients)</td>
      </tr>
   </table>
<?
if ($go and $Id_utilisateur_affect) {

   $requete = "
   SELECT distinct 
      client.id_client 
   $requete_primaire";
   $resultat = new db_sql($requete);
   while ( $resultat->n() ) {
      $liste_a_reaffecter[] = $resultat->f("id_client");
   }
   if (is_array($liste_a_reaffecter)) {
      foreach ($liste_a_reaffecter as $id_client) {
         new db_sql("delete from portefeuille where id_client = $id_client");
         new db_sql("insert into portefeuille (id_client, id_utilisateur) values($id_client, $Id_utilisateur_affect)");
         $n++;
      }
      new db_sql("update affaire set id_utilisateur = (select id_utilisateur from portefeuille where portefeuille.id_client = affaire.id_client) where id_utilisateur <> 12");
      new db_sql("update interaction set id_utilisateur = (select id_utilisateur from portefeuille where portefeuille.id_client = interaction .id_client) where id_utilisateur <> 12");
      echo " $n clients réaffectés !";
      echo"<script language = javascript>
         parent.document.forms[0].go.click();
      </script>"; 
   }
}

/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
