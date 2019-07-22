<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*   Id_affaire
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
        <body class="application">
        <table class="menu_haut_resultat">
        <tr>
        <td class="interne_actif">Actions liées</td>
        <? if($pass_test){ ?>
        <td class="interne"><a class="interne" href="affaire_detail_fdc_list.php?Id_affaire=<? echo $Id_affaire; ?>">Feuilles de cotes</a> </td>
        <? } ?>
        <td class="interne"><a class="interne" href="affaire_detail_produit_list.php?Id_affaire=<? echo $Id_affaire; ?>">Produits</a> </td>
		<td width="467"></td>
        </tr>
        </table>
<?
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="SELECT
                                interaction.Id_interaction,
                                interaction.Notes,
                                DATE_FORMAT(interaction.Date_crea,'%d/%m/%y') AS Date_crea,
                                DATE_FORMAT(interaction.Date_prev,'%d/%m/%y') AS Date_prev,
                                teneur.Lb_teneur,
                                utilisateur.Nom,
                                contact.Nom as nom_intl
                FROM
                                interaction,
                                teneur,
                                utilisateur,
                                contact
                WHERE
                                utilisateur.Id_utilisateur = interaction.Id_utilisateur AND
                                interaction.Id_contact = contact.Id_contact AND
                                interaction.Id_teneur = teneur.Id_teneur AND
                                interaction.Id_affaire = $Id_affaire";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
    /*----------- AFFICHAGE ---------*/
    // Affichage des en_têtes
        ?>
        <body class="application">
<table class="resultat">
  <tr>
    <td class="resultat_tittle">Date de prévision/réalisation</td>
    <td class="resultat_tittle">Nom Interlocuteur</td>
    <td class="resultat_tittle">Teneur</td>
    <td class="resultat_tittle">Statut</td>
    <td class="resultat_tittle">Commercial</td>
  </tr>        <?
        // Boucle de lecture
                $arr_champs=array("date_prev"=>8, "nom_intl"=>20, "lb_teneur"=>30,"notes"=>8,"Nom"=>30);
                        while($resultat->n()){
                                foreach($arr_champs as $nom=>$lenght){
                                        // formatage des données
                                                $val=substr($resultat->f($nom),0,$lenght);
                                        // affichage
                                        if($nom=="notes") {
                                           if (empty($val)){
                                              $val="A Faire";
                                           } else {
                                              $val="Fait";
                                           }
                                        }
                                        if($nom=="date_prev"){
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='interaction_detail.php?Id_interaction=",$resultat->f('id_interaction'),"' target='droite'>",$val,"</a></td>\n";
                                        }
                                        else{
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$val,"</td>\n";
                                        }
                                }
                                                $z++;
                        echo "</tr>\n";
                        }
                        echo "<tr>\n";
                        echo"<td class='resultat_footer' colspan='6'>Cliquer sur la date de création pour ouvrir un contact</td>";
                        echo "</tr></table>";

        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
