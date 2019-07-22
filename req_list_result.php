<?
/* Liste des critères

Client_code_effectif
Client_code_effectif_o
Client_code_naf
Client_cp
Client_id_gamme
Client_id_utilisateur
Client_prio
Client_raison_sociale
Client_siret
Client_telephone
Client_ville
Client_prio
Client_statut

Contact_id_decision
Contact_id_fonction

Commercial_id_utilisateur

Interaction_date_crea_d
Interaction_date_crea_f
Interaction_date_prev_d
Interaction_date_prev_f
Interaction_id_media
Interaction_id_teneur
Interaction_id_utilisateur
Interaction_notes

Affaire_date_crea_d
Affaire_date_crea_f
Affaire_date_prev_d
Affaire_date_prev_f
Affaire_id_statut
Affaire_id_utilisateur
Affaire_id_transac

Affaire_detail_gamme
Affaire_detail_produit


*/

/* Liste des tables à afficher

Client_affiche

Contact_affiche

Interaction_affiche

Affaire_affiche

Affaire_detail_affiche

*/

/*-------- Variables de session -------*/
include ("ress/var_session.php");
include ("ress/util.php");
include ("ress/db_mysql.php");
include ("ress/register_globals.php");
register_globals('gp');

set_time_limit(0);

//set_time_limit (0);
//$secto = droit_utilisateur("secto");

/* initailisation des tables présentes */
$tables               = "";
$table_client         = $Client_affiche;
$table_contact        = $Contact_affiche;
$table_commercial     = $Commercial_affiche;
$table_interaction    = $Interaction_affiche;
$table_affaire        = $Affaire_affiche;
//$table_affaire_detail = $Affaire_detail_affiche;

