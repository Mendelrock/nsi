<?
include("ress/entete.php");
?>
<script src="ress/jquery.inputmask.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
   $("input[name=Telephone]").inputmask("99 99 99 99 99");
   $("input[name=Fax]").inputmask("99 99 99 99 99");
   $("input[name=Mobile]").inputmask("99 99 99 99 99");
});
</script>
<?

/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Mode création
/*   Id_client
/*  Mode Consultation
/*   Id_contact
/*
/* Internes
/*  $ACTE
/*  $Id_client
/*  $Id_civilite
/*  $Id_decision
/*  $Id_fonction
/*  $Nom
/*  $Prenom
/*  $Telephone
/*  $Fax
/*  $Mail
/*
/*------------------------------------------------*/

/*------------Verification Formulaire------------*/
if (($ACTE==1) or ($ACTE==2)) {
   reset ($_SESSION[champ_parametrable][contact]);
   while (list($i,$j) = each($_SESSION[champ_parametrable][contact])) {
      if (empty($$i) and ($j[obligatoire]=="O"))
         $message = "donnée ".$j[libelle]." manquante";
   }
}
/*----------- CREATION NOUVEAU CONTACT ---------------*/
if($ACTE==1){
        //Requete de Creation
        if (!$message) {
           $requete="INSERT INTO contact (Id_client,
                                          Adresse1,
                                          Adresse2,
                                          Adresse3,
                                          Cp,
                                          Ville,
                                          Decideur,
                                          Mobile,
                                          Id_civilite, 
                                          Nom, 
                                          Id_fonction, 
                                          Prenom, 
                                          Telephone, 
                                          Fax, 
                                          Id_decision, 
                                          Mail,
                                          Date_crea)
           VALUES($Id_client,
               ".My_sql_format($Adresse1).",
               ".My_sql_format($Adresse2).",
               ".My_sql_format($Adresse3).",
               ".My_sql_format($Cp).",
               ".My_sql_format($Ville).",
               ".My_sql_format($Decideur).",
               ".My_sql_format($Mobile).",
               ".My_sql_format($Id_civilite).",
               ".My_sql_format($Nom).",
               ".My_sql_format($Id_fonction).",
               ".My_sql_format($Prenom).",
               ".My_sql_format($Telephone).",
               ".My_sql_format($Fax).",
               ".My_sql_format($Id_decision).",
               ".My_sql_format($Mail).",
               now())";
           //Execution
           $resultat = new db_sql($requete);
           $Id_contact=db_sql::last_id ();
           set_refresh ("Id_contact|$Id_contact");
        }
}
/*----------------- UPDATE CONTACT ------------------*/
if($ACTE==2){
        if (!$message) {
           // Requete : UPDATE
           $requete="UPDATE contact SET
                     Id_civilite ='$Id_civilite',
                     Adresse1    =".My_sql_format($Adresse1).",
                     Adresse2    =".My_sql_format($Adresse2).",
                     Adresse3    =".My_sql_format($Adresse3).",
                     Cp          =".My_sql_format($Cp).",
                     Ville       =".My_sql_format($Ville).",
                     Decideur    =".My_sql_format($Decideur).",
                     Mobile      =".My_sql_format($Mobile).",
                     Nom         =".My_sql_format($Nom).",
                     Id_fonction =".My_sql_format($Id_fonction).",
                     Prenom      =".My_sql_format($Prenom).",
                     Id_decision =".My_sql_format($Id_decision).",
                     Telephone   =".My_sql_format($Telephone).",
                     Fax         =".My_sql_format($Fax).",
                     Mail        =".My_sql_format($Mail).",
                     Fg_supp     =".My_sql_format($Fg_supp)."
                     WHERE
                     Id_contact=$Id_contact";
           //Execution
           $resultat= new db_sql($requete);
           set_refresh ("Id_contact|$Id_contact");
        }
}
/*---------------- AFFICHAGE FICHE CONTACT---------*/
if ($message) {
// Pas de mise à jour
} else if ($Id_contact) {
        /*---------------------------------------------*/
        // Requete : SELECT
        $requete="SELECT
        contact.Id_client,
        contact.Adresse1,
        contact.Adresse2,
        contact.Adresse3,
        contact.Cp,
        contact.Ville,
        contact.Decideur,
        contact.Mobile,
        contact.Id_civilite,
        contact.Id_decision,
        contact.Id_fonction,
        contact.Nom,
        contact.Prenom,
        contact.Telephone,
        contact.Fax,
        contact.Mail,
        contact.Fg_supp,
        client.Raison_sociale
        FROM contact, client
        WHERE contact.Id_client=client.Id_client
        AND contact.Id_contact='$Id_contact'";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        $resultat->n();
        /*----------- Inititalisation des variables du formulaire --------*/
        //Valeur par défaut
        $Id_client=$resultat->f("id_client");
        $Adresse1=$resultat->f("adresse1");
        $Adresse2=$resultat->f("adresse2");
        $Adresse3=$resultat->f("adresse3");
        $Cp=$resultat->f("Cp");
        $Ville=$resultat->f("ville");
        $Decideur=$resultat->f("decideur");
        $Mobile=$resultat->f("mobile");
        $Nom=$resultat->f("nom");
        $Prenom=$resultat->f("prenom");
        $Telephone=$resultat->f("telephone");
        $Fax=$resultat->f("fax");
        $Mail=$resultat->f("mail");
        $raison_sociale=$resultat->f("raison_sociale");
        $ville=$resultat->f("ville");
        $client_telephone=$resultat->f("client_telephone");
        $Id_civilite=$resultat->f("id_civilite");
        $Id_fonction=$resultat->f("id_fonction");
        $Id_decision=$resultat->f("id_decision");
        $Fg_supp=$resultat->f("fg_supp");
} else {
        //*----------- Inititalisation des variables du formulaire --------*/
        //récupération du client de rattachement
        $requete="SELECT Raison_sociale FROM client
        WHERE Id_client='$Id_client'";
        $resultat = new db_sql($requete);
        while ($resultat->n()){
                $raison_sociale=$resultat->f("raison_sociale");
        }
        //Champs
        // $Id_client est paramètre
        $Adresse1="";
        $Adresse2="";
        $Adresse3="";
        $Cp="";
        $Ville="";
        $Decideur="N";
        $Mobile="";
        $Nom="";
        $Prenom="";
        $Telephone="";
        $Fax="";
        $Mail="";
        $Id_civilite="";
        $Id_fonction="";
        $Id_decision="";
}
if ($Id_contact) {
        //titre
        $titre="Interlocuteur";
        $titre_bouton="Enregistrer";
        //Action si validation formulaire
        $ACTE=2;
        if ($Id_decision <> 10) {
           $where_decision = " where Id_decision <> 10 ";
        }
} else {

        //Titre
        $titre="Nouveau interlocuteur";
        $titre_bouton="Créer";
        //Action si validation formulaire
        $ACTE=1;
        $where_decision = " where Id_decision <> 10 ";
}
/*------ Ecran du Requêteur-------*/
?>
<body class="application">
<form method="post" name ="contact" action="contact_detail.php?ACTE=<? echo $ACTE; ?>">
<table class="cadre_application">
   <tr>
      <td class="cadre_application" align="center" valign="middle">
         <table class="menu_haut">
            <tr>
                <td class="menu_haut"><? echo $titre; ?></td>
                <td class='externe'><A class='externe' href='client_detail.php?Id_client=<? echo $Id_client ?>'>Ouvrir le client</A></td>
