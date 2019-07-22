<?
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DES FOURNISSEURS --------*/
If($ACTE){
                //Verification formulaire
                If( empty($lb_fournisseur)){
                        $message="Le nom est absent";
                }
}
        //Ajouter un fournisseur
if(!$message){
        if($ACTE==1){
                //Verification du login
                        if(req_sim("SELECT count(1) as compte FROM fournisseur WHERE lb_fournisseur='$lb_fournisseur'","compte")>0){
                                $message="Ce fournisseur existe déjà";
                        }
                 if(!$message){
                        if($envoi_automatique!=1) $envoi_automatique=0;	
                        if(!$adresse_postale) $adresse_postale="";	
                        $requete="
                        INSERT INTO
                                fournisseur(
                                lb_fournisseur,
                                adresse_postale,
                                adresse_mail,
                                cond_regle,
                                envoi_automatique,
                                statut)
                        VALUES (
                                '$lb_fournisseur',
                                '$adresse_postale',
                                '$adresse_mail',
                                '$cond_regle',
                                ".$envoi_automatique.",
                                ".My_sql_format($statut_fournisseur).")";
                                // Execution
                $resultat= new db_sql("$requete");
                        }
        }
        //Modifier un utilisateur
        if($ACTE==2) {
                //Verification du login
                        if(req_sim("SELECT count(1) as compte FROM fournisseur WHERE lb_fournisseur='$lb_fournisseur' AND
                                                Id_fournisseur <> '$Id_fournisseur'","compte")>0){
                                $message="Ce fournisseur existe déjà";
                        }
                        if(!$message){
                        if($envoi_automatique!=1) $envoi_automatique=0;		
                $requete="
                     UPDATE
                        fournisseur
                     SET
                        lb_fournisseur=".My_sql_format($lb_fournisseur).",
                        adresse_postale=".My_sql_format($adresse_postale).",
                        adresse_mail=".My_sql_format($adresse_mail).",
                        cond_regle=".My_sql_format($cond_regle).",
                        envoi_automatique=".$envoi_automatique.",
                        statut=".My_sql_format($statut_fournisseur)."
                     WHERE
                        Id_fournisseur='$Id_fournisseur'";
                                // Execution
                $resultat = new db_sql("$requete");
                        }
        }
}
?>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Liste des fournisseurs</td>
       <td></td>
    </tr>
</table>
<?
 /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="SELECT
                     id_fournisseur,
                     lb_fournisseur,
                     adresse_postale,
                     adresse_mail,
                     cond_regle,
                     envoi_automatique,
                     statut
                  FROM
                     fournisseur ";

        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
<table class="resultat">
   <tr>
        <td class="resultat_tittle">Nom</td>
        <td class="resultat_tittle">Adresse postale</td>
        <td class="resultat_tittle">Adresse mail</td>
        <td class="resultat_tittle">Conditions de Règlement</td>
        <td class="resultat_tittle">Envoi automatique</td>
        <td class="resultat_tittle">Statut</td>
        <td class="resultat_tittle">&nbsp;</td>
   </tr>
                <?        // Boucle de lecture
                while($resultat->n()){
                ?>
                  <form method="post" name="utilisateur<? echo $z; ?>" action="admin_fournisseur.php?ACTE=2">
                                  <tr>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "lb_fournisseur", $resultat->f("lb_fournisseur"), 30, 30, "O"); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? text_area_droit ("admin","adresse_postale", 30, 3, 255, $resultat->f("adresse_postale"),'N'); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "adresse_mail",$resultat->f("adresse_mail"), 255, 40 , "O"); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? text_area_droit ("admin","cond_regle", 30, 1, 255, $resultat->f("cond_regle"),'N'); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_binaire_droit ("admin", "envoi_automatique", "1", $resultat->f("envoi_automatique")); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O","", "statut_fournisseur", "", "", $resultat->f("statut"),false, "","N", "A|I", "Actif|Inactif"); ?></td>
                  <td bgcolor="<? echo alternat($z) ?>">
                   	<input class="requeteur_button" type="submit" name="modifier" value="Modifier" >
                   	<input type="hidden" name="Id_fournisseur" value="<? echo $resultat->f("id_fournisseur");?>">
                  </td>
                   </tr>
                   </form>
                <?
                $z++;
                }
                ?>
                  <form method="post" name="nouveau" action="admin_fournisseur.php?ACTE=1">
                  <tr>
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "lb_fournisseur", "", 30,30, "O"); ?></td>
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? text_area_droit ("admin","adresse_postale", 30, 3, 255, "",'N'); ?></td>
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "adresse_mail", "", 255,40, "O"); ?></td>
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? text_area_droit ("admin","cond_regle", 30, 1, 255, "",'N'); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_binaire_droit ("admin", "envoi_automatique", "1", ""); ?></td>
                  <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O","", "statut_fournisseur", "", "", "",false, "","N", "A|I", "Actif|Inactif"); ?></td>
                   <td bgcolor="<? echo alternat($z) ?>">
                   <input class="requeteur_button" type="submit" name="creer" value="Créer">
                   </td>
                   </tr>
                   </form>
                   </table>
<?
include ("ress/enpied.php");
?>