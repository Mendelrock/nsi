<?
include("ress/entete.php");
require_once("c_o_dataset.php");
$champ_origine=new champ('origine');
if ($ACTE==1) {
	$_SESSION[feuille_liste_go] = 1;
	// Critere de recherche
	$_SESSION["_champ_statut_of"] = $_POST["_champ_statut_of"];
	if($_SESSION["_champ_statut_of"]){
	   $requete.="dt.statut_of = '".trim($_SESSION["_champ_statut_of"])."' AND ";
	}
   $_SESSION[origine_of_list] = $_POST['origine_of_list'];
	if(trim($_SESSION[origine_of_list])){
	   $requete.="dt.origine = '".trim($_SESSION[origine_of_list])."' AND ";
	}
	$_SESSION[NumCommande] = trim($_POST[NumCommande]);
	if($_SESSION[NumCommande]){
	   $requete.="dt.numcommande_fdc_of like '%".$_SESSION[NumCommande]."%' AND ";
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
	$_SESSION['Date_creation_d'] = $_POST['Date_creation_d'];
	if ($_SESSION['Date_creation_d']) {
		$requete.="dt.date_creation>='".$_SESSION['Date_creation_d']."' and ";
	}
	$_SESSION['Date_creation_f'] = $_POST['Date_creation_f'];
	if ($_SESSION['Date_creation_f']) {
		$requete.="dt.date_creation<='".$_SESSION['Date_creation_f']."' and ";
	}
	$_SESSION[Affichage] = $_POST[Affichage];

	$_SESSION[agence] = $_POST[nom_agence];
	if($_SESSION[agence]){
	    $requete .="dt.agence ='".$_SESSION[agence]."' and";
	}
	
	$_SESSION[num_of] = $_POST[num_of];
	if($_SESSION[num_of]){
		$requete .="dt.fdc = '".$_SESSION[num_of]."' and";
	}

	$req="
        SELECT
            doc.type_doc,
            dt.date_cde as date,
            dt.nomclient_fdc as client,
            dt.numcommande_fdc_of as commande,
            dt.statut_of as statut,
            dt.origine as origine,
            dt.agence as agence,
            doc.id_doc
        FROM
            doc
            inner join
				(
					SELECT
						zdo.id_dataset,
						zdo.origine,
						zdo.date_creation,
						zdo.agence,
						zdo.date_cde,
						zdo.fdc,
						zdo.nomclient_fdc,
						zdo.numcommande_fdc_of,
						zdo.statut_of 
					FROM
						zdataset_ofentete zdo 
				UNION
					SELECT
						zdc.id_dataset,
						zdc.origine,
						zdc.date_creation,
						zdc.agence,
						zdc.date_cde,
						zdc.fdc,
						zdc.nomclient_fdc,
						zdc.numcommande_fdc_of,
						zdc.statut_of 
					FROM
						zdataset_bonentete zdc
				) dt on (dt.id_dataset = doc.id_dataset_entete)
        WHERE $requete
            ( type_doc like 'Bon%' or type_doc like 'OF - %' )
        ORDER BY
            dt.numcommande_fdc_of DESC
            LIMIT 0, ".($Affichage+2);
	   
	/*----------- EXECUTION ---------*/
	$resultat = new db_sql($req);
	/*----------- AFFICHAGE ---------*/
	/* Affichage des en_têtes */
	$tab_id_doc = array();
	?>
	<body class="application">
	   <table class="resultat">
	      <tr>
	      	<td class = "resultat_tittle">Origine</td>
	         <td class = "resultat_tittle">Type OF</td>
	         <td class = "resultat_tittle">Date de commande</td>
	         <td class = "resultat_tittle">Nom du client</td>
	         <td class = "resultat_tittle">Statut</td>
	         <td class = "resultat_tittle">N° de commande</td>
	         <td class = "resultat_tittle">Agence</td>
	      </tr>
	<?
	while($z<$Affichage AND $resultat->n() ){
	   $z++;
	   $ligne['Origine'] = $champ_origine->valeurs[$resultat->f('origine')];
	   $ligne['Type OF'] = $resultat->f('type_doc');
	   $ligne['Date de commande'] = $resultat->f('date');
	   $ligne['Nom du client'] = $resultat->f('client');
	   $ligne['Statut'] = $resultat->f('statut');
	   $ligne['N° de commande'] = $resultat->f('commande');
	   $ligne['Agence'] = $resultat->f('agence');
	   $_SESSION[xl_of_list][] = $ligne;
	?>
	      <tr>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $champ_origine->valeurs[$resultat->f('origine')] ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('type_doc') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('date') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('client') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><a class='resultat' href='doc.php?id_doc=<? echo $resultat->f('id_doc') ?>' target='droite'><? echo $resultat->f('statut') ?></a></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('commande') ?></td>
				<td nowrap class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo $resultat->f('agence') ?></td>
	      </tr>
	<?
	$tab_id_doc[] = $resultat->f('id_doc');
	}
	$_SESSION['tab_id_doc'] = $tab_id_doc;
	if($z) {
	   $total_e = explode('.',$total);
	   $total = $total_e[0].'.'.substr($total_e[1].'00',0,2);
	   $suiv_text="Fin de liste";
	   if($resultat->n()){
	      $suiv_text="Liste non terminée";
	   }
	   echo "<tr>";
	   echo"<td class='resultat_footer' colspan='5'>Cliquer sur statut pour ouvrir un OF</td>";
	   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
		echo "</tr>";
	}
	echo "</table>";
}
/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>