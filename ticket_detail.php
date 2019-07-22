<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
function validateticket($record){
    if(!$_POST[Id_utilisateur_createur])
        return "Le champ Utilisateur créateur doit être renseigné";
    else if(!$_POST[Id_utilisateur_responsable])
        return "Le champ Utilisateur responsable doit être renseigné";
}
$droit_maj = 'O';
$Texte = str_replace("'", "''", $Texte);

if(isset($_GET['resp'])) {
    $responsable = intval($_GET['resp']);
} else {
    $responsable = 1;
}
if(isset($_GET['close'])) {
    $Id_statut = 4;
    $req = new db_sql('UPDATE ticket SET Id_statut = '.$Id_statut.' where Id_ticket = '.$Id_ticket.'' );
}

if ($ACTE == 1) {
    $message = validateticket($_POST);
    /*if ($message) {
       break;
 }*/
    $statut_enr = "";
    if (!$message) {
        if(!$id_origine) {
            $id_origine = 0;
        }
        if($Id_ticket){
            $statut_depuis = req_sim('SELECT Id_statut from ticket where Id_ticket = '.$Id_ticket.'', 'id_statut');

            $Id_ancien_resp = req_sim('SELECT Id_utilisateur_responsable from ticket where Id_ticket = '.$Id_ticket.'', 'id_utilisateur_responsable');

            if($Id_statut != 4 ) {
                if ($Id_ancien_resp != $Id_utilisateur_responsable || $_SESSION[id_utilisateur] != $Id_utilisateur_responsable) {
                    $Id_statut = 3;
                } else {
                    $Id_statut = 2;
                }
            }

            $req = new db_sql('UPDATE ticket SET Id_statut = '.$Id_statut.' where Id_ticket = '.$Id_ticket.'' );
            $statut_enr = $Id_statut;

            $Update = 1;

            new db_sql("update ticket set 
  	     	         	Id_utilisateur_createur='$Id_utilisateur_createur',
  	     	         	Id_utilisateur_responsable='$Id_utilisateur_responsable',
  	     	         	Nom='$Nom',
  	     	         	Prenom='$Prenom',
  	     	         	Telephone='$Telephone',
  	     	         	Mail='$Mail',
  	     	         	Id_origine = '$id_origine'
  	     	         	where id_ticket=$Id_ticket");
        } else {
            $Update = 1;
            new db_sql("insert ticket (Id_utilisateur_createur,Id_utilisateur_responsable,Id_statut,Nom,Prenom,Telephone,Mail,Id_origine) values (
				     '$Id_utilisateur_createur',
				     '$Id_utilisateur_responsable',
				     2,
				     '$Nom',
				     '$Prenom',
					 '$Telephone',
				     '$Mail',
				     $id_origine)");
            $Id_ticket = db_sql::last_id ();
            $statut_enr = 2;
        }
        if(!$statut_depuis) {
            $statut_depuis = 0;
        }
        if(!$Id_ancien_resp) {
            $Id_ancien_resp = $_SESSION[id_utilisateur];
        }

        if($Update) {


            if($statut_depuis == $statut_enr) {
                $vers = 0;
            } else {
                $vers = $statut_enr;
            }

            if($Id_ancien_resp == $Id_utilisateur_responsable) {
                $resp_ff = 'null';
            } else {
                $resp_ff = $Id_utilisateur_responsable;
            }
            $req = new db_sql("
                INSERT INTO 
                    ticket_historique (id_ticket, statut_vers, nouveau_responsable, texte)
                VALUES 
                    (" . $Id_ticket . ",
                    ".$vers.",
                    " . $resp_ff . ",
                    '" . trim($Texte) . "')
            ");
        }

    }
} else {
    if($Id_ticket) {
        $statut_depuis = req_sim('SELECT Id_statut from ticket where Id_ticket = '.$Id_ticket.'', 'id_statut');
        $Id_ancien_resp = req_sim('SELECT Id_utilisateur_responsable from ticket where Id_ticket = '.$Id_ticket.'', 'id_utilisateur_responsable');

        if($statut_depuis != 4 && !$Id_ancien_resp) {
            $req = new db_sql('UPDATE ticket SET Id_utilisateur_responsable = ' . $_SESSION[id_utilisateur] . ' , Id_statut = 2');

            if ($statut_depuis != 2 && $Id_ancien_resp != $_SESSION[id_utilisateur]) {
                $req = new db_sql("
                    INSERT INTO 
                        ticket_historique (id_ticket, statut_vers,nouveau_responsable, texte)
                    VALUES 
                        (" . $Id_ticket . ",
                        2,
                        " . $_SESSION[id_utilisateur] . ",
                        ''
                        )
                ");
            }
        } elseif($Id_ancien_resp == $_SESSION[id_utilisateur] && $statut_depuis != 4) {
            if($statut_depuis == 2) {
                $vers = 0;
                $nouupdat = 1;
            } else {
                $vers = 2;
                $nouupdat = 0;
            }

            if(!$nouupdat) {
                $req = new db_sql('UPDATE ticket SET Id_statut = 2 where Id_ticket = ' . $Id_ticket . '');
                $req = new db_sql("
                    INSERT INTO 
                        ticket_historique (id_ticket, statut_vers,nouveau_responsable, texte)
                    VALUES 
                        (" . $Id_ticket . ",
                        $vers,
                        0,
                        '')
                ");
            }
        } elseif($statut_depuis == 4) {
            $req = new db_sql("
                    INSERT INTO 
                        ticket_historique (id_ticket, statut_vers,nouveau_responsable, texte)
                    VALUES 
                        (" . $Id_ticket . ",
                        4,
                        0,
                        '')
                ");
        }
    }
}

if ($message) {
} else if ($Id_ticket) {


    $req="
      SELECT 
         * 
      FROM
         ticket
      WHERE 
	     id_ticket = $Id_ticket";
    $resultat = new db_sql($req);
    $resultat->n();
    $Id_utilisateur_createur    = $resultat->f("Id_utilisateur_createur");
    $Id_utilisateur_responsable         = $resultat->f("Id_utilisateur_responsable");
    $Id_statut    = $resultat->f("Id_statut");
    if($Id_statut == 4) {
        $droit_maj = 'N';
    } else {
        $droit_maj = 'O';
    }
    $Nom  = $resultat->f("Nom");
    $Prenom     = $resultat->f("Prenom");
    $Telephone    = $resultat->f("Telephone");
    $Mail  = $resultat->f("Mail");
    $Id_origine = $resultat->f("Id_origine");

    $historiques = [];
    $req_histo = new db_sql('SELECT * FROM ticket_historique WHERE id_ticket = '.$Id_ticket.' ORDER BY date_historique DESC');
    $i = 0;
    while($req_histo->n()) {
        $historiques[$i]['date_historique'] = $req_histo->f('date_historique');
        $historiques[$i]['statut_vers'] = $req_histo->f('statut_vers');
        $historiques[$i]['nouveau_responsable'] = $req_histo->f('nouveau_responsable');
        $historiques[$i]['texte'] = $req_histo->f('texte');
        $i++;
    }
}
$Id_utilisateur_createur = $_SESSION[id_utilisateur];
if(!$Id_utilisateur_responsable)$Id_utilisateur_responsable= $_SESSION[id_utilisateur];
if(!$Id_statut)$Id_statut="1";
$liste_enregistrement = array();
?>
    <style type="text/css">
        .title-th {
            font-size: 13px;
            padding: 6px;
        }
        .data-td {
            font-size: 13px;
            text-align: center;
        }
    </style>
    <body class="application">
<FORM method=post name=formulaire action=ticket_detail.php?ACTE=1>
    <table class="cadre_application_auto">
        <tr>
            <td class="cadre_application">
                <table class="menu_haut">
                    <tr>
                        <td class="menu_haut">Ticket</td>
                        <td class='externe'><A class='externe' href='ticket_list.php?liste_go=1'>Retour à la liste</A></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="requeteur">
        <tr>
            <td class="requet_right">Numéro</td>
            <td class="requet_left" ><? champ_ouvert_droit("N","Id_ticket",$Id_ticket,10, 10, "O");?></td>
            <td class="requet_right">Créateur</td>
            <td class="requet_left" ><? drop_down_droit("N","SELECT Id_utilisateur, Nom FROM utilisateur u,autorisation a where u.Id_profil=a.Id_profil and Id_droit=29", "Id_utilisateur_createur", "Id_utilisateur", "Nom", $Id_utilisateur_createur,false, "ticket","N", "", " "); ?></td>
            <td class="requet_right" >Responsable</td>
            <td class="requet_left"><? drop_down_droit($droit_maj,"SELECT Id_utilisateur, Nom FROM utilisateur u,autorisation a where u.Id_profil=a.Id_profil and Id_droit=29", "Id_utilisateur_responsable", "Id_utilisateur", "Nom", $Id_utilisateur_responsable,false, "ticket","N", "", ""); ?></td>
        </tr>
        <tr>
            <td class="requet_right">Statut</td>
            <td class="requet_left"><? champ_ouvert_droit("N","Lb_statut", req_sim("SELECT Lb_statut FROM ticket_statut WHERE Id_statut = $Id_statut", "Lb_statut"),60, 35, "O"); ?></td>
            <td class="requet_right" >Nom</td>
            <td class="requet_left"><? champ_ouvert_droit($droit_maj,"Nom",$Nom,60, 35, "O"); ?></td>
            <td class="requet_right">Prenom</td>
            <td class="requet_left"><? champ_ouvert_droit($droit_maj,"Prenom",$Prenom,60, 35, "O");?></td>
        </tr>
        <tr>
            <td class="requet_right">Telephone</td>
            <td class="requet_left"><? champ_ouvert_droit($droit_maj,"Telephone",$Telephone,60, 10, "O");?></td>
            <td class="requet_right">Mail</td>
            <td class="requet_left" colspan = 1><? champ_ouvert_droit($droit_maj,"Mail",$Mail,60, 35, "O"); ?></td>
            <td class="requet_right">Origine</td>
            <td class="requet_left" colspan="2" ><? drop_down_droit($droit_maj,"SELECT lb_origine, id_origine FROM ticket_origine ORDER BY id_origine", "id_origine", "id_origine", "lb_origine", $Id_origine,false, "ticket","N", "", " "); ?></td>
        </tr>
        <tr>
            <td class="requet_left" colspan = 6>Contenu</td>
        </tr>
        <tr>
            <?php if($droit_maj == 'N') {
                $add = " disabled='disabled' ";
            }
            ?>
            <td class="requet_left" colspan="6"><TEXTAREA <?php echo $add; ?> NAME="Texte" ROWS="20" COLS="150"></TEXTAREA></td>
        </tr>
    </table>
    <?php
    if($droit_maj == 'O') {?>
        <INPUT class=requeteur_button name=Submit value=Enregistrer type=submit> <INPUT class=requeteur_button onclick="window.close();" name=Annuler value=Annuler type=button>
        <a style="border: 1px inset silver;
                background-color: InfoBackground;
                font-size: 10px;
                color: #000066;
                width: 86px;
                padding: 1px 6px;"
           class=requeteur_button href="ticket_detail.php?close=O&Id_ticket=<?php echo $Id_ticket;?>" >Cloturer</a>
        <?php
    }

        if(is_array($historiques)) {
            echo '<h4>Historique du ticket</h4>';
            echo '<table class="requeteur">';
            echo '<tbody>
                        <tr>
                            <td class="resultat_tittle">Date</td>
                            <td class="resultat_tittle">Statut</td>
                            <td class="resultat_tittle">Responsable</td>
                            <td class="resultat_tittle">Texte</td>
                        </tr>';
            foreach ($historiques as $historique) {
                if($historique['nouveau_responsable']) {
                    $new_resp = req_sim('SELECT Nom FROM utilisateur u,autorisation a where u.Id_profil=a.Id_profil and Id_droit=29 and Id_utilisateur = ' . $historique['nouveau_responsable'] . ' ', 'Nom');
                } else {
                    $new_resp = "";
                }
                echo '<tr style="border-bottom: 1px solid black;">';
                    echo '<td nowrap class="resultat_list">' . $historique['date_historique'] . '</td>';
                    echo '<td nowrap class="resultat_list">' . utf8_encode(req_sim('SELECT Lb_statut from ticket_statut WHERE id_statut = '.$historique['statut_vers'].'', 'Lb_statut')). '</td>';
                    echo '<td nowrap class="resultat_list">' . utf8_encode($new_resp) . '</td>';
                    echo '<td class="resultat_list"><p '.$add.' rows="7" cols="150°">' . nl2br($historique['texte']) . '</p></td>';
                echo '</tr>';
            }
            echo '</tbody>';
        }
        ?>

    <?
    if(droit_utilisateur("article")){

    }
    ?>
    <input type="hidden" name="liste_enregistrement" value="<? echo urlencode(serialize($liste_enregistrement)); ?>">
</FORM>
<?
include ("ress/enpied.php");
?>