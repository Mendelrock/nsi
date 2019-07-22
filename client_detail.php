<?
include("ress/entete.php");
?>
<script src="ress/jquery.inputmask.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
   $("input[name=Telephone]").inputmask("99 99 99 99 99");
   $("input[name=Fax]").inputmask("99 99 99 99 99");
});
</script>
<?
/*------------Verification Formulaire------------*/
if(($ACTE==1) or ($ACTE==2)){
   reset ($_SESSION[champ_parametrable][client]);
   while (list($i,$j) = each($_SESSION[champ_parametrable][client])) {
      if (empty($$i) and ($j[obligatoire]=="O"))
         $message = "donn‚e ".$j[libelle]." manquante";
   }
//   if (!$message) {
//           if ((req_sim ("select count(1) as compte from code_naf where Code_naf = ".My_sql_format($Code_naf),"compte")==0) and $Code_naf) {
//              $message = "Code Naf inexistant";
//           }
//   }
}
/*----------- CREATION NOUVEAU CLIENT ---------------*/
if($ACTE==1){

        if (!$message) {
           if ((req_sim ("select count(1) as compte from client where Siret = ".My_sql_format($Siret),"compte")>0)  and ($_SESSION[champ_parametrable][client][Siret][obligatoire]=="O")) {
              $message = "Siret d‚j… existant dans la base";
           }
        }
        if (!$message) {
           //Requete de Creation
           $requete="INSERT INTO client (Siret, Raison_sociale, Adresse1, Adresse2, Adresse3, Cp, Ville, Telephone, Fax, Code_naf, Tva_intra, Code_effectif, Id_type,  Prio, Groupe)
           VALUES(".My_sql_format($Siret).",
               ".My_sql_format($Raison_sociale).",
               ".My_sql_format($Adresse1).",
               ".My_sql_format($Adresse2).",
               ".My_sql_format($Adresse3).",
               ".My_sql_format($Cp).",
               ".My_sql_format($Ville).",
               ".My_sql_format($Telephone).",
               ".My_sql_format($Fax).",
               ".My_sql_format($Code_naf).",
               ".My_sql_format($Tva_intra).",
               ".My_sql_format($Code_effectif).",
               ".My_sql_format($Id_type).",
               ".My_sql_format($Prio).",
               ".My_sql_format($Groupe).")";
           //Execution
           $resultat = new db_sql($requete);
           $Id_client=db_sql::last_id ();
           if(droit_utilisateur("secto")){
              $requete="INSERT INTO portefeuille (Id_utilisateur, Id_client)
              VALUES(".My_sql_format($_SESSION[id_utilisateur]).",
               ".My_sql_format($Id_client).")";
              //Execution
              $resultat = new db_sql($requete);
           }
           set_refresh ("Id_client|$Id_client");
        }
}
/*----------------- UPDATE CLIENT ------------------*/
if($ACTE==2){

        if (!$message) {
           // Requete : UPDATE
           $requete="UPDATE client SET
           Siret         =".My_sql_format($Siret).",
           Raison_sociale=".My_sql_format($Raison_sociale).",
           Adresse1      =".My_sql_format($Adresse1).",
           Adresse2      =".My_sql_format($Adresse2).",
           Adresse3      =".My_sql_format($Adresse3).",
           Cp            =".My_sql_format($Cp).",
           Code_naf      =".My_sql_format($Code_naf).",
           Ville         =".My_sql_format($Ville).",
           Tva_intra     =".My_sql_format($Tva_intra).",
           Prio          =".My_sql_format($Prio).",
           Groupe        =".My_sql_format($Groupe).",
           Telephone     =".My_sql_format($Telephone).",
           Code_effectif =".My_sql_format($Code_effectif).",
           Id_type       =".My_sql_format($Id_type).",
           Fax           =".My_sql_format($Fax)."
           WHERE Id_client=$Id_client";
           //Execution
           $resultat= new db_sql($requete);
           set_refresh ("Id_client|$Id_client");
        }
}
/*---------------- AFFICHAGE FICHE CLIENT---------*/
if ($message) {
} else if ($Id_client) {
        /*---------------------------------------------*/
        // Requete : SELECT
        $requete="SELECT
        Siret,
        Raison_sociale,
        Adresse1,
        Adresse2,
        Adresse3,
        Cp,
        Code_naf,
        Ville,
        Telephone,
        Tva_intra,
        Prio,
        Id_type,
        Groupe,
        Code_effectif,
        Fax
        FROM client
        WHERE Id_client=$Id_client";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        $resultat->n();
        /*----------- Inititalisation des variables du formulaire --------*/
        //Valeur par défaut
        $Siret=$resultat->f("siret");
        $Raison_sociale=$resultat->f("raison_sociale");
        $Prio=$resultat->f("prio");
        $Code_naf=$resultat->f("code_naf");
        $Adresse1=$resultat->f("adresse1");
        $Adresse2=$resultat->f("adresse2");
        $Adresse3=$resultat->f("adresse3");
        $Tva_intra=$resultat->f("tva_intra");
        $Ville=$resultat->f("ville");
        $Telephone=$resultat->f("telephone");
        $Fax=$resultat->f("fax");
        $Groupe=$resultat->f("groupe");
        $Code_effectif=$resultat->f("code_effectif");
        $Id_type=$resultat->f("id_type");
        $Cp=$resultat->f("cp");
        //titre
        // Demande de Laurent TISSOT
        //if ($Id_client > 20809) {
        //   $tissot = "_tissot";
        //}
        $titre="Client";
        $titre_bouton="Enregistrer";
        //Action si validation formulaire
        $ACTE=2;
} else {
        //*----------- Inititalisation des variables du formulaire --------*/
        //Champs
        $Siret="";
        $Raison_sociale="";
        $Prio="";
        $Code_naf="";
        $Adresse1="";
        $Adresse2="";
        $Adresse3="";
        $Tva_intra=1;
        $Ville="";
        $Telephone="";
        $Fax="";
        $Code_effectif="";
        $Id_type="";
        $Cp="";
        $Groupe="";
        //Titre
        //$tissot = "_tissot";
        $titre = "Nouveau Client";
        $titre_bouton = "Cr&eacute;er";
        //Action si validation formulaire
        $ACTE=1;
}
/*------ Ecran du Requˆteur-------*/
?>

