<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Mode création
/*   Id_contact
/*   Id_affaire (optionnel si création à partir d'une affaire
/*  Mode Consultation
/*   Id_interaction
/*
/* Internes
/*  $ACTE
/*  $Id_interaction
/*  $Id_contact
/*  $Id_affaire
/*  $Date_prev
/*  $Id_teneur
/*  $Notes
/*
/*------------------------------------------------*/
/*----------- CREATION Interaction ---------------*/

function historise_interaction ($Id_interaction) {
	$requete="
	INSERT INTO 
		interaction_histo
	SELECT 
		interaction . * , 
		NOW( ) 
	FROM 
		interaction
	WHERE 
		id_interaction = '$Id_interaction'";
	$resultat= new db_sql($requete);
}

if ($ACTE) {
	if (($Id_teneur == 2 or $Id_teneur == 5 or $Id_teneur == 6) and (!$Id_affaire)) {
		$message = "Merci de spécifier l'affaire";
	}
	if (!$Date_prev or !$Id_teneur) {
		$message = "Erreur technique";
	}	
}

if (!$message) {
	if ($ACTE==1) {
      //Traitement du champ commentaire
      //if ($Notes) {
      if ($Notes and !$Date_prev) {
         $Date_prev=aujourdhui();
      }
      $Heure = $Heure*1;
      if (($Heure > 23) or ($Heure < 1)) $Heure = "";
      $Id_client=req_sim("select Id_client from contact where Id_contact = $Id_contact","Id_client");
      $requete = "
         INSERT INTO interaction ( 
            Id_utilisateur,
            Id_contact,
            Date_crea,
            Date_prev,
            Heure,
            Id_teneur,
            Notes,
            Commentaire,
            Id_affaire,
            Id_client,
            Date_clot,
            Argumente)
         VALUES( 
            $_SESSION[id_utilisateur],
            '$Id_contact',
            now(),
            '$Date_prev',
            ". My_sql_format($Heure).",
            '$Id_teneur',
            ". My_sql_format($Notes) .",
            ". My_sql_format($Commentaire) .",
            ". My_sql_format($Id_affaire) .",
            $Id_client,
            ".($Notes ? "NOW()" : "null").",
            ". My_sql_format($Argumente) ." )";
      $resultat = new db_sql($requete);
      $Id_interaction = db_sql::last_id ();
      historise_interaction ($Id_interaction);
      set_refresh ("Id_interaction|$Id_interaction");
	}
	/*----------------- UPDATE Interaction ------------------*/
	if ($ACTE==2) {
	   //Traitement du champ commentaire
		//if ($Notes and !req_sim("select Notes from interaction where id_interaction = $Id_interaction","Notes")) {
      if ($Notes and !$Date_prev) {
         $Date_prev=aujourdhui();
      }
      $Heure = $Heure*1;
      if (($Heure > 23) or ($Heure < 1)) $Heure = "";
      if ($fait == "A faire" and $Notes) $add = " Date_clot = NOW(), ";
      //Execution
      $requete="
         UPDATE interaction
         SET $add
            Date_prev='$Date_prev',
            Heure=". My_sql_format($Heure) .",
            Notes=". My_sql_format($Notes) .",
            Commentaire=". My_sql_format($Commentaire) .",
            Id_teneur='$Id_teneur',
            Id_affaire=". My_sql_format($Id_affaire) .",
            Argumente=". My_sql_format($Argumente) ."
         WHERE
            Id_interaction='$Id_interaction'";
      $resultat= new db_sql($requete);
      historise_interaction ($Id_interaction);
      set_refresh ("Id_interaction|$Id_interaction");
      
      //gestion de l'interaction suivante
      //*********************************
      if ($Id_interaction_suivant) {
         if (!$Id_teneur_suivant) {
            $message = "Action suivante sans type";
         } else if (!$Date_prev_suivant) {
            $message = "Action suivante sans date de prévision";
         } else {
            $Heure_suivant = $Heure_suivant*1;
            if (($Heure_suivant > 23) or ($Heure_suivant < 1)) $Heure_suivant = "";
            //Execution
            $requete = "
               UPDATE interaction
               SET
                  Date_prev='$Date_prev_suivant',
                  Heure=". My_sql_format($Heure_suivant) .",
                  Commentaire=". My_sql_format($Commentaire_suivant) .",
                  Id_teneur='$Id_teneur_suivant'
               WHERE
                  Id_interaction = '$Id_interaction_suivant'";
				$resultat= new db_sql($requete);
				historise_interaction ($Id_interaction_suivant);
         }
      } else {
         if ($Date_prev_suivant) {
            if (!$Id_teneur_suivant) {
               $message = "Nouvelle action suivante sans type";
            } else {
               $Heure_suivant = $Heure_suivant*1;
               if (($Heure_suivant > 23) or ($Heure_suivant < 1)) $Heure_suivant = "";
               $Id_client=req_sim("select Id_client from contact where Id_contact = $Id_contact","Id_client");
               //Execution
               $requete = "
               INSERT INTO interaction (
                  Id_interaction_pere,
                  Id_utilisateur,
                  Id_contact,
                  Date_crea,
                  Date_prev,
                  Heure,
                  Id_teneur,
                  Commentaire,
                  Id_affaire,
                  Id_client)
               VALUES(
                  $Id_interaction,
                  $_SESSION[id_utilisateur],
                  '$Id_contact',
                  now(),
                  '$Date_prev_suivant',
                  ". My_sql_format($Heure_suivant).",
                  '$Id_teneur_suivant',
                  ". My_sql_format($Commentaire_suivant) .",
                  ". My_sql_format($Id_affaire) .",
                  $Id_client)";
               $resultat = new db_sql($requete);
      			$Id_interaction_suivant=db_sql::last_id ();
					historise_interaction ($Id_interaction_suivant);
            }
         } else if ($Id_teneur_suivant) {
            $message = "Nouvelle action suivante sans date de prévision";
         }
      } 
	}
}
/*---------------- AFFICHAGE FICHE CLIENT---------*/
if ($Id_interaction) {
   // Requete : SELECT
   $requete="
       SELECT
            interaction.Id_utilisateur,
            interaction.Id_contact,
            interaction.Id_client,
            concat(civilite.Lb_civilite,' ', contact.Prenom,' ', contact.Nom) as Nom,
            client.Raison_sociale,
            interaction.Date_crea,
            interaction.Date_prev,
            interaction.Id_teneur,
            interaction.Notes,
            interaction.Commentaire,
            interaction.Id_affaire,
            interaction.Heure,
            interaction.Date_clot,
            interaction.Argumente,
            now() as now
        FROM
            interaction,
            client,
            contact 
        LEFT OUTER join civilite on (civilite.Id_civilite = contact.Id_civilite)
        WHERE
            interaction.Id_client=client.Id_client AND
            interaction.Id_contact=contact.Id_contact AND
            interaction.Id_interaction = $Id_interaction";
   /*----------- EXECUTION ---------*/
   $resultat = new db_sql($requete);
   while($resultat->n()){
   /*----------- Inititalisation des variables du formulaire --------*/
   //Valeur par défaut
        $Id_utilisateur = $resultat->f("Id_utilisateur");
        $Id_contact     = $resultat->f("Id_contact");
        $Id_affaire     = $resultat->f("Id_affaire");
        $Id_client      = $resultat->f("Id_client");
        $Heure=$resultat->f("Heure");
        $nom=$resultat->f("nom");
        $raison_sociale=$resultat->f("raison_sociale");
        $ville=$resultat->f("ville");
        $Date_crea=$resultat->f("date_crea");
        $Date_prev=$resultat->f("date_prev");
        $Id_teneur=$resultat->f("id_teneur");
        $Notes=$resultat->f("notes");
        $Argumente=$resultat->f("Argumente");
        $Commentaire=$resultat->f("Commentaire");
        $Date_clot=substr($resultat->f("Date_clot"),0,10);
        $now=substr($resultat->f("now"),0,10);
   }
   $requete="
       SELECT
            interaction.Id_interaction,
            interaction.Date_prev,
            interaction.Id_teneur,
            interaction.Commentaire,
            interaction.Notes,
            interaction.Heure
        FROM
            interaction
        WHERE
            interaction.Id_interaction_pere = '$Id_interaction'";
   /*----------- EXECUTION ---------*/
   $resultat = new db_sql($requete);
   if($resultat->n()){
   /*----------- Inititalisation des variables du formulaire --------*/
   //Valeur par défaut
        $Id_interaction_suivant=$resultat->f("Id_interaction");
        $Heure_suivant=$resultat->f("Heure");
        $Date_prev_suivant=$resultat->f("date_prev");
        $Id_teneur_suivant=$resultat->f("id_teneur");
        $Commentaire_suivant=$resultat->f("Commentaire");
        $Notes_suivant=$resultat->f("notes");
   } else {
        $Heure_suivant="";
        $Date_prev_suivant="";
        $Id_teneur_suivant="";
        $Commentaire_suivant=$Notes;
   }
   //titre
   $titre="Action";
   $titre_bouton="Enregistrer";
   //Action si validation formulaire
   $ACTE_form=2;
} else {
   // Requete : SELECT
   $requete="
        SELECT
            contact.Id_client,
            contact.Nom,
            client.Raison_sociale,
            client.Ville
        FROM
            client,
            contact
        WHERE
            contact.Id_client = client.Id_client AND
            contact.Id_contact = $Id_contact";
        /*----------- EXECUTION ---------*/
   $resultat = new db_sql($requete);
   while($resultat->n()){
        /*----------- Inititalisation des variables du formulaire --------*/
        //Valeur par défaut
        // Id_contact est un paramètre
        // Id_affaire est un paramètre
        $Id_client=$resultat->f("id_client");
        $nom=$resultat->f("nom");
        $raison_sociale=$resultat->f("raison_sociale");
        $ville=$resultat->f("ville");
   }
   $Id_utilisateur = $_SESSION[id_utilisateur];
   $Date_crea=aujourdhui();
   $Date_prev="";
   $Id_teneur="";
   $Notes="";
   $Heure="";
   $Commentaire="";
   //Titre
   $titre="Nouvelle Action";
   $titre_bouton="Créer";
   //Action si validation formulaire
   $ACTE_form=1;
}

