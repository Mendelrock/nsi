<?php
/*
-------------------------------------------------
 Retourne CONFECTION HAUTE pour enrouleur
-------------------------------------------------*/
function get_confechaute_enrouleur_of($parametres) {
  return "NET DE COUPE";
}

/*
-------------------------------------------------
 Retourne Qté pour enrouleur
-------------------------------------------------*/
function get_qte_enrouleur_of($parametres) {
  return $parametres['qte']*1;
}


/*
-------------------------------------------------
 Retourne l'Enroulement des Toiles
-------------------------------------------------*/
function get_enroulement_des_toile_of($parametres) {
    return $parametres['enroulement_toiles'];
}

/*
-------------------------------------------------
 Retourne LONGUEUR CHAINETTE pour enrouleur
-------------------------------------------------*/
function get_lchainette_enrouleurmeca_of($parametres) {
  return (2*max(($parametres['hauteur_cde_enrouleur']/1000),($parametres['hauteur_enrouleur']/1000*0.75)));
}

/*
-------------------------------------------------
 Retourne TYPE DE POSE pour enrouleur
-------------------------------------------------*/
function get_typepose_enrouleurmeca_of($parametres) {
  return $parametres['typepose_enrouleur'];
}

/*
-------------------------------------------------
 Retourne Guidage pour enrouleur
-------------------------------------------------*/
function get_guidage_enrouleurmeca_of($parametres) {
  return $parametres['guidage_enrouleur'];
}

function get_coloris_mecanismes_enrouleur_of($parametres) {
  return $parametres['coloris_mecanismes_enrouleur'];
}

/*
-------------------------------------------------
 Retourne LARGEUR AXE pour enrouleur
-------------------------------------------------*/
function get_laxe_enrouleurmeca_of($parametres) {
	if ($parametres['typecommande_enrouleur'] == "Motorisé 24 volts Batterie") {
		return get_largeur_enrouleur_of($parametres)-0.025;
	} else {	
		return get_largeur_enrouleur_of($parametres)-0.03;
	}
}

/*
-------------------------------------------------
 Retourne Barre de charge pour enrouleur
-------------------------------------------------*/
function get_bcharge_enrouleurmeca_of($parametres) {
	/*
	$DEDUCCBDC = -0.035;
	$DEDUCCBDCDUO = -0.04;
	if (is_duo($parametres)) {
		return get_largeur_enrouleur_of($parametres)+$DEDUCCBDCDUO;
	} else {
		return get_largeur_enrouleur_of($parametres)+$DEDUCCBDC;
	}
	*/
	return get_largeur_enrouleur_of($parametres)-0.037;
}


/*
-------------------------------------------------
 Retourne Plaque arrière pour enrouleur
-------------------------------------------------*/
function get_parriere_enrouleurmeca_of($parametres) {
	if ($parametres['typecommande_enrouleur'] == "Motorisé 24 volts Batterie") {
		return get_largeur_enrouleur_of($parametres)-0.005;
	} else {	
		return get_largeur_enrouleur_of($parametres)-0.002;
	}
}


/*
-------------------------------------------------
 Retourne Jonc pour enrouleur
-------------------------------------------------*/
function get_jonc_enrouleurmeca_of($parametres) {
  return get_largeur_enrouleur_of($parametres)-0.04;
}

/*
-------------------------------------------------
 Retourne COTE COMMANDE pour enrouleur
-------------------------------------------------*/
function get_cotecde_enrouleurmeca_of($parametres) {
  return $parametres['cotecommande_enrouleur'];
}

/*
-------------------------------------------------
 Retourne CONFECTION BASSE pour enrouleur
-------------------------------------------------*/
function get_confecbasse_enrouleur_of($parametres) {
	if (StartsWith($parametres['guidage_enrouleur'],"Guidage Coulisse 23x17")) {
		return "FOURREAU 5CM";
	} else {
		return "NET DE COUPE";
	}
}

/*
-------------------------------------------------
 Retourne LARGEUR COUPE TOILE pour enrouleur 
-------------------------------------------------*/
function get_largeur_coupet_enrouleur_of($parametres) {
  return get_largeur_enrouleur_of($parametres)-0.04;
}


/*
-------------------------------------------------
 Retourne Coloris pour enrouleur
-------------------------------------------------*/
function get_coloris_enrouleur_of($parametres) {
  return (isset($parametres['gamme_enrouleur_interieur']))?$parametres['gamme_enrouleur_interieur']:$parametres['gamme_enrouleur_exterieur'];
}

function is_duo($parametres) {
	$toile = get_coloris_enrouleur_of($parametres);
	if (!isset($GLOBALS[memoire][is_duo][$toile])) { 
		$req = new db_sql("select lb_toile_sr from toile where id_toile = ".$toile." and lb_toile_sr like 'TOILE ENROULEUR DUO%'");
		if ($req->n()) {
			$GLOBALS[memoire][is_duo][$toile] = true;
		} else {
			$GLOBALS[memoire][is_duo][$toile] = false;
		}
	}
	return $GLOBALS[memoire][is_duo][$toile];	
}

/*
-------------------------------------------------
 Retourne HAUTEUR COUPE TOILE pour enrouleur
-------------------------------------------------*/
function get_hauteur_coupet_enrouleur_of($parametres) {
	$MODULO = 0.129;
	$HAUTTOILE = 0.2;
	if (is_duo($parametres)) {
		return ceil(((2*(get_hauteur_enrouleur_of($parametres)+0.1))+$HAUTTOILE)/$MODULO)*$MODULO;
	} else {
		return get_hauteur_enrouleur_of($parametres)+$HAUTTOILE;
	}  
}


/*
-------------------------------------------------
 Retourne LARGEUR pour enrouleur 
-------------------------------------------------*/
function get_largeur_enrouleur_of($parametres) {
  return $parametres['largeur_enrouleur']/1000;
}

/*
-------------------------------------------------
 Retourne LARGEUR pour enrouleur 
-------------------------------------------------*/
function get_hauteur_enrouleur_of($parametres) {
  return $parametres['hauteur_enrouleur']/1000;
}

/*
-------------------------------------------------
 Retourne typecommande_enrouleur 
-------------------------------------------------*/
function get_typecommande_enrouleur_of($parametres) {
	return $parametres['typecommande_enrouleur'];
}

?>