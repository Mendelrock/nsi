<?
include("ress/db_mysql.php");

$req = new db_sql("
	select
		article.id_article,
		article.lb_article,
		article.lb_article_aff,
		article.type,
		article.selecteur,
		article.laize,
		article.qt_stock - ifnull(besoin.qt,0) + ifnull(po.qt,0) as qt_stock,
		article.qt_mini,
		article.qt_max,
		article.actif,
		article.FDV,
		article.coef_chute,
		article.delai,
		familles.lb_famille,
		fournisseur.lb_fournisseur,
		fournisseur.adresse_postale,
		fournisseur.adresse_mail,
		fournisseur.cond_regle,
		fournisseur.envoi_automatique,
		fournisseur_article.reference,
		fournisseur_article.quotite,
		fournisseur_article.prix,
		fournisseur_article.principal,
		toile.lb_toile_atelier,
		toile.lb_toile_sr,
		toile.selecteur_rideau,
		toile.selecteur_doublure,
		toile.selecteur_store,
		toile.selecteur_enrouleur_exterieur,
		toile.selecteur_enrouleur_interieur,
		toile.selecteur_coffre_exterieur,
		toile.selecteur_coffre_interieur,
		toile.selecteur_film_exterieur,
		toile.selecteur_film_interieur,
		toile.selecteur5,
		toile.selecteur6,
		toile.selecteur7,
		toile.selecteur8,
		toile.selecteur9,
		toile.selecteur_coussin,
		toile.selecteur_paroi,
		toile.amotif_raccordable,
		toile.amotif_ajustable,
		toile.couleur,
		toile.gamme,
		toile.orientation,
		toile.FDV as toile_FDV,
		article_propriete.valeur as decote 
	FROM 
		article
	LEFT OUTER JOIN toile ON ( article.id_toile = toile.id_toile ) 
	LEFT OUTER JOIN article_propriete ON ( article.id_article = article_propriete.id_article and article_propriete.propriete = 'decote' )
	LEFT OUTER JOIN (select sum(qt - qt_solde) as qt, id_article from po_ligne group by id_article) as po ON ( article.id_article = po.id_article )
	LEFT OUTER JOIN (select sum(qt) as qt, id_article from besoin group by id_article) as besoin ON ( article.id_article = besoin.id_article )
	LEFT OUTER JOIN fournisseur_article ON ( article.id_article = fournisseur_article.id_article )
	LEFT OUTER JOIN fournisseur ON ( fournisseur.id_fournisseur = fournisseur_article.id_fournisseur )
	LEFT OUTER JOIN familles ON ( familles.id_famille = article.id_famille )
	");
	
while ($req->n()) {
		$extprod[] = array(
			'id_article' => $req->f('id_article'),
			'lb_article' => $req->f('lb_article'),
			'lb_article_aff' => $req->f('lb_article_aff'),
			'type' => $req->f('type'),
			'selecteur' => $req->f('selecteur'),
			'laize' => $req->f('laize'),
			'qt_stock' => $req->f('qt_stock'),
			'qt_mini' => $req->f('qt_mini'),
			'qt_max' => $req->f('qt_max'),
			'actif' => $req->f('actif'),
			'delai' => $req->f('delai'),
			'FDV' => $req->f('FDV'),
			'Coef_Chute' => $req->f('coef_chute'),
			'lb_fournisseur' => $req->f('lb_fournisseur'),
			'lb_famille' => $req->f('lb_famille'),
			'adresse_mail' => $req->f('adresse_mail'),
			'adresse_postale' => $req->f('adresse_postale'),
			'cond_regle' => $req->f('cond_regle'),		
			'envoi_automatique' => $req->f('envoi_automatique'),
			'reference' => $req->f('reference'),
			'quotite' => $req->f('quotite'),
			'prix' => $req->f('prix'),
			'principal' => $req->f('principal'),
			'lb_toile_atelier' => $req->f('lb_toile_atelier'),
			'lb_toile_sr' => $req->f('lb_toile_sr'),
			'amotif_raccordable' => $req->f('amotif_raccordable'),
			'amotif_ajustable' => $req->f('amotif_ajustable'),
			'selecteur_rideau' => $req->f('selecteur_rideau'),
			'selecteur_doublure' => $req->f('selecteur_doublure'),
			'selecteur_store' => $req->f('selecteur_store'),
			'selecteur_enrouleur_exterieur' => $req->f('selecteur_enrouleur_exterieur'),
			'selecteur_enrouleur_interieur' => $req->f('selecteur_enrouleur_interieur'),
 			'selecteur_coffre_exterieur' => $req->f('selecteur_coffre_exterieur'),
			'selecteur_coffre_interieur' => $req->f('selecteur_coffre_interieur'),
			'selecteur_film_exterieur' => $req->f('selecteur_film_exterieur'),
			'selecteur_film_interieur' => $req->f('selecteur_film_interieur'),
			'selecteur5' => $req->f('selecteur5'),
			'selecteur6' => $req->f('selecteur6'),
			'selecteur7' => $req->f('selecteur7'),
			'selecteur8' => $req->f('selecteur8'),
			'selecteur9' => $req->f('selecteur9'),
			'selecteur_coussin' => $req->f('selecteur_coussin'),
			'selecteur_paroi' => $req->f('selecteur_paroi'),
			'couleur' => $req->f('couleur'),
			'gamme' => $req->f('gamme'),
			'orientation' => $req->f('orientation'),
			'toile_FDV' => $req->f('toile_FDV'),
			'decote' => $req->f('decote'));
}

$_SESSION['extprod'] = $extprod;
$tableau="extprod";
$nodecale=true;
include("ress/xl.php"); 
?>