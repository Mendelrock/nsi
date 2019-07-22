<?php

// incompatibilité post dimensionnel gestion

include("ress/entete.php");

?>
    <body class="application">

	<?php include_once 'include_import_export_incomp_dimensionnel_post.php'; ?>

<? include_once 'incompatibilite_post_dimensionnel_gestion_cud.php'; ?>

    <form method="post" name ="incompatibilite_post_dimensionnel_gestion" action="incompatibilite_post_dimensionnel_gestion_result.php?ACTE=1" target="resultat">
        <table class="cadre_application">
            <tr>
                <td class="cadre_application">

                    <table class="requeteur">
                        <tr>
                            <td class="requet_right">Produit</td>
                            <td class="requet_left" ><? drop_down_droit ("L","","produit", "", "", "", false, "incompatibilites", $fg_oblig="N", $onglets_manquant_adresse, $onglets_manquant_libelle); ?></td>
                            <td class="requet_right"></td>
                            <td class="requet_left"></td>
                            <td class="requet_right">Affichage</td>
                            <td class="requet_left"><? champ_numeric_droit("O","Affichage", $Affichage_defaut, 0, 5, "affaire", "O"); ?> (Lignes)</td>
                            <td class="requet_right" ><input class="requeteur_button" type="submit" name="Submit" value="Chercher")"></td>
                        </tr>
                    </table>
                    <table class="requeteur">
                        <tr>
                            <td><iframe src="" width=100% height=550 name="resultat" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
<?
if ($liste_go) {
    ?>
    <script langage="javascript">incompatibilite_post_dimensionnel_gestion.submit();</script>
    <?
}



?>