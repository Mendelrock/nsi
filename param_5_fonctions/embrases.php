<?php
/*
-------------------------------------------------
 Retourne Qté pour embrases
-------------------------------------------------*/
function get_qte_embrases_of($parametres) {
	return $parametres['qte']*1;
}

/*
-------------------------------------------------
 Retourne Coloris pour embrases
-------------------------------------------------*/
function get_coloris_embrases_of($parametres) {
	return $parametres['articleapicker'];
}
?>