<?
if(droit_utilisateur("com") and $Id_contact){
?>
                <td class='externe'><A class='externe' href='interaction_detail.php?Id_contact=<? echo $Id_contact ?>'>Créer une action</A></td>
<?
}
if (droit_utilisateur("bureau") and $Id_contact) {
?>
              <td class='externe'><A class='externe' href='affaire_detail.php?Id_contact=<?php echo $Id_contact; ?>&fg_interlocuteur=1'>Créer une affaire</A></td>
<?
}
?>
             </tr>
         </table>
         <table class="requeteur">
            <tr>
               <td class="requet_right">Raison Sociale</td>
               <td class="requet_left" colspan = 3><? champ_ouvert_droit("N","Raison_sociale",$raison_sociale,60, 35, "N"); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Adresse1][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Adresse1",$Adresse1,100, 35,$_SESSION[champ_parametrable][contact][Adresse1][obligatoire]); ?></td>
            </tr>
            <tr>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Id_civilite][libelle]; ?></td>
               <td class="requet_left"><? drop_down_droit ("com", "SELECT Id_civilite, Lb_civilite FROM civilite", "Id_civilite", "id_civilite", "lb_civilite", $Id_civilite, false, "contact", $_SESSION[champ_parametrable][contact][Id_civilite][obligatoire], "", " "); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Telephone][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Telephone",$Telephone,60, 20,$_SESSION[champ_parametrable][contact][Telephone][obligatoire]); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Adresse2][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Adresse2",$Adresse2,100, 35,$_SESSION[champ_parametrable][contact][Adresse2][obligatoire]); ?></td>
            </tr>
            <tr>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Nom][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Nom",$Nom,30, 30,$_SESSION[champ_parametrable][contact][Nom][obligatoire]); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Fax][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Fax",$Fax,60, 20,$_SESSION[champ_parametrable][contact][Fax][obligatoire]); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Adresse3][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Adresse3",$Adresse3,100, 35,$_SESSION[champ_parametrable][contact][Adresse3][obligatoire]); ?></td>

            </tr>
            <tr>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Prenom][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Prenom",$Prenom,30, 30,$_SESSION[champ_parametrable][contact][Prenom][obligatoire]); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Mobile][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Mobile",$Mobile,60, 20,$_SESSION[champ_parametrable][contact][Mobile][obligatoire]); ?></td>

               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Cp][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Cp",$Cp,5, 5,$_SESSION[champ_parametrable][contact][Cp][obligatoire]); ?></td>
            </tr>
            <tr>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Id_decision][libelle]; ?></td>
               <td class="requet_left"><? drop_down_droit ("com", "SELECT Id_decision, Lb_decision FROM decision".$where_decision, "Id_decision", "id_decision", "lb_decision", $Id_decision, false, "contact", $_SESSION[champ_parametrable][contact][Id_decision][obligatoire], "", " "); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Id_fonction][libelle]; ?></td>
               <td class="requet_left"><? drop_down_droit ("com", "SELECT Id_fonction, Lb_fonction FROM fonction", "Id_fonction", "id_fonction", "lb_fonction", $Id_fonction, false, "contact", $_SESSION[champ_parametrable][contact][Id_fonction][obligatoire], "", " "); ?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Ville][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Ville",$Ville,60, 35,$_SESSION[champ_parametrable][contact][Ville][obligatoire]); ?></td>
            </tr>
            <tr>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Decideur][libelle]; ?></td>
               <td class="requet_left"><?drop_down_droit("com","", "Decideur", "", "", $Decideur,false, "com",$_SESSION[champ_parametrable][contact][Decideur][obligatoire], "O|N", "OUI|NON");?></td>
               <td class="requet_right"><? echo $_SESSION[champ_parametrable][contact][Mail][libelle]; ?></td>
               <td class="requet_left"><? champ_ouvert_droit("com","Mail",$Mail,60, 35,$_SESSION[champ_parametrable][contact][Mail][obligatoire]); ?></td>
