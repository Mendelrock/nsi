<?
/*-----------------PARAMETRES---------------------
/* Internes
/* $Id_utilisateur
/* $Nom
/* $Prenom
/* $Login
/* $Pwd
/* $Droit_connect
/* $Id_responsable
/* $Id_profil
/*
/*------------------------------------------------*/
include("ress/entete.php");
include("c_parm.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DES UTILISATEURS --------*/
If($ACTE){
                //Verification formulaire
                If( empty($fg_droit)){
                        $message="Le droit est absent";
                }
                If( empty($Id_statut)){
                        $message="Le statut est absent";
                }
                If( empty($Id_profil)){
                        $message="Le profil est absent";
                }
                $champ_statut = charge_champ('statut');
                foreach ($champ_statut[ordre] as $key=>$val){
                	if($val==$Id_statut){
                		$lb_statut = $key; break;
                	}
                }
}
        //Ajouter un utilisateur
if(!$message){
        if($ACTE==1){
                //Verification du login
                        /*if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login'","compte")>0){
                                $message="Ce login existe déjà";
                        }*/
                 if(!$message){
                         $requete="
                        INSERT INTO
                                profil_statut_fdc(
                                Id_profil,
                                Id_statut,
                                lb_statut,
                                fg_droit)
                        VALUES (
                                ".My_sql_format($Id_profil).",
                                ".My_sql_format($Id_statut).",
                                ".My_sql_format($lb_statut).",
                                ".My_sql_format($fg_droit).")";
                                // Execution
                $resultat= new db_sql("$requete");$Id_profil="";$Id_statut="";$lb_statut="";$fg_droit="";
                        }
        }
        //Modifier un utilisateur
        if($ACTE==2) {
                //Verification du login
                        /*if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login' AND
                                                Id_utilisateur <> '$Id_utilisateur'","compte")>0){
                                $message="Ce login existe déjà";
                        }*/
                        if(!$message){
                $requete="
                     UPDATE
                        profil_statut_fdc
                     SET
                        Id_profil=".My_sql_format($Id_profil).",
                        Id_statut=".My_sql_format($Id_statut).",
                        lb_statut=".My_sql_format($lb_statut).",
                        fg_droit=".My_sql_format($fg_droit)."
                     WHERE
                        Id_profil_statut_fdc='$Id_profil_statut_fdc'";
                                // Execution
                $resultat = new db_sql("$requete");$Id_profil="";$Id_statut="";$lb_statut="";$fg_droit="";
                        }
        }
}
?>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Liste</td>
       <td></td>
    </tr>
</table>
<?
 /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="SELECT
                    *
                  FROM
                     profil_statut_fdc ";

        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
<table class="resultat">
   <tr>
        <td class="resultat_tittle">Profil</td>
        <td class="resultat_tittle">Statut</td>
        <td class="resultat_tittle">Droit</td>
        <td class="resultat_tittle">&nbsp;</td>
   </tr>
                <?        // Boucle de lecture
                while($resultat->n()){
                    // Traitement password
                    
                ?>
                  <form method="post" name="utilisateur<? echo $z; ?>" action="admin_profil_statut_fdc.php?ACTE=2">
                  <tr>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit ("admin", "select Id_profil, Lb_profil from profil", "Id_profil", "Id_profil", "Lb_profil",$resultat->f("Id_profil"), false, "utilisateur".$z, "", "", ""); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "Id_statut", "", "", $resultat->f("Id_statut"),false, "feuille", "N", "|1|2|5|6|7|8", "|En cours|Validée|A produire|A poser|A livrer|A facturer|Facturée") ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "fg_droit", "", "", $resultat->f("fg_droit"),false, "feuille", "N", "0|1|2", "Cible|Consultation|Modification") ?></td>
                  <td bgcolor="<? echo alternat($z) ?>">
                   <input class="requeteur_button" type="submit" name="modifier" value="Modifier"   onClick="return champ_oblig('utilisateur<? echo $z; ?>')">
                   <input type="hidden" name="Id_profil_statut_fdc" value="<? echo $resultat->f("Id_profil_statut_fdc");?>">
                   </td>
                   </tr>
                   </form>
                <?
                $z++;
                }
                ?>
                  <form method="post" name="nouveau" action="admin_profil_statut_fdc.php?ACTE=1">
                  <tr>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit ("admin", "select Id_profil, Lb_profil from profil", "Id_profil", "Id_profil", "Lb_profil",$Id_profil, false, "utilisateur".$z, "", "", "|"); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "Id_statut", "", "", $Id_statut,false, "feuille", "N", "|1|2|5|6|7|8", "|En cours|Validée|A produire|A poser / A livrer|A facturer|Facturée") ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "fg_droit", "", "", $fg_droit,false, "feuille", "N", "|1|2", "|Consultation|Modification") ?></td>
                   <td bgcolor="<? echo alternat($z) ?>">
                   <input class="requeteur_button" type="submit" name="creer" value="Créer"  onClick="return champ_oblig('nouveau')">
                   </td>
                   </tr>
                   </form>
                   </table>
<?
include ("ress/enpied.php");
?>