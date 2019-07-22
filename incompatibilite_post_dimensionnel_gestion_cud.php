<?php
$ACTE = $_GET['ACTE'];
if($ACTE ==3){
    include("ress/entete.php");
}
/*----------- EXECUTION ---------*/
$req="  SELECT * FROM incompatibilites_dimension_post  ORDER BY produit";
$resultat = new db_sql($req);
$onglets_manquant_adresse = "";
$onglets_manquant_libelle = "Choisir";
$files = scandir("param_2_produits");
foreach ($files as $i => $fichier) {
    if($fichier!="." && $fichier!=".."){
        $produit = substr($fichier,0,strlen($fichier)-4);
        $onglets_manquant_adresse .= "|".$produit;
        $onglets_manquant_libelle .= "|".$produit;
    }
}
?>
<form method="post" name ="incompatibilite_post_dimensionnel_create_update_delete" action="incompatibilite_post_dimensionnel_gestion_result.php?ACTE=2" target="resultat">
    <table class="requeteur">
        <table class="requeteur">
            <tr>
                <td class="requet_left">Produit</td>
                <td class="requet_left">Propriété 1</td>
                <td class="requet_left">Valeur 1</td>
                <td class="requet_left">Propriété 2</td>
                <td class="requet_left">Valeur 2</td>
                <td class="requet_left">Propriété 3</td>
                <td class="requet_left">Valeur 3</td>
                <td class="requet_left"></td>
            </tr>
            <tr>
                <td class="requet_left"><? drop_down_droit ("L","","Produit", "", "", htmlspecialchars($_GET['produit']), false, "incompatibilites", $fg_oblig="N", $onglets_manquant_adresse, $onglets_manquant_libelle); ?></td>
                <td class="requet_left" ><? champ_ouvert_droit("O","Propriete1", htmlspecialchars($_GET['prop1']),60, 30, "N"); ?></td>
                <td class="requet_left"><? champ_ouvert_droit("O","Valeur1", htmlspecialchars($_GET['val1']),60, 10, "N"); ?></td>
                <td class="requet_left"><? champ_ouvert_droit("O","Propriete2", htmlspecialchars($_GET['prop2']),60, 30, "N"); ?></td>
                <td class="requet_left"><? champ_ouvert_droit("O","Valeur2", htmlspecialchars($_GET['val2']),60, 10, "N"); ?></td>
                <td class="requet_left"><? champ_ouvert_droit("O","Propriete3", htmlspecialchars($_GET['prop3']),60, 30, "N"); ?></td>
                <td class="requet_left"><? champ_ouvert_droit("O","Valeur3", htmlspecialchars($_GET['val3']),60, 10, "N"); ?></td>

                <input type='hidden' name='id' value="<? echo $_GET['id'] ?>">
                <?php if($ACTE==3){ ?>
                    <td class="requet_left" ><input class="requeteur_button" type="submit" name="Submit" value="Modifier"></td>
                    <td class="requet_left" ><input class="requeteur_button" type="submit" name="Submit" value="Supprimer"></td>
                <?php } else { ?>
                    <td class="requet_left" ><input class="requeteur_button" type="submit" name="Submit" value="Ajouter"></td>
                <?php } ?>
            </tr>
        </table>
</form>


