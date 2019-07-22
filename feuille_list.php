<?
include("ress/entete.php");
require_once("c_o_dataset.php");
require_once("fonction.php");

// Chargement des données
if ($ACTE==1) {
	$_SESSION[NumCommande]                = trim($_POST[NumCommande]);
	$_SESSION[Date_com_fdc]               = $_POST['Date_com'];
	$_SESSION[Date_com_f_fdc]             = $_POST['Date_com_f'];
	$_SESSION[Date_creation_d]            = $_POST['Date_creation_d'];
	$_SESSION[Date_creation_f]            = $_POST['Date_creation_f'];
	$_SESSION[_champ_statut_fdc]          = $_POST['_champ_statut']; // C'est normal, le champ est affiché via dataset
	$_SESSION[origine_feuille]            = $_POST['origine_feuille'];
	$_SESSION[Affichage]                  = $_POST[Affichage];
	$_SESSION[fdc_section_id_utilisateur] = $Id_utilisateur;
	$_SESSION[fdc_liste_go]               = 1;
	$_SESSION[client]                     = trim($_POST['client']);
	$_SESSION[affaire]                    = trim($_POST['affaire']);
	$_SESSION[interlocuteur]              = trim($_POST['interlocuteur']);
	$_SESSION[Id_statut_affaire]          = $_POST['Id_statut_affaire'];
	$_SESSION[Date_import_fdc]            = $_POST['Date_import_d'];
	$_SESSION[Date_import_f_fdc]          = $_POST['Date_import_f'];
	$_SESSION[Date_validation_d]          = $_POST['Date_vld_d'];
	$_SESSION[Date_validation_f]          = $_POST['Date_vld_f'];
	$_SESSION[agence]          			  = $_POST['agence'];
	$_SESSION[fdc_liste_go]               = 1;
	$Affichage                            = $_POST['Affichage'];
	$_SESSION[operateur_type_doc_fdc]     = $_POST['operateur_type_doc_fdc'];
	$_SESSION[operateur_type_doc_affaire] = $_POST['operateur_type_doc_affaire'];
}
if (!$_SESSION[Affichage]) {
	$_SESSION[Affichage] = $Affichage_defaut;
	$Affichage = $Affichage_defaut;
}

/*------ Ecran du Requèteur-------*/
?>

