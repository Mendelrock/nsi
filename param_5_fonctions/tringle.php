<?php
/*
-------------------------------------------------
 Retourne COLORIS pour tringle
-------------------------------------------------*/
function get_coloris_tringle($parametres) {
  return $parametres['gammetringle'];
}

/*
-------------------------------------------------
 Retourne Nombre supports pour tringle
-------------------------------------------------*/
function get_nb_support_tringle($parametres) {
	return req_sim("
		select 
			param_tringle_supports.nb_supports 
		from 
			param_tringle_supports,
			param_tringle_regles
		where 
			param_tringle_regles.type_tringle = ".$parametres[gammetringle]." and
			param_tringle_supports.nb_regle = param_tringle_regles.nb_regle and
			".$parametres[larg_tringle]." <= param_tringle_supports.dimension_jusqua  
		order by 
			param_tringle_supports.dimension_jusqua 
		limit 1",'nb_supports');
}

/*
-------------------------------------------------
 Retourne Division pour tringle
-------------------------------------------------*/
function get_division_tringle($parametres) {

    if($GLOBALS[origine] == 3) {
        return "NON";
    }else {
        $curs = new db_sql("SELECT lb_article FROM article WHERE id_article = ".get_coloris_tringle($parametres)." ");
        $curs->n();
        $couleur = $curs->f(lb_article);
        if(get_longueur_coupe_tringle($parametres)>2 and (substr($couleur,0, 13) != "CHEMIN DE FER"))
            return "2 Parties avec Raccord";
        else
            return "NON";
    }

}

/*
-------------------------------------------------
 Retourne Longueur coupe pour tringle
-------------------------------------------------*/
function get_longueur_coupe_tringle($parametres) {
  return get_article_propriete($parametres['embouts_tringle'], "decote")/100+get_longueur_tringle($parametres);
}

/*
-------------------------------------------------
 Retourne Longueur pour tringle
-------------------------------------------------*/
function get_longueur_tringle($parametres) {
  return $parametres['larg_tringle']/1000;
}

/*
-------------------------------------------------
 Retourne TYPE SUPPORTS pour tringle
-------------------------------------------------*/
function get_type_support_tringle($parametres) {
  return $parametres['support_tringle'];
}

/*
-------------------------------------------------
 Retourne Qte pour tringle
-------------------------------------------------*/
function get_qte_tringle_of($parametres) {
  return $parametres['qte']*1;
}

/*
-------------------------------------------------
 Retourne Nombre de Charriots / Anneaux pour tringle
-------------------------------------------------*/
function get_nbcharriots_tringle($parametres) {
	if( substr($parametres['confection_rideau'],0,10)=="PRE PLISSE" || substr($parametres['confection_rideau'],0,13)=="TETE FLAMANDE" || substr($parametres['confection_rideau'],0,15)=="Fronçage Manuel" ){
		$x = floor(get_longueur_tringle($parametres)*10)+2;  	
		if (get_type_refoulement_tringle($parametres) == "DOUBLE Refoulement" and floor($x/2) != $x/2) $x=$x+1;
		return $x;
	} else 
   	return "-";
}

/*
-------------------------------------------------
 Retourne EMBOUTS pour tringle
-------------------------------------------------*/
function get_embouts_tringle($parametres) {
	return $parametres['embouts_tringle'];
}

/*
-------------------------------------------------
 Retourne Hauteur commande pour tringle
-------------------------------------------------*/
function get_h_cde_tringle_of($parametres) {
  return $parametres['hauteur_cde_tringle']/1000;
}

/*
-------------------------------------------------
 Retourne Type refoulement pour tringle
-------------------------------------------------*/
function get_type_refoulement_tringle($parametres) {
	if($parametres['refoulementtringle'] != "") {
      return $parametres['refoulementtringle'];
	}else{
      return "-";
	}
}

/*-------------------------------------------------
 Retourne le nombre de barres
-------------------------------------------------*/
function get_nb_barre($parametres) {
	$support = get_type_support_tringle($parametres);
  	if ($support) {
		$requete="
			SELECT 
				article_propriete.valeur  
			FROM  
				article_propriete , 
				article
			WHERE 
				article_propriete.id_article = article.id_article AND 
				article.selecteur = 'support' AND 
				article_propriete.propriete = 'nb_barre' AND 
				article_propriete.id_article = ".$support; 
		$resultat = new db_sql($requete);
		if ($resultat->n()) {
			return($resultat->f('valeur'));
		}
	}
	return(1);
}

/*-------------------------------------------------
 Retourne le nombre d'embouts
-------------------------------------------------*/
function get_nb_embout($parametres) {
	return(2*get_nb_barre($parametres));
}

?>