/* Cas : Pas de table séclectionnée en visu */
if ($table_client +
    $table_contact +
    $table_interaction +
	$table_commercial +
    $table_affaire +
    $table_affaire_detail == 0) {
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   </head>
   <body>
      Vous devez cocher au moins une entité en visualisation (coches devant les titres d'entité).
   </body>
</html>
<?
   exit();
}

/* Critères Client */

$Client_raison_sociale = addslashes($Client_raison_sociale);
$Client_ville          = addslashes($Client_ville);
$Client_code_naf       = addslashes($Client_code_naf);

/*
if($Client_id_gamme){
   $table_client = 1;
   $tables  .=" parc,";
   $clauses .="parc.id_client = client.id_client AND ";
   $clauses .="parc.id_gamme = $Client_id_gamme AND ";
}*/

if($Client_code_effectif){
   $table_client = 1;
   $clauses .="client.Code_effectif $Client_code_effectif_o '$Client_code_effectif' AND ";
}
if($Client_siret){
   $table_client = 1;
   $clauses .="client.Siret LIKE '$Client_siret%' AND ";
}
if($Client_raison_sociale){
   $table_client = 1;
   $clauses .="client.Raison_Sociale LIKE '$Client_raison_sociale%' AND ";
}
if($Client_code_naf){
   $table_client = 1;
   $clauses .="client.Code_naf LIKE '$Client_code_naf%' AND ";
}
if($Client_cp){
   $table_client = 1;
   $clauses .="client.Cp LIKE '$Client_cp%' AND ";
}
if($Client_ville){
   $table_client = 1;
   $clauses .="client.Ville LIKE '$Client_ville%' AND ";
}
if($Client_telephone){
   $table_client = 1;
   $clauses .="client.Telephone LIKE '$Client_telephone%' AND ";
}
if($Client_prio<>""){
   $table_client = 1;
   $clauses .="client.Prio = '$Client_prio' AND ";
}
if($Client_id_type){
   $table_client = 1;
   $clauses .="client.id_type ".stripslashes($Client_id_type)." AND ";
}
//if($Client_id_utilisateur and ((!($secto)) or $table_client)) {
if($Client_id_utilisateur) {
   $table_client = 1;
   $tables  .=" portefeuille ,";
   $clauses .=" client.Id_client = portefeuille.Id_client AND ";
   $clauses .=" portefeuille.Id_utilisateur = '$Client_id_utilisateur' AND ";
}
if($Client_id_marquage){
   reset ($Client_id_marquage);
   $liste = -1;
   while (list($i,$j) = each($Client_id_marquage)) {
      if ($j) $liste .= ",".$j;
   }
   if ($liste <> "-1") {
      $table_client = 1;
      $tables  .=" client_marquage ,";
      $clauses .=" client_marquage.Id_marquage in (".$liste.") AND";
      $clauses .=" client_marquage.Id_client = client.Id_client AND ";
   }
}
if($Client_statut){
   $table_client = 1;
   $clauses .="client.tva_intra = ".stripslashes($Client_statut)." AND ";
}



/* Critères Contact */

if($Contact_id_decision){
   $table_contact = 1;
   $clauses .="contact.id_decision = $Contact_id_decision AND ";
}
if($Contact_id_fonction<>""){
   $table_contact = 1;
   $clauses .="contact.id_fonction = $Contact_id_fonction AND ";
}

/* Critères Commercial */

if($Commercial_id_utilisateur) {
   $table_commercial = 1;
   $clauses .="portefeuille.Id_utilisateur = '$Commercial_id_utilisateur' AND ";
}

/* Critères Interaction */

if($Interaction_date_crea_d){
   $table_interaction = 1;
   $clauses .="interaction.Date_crea >= '$Interaction_date_crea_d' AND ";
}
if($Interaction_date_crea_f){
   $table_interaction = 1;
   $clauses .="interaction.Date_crea <= '$Interaction_date_crea_f' AND ";
}
if($Interaction_date_prev_d){
   $table_interaction = 1;
   $clauses .="interaction.Date_prev >= '$Interaction_date_prev_d' AND ";
}
if($Interaction_date_prev_f){
   $table_interaction = 1;
   $clauses .="interaction.Date_prev <= '$Interaction_date_prev_f' AND ";
}
if(!empty($Interaction_notes)){
   $table_interaction = 1;
   $clauses .="interaction.Notes is $Interaction_notes AND ";
}
/*
if($Interaction_id_media){
   $table_interaction = 1;
   $clauses .="interaction.Id_media = '$Interaction_id_media' AND ";
}
*/
if($Interaction_id_teneur){
   $table_interaction = 1;
   $clauses .="interaction.Id_teneur = '$Interaction_id_teneur' AND ";
}
//if($Interaction_id_utilisateur and ((!($secto)) or $table_interaction)){
if($Interaction_id_utilisateur){
   $table_interaction = 1;
   $clauses .="interaction.Id_utilisateur = '$Interaction_id_utilisateur' AND ";
}

/* Critères Affaire */

if($Affaire_date_crea_d){
   $table_affaire = 1;
   $clauses .= "affaire.Date_crea >= '$Affaire_date_crea_d' AND ";
}
if($Affaire_date_crea_f){
   $table_affaire = 1;
   $clauses .= "affaire.Date_crea <= '$Affaire_date_crea_f' AND ";
}
if($Affaire_date_prev_d){
   $table_affaire = 1;
   $clauses .= "affaire.Date_prev >= '$Affaire_date_prev_d' AND ";
}
if($Affaire_date_prev_f){
   $table_affaire = 1;
   $clauses .= "affaire.Date_prev <= '$Affaire_date_prev_f' AND ";
}
if($Affaire_id_statut){
   $table_affaire = 1;
   $clauses .= "affaire.Id_statut = $Affaire_id_statut AND ";
}
if($Affaire_id_transac){
   $table_affaire = 1;
   $clauses .= "affaire.Id_transac like '%".$Affaire_id_transac."%' AND ";
}
if($Affaire_id_produit){
   $table_affaire = 1;
   $clauses .= "exists (select 1 from affaire_detail where affaire_detail.id_affaire = affaire.id_affaire and affaire_detail.id_produit = $Affaire_id_produit) AND ";
}


//if(($Affaire_id_utilisateur) and ((!($secto)) or $table_affaire or $table_affaire_detail)){
if($Affaire_id_utilisateur){
   $table_affaire = 1;
   $clauses .= "affaire.Id_utilisateur = '$Affaire_id_utilisateur' AND ";
}
/*
if($Affaire_id_statut_adv){
   $table_affaire = 1;
   $clauses .= " affaire.Id_statut_adv = '$Affaire_id_statut_adv' AND ";
}
*/

/* Critères Affaire_detail */

/*
if($Affaire_detail_id_gamme){
   $table_affaire_detail = 1;
   $clauses .= "produit.id_produit_pere = $Affaire_detail_id_gamme AND ";
}
if($Affaire_detail_id_produit){
   $table_affaire_detail = 1;
   $clauses .= "affaire_detail.id_produit = $Affaire_detail_id_produit AND ";
}
*/


   $entete_1 = array();
   $entete_2 = array();
   $champs_1 = array();
   $champs_2 = array();
   $inner_client = "";
   $inner_contact = "";
   $inner_interaction = "";
   $inner_affaire = "";

/* Champs Client */

if ($Client_affiche) {

   $entete_1[] = array("Client",13);

   $entete_2[] = "Siret";
   $entete_2[] = "Raison sociale";
   $entete_2[] = "Adresse 1";
   $entete_2[] = "Adresse 2";
   $entete_2[] = "Adresse 3";
   $entete_2[] = "Code Postal";
   $entete_2[] = "Ville";
   $entete_2[] = "Téléphone";
   $entete_2[] = "Fax";
   $entete_2[] = "Code Naf";
   $entete_2[] = "Activité";
   $entete_2[] = "Effectif";
   $entete_2[] = "Cible";

   $champs_1[] = "client.Siret as client_siret";
   $champs_1[] = "client.Raison_sociale";
   $champs_1[] = "client.Adresse1";
   $champs_1[] = "client.Adresse2";
   $champs_1[] = "client.Adresse3";
   $champs_1[] = "client.Cp";
   $champs_1[] = "client.Ville";
   $champs_1[] = "client.Telephone as client_telephone";
   $champs_1[] = "client.Fax as client_fax";
   $champs_1[] = "client.Code_naf";
   $champs_1[] = "prio.Lb_prio";
   $inner_client .= " left join prio on (client.Prio = prio.Id_prio) ";
   $champs_1[] = "code_effectif.lb_effectif";
   $inner_client .= " left join code_effectif on (client.code_effectif = code_effectif.code_effectif) ";
   $champs_1[] = "type.lb_type";
   $inner_client .= " left join type on (client.id_type = type.id_type) ";

   $champs_2[] = "client_siret";
   $champs_2[] = "Raison_sociale";
   $champs_2[] = "Adresse1";
   $champs_2[] = "Adresse2";
   $champs_2[] = "Adresse3";   
   $champs_2[] = "Cp";
   $champs_2[] = "Ville";
   $champs_2[] = "client_telephone";
   $champs_2[] = "client_fax";
   $champs_2[] = "Code_naf";
   $champs_2[] = "Lb_prio";
   $champs_2[] = "lb_effectif";
   $champs_2[] = "lb_type";

}

if ($Contact_affiche) {

   $entete_1[] = array("Contact",8);

   $entete_2[] = "Civilité";
   $entete_2[] = "Nom";
   $entete_2[] = "Prénom";
   $entete_2[] = "Téléphone";
   $entete_2[] = "Fax";
   $entete_2[] = "Mail";
   $entete_2[] = "Service";
   $entete_2[] = "Fonction";

   $champs_1[] = "contact.id_contact";
   $champs_1[] = "civilite.lb_civilite as contact_civilite";
   $inner_contact .= " left join civilite on (contact.id_civilite = civilite.id_civilite) ";
   $champs_1[] = "contact.Nom as contact_nom";
   $champs_1[] = "contact.Prenom as contact_prenom";
   $champs_1[] = "contact.Telephone as contact_telephone";
   $champs_1[] = "contact.Fax as contact_fax";
   $champs_1[] = "contact.Mail as contact_mail";
   $champs_1[] = "decision.lb_decision as contact_lb_decision";
   $inner_contact .= " left join decision on (contact.id_decision = decision.id_decision) ";
   $champs_1[] = "fonction.lb_fonction as contact_lb_fonction";
   $inner_contact .= " left join fonction on (contact.id_fonction = fonction.id_fonction) ";

   $champs_2[] = "contact_civilite";
   $champs_2[] = "contact_nom";
   $champs_2[] = "contact_prenom";
   $champs_2[] = "contact_telephone";
   $champs_2[] = "contact_fax";
   $champs_2[] = "contact_mail";
   $champs_2[] = "contact_lb_decision";
   $champs_2[] = "contact_lb_fonction";

}

if($Commercial_affiche) {
	$entete_1[] = array("Commercial",1);
	$entete_2[] = "Commercial";
	$champs_1[] = "utilisateur_portefeuille.Nom";
	$champs_2[] = "nom";
	$inner_commercial = " left join utilisateur as utilisateur_portefeuille on (portefeuille.Id_utilisateur = utilisateur_portefeuille.Id_utilisateur) ";

}

if ($Interaction_affiche) {


	$entete_1[] = array("Interaction",9);

   $entete_2[] = "Date de création";
   $entete_2[] = "Date d'action précédente";
   $entete_2[] = "Date de prévision/réalisation";
   $entete_2[] = "Date de prochaine action";
	$entete_2[] = "Date de saisie du CR";
   $entete_2[] = "Type";
//   $entete_2[] = "Média";
   $entete_2[] = "Compte-rendu";
   $entete_2[] = "Commentaire";
   $entete_2[] = "Commercial";

   $champs_1[] = "interaction.id_interaction";
   $champs_1[] = "DATE_FORMAT(interaction.Date_crea,'%Y/%m/%d à %k:%i') as Date_crea_interaction";
   $champs_1[] = "CONCAT (DATE_FORMAT(interaction1.Date_prev,'%Y/%m/%d'), ifnull(concat (' à ',interaction1.heure,'h'),' _h')) as Date_prec_interaction";
   $inner_interaction .= " left outer join interaction as interaction1 on (interaction1.Id_interaction = interaction.Id_interaction_pere)";
   $champs_1[] = "CONCAT (DATE_FORMAT(interaction.Date_prev,'%Y/%m/%d'),interaction.heure) as Date_prev_interaction";
   $champs_1[] = "CONCAT (DATE_FORMAT(interaction.Date_prev,'%Y/%m/%d'), ifnull(concat (' à ',interaction.heure,'h'),' _h')) as Date_prev_interaction";
   $inner_interaction .= " left outer join interaction as interaction2 on (interaction2.Id_interaction_pere = interaction.Id_interaction)";
   $champs_1[] = "CONCAT (DATE_FORMAT(interaction2.Date_prev,'%Y/%m/%d'), ifnull(concat (' à ',interaction2.heure,'h'),' _h')) as Date_proc_interaction";
	$champs_1[] = "DATE_FORMAT(interaction.Date_clot,'%Y/%m/%d à %k:%i') as Date_clot_interaction";
   $champs_1[] = "teneur.lb_teneur as interaction_lb_teneur";
   $inner_interaction .= " left join teneur on (interaction.id_teneur = teneur.id_teneur) ";
//   $champs_1[] = "media.lb_media as interaction_lb_media";
//   $clauses .= "interaction.id_media = media.id_media AND ";
//   $tables  .=" media,";
   $champs_1[] = "interaction.Commentaire as interaction_commentaire";
   $champs_1[] = "interaction.Notes as interaction_notes";
   $champs_1[] = "utilisateur_interaction.Nom as Nom_interaction";
   $inner_interaction .= " left join utilisateur as utilisateur_interaction on (interaction.id_utilisateur = utilisateur_interaction.id_utilisateur) ";

   $champs_2[] = "date_crea_interaction";
   $champs_2[] = "date_prec_interaction";
   $champs_2[] = "date_prev_interaction";
   $champs_2[] = "date_proc_interaction";
   $champs_2[] = "date_clot_interaction";
   $champs_2[] = "interaction_lb_teneur";
//   $champs_2[] = "interaction_lb_media";
   $champs_2[] = "interaction_notes";
   $champs_2[] = "interaction_commentaire";
   $champs_2[] = "Nom_interaction";

}

if ($Affaire_affiche) {

   $entete_2[] = "Numéro";
   $entete_2[] = "Date de création";
   $entete_2[] = "Date de prévision/clôture";
   $entete_2[] = "Montant";
   $entete_2[] = "Pose";
//   $entete_2[] = "Maintenance";
//   $entete_2[] = "Loyer";
   $entete_2[] = "N° Devis";
   $entete_2[] = "Titre";
//   $entete_2[] = "Durée";
   $entete_2[] = "Statut";
//   $entete_2[] = "Statut adv";
//   $entete_2[] = "Commentaire adv";
//   $entete_2[] = "Montant saisi";
   $entete_2[] = "Commercial";

   $entete_1[] = array("Affaire",count($entete_2));

   $champs_1[] = "affaire.id_affaire as affaire_id_affaire";
   $champs_1[] = "DATE_FORMAT(affaire.Date_crea,'%Y/%m/%d') as Date_crea_affaire";
   $champs_1[] = "DATE_FORMAT(affaire.Date_prev,'%Y/%m/%d') as Date_prev_affaire";
   $champs_1[] = "replace(affaire.prix,'.',',') as affaire_prix";
   $champs_1[] = "replace(affaire.pose,'.',',') as affaire_pose";
   $champs_1[] = "affaire.Id_transac as ndevis";
//   $champs_1[] = "affaire.Maintenance as affaire_maintenance";
//   $champs_1[] = "affaire.Loyer as affaire_loyer";
//   $champs_1[] = "transac.lb_transac as affaire_lb_transac";
//   $inner_affaire .= " left join transac on affaire.id_transac = transac.id_transac ";
   $champs_1[] = "affaire.commentaire as affaire_commentaire";
//   $champs_1[] = "duree.lb_duree as affaire_duree";
//   $inner  .= " left join duree on affaire.id_duree = duree.id_duree ";
   $champs_1[] = "statut.lb_statut as affaire_lb_statut";
   $inner_affaire .= " left join statut on (affaire.id_statut = statut.id_statut) ";
//   $champs_1[] = "statut_adv.lb_statut_adv as affaire_lb_statut_adv";
//   $clauses .= "affaire.id_statut_adv = statut_adv.id_statut_adv AND ";
//   $tables  .=" statut_adv,";
//   $champs_1[] = "replace(affaire.Prix_adv,'.',',') as affaire_prix_adv";
   $champs_1[] = "utilisateur_affaire.Nom as Nom_affaire";
   $inner_affaire .= " left join utilisateur as utilisateur_affaire on (affaire.id_utilisateur = utilisateur_affaire.id_utilisateur) ";

   $champs_2[] = "affaire_id_affaire";
   $champs_2[] = "Date_crea_affaire";
   $champs_2[] = "Date_prev_affaire";
   $champs_2[] = "affaire_prix";
   $champs_2[] = "affaire_pose";
   $champs_2[] = "ndevis";
//   $champs_2[] = "affaire_maintenance";
//   $champs_2[] = "affaire_loyer";
//   $champs_2[] = "affaire_lb_transac";
//   $champs_2[] = "affaire_lb_duree";
   $champs_2[] = "affaire_commentaire";
   $champs_2[] = "affaire_lb_statut";
//   $champs_2[] = "affaire_lb_statut_adv";
//   $champs_2[] = "affaire_prix_adv";
   $champs_2[] = "Nom_affaire";

}
/*
if ($Affaire_detail_affiche) {

   $entete_1[] = array("Contenu d'affaire",4);

   $entete_2[] = "Gamme";
   $entete_2[] = "Produit";
   $entete_2[] = "Prix";
   $entete_2[] = "Quantité";

   $champs_1[] = "affaire_detail.id_affaire_detail";
   $champs_1[] = "gamme.lb_produit as gamme_lb_gamme";
   $champs_1[] = "produit.lb_produit as produit_lb_produit";
   $champs_1[] = "replace(affaire_detail.prix,'.',',') as affaire_detail_prix";
   $champs_1[] = "affaire_detail.qt as affaire_detail_qt";

   $champs_2[] = "gamme_lb_gamme";
   $champs_2[] = "produit_lb_produit";
   $champs_2[] = "affaire_detail_prix";
   $champs_2[] = "affaire_detail_qt";

}
*/

/* Ajout des tables principales */
if ($table_client == 1) {
   $tables  .=" client $inner_client ,";
}
if ($table_contact == 1) {
   $tables  .=" contact $inner_contact ,";
}
if ($table_interaction == 1) {
   $tables  .=" interaction $inner_interaction ,";
}
if ($table_commercial == 1) {
	$tables .="portefeuille $inner_commercial ,";
}
/*
if ($table_affaire_detail == 1) {
   $tables  .=" affaire_detail,";
   $tables  .=" produit,";
   $tables  .=" produit gamme,";
   $clauses .=" affaire_detail.id_produit = produit.id_produit AND ";
   $clauses .=" produit.id_produit_pere = gamme.id_produit AND ";
   $table_affaire = 1;
}
*/
if ($table_affaire == 1) {
   $tables  .=" affaire $inner_affaire ,";
}

/* Ajout des clauses principales */

if ($table_client * $table_contact== 1) {
   $clauses  .=" client.id_client = contact.id_client AND ";
}
if ($table_client * $table_interaction== 1) {
   $clauses  .=" client.id_client = interaction.id_client AND ";
}
if ($table_client * $table_affaire== 1) {
   $clauses  .=" client.id_client = affaire.id_client AND ";
}
if ($table_client * $table_commercial == 1) {
	$clauses  .=" client.id_client = portefeuille.id_client AND ";
}
if ($table_contact * $table_interaction== 1) {
   $clauses  .=" contact.id_contact = interaction.id_contact AND ";
}
if ($table_contact * $table_affaire== 1) {
   $clauses  .=" contact.id_contact = affaire.id_contact AND ";
}
if ($table_affaire * $table_interaction== 1) {
   $clauses  .=" affaire.id_affaire = interaction.id_affaire AND ";
}
/*
if ($table_affaire_detail== 1) {
   $clauses  .=" affaire.id_affaire = affaire_detail.id_affaire AND ";
}
*/


/* Requête */
function raccourci_chaine($chaine, $nb_car) {
    $temp = substr($chaine,0,(strlen($chaine)-$nb_car));
    return ($temp);
}

reset ($champs_1);
$champs = "";
while (list(,$libelle) = each($champs_1)) {
   $champs .= $libelle." ,";
}

$req = "
   select distinct
      ".raccourci_chaine($champs,2)."
   from
      ".raccourci_chaine($tables,1).$inner;

if ($clauses) {
   $req .= "
   where
      ".raccourci_chaine($clauses,5);
}

$resultat = new db_sql($req);



flux_ouvre();

flux_ecrit("");

flux_ecrit("
<html>
   <body>
   <table border=1>
      <tr>");
$longueur_tableau = 0;
reset ($entete_1);
while (list(,$libelle) = each($entete_1)) {
   flux_ecrit("<td align = center bgcolor = D3DCE3 colspan = ".$libelle[1]."><B>".$libelle[0]."</B></td>");
   $longueur_tableau += $libelle[1];
}
flux_ecrit("
      </tr>
      <tr>
");
reset ($entete_2);
while (list(,$libelle) = each($entete_2)) {
   flux_ecrit("<td bgcolor = 'E3ECE3'><I>".$libelle."</I></td>");
}
flux_ecrit("
      </tr>
");
while ($resultat->n()) {
flux_ecrit("
      <tr>
");
reset ($champs_2);
while (list(,$libelle) = each($champs_2)) {
   flux_ecrit("<td>".$resultat->f($libelle)."</td>");
}
flux_ecrit("
      </tr>
");
}
flux_ecrit("
      <tr>
         <td align = center bgcolor = D3DCE3 colspan = ".$longueur_tableau."><I>Fin de la liste</I></td>
      </tr>
");
if ($_SESSION[id_utilisateur]==2) {
flux_ecrit("
      <tr>
         <td align = center bgcolor = D3DCE3 colspan = ".$longueur_tableau."><I>".$req."</I></td>
      </tr>
");
}
flux_ecrit("
   </table>
   </body>
</html>

");
flux_ferme();
?>
