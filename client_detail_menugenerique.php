<?php

$classe = 'interne';
$param_name = 'client_detail_';
$param_get = "Id_client=$Id_client";

/*1*/
$tab[1]['Nom']='Interlocuteurs';
$tab[1]['Href']=$param_name.'contact_list.php?'.$param_get;

/*2*/
$tab[2]['Nom']='Actions';
$tab[2]['Href']=$param_name.'interac_list.php?'.$param_get;

/*3*/
$tab[3]['Nom']='Affaires';
$tab[3]['Href']=$param_name.'affaire_list.php?'.$param_get;

/*4*/
$tab[4]['Nom']='Source fichier';
$tab[4]['Href']=$param_name.'marquage.php?'.$param_get;

/*5*/
$tab[5]['Nom']='Affectation';
$tab[5]['Href']=$param_name.'affectation.php?'.$param_get;

/*6*/
$tab[6]['Nom']='Commentaire';
$tab[6]['Href']=$param_name.'commentaire.php?'.$param_get;

/*7*/
$tab[7]['Nom']='Feuilles de cote';
$tab[7]['Href']=$param_name.'feuilles_de_cote.php?'.$param_get;

$menu = array();
$menu[] = $tab;

function get_menu($valeur){
    global $menu, $classe;

    $tpt = '<table class="menu_haut_resultat">';
    $tpt .= '<tr>';

    for ($i=1; $i<=sizeof($menu[0]); $i++){
        $tpt .= ($valeur == $i)? "<td class='interne_actif'>".$menu[0][$i]['Nom']."</td>" :
            "<td class='".$classe."'>  <a class='".$classe."' href='".$menu[0][$i]["Href"]."' > ".$menu[0][$i]['Nom']." </a> </td>" ;
    }

    $tpt .= '<td width"467"></td>';
    $tpt .= '</tr>';
    $tpt .= '</table>';

    return $tpt;
}


?>


