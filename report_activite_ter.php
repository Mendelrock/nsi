<?
/*-----------------PARAMETRES---------------------*/


/*------------------------------------------------*/
/*---- tableau des résultats ---------------------*/
/*------------------------------------------------*/

// Répartition des clients sur les opérations et les vendeurs
$reqa = new db_sql("
   SELECT
      liste.id_liste,
      liste.lb_liste,
      liste.date_debut,
      liste.date_fin,
      portefeuille.id_client,
      portefeuille.id_utilisateur
   FROM
      portefeuille
      inner join liste_client on (liste_client.id_client = portefeuille.id_client)
      inner join liste on (liste.id_liste = liste_client.id_liste and not (liste.date_fin < '$Date_d' or liste.date_debut > '$Date_f'))");
      
while ($reqa->n()) {
   $liste_des_listes[$reqa->f('id_utilisateur')][$reqa->f('id_liste')] = 1;
   if (!$liste_du_client[$reqa->f('id_client')]) {
   	$liste_du_client[$reqa->f('id_client')] = $reqa->f('id_liste');
   	$nb_client_de_la_liste[$reqa->f('id_liste')]++;
   }
   if (!$liste_des_clients[$reqa->f('id_client')]) {
   	$liste_des_clients[$reqa->f('id_client')] = $reqa->f('id_client');
   }
   if ($reqa->f('id_liste') and !$info_liste[$reqa->f('id_liste')]) {
   	$info_liste[$reqa->f('id_liste')][date_debut] = $reqa->f('date_debut');
   	$info_liste[$reqa->f('id_liste')][date_fin] =   $reqa->f('date_fin');
   	$info_liste[$reqa->f('id_liste')][lb_liste] =   $reqa->f('lb_liste');
   }
}


$info_liste[-1][lb_liste] = 'Autre (hors liste)';


// Contacts
// Interactions
$reqa = new db_sql("
   SELECT
      Id_client,
      Id_utilisateur,
      Id_teneur,
      Date_prev
   FROM
      interaction $clause_type
   WHERE
      Notes is not null AND
      Date_prev >= '$Date_d' AND
      Date_prev <= '$Date_f'");

while ($reqa->n()) {
	$id_client = $reqa->f('Id_client');
	$dans_une_liste = false;
	if ($liste_du_client[$id_client]) {
		$id_liste = $liste_du_client[$id_client];
		if (($reqa->f('Date_prev') <= $info_liste[$id_liste][date_fin]) and ($reqa->f('Date_prev') >= $info_liste[$id_liste][date_debut])) {
			$dans_une_liste = true;
         $indicateur[$reqa->f('Id_utilisateur')][$id_liste]['contact'][$reqa->f('Id_teneur')]++;
		}
	} 
	if (!$dans_une_liste) {
         $indicateur[$reqa->f('Id_utilisateur')][-1]['contact'][$reqa->f('Id_teneur')]++;
	}
}
// Ca portefeuille
$reqa = new db_sql("
        SELECT
                Prix,
                Id_client,
                Date_crea,
                Id_utilisateur
        FROM
                affaire $clause_type
        WHERE
                Date_crea >= '$Date_d' AND
                Date_crea <= '$Date_f'");

while ($reqa->n()) {
	$id_client = $reqa->f('Id_client');
	$dans_une_liste = false;
	if ($liste_du_client[$id_client]) {
		$id_liste = $liste_du_client[$id_client];
		if (($reqa->f('Date_crea') <= $info_liste[$id_liste][date_fin]) and ($reqa->f('Date_crea') >= $info_liste[$id_liste][date_debut])) {
			$dans_une_liste = true;
         $indicateur[$reqa->f('Id_utilisateur')][$id_liste]['affaire'][mt] += $reqa->f('Prix')*1;
         $indicateur[$reqa->f('Id_utilisateur')][$id_liste]['affaire'][nb] += 1;
		}
	} 
	if (!$dans_une_liste) {
         $indicateur[$reqa->f('Id_utilisateur')][-1]['affaire'][mt] += $reqa->f('Prix')*1;
         $indicateur[$reqa->f('Id_utilisateur')][-1]['affaire'][nb] += 1;
	}
}

/*-------- Parametre des ruptures -----------------*/

function ecrit_ligne ($titre, $couleur, $tableau) {
?>
            <tr>
               <td class="resultat_list" align="<? if ($couleur == "#EEEEFF") {echo "right";} else {echo "left";} ?>"  bgcolor="<? echo $couleur ?>"><? echo $titre ?></td>
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
   global $critere, $valeur, $indicateur, $req, $totaux, $aff, $con, $arg;
   $critere = array ($req->f('Nom_vendeur'),$req->f('Nom_chef'),1);
}

function rupture_h1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[1] = array();
}

function rupture_b1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($totaux[1])) {
	   foreach ($totaux[1] as $i=>$suite) {
	      if (is_array($suite)) {
		      foreach ($suite as $j=>$k) {
	         	$totaux[2][$i][$j] += $k;
	   		}
			}
	   }
   }
   ecrit_ligne ($critere[1], "#CCFFCC", $totaux[1]);
}