<body class="application">
	<table class="cadre_application">
		<tr>
			<td class="cadre_application">
				<table class="menu_haut">
					<tr>
						<td class="menu_haut">Recherche des feuilles de cotes</td>
						<td class='externe'><A class='externe' href="javascript:window.open('feuille_export_xl.php');void(0);">Export EXCEL</A>
							<?php if($_SESSION[id_droit]["production"]){ ?>
						<td class='externe'><A class='externe' href="javascript:window.open('feuille_xl.php');void(0);">Exporter les produits des feuilles de cotes</A>
						<td class='externe'><A class='externe' href="javascript:window.open('feuille_gesprod.php');void(0);">Export GESPROD</A>
							<?php } ?>
					</tr>
				</table>
				<table class="requeteur">
					<form method="post" name="feuille" action="feuille_list.php?ACTE=1" target="droite">
						<tr>
							<td class="requet_right">Origine</td>
							<td class="requet_left">
								<?  // S'il n'y a qu'une origine, elle est bloquée...
								if (!strpos('X'.$_SESSION[origine][list_origine],"|")) {
									$_SESSION[origine_feuille] = $_SESSION[origine][list_origine];
									$droit_or = "N";
								} else {
									$droit_or = "O";
								}
							?>
								<? drop_down_droit($droit_or, "", "origine_feuille", "", "", $_SESSION[origine_feuille],false, "feuille", "N", $_SESSION[origine][list_origine], $_SESSION[origine][list_origine_value]) ?>
							</td>

							<td class="requet_right">Statut FDC</td>
							<td class="requet_left">

								<? drop_down_droit("O", "", "operateur_type_doc_fdc", "", "", $_SESSION[operateur_type_doc_fdc],false, "feuille", "N", "|<>", "=|<>") ?>

								<?
                                                $statut = new champ('statut');
                                                $start = array_slice($statut->valeurs, 0, 0);
                                                $end = array_slice($statut->valeurs, 0);
                                                $start[""] = "[Tous]";
                                                $statut->valeurs = array_merge($start, $end);
                                                if (!$_SESSION[_champ_statut_fdc]) $_SESSION[_champ_statut_fdc] = " ";
                                                $statut->set($_SESSION[_champ_statut_fdc]);
                                                $statut->id_type_champ='lov';
                                                $statut->nonmodifiable=0;
                                                echo $statut->html();
                                                ?>
							</td>
							<td class="requet_right">Date de commande du</td>
							<td class="requet_left">
								<? champ_date_droit ("O", "Date_com", $_SESSION[Date_com_fdc], "feuille", "N") ?>au
								<? champ_date_droit ("O", "Date_com_f", $_SESSION[Date_com_f_fdc], "feuille", "N") ?>
							</td>
						</tr>
			<td class="requet_right">N° de commande</td>
			<td class="requet_left">
				<? champ_ouvert_droit("O","NumCommande", $_SESSION[NumCommande],60, 30, "N"); ?>
			<td class="requet_right">Statut Affaire</td>
			<td class="requet_left">
				<? drop_down_droit("O", "", "operateur_type_doc_affaire", "", "", $_SESSION[operateur_type_doc_affaire],false, "feuille", "N", "|<>", "=|<>") ?>
				<? drop_down_droit('O',
                          "SELECT Id_statut, Lb_statut FROM statut","Id_statut_affaire", "Id_statut", "Lb_statut", $_SESSION[Id_statut_affaire], false, "affaire","O", "", " "); ?>
			</td>
			<td class="requet_right">Date de création du</td>
			<td class="requet_left">
				<? champ_date_droit ("O", "Date_creation_d", $_SESSION[Date_creation_d], "feuille", "N") ?>au
				<? champ_date_droit ("O", "Date_creation_f", $_SESSION[Date_creation_f], "feuille", "N") ?>
			</td>

		<tr>
			<td class="requet_right">Commercial</td>
			<td class="requet_left">
				<?
                          $defident="";
                          if(droit_utilisateur("secto")){
                              $droit_drop_down = "N";
                              $defident=$_SESSION[id_utilisateur];
                          } else {
                              $defident=$_SESSION[fdc_section_id_utilisateur];
                              $droit_drop_down = "O";
                          }
									drop_down_droit($droit_drop_down,"SELECT 
										Id_utilisateur,
										Nom 
									 FROM 
										utilisateur
									 WHERE 
										utilisateur.id_profil in (1,5)
									 ORDER BY 
										Nom ASC", "Id_utilisateur", "Id_utilisateur", "nom", $defident,false, "client","N","", " ");
                          ?>
			</td>

			<td class="requet_right">Raison sociale</td>
			<td class="requet_left">
				<? champ_ouvert_droit("O","client", $_SESSION[client],60, 30, "N"); ?>

			<td class="requet_right">Date d'import du</td>
			<td class="requet_left">
				<? champ_date_droit ("O", "Date_import_d", $_SESSION[Date_import_fdc], "feuille", "N") ?>au
				<? champ_date_droit ("O", "Date_import_f", $_SESSION[Date_import_f_fdc], "feuille", "N") ?>
			</td>

		</tr>

		<tr>
			<td class="requet_right">Nom de l'interlocuteur</td>
			<td class="requet_left">
				<? champ_ouvert_droit("O","interlocuteur", $_SESSION[interlocuteur],60, 30, "N"); ?>

			<td class="requet_right">Affichage</td>
			<td class="requet_left">
				<? champ_numeric_droit("O","Affichage", $_SESSION[Affichage], 0, 5, "affaire", "O"); ?> (Lignes)</td>

			<td class="requet_right">Date de validation du</td>
			<td class="requet_left">
				<? champ_date_droit ("O", "Date_vld_d", $_SESSION[Date_validation_d], "feuille", "N") ?>au
				<? champ_date_droit ("O", "Date_vld_f", $_SESSION[Date_validation_f], "feuille", "N") ?>
			</td>
		</tr>
		<tr>
			<td class="requet_right">Nom de l'affaire</td>
			<td class="requet_left">
				<? champ_ouvert_droit("O","affaire", $_SESSION[affaire],60, 30, "N"); ?>

			<td class="requet_right">Agence</td>
			<td class="requet_left">
				<? drop_down_droit ("O",
							  "SELECT nom FROM utilisateur u WHERE exists(SELECT 1 from utilisateur WHERE Id_responsable = u.Id_utilisateur)",
							  "agence", "nom", "nom",$_SESSION[agence], false, "utilisateur", "N", "", " "); ?>
			</td>
			<td class="requet_right">N° Feuille de cotes</td>
			<td class="requet_left">
				<? champ_ouvert_droit("O","num_fdc", $_SESSION[num_fdc],60,30,"N"); ?>
			</td>
		</tr>
		<tr>
			<td class="requet_right" colspan=6><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
		</tr>
		</form>
	</table>
	<?
if ($_SESSION[feuille_liste_go] or $_SESSION[id_profil]==6 or $ACTE==1) {
	$_SESSION[feuille_liste_go] = 1;

	// Critere de recherche
	if(trim($_SESSION[_champ_statut_fdc])){
        $operateur = ($op = $_SESSION[operateur_type_doc_fdc])?$op:"=";
        $requete.="dt.statut ".$operateur." '".trim($_SESSION[_champ_statut_fdc])."' AND ";
	}

	if(trim($_SESSION[origine_feuille])){
		$requete.="dt.origine = '".trim($_SESSION[origine_feuille])."' AND ";
	}

	$Date_com = $_SESSION[Date_com_fdc];
	if($Date_com){
	   //$Date_com = formatterDate(substr($Date_com,0,6).'20'.substr($Date_com,6,2)) ;
	   $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_com', '%Y-%m-%d' ) AND ";
	}

	$Date_com_f= $_SESSION[Date_com_f_fdc];
	if($Date_com_f){
	   $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_com_f', '%Y-%m-%d' )  AND ";
	}
	
	if($_SESSION[NumCommande]){
	   $requete.="dt.numcommande_fdc like ".My_sql_format("%".trim($_SESSION[NumCommande])."%")." AND ";
	}

	$Date_creation_d = $_SESSION[Date_creation_d];
	if($Date_creation_d){
		//$Date_com = formatterDate(substr($Date_com,0,6).'20'.substr($Date_com,6,2)) ;
		$requete.="STR_TO_DATE(dt.date_creation, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_creation_d', '%Y-%m-%d' ) AND ";
	}

	$Date_creation_f= $_SESSION[Date_creation_f];
	if($Date_creation_f){
		$requete.="STR_TO_DATE(dt.date_creation, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_creation_f', '%Y-%m-%d' )  AND ";
	}

	if($Id_utilisateur){
		$requete .= " affaire.id_utilisateur = ".$Id_utilisateur." AND ";
	}

    if($client){
        $requete .= " dt.nomclient_fdc like ".My_sql_format("%".trim($client)."%")." AND ";
    }

    if($affaire){
        $requete .= " affaire.commentaire like ".My_sql_format("%".trim($affaire)."%")." AND ";
    }

    if($interlocuteur){
        $requete .= " contact.nom like ".My_sql_format("%".trim($interlocuteur)."%")." AND ";
    }

    if($Id_statut_affaire){
        $operateur = ($op = $_SESSION[operateur_type_doc_affaire])?$op:"=";
        $requete .= "statut.Id_statut ".$operateur." ".$Id_statut_affaire." AND ";
    }

    if($Date_import_d) {
        $requete .= "STR_TO_DATE(dt.date_import, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_import_d', '%Y-%m-%d' ) AND ";
    }


    if($Date_import_f) {
        $requete .= "STR_TO_DATE(dt.date_import, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_import_f', '%Y-%m-%d' )  AND ";
    }

    if($Date_vld_d) {
        $requete .= "STR_TO_DATE(dt.date_vld_fdc, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_vld_d', '%Y-%m-%d' ) AND ";
    }

    if($Date_vld_f) {
        $requete .= "STR_TO_DATE(dt.date_vld_fdc, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_vld_f', '%Y-%m-%d' )  AND ";
    }

    if($agence) {
        $requete .= "dt.agence = '".$agence."' AND ";
    }
	
	$_SESSION[num_fdc] = $_POST[num_fdc];
	if($_SESSION[num_fdc]){
	   $requete.="doc.id_doc = '".$_SESSION[num_fdc]."' and";
	}

	$req="
	SELECT
		doc.id_doc as 'N Feuille de cote',
		dt.nomclient_fdc as 'Raison sociale',
		dt.numcommande_fdc as 'N de commande ou BCC',
		dt.phase as Phase,
		dt.prix_ht as 'montant_Montant HT',
		Lb_statut as 'Statut Affaire',
		commentaire as 'Nom Affaire',
		CONCAT('<a class=\"resultat\" href=\"doc.php?id_doc=', doc.id_doc, '\" target=\"droite\">', dt.statut, '</a>') as 'Statut Feuille de Cotes',
		concat(u.nom,' ' ,u.prenom) as Commercial
	FROM
		affaire,
		statut,
		zdataset_fdcentete dt,
		utilisateur u,
		contact,
		doc
	WHERE $requete
		type_doc='fdc' AND
		dt.id_dataset = doc.id_dataset_entete AND
		affaire.id_affaire = dt.affaire AND statut.Id_statut=affaire.Id_statut AND
		affaire.id_contact = contact.id_contact AND
		u.id_utilisateur = affaire.id_utilisateur 
	ORDER BY
	   doc.id_doc DESC	
	LIMIT 0, ".($Affichage+2);
	/*----------- EXECUTION ---------*/
    $resultat = new db_sql($req);
    $_SESSION[fdc_requete]  = $req; // Pour l'export des produits
	$_SESSION[fdc_affichage]  = $Affichage; // Pour Gest prod
	$_SESSION[fdc_critere]  = $requete; // Pour Gest prod
	/*----------- AFFICHAGE ---------*/
	/* Affichage des en_têtes */
	$tableau = new tableau($req);
	echo $tableau->html();
}

if ($ACTE != 1 and $_SESSION[feuille_liste_go]) {
?>
	<script type="application/javascript">
		feuille.submit();
	</script>
	<?
}
include ("ress/enpied.php");
?>