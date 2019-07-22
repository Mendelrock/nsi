<?
/*-----------------PARAMETRES---------------------*/
/* Id_utilisateur
   Prio
   Id_type
   Dates
/*------------------------------------------------*/

/*-----------------PARAMETRES---------------------*/
if (!$Date_d || empty($Date_d)) {
                $Date_d=substr(aujourdhui(),0,8)."01";
}
if (!$Date_f || empty($Date_f)) {
                $Date_f=substr(aujourdhui(),0,8).date("t");
}
if (!$Affichage) {
   $Affichage = $Affichage_defaut;
}
recupere('Id_type');
if (!$Id_type) {
   $Id_type = "is not null";
}

/*---- Si l'utilisateur connecté est dans la hiérarchie commerciale, le mettre par défaut ----------------*/
if (!$Id_utilisateur) {
   if (req_sim ("SELECT
                     count(1) as compte
                FROM
                     utilisateur
                     left join utilisateur as sous on (sous.id_responsable = utilisateur.id_utilisateur)
                     left join utilisateur as sur on (sur.id_utilisateur = utilisateur.id_responsable)
                WHERE
                     (sous.id_utilisateur is not null or
                     sur.id_utilisateur is not null) and
                     utilisateur.id_utilisateur = $_SESSION[id_utilisateur]","compte")>0) {
      $Id_utilisateur = $_SESSION[id_utilisateur];
   } else {
      $Id_utilisateur = 'T';
   }
}

if ($Id_utilisateur <> 'T') {
      $table = " , portefeuille ";
      $where = " AND portefeuille.Id_client = client.id_client ";
   if (req_sim("select id_responsable from utilisateur where id_utilisateur = $Id_utilisateur","id_responsable")) {
      $where .= " AND portefeuille.Id_utilisateur = $Id_utilisateur ";
   } else {
      $table .= " , utilisateur ";
      $where .= " AND portefeuille.Id_utilisateur = utilisateur.id_utilisateur
                  AND utilisateur.id_responsable = $Id_utilisateur ";
   }
}

if($Prio){
   $where .=" AND Prio = '$Prio' ";
}

if($Id_type<>"*"){
   $where .=" AND Id_type ".stripslashes($Id_type)." ";
}

if($Id_marquage){
   reset ($Id_marquage);
   $liste = -1;
   while (list($i,$j) = each($Id_marquage)) {
      if ($j) $liste .= ",".$j;
   }
   if ($liste <> "-1") {
      $table  .=" , client_marquage ";
      $where .=" AND client_marquage.Id_marquage in (".$liste.") ";
      $where .=" AND client_marquage.Id_client = client.Id_client ";
   }
}

/*-------- Parametre des ruptures -----------------*/
$nb = 1;

$req = new db_sql("SELECT distinct
                      client.id_client,
                      client.raison_sociale
                   FROM
                      client $table
                   WHERE
                      1=1 $where
                   LIMIT 0, ".($Affichage+1));

function affect_valeur () {
   global $critere, $valeur, $indicateur, $req, $totaux, $Date_d, $Date_f ;

   $valeur = array();

   $valeur['raison_sociale'] = $req->f('raison_sociale');

   $id_client=$req->f('id_client');

   $s_req = new db_sql("
               SELECT
                  date_prev
               FROM
                  interaction
               WHERE
                  interaction.Id_client = $id_client AND
                  interaction.Id_media = 1 AND
                  Notes is not null
              order by
                  date_prev DESC
              limit
                  0,1
              ");

   $s_req->n();

   $valeur['date'] = $s_req->f('date_prev');

   $s_req = new db_sql("
               SELECT
                  SUM(prix) as CA,
                  affaire.Id_statut
               FROM
                  affaire
              WHERE
                  affaire.Id_client = $id_client AND
                  affaire.Date_prev >= '$Date_d' AND
                  affaire.Date_prev <= '$Date_f' AND
                  affaire.Id_statut in (1,2,3,4)
              GROUP BY
                  affaire.Id_statut");

   while ($s_req->n()) {
      $valeur[$s_req->f('Id_statut')] = $s_req->f('CA');
   }

   $s_req = new db_sql("
               SELECT
                  SUM(prix) as CA,
                  affaire.Id_statut
               FROM
                  affaire
              WHERE
                  affaire.Id_client = $id_client AND
                  affaire.Id_statut in (2,3,4,5)
              GROUP BY
                  affaire.Id_statut");

   while ($s_req->n()) {
      if ($s_req->f('Id_statut') == 5) {
         $valeur[6] = $s_req->f('CA');
      } else {
         $valeur[5] += $s_req->f('CA');
      }
   }
}

function rupture_b1 () {
   global $critere, $valeur, $indicateur, $req, $totaux, $compteur, $Affichage;
   if ($compteur > $Affichage) {
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="D3DCE3" colspan = 8>Limite d'affichage atteinte, affiner les critères ou augmenter la limite</td>
            <tr>
<?
   } else {
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="D3DCE3" colspan = 2>Total général</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[6],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[5],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[4],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[3],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[2],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[1],2,'.',''); ?></td>
            </tr>
<?
   }
}

function coeur () {
   global $critere, $valeur, $indicateur, $req, $totaux, $compteur, $Affichage;
   $compteur ++;
   if ($compteur <= $Affichage) {
      for ($i=1; $i<=6; $i++) {
         $totaux[$i] += $valeur[$i];
      }
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo $valeur["raison_sociale"] ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo_tableau_date($valeur["date"]) ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[6],2,'.','') ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[5],2,'.','') ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[4],2,'.','') ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[3],2,'.','') ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[2],2,'.','') ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur[1],2,'.','') ?></td>
            </tr>
