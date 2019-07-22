<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------*/
if (droit_utilisateur("admin")) {
$url[1] = 'admin_utilisateur.php';
//$url[2] = 'admin_produit.php?NIVEAU=1';
//$url[3] = 'admin_produit.php?NIVEAU=2';
//$url[4] = 'admin_import_siret.php';
//$url[5] = 'admin_import_type.php';
//$url[6] = 'admin_sectorisation_maj_consigne.php';
//$url[61] = 'admin_sectorisation_maj_binome.php';
//$url[62] = 'admin_sectorisation_traitement.php';
//$url[7] = 'admin_marquage_valeur.php';
//$url[71] = 'admin_marquage_import.php';
$url[2] = 'admin_coherence.php';
$url[3] = 'admin_profil.php';
$url[4] = 'admin_secto.php';
$url[5] = 'admin_import_clients.php';
$url[6] = 'admin_fournisseur.php';
$url[7] = 'correspondance_produit_list.php';
$url[8] = 'correspondance_propriete_list.php';
$url[9] = 'correspondance_valeur_list.php';
$url[10] = 'toile_list.php';
$url[11] = 'article_list.php';
$url[12] = 'admin_import_articles.php';
$url[13] = 'configs.php';
?>
<body class="application">
<table class="cadre_application">
   <tr>
      <td class="cadre_application" align="center" valign="middle">
        <table class="menu_haut"><form name = "Id_ecran">
           <tr>
              <td class="menu_haut">Administration</td>
              <td class="menu_haut"><? drop_down_droit("O","","Id_ecran", "Id_ecran", "Lb_ecran",$Id_ecran,true, "Id_ecran","N","|1|2|3|4|5|6|7|8|9|10|11|12|13","|Utilisateurs|Cohérence technique|Profils|Sectorisation|Import client|Fournisseurs|Correspondances produit|Correspondances propriété|Correspondances valeur|Collections|Articles|Export/Import collection/articles|Configurations"); ?></td>
           </tr></form>
        </table>
        <table class="requeteur">
           <tr>
              <td><iframe src="<? echo $url[$Id_ecran]; ?>" width=100% height=650 name="bas" align="middle" scrolling=auto frameborder="0" allowtransparency="true"></iframe></td>
           </tr>
        </table>
      </td>
   </tr>
</table>
<?
}
include ("ress/enpied.php");
?>