if (droit_utilisateur("com") and ($Id_utilisateur == $_SESSION[id_utilisateur])) {
   $g_droit = "O";
} else {
   $g_droit = "N";
}

/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="interaction" action="interaction_detail.php?ACTE=<? echo $ACTE_form; ?>">
<table class="cadre_application">
   <tr>
      <td class="cadre_application" align="center" valign="middle">
        <table class="menu_haut">
           <tr>
              <td class="menu_haut"><? echo $titre; ?></td>
              <td class='externe'><A class='externe' href='client_detail.php?Id_client=<? echo $Id_client?>'>Ouvrir le client</A></td>
              <td class='externe'><A class='externe' href='contact_detail.php?Id_contact=<? echo $Id_contact?>'>Ouvrir l'interlocuteur</A></td>
<?
if ($Id_interaction and $Id_affaire) {
?>
              <td class='externe'><A class='externe' href='affaire_detail.php?Id_affaire=<? echo $Id_affaire?>'>Ouvrir l'affaire</A></td>
<?
}
if (droit_utilisateur("com") and $Id_interaction and !$Id_affaire) {
?>
              <td class='externe'><A class='externe' href='affaire_detail.php?Id_interaction=<? echo $Id_interaction?>'>Créer une affaire</A></td>
<?
}
if (($g_droit == "O") and $Id_interaction and $Id_affaire) {
?>
              <td class='externe'><A class='externe' href='interaction_detail.php?Id_affaire=<? echo $Id_affaire?>&Id_contact=<? echo $Id_contact?>'>Créer une action</A></td>
<?
}
?>
           </tr>
        </table>
