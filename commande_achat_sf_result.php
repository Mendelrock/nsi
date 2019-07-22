<?
include("ress/entete.php");
require_once("c_o_dataset.php");
$champ_origine=new champ('origine');

if ($ACTE==1) {
	$_SESSION[feuille_liste_go] = 1;
	// Critere de recherche
	$_SESSION[commande_liste_statut] = $_POST[id_statut_commande];
	if($_SESSION[commande_liste_statut]){
		if($_SESSION[commande_liste_statut] == 5){
			$requete .= "dt.statut_commande IN (3,4) AND ";
		}else {
			$requete .= "dt.statut_commande IN ('$_SESSION[commande_liste_statut]') AND ";
		}
	}
   $_SESSION[origine_cde] = $_POST['origine_cde'];
	if(trim($_SESSION[origine_cde])){
	   $requete.="dt.origine = '".trim($_SESSION[origine_cde])."' AND ";
	}
	$_SESSION[commande_liste_fournisseur] = $_POST[id_fournisseur_commande];
	if($_SESSION[commande_liste_fournisseur]){
		$requete.="dt.fournisseur_commande = '$_SESSION[commande_liste_fournisseur]' AND ";
	}
	$_SESSION[NumCommande] = trim($_POST[NumCommande]);
	if($_SESSION[NumCommande]){
	   $requete.="dt.numcommande_fdc_commande like '%".$_SESSION[NumCommande]."%' AND ";
	}
	$_SESSION['type_doc'] = $_POST['type_doc'];
	$_SESSION['operateur_type_doc'] = $_POST['operateur_type_doc'];	
	if(is_array($_SESSION[type_doc])){
	   $requete.="doc.type_doc ".$_SESSION["operateur_type_doc"]." in ('".implode("','",$_SESSION['type_doc'])."') AND ";
	}
	$_SESSION['Date_of_d'] = $_POST['Date_of_d'];
	if ($_SESSION['Date_of_d']) {
		$requete.="dt.date_cde>='".$_SESSION['Date_of_d']."' and ";
	}
	$_SESSION['Date_of_f'] = $_POST['Date_of_f'];
	if ($_SESSION['Date_of_f']) {
		$requete.="dt.date_cde<='".$_SESSION['Date_of_f']."' and ";
	}
	$_SESSION[Affichage] = $_POST[Affichage];
	$req="
		SELECT
			doc.type_doc,
			dt.date_cde as date,
			dt.nomclient_fdc as client,
			dt.numcommande_fdc_commande as commande,
			dt.statut_commande as statut_commande,
			dt.origine as origine,
			dt.fournisseur_commande as fournisseur_commande,
			doc.id_doc
		FROM
			zdataset_commandeentete dt,
			doc
		WHERE
			$requete
			dt.id_dataset = doc.id_dataset_entete 
		ORDER BY
			dt.numcommande_fdc_commande DESC
		LIMIT 0, ".($Affichage+2);
	   
	/*----------- EXECUTION ---------*/
	$resultat = new db_sql($req);	
	/*----------- AFFICHAGE ---------*/
	/* Affichage des en_têtes */
	?>
	<body class="application">
	
	<form method="post" name ="feuille" action="commande_achat_sf_mail.php?ACTE=1&id_doc=. id_doc" target="resultat">
	   <table class="resultat">
	      <tr>
	      	<td class = "resultat_tittle">Origine</td>
	         <td class = "resultat_tittle">Type Commande</td>
	         <td class = "resultat_tittle">Date de commande</td>
	         <td class = "resultat_tittle">Nom du client</td>
	         <td class = "resultat_tittle">Statut</td>
	         <td class = "resultat_tittle">N° de commande</td>

	      </tr>
	<?
	$_SESSION['tableau_req_export'] = [];
	$cpt=0;
	while($z<$Affichage AND $resultat->n() ){
		$z++;
		//var_dump($resultat->f('id_doc'));
		if($resultat->f('statut_commande') == 1){
			$statut_commande = 'Générée';
		}else if($resultat->f('statut_commande') == 2){
			$statut_commande = 'Envoyée';
		}else if($resultat->f('statut_commande') == 3){
			$statut_commande = 'Réceptionnée partiellement';
		}else if($resultat->f('statut_commande') == 4){
			$statut_commande = 'Réceptionnée entièrement';
		}else if($resultat->f('statut_commande') == 6){
			$statut_commande = 'Annulée';
		}
		
		$_SESSION['tableau_req_export'][$cpt]['Origine'] = $champ_origine->valeurs[$resultat->f('origine')];
		$_SESSION['tableau_req_export'][$cpt]['Type Commande'] = $resultat->f('type_doc');
		$_SESSION['tableau_req_export'][$cpt]['Date de Commande'] = $resultat->f('date');
		$_SESSION['tableau_req_export'][$cpt]['Nom du Client'] = $resultat->f('client');
		$_SESSION['tableau_req_export'][$cpt]['Statut'] = $statut_commande;
		$_SESSION['tableau_req_export'][$cpt]['N° de Commande'] = $resultat->f('commande');
		$cpt++;
	?>
	      <tr>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $champ_origine->valeurs[$resultat->f('origine')] ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('type_doc') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('date') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('client') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><a class='resultat' href='doc.php?id_doc=<? echo $resultat->f('id_doc') ?>' target='droite'><? echo $statut_commande ?></a></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('commande') ?></td>
				<!--<td nowrap class = "resultat_list" align='center' bgcolor = "<? /*echo alternat($z) ?>"><? if (($resultat->f('statut_commande') == '1') && ($_SESSION[commande_liste_fournisseur] != '')) { ?> <input type="checkbox" name="$mail_send_checkbox_id_doc[]"  id="mail_send_checkbox_<?echo($resultat->f('id_doc'));?>" value="<?echo($resultat->f('id_doc'));?>"> <? }else{echo'*';}*/?> </td>-->
				<?//$tab_id_doc[]=$resultat->f('id_doc'); ?>
		  </tr>
	<?
	}
				//$parm = array(fournisseur => $_SESSION[commande_liste_fournisseur]);
				//$parm[commandes]=$tab_id_doc;

	if($z) {
	   $total_e = explode('.',$total);
	   $total = $total_e[0].'.'.substr($total_e[1].'00',0,2);
	   $suiv_text="Fin de liste";
	   if($resultat->n()){
	      $suiv_text="Liste non terminée";
	   }
		/*echo"<tr>";
		if($_SESSION[commande_liste_fournisseur] != '') {
			echo "<td class='requet_right' colspan = 7><input class='requeteur_button' style='width: auto; !important;' type='submit' name='Submit' value='Envoyer commande(s)')></td>";
		}*/
		echo"</form>";
        echo"</tr>";
	   echo "<tr>";
	   echo"<td class='resultat_footer' colspan='4'>Cliquer sur statut pour ouvrir une commande | Selectionner les commandes puis cliquer sur Envoyer</td>";
	   echo"<td class='resultat_footer' align='center' colspan='3'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
		echo "</tr>";
	}
	echo "</table>";
}
/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
