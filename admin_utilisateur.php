<?
include("ress/entete.php");
?>
    <body class="application">
<?

/*--------------- TRAITEMENT DES UTILISATEURS --------*/
If($ACTE){
    //Verification formulaire
    If( empty($Nom)){
        $message="Le nom est absent";
    }
    If( empty($Login)){
        $message="Le login est absent";
    }
}

if(!$message){
    //Modifier un utilisateur
    if($ACTE==2) {
        //Verification du login
        if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login' AND
                                                Id_utilisateur <> '$Id_utilisateur'","compte")>0){
            $message="Ce login existe déjà";
        }
        if(!$message){
            $requete="
                     UPDATE
                        utilisateur
                     SET
                        Nom=".My_sql_format($Nom).",
                        Prenom=".My_sql_format($Prenom).",
                        Login=".My_sql_format($Login).",
                        Mail=".My_sql_format($Mail)."
                     WHERE
                        Id_utilisateur='$Id_utilisateur'";
            // Execution
            $resultat = new db_sql("$requete");
        }
    }
}
?>
    <table class="menu_haut_resultat">
        <tr>
            <td class="interne_actif">Mon compte</td>
            <td></td>
        </tr>
    </table>
<?
/*---------------------------------------------*/
// Requete, SELECT collecte des données principales
$requete="SELECT
                     Id_utilisateur,
                     Nom,
                     Prenom,
                     Login,
                     Pwd,
                     Droit_connect,
                     Id_responsable,
                     Id_profil,
                     Mail
                  FROM
                     utilisateur 
                  WHERE   
                     Id_utilisateur = $_SESSION[id_utilisateur]";

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
/*----------- AFFICHAGE ---------*/
// Affichage des en_têtes
?>
    <table class="resultat">
        <tr>
            <td class="resultat_tittle">Nom</td>
            <td class="resultat_tittle">Prénom</td>
            <td class="resultat_tittle">Login</td>
            <td class="resultat_tittle">Mail</td>
            <td class="resultat_tittle">&nbsp;</td>
        </tr>
        <? // Boucle de lecture curseur SQL
        while($resultat->n()){
            ?>
            <form method="post" name="utilisateur<? echo $z; ?>" action="?ACTE=2">
                <tr>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "Nom", $resultat->f("Nom"), 30, 30, "O"); ?></td>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "Prenom", $resultat->f("Prenom"), 30, 30, "N"); ?></td>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("O", "Login",$resultat->f("Login"), 10, 10 , "O"); ?></td>
                    <td class="resultat_list" bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("O", "Mail", $resultat->f("Mail"), 30, 100, "O"); ?></td>

                    <td bgcolor="<? echo alternat($z) ?>">
                        <input class="requeteur_button" type="submit" name="modifier" value="Modifier"   onClick="return champ_oblig('utilisateur<? echo $z; ?>')">
                        <input type="hidden" name="Id_utilisateur" value="<? echo $resultat->f("Id_utilisateur");?>">
                    </td>
                </tr>
            </form>
            <?
            $z++;
        }
        ?>
        </form>
    </table>
<?
include ("ress/enpied.php");
?>