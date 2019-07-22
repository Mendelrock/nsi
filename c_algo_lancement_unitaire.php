<?
// paramètres à passer en millimètre

// Doit impérativement être entier

include ("fonction.php");

$parm_defaut ='
// mettre autant de array qu il y a de morceaux à couper
$acouper = array (
                           array("hauteur"=>255,
                                 "largeur"=>297,
                                 "repere"=>"repère 2",
                                 "nombre"=>1),
                           array("hauteur"=>255,
                                 "largeur"=>157,
                                 "repere"=>"repère 3",
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
Paramètres<BR>
**********<BR>
<form method = post><TEXTAREA NAME="parm" ROWS="30" COLS="80"><? echo $parm_affiche ?> </TEXTAREA><input type = submit></form> 
<?
if ($parm) {
?>
Résultats<BR>
*********<BR>
<?
eval($parm);

$x = calcul_besoin_matiere($acouper,$orientation,$sens_des_soudures,$laizes);
print_r($x);
}

?>