<?php

/* Paramètre d'entrée */
if (!$Date_d || empty($Date_d)) {
    $Date_d=substr(aujourdhui(),0,8)."01";
}
if (!$Date_f || empty($Date_f)) {
    $Date_f=substr(aujourdhui(),0,8).date("t");
}

/*
 * Requête ressortant le nombre de feuilles de cote
 * créées, validées et produites sur une période
 */
$requête="
    SELECT
        concat(utilisateur.Nom, ' ', utilisateur.Prenom) as commercial ,
        utilisateur.Id_utilisateur as id_user,
        count(
            case
            	when dt.date_creation < '$Date_f' and dt.date_creation > '$Date_d' then 1
            	else null
            end
        ) as fdc_creer,
         count(
            case
            	when dt.date_vld_fdc < '$Date_f' and dt.date_vld_fdc > '$Date_d' then 1
            	else null
            end
        ) as fdc_valider,
        0 as fdc_produit,
        agence.Id_utilisateur as agence_id,
        agence.nom as agence_nom
    FROM
        affaire fait $clause_type,
        utilisateur,
        zdataset_fdcentete dt,
        utilisateur agence,
        doc
	WHERE
        agence.Id_utilisateur $critere_societe and
        dt.id_dataset = doc.id_dataset_entete and
        dt.affaire = fait.Id_affaire and
        doc.type_doc = 'fdc' and
        fait.Id_utilisateur = utilisateur.Id_utilisateur AND
        utilisateur.Id_responsable = agence.Id_utilisateur
    GROUP BY 
        utilisateur.Id_utilisateur ";

/*
 * Requête ressortant le nombre d'affaires signées
 * sans feuilles de cote
 */
$req_aff_sign_sans_fdc = "
    SELECT
        utilisateur.Id_utilisateur as commercial,
        count(affaire.Id_affaire) as affaire_signe_sans_fdc
    FROM
        utilisateur,
        statut,
        affaire
    left outer join (
        select
            distinct dt1.affaire
        from
            zdataset_fdcentete dt1,
            doc
        where
            doc.type_doc='fdc' and
            doc.id_dataset_entete = dt1.id_dataset )
    as sub on (sub.affaire = affaire.Id_affaire)
    WHERE
        affaire.Id_statut = 6 AND
        sub.affaire is null AND
        utilisateur.Id_utilisateur = affaire.id_utilisateur AND
        affaire.Id_statut=statut.Id_statut
    GROUP BY 
        utilisateur.Id_utilisateur  ";

/*
 * Requête resortant le nombre d'affaires signées
 * avec feuilles de cote en cours
 */
$req_aff_sign_avec_fdc_en_cours="
    SELECT
        utilisateur.Id_utilisateur as commercial,
        count(affaire.Id_affaire) as affaire_signe_with_fdc_en_cours
    FROM
        affaire,
        utilisateur,
        statut,
        doc
    LEFT JOIN
        zdataset_fdcentete dt on (dt.id_dataset = doc.id_dataset_entete )
    WHERE
        utilisateur.Id_utilisateur = affaire.Id_utilisateur and
        doc.type_doc='fdc' AND
        dt.affaire = affaire.Id_affaire AND
        dt.statut = 'En cours' AND
        affaire.Id_statut = statut.Id_statut AND
        statut.Id_statut = 6
    GROUP BY 
        utilisateur.Id_utilisateur ";

/*
 * Requête resortant le nombre d'affaires non signées
 * avec feuilles de cote validées
 */
$req_aff_non_sign_avec_fdc_valid = "
    SELECT
        utilisateur.Id_utilisateur as commercial,
        count(affaire.Id_affaire) as affaire_non_signe_with_fdc_valid
    FROM
        affaire,
        utilisateur,
        statut,
        doc
    LEFT JOIN
        zdataset_fdcentete dt on (dt.id_dataset = doc.id_dataset_entete )
    WHERE
        utilisateur.Id_utilisateur = affaire.Id_utilisateur and
        doc.type_doc='fdc' AND
        dt.affaire = affaire.Id_affaire AND
        dt.statut = 'Validée' AND
        affaire.Id_statut = statut.Id_statut AND
        statut.Id_statut <> 6
    GROUP BY 
        utilisateur.Id_utilisateur  ";


/* Exécution des requêtes */
$req = new db_sql($requête);

$res_aff_sign_sans_fdc = new db_sql($req_aff_sign_sans_fdc);
$res_aff_sign_avec_fdc_en_cours = new db_sql($req_aff_sign_avec_fdc_en_cours);
$res_aff_non_sign_avec_fdc_valid = new db_sql($req_aff_non_sign_avec_fdc_valid);

