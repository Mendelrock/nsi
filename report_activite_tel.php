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

// Contacts
$reqa = new db_sql("
   SELECT
      Date_prev,
      Id_client,
      Id_teneur,
      argumente,
      Id_utilisateur
   FROM
      interaction $clause_type
   WHERE
      id_teneur in (1,3) AND
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
			$nb_tot[$reqa->f('Id_utilisateur')][$id_liste]++;
			if ($reqa->f('argumente')) {
				$nb_tot_arg[$reqa->f('Id_utilisateur')][$id_liste]++;
			}
		}
	} 
	if (!$dans_une_liste) {
			$nb_tot_temp[$reqa->f('Id_utilisateur')][$id_client]++;
			if ($reqa->f('argumente')) {
				$nb_tot_arg_temp[$reqa->f('Id_utilisateur')][$id_client]++;
			}
			if ($reqa->f('Id_teneur')==3) {
            $entrant[$id_client] = 1;			
			}
	}
}

$info_liste[-1][lb_liste] = 'Sur Contacts Entrants';
$info_liste[-2][lb_liste] = 'Autres Appels de Prospection';

if (is_array($nb_tot_temp)) {
	foreach ($nb_tot_temp as $vendeur => $reste) {
		foreach ($reste as $id_client => $nbi) {
			if ($entrant[$id_client]) {
				$nb_tot[$vendeur][-1] += $nbi;
			} else {
				$nb_tot[$vendeur][-2] += $nbi;
			}
	   }
	}
}

if (is_array($nb_tot_arg_temp)) {
	foreach ($nb_tot_arg_temp as $vendeur => $reste) {
		foreach ($reste as $id_client => $nbi) {
			if ($entrant[$id_client]) {
				$nb_tot_arg[$vendeur][-1] += $nbi;
			} else {
				$nb_tot_arg[$vendeur][-2] += $nbi;
			}
	   }
	}
}

