<?php
/*
-------------------------------------------------
 Retourne typepose_banne pour store banne
-------------------------------------------------*/
function get_typepose_banne($parametres) {
    return $parametres['typepose_banne'];
}

/*
-------------------------------------------------
 Retourne hauteur_manivelle pour store banne
-------------------------------------------------*/
function get_hauteur_manivelle($parametres) {
    return $parametres['hauteur_manivelle'];
}


/*
-------------------------------------------------
 Retourne cote_commande_banne pour store banne
-------------------------------------------------*/
function get_cote_commande_banne($parametres) {
    return $parametres['cote_commande_banne'];
}

/*
-------------------------------------------------
 Retourne largeur_storebanne pour store banne
-------------------------------------------------*/
function get_largeur_storebanne($parametres) {
    return ( $parametres['largeur_storebanne']/1000);
}

/*
-------------------------------------------------
 Retourne avancee_storebanne pour store banne
-------------------------------------------------*/
function get_avancee_storebanne($parametres) {
    return ($parametres['avancee_storebanne']/1000);
}

/*
-------------------------------------------------
 Retourne type_commande_banne pour store banne
-------------------------------------------------*/
function get_type_commande_banne($parametres) {
    return $parametres['type_commande_banne'];
}

/*
-------------------------------------------------
 Retourne coloris_armature pour store banne
-------------------------------------------------*/
function get_coloris_armature($parametres) {
    return $parametres['coloris_armature'];
}

/*
-------------------------------------------------
 Retourne reference_toile pour store banne
-------------------------------------------------*/
function get_reference_toile($parametres) {
    return $parametres['reference_toile'];
}

/*
-------------------------------------------------
 Retourne lambrequin pour store banne
-------------------------------------------------*/
function get_lambrequin($parametres) {
    return $parametres['lambrequin'];
}

/*
-------------------------------------------------
 Retourne automatisme pour store banne
-------------------------------------------------*/
function get_automatisme($parametres) {
    return $parametres['automatisme'];
}

/*
-------------------------------------------------
 Retourne kit_pose pour store banne
-------------------------------------------------*/
function get_kit_pose($parametres) {
    return $parametres['kit_pose'];
}

/*
-------------------------------------------------
 Retourne produit pour store banne
-------------------------------------------------*/
function get_produit_banne($parametres) {
    return $parametres['produit_banne'];
}

/** Bon de commande  **/


/* Retourne le Refoulement Lames */
function get_type_de_refoulement_bc($parametres) {
    return $parametres['typederefoulement_rsm'];
}

/* Retourne l'avancée */
function get_avancee_bc($parametres) {

    if($parametres['avancee_comptacto']) {
        return $parametres['avancee_comptacto'];
    }elseif ($parametres['avancee']) {
        return $parametres['avancee'];
    }
}

/* Retourne lambrequin */
function get_lambrequin_bc($parametres) {

    if($parametres['lambrequin_sbcl']) {
        return $parametres['lambrequin_sbcl'];
    }elseif ($parametres['lambrequin_sbcl']) {
        return $parametres['lambrequin_sbcl'];
    }elseif ($parametres['lambrequin_sbmo']) {
        return $parametres['lambrequin_sbmo'];
    }elseif ($parametres['lambrequin_sbcc']){
        return $parametres['lambrequin_sbcc'];
    }

}

/* Retourne l' automatisme_bc */
function get_automatisme_bc($parametres) {

    if($parametres['automatisme_sbcc']) {
        return $parametres['automatisme_sbcc'];
    }elseif ($parametres['automatisme_sbcl']) {
        return $parametres['automatisme_sbcl'];
    }elseif ($parametres['automatisme_sbmo']) {
        return $parametres['automatisme_sbmo'];
    }

}

/* Retourne le coloris armature */
function get_coloris_armature_bc($parametres) {

    if($parametres['colorisarmature_sbcc']) {
        return $parametres['colorisarmature_sbcc'];
    }elseif ($parametres['colorisarmature_sbcl']) {
        return $parametres['colorisarmature_sbcl'];
    }elseif ($parametres['colorisarmature_sbmo']) {
        return $parametres['colorisarmature_sbmo'];
    }

}

/* Retourne la hauteur de la manivelle */
function get_hauteur_manivelle_bc($parametres) {

    if($parametres['hauteurmanivelle_sbcc']) {
        return $parametres['hauteurmanivelle_sbcc'];
    }elseif ($parametres['hauteur_manivelle_sbcl']) {
        return $parametres['hauteur_manivelle_sbcl'];
    }elseif ($parametres['hauteur_manivelle_sbmo']) {
        return $parametres['hauteur_manivelle_sbmo'];
    }elseif ($parametres['hauteurmanivelle_sbcl']) {
        return $parametres['hauteurmanivelle_sbcl'];
    }

}

/* Retourne Auvent */
function get_auvent_bc($parametres) {
    return $parametres['auvent_sbmo'];
}

/* Retourne la hauteur de la perche */
function get_hauteur_perche_bc($parametres) {
    return $parametres['hauteur_perche'];
}

?>