/* Resultat des requêtes en tableau */
$tab_aff_sign_sans_fdc = array();
$tab_aff_sign_avec_fdc_en_cours = array();
$tab_aff_non_sign_avec_fdc_valid = array();

while($res_aff_sign_sans_fdc->n() ) {
    $tab_aff_sign_sans_fdc[$res_aff_sign_sans_fdc->f('commercial')]=$res_aff_sign_sans_fdc->f('affaire_signe_sans_fdc');
}

while($res_aff_sign_avec_fdc_en_cours->n() ) {
    $tab_aff_sign_avec_fdc_en_cours[$res_aff_sign_avec_fdc_en_cours->f('commercial')]=$res_aff_sign_avec_fdc_en_cours->f('affaire_signe_with_fdc_en_cours');
}

while($res_aff_non_sign_avec_fdc_valid->n() ) {
    $tab_aff_non_sign_avec_fdc_valid[$res_aff_non_sign_avec_fdc_valid->f('commercial')]=$res_aff_non_sign_avec_fdc_valid->f('affaire_non_signe_with_fdc_valid');
}

function nbre_affaire($tab, $commercial){
    foreach ($tab as $com => $nb){
        if($commercial == $com){
            return $nb;
        }
    }
    return 0;
}

$total = array();
$totaux = array();

function affect_valeur () {
    global $critere, $req;
    $critere = array ($req->f('commercial'),$req->f('agence_nom'),1);
}

function rupture_h1 () {
    global $total;
    $total  = array();
}

function rupture_b1 () {
    global $critere, $total, $totaux;

    $totaux[a] += $total[a];
    $totaux[b] += $total[b];
    $totaux[c] += $total[c];
    $totaux[d] += $total[d];
    $totaux[e] += $total[e];
    $totaux[f] += $total[f];

    ecrit_ligne ($critere[1], "#CCFFCC", $total);
}

function rupture_h2 () {
    global $totaux;
    $totaux  = array();
}

function rupture_b2 () {
    global $totaux;
    ecrit_ligne ("Total général", "#FFBBBB", $totaux);
}


function coeur () {

    global $tab_aff_sign_sans_fdc, $tab_aff_sign_avec_fdc_en_cours, $tab_aff_non_sign_avec_fdc_valid,
            $valeur, $total, $req;
    $valeur = array();
    $com_agence = $req->f('commercial');
    $valeur[a] = $req->f('fdc_creer');
    $valeur[b] = $req->f('fdc_valider');
    $valeur[c] = $req->f('fdc_produit');
    $valeur[d] = nbre_affaire($tab_aff_sign_sans_fdc, $req->f('id_user'));
    $valeur[e] = nbre_affaire($tab_aff_sign_avec_fdc_en_cours, $req->f('id_user'));
    $valeur[f] = nbre_affaire($tab_aff_non_sign_avec_fdc_valid, $req->f('id_user'));

    $total[a] += $valeur[a];
    $total[b] += $valeur[b];
    $total[c] += $valeur[c];
    $total[d] += $valeur[d];
    $total[e] += $valeur[e];
    $total[f] += $valeur[f];

    ecrit_ligne ($com_agence, "#DDDDFF", $valeur);

}

function ecrit_ligne ($titre, $couleur, $tableau) {
    ?>


    <tr>

        <td class="resultat_list" align="left"  bgcolor="<? echo $couleur ?>"><? echo $titre ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[a] ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[b] ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[c] ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[d] ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[e] ?></td>
        <td class="resultat_list" align="right"  bgcolor="<? echo $couleur ?>"><? echo $tableau[f] ?></td>
    </tr>


    <?
}


?>



<table class="resultat">
    <tr>
        <td  align=center class="resultat_tittle" colspan = 8><p style="text-decoration: none; color:<? echo $couleur ?>; font-size: medium">Tableau de bord FEUILLE DE COTES</p></td>
    </tr>

    <tr>
        <td></td>
        <td align=center class="resultat_tittle" colspan = 3><a style="text-decoration: none;color:gray">Sur la période</a></td>
        <td align=center class="resultat_tittle" colspan = 3><a style="text-decoration: none;color:gray">A la date de l'état (du jour)</a></td>
    </tr>
    <tr>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(a)" >Commerciaux</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(b)" >Nbre de FDC créées</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(c)" >Nbre de FDC validées</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(d)" >Nbre de FDC à produire</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(e)" >Nbre d'affaires signées sans FDC</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(f)" >Nbre d'affaires signées avec FDC En cours</p></td>
        <td class="resultat_tittle"><p style="text-decoration: none;color:gray" TITLE="(g)" >Nbre d'affaires non signées avec FDC Validées</p></td>
    </tr>


<? include ('ress/rupture.php'); ?>


</table>





