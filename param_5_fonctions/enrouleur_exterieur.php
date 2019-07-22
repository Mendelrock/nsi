<?php

/*
-------------------------------------------------
 Retourne la largeur du store
-------------------------------------------------*/
function get_largeur_store($parametres) {
    if($parametres['largeur'] != "") {
        return ($parametres['largeur']/1000);
    }else{
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne la hauteur du store
-------------------------------------------------*/
function get_hauteur_store($parametres) {
    if($parametres['hauteur'] != "") {
        return ($parametres['hauteur']/1000);
    }else{
        return "-";
    }
}


/*
-------------------------------------------------
 Retourne la cote de la commande
-------------------------------------------------*/
function get_cote_cmde($parametres) {
    if ($parametres['cotedecommande_enex'] != "") {
        return $parametres['cotedecommande_enex'];
    } else {
        return "-";
    }
}


/*
-------------------------------------------------
 Retourne la largeur axe enroulement
-------------------------------------------------*/
function get_largeur_axe_enroulement($parametres) {
    if ($parametres['largeur'] != "") {
        return ( ($parametres['largeur'] /1000)- 0.06 );
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne la hauteur de commande
-------------------------------------------------*/
function get_hauteur_commande($parametres) {
    if ($parametres['hauteurmanivelle_cordon'] != "") {
        return ( $parametres['hauteurmanivelle_cordon']/1000 );
    } else {
        return "-";
    }
}


/*
-------------------------------------------------
 Retourne le type pose
-------------------------------------------------*/
function get_type_pose($parametres) {
    if ($parametres['type_pose_enex'] != "") {
        return $parametres['type_pose_enex'];
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne le type commande
-------------------------------------------------*/
function get_type_commande($parametres) {
    if ($parametres['typedecommande_enex'] != "") {
        return $parametres['typedecommande_enex'];
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne le point de commande
-------------------------------------------------*/
function get_point_commande($parametres) {
    if ($parametres['moto4'] != "") {
        return $parametres['moto4'];
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne le guidage
-------------------------------------------------*/
function get_guidage($parametres) {
    if ($parametres['guidage_enex'] != "") {
        return $parametres['guidage_enex'];
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne la coupe guidage
-------------------------------------------------*/
function get_coupe_guidage($parametres) {
    $coupe_guidage = $parametres['guidage_enex'];
    if ($coupe_guidage != "") {

        if($coupe_guidage == "Coulisse 23 x 17") return ( ($parametres['hauteur'] /1000) - 0.1);
        if($coupe_guidage == "Cable 3 mm") return (($parametres['hauteur'] /1000) + 0.1);
        if($coupe_guidage == "Sans") return '-';

    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne le sens du enroulement
-------------------------------------------------*/
function get_sens_enroulement($parametres) {
    if ($parametres['sensenroulementtoile_enex'] != "") {
        return $parametres['sensenroulementtoile_enex'];
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne la barre de charge
-------------------------------------------------*/
function get_barre_de_charge($parametres) {

    $barre_de_charge = $parametres['guidage_enex'];
    if ($barre_de_charge  != "") {

        if($barre_de_charge == "Coulisse 23 x 17" or $barre_de_charge == "Sans") {
            return (($parametres['largeur'] /1000) - 0.06);
        }elseif ($barre_de_charge == "Cable 3 mm") {
            return (($parametres['largeur'] /1000) - 0.09);
        }else {
            return "-";
        }

    } else {
        return "-";
    }
}


/*
-------------------------------------------------
 Retourne le jonc barre de charge
-------------------------------------------------*/
function get_jonc_barre_de_charge($parametres) {
    $barre_de_charge = $parametres['typedecommande_enex'];
    if ( $barre_de_charge  != "") {
        if($barre_de_charge == "Cordon" or $barre_de_charge == "Manivelle") {
            return (($parametres['largeur'] /1000)- 0.06);
        }else {
            return "-";
        }
    } else {
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne la largeur coupe toile
-------------------------------------------------*/
function get_largeur_coupe_toile($parametres) {
	if($parametres['guidage_enex'] == "Cable 3 mm") {
		return (($parametres['largeur']/1000) - 0.08);
	} else {
		return (($parametres['largeur']/1000) - 0.06);
	}
}

/*
-------------------------------------------------
 Retourne la hauteur coupe toile
-------------------------------------------------*/
function get_hauteur_coupe_toile($parametres) {
    if($parametres['hauteur'] != "") {
        return (($parametres['hauteur'] /1000)+ 0.3);
    }else{
        return "-";
    }
}

/*
-------------------------------------------------
 Retourne le coloris
-------------------------------------------------*/
function get_coloris($parametres) {
    return $parametres['gamme_enrouleur_exterieur'];
}

/*
-------------------------------------------------
 Retourne la confection haute
-------------------------------------------------*/
function get_of_confection_haute($parametres) {
    return "Net de coupe";
}

/*
-------------------------------------------------
 Retourne la confection basse
-------------------------------------------------*/
function get_of_confection_basse($parametres) {
    return "Fourreau 5cm sur Face Technique";
}


?>