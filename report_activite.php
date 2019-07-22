<?
/*-----------------PARAMETRES---------------------*/


/*------------------------------------------------*/
/*---- tableau des résultats ---------------------*/
/*------------------------------------------------*/

// Interactions
$reqa = new db_sql("
   SELECT
      count(1) as compte,
      Id_utilisateur,
      Id_teneur
   FROM
      interaction fait $clause_type
   WHERE
      Notes is not null AND
      Date_prev >= '$Date_d' AND
      Date_prev <= '$Date_f'
   GROUP BY
      Id_utilisateur,
      Id_teneur");

while ($reqa->n()) {
   $indicateur[$reqa->f('Id_utilisateur')]['contact'][$reqa->f('Id_teneur')] = $reqa->f('compte');
}

// Ca portefeuille
$reqa = new db_sql("
        SELECT
                SUM(Prix) as mt,
                count(1) as nb,
                Id_utilisateur
        FROM
                affaire fait $clause_type
        WHERE
                Date_crea >= '$Date_d' AND
                Date_crea <= '$Date_f'
        GROUP BY
                Id_utilisateur");

while ($reqa->n()) {
   $indicateur[$reqa->f('Id_utilisateur')]['affaire']['mt'] = $reqa->f('mt')*1;
   $indicateur[$reqa->f('Id_utilisateur')]['affaire']['nb'] = $reqa->f('nb');
}

/*-------- Parametre des ruptures -----------------*/

function ecrit_ligne ($titre, $couleur, $tableau) {
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $titre ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][1] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][3] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][2] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][1]+$tableau['contact'][2]+$tableau['contact'][3] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][8] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][4] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][5] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][6] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][9] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['contact'][4]+$tableau['contact'][5]+$tableau['contact'][6]+$tableau['contact'][8]+$tableau['contact'][9] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['affaire']['nb'] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['affaire']['mt'],0,'',' ') ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format(intval( ( ($tableau['contact'][4]+$tableau['contact'][8])/($tableau['contact'][4]+$tableau['contact'][5]+$tableau['contact'][6]+$tableau['contact'][8]+$tableau['contact'][9]) )*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format(intval( ($tableau['affaire']['nb']/($tableau['contact'][8]+ $tableau['contact'][4]) )*100),0,'.',''); ?> %</td>

            </tr>
<?
}

function affect_valeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $critere = array ($req->f('Nom_vendeur'),$req->f('Nom_chef'),1);
   $valeur  = $indicateur[$req->f('Id_vendeur')];
}

function rupture_h1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[1] = array();
}

function rupture_b1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($totaux[1])) {
      foreach ($totaux[1] as $i=>$suite) {
         foreach ($suite as $j=>$k) {
            $totaux[2][$i][$j] += $k;
         }
      }
   }
   ecrit_ligne ($critere[1], "D3DCE3", $totaux[1]);
}

function rupture_h2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[2] = array();
}

function rupture_b2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   ecrit_ligne ("Total général", "D3DCC3", $totaux[2]);
}

function coeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($valeur)) {
      foreach ($valeur as $i=>$suite) {
         foreach ($suite as $j=>$k) {
            $totaux[1][$i][$j] += $k;
         }
      }
   }
   ecrit_ligne ($critere[0], "WhiteSmoke", $valeur);
}

?>

         <table class="resultat">
            <tr>
               <td></td>
               <td class="resultat_tittle" colspan = 4><A style="text-decoration: none;color:gray">ACTIVITE TELEPHONIQUE</A></td>
               <td class="resultat_tittle" colspan = 6><A style="text-decoration: none;color:gray">ACTIVITE TERRAIN</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">ACTIVITE ENTREES EN PORTEFEUILLE</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">RATIOS D'ACTIVITE</A></td>
            </tr>
            <tr>
               <td></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(1)">Appels Prospections</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(2)">Appels Relance d'Action</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(3)">Appels Suivi de Portefeuille</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(3)">Total Appels</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(4)">Rendez-vous Découverte</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(5)">Rendez-vous Prospection Physique</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(6)">Rendez-vous Suivi d'Affaires</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(7)">Rendez-vous Signature</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(8)">Rendez-vous Prise de Mesures</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(8)">Total RDV</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(9)">Nombre d'Affaires</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(10)">Valeur des Affaires</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(4)+(5) / (4)+(5)+(6)+(7)+(8)">Taux de RDV de Prospection</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(9) / (4)+(5)">Taux d'entrée en Portefeuille</A></td>
            </tr>
<?
include ('ress/rupture.php')
?>
         </table>
      </td>
   </tr>
</table>
