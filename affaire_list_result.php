<?
include("ress/entete.php");
if ($ACTE==1) {
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Date_crea
/*  Date_prev
/*  Statut
/*  Affichage
/*  Id_utilisateur
/*
/* Internes
/*  $Id_affaire
/*  $Id_client
/*  $Date_crea
/*  $Date_prev
/*  $Raison_sociale
/*  $Statut
/*  $Prix (champ calculé)
/*
/*------------------------------------------------*/

$_SESSION[affaire_liste_id_affaire]      = $Id_affaire;
$_SESSION[affaire_liste_id_transac]      = $Id_transac;
$_SESSION[affaire_liste_id_utilisateur]  = $Id_utilisateur;
$_SESSION[affaire_liste_id_secteur]      = $Id_secteur;
$_SESSION[affaire_liste_date_crea_d]     = $Date_crea_d;
$_SESSION[affaire_liste_date_crea_f]     = $Date_crea_f;
$_SESSION[affaire_liste_date_prev_d]     = $Date_prev_d;
$_SESSION[affaire_liste_date_prev_f]     = $Date_prev_f;
$_SESSION[affaire_liste_id_statut]       = $Id_statut;
$_SESSION[affaire_liste_id_marquage]     = $Id_marquage;
$_SESSION[affaire_liste_id_statut_adv]   = $Id_statut_adv;
$_SESSION[affaire_liste_id_type]         = $Id_type;
$_SESSION[affaire_liste_interlocuteur]   = $Internlocuteur;
$_SESSION[affaire_liste_commentaire]     = $Commentaire;
$_SESSION[affaire_liste_raison_sociale]  = $Raison_sociale;
$_SESSION[affaire_liste_pnne]  = $pnne;
$_SESSION[affaire_statut_fdc]            = $_POST['id_statut_fdc'];
$_SESSION[affaire_liste_go]              = 1;

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales


// Critere de recherche
if($Id_affaire){
   $requete.="affaire.Id_affaire = '$Id_affaire' AND ";
}

if($Pnne){
   $requete.=" (affaire.Pnne is null  or affaire.Pnne = 0) and ";
}

if($Id_marquage){
   reset ($Id_marquage);
   $liste = -1;
   while (list($i,$j) = each($Id_marquage)) {
      if ($j) $liste .= ",".$j;
   }
   if ($liste <> "-1") {
      $table  .=", client_marquage ";
      $requete .=" client_marquage.Id_marquage in (".$liste.") AND ";
      $requete .=" client_marquage.Id_client = affaire.Id_client AND ";
   }
}

if($Date_crea_d){
   $requete.="affaire.Date_crea >= '$Date_crea_d' AND ";
}
if($Date_crea_f){
   $requete.="affaire.Date_crea <= '$Date_crea_f' AND ";
}
if($Date_prev_d){
   $requete.="affaire.Date_prev >= '$Date_prev_d' AND ";
}
if($Date_prev_f){
   $requete.="affaire.Date_prev <= '$Date_prev_f' AND ";
}
if($Id_transac){
   $requete.=" affaire.Id_transac like '".$Id_transac."%' AND ";
}
if($Id_statut){
   $requete.=" affaire.Id_statut = $Id_statut AND ";
}
if($Id_statut_adv){
   $requete.=" affaire.Id_statut_adv = $Id_statut_adv AND ";
}
if($Id_utilisateur){
   $requete.=" affaire.Id_utilisateur = $Id_utilisateur AND ";
}
if($Id_secteur){
   $requete.=" portefeuille.Id_utilisateur = $Id_secteur AND ";
   $requete.=" portefeuille.Id_client = affaire.id_client AND ";
   $table  .=" ,portefeuille ";
}

if($Commentaire){
   $requete.="affaire.commentaire like '%".$Commentaire."%' AND ";
}

if($Id_type){
   $requete.="client.Id_type = '$Id_type' AND ";
}

if($Interlocuteur){
   $requete .= " contact.Id_client = client.Id_client and contact.Nom like '%".$Interlocuteur."%' AND ";
}

if($Raison_sociale){
    $requete .= " client.Raison_sociale like '%".trim($Raison_sociale)."%' AND ";
}

$hasBreak = 0;
if($_SESSION[affaire_statut_fdc]){
		$crit_fdc = array();
		foreach($_SESSION[affaire_statut_fdc] as $val) {
			if (!$val) {
				$rien = 1;
			} else if ($val == 'Aucune') {
				$crit_fdc[] = " not exists (
					select 1 
					from 
						zdataset_fdcentete dt, 
						doc  
					where 
						doc.type_doc = 'fdc' and 
						doc.id_dataset_entete = dt.id_dataset and
						dt.affaire = affaire.Id_affaire)";
			} else {
				$crit_fdc[] = " exists (
					select 1 
					from 
						zdataset_fdcentete dt, 
						doc  
					where 
						doc.type_doc = 'fdc' and 
						doc.id_dataset_entete = dt.id_dataset and
						dt.affaire = affaire.Id_affaire and
						dt.statut = '".$val."')";
			}
		}
		if (count($crit_fdc)) {
			$requete .= " (".implode(' or ', $crit_fdc).") and ";
		}
}

