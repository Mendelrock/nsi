<?
include("ress/entete.php");
$req="
   SELECT
      *
   FROM
      incompatibilites_limites
   ORDER BY
      produit";
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);
$onglets_manquant_adresse = "";
$onglets_manquant_libelle = "Choisir";
$files = scandir("param_2_produits");
foreach ($files as $i => $fichier) {	
   //foreach ($GLOBALS[parms][produits] as $produit => $infos_produit) {
   	  if($fichier!="." && $fichier!=".."){
      	 $produit = substr($fichier,0,strlen($fichier)-4);
         $onglets_manquant_adresse .= "|".$produit;
         $onglets_manquant_libelle .= "|".$produit;
      }
   }
?>
<body class="application">

<?php include_once 'include_import_export_incomp_limites.php'; ?>

<form method="post" name ="toile" action="incompatibilite_limites_list_result.php?ACTE=1" target="resultat">
<table class="cadre_application">
   <tr>
      <td class="cadre_application">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut"></td>
               <? if(droit_utilisateur("toile")){
                   echo "<td class='externe'><A class='externe' href='incompatibilite_limites_detail.php'>Créer une incompatibilité</A></td>";
                  }
                ?>
            </tr>
         </table>
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
<script langage="javascript">toile.submit();</script>
<?
}
include ("ress/enpied.php");
?>