<?
//Si le commentaire n'est pas vide date_prev est non modifiable
if ($Notes) {
   $droit_prev = $g_droit;
   $statut = "Fait";
   $fg_note_oblig="O";
} else {
   $droit_prev = $g_droit;
   $statut = "A faire";
   $fg_note_oblig="N";
}
?>
        <table class="requeteur">
           <tr>
              <td class="requet_right">Raison sociale</td>
              <td class="requet_left" ><? champ_ouvert_droit("N","Raison_sociale",$raison_sociale,60, 35, "N"); ?></td>
              <td class="requet_right">Interlocuteur</td>
              <td class="requet_left"><? champ_ouvert_droit("N","contact",$nom,30, 30,"N"); ?></td>
           </tr>
           <tr>
              <td class="requet_right">Statut</td>
              <td class="requet_left"><? echo champ_ouvert_droit("N","fait",$statut,8, 8,"N") ?></td>
              <td class="requet_right"><Span class="oblig">Date de prévision/réalisation</span></td>
              <td class='requet_left'><? echo champ_date_droit ($droit_prev, "Date_prev", $Date_prev, "interaction", "O").champ_numeric_droit($droit_prev, "Heure", $Heure, 0, 2, "interaction", "N") ?>h</td>
           </tr>
           <tr>
              <td class="requet_right"><? if ($Id_teneur == 2 or $Id_teneur == 5 or $Id_teneur == 6) { ?><Span class="oblig"><? } ?>Affaire concernée<? if ($Id_teneur == 2 or $Id_teneur == 5 or $Id_teneur == 6) { ?></Span><? } ?></td>
              <td class="requet_left" ><? drop_down_droit($g_droit,"SELECT Id_affaire, Commentaire as libelle FROM affaire where (Id_contact= '$Id_contact' AND Id_client = '$Id_client' AND Id_statut in (1,5)) or (Id_affaire = '".$Id_affaire."')","Id_affaire", "Id_affaire", "libelle", $Id_affaire, false, "interaction","N","","|"); ?></td>
              <td class="requet_right"><Span class="oblig">Type</Span></td>
              <td class="requet_left"><? drop_down_droit($g_droit,"SELECT distinct teneur.Id_teneur, teneur.Lb_teneur FROM teneur, teneur_profil where teneur.id_teneur =  teneur_profil.id_teneur and (teneur_profil.id_profil = $_SESSION[id_profil] or teneur.Id_teneur='$Id_teneur') ORDER BY Lb_teneur ASC", "Id_teneur", "Id_teneur", "Lb_teneur", $Id_teneur,false, "interaction","O", "", " "); ?></td>
           </tr>
           <tr>
              <td class="requet_right">Commercial</td>
              <td class="requet_left"><? drop_down_droit("N","SELECT Id_utilisateur,Nom FROM utilisateur", "Id_utilisateur", "Id_utilisateur", "nom", $Id_utilisateur,false, "client","N","", " "); ?></td>
              <td class="requet_right" valign = top>Contact argumenté</td>
              <td class="requet_left"><? champ_binaire_droit ($g_droit,"Argumente", 1, $Argumente); ?></td>
           </tr>
           <tr>
              <td class="requet_right" valign = top>Commentaire</td>
              <td class="requet_left" colspan="3"><? text_area_droit ($g_droit,"Commentaire", 80, 4, 255, $Commentaire,'N'); ?></td>
           </tr>
           <tr>
              <td class="requet_right" valign = top>Compte-rendu</td>
              <td class="requet_left" colspan="3"><? text_area_droit ($g_droit,"Notes", 80, 4, 255, $Notes, $fg_note_oblig); ?></td>
           </tr>
