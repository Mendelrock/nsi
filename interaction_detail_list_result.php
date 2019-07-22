<?
include("ress/entete.php");
?>
        <body class="application">
        <table class="menu_haut_resultat">
        <tr>
                <td class="interne_actif">Actions liées</td>
                <td width='667'></td>
        </tr>
        </table>
<?
        /*---------------------------------------------*/
        $requete = "
        SELECT
           interaction.Id_interaction,
           interaction.Id_affaire,
           DATE_FORMAT(interaction.Date_crea,'%d/%m/%y') as Date_crea,
           DATE_FORMAT(interaction.Date_prev,'%d/%m/%y') as Date_prev,
           interaction.Notes,
           teneur.Lb_teneur,
           utilisateur.Nom
        FROM
           interaction,
           teneur,
           utilisateur
        WHERE
           utilisateur.id_utilisateur = interaction.id_utilisateur
           AND interaction.Id_teneur=teneur.Id_teneur
           AND interaction.Id_affaire = $Id_affaire
           AND interaction.Id_interaction <> '$Id_interaction'";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
        <body class="application">
        <table class="resultat"><tr>
        <td class="resultat_tittle">Date Création</td>
        <td class="resultat_tittle">Date Prévision</td>
        <td class="resultat_tittle">Statut</td>
        <td class="resultat_tittle">Média</td>
        <td class="resultat_tittle">Teneur</td>
        <td class="resultat_tittle">Consultant</td></tr>
        <?
        // Boucle de lecture
                $arr_champs=array("date_crea"=>8,"date_prev"=>8,"notes"=>1,"lb_media"=>30,"lb_teneur"=>30,"Nom"=>30);
                while($resultat->n()){
                                echo "<tr>\n";
                                foreach($arr_champs as $nom=>$lenght){
                                        // formatage des données
                                        $val=substr($resultat->f($nom),0,$lenght);
                                        if($nom=="notes") {
                                           if (empty($val)){
                                              $val="A Faire";
                                           } else {
                                              $val="Fait";
                                           }
                                        }
                                        // affichage
                                        if($nom=="date_crea"){
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='interaction_detail.php?Id_interaction=",$resultat->f('id_interaction'),"' target='droite'>",$val,"</a></td>\n";
                                        }
                                        else{
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$val,"</td>\n";
                                        }
                        }
                        echo "</tr>\n";
                        $z++;
                }
                        echo "<tr>";
                        echo"<td class='resultat_footer' colspan='6'>Cliquer sur la Date de Création pour ouvrir un contact</td>";
                        echo "</tr></table>";
        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
