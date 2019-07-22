<?
include("ress/entete.php");

$_SESSION[client_liste_groupe]           = $Groupe;
$_SESSION[client_liste_id_statut]        = $Id_statut;
$_SESSION[client_liste_id_utilisateur]   = $Id_utilisateur;
$_SESSION[client_liste_prio]             = $Prio;
$_SESSION[client_liste_raison_sociale]   = $Raison_sociale;
$_SESSION[client_liste_siret]            = $Siret;
$_SESSION[client_liste_code_naf]         = $Code_naf;
$_SESSION[client_liste_ville]            = $Ville;
$_SESSION[client_liste_telephone]        = $Telephone;
$_SESSION[client_liste_cp]               = $Cp;
$_SESSION[client_liste_code_effectif]    = $Code_effectif;
$_SESSION[client_liste_code_effectif_o]  = $Code_effectif_o;
$_SESSION[client_liste_id_gamme]         = $Id_gamme;
$_SESSION[client_liste_id_marquage]      = $Id_marquage;
$_SESSION[client_liste_date_prem_sign_d] = $_POST[client_liste_date_prem_sign_d];
$_SESSION[client_liste_date_prem_sign_f] = $_POST[client_liste_date_prem_sign_f];
recupere($Id_type);
$_SESSION[client_liste_id_type]           = stripslashes($Id_type);
$_SESSION[client_liste_go]                = 1;

$Raison_sociale = addslashes($Raison_sociale);
$Ville          = addslashes($Ville);
$Code_naf       = addslashes($Code_naf);

if ($_SESSION[client_liste_date_prem_sign_d] or $_SESSION[client_liste_date_prem_sign_f]){
   $tables  .=", (select Id_client, min(date_prev) as date from affaire where id_statut = 2 group by Id_client) affaire ";
   $clauses .=" client.Id_Client = affaire.Id_client AND ";
} 

if ($_SESSION[client_liste_date_prem_sign_d]){
   $clauses .= "'".$_SESSION[client_liste_date_prem_sign_d]."' <= affaire.date AND ";
} 

if ($_SESSION[client_liste_date_prem_sign_f]){
   $clauses .= "'".$_SESSION[client_liste_date_prem_sign_f]."' >= affaire.date AND ";
} 

if($Id_utilisateur){
   $clauses .=" portefeuille.Id_utilisateur = '$Id_utilisateur' AND ";
}
if($Id_statut){
   $clauses .=" client.Tva_intra = $Id_statut AND ";
}
if($Groupe){
   $clauses .="Groupe LIKE '$Groupe%' AND ";
}

if($Id_marquage){
   /*reset ($Id_marquage);
   $liste = -1;
   while (list($i,$j) = each($Id_marquage)) {
      if ($j) $liste .= ",".$j;
   }
   if ($liste <> "-1") {
      $tables  .=", client_marquage ";
      $clauses .=" client_marquage.Id_marquage in (".$liste.") AND";
      $clauses .=" client_marquage.Id_client = client.Id_client AND ";
   }*/
   $tables  .=", client_marquage ";
   $clauses .=" client_marquage.Id_marquage = $Id_marquage AND";
   $clauses .=" client_marquage.Id_client = client.Id_client AND ";
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

$requete = "
   SELECT Distinct
      client.Id_client,
      client.Siret,
      client.Raison_sociale,
      client.Code_naf,
      client.Cp,
      client.Ville,
      client.Telephone,
      prio.Lb_prio,
      client.Id_type,
      utilisateur.Nom,
      type.Lb_type
   FROM
      client
      left outer join prio on (prio.id_prio = client.prio)
      left outer join type on (type.Id_type = client.Id_type)
      left outer join portefeuille on (client.Id_client = portefeuille.Id_client)
      left outer join utilisateur on (portefeuille.Id_utilisateur = utilisateur.Id_utilisateur)
      $tables
    WHERE
      $clauses
      1=1
    
    LIMIT 0, ".($Affichage+2);

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des entetes
?>
<body class="application">
<form target=droite method="post" name ="liste" action="liste_detail_add.php">
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[client_liste_id_utilisateur]) {
	?>
	<input style = "
	background-color: rgb(0, 102, 153);
	border-color: rgb(192, 192, 192);
	border-style: solid;
	border-width: 2px;
	color: rgb(0, 0, 102);
	font-family: verdana;
	font-size: 10px;
	font-style: normal;
	font-variant: normal;
	color: white;
	font-weight: bold;
	height: 18px;
	text-align: center;
	vertical-align: middle;
	white-space: normal;" type="submit" value="Assigner les clients sélectionnés à une DAM">
	<?
	$_SESSION[prochain_utilisateur_pour_affectation_liste] = $_SESSION[client_liste_id_utilisateur];
}
?>

   <table class="resultat">
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[client_liste_id_utilisateur]) {
?>
			<td class="resultat_list"><input type ="checkbox" name ="client?>" value="1" id = "sel"></td>
<?
}
?>
         <td class="resultat_tittle">Raison Sociale</td>
         <td class="resultat_tittle"><? echo $_SESSION[champ_parametrable][client][Prio][libelle] ?></td>
         <td class="resultat_tittle">Cible</td>
         <td class="resultat_tittle">CP</td>
         <td class="resultat_tittle">Ville</td>
         <td class="resultat_tittle">Téléphone</td>
         <td class="resultat_tittle">Commercial</td>
      </tr>
<?
// Boucle de lecture
while ( $resultat->n() AND $z<$Affichage ) {
?>
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[client_liste_id_utilisateur]) {
?>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><input type ="checkbox" name ="client_<? echo $resultat->f('id_client') ?>" value="1" class = "sel"></td>
<?
}
?>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='client_detail.php?Id_client=<? echo $resultat->f('id_client') ?>' target='droite'><? echo $b_in.substr($resultat->f('raison_sociale'),0,30).$b_out ?></a></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('lb_prio'),0,30).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('lb_type'),0,20).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('cp'),0,5).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('ville'),0,20).$b_out ?></td>
 			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>" nowrap><? echo $b_in.substr($resultat->f('telephone'),0,20).$b_out ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $b_in.substr($resultat->f('nom'),0,20).$b_out ?></td>
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
         <td class='resultat_footer' colspan='5'>Cliquer sur la Raison Sociale pour ouvrir un client</td>
         <td class='resultat_footer' align='center' colspan='3'><span style='font-weight:bold'><? echo $suiv_text ?></span></td>
      </tr>
   </table>
<script>
	$('#sel').change(function() {
		$('.sel').prop("checked", $('#sel').prop("checked") );
	})
</script>
<form>
<?
}
/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