<?
if ($Notes) {
   if ($g_droit == "O" and !$Notes_suivant) {
      $droit_suiv = "O";
   }

?>
        </table>
        <table class="menu_haut">
           <tr>
              <td class="menu_haut">Action suivante</td>
           </tr>
        </table>
        <table class="requeteur">
           <tr>
              <td class="requet_right">Date de prévision</td>
              <td class='requet_left'><? echo champ_date_droit ($droit_suiv, "Date_prev_suivant", $Date_prev_suivant, "interaction", "N").champ_numeric_droit($g_droit, "Heure_suivant", $Heure_suivant, 0, 2, "interaction", "N") ?>h</td>
              <td class="requet_right">Type</td>
              <td class="requet_left"><? drop_down_droit($g_droit,"SELECT distinct teneur.Id_teneur, teneur.Lb_teneur FROM teneur, teneur_profil where teneur.id_teneur =  teneur_profil.id_teneur and (teneur_profil.id_profil = $_SESSION[id_profil] or teneur.Id_teneur='$Id_teneur_suivant') ORDER BY Lb_teneur ASC", "Id_teneur_suivant", "Id_teneur", "Lb_teneur", $Id_teneur_suivant,false, "interaction","N", "", " "); ?></td>
           </tr>
           <tr>
              <td class="requet_right" valign = top>Commentaire</td>
              <td class="requet_left" colspan="3"><? text_area_droit ($g_droit,"Commentaire_suivant", 80, 4, 255, $Commentaire_suivant,'N'); ?></td>
           </tr>
<?
}
?>

           <tr>
              <td class="requet_button" colspan="3"></td>
              <td class="requet_button" colspan="1">
<?
if (($g_droit == "O")) {
?>
                 <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
                 <input type="hidden" name="Date_crea" value="<? echo $Date_crea; ?>">
                 <input type="hidden" name="Id_interaction_suivant" value="<? echo $Id_interaction_suivant; ?>">
                 <input type="hidden" name="Id_interaction" value="<? echo $Id_interaction; ?>">
                 <input type="hidden" name="Id_contact"     value="<? echo $Id_contact; ?>">
                 <input class="requeteur_button" type="submit" name="Submit" value="<? echo $titre_bouton ?>" OnClick="return champ_oblig('interaction')">
<?
}
?>
              </td>
           </tr>
        </table>
<?
if ($Id_affaire) {
?>
        <table class="requeteur">
           <tr>
              <td><iframe src="interaction_detail_list_result.php?Id_affaire=<? echo $Id_affaire; ?>&Id_interaction=<? echo $Id_interaction; ?>" width=100% height=380 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
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
<script langage="javascript">var tab_val=form_ref();</script>
<?
include ("ress/enpied.php");
?>
