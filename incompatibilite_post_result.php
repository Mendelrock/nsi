<?
include("ress/entete.php");

$req=" SELECT  *  FROM  incompatibilites_post";
	  
if($ACTE==1){
	
if($produit)$req.=" where produit='".$produit."'";
$req.=" ORDER BY produit";
$resultat = new db_sql($req);

}

if($ACTE==2){

    $dbres= new db_sql();
	if($_POST['Submit'] =='Modifier'){
    $dbres->q("
        update incompatibilites_post 
        set produit = '$Produit' , prop1 = '$Propriete1' , val1 = '$Valeur1' , prop2 = '$Propriete2' , val2 = '$Valeur2'
        where id = ".$_POST['id'] );
    }elseif ($_POST['Submit'] =='Supprimer' ){
        $dbres->q("
        delete from incompatibilites_post 
        where id = ".$_POST['id'] );
    }else {
        $dbres->q("
        insert into incompatibilites_post(produit,prop1,val1,prop2,val2) 
        values ('$Produit', '$Propriete1', '$Valeur1', '$Propriete2', '$Valeur2')");
    }

    $resultat = new db_sql($req);
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
    </tr>
    <?
		while(/*$z<$Affichage AND*/ $resultat->n() ) {
            $z++;
            ?>
            <tr id="<? echo $z * 100 ?>">
                <?
                $bgcolor = alternat($z);
                $link = "id=".$resultat->f('id')."&produit=" . $resultat->f('Produit') . "&prop1=" . $resultat->f('prop1') . "&val1=" . $resultat->f('val1') . "&prop2=" . $resultat->f('prop2') . "&val2=" . $resultat->f('val2') . "&ACTE=3";
                ?>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a
                            href="incompatibilite_post_cud.php?<? echo $link ?>"> <? echo $resultat->f('Produit') ?> </a>
                </td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop1') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val1') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop2') ?></td>
                <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val2') ?></td>
            </tr>
            <?
        }

    if($z){
        $suiv_text="Fin de liste";
        if($resultat->n()){
            $suiv_text="Liste non termin?e";
        }
        echo "<tr>";
        echo"<td class='resultat_footer' colspan='4'></td>";
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