// RdV découverte
$reqa = new db_sql("
   SELECT
      Date_crea,
      Id_client,
      Id_utilisateur
   FROM
      interaction $clause_type
   WHERE
      id_teneur = 8 AND
      Date_crea >= '$Date_d' AND
      Date_crea <= '$Date_f'");

while ($reqa->n()) {
	$id_client = $reqa->f('Id_client');
	$dans_une_liste = false;
	if ($liste_du_client[$id_client]) {
		$id_liste = $liste_du_client[$id_client];
		if ( ($reqa->f('Date_crea') <= $info_liste[$id_liste][date_fin]) and 
		     ($reqa->f('Date_crea') >= $info_liste[$id_liste][date_debut])
		   ) {
			$dans_une_liste = true;
			$nb_tot_rdv_dev[$reqa->f('Id_utilisateur')][$id_liste]++;
		} 
	} 
	if (!$dans_une_liste) {
		if ($entrant[$id_client]) {
			$nb_tot_rdv_dev[$reqa->f('Id_utilisateur')][-1]++;
		} else {
			$nb_tot_rdv_dev[$reqa->f('Id_utilisateur')][-2]++;
		}
	}
}

// Contact
$reqa = new db_sql("
   SELECT
      contact.Date_crea,
      contact.Id_client,
      portefeuille.Id_utilisateur
   FROM
      portefeuille,
      contact $clause_type
   WHERE
      portefeuille.id_client = contact.id_client and
      contact.Date_crea >= '$Date_d' AND
      contact.Date_crea <= '$Date_f'");

while ($reqa->n()) {
	$id_client = $reqa->f('Id_client');
	$dans_une_liste = false;
	if ($liste_du_client[$id_client]) {
		$id_liste = $liste_du_client[$id_client];
		if (($reqa->f('Date_crea') <= $info_liste[$id_liste][date_fin]) and ($reqa->f('Date_crea') >= $info_liste[$id_liste][date_debut])) {
			$dans_une_liste = true;
			$nb_cont_cree[$reqa->f('Id_utilisateur')][$id_liste]++;
		}
	} 
	if (!$dans_une_liste) {
			if ($entrant[$id_client]) {
				$nb_cont_cree[$reqa->f('Id_utilisateur')][-1]++;
			} else {
				$nb_cont_cree[$reqa->f('Id_utilisateur')][-2]++;
			}
	}
}

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

// Suivi de portefeuille
$reqa = new db_sql("
   SELECT 
   	count(distinct(top.id_affaire)) as aff,
   	count(distinct(interaction.id_interaction)) as con,
   	count(interaction.argumente) as arg,
   	top.Id_utilisateur 
   FROM
	  (SELECT
              affaire_histo.Date_crea,
              affaire_histo.Id_affaire,
              affaire_histo.Id_utilisateur
           from 
              affaire_histo, 
              ($req_1) as req_1
           where
              affaire_histo.Id_affaire = req_1.Id_affaire and
              affaire_histo.Date_crea = req_1.Date_crea and
              affaire_histo.Id_statut in (1,5)) top
		LEFT OUTER JOIN interaction on (
			interaction.id_affaire = top.id_affaire and 
			interaction.id_teneur in (2) AND
	      interaction.Notes is not null AND
	      interaction.Date_prev >= '$Date_d' AND
	      interaction.Date_prev <= '$Date_f' $clause )
	GROUP BY
		Id_utilisateur");
		 
while ($reqa->n()) {
	$aff[$reqa->f('Id_utilisateur')] = $reqa->f('aff');
	$con[$reqa->f('Id_utilisateur')] = $reqa->f('con');
	$arg[$reqa->f('Id_utilisateur')] = $reqa->f('arg');
}

/*-------- Parametre des ruptures -----------------*/
function ecrit_ligne ($titre, $couleur, $tableau) {
?>
            <tr>
               <td class="resultat_list" align="<? if ($couleur == "#EEEEFF") {echo "right";} else {echo "left";} ?>" bgcolor="<? echo $couleur; ?>" nowrap><? echo $titre ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau['&'] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[a] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[b] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? if (($couleur == "#EEEEFF") and ($titre != 'Sur Contacts Entrants') and ($titre != 'Autres Appels de Prospection')) echo @number_format(intval($tableau[b]/$tableau['&']*100),0,'.','')." %"; ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format(intval($tableau[b]/$tableau[a]*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[d] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format(intval($tableau[d]/$tableau[b]*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[f] ?></td>
               <? if ($couleur == "#EEEEFF") { ?>
               <td class="resultat_list" bgcolor="#FFFFFF" colspan = 4> </td>
               <? } else { ?>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[g] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[h] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[i] ?></td>
               <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo @number_format(intval($tableau[i]/$tableau[g]*100),0,'.',''); ?> %</td>
               <? } ?>
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
            $totaux[2][$i] += $suite;
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
	global $critere, $valeur, $indicateur, $req, $totaux, $liste_des_listes, $nb_client_de_la_liste, $nb_tot, $nb_tot_arg, $nb_tot_rdv_dev, $nb_cont_cree, $info_liste, $aff, $con, $arg;
   $valeur = array();
   $valeur[g] = $aff[$req->f('Id_vendeur')]; 
   $valeur[h] = $con[$req->f('Id_vendeur')];
   $valeur[i] = $arg[$req->f('Id_vendeur')];
	$liste_des_listes[$req->f('Id_vendeur')][-1]=1;
	$liste_des_listes[$req->f('Id_vendeur')][-2]=1;		
	$sous_valeurs = array();
	foreach ($liste_des_listes[$req->f('Id_vendeur')] as $id_liste => $x) {
		$sous_valeur = array();
	   $sous_valeur['&'] = $nb_client_de_la_liste[$id_liste];
		$sous_valeur[a] = $nb_tot[$req->f('Id_vendeur')][$id_liste];
		$sous_valeur[b] = $nb_tot_arg[$req->f('Id_vendeur')][$id_liste];
		$sous_valeur[d] = $nb_tot_rdv_dev[$req->f('Id_vendeur')][$id_liste];
		$sous_valeur[f] = $nb_cont_cree[$req->f('Id_vendeur')][$id_liste];
		$sous_valeur[titre] = $info_liste[$id_liste][lb_liste];
		$sous_valeurs[$id_liste] = $sous_valeur;
      foreach ($sous_valeur as $i=>$suite) {
      	$valeur[$i] += $suite;
      }
	}
	ecrit_ligne ($critere[0], "#DDDDFF", $valeur);
	foreach ($sous_valeurs as $titre => $sous_valeur) {
		ecrit_ligne ($sous_valeur[titre], "#EEEEFF", $sous_valeur);
	}
	foreach ($valeur as $i=>$suite) {
   	$totaux[1][$i] += $suite;
	}

}

?>

         <table class="resultat">
            <tr>
               <td></td>
               <td align=center class="resultat_tittle" colspan = 8 bgcolor=white><P style="text-decoration: none;color:gray">APPELS DE PROSPECTION</P></td>
               <td align=center class="resultat_tittle" colspan = 6 bgcolor=white><P style="text-decoration: none;color:gray">APPELS DE SUIVI DE PORTEFEUILLE</P></td>
            </tr>
            <tr>
               <td width=16%></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(&)">Total<br/>Clients<br/>Liste</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(a)">Total<br/>Appels</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(b)">Total<br/>Contacts<br/>Arg.</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(b)/(&)">%<br/>Couverture<br/>Liste</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(b)/(a)">%<br/>Contacts<br/>Argumentés</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(d)">Nb RDV<br/>Découverte<br/>Obtenus</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(d)/(b)">% RDV <br/>sur Contacts<br/>Argumentés</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(f)">Nombre<br/>Interlocuteurs<br/>Créés</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(g)">Nombre<br/>Affaires<br/>Portefeuille<br/>début Mois</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(h)">Total<br/>Appels</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(i)">Total<br/>Contacts<br/>Argumentés</P></td>
               <td align=center width=7% class="resultat_tittle"><P style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="(i)/(g)">Taux<br/>Couverture</P></td>
            </tr>
<?

include ('ress/rupture.php');

?>
         </table>
      </td>
   </tr>
</table>
