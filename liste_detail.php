<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Mode création
/*  Mode Consultation
/*   id_liste
/*
/* Internes
/*   ACTE = 1 
/*   id_liste qui indique si mise à jour ou pas
/*
/*------------------------------------------------*/
/*----------- CREATION Interaction ---------------*/


if ($ACTE==1) {
   if (!$date_debut) {
      $message = "Date de début obligatoire";
   } else if (!$date_fin) {
      $message = "Date de fin obligatoire";
   } else if (!$lb_liste) {
      $message = "Libellé de liste obligatoire";
   } else if (!$id_utilisateur) {
      $message = "Vendeur obligatoire";
   } else if (!$id_liste) {
      $requete = "
         INSERT INTO liste ( 
            id_utilisateur,
            lb_liste,
            date_debut,
            date_fin)
         VALUES( 
            $id_utilisateur,
            ". My_sql_format($lb_liste).",
            ". My_sql_format($date_debut).",
            ". My_sql_format($date_fin) ." )";
      $resultat = new db_sql($requete);
      $id_liste = db_sql::last_id ();
      set_refresh ("id_liste|$id_liste");
   } else {
      $requete="
         UPDATE liste
         SET 
            id_utilisateur='$id_utilisateur',
            lb_liste=". My_sql_format($lb_liste) .",
            date_debut=". My_sql_format($date_debut) .",
            date_fin=". My_sql_format($date_fin) ."
         WHERE
            id_liste = $id_liste ";
      $resultat= new db_sql($requete);
      set_refresh ("id_liste|$id_liste");
   }
}
/*---------------- AFFICHAGE FICHE LISTE---------*/
if ($id_liste and !$ACTE) {
   $requete="
       SELECT
            liste.id_liste,
            liste.id_utilisateur,
            liste.lb_liste,
            liste.date_debut,
            liste.date_fin
        FROM
            liste
        WHERE
            liste.id_liste=$id_liste";
   $resultat = new db_sql($requete);
   if ($resultat->n()){
        $id_utilisateur	= $resultat->f("id_utilisateur");
        $lb_liste			= $resultat->f("lb_liste");
        $date_debut		= $resultat->f("date_debut");
        $date_fin			= $resultat->f("date_fin");
   }
} else if (!$id_liste) {
   $id_utilisateur	   = $_SESSION[liste_list_id_utilisateur];
}

if (droit_utilisateur("com") and (!droit_utilisateur("secto"))) {
   $g_droit = "O";
} else {
   $g_droit = "N";
}

/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="liste" action="liste_detail.php?ACTE=1">
        <table class="menu_haut">
           <tr>
              <td class="menu_haut">Liste <? echo $lb_liste; ?></td>
           </tr>
        </table>
        <table class="requeteur">
           <tr>
              <td class="requet_right"><Span class="oblig">Nom</span></td>
              <td class="requet_left"><? champ_ouvert_droit($g_droit,"lb_liste",$lb_liste,60, 35, "N"); ?></td>
              <td class="requet_right"><Span class="oblig">Période</span></td>
              <td class='requet_left'><? echo champ_date_droit ($g_droit, "date_debut", $date_debut, "liste", "O")?><Span class="oblig">à</span><? echo champ_date_droit ($g_droit, "date_fin", $date_fin, "liste", "O")?></td>
              <td class="requet_right"><Span class="oblig">Commercial</span></td>
              <td class="requet_left"><? drop_down_droit($g_droit,"SELECT 
                                                            Id_utilisateur,
                                                            Nom 
                                                          FROM 
                                                            utilisateur, 
                                                            autorisation, 
                                                            droit
                                                          WHERE 
                                                            autorisation.id_profil = utilisateur.id_profil and
                                                            autorisation.id_droit = droit.id_droit and
                                                            droit.code = 'secto'
                                                          ORDER BY 
                                                            Nom ASC", "id_utilisateur", "Id_utilisateur", "nom", $id_utilisateur,false, "client","N","", " "); ?></td>
<?
if (($g_droit == "O")) {
?>
              <td class="requet_right">
                 <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
                 <input type="hidden" name="id_liste" value="<? echo $id_liste ?>">
                 <input class="requeteur_button" type="submit" name="Submit" value="Enregistrer" OnClick="return champ_oblig('liste')">
<?
}
?>
              </td>
           </tr>
        </table>
<?
if ($id_liste) {
	if (is_array($_SESSION[client_à_ajouter_a_la_liste])) {
		foreach($_SESSION[client_à_ajouter_a_la_liste] as $id_client) {
			$client = new db_sql("select id_client from liste_client where id_client = $id_client and id_liste = $id_liste");
         if ($client->n()) {
         	$doublons++;
         } else {
				$client = new db_sql("insert into liste_client(id_client, id_liste) values ( $id_client , $id_liste )");
         	$insérés++;
         }
         unset($_SESSION[client_à_ajouter_a_la_liste]);
		}
	}
   if ($insérés) 
   	$message = $insérés." client(s) inséré(s)   ";
   if ($doublons) 
   	$message .= $doublons." client(s) déjà dans la liste";

	foreach($_POST as $champ => $valeur) {
		if ((substr($champ,0,7) == "client_") and ($valeur == 1)) {
			$client = new db_sql("delete from liste_client where id_client = ".substr($champ,7)." and id_liste = $id_liste");
         $supprimé++;
      }      
	}
   if ($supprimé) 
   	$message = $supprimé." client(s) supprimés(s)";
	
if ($g_droit == 'O') {
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
	white-space: normal;" type="submit" value="Supprimer les clients en les sélectionnant puis enregistrez la liste">
<?
}
?>
   <table class="resultat">
      <tr>
<?
if ($g_droit == 'O') {
?>
			<td class="resultat_list" width=5%><input type ="checkbox" name ="client" value="1" id = "sel"></td>
<?
}
?>
         <td class="resultat_tittle">Client</td>
         <td class="resultat_tittle">Traité</td>
      </tr>
<?
   $requete="
		SELECT distinct
			client.id_client,
			client.raison_sociale,
			interaction.id_client as traite
		FROM
			liste
         inner join liste_client on (liste_client.id_liste = liste.id_liste)
			inner join client on (liste_client.id_client = client.id_client)
			left outer join interaction on (interaction.id_client = client.id_client and interaction.date_prev >= liste.date_debut and interaction.date_prev <= liste.date_fin)
		WHERE
			liste_client.id_liste = $id_liste";
   $client = new db_sql($requete);
while ( $client->n()) {
	?>
	      <tr>
	<?
	if ($g_droit == 'O') {
	?>
				<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><input type ="checkbox" name ="client_<? echo $client->f('id_client') ?>" value="1" class = "sel"></td>
	<?
	}
	?>
				<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='client_detail.php?Id_client=<? echo $client->f('id_client') ?>' target='droite'><? echo substr($client->f('raison_sociale'),0,50) ?></a></td>
				<td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo ($client->f('traite')?"X":"") ?></td>
	      </tr>
	<?
	$z++;
}
?>
      <tr>
         <td class='resultat_footer' colspan='3'>Cliquer sur le nom du client pour l'ouvrir</td>
      </tr>
   </table>
<script>
	$('#sel').change(function() {
		$('.sel').prop("checked", $('#sel').prop("checked") );
	})
</script>
<?
}
stop_refresh_form () ?>
</form>
<script langage="javascript">var tab_val=form_ref();</script>
<?
include ("ress/enpied.php");
?>