<?
if(droit_utilisateur("com")){
?>
               <td class="requet_button" colspan = 2>
                  <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
                  <input type="hidden" name="Fg_supp"    value="<? echo $Fg_supp ?>">
                  <input type="hidden" name="Id_contact" value="<? echo $Id_contact ?>">
                  <input type="hidden" name="Id_client"  value="<? echo $Id_client ?>">
                  <input class="requeteur_button" type="submit" name="Submit" value="Supprimer" OnClick="javascript: if(confirm('Confirmez vous la suppression ?')) {document.getElementsByName('Fg_supp')[0].value = 1; return true} else {return false} ;">
                  <input class="requeteur_button" type="submit" name="Submit" value="<? echo $titre_bouton; ?>" OnClick="return champ_oblig('contact')">
               </td>
<?
}else{
?>
               <td colspan = 2></td>
<?
}
?>
            </tr>
         </td>
      </tr>
   </table>
<? stop_refresh_form () ?>
</form>
<?
if ($Id_contact) {
?>
   <table class="requeteur">
      <tr>
         <td><iframe src="contact_detail_interac_list.php?Id_client=<? echo $Id_client ?>&Id_contact=<? echo $Id_contact ?>" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
      </tr>
   </table>
<?
}
?>
<script langage="javascript">var tab_val=form_ref();</script>
<?
include ("ress/enpied.php");
?>
