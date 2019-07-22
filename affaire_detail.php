<?
include("ress/entete.php");
require_once("c_o_dataset.php");

/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Mode création
/*   $Id_interaction
/*  Mode Consultation
/*   Id_affaire
/*
/* Internes
/*  $ACTE
/*  $Id_contact
/*  $Id_affaire
/*  $Date_prev
/*  $Date_crea
/*  Parametres gestion produit
/*  $ACTE_Produit
/*  $Id_affaire
/*        $Id_produit
/*        $Prix
/*        $Qt
/*
/*------------------------------------------------*/
function droit($droit1, $droit2){
    return ($_SESSION[id_droit][$droit1])? $droit1 : ( ($_SESSION[id_droit][$droit2])? $droit2 : '' );
}

/*----------- CREATION Affaire ---------------*/
if (!$Id_client and $Id_contact) {
	$Id_client    = req_sim("select Id_client from contact where Id_contact = $Id_contact","Id_client");
}

if($id_doc and $supr=="yes"){

    $erase = new db_sql("delete from doc_ligne where id_doc = $id_doc");
    $erase = new db_sql("delete from doc_groupe_ligne where id_doc = $id_doc");
    $erase = new db_sql("delete from doc where id_doc = $id_doc");
    $erase = new db_sql("delete from dataset where not exists (select 1 from doc where id_dataset = id_dataset_empied) and
																		not exists (select 1 from doc where id_dataset = id_dataset_entete) and
																		not exists (select 1 from doc_ligne where dataset.id_dataset = doc_ligne.id_dataset) and
																		not exists (select 1 from doc_groupe_ligne where dataset.id_dataset = doc_groupe_ligne.id_dataset)");
    $req = charge("select distinct type_dataset from dataset");
	foreach($zdatasets as $type_zdataset){
		$erase = new db_sql("delete from `zdataset_".$type_zdataset."` where not exists (select 1 from dataset where dataset.id_dataset = `zdataset_".$type_zdataset."`.id_dataset)");
	}
}

if ($ACTE==1) {

    /*------------Verification Formulaire------------*/
    if( empty($Date_prev) || empty($Date_crea) || empty($Id_statut) || empty($Commentaire) || empty($Prix)) {
        $message = "Des données obligatoires sont manquantes";
    }
    if( $Date_prev < $Date_crea ) {
        $message = "La date de prévision doit être ultérieure ou égale à la date de création";
    }
    if (!$message) {
        //Traitement du champ statut
        //if ($Id_statut=="5" || $Id_statut=="6" || $Id_statut=="7") {
        //   $Date_prev=aujourdhui();
        //}
        //Execution
        $requete="
         INSERT INTO
            affaire (
               Id_utilisateur,
               Commentaire,
					Reference,
               Date_crea,
               Date_prev,
               Prix,
               Pose,
               Id_transac,
               Id_statut,
               Id_contact,
               Id_client,
			   Pnne)
         VALUES (
            $_SESSION[id_utilisateur],
            ".My_sql_format($Commentaire).",
            ".My_sql_format($Reference).",
            ".My_sql_format(aujourdhui()).",
            ".My_sql_format($Date_prev).",
            ".My_sql_format($Prix).",
            ".My_sql_format($Pose*1).",
            ".My_sql_format($Id_transac).",
            ".My_sql_format($Id_statut).",
            ".My_sql_format($Id_contact).",
            ".My_sql_format($Id_client).",
			".My_sql_format($Pnne).")";
        $dbres= new db_sql();
        $dbres->begin();
        $dbres->q($requete);
        $Id_affaire = db_sql::last_id ();
        $requete="
         INSERT INTO
            affaire_detail (
               Id_affaire,
               Id_produit,
               Prix,
               Qt)
         select 
            $Id_affaire,
            Id_produit,
            0,
            0
			from
				produit
			where niveau = 2";
        $dbres->q($requete);
        //$resultat = new db_sql($requete);
		if(!$BUREAU) {
			$requete="
			 UPDATE
				interaction
			 SET
				id_affaire = $Id_affaire
			 WHERE
				id_affaire is null and
				id_interaction = $Id_interaction or 
				id_interaction_pere = $Id_interaction";
				
			//$resultat = new db_sql($requete);
			$dbres->q($requete);
		}
        set_refresh ("Id_affaire|$Id_affaire");
    }
}

/*----------------- UPDATE affaire ------------------*/
if ($ACTE==2) {
    if(empty($Date_crea)||empty($Date_prev)||empty($Id_statut)||empty($Prix)) {
        $message = "Des données obligatoires sont manquantes";
    }
    if( $Date_prev<$Date_crea ) {
        $message = "La date de prévision doit être ultérieure ou égale à la date de création";
    }
    if (!$message) {
        //Traitement du champ statut et Date_prev
        //$ctrl=req_sim("SELECT Id_statut FROM affaire","Id_statut");
        //if ($Id_statut=="5" || $Id_statut=="6" || $Id_statut=="7" AND $ctrl!="5" || $ctrl!="6" || $ctrl!="7") {
        //   $Date_prev=aujourdhui();
        //}
        //Execution
        $requete="
          UPDATE
             affaire
          SET
             Date_crea   =".My_sql_format($Date_crea).",
             Date_prev   =".My_sql_format($Date_prev).",
             Commentaire =".My_sql_format($Commentaire).",
             Reference   =".My_sql_format($Reference).",
             Prix        =".My_sql_format($Prix).",
             Pose        =".My_sql_format($Pose*1).",
             Id_transac  =".My_sql_format($Id_transac).",
             Num_bcc     =".My_sql_format($Num_bcc).",
             Id_statut   =".My_sql_format($Id_statut).",
             Id_contact  =".My_sql_format($Id_contact).",
			 Pnne  		 =".My_sql_format($Pnne)."
          WHERE
             Id_affaire = $Id_affaire ";
        $dbres= new db_sql();
        $dbres->begin();
        $dbres->q($requete);
        //$resultat= new db_sql($requete);
        // Mise à jour de la date commande par la date de la signature de l'affaire
        if($Id_statut == 6) {
            $requete_maj ="
              UPDATE
                  zdataset_fdcentete dt,
                  doc
              SET
                  dt.date_cde ='".aujourdhui()."'
              WHERE
                  dt.date_cde = '' and
                  dt.id_dataset = doc.id_dataset_entete and
                  doc.type_doc='fdc' and
                  dt.affaire=  $Id_affaire";
            $dbres->q($requete_maj);
        }
		
		if ($Num_bcc) {
			$requete_maj="
				UPDATE
					zdataset_fdcentete dt,
					doc,
					affaire a
				SET
					dt.numcommande_fdc = ".My_sql_format($Num_bcc)."
				WHERE
					a.id_affaire = dt.affaire and
					type_doc='fdc' and
					dt.id_dataset = doc.id_dataset_entete and
					dt.affaire= $Id_affaire ";
			$dbres->q($requete_maj);
		}
		set_refresh ("Id_affaire|$Id_affaire");
	}
}

// Hitorisation 
if ($ACTE and !$message) {
	$isgenerer_of = false;
	if($Id_statut==2) {
		$requete="
			SELECT 
            *
		   FROM
            affaire_histo 
		   WHERE 
		      Id_statut = $Id_statut AND 
		      Id_affaire = $Id_affaire ";
			// Si l'affaire n'a pas encore été signée avant
			$notalreadysigned = isEmpty_Record(new db_sql($requete));
		}
		$requete="
         REPLACE INTO
            affaire_histo (
               Id_affaire,
               Id_utilisateur,
               Commentaire,
               Reference,
               Date_crea,
               Date_prev,
               Prix,
               Pose,
               Id_transac,
               Num_bcc,
               Id_statut,
               Id_contact,
               Id_client)
         select 
               Id_affaire,
               Id_utilisateur,
               Commentaire,
					Reference,
               ".My_sql_format(aujourdhui()).",
               Date_prev,
               Prix,
               Pose,
               Id_transac,
               Num_bcc,
               Id_statut,
               Id_contact,
               Id_client
         from 
               affaire
         where 
               affaire.Id_affaire = $Id_affaire ";
	$dbres->q($requete);
	//$resultat = new db_sql($requete);
	//la signature d'une affaire passe à A produire toutes les feuilles de cotes Validées
	if($Id_statut== 2 && $notalreadysigned) {
        $requete = "
				SELECT 
					*
				FROM
					affaire,client
				WHERE 
					affaire.Id_client = client.Id_client
					and Id_affaire=$Id_affaire";
        $resultat = new db_sql($requete);
        while ($resultat->n()) {
            //Lors de la signature de l'affaire sur un client/prospect : Modifier le statut de prospect pour le rendre client.
            if ($resultat->f("Tva_intra") == 1) {
                $idclient = $resultat->f("Id_client");
                $requete = "
				UPDATE 
					client 
				SET 
					Tva_intra=2
				WHERE 
					Id_client=$idclient";
				 $dbres->q($requete);
			}
		}
	}
	if($Id_statut==6) {
		$requete = "
		SELECT
			doc.id_doc
		FROM
			zdataset_fdcentete dt, doc
		WHERE
			type_doc='fdc'
			and dt.id_dataset = doc.id_dataset_entete
			and dt.statut='Validée'
			and dt.affaire=$Id_affaire";
		$resultat = new db_sql($requete);
		while($resultat->n()){
			if(!is_document_lie_cree($resultat->f("id_doc"))) {
				$message = generer_of($resultat->f("id_doc"),$dbres);
				if($message) 
					break;				
				$dbres->q("
					UPDATE
						zdataset_fdcentete dt,
						doc
					SET
						dt.statut = 'A produire'
					WHERE
 						doc.id_doc = ".$resultat->f("id_doc")."
						and dt.id_dataset = doc.id_dataset_entete
 						and type_doc='fdc'");
			}
		}
	}
	if (!$message) {
		$dbres->commit();
	} else {
		$dbres->rollback();
	}
}

/*---------------- AFFICHAGE FICHE CLIENT---------*/
if ($message) {
} else if ($Id_affaire) {
    // Requete : SELECT
    $requete="
       SELECT
            affaire.Id_affaire,
	         affaire.Id_utilisateur,
	         affaire.Date_crea,
	         affaire.Commentaire,
	         affaire.Reference,
	         affaire.Date_prev,
	         affaire.Prix,
	         affaire.Pose,
	         affaire.Id_transac,
	         affaire.Num_bcc,
	         affaire.Id_statut,
	         affaire.Id_contact,
	         civilite.Lb_civilite,
				contact.Prenom,
				contact.Nom,
	         contact.Telephone,
	         contact.Mobile,
	         contact.Fax,
	         contact.Mail,
	         client.Id_client,
	         client.raison_sociale,
	         concat(IFNULL(client.Adresse1,''),'\r', IFNULL(client.Adresse2,''),'\r', (IFNULL(client.Adresse3,'')),'\r', IFNULL(client.Cp,''), '  ', IFNULL(client.Ville,'')) as Client_adresse,
	         champ_lov_valeurs.valeur_affichee
        FROM
            affaire,
            utilisateur
			LEFT OUTER join champ_lov_valeurs on (
				utilisateur.Id_responsable = champ_lov_valeurs.valeur_stockee and
				champ_lov_valeurs.field = 'agence'),
            contact
            LEFT OUTER join civilite on (civilite.Id_civilite = contact.Id_civilite),
            client
        WHERE
			affaire.Id_contact = contact.Id_contact AND
			affaire.Id_client = client.Id_client AND
			utilisateur.Id_utilisateur = affaire.Id_utilisateur AND
			affaire.Id_affaire = $Id_affaire";
			/*----------- EXECUTION ---------*/
    $resultat = new db_sql($requete);
    while($resultat->n()){
        /*----------- Inititalisation des variables du formulaire --------*/
        //Valeur par défaut
        $Id_utilisateur = $resultat->f("Id_utilisateur");
        $Commentaire    = $resultat->f("Commentaire");
        $Date_crea      = $resultat->f("Date_crea");
        $Date_prev      = $resultat->f("Date_prev");
        $Prix           = $resultat->f("Prix");
        $Pose           = $resultat->f("Pose");
        $Reference      = $resultat->f("Reference");
        $Id_transac     = $resultat->f("Id_transac");
        $Num_bcc        = $resultat->f("Num_bcc");
        $Id_statut      = $resultat->f("Id_statut");
        $Id_contact     = $resultat->f("Id_contact");
        $nom				= $resultat->f("Lb_civilite")." ".$resultat->f("Prenom")." ".$resultat->f("Nom");
        $telephone      = $resultat->f("Telephone");
        $mobile         = $resultat->f("Mobile");
        $fax            = $resultat->f("Fax");
        $mail           = $resultat->f("Mail");
        $Id_client      = $resultat->f("Id_client");
        $raison_sociale = $resultat->f("Raison_sociale");
        $ville          = $resultat->f("Ville");
        $agence         = $resultat->f("valeur_affichee");
        $client_adresse = $resultat->f("Client_adresse");
    }
} else {
    // Requete : SELECT
    // $Id_interaction est un parametre
	
	$clause = 'AND client.Id_client  = interaction.Id_client  AND
            contact.Id_contact = interaction.Id_contact AND 
			interaction.id_interaction = '.$Id_interaction.'';
	$clause_tb = ', interaction';
	if($fg_interlocuteur) {
		$clause = 'AND contact.Id_contact = '.$Id_contact.' and client.Id_client = '.$Id_client.'';
		$clause_tb = '';
		$Id_interaction = '';
	}
	
    $requete = "
        SELECT
            contact.Id_contact,
            contact.Id_client,
            civilite.Lb_civilite,
				contact.Prenom,
				contact.Nom,
            client.Raison_sociale
        FROM
            client,
            contact
        LEFT OUTER join civilite on (civilite.Id_civilite = contact.Id_civilite)
			$clause_tb
        WHERE
			1 
            $clause
		";
			
			
    /*----------- EXECUTION ---------*/
    $resultat = new db_sql($requete);
    while($resultat->n()){
        /*----------- Inititalisation des variables du formulaire --------*/
        //Valeur par défaut
        $Id_client 		= $resultat->f("id_client");
        $nom				= $resultat->f("Lb_civilite")." ".$resultat->f("Prenom")." ".$resultat->f("Nom");
        $raison_sociale = $resultat->f("raison_sociale");
        $ville 			= $resultat->f("ville");
        $Id_contact 		= $resultat->f("Id_contact");
    }
    $Id_utilisateur = $_SESSION[id_utilisateur];
    $Date_crea=aujourdhui();
    $Date_prev="";
    $Id_transac="";
    $Id_statut="";
}

if ($Id_affaire) {
    //Titre
    $titre="Affaire";
    $titre_bouton="Enregistrer";
    //Action si validation formulaire
    $ACTE=2;
} else {
    //Titre
    $titre="Nouvelle affaire";
    $titre_bouton="Créer";
    //Action si validation formulaire
    $ACTE=1;
}

if ( (droit_utilisateur("com") and ($Id_utilisateur == $_SESSION[id_utilisateur])) or droit_utilisateur("adv") or droit_utilisateur("ascom") ) {
    $g_droit = "O";
} else {
    $g_droit = "N";
}

if ($Id_affaire){
    if( $Id_affaire == 1) {
        $origine = "1";
        $agence = "Stores & Rideaux";
    } elseif($Id_affaire == 2) {
        $origine = "2";
        $agence = "Prosolair";
    } else {
        $origine = "3";
    }
}
?>
<body class="application">
<form method="post" name ="affaire" action="affaire_detail.php?ACTE=<? echo $ACTE; ?>">
    <table class="cadre_application">
        <tr>
            <td class="cadre_application" align="center" valign="middle">
                <table class="menu_haut">
                    <tr>
                        <td class="menu_haut"><? echo $titre; ?></td>
                        <td class='externe'><A class='externe' href='client_detail.php?Id_client=<? echo $Id_client?>'>Ouvrir le client</A></td>
                        <td class='externe'><A class='externe' href='contact_detail.php?Id_contact=<? echo $Id_contact?>'>Ouvrir l'interlocuteur</A></td>
<?
if (droit_utilisateur("com") and $Id_affaire ) {
?>
              <td class='externe'><A class='externe' href='interaction_detail.php?Id_contact=<? echo $Id_contact; ?>&Id_affaire=<? echo $Id_affaire?>'>Créer une action</A></td>
<?
}
if($Id_statut >= 6) $dte_cmd = aujourdhui();
// Pages et Fontaine
//if(droit_feuille_de_cote('En cours')==2 and (($_SESSION[id_utilisateur] == 5) or ($_SESSION[id_utilisateur] == 15))) {
if(droit_feuille_de_cote('En cours')==2) {
	$query_string = 'type_doc=fdc'; // Ordre des champs important à cause de l'action collatérale du SET
	if ($Id_affaire) $query_string .= '&champ_affaire=' . urlencode($Id_affaire);
	if ($origine) $query_string .= '&champ_origine=' . urlencode($origine);
	if ($raison_sociale) $query_string .= '&champ_nomclient_fdc=' . urlencode($raison_sociale);
	if ($Reference) $query_string .= '&champ_reference=' . urlencode($Reference);
	//if ($Id_transac) $query_string .= '&champ_numdevis=' . urlencode($Id_transac);
	if ($agence) $query_string .= '&champ_agence=' . urlencode($agence);
	//if ($Id_transac) $query_string .= '&champ_commercial='. urlencode($Id_transac);
	if ($nom) $query_string .= '&champ_interlocuteur=' . urlencode($nom);
	if ($telephone) $query_string .= '&champ_telephone=' . urlencode($telephone);
	if ($mobile) $query_string .= '&champ_mobile=' . urlencode($mobile);
	if ($fax) $query_string .= '&champ_fax=' . urlencode($fax);
	if ($mail) $query_string .= '&champ_mailinterlocuteur=' . urlencode($mail);
	if ($Num_bcc) $query_string .= '&champ_numcommande_fdc=' . urlencode($Num_bcc);
	$pieces = explode("\r", $client_adresse);
    $client_adresse = "";
    foreach ($pieces as $adresse){
        if($adresse){
            $client_adresse .=$adresse."\n";
        }
    }
	if ($client_adresse) $query_string .= '&champ_adresse=' . urlencode($client_adresse);
	if ($Prix) $query_string .= '&champ_prix_ht=' . urlencode($Prix);
	if ($Pose) $query_string .= '&champ_pose_ht=' . urlencode($Pose);
	echo '<td class="externe"><A class="externe" href="doc.php?' . htmlentities($query_string) . '">Créer une feuille de cotes</A></td>';
}
if(!droit_utilisateur('adv') and ($Id_statut!=6)) $restriction_statut = " where Id_statut <> 6 ";
?>
            </tr>
        </table>
        <table class="requeteur">
           <tr>
              <td class="requet_right">Raison sociale</td>
              <td class="requet_left" ><? champ_ouvert_droit("N","Raison_sociale",$raison_sociale,60, 35, "N"); ?></td>
              <td class="requet_right">Interlocuteur</td>
               <td class="requet_left" colspan = 3>
                   <?drop_down_droit(droit("com", "adv"),"
                SELECT DISTINCT
                    concat(IFNULL(civilite.Lb_civilite,''),' ', IFNULL(contact.Prenom,''),' ',IFNULL(contact.Nom,'')) as Nom,
                    contact.Id_contact
                FROM
                    client,
                    contact
                LEFT OUTER JOIN civilite on (civilite.Id_civilite = contact.Id_civilite)
                WHERE
                    client.Id_client = contact.Id_client AND
                    client.Id_client = $Id_client ","Id_contact", "Id_contact", "Nom", $Id_contact, false, "affaire","O", "", " ");
                   ?>
               </td>
           </tr>
           <tr>
              <td class="requet_right"><Span class="oblig">Date de création</span></td>
              <td class="requet_left"><? champ_date_droit (droit("com", "adv"), "Date_crea", $Date_crea, "affaire", "O"); ?></td>
              <td class="requet_right"><Span class="oblig">Date de prévision/réalisation</span></td>
              <td class="requet_left"><? echo champ_date_droit (droit("com", "adv"), "Date_prev", $Date_prev, "affaire", "O")?></td>
           </tr>
           <tr>
              <td class="requet_right"><Span class="oblig">Statut</Span></td>
              <td class="requet_left"><? drop_down_droit($g_droit,"SELECT Id_statut, Lb_statut FROM statut $restriction_statut ","Id_statut", "Id_statut", "Lb_statut", $Id_statut, false, "affaire","O", "", " "); ?></td>
              <td class="requet_right"><Span class="oblig">Montant H.T.</Span></td>
              <td class="requet_left"><? champ_numeric_droit(droit("com", "ascom"), "Prix", $Prix, 2, 12, "affaire", "N") ?> <Span class="oblig">dont pose</Span> <? champ_numeric_droit($g_droit, "Pose", $Pose, 2, 12, "affaire", "N") ?></td>
           </tr>
           <tr>
              <td class="requet_right"><Span class="oblig">Nom de l'affaire</Span></td>
              <td class="requet_left"><? champ_ouvert_droit(droit("com", "adv"),"Commentaire",$Commentaire,30, 30, "O");?></td>
              <td class="requet_right">Commercial</td>
              <td class="requet_left"><? drop_down_droit("N","SELECT Id_utilisateur, Nom FROM utilisateur", "Id_utilisateur", "Id_utilisateur", "Nom", $Id_utilisateur, false, "affaire","N","", " "); ?></td>
           </tr>
           <tr>
              <td class="requet_right">N° de devis</td>
              <td class="requet_left"><? champ_ouvert_droit(droit("adv", "ascom"),"Id_transac",$Id_transac,30, 30,"N"); ?>(à saisir par Corinne)</td>
              <td class="requet_right">N° de BCC</td>
              <td class="requet_left"><? champ_ouvert_droit("adv","Num_bcc",$Num_bcc,30, 30,"N"); ?>(à saisir par Claudine)</td>
            </tr>
           <tr>
              <td class="requet_right">Référence de commande</td>
              <td class="requet_left"><? champ_ouvert_droit("adv","Reference",$Reference,30, 30,"N"); ?>(à saisir par ADV)</td>
              <td class="requet_right">Produits non NSI exclusivement</td>
              <td class="requet_left"><? champ_binaire_droit("com","Pnne","1",$Pnne); ?></td>
				  </tr>
           <tr>
<?
if ($g_droit == "O") {
?>
              <td class="requet_button" colspan=4">
                 <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
                 <input type="hidden" name="Id_affaire" value="<? echo $Id_affaire; ?>">
                 <input type="hidden" name="Id_interaction" value="<? echo $Id_interaction; ?>">
				 <input type="hidden" name="BUREAU" value="<? echo $fg_interlocuteur; ?>">
                 <input class="requeteur_button" type="submit" name="Submit" value="<? echo $titre_bouton ?>" OnClick="return champ_oblig('affaire')">
              </td>
<?
} else {
?>
              <td class='requet_button' colspan="2"></td>
<?
}
?>
           </tr>
        </table>
<?
if ($Id_affaire) {
?>
        <table class="requeteur">
           <tr>
              <td><iframe src="affaire_detail_interaction_list.php?Id_affaire=<? echo $Id_affaire; ?>" width=100% height=600 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
           </tr>
        </table>
<?
}
?>
      </td>
   </tr>
</table>
<? stop_refresh_form () ?>
</form>
<?
include ("ress/enpied.php");
?>