<body class="application">
<form method="post" name ="client" action="client_detail.php?ACTE=<? echo $ACTE; ?>">
<table class="cadre_application"><tr><td class="cadre_application" align="center" valign="middle">
   <table class="menu_haut">
       <tr>
          <td class="menu_haut"><? echo $titre; ?></td>
<?
if (droit_utilisateur("com") and $Id_client) {
?>
          <td class="externe"><a class="externe" href="contact_detail.php?Id_client=<? echo $Id_client ?>" target="droite">Nouvel interlocuteur</A></td>
<?
}
?>
       </tr>
   </table>
   <table class="requeteur<? echo $tissot ?>">
        <tr>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Raison_sociale][libelle]; ?></td>
           <td class="requet_left" ><? champ_ouvert_droit("client","Raison_sociale",$Raison_sociale,60, 35, $_SESSION[champ_parametrable][client][Raison_sociale][obligatoire]); ?></td>
           <td class="requet_right" ><? echo $_SESSION[champ_parametrable][client][Siret][libelle]; ?></td>
           <td class="requet_left" ><? champ_ouvert_droit("client","Siret",$Siret,14, 18,$_SESSION[champ_parametrable][client][Siret][obligatoire]) ?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Adresse1][libelle]; ?></td>
           <td class="requet_left"><? champ_ouvert_droit("client","Adresse1",$Adresse1,100, 35,$_SESSION[champ_parametrable][client][Adresse1][obligatoire]); ?></td>
        </tr>
        <tr>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Telephone][libelle]; ?></td>
           <td class="requet_left"><? champ_ouvert_droit("client","Telephone",$Telephone,60, 20,$_SESSION[champ_parametrable][client][Telephone][obligatoire]); ?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Fax][libelle]; ?></td>
           <td class="requet_left"><? champ_ouvert_droit("client","Fax",$Fax,60, 20,$_SESSION[champ_parametrable][client][Fax][obligatoire]); ?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Adresse2][libelle]; ?>
           <td class="requet_left"><? champ_ouvert_droit("client","Adresse2",$Adresse2,100, 35,$_SESSION[champ_parametrable][client][Adresse2][obligatoire]); ?>&nbsp;</td>
        </tr>
        <tr>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Groupe][libelle]; ?>
           <td class="requet_left" colspan = 3><? champ_ouvert_droit("client","Groupe",$Groupe,60, 35,$_SESSION[champ_parametrable][client][Groupe][obligatoire]); ?>&nbsp;</td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Adresse3][libelle]; ?>
           <td class="requet_left"><? champ_ouvert_droit("client","Adresse3",$Adresse3,100, 35,$_SESSION[champ_parametrable][client][Adresse3][obligatoire]); ?>&nbsp;</td>
        </tr>
        <tr>
           <td class="requet_right"  height="20" ><? echo $_SESSION[champ_parametrable][client][Prio][libelle]; ?></td>
           <td class="requet_left"><? drop_down_droit("client","SELECT Id_prio, Lb_prio FROM prio", "Prio", "Id_prio", "Lb_prio", $Prio,false, "client",$_SESSION[champ_parametrable][client][Prio][obligatoire], "", " "); ?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Tva_intra][libelle]; ?></td>
           <td class="requet_left"><? drop_down_droit("client","SELECT Id_statut, Lb_statut FROM client_statut", "Tva_intra", "Id_statut", "Lb_statut", $Tva_intra,false, "client",$_SESSION[champ_parametrable][client][Tva_intra][obligatoire], "", ""); ?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Cp][libelle]; ?></td>
           <td class="requet_left"><? champ_ouvert_droit("client","Cp",$Cp,5, 5,$_SESSION[champ_parametrable][client][Cp][obligatoire]); ?></td>
        </tr>
        <tr>
