<?
include("ress/entete.php");

$req=" SELECT  *  FROM  incompatibilites_dimension_post";

if($ACTE==1){

    if($produit)$req.=" where produit='".$produit."'";
    $req.=" ORDER BY produit";
    $resultat = new db_sql($req);

}

if($ACTE==2){

    $dbres= new db_sql();
    if($_POST['Submit'] =='Modifier'){
        $Valeur1 = correction($Valeur1);
        $Valeur2 = correction($Valeur2);
        $Valeur3 = correction($Valeur3);

        $dbres->q("
        update incompatibilites_dimension_post 
        set produit = ".My_sql_format($Produit)." , 
		  prop1 = ".My_sql_format($Propriete1)." , 
		  val1 = ".My_sql_format($Valeur1)." , 
		  prop2 = ".My_sql_format($Propriete2)." , 
		  val2 = ".My_sql_format($Valeur2).", 
		  prop3 = ".My_sql_format($Propriete3)." , 
		  val3 = ".My_sql_format($Valeur3)."
        where id = ".$_POST['id'] );
    }elseif ($_POST['Submit'] =='Supprimer' ){
        $dbres->q("
        delete from incompatibilites_dimension_post 
        where id = ".$_POST['id'] );
    } else {
        $dbres->q("
        insert into incompatibilites_dimension_post(produit,prop1,val1,prop2,val2,prop3,val3) 
        values (".My_sql_format($Produit).",
					".My_sql_format($Propriete1).",
					".My_sql_format($Valeur1).",
					".My_sql_format($Propriete2).",
					".My_sql_format($Valeur2).",
					".My_sql_format($Propriete3).",
					".My_sql_format($Valeur3).")");
    }

    $resultat = new db_sql($req);
}

function delt_ope($text){
   return explode(">=",$text)[1];
}

function correction($text){
    return  (delt_ope($text)) ? $text : ">=".$text;
}

?>

<body class="application">
<?php if($resultat){ ?>
    <table class="resultat">
        <tr>
            <td class="resultat_tittle">Produit</td>
            <td class="resultat_tittle">Propriété 1</td>
            <td class="resultat_tittle">Valeur 1</td>
            <td class="resultat_tittle">Propriété 2</td>
            <td class="resultat_tittle">Valeur 2</td>
            <td class="resultat_tittle">Propriété 3</td>
            <td class="resultat_tittle">Valeur 3</td>
        </tr>
        <?
        while(/*$z<$Affichage AND*/ $resultat->n() ) {
            $z++;
            ?>
            <tr id="<? echo $z * 100 ?>">
                <?
                $bgcolor = alternat($z);
                $link = "id=".urlencode($resultat->f('id')).
					 "&produit=" . urlencode($resultat->f('Produit')) . 
					 "&prop1=" . urlencode($resultat->f('prop1')) . 
					 "&val1=" . urlencode($resultat->f('val1')) .
                "&prop2=" . urlencode($resultat->f('prop2')) . 
					"&val2=" . urlencode($resultat->f('val2')) . 
						  "&prop3=" . urlencode($resultat->f('prop3')) . 
						  "&val3=" . urlencode($resultat->f('val3')). 
						  "&ACTE=3";

					 ?>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a
                        href="incompatibilite_post_dimensionnel_gestion_cud.php?<? echo $link ?>"> <? echo $resultat->f('Produit') ?> </a>
                </td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop1') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val1') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop2') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val2') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop3') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val3') ?></td>
            </tr>
            <?
        }

        if($z){
            $suiv_text="Fin de liste";
            if($resultat->n()){
                $suiv_text="Liste non termin?e";
            }
            echo "<tr>";
            echo"<td class='resultat_footer' colspan='6'></td>";
            echo"<td class='resultat_footer' align='center' colspan='1'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
            ?><?
        }
        echo "</tr></table>";

        ?>

        </td>
        </tr>
    </table>
<?php } ?>
<?
if ($liste_go) {
    ?>
    <script langage="javascript">incompatibilite_post.submit();</script>
    <?
}
include ("ress/enpied.php");
?>
