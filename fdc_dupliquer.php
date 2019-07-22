<?php
include "ress/util.php";
include("ress/db_mysql.php");
$id_doc = $_GET[id_doc];
$req="
	select 
		distinct produit 
	from 
		doc_ligne
	where 
		id_doc = $id_doc";
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<input type="hidden" name="id_doc" value="<? echo $id_doc; ?>">
<input type="hidden" name="dupliquer" value="1">
<input type="hidden" name="mode" value="<? echo $_GET[mode] ?>">
<table class="resultat">
      <tr>
         <td class="resultat_tittle"  align = right >S&eacute;lectionner</td>
         <td class="resultat_tittle">Produit</td>
      </tr>
<?
// Boucle de lecture
while($resultat->n() ){
   $z++
?>
      <tr>
         <td class='resultat_list' align = right bgcolor='<? echo alternat($z) ?>'><? champ_binaire_droit ("O","produits_a_dupliquer_".$z, $resultat->f('produit'),  "")?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('produit') ?></td>
      </tr>
<?
}
?> 
</table>