$req="
   SELECT
      affaire.Id_affaire,
      affaire.Id_statut,
      affaire.Id_transac,
      affaire.Id_client,
      affaire.Commentaire,
      utilisateur.Nom as nom_consultant,
      affaire.Prix,
      affaire.Prix_adv,
      DATE_FORMAT(affaire.Date_crea,'%d/%m/%y') as Date_crea,
      DATE_FORMAT(affaire.Date_prev,'%d/%m/%y') as Date_prev,
      contact.Nom,
      contact.Telephone,
      client.Raison_sociale,
      client.Id_type,
      statut.Lb_statut
   FROM
      utilisateur,
      statut,
      contact,
      affaire $join,
      client $table
   WHERE $requete
      utilisateur.Id_utilisateur = affaire.id_utilisateur AND
      affaire.Id_statut=statut.Id_statut AND
      affaire.Id_contact=contact.Id_contact AND
      affaire.Id_client=client.Id_client 
   ORDER BY
      affaire.Date_prev DESC
   LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

$_SESSION[affaire_requete]  = $req;

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
<form target=droite method="post" name ="liste" action="liste_detail_add.php">
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[affaire_liste_id_secteur]) {
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
	$_SESSION[prochain_utilisateur_pour_affectation_liste] = $_SESSION[affaire_liste_id_secteur];
}
?>
   <table class="resultat">
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[affaire_liste_id_secteur]) {
?>
			<td class="resultat_list"><input type ="checkbox" name ="client?>" value="1" id = "sel"></td>
<?
}
?>
         <td class="resultat_tittle">Nom affaire</td>
         <td class="resultat_tittle">N° de devis</td>
         <td class="resultat_tittle">Raison sociale</td>
         <td class="resultat_tittle">Nom</td>
         <td class="resultat_tittle">Téléphone</td>
         <td class="resultat_tittle">Commercial</td>
         <td class="resultat_tittle">Date Création</td>
         <td class="resultat_tittle">Date Prévision</td>
         <td class="resultat_tittle">Statut</td>
         <td class="resultat_tittle">Montant HT</td>
      </tr>
<?
// Boucle de lecture
$total     = 0;
$total_adv = 0;
while($z<$Affichage AND $resultat->n() ){
   $total += $resultat->f('Prix');
   $total_adv += $resultat->f('Prix_adv');
   $z++
?>
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[affaire_liste_id_secteur]) {
?>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><input type ="checkbox" name ="client_<? echo $resultat->f('id_client') ?>" value="1" class = "sel"></td>
<?
}
?>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='affaire_detail.php?Id_affaire=<? echo $resultat->f('id_affaire') ?>' target='droite'><? echo $resultat->f('commentaire') ?></a></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Id_transac') ?></td>
		 <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo substr($resultat->f('raison_sociale'),0,30) ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo substr($resultat->f('nom'),0,30) ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' nowrap><? echo substr($resultat->f('telephone'),0,20) ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo substr($resultat->f('nom_consultant'),0,30) ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('date_crea') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('date_prev') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_statut') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' align = 'right'><? echo $resultat->f('Prix') ?></td>
      </tr>
<?
}
if($z){
   $total_e = explode('.',$total);
   $total = $total_e[0].'.'.substr($total_e[1].'00',0,2);
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='7'>Cliquer sur le nom pour ouvrir une affaire</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
   echo"<td class='resultat_footer' align = 'right'><span style='font-weight:bold'>",$total,"</span></td>";
?>
</tr></table>
<?
}
?>
<script>
	$('#sel').change(function() {
		$('.sel').prop("checked", $('#sel').prop("checked") );
	})
</script>
<form>   
<?
}
include ("ress/enpied.php");
?>