function rupture_h2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[2] = array();
}

function rupture_b2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   ecrit_ligne ("Total général", "#FFBBBB", $totaux[2]);
}

function coeur () {
	global $critere, $valeur, $indicateur, $req, $totaux, $liste_des_listes, $indicateur, $info_liste;
   $valeur = array();
   $liste_des_listes[$req->f('Id_vendeur')][-1] = "Autre";
	foreach ($liste_des_listes[$req->f('Id_vendeur')] as $id_liste => $x) {
		$sous_valeur = $indicateur[$req->f('Id_vendeur')][$id_liste];
		$sous_valeur[titre] = $info_liste[$id_liste][lb_liste];
		$sous_valeurs[$id_liste] = $sous_valeur;
      foreach ($sous_valeur as $i=>$suite) {
	      if (is_array($suite)) {
		      foreach ($suite as $j=>$k) {
	         	$valeur[$i][$j] += $k;
	   		}
			}
      }
	}
	ecrit_ligne ($critere[0], "#DDDDFF", $valeur);
	foreach ($sous_valeurs as $titre => $sous_valeur) {
		ecrit_ligne ($sous_valeur[titre], "#EEEEFF", $sous_valeur);
	}
   foreach ($valeur as $i=>$suite) {
      if (is_array($suite)) {
	      foreach ($suite as $j=>$k) {
         	$totaux[1][$i][$j] += $k;
   		}
		}
   }
}

?>

         <table class="resultat">
            <tr>
               <td></td>
               <td align=center class="resultat_tittle" colspan = 6><P style="text-decoration: none; color:gray">RENDEZ VOUS</P></td>
               <td align=center class="resultat_tittle" colspan = 2><P style="text-decoration: none; color:gray">ENTREES EN PORTEFEUILLE</P></td>
               <td align=center class="resultat_tittle" colspan = 2><P style="text-decoration: none; color:gray">RATIOS</P></td>
            </tr>
            <tr>
               <td width=20%></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(a)">Rendez-vous Découverte</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(b)">Rendez-vous Prospection Physique</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(d)">Rendez-vous Suivi d'Affaires</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(f)">Rendez-vous Signature</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(g)">Rendez-vous Prise de Mesures</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(h)">Total RDV</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(i)">Nombre d'Affaires</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(j)">Valeur des Affaires</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="((a)+(b))/(h)">Taux de RDV de Prospection</P></td>
               <td align=center width=8% class="resultat_tittle"><P style="text-decoration: none;color:gray" TITLE="(i)/((a)+(b))">Taux d'entrée en Portefeuille</P></td>
            </tr>
<?
include ('ress/rupture.php')
?>
         </table>
      </td>
   </tr>
</table>
