<?
$GLOBALS[parm][paroi][typederail]["2"] = "Rail 2 Voies";
$GLOBALS[parm][paroi][typederail]["3"] = "Rail 3 Voies";
$GLOBALS[parm][paroi][typederail]["4"] = "2 Rails 2 Voies";

$GLOBALS[parm][paroi][DEDUCRAILPAROI] = -0.005;
$GLOBALS[parm][paroi][RECOUVPAROI] = 0.06;

$GLOBALS[parm][paroi][support]["POSE PLAFOND"] = "Support Plafond";
$GLOBALS[parm][paroi][support]["POSE DE FACE SUR EQUERRES 6-11 cm"] = "Equerres 6 -11 cm + support";

$GLOBALS[parm][paroi][deduction]["POSE PLAFOND"] = "1 tous les 100 cm";
$GLOBALS[parm][paroi][deduction]["POSE DE FACE SUR EQUERRES 6-11 cm"] = "1 tous les 100 cm";

$GLOBALS[parm][paroi]["AJOUTCOUPETISSUPANNEAULARGEUR"]['BERLIN black out'] = 0;
$GLOBALS[parm][paroi]["AJOUTCOUPETISSUPANNEAULARGEUR"]['BERLIN transparent'] = 0; 
$GLOBALS[parm][paroi]["AJOUTCOUPETISSUPANNEAULARGEUR"]['TOILE DE LIN'] = 0.060;  	

$GLOBALS[parm][paroi]["RETRAITCOUPETISSUPANNEAUHAUTEUR"]['BERLIN black out'] = -0.025;	
$GLOBALS[parm][paroi]["RETRAITCOUPETISSUPANNEAUHAUTEUR"]['BERLIN transparent'] = -0.025;
$GLOBALS[parm][paroi]["RETRAITCOUPETISSUPANNEAUHAUTEUR"]['TOILE DE LIN'] = -0.015;

$GLOBALS[parm][paroi]["ConfectionHAUTE"]['BERLIN black out'] = "Couture Directe Velcro sur Toile";
$GLOBALS[parm][paroi]["ConfectionHAUTE"]['BERLIN transparent'] = "Couture Directe Velcro sur Toile";
$GLOBALS[parm][paroi]["ConfectionHAUTE"]['TOILE DE LIN'] = "Rentré 1 cm + Couture Velcro";	

$GLOBALS[parm][paroi]["ConfectionLATERALE"]['BERLIN black out'] = "";
$GLOBALS[parm][paroi]["ConfectionLATERALE"]['BERLIN transparent'] = "";
$GLOBALS[parm][paroi]["ConfectionLATERALE"]['TOILE DE LIN'] = "Ourlet 1cm avec Rentré 1cm";

$GLOBALS[parm][paroi]["Coupeur"]['BERLIN black out'] = "Coupe Automatique";
$GLOBALS[parm][paroi]["Coupeur"]['BERLIN transparent'] = "Coupe Automatique";
$GLOBALS[parm][paroi]["Coupeur"]['TOILE DE LIN'] = "Coupe Manuelle";


function get_of_pa_coloris($parm) {
	return $parm[fdc_pa_toile];	
}

function get_of_pa_largeur_fenetre($parm) {
	return $parm[largeur]/1000;
}

function get_of_pa_largeur_rail($parm) {
	return $parm[largeurrail]/1000;
}

function get_of_pa_hauteur($parm) {
	return $parm[hauteur_cm]/1000;
}

function get_of_pa_qte($parm) {
	return $parm[qte];
}

function get_of_pa_ra_rail_type($parm) {
	return $GLOBALS[parm][paroi][typederail][$parm[fdc_pa_nb_panneaux]];
}

function get_of_pa_ra_rail_longueurcoupe($parm) {
	return get_of_pa_largeur_rail($parm) + $GLOBALS[parm][paroi][DEDUCRAILPAROI];
}

function get_of_pa_ra_pan_typeprofils($parm) {
	return "- Support Panneau\n- Velcro Rigide \n- Barre de Charge";
}

function get_of_pa_ra_pan_longueurcoupe($parm) {
    return floor(((get_of_pa_largeur_fenetre($parm)+(($parm[fdc_pa_nb_panneaux]-1)*$GLOBALS[parm][paroi][RECOUVPAROI]))
               /$parm[fdc_pa_nb_panneaux])*100)/100;	
}	

function get_of_pa_nb_panneaux($parm) {
	//return $parm[fdc_pa_qte]*$parm[fdc_pa_nb_panneaux];
	return $parm[fdc_pa_nb_panneaux];
} 

function get_of_pa_ra_sup_typesupports($parm) {
	return $GLOBALS[parm][paroi][support][$parm[fdc_pa_type_pose]];
}
function get_of_pa_sup_qte($parm) {
	return 2+floor(get_of_pa_largeur_rail($parm));
}

function get_gamme($toile) {
	if (!$GLOBALS['memoire']['gamme_de_toile'][$toile]) {
		$req = new db_sql("select gamme from toile where id_toile = ".$toile);
		$enr = $req->n();
		$GLOBALS[memoire][gamme_de_toile][$toile] = $req->f("gamme");
	}
	return $GLOBALS[memoire][gamme_de_toile][$toile];
}

function get_of_pa_toile_largeur($parm) {
    return floor(((get_of_pa_largeur_fenetre($parm)+(($parm[fdc_pa_nb_panneaux]-1)*$GLOBALS[parm][paroi][RECOUVPAROI]))
               /$parm[fdc_pa_nb_panneaux])*100)/100
    		+$GLOBALS[parm][paroi]["AJOUTCOUPETISSUPANNEAULARGEUR"][get_gamme($parm[fdc_pa_toile])];	
}

function get_of_pa_toile_hauteur($parm) {
	return get_of_pa_hauteur($parm)+
	+$GLOBALS[parm][paroi]["RETRAITCOUPETISSUPANNEAUHAUTEUR"][get_gamme($parm[fdc_pa_toile])];
}
	
function get_of_pa_toile_collaborateur($parm) {
	return $GLOBALS[parm][paroi]["Coupeur"][get_gamme($parm[fdc_pa_toile])];
}

function get_of_toile_confection_haute($parm) {
	return $GLOBALS[parm][paroi]["ConfectionHAUTE"][get_gamme($parm[fdc_pa_toile])];
}

function get_of_toile_confection_laterale($parm) {
	return $GLOBALS[parm][paroi]["ConfectionLATERALE"][get_gamme($parm[fdc_pa_toile])];
}


?>