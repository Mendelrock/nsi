<?
// param�tres � passer en millim�tre

// Doit imp�rativement �tre entier

include ("fonction.php");

$parm_defaut ='
// mettre autant de array qu il y a de morceaux � couper
$acouper = array (
                           array("hauteur"=>255,
                                 "largeur"=>297,
                                 "repere"=>"rep�re 2",
                                 "nombre"=>1),
                           array("hauteur"=>255,
                                 "largeur"=>157,
                                 "repere"=>"rep�re 3",
                                 "nombre"=>1)                                 
                                );
                                
// mettre une des valeurs laize, oppose, neutre)
$orientation = "oppose";  

// mettre une des valeurs hauteur, largeur
$sens_des_soudures = "hauteur"; 

// mettre 1 ligne par laize
$laizes[] = 135;
$laizes[] = 195;
';

$parm_affiche = stripslashes($_POST[parm]);
$parm = stripslashes($_POST[parm]);
if (!$parm_affiche) $parm_affiche = $parm_defaut;
?>
Param�tres<BR>
**********<BR>
<form method = post><TEXTAREA NAME="parm" ROWS="30" COLS="80"><? echo $parm_affiche ?> </TEXTAREA><input type = submit></form> 
<?
if ($parm) {
?>
R�sultats<BR>
*********<BR>
<?
eval($parm);

$x = calcul_besoin_matiere($acouper,$orientation,$sens_des_soudures,$laizes);
print_r($x);
}

?>