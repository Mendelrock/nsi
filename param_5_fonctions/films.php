<?php


/*
-------------------------------------------------
 Retourne référence film
-------------------------------------------------*/
function get_reference_film($parametres){
	if ($parametres['references_film_interieur']) {
		return $parametres['references_film_interieur'];
	} else {
		return $parametres['references_film_exterieur'];	
	}
}
/*
-------------------------------------------------
 Retourne la largeur de la vitre
-------------------------------------------------*/
function get_largeur_vitre($parametres){
    if($parametres['largeur_filmsinterieurs']){
        return (($parametres['largeur_filmsinterieurs'])/1000);
    }
    if($parametres['largeur']){
        return ($parametres['largeur']/1000);
    }
}

/*
-------------------------------------------------
 Retourne la hauteur de la vitre
-------------------------------------------------*/
function get_hauteur_vitre($parametres){
    if($parametres['hauteur_filmsinterieurs']){
        return (($parametres['hauteur_filmsinterieurs'])/1000);
    }
    if($parametres['hauteur']){
        return ($parametres['hauteur']/1000);
    }
}

/*
-------------------------------------------------
 Retourne la largeur coupe
-------------------------------------------------*/
function get_largeur_coupe_vitre($parametres){
    if($parametres['largeur_filmsinterieurs']){
        return (($parametres['largeur_filmsinterieurs']+50))/1000;
    }
    if($parametres['largeur']){
        return (($parametres['largeur']+50)/1000);
    }
}

/*
-------------------------------------------------
 Retourne la hauteur coupe
-------------------------------------------------*/
function get_hauteur_coupe_vitre($parametres){
    if($parametres['hauteur_filmsinterieurs']){
        return (($parametres['hauteur_filmsinterieurs']+50)/1000);
    }
    if($parametres['hauteur']){
        return (($parametres['hauteur']+50)/1000);
    }
}

?>