<?
   }
}
?>
               <td class="menu_haut">Marquage : </td><td class="menu_haut" rowspan = 3><? drop_down_droit("O","SELECT Id_marquage, Lb_marquage FROM marquage ORDER BY Lb_marquage", "Id_marquage[]\" multiple=\"yes", "Id_marquage", "Lb_marquage", $Id_marquage,false, "client","N", "", " "); ?></td>
               <td class="menu_haut">Période :
               <? champ_date(Date_d, $Date_d, "periode", "O"); ?> au <? champ_date(Date_f, $Date_f, "periode", "O"); ?>
            </tr>
            <tr>
                           <td class="menu_haut" colspan = 2> Secteur : <? drop_down_droit("O","SELECT distinct
                                                                           utilisateur.Id_utilisateur,
                                                                           utilisateur.Nom
                                                                        FROM
                                                                           utilisateur
                                                                           left join utilisateur as sous on (sous.id_responsable = utilisateur.id_utilisateur)
                                                                           left join utilisateur as sur on (sur.id_utilisateur = utilisateur.id_responsable)
                                                                        WHERE
                                                                           sous.id_utilisateur is not null or
                                                                           sur.id_utilisateur is not null",
                                                                        "Id_utilisateur", "Id_utilisateur", "nom",$Id_utilisateur,true, "efficacite","N","T","Global"); ?>
               <td class="menu_haut">Prioritaire : <?drop_down_droit("O","", "Prio", "", "", $Prio,false, "client","N", "|O|N", "|OUI|NON");?></td>
            </tr>
            <tr>
               <td class="menu_haut"colspan = 2>Type : <? drop_down_droit("O","SELECT concat(' = \'',Id_type,'\'') as Id_type, Lb_type FROM type", "Id_type", "Id_type", "Lb_type", $Id_type,false, "client","N", "*|is not null", "|Typé"); ?></td>
               <td class="menu_haut">Affichage : <? champ_numeric_droit("O","Affichage", $Affichage, 0, 5, "affaire", "O"); ?> (Lignes)</td>
               <td class="menu_haut">
                  <input class="requet_button" type="submit" value="Appliquer" OnClick="return champ_oblig('report')"></td>
               </td>
            </tr>
         </table>
</form>
         <table class="resultat">
            <tr>
               <td colspan="2"></td>
               <td class="resultat_tittle" colspan="2">Global</td>
               <td class="resultat_tittle" colspan="4">Période</td>
            </tr>
            <tr>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Raison sociale du client">Raison Sociale</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Date de la dernière interaction de média visite et réalisée">Visité</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut signé quelques soient les dates">CA signé</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut du portefeuille élargi (signature, négo, portefeuille) quelques soient les dates">CA portefeuille</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut signature dont la date de prévision est dans la période">CA signature</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut négo dont la date de prévision est dans la période">CA négo</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut portefeuille dont la date de prévision est dans la période">CA portefeuille</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut projet dont la date de prévision est dans la période">CA projet</A></td>
            </tr>
<?
        include ('ress/rupture.php');
?>
         </table>
      </td>
   </tr>
</table>