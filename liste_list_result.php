<?
include("ress/entete.php");

$_SESSION[liste_list_date]               = $liste_list_date;
$_SESSION[liste_list_lb_liste]      	  = $liste_list_lb_liste;
$_SESSION[liste_list_id_utilisateur]     = $liste_list_id_utilisateur;

$liste_list_lb_liste = addslashes($liste_list_lb_liste);

if ($liste_list_date){
   $clauses .=" liste.date_debut <= '".$liste_list_date."' and liste.date_fin >= '".$liste_list_date."' and ";
} 

if ($liste_list_lb_liste){
   $clauses .=" liste.lb_liste like '%".$liste_list_lb_liste."%' AND";
} 

if($liste_list_id_utilisateur){
   $clauses .=" liste.Id_utilisateur = $liste_list_id_utilisateur AND ";
}

$requete = "
   SELECT 
      liste.id_liste,
      liste.lb_liste,
      liste.date_debut,
      liste.date_fin,
      utilisateur.nom,
      count(1) as nb,
      max(id_client) as max
   FROM
      liste
      left outer join liste_client on (liste.id_liste = liste_client.id_liste)
      left outer join utilisateur on (utilisateur.id_utilisateur = liste.id_utilisateur)
    WHERE
      $clauses
      1=1
    GROUP BY
      liste.id_liste,
      liste.lb_liste,
      liste.date_debut,
      liste.date_fin,
      utilisateur.nom
    LIMIT 0, ".($Affichage+2);

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des en_tˆtes
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Liste</td>
         <td class="resultat_tittle">Commercial</td>
         <td class="resultat_tittle">Début</td>
         <td class="resultat_tittle">Fin</td>
         <td class="resultat_tittle">Nb Clients</td>
      </tr>
<?
// Boucle de lecture
while ( $resultat->n() AND $z<$Affichage ) {
	$nb = $resultat->f('nb');
	if (($nb == 1) and !$resultat->f('max')) $nb=0;	
?>
      <tr>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='liste_detail.php?id_liste=<? echo $resultat->f('id_liste') ?>' target='droite'><? echo $b_in.substr($resultat->f('lb_liste'),0,30).$b_out ?></a></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('nom'),0,60).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('date_debut'),0,20).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('date_fin'),0,20).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($nb,0,20).$b_out ?></td>
      </tr>
<?
$z++;
}
if ($z) {
   $suiv_text="Fin de liste";
   if($resultat->n()) {
      $suiv_text="Liste non terminée";
   }
?>
      <tr>
         <td class='resultat_footer' colspan='3'>Cliquer sur le nom de la liste pour l'ouvrir</td>
         <td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'><? echo $suiv_text ?></span></td>
      </tr>
   </table>
<?
}
/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