<?
if (droit_utilisateur("client")) {
?>
           <td class="requet_right" >
                 <SCRIPT LANGUAGE="javascript">
                   function choix_naf(){window.open('client_detail_choix_naf.php?Code_naf='+document.client.Code_naf.value,'naf_choix','toolbar=0,location=0,directories=0,status=0,copyhistory=0,menuBar=0,scrollbars=1,Height=400,Width=400');}
                 </SCRIPT>
                 <A href="javascript:choix_naf()"><? echo $_SESSION[champ_parametrable][client][Code_naf][libelle]; ?></A>
           </td>
<?
} else {
?>
           <td class="requet_right" ><? echo $_SESSION[champ_parametrable][client][Code_naf][libelle]; ?></td>
<?
}
        $req_naf = new db_sql("select lb_naf from code_naf where code_naf = ".My_sql_format($Code_naf));
        if ($req_naf->n()) {
           $lb_naf = $req_naf->f('lb_naf');
        } else {
           $lb_naf = '';
        }
?>
           <td class="requet_left" colspan =3>
<?
if (droit_utilisateur("client")) {
?>
               <input type='text' name="Code_naf" value="<? echo $Code_naf?>" size=5 maxlength=5 onchange="javascript:choix_naf()">
<?
} else {
?>
               <? champ_ouvert_droit("N","Code_naf",$Code_naf,5, 5, $_SESSION[champ_parametrable][client][Cp][obligatoire]);?>
<?
}
?>
               <? champ_ouvert_droit("N","lb_naf",$lb_naf,60, 60, "N");?>
           </td>


           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Ville][libelle]; ?></td>
           <td class="requet_left"><? champ_ouvert_droit("client","Ville",$Ville,60, 35,$_SESSION[champ_parametrable][client][Ville][obligatoire]); ?></td>
        </tr>
        <tr>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Id_type][libelle]; ?></td>
           <td class="requet_left"><?drop_down_droit("client","SELECT Id_type, Lb_type FROM type", "Id_type", "Id_type", "Lb_type", $Id_type,false, "client",$_SESSION[champ_parametrable][client][Id_type][obligatoire], "", " ");?></td>
           <td class="requet_right"><? echo $_SESSION[champ_parametrable][client][Code_effectif][libelle]; ?></td>
           <td class="requet_left"><?drop_down_droit("client","SELECT Code_effectif, Lb_effectif FROM code_effectif", "Code_effectif", "Code_effectif", "Lb_effectif", $Code_effectif,false, "client",$_SESSION[champ_parametrable][client][Code_effectif][obligatoire], "", " ");?></td>
<?
if(droit_utilisateur("client")){
?>
           <td colspan = 2>
              <input class="requeteur_button" type="reset" name="Reset" value="Rafraîchir">
              <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
              <input class="requeteur_button" type="submit" name="Submit" value="<? echo $titre_bouton; ?>" OnClick="return champ_oblig('client')">
           </td>
<?
} else {
?>
           <td class="requet_button" colspan = 2>
           </td>
<?
}
?>

        </tr>
   </table>
<?
if ($Id_client) {
?>
   <table class="requeteur">
      <tr>
         <td><iframe src="client_detail_contact_list.php?Id_client=<? echo $Id_client ?>" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
      </tr>
   </table>
<?
}
?>
</td></tr></table>
<? stop_refresh_form () ?>
</form>
<script langage="javascript">var tab_val=form_ref();</script>
<?
include ("ress/enpied.php");
?>
