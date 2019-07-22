<?
/*-----------------PARAMETRES---------------------*/


/*------------------------------------------------*/
/*---- tableau des résultats ---------------------*/
/*------------------------------------------------*/

// $req_1 = affaires déja présentes à $Date_d avec date de photo à prendre
$req_1 = " select 
              max(fait.Date_crea) as Date_crea, 
              fait.Id_affaire 
           from 
              affaire_histo as fait $clause_type
           where 
              fait.Date_crea < '$Date_d'
           group by 
              fait.Id_affaire ";

// $req_2 = affaires déja présentes à $Date_d au statut "En portefeuille" avec le prix
$req_2 = " select
              affaire_histo.Id_affaire,
              affaire_histo.Prix,
              'avant' as quand
           from 
              affaire_histo, 
              ($req_1) as req_1
           where
              affaire_histo.Id_affaire = req_1.Id_affaire and
              affaire_histo.Date_crea = req_1.Date_crea and
              affaire_histo.Id_statut in (1,5) ";
               
// $req_3 = affaires créées pendant la période 
$req_3 = " select
              fait.Id_affaire,
              fait.Prix,
              'apres' as quand
           from 
              affaire, 
              affaire_histo as fait $clause_type 
           where
              fait.Id_affaire = affaire.Id_affaire and
              fait.Date_crea = affaire.Date_crea and
              affaire.Date_crea >= '$Date_d' and
              affaire.Date_crea <= '$Date_f' ";              
              
// $req_4 = affaires à étudier en fin de période
$req_4 = " ($req_2) union ($req_3) ";

// $req_5 = affaires à étudier en fin de période avec date de photo à prendre en compte 
$req_5 = " select
              req_4.Id_affaire,
              req_4.Prix,
              req_4.quand,
              max(affaire_histo.Date_crea) as Date_crea
           from 
              affaire_histo,
              ($req_4) as req_4 
           where
              req_4.Id_affaire = affaire_histo.Id_affaire and
              affaire_histo.Date_crea <= '$Date_f' 
           group by 
              req_4.Id_affaire,
              req_4.Prix,
              req_4.quand ";    

// $req_6 = affaires créées pendant la période avec date de photo à prendre en compte 
$req_6 = " select
              req_5.Id_affaire,
              req_5.Prix,
              req_5.quand,
              affaire_histo.Id_statut,
              affaire_histo.prix as prix_fin,
              affaire_histo.Id_utilisateur              
           from 
              affaire_histo,
              ($req_5) as req_5 
           where
              req_5.Id_affaire = affaire_histo.Id_affaire and
              req_5.Date_crea = affaire_histo.Date_crea ";                            

// $req_7 = group by du $req_6
$req_7 = " select
              count(req_6.Id_affaire) as nb,
              sum(req_6.Prix) as Prix,
              req_6.quand,
              req_6.Id_statut,
              sum(req_6.prix_fin) as Prix_fin,
              req_6.Id_utilisateur              
           from 
              ($req_6) as req_6 
           group by
               req_6.Id_utilisateur,
               req_6.Id_statut,
               req_6.quand ";  
/*     
$reqa = new db_sql($req_7);

while ($reqa->n()) {
   $indicateur[$reqa->f('Id_utilisateur')][$reqa->f('quand')]['nb']+=$reqa->f('nb');
   $indicateur[$reqa->f('Id_utilisateur')][$reqa->f('quand')]['Prix']+=$reqa->f('Prix');
   $indicateur[$reqa->f('Id_utilisateur')][$reqa->f('Id_statut')]['nb']+=$reqa->f('nb');
   $indicateur[$reqa->f('Id_utilisateur')][$reqa->f('Id_statut')]['Prix']+=$reqa->f('Prix_fin');
}

/*-------- Parametre des ruptures -----------------*/

/*
function ecrit_ligne ($titre, $couleur, $tableau) {
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $titre ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['avant']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['avant']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['apres']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['apres']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['2']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['2']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['3']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['3']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['4']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['4']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['1']['nb']+$tableau['5']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['1']['Prix'] + $tableau['5']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format(intval( ($tableau['2']['nb']/$tableau['avant']['nb'])*100),0,'.',''); ?> %</td>
            </tr>
<?
}
*/

