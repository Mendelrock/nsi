<?
include("ress/db_mysql.php");

$req = new db_sql("
        select
            id_toile,
            lb_toile_atelier,
            lb_toile_sr,
            selecteur_rideau,
            selecteur_doublure,
            selecteur_store,
            selecteur_enrouleur_interieur,
            selecteur_enrouleur_exterieur,
            selecteur_coussin,
            selecteur_paroi,
            couleur,
            gamme,
            orientation,
            amotif_raccordable,
            amotif_ajustable,
            FDV,
            selecteur_coffre_interieur,
            selecteur_coffre_exterieur,
            selecteur_film_exterieur,
            selecteur_film_interieur,
            selecteur5,
            selecteur6,
            selecteur7,
            selecteur8,
            selecteur9
        FROM 
            toile
	");

while ($req->n()) {
    $extprod[] = array(
        'toile' => $req->f('id_toile'),
        'lb_toile_atelier' => $req->f('lb_toile_atelier'),
        'lb_toile_sr' => $req->f('lb_toile_sr'),
        'selecteur_rideau' => $req->f('selecteur_rideau'),
        'selecteur_doublure' => $req->f('selecteur_doublure'),
        'selecteur_store' => $req->f('selecteur_store'),
        'selecteur_enrouleur_interieur' => $req->f('selecteur_enrouleur_interieur'),
        'selecteur_enrouleur_exterieur' => $req->f('selecteur_enrouleur_exterieur'),
        'selecteur_coussin' => $req->f('selecteur_coussin'),
        'selecteur_paroi' => $req->f('selecteur_paroi'),
        'couleur' => $req->f('couleur'),
        'gamme' => $req->f('gamme'),
        'orientation' => $req->f('orientation'),
        'amotif_raccordable' => $req->f('amotif_raccordable'),
        'amotif_ajustable' => $req->f('amotif_ajustable'),
        'FDV' => $req->f('FDV'),
        'selecteur_coffre_interieur' => $req->f('selecteur_coffre_interieur'),
        'selecteur_coffre_exterieur' => $req->f('selecteur_coffre_exterieur'),
        'selecteur_film_exterieur' => $req->f('selecteur_film_exterieur'),
        'selecteur_film_interieur' => $req->f('selecteur_film_interieur'),
        'selecteur5' => $req->f('selecteur5'),
        'selecteur6' => $req->f('selecteur6'),
        'selecteur7' => $req->f('selecteur7'),
        'selecteur8' => $req->f('selecteur8'),
        'selecteur9' => $req->f('selecteur9'));
}

$_SESSION['extprod'] = $extprod;
$tableau="extprod";
$nodecale=true;
include("./ress/xl.php");
?>