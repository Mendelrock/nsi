<?
/*-----------------PARAMETRES---------------------*/
/* Id_utilisateur
/*------------------------------------------------*/

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
   if (req_sim("select id_responsable from utilisateur where id_utilisateur = $Id_utilisateur","id_responsable")) {
      $where = " AND affaire.Id_utilisateur = $Id_utilisateur ";
   } else {
      $table = " , utilisateur ";
      $where = " AND affaire.Id_utilisateur = utilisateur.id_utilisateur
                 AND utilisateur.id_responsable = $Id_utilisateur ";
   }
}

$req = new db_sql("
               SELECT
                  SUM(affaire_detail.Qt) as Qt,
                  SUM(affaire_detail.Qt*affaire_detail.Prix) as Px,
                  produit.Lb_produit,
                  gam.Lb_produit as Lb_gam,
                  affaire.Id_statut
               FROM
                  affaire,
                  affaire_detail,
                  produit,
                  produit as gam
                  $table
              WHERE
                  affaire.Id_affaire = affaire_detail.Id_affaire AND
                  affaire_detail.Id_produit = produit.Id_produit AND
                  produit.Id_produit_pere = gam.Id_produit AND
                  affaire.id_statut in (1,2,3,4)
                  $where
              GROUP BY
                  produit.Lb_produit,
                  gam.Lb_produit,
                  affaire.id_statut");

while ($req->n()) {
   $indicateur[$req->f('Lb_gam')][$req->f('Lb_produit')]['Qt'][$req->f('Id_statut')] = $req->f('Qt');
   $indicateur[$req->f('Lb_gam')][$req->f('Lb_produit')]['Px'][$req->f('Id_statut')] = $req->f('Px');
}

/*-------- Parametre des ruptures -----------------*/
$nb = 2;

$req = new db_sql("
                   select
                      gam.Lb_produit as Lb_gam,
                      produit.Lb_produit
                   from
                      produit as gam,
                      produit
                   where
                      produit.Id_produit_pere = gam.Id_produit
                   order by
                      gam.lb_produit,
                      produit.lb_produit");

function affect_valeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $critere = array ($req->f('Lb_produit') ,$req->f('Lb_gam') ,1);
   $valeur  = $indicateur[$req->f('Lb_gam')][$req->f('Lb_produit')];
}

function rupture_h1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[1] = array();
}

function rupture_b1 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($totaux[1])) {
      reset ($totaux[1]);
      while (list($i,$j) = each($totaux[1])) {
         reset ($j);
         while (list($k,$l) = each($j)) {
            $totaux[2][$i][$k] += $l;
         }
      }
   }

?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo $critere[1] ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['4']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['4']/array_sum($totaux[1]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Px']['4']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['3']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['3']/array_sum($totaux[1]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Px']['3']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['2']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['2']/array_sum($totaux[1]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Px']['2']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['1']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Qt']['1']/array_sum($totaux[1]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['Px']['1']),2,'.',''); ?></td>
            </tr>
<?
}

function rupture_h2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $totaux[2] = array();
}

function rupture_b2 () {
   global $critere, $valeur, $indicateur, $req, $totaux;
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="D3DCC3">Total général</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['4']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['4']/array_sum($totaux[2]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Px']['4']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['3']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['3']/array_sum($totaux[2]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Px']['3']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['2']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['2']/array_sum($totaux[2]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Px']['2']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['1']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Qt']['1']/array_sum($totaux[2]['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['Px']['1']),2,'.',''); ?></td>
            </tr>
<?
}

function coeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($valeur)) {
      reset ($valeur);
      while (list($i,$j) = each($valeur)) {
         reset ($j);
         while (list($k,$l) = each($j)) {
            $totaux[1][$i][$k] += $l;
         }
      }
   }
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo $critere[0] ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['4']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['4']/array_sum($valeur['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Px']['4']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['3']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['3']/array_sum($valeur['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Px']['3']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['2']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['2']/array_sum($valeur['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Px']['2']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['1']),0,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Qt']['1']/array_sum($valeur['Qt'])*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['Px']['1']),2,'.',''); ?></td>
            </tr>
<?
}
?>
               <td class="menu_haut"> Consultant : <? drop_down_droit("O","SELECT distinct
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
                <td>
                    <input class="requet_button" type="submit" value="Appliquer" OnClick="return champ_oblig('report')"></td>
                </td>

            </tr>
         </table>
</form>
         <table class="resultat">
            <tr>
               <td rowspan="2"></td>
               <td class="resultat_tittle" colspan="3">En Signature</td>
               <td class="resultat_tittle" colspan="3">En négociation</td>
               <td class="resultat_tittle" colspan="3">En portefeuille</td>
               <td class="resultat_tittle" colspan="3">En projet</td>
            </tr>
            <tr>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates">Qt</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates / Somme des quantité des affaires correspondantes quelque soit le statut du portefeuille élargi">%</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des lignes d'affaires correspondantes">CA</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates">Qt</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates / Somme des quantité des affaires correspondantes quelque soit le statut du portefeuille élargi">%</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des lignes d'affaires correspondantes">CA</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates">Qt</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates / Somme des quantité des affaires correspondantes quelque soit le statut du portefeuille élargi">%</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des lignes d'affaires correspondantes">CA</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates">Qt</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des quantités des affaires correspondates / Somme des quantité des affaires correspondantes quelque soit le statut du portefeuille élargi">%</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des lignes d'affaires correspondantes">CA</A></td>
            </tr>
<?
        include ('ress/rupture.php');
?>
         </table>
      </td>
   </tr>
</table>