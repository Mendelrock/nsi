<?
include("ress/entete.php");

/**********************************************************/
/* Critères
/* $Code_naf
/* $Lb_naf
/* $ACTE vaut 1 s'il s'agit juste de renvoyer le libellé
/**********************************************************/
$Code_naf=strtoupper(trim($Code_naf));

function envoi ($Code_naf) {
?>
 <body>
 <SCRIPT language = 'javascript'>
         window.opener.document.client.Code_naf.value = '<? echo $Code_naf ?>';
         window.opener.document.client.lb_naf.value = '<? echo addslashes(req_sim("select Lb_naf from code_naf where Code_naf = '$Code_naf'","Lb_naf"));?>';
         window.close();
 </SCRIPT>
 </body>
 </html>
<?
}

if ($ACTE==1) {
   envoi ($Code_naf);
   exit;
}

?>

<body class="application">
<table width = 100%>
   <tr>
      <td class="cadre_application" align="center" valign="middle">
         <form method="post" name ="naf" action="client_detail_choix_naf.php?ACTE=2">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Recherche de Code Naf</td>
            </tr>
         </table>
         <table class="requeteur">
            <tr>
                <td class="requet_right" >Code</td>
                <td class="requet_left" ><? champ_ouvert_droit("O","Code_naf",$Code_naf,4, 4,"N"); ?></td>
            </tr>
            <tr>
               <td class="requet_right">Libellé</td>
               <td class="requet_left" ><? champ_ouvert_droit("O","Lb_naf",$Lb_naf,60, 60, "N"); ?></td>
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


if (($ACTE == 2) or $Code_naf) {
   $resultat = new db_sql("SELECT
           Code_naf,
           Lb_naf
        FROM
           code_naf
        WHERE
           Code_naf like '$Code_naf%' AND
           Lb_naf like '$Lb_naf%'");
?>
   <tr>
      <td>
         <table class="resultat">
           <tr>
              <td class="resultat_tittle">Code</td>
              <td class="resultat_tittle">Libellé</td>
           </tr>
<?
        // Boucle de lecture
   $arr_champs=array("Code_naf"=>4,"Lb_naf"=>60);
   while($resultat->n()){
?>
          <tr>
<?
      foreach($arr_champs as $nom=>$lenght){
          // formatage des données
          $val=substr($resultat->f($nom),0,$lenght);
          // affichage
          if($nom=="Code_naf"){
             echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='client_detail_choix_naf.php?ACTE=1&Code_naf=".$val."'>",$val,"</a></td>";
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
             <td class='resultat_footer' colspan='2'>Cliquer sur le Code Naf pour sélectionner la valeur</td>
          </tr>
         </table>
      </td>
   </tr>
<?
}
?>
</table>
<?
if (($z == 1) and ($resultat->f('Code_naf')==$Code_naf) and ($Lb_naf=='')) {
/* cas d'une validation simple*/
   envoi ($Code_naf);
   exit;
}
?>
<SCRIPT LANGUAGE="javascript">
   window.focus();
</SCRIPT>
<?
include ("ress/enpied.php");
?>