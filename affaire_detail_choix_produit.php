<?
include("ress/entete.php");

/**********************************************************/
/* Critères
/* $Ref
/* $Lb_produit
/* $Id_gamme
/* $ACTE vaut 1 s'il s'agit juste de renvoyer le libellé
/**********************************************************/
$Ref=strtoupper(trim($Ref));

function envoi ($Id_produit) {
?>
 <body>
 <SCRIPT language = 'javascript'>
         window.opener.document.nouveau.Id_produit.value = '<? echo $Id_produit ?>';
         window.opener.document.nouveau.Ref.value = '<? echo addslashes(req_sim("select Ref from produit where Id_produit = '$Id_produit'","Ref"));?>';
         window.opener.document.nouveau.Lb_produit.value = '<? echo addslashes(req_sim("select Lb_produit from produit where Id_produit = '$Id_produit'","Lb_produit"))?>';
         window.close();
 </SCRIPT>
 </body>
 </html>
<?
}

if ($ACTE==1) {
   envoi ($Id_produit);
   exit;
}

?>

<body class="application">
<table width = 100%>
   <tr>
      <td class="cadre_application" align="center" valign="middle">
         <form method="post" name ="produit" action="affaire_detail_choix_produit.php?ACTE=2">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Recherche de Segments</td>
            </tr>
         </table>
         <table class="requeteur">
                    <tr>
                <td class="requet_right" >Gamme</td>
                <td class="requet_left" ><? drop_down_droit("O","SELECT Id_produit, Lb_produit FROM produit where niveau = 2","Id_gamme", "Id_produit", "Lb_produit", $Id_gamme, false, "produit","O",""," "); ?></td></td>
            </tr>
            <tr>
                <td class="requet_right" >Référence</td>
                <td class="requet_left" ><? champ_ouvert_droit("O","Ref",$Ref,10, 10,"N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Libellé</td>
               <td class="requet_left" ><? champ_ouvert_droit("O","Lb_produit",$Lb_produit,30, 30, "N"); ?></td>
            </tr>
            <tr>
               <td colspan =2 ><input class="requeteur_button" type="submit" name="Submit" value="Chercher"></td>
            <tr>
         </table>
         </form>
      </td>
   </tr>
<?
/**********************************************************/
/* Construction de la requete                             */
/**********************************************************/
if (($ACTE == 2) or ($Ref and !$Lb_produit and !$Id_gamme)) {
   $requete="SELECT
           produit.Id_produit,
           produit.Ref,
           gamme.Lb_produit as Lb_gamme,
           produit.Lb_produit
        FROM
           produit,
           produit gamme
        WHERE
           produit.Id_produit_pere = gamme.Id_produit AND ";
        if($Id_gamme){
                $requete.="produit.Id_produit_pere = $Id_gamme AND ";
        }
        if(!empty($Ref)){
                $requete.="produit.Ref like '$Ref%' AND ";
        }
        if($Lb_produit){
                $requete.="produit.Lb_produit like '$Lb_produit%' AND ";
        }
        // Suppression du dernier AND
        $requete.=" 1 ";
        $resultat = new db_sql($requete);
?>
   <tr>
      <td>
         <table class="resultat">
           <tr>
              <td class="resultat_tittle">Gamme</td>
              <td class="resultat_tittle">Réference</td>
              <td class="resultat_tittle">Libellé</td>
           </tr>
<?
        // Boucle de lecture
   $arr_champs=array("Lb_gamme"=>30,"Ref"=>10,"Lb_produit"=>30);
   while($resultat->n()){
?>
          <tr>
<?
      foreach($arr_champs as $nom=>$lenght){
          // formatage des données
          $val=substr($resultat->f($nom),0,$lenght);
          // affichage
          if($nom=="Ref"){
             echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='affaire_detail_choix_produit.php?ACTE=1&Id_produit=".$resultat->f("Id_produit")."'>",$val,"</a></td>";
          } else {
             echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$val,"</td>";
          }
      }
?>
          <tr>
<?
      $z++;
   }
?>
          <tr>
             <td class='resultat_footer' colspan='3'>Cliquer sur la réference pour sélectionner la valeur</td>
          </tr>
         </table>
      </td>
   </tr>
<?
}
?>
</table>
<?
if (($z == 1) and ($resultat->f('Ref')==$Ref) and !$Id_gamme and !$Lb_produit) {
/* cas d'une validation simple*/
   envoi ($resultat->f('Id_produit'));
   exit;
}
?>
<SCRIPT LANGUAGE="javascript">
   window.focus();
</SCRIPT>
<?
include ("ress/enpied.php");
?>