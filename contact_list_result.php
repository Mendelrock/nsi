<?
include("ress/entete.php");

$_SESSION[contact_liste_raison_sociale] = $Raison_sociale;
$_SESSION[contact_liste_ville]          = $Ville;
$_SESSION[contact_liste_cp]             = $Cp;
$_SESSION[contact_liste_id_civilite]    = $Id_civilite;
$_SESSION[contact_liste_nom]            = $Nom;
$_SESSION[contact_liste_prenom]         = $Prenom;
$_SESSION[contact_liste_telephone]      = $Telephone;
$_SESSION[contact_liste_mail]           = $Mail;
$_SESSION[contact_liste_id_decision]    = $Id_decision;
$_SESSION[contact_liste_id_fonction]    = $Id_fonction;
$_SESSION[contact_liste_id_utilisateur] = $Id_utilisateur;
$_SESSION[contact_liste_id_marquage]     = $Id_marquage;
$_SESSION[contact_liste_go]              = 1;

$Raison_sociale = addslashes($Raison_sociale);
$Ville          = addslashes($Ville);
$Nom            = addslashes($Nom);
$Prenom         = addslashes($Prenom);
$Telephone      = addslashes($Telephone);
$Mail           = addslashes($Mail);
$Cp             = addslashes($Cp);

if($Id_utilisateur){
   $tables  .=", portefeuille ";
   $clauses .=" client.Id_client = portefeuille.Id_client AND ";
   $clauses .=" portefeuille.Id_utilisateur = '$Id_utilisateur' AND ";
}

if($Id_type){
      $clauses .=" client.Id_type = $Id_type AND ";
}

if($Id_civilite){
   $clauses .="contact.id_civilite = $Id_civilite AND ";
}

if($Id_decision){
   $clauses .="contact.Decideur = '$Id_decision' AND ";
}

if($Id_fonction){
   $clauses .="contact.Id_fonction = $Id_fonction AND ";
}

if($Nom){
   $clauses .="contact.Nom LIKE '$Nom%' AND ";
}

if($Prenom){
   $clauses .="contact.Prenom LIKE '$Prenom%' AND ";
}

if($Telephone){
   $clauses .="contact.Telephone LIKE '$Telephone%' AND ";
}

if($Mail){
   $clauses .="contact.Mail LIKE '$Mail%' AND ";
}

if($Raison_sociale){
   $clauses .="client.Raison_Sociale LIKE '$Raison_sociale%' AND ";
}

if($Cp){
   $clauses .="client.Cp LIKE '$Cp%' AND ";
}

if($Ville){
   $clauses .="client.Ville LIKE '$Ville%' AND ";
}

$requete = "
   SELECT Distinct
      client.Id_client,
      contact.Id_contact,
      client.Raison_sociale,
      civilite.lb_civilite,
      contact.Nom,
      contact.Prenom,
      fonction.Lb_fonction,
      `type`.lb_type,
      contact.telephone,
      contact.mobile,
      contact.mail
   FROM
      client
      left outer join `type` on (client.Id_type = `type`.Id_type)      
      $tables
      ,contact
      left outer join civilite on (contact.Id_civilite = civilite.Id_civilite)
      left outer join fonction on (contact.Id_fonction = fonction.Id_fonction)
    WHERE
      $clauses
      client.Id_client = contact.Id_client and
      contact.Fg_supp is null   
    LIMIT 0, ".($Affichage+2);

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des en_têtes
?>
<body class="application">
<form target=droite method="post" name ="liste" action="liste_detail_add.php">
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[contact_liste_id_utilisateur]) {
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
	$_SESSION[prochain_utilisateur_pour_affectation_liste] = $_SESSION[contact_liste_id_utilisateur];
}
?>
   <table class="resultat">
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[contact_liste_id_utilisateur]) {
?>
			<td class="resultat_list"><input type ="checkbox" name ="client?>" value="1" id = "sel"></td>
<?
}
?>
         <td class="resultat_tittle">Raison sociale</td>
         <td class="resultat_tittle">Civilité</td>
         <td class="resultat_tittle">Nom</td>
         <td class="resultat_tittle">Prénom</td>
         <td class="resultat_tittle">Fonction</td>
         <td class="resultat_tittle">Cible</td>
         <td class="resultat_tittle">Téléphone</td>
         <td class="resultat_tittle">Mobile</td>
         <td class="resultat_tittle">Mail</td>
      </tr>
<?
// Boucle de lecture
while ( $resultat->n() AND $z<$Affichage ) {
   /* Demande Laurent Tissot à supprimer */
   if ( $resultat->f('id_client') > 20809 ) {
      $b_in  = '<B>';
      $b_out = '</B>';
   } else {
      $b_in  = '';
      $b_out = '';
   }
?>
      <tr>
<?
if (droit_utilisateur("com") and (!droit_utilisateur("secto")) and $_SESSION[contact_liste_id_utilisateur]) {
?>
			<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><input type ="checkbox" name ="client_<? echo $resultat->f('id_client') ?>" value="1" class = "sel"></td>
<?
}
?>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('Raison_sociale'),0,14).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('lb_civilite'),0,5).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='contact_detail.php?Id_contact=<? echo $resultat->f('id_contact') ?>' target='droite'><? echo $b_in.substr($resultat->f('Nom'),0,30).$b_out ?></a></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('Prenom'),0,30).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('Lb_fonction'),0,30).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('lb_type'),0,30).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' nowrap><? echo $b_in.substr($resultat->f('telephone'),0,20).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' nowrap><? echo $b_in.substr($resultat->f('mobile'),0,20).$b_out ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $b_in.substr($resultat->f('mail'),0,30).$b_out ?></td>
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
         <td class='resultat_footer' colspan='7'>Cliquer sur le nom pour ouvrir un interlocuteur</td>
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
