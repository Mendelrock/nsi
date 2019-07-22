<?
/*-----------------PARAMETRES---------------------*/
if (!$Date_d || empty($Date_d)) {
                $Date_d=substr(aujourdhui(),0,8)."01";
        }
if (!$Date_f || empty($Date_f)) {
                $Date_f=substr(aujourdhui(),0,8).date("t");
        }

if (!$Mode)
   $Mode = 'secteur';

/*------------------------------------------------*/
/*---- tableau des r�sultats ---------------------*/
//Nb de decouverte, Nb d'interaction de m�dia visite et de teneur
//d�couverte r�alis�e entre les dates sp�cifi�es

if ($Mode == 'secteur') {
   $tablei  = 'portefeuille';
   $tablea  = 'portefeuille';
   $tablev  = ',portefeuille';
   $linki   = 'interaction.Id_client = portefeuille.Id_client AND';
   $linka   = 'affaire.Id_client = portefeuille.Id_client AND';
   $linkf   = 'consultant.Id_profil = 1 AND';
} else {
   $tablei  = 'interaction';
   $tablea  = 'affaire';
   $tablev  = '';
   $linki   = '';
   $linka   = '';
}

$req = new db_sql("
   SELECT
      count(*) as compte,
      $tablei.Id_utilisateur
   FROM
      interaction $tablev
   WHERE $linki
      Id_teneur=1 AND
      Id_media=1 AND
      Notes is not null AND
      Date_prev>='$Date_d' AND
      Date_prev<='$Date_f'
   GROUP BY
      $tablei.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['nb_decouverte'] = $req->f('compte');
}
// Nb affaire cree entre les dates sp�cifi�es dont le statut n'est pas en projet
$req = new db_sql("
        SELECT
              count(*) as compte,
              $tablea.Id_utilisateur
        FROM
              affaire $tablev
        WHERE $linka
              Id_statut<>1 AND
              Date_crea>='$Date_d' AND
              Date_crea<='$Date_f'
        GROUP BY
              $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['nb_affaire_cree'] = $req->f('compte');
}
// Nb affaire cree entre les dates sp�cifi�es dont le statut est en projet
$req = new db_sql("
        SELECT
              count(*) as compte,
             $tablea.Id_utilisateur
        FROM
              affaire $tablev
        WHERE $linka
              Id_statut = 1 AND
              Date_crea>='$Date_d' AND
              Date_crea<='$Date_f'
        GROUP BY
              $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['nb_affaire_cree_port'] = $req->f('compte');
}
// Ca en prevision + en signature, somme des CA des affaires dont la date de signature
// (pr�vision) est entre les dates sp�cifi�es et dont le statut est en n�go ou en signature
$req = new db_sql("
        SELECT
                SUM(Prix) as compte,
                $tablea.Id_utilisateur
        FROM
                affaire $tablev
        WHERE $linka
                (Id_statut=3 OR
                 Id_statut=4) AND
                Date_prev>='$Date_d' AND
                Date_prev<='$Date_f'
        GROUP BY
                $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['ca_prev_sign'] = $req->f('compte');
}
// Ca sign� entre les dates sp�cifi�es, somme des CA des affaires de statut sign� dont la date de
// cloture est entre les dates sp�cifi�es
$req = new db_sql("
        SELECT
                SUM(Prix) as compte,
                $tablea.Id_utilisateur
        FROM
                affaire $tablev
        WHERE $linka
                Id_statut=5 AND
              Date_prev>='$Date_d' AND
              Date_prev<='$Date_f'
        GROUP BY
                $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['ca_signe'] = $req->f('compte');
}
// Ca sign� entre les dates sp�cifi�es et cr�� entre les dates sp�cifi�es, somme des CA des affaires de statut sign�
// dont la date de cloture est entre les dates sp�cifi�es et la date de cr�ation est entre les dates sp�cifi�es
$req = new db_sql("
        SELECT
              SUM(Prix) as compte,
              $tablea.Id_utilisateur
        FROM
              affaire $tablev
        WHERE $linka
              Id_statut=5 AND
              Date_crea>='$Date_d' AND
              Date_crea<='$Date_f' AND
              Date_prev>='$Date_d' AND
              Date_prev<='$Date_f'
        GROUP BY
              $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['ca_sign_cree'] = $req->f('compte');
}
// Nb affaire de statut en portefeuille, en nego, en signature quelque soient les dates
$req = new db_sql("
        SELECT
                count(*) as compte,
                $tablea.Id_utilisateur
        FROM
                affaire $tablev
        WHERE   $linka
                (Id_statut=2 OR
                Id_statut=3 OR
                Id_statut=4)
        GROUP BY
                $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['nb_affaire_portefeuille'] = $req->f('compte');
}
// Ca portefeuille, somme des CA des affaires de statut en portefeuille, en signature, en
// nego quelque soient les dates
$req = new db_sql("
        SELECT
                SUM(Prix) as compte,
                $tablea.Id_utilisateur
        FROM
                affaire $tablev
        WHERE   $linka
                (Id_statut=2 OR
                Id_statut=3 OR
                Id_statut=4)
        GROUP BY
                $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['ca_portefeuille'] = $req->f('compte');
}
// Nb affaire sign� entre les dates sp�cifi�es, nombre d'affaires de statut sign� dont la date de signature
// est entre les dates sp�cifi�es
$req = new db_sql("
        SELECT
                count(*) as compte,
                $tablea.Id_utilisateur
        FROM
                affaire $tablev
        WHERE   $linka
                Id_statut=5 AND
                Date_prev>='$Date_d' AND
                Date_prev<='$Date_f'
        GROUP BY
                $tablea.Id_utilisateur");

while ($req->n()) {
   $indicateur[$req->f('Id_utilisateur')]['nb_affaire_sign'] = $req->f('compte');
}
/*-------- Param�tre des ruptures -----------------*/
$nb = 2;

$req = new db_sql("
   SELECT
      consultant.Id_utilisateur,
      consultant.Nom as Nom_consultant,
      chef.Nom    as Nom_chef
   FROM
      utilisateur as consultant,
      utilisateur as chef
   WHERE $linkf
      consultant.Id_responsable = chef.Id_utilisateur
   ORDER BY
      chef.Nom,
      consultant.Nom");

function affect_valeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   $critere = array ($req->f('Nom_consultant'),$req->f('Nom_chef'),1);
   $valeur  = $indicateur[$req->f('Id_utilisateur')];
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
         $totaux[2][$i] += $j;
      }
   }
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo $critere[1] ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['nb_affaire_cree']/$totaux[1]['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['nb_affaire_cree_port']/$totaux[1]['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[1]['ca_prev_sign'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format($totaux[1]['ca_signe'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['ca_signe']/$totaux[1]['ca_prev_sign']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['ca_sign_cree']/$totaux[1]['nb_affaire_cree']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['ca_portefeuille']/$totaux[1]['nb_affaire_portefeuille']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCE3"><? echo @number_format(intval($totaux[1]['ca_signe']/$totaux[1]['nb_affaire_sign']),2,'.',''); ?></td>
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
               <td class="resultat_list" align="right"  bgcolor="D3DCC3">Total g�n�ral</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['nb_affaire_cree']/$totaux[2]['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['nb_affaire_cree_port']/$totaux[2]['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format($totaux[2]['ca_prev_sign'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format($totaux[2]['ca_signe'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['ca_signe']/$totaux[2]['ca_prev_sign']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['ca_sign_cree']/$totaux[2]['nb_affaire_cree']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['ca_portefeuille']/$totaux[2]['nb_affaire_portefeuille']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="D3DCC3"><? echo @number_format(intval($totaux[2]['ca_signe']/$totaux[2]['nb_affaire_sign']),2,'.',''); ?></td>
            </tr>
<?
}
function coeur () {
   global $critere, $valeur, $indicateur, $req, $totaux;
   if (is_array($valeur)) {
      reset ($valeur);
      while (list($i,$j) = each($valeur)) {
         $totaux[1][$i] += $j;
      }
   }
?>
            <tr>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo $critere[0] ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['nb_affaire_cree']/$valeur['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['nb_affaire_cree_port']/$valeur['nb_decouverte']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur['ca_prev_sign'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format($valeur['ca_signe'],2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['ca_signe']/$valeur['ca_prev_sign']*100),0,'.',''); ?> %</td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['ca_sign_cree']/$valeur['nb_affaire_cree']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['ca_portefeuille']/$valeur['nb_affaire_portefeuille']),2,'.',''); ?></td>
               <td class="resultat_list" align="right" bgcolor="WhiteSmoke"><? echo @number_format(intval($valeur['ca_signe']/$valeur['nb_affaire_sign']),2,'.',''); ?></td>
            </tr>
<?
}
?>
                           <td class="menu_haut">Mode :
                           <?drop_down_droit("O","", "Mode", "", "", $Mode,false, "client","N", "secteur|consultant", "Secteur|Consultant");?></td>
                           <td class="menu_haut"> Periode :
                           <? champ_date(Date_d, $Date_d, "periode", "O"); ?> au <? champ_date(Date_f, $Date_f, "periode", "O"); ?></td>
                           </td>
                           <td>
                              <input class="requet_button" type="submit" value="Appliquer" OnClick="return champ_oblig('report')"></td>
                           </td>
            </tr>
         </table>
</form>
         <table class="resultat">
            <tr>
               <td></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Nombre d'affaires cr��es entre les dates sp�cifi�es dont le statut n'est pas en projet / Nombre d'contacts de m�dia visite et de teneur d�courverte r�alis�es entre les dates sp�cifi�es (1)">Entr�es en portefeuille (hors projet) / Nb RDV d�couverte</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Nombre d'affaires cr��es entre les dates sp�cifi�es dont le statut est en projet / Nombre d'contacts de m�dia visite et de teneur d�courverte r�alis�es entre les dates sp�cifi�es (1 bis)">Entr�es en portefeuille (projet) / Nb RDV d�couverte</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires dont la date de pr�vision de signature est entre les dates sp�cifi�es et dont le statut est en n�go ou en signature (2)">CA en pr�vision + en signature</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut sign� dont la date de cl�ture est entre les dates sp�cifi�es (3)">CA sign�</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut sign� dont la date de cl�ture est entre les dates sp�cifi�es (3) / Somme des CA des affaires dont la date de pr�vision de signature est entre les dates sp�cifi�es et dont le statut est en n�go ou en signature (2) (4)">CA sign� / CA en pr�vision + en signature</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut sign� dont la date de cl�ture est entre les dates sp�cifi�es et la date de cr�ation est entre les dates sp�cifi�es / Nombre d'affaires cr��es entre les dates sp�cifi�es dont le statut n'est pas en projet (1) (5)">CA sign� / Entr�e en portefeuille</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut en portefeuille, en signature, en n�go quelques soient les dates / Nombre d'affaires de statut en portefeuille, en signature, en nego quelque soient les dates (6)">Panier moyen portefeuille (hors projet)</A></td>
               <td class="resultat_tittle"><A style="text-decoration: none;color:gray" HREF="javascript:void(0)" TITLE="Somme des CA des affaires de statut sign� dont la date de cl�ture est entre les dates sp�cifi�es (3) / Nombre d'affaires de statut sign� dont la date de signature est entre les dates sp�cifi�es (7)">Panier moyen portefeuille sign�</A></td>
            </tr>
<?
include ('ress/rupture.php')
?>
         </table>
      </td>
   </tr>
</table>