$req_7 = " select
              count(req_6.Id_affaire) as nb,
              sum(req_6.Prix) as Prix,
              req_6.quand,
              req_6.Id_statut,
              sum(req_6.prix_fin) as Prix_fin,
              req_6.Id_utilisateur              
           from 
              ($req_6) as req_6 
           group by
               req_6.Id_utilisateur,
               req_6.Id_statut,
               req_6.quand ";  
               
if ($Id_type and ($Id_type != 'T')) {
   $tables = " ,client ";
   $where = "affaire.id_client = client.id_client and
            client.id_Type = $Id_type and ";
}

$req_7 = " select
               1 as nb,
               Date_prev,
               Date_crea,
               Id_statut,
               Prix,
               Id_utilisateur
           from 
               affaire $tables
           where $where
               affaire.Date_crea <= '$Date_f' and 
               not (affaire.Date_prev < '$Date_d' and (Id_statut=2 or Id_statut=3 or Id_statut=4 or Id_statut=6))";                
               
     
$reqa = new db_sql($req_7);

while ($reqa->n()) {

   if (($reqa->f('Date_crea'))<$Date_d) {
      $indicateur[$reqa->f('Id_utilisateur')]['avant']['nb']+=$reqa->f('nb');
      $indicateur[$reqa->f('Id_utilisateur')]['avant']['Prix']+=$reqa->f('Prix');
   } else {
      $indicateur[$reqa->f('Id_utilisateur')]['apres']['nb']+=$reqa->f('nb');
      $indicateur[$reqa->f('Id_utilisateur')]['apres']['Prix']+=$reqa->f('Prix');
   }
   if ( ($reqa->f('Date_prev') <= $Date_f) 
      && 
        (
          ($reqa->f('Id_statut') == 2) 
          or 
          ($reqa->f('Id_statut') == 3) 
          or 
          ($reqa->f('Id_statut') == 4)
          or 
          ($reqa->f('Id_statut') == 6)
        ) 
      ) {
		$stat = $reqa->f('Id_statut');
		if ($stat == 6) { // On met les affaires signées avec les affaires gagnées
			$stat = 2;
		}
      $indicateur[$reqa->f('Id_utilisateur')][$stat]['nb']+=$reqa->f('nb');
      $indicateur[$reqa->f('Id_utilisateur')][$stat]['Prix']+=$reqa->f('Prix');
   } else {
      $indicateur[$reqa->f('Id_utilisateur')]['solde']['nb']+=$reqa->f('nb');
      $indicateur[$reqa->f('Id_utilisateur')]['solde']['Prix']+=$reqa->f('Prix');
   }
   
}

/*-------- Parametre des ruptures -----------------*/

function ecrit_ligne ($titre, $couleur, $tableau) {
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $titre ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['avant']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['avant']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['apres']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['apres']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['2']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['2']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['3']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['3']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['4']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['4']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo $tableau['solde']['nb'] ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format($tableau['solde']['Prix'],0,'',' ') ?></td>
               <td class="resultat_list" align="right" bgcolor="<? echo $couleur ?>"><? echo @number_format(intval( ($tableau['2']['nb']/$tableau['avant']['nb'])*100),0,'.',''); ?> %</td>
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
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Portefeuille début de période</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Entrées</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Gagnées + Signées</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Perdues</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Abandonnées</A></td>
               <td class="resultat_tittle" colspan = 2><A style="text-decoration: none;color:gray">Portefeuille fin de période</A></td>
               <td class="resultat_tittle" rowspan = 2><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(5)/(1)">Taux de transformation</A></td>
            </tr>
            <tr>
               <td></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(1)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(2)">Valeur</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(3)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(4)">Valeur</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(5)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(6)">Valeur</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(7)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(8)">Valeur</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(9)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(10)">Valeur</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(1)+(3)-(5)-(7)-(9)">Nombre</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(2)+(4)-(6)-(8)-(10) aux changements de valeurs d'affaires prêt">Valeur</A></td>
            </tr>
<?
include ('ress/rupture.php')
?>
         </table>
      </td>
   </tr>
</table>
