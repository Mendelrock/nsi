<?
include("ress/entete.php");
$req="
   SELECT
      *
   FROM
      incompatibilites_limites";
if($produit)$req.=" where produit='".$produit."'";
   $req.=" ORDER BY
      produit";
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);
?>
				
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Produit</td>
         <td class="resultat_tittle">Propri�t� 1</td>
         <td class="resultat_tittle">Valeur 1</td>
         <td class="resultat_tittle">Propri�t� 2</td>
         <td class="resultat_tittle">Mini</td>
         <td class="resultat_tittle">Maxi</td>
       
      </tr>
<?
while(/*$z<$Affichage AND*/ $resultat->n() ){
      $z++;
?>
      <tr id="<? echo $z * 100?>">
         <?
               $bgcolor=alternat($z);	
         ?>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='incompatibilite_limites_detail.php?Id_incompatibilite=<? echo $resultat->f('Id_incompatibilite') ?>' target='droite'><? echo $resultat->f('Produit') ?></a></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop1') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('val1') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('prop2') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo round($resultat->f('min'),4) ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo round($resultat->f('max'),4) ?></td>
            </tr>
<?
}
if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non termin�e";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='7'>Cliquer sur le produit pour ouvrir une incompatibilit�</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
   ?><?
}
   echo "</tr></table>";

?>
				
      </td>
   </tr>
</table>
<!--</form>-->
<?
if ($liste_go) {
?>
<script langage="javascript">toile.submit();</script>
<?
}
include ("ress/enpied.php");
?>