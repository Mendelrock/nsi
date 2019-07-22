<?
include("ress/entete.php");
include("c_parm.php");
?>
    <body class="application">
<?

/*--------------- TRAITEMENT DES UTILISATEURS --------*/
If($ACTE){
    //Verification formulaire
    If( empty($id_origine)){
        $message="L'origine est absent";
    }
    If( empty($id_profil)){
        $message="Le profil est absent";
    }
}
//Ajouter un utilisateur
if(!$message){
    if($ACTE==1){
        //Verification du login
        if(!$message){
            $requete="
                        INSERT INTO
                                profil_origine(
                                Id_profil,
                                Id_origine
                                )
                        VALUES (
                                ".My_sql_format($id_profil).",
                                ".My_sql_format($id_origine)." )";

            if(check($id_profil,$id_origine)){
                $message = "Ce profil a déjà cette origine.";
            }

            if(!$message){
                $resultat= new db_sql("$requete");
                $id_profil=$id_origine="";
            }

        }
    }
    //Modifier un utilisateur
    if($ACTE==2) {

        if(!$message){
            $requete="
                     UPDATE
                        profil_origine
                     SET
                        id_profil=".My_sql_format($id_profil).",
                        id_origine=".My_sql_format($id_origine)."
                     WHERE
                        id_profil=".My_sql_format($id_profil_avant)." and 
                        id_origine=".My_sql_format($id_origine_avant)."  ";
            if(check($id_profil,$id_origine)){
                $message = "Ce profil a déjà cette origine.";
            }

            if(!$message){
                // Execution
                $resultat = new db_sql("$requete");$id_profil=$id_origine="";
            }
        }
    }
}

function check($id_profil,$id_origine){
    $requete_verif = "select count(*) as nbr
                              from  profil_origine 
                              where 
                                  Id_profil=".My_sql_format($id_profil)." and 
                                  Id_origine=".My_sql_format($id_origine)." ";
    // Execution
    $resultat= new db_sql("$requete_verif");
    if( ($resultat->n()) and ($resultat->f("nbr") >= 1) ){
        return true;
    }
    return false;
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
$requete="SELECT * FROM  profil_origine ";

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
/*----------- AFFICHAGE ---------*/
// Affichage des en_têtes
?>
    <table class="resultat">
        <tr>
            <td class="resultat_tittle">Profil</td>
            <td class="resultat_tittle">Origine</td>
            <td class="resultat_tittle"></td>
        </tr>
        <?        // Boucle de lecture
        while($resultat->n()){
            // Traitement password

            ?>
            <form method="post" name="utilisateur<? echo $z; ?>" action="admin_profil_origine.php?ACTE=2">
                <tr>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit ("admin", "select Id_profil, Lb_profil from profil", "id_profil", "id_profil", "Lb_profil",$resultat->f("Id_profil"), false, "utilisateur".$z, "", "", ""); ?></td>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "id_origine", "", "", $resultat->f("id_origine"),false, "feuille", "N", "1|2|3", "Store et Rideau|Prosolaire|Force de vente") ?></td>
                    <td bgcolor="<? echo alternat($z) ?>">
                        <input class="requeteur_button" type="submit" name="modifier" value="Modifier"   onClick="return champ_oblig('utilisateur<? echo $z; ?>')">
                        <input type="hidden" name="id_profil_avant" value="<? echo $resultat->f("id_profil");?>">
                        <input type="hidden" name="id_origine_avant" value="<? echo $resultat->f("id_origine");?>">
                    </td>
                </tr>
            </form>
            <?
            $z++;
        }
        ?>
        <form method="post" name="nouveau" action="admin_profil_origine.php?ACTE=1">
            <tr>
                <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit ("admin", "select Id_profil, Lb_profil from profil", "id_profil", "id_profil", "Lb_profil",$id_profil, false, "utilisateur".$z, "", "", "|"); ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? drop_down_droit("O", "", "id_origine", "", "", $id_origine,false, "feuille", "N", "|1|2|3", "|Store et Rideau|Prosolaire|Force de vente") ?></td>
                <td bgcolor="<? echo alternat($z) ?>">
                    <input class="requeteur_button" type="submit" name="creer" value="Créer"  onClick="return champ_oblig('nouveau')">
                </td>
            </tr>
        </form>
    </table>
<?
include ("ress/enpied.php");
?>