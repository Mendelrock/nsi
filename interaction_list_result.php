<?
include("ress/entete.php");

$_SESSION[interaction_liste_id_utilisateur]  = $Id_utilisateur;
$_SESSION[interaction_liste_id_secteur]      = $Id_secteur;
$_SESSION[interaction_liste_date_crea_d]     = $Date_crea_d;
$_SESSION[interaction_liste_date_crea_f]     = $Date_crea_f;
$_SESSION[interaction_liste_date_prev_d]     = $Date_prev_d;
$_SESSION[interaction_liste_date_prev_f]     = $Date_prev_f;
$_SESSION[interaction_liste_notes]           = $Notes;
$_SESSION[interaction_liste_id_teneur]       = $Id_teneur;
$_SESSION[interaction_liste_id_marquage]     = $Id_marquage;
$_SESSION[interaction_liste_id_statut]      = $Id_statut;
$_SESSION[interaction_liste_commentaire]        = $Commentaire;
$_SESSION[interaction_liste_id_type]        = $Id_type;
$_SESSION[interaction_liste_go]               = 1;
$_SESSION[interaction_liste_argumente]      = $interaction_liste_argumente;

        /*---------------------------------------------*/
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        // Critere de recherche
        if($Id_marquage){
           reset ($Id_marquage);
           $liste = -1;
           while (list($i,$j) = each($Id_marquage)) {
              if ($j) $liste .= ",".$j;
           }
           if ($liste <> "-1") {
              $table   .=" ,client_marquage ";
              $requete .=" client_marquage.Id_marquage in (".$liste.") AND ";
              $requete .=" client_marquage.Id_client = interaction.Id_client AND ";
           }
        }
        if($Date_crea_d){
                $requete.="interaction.Date_crea >= '$Date_crea_d' AND ";
        }
        if($Date_crea_f){
                $requete.="interaction.Date_crea <= '$Date_crea_f' AND ";
        }
        if($Date_prev_d){
                $requete.="interaction.Date_prev >= '$Date_prev_d' AND ";
        }
        if($Date_prev_f){
                $requete.="interaction.Date_prev <= '$Date_prev_f' AND ";
        }
        if(!empty($Notes)){
                $requete.="interaction.Notes is $Notes AND ";
        }
        if($Id_teneur){
                $requete.="interaction.Id_teneur = '$Id_teneur' AND ";
        }
        if($Id_utilisateur){
                $requete.="interaction.Id_utilisateur = '$Id_utilisateur' AND ";
        }
        if($Id_secteur){
           $requete.=" portefeuille.Id_utilisateur = $Id_secteur AND ";
           $requete.=" portefeuille.Id_client = interaction.id_client AND ";
           $table  .=" ,portefeuille ";
        }
        if($Commentaire){
                $requete.="affaire.commentaire like '".$Commentaire."%' AND ";
        }
        if($Id_statut){
                $requete.="affaire.Id_statut = '$Id_statut' AND ";
        }
        if($Id_type){
                $requete.="client.Id_type = '$Id_type' AND ";
        }
        if($interaction_liste_argumente){
                $requete.="interaction.Argumente $interaction_liste_argumente AND ";
        }
        $req="
        SELECT
           client.Raison_sociale,
           client.id_type,
           contact.Nom,
           utilisateur.Nom  as nom_consultant,
           interaction.Id_interaction,
           affaire.commentaire,
           interaction.Id_interaction,
           interaction.Id_affaire,
           DATE_FORMAT(interaction.Date_crea,'%d/%m/%y') as Date_crea,
           DATE_FORMAT(interaction.Date_prev,'%d/%m/%y') as Date_prev,
           interaction.Notes,
           interaction.Argumente,
           teneur.Lb_teneur
        FROM
           utilisateur,
           interaction 
           left outer join affaire on (affaire.id_affaire = interaction.id_affaire),
           teneur,
           client,
           contact $table
        WHERE
           $requete
           utilisateur.Id_utilisateur = interaction.id_utilisateur AND
           interaction.Id_teneur=teneur.Id_teneur AND
           interaction.Id_client=client.Id_client AND
           interaction.Id_contact=contact.Id_contact
        ORDER BY
           interaction.Date_prev ASC
        LIMIT 0, ".($Affichage+2);
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($req);

        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
        <body class="application">
           <table class="resultat">
              <tr>
                 <td class="resultat_tittle">Raison sociale</td>
                 <td class="resultat_tittle">Nom interlocuteur</td>
                 <td class="resultat_tittle">Date de prévision</td>
                 <td class="resultat_tittle">Type d'action</td>
                 <td class="resultat_tittle">Statut</td>
                 <td class="resultat_tittle">Nom Affaire</td>
                 <td class="resultat_tittle">Commercial</td>
                 <td class="resultat_tittle">Contact</td>
              </tr>
        <?
        // Boucle de lecture
                $arr_champs=array("raison_sociale"=>20,"nom"=>30,"date_prev"=>8,"lb_teneur"=>20,"notes"=>20,"commentaire"=>30,"nom_consultant"=>20,"Argumente"=>2);
                while($resultat->n() AND $z<$Affichage){
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
                                        if($nom=="Argumente") {
                                           if (empty($val)){
                                              $val="Non argumenté";
                                           } else {
                                              $val="Argumenté";
                                           }
                                        }
                                        // affichage
                                        if($nom=="raison_sociale"){
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='interaction_detail.php?Id_interaction=",$resultat->f('id_interaction'),"' target='droite'>",$val,"</a></td>\n";
                                        }
                                        else{
                                                echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$val,"</td>\n";
                                        }
                                }
                        echo "</tr>\n";
                        $z++;
                }
                if($z){
                        $suiv_text="Fin de liste";
                        if($resultat->n()){
                                $suiv_text="Liste non terminée";
                        }
                        echo"<tr>";
                        echo"<td class='resultat_footer' colspan='7'>Cliquer sur le client pour ouvrir un contact</td>";
                        echo"<td class='resultat_footer' align='center' colspan='3'><span style='font-weight:bold'>",$suiv_text,"</span></td>\n";
                }
                        echo "</tr></table>";

        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
