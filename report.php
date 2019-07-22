<?
include("ress/entete.php");

/*-----------------PARAMETRES---------------------*/
/* Id_report
/*------------------------------------------------*/
if (!$Id_report) {
   $Id_report = "activite_tel";
}
if (!$critere_societe) {
   $critere_societe = "!=76";
}

if (!$Id_type) {
   $Id_type = "T";
}
if (!$Date_d || empty($Date_d)) {
                $Date_d=substr(aujourdhui(),0,8)."01";
}
if (!$Date_f || empty($Date_f)) {
                $Date_f=substr(aujourdhui(),0,8).date("t");
}

?>
<body class="application">
<form method="post" name="report" action="report.php">
<table class="menu_haut_resultat">
   <tr>
      <td class="cadre_application" align="center" valign="middle">
         <table class="menu_haut">
            <tr>
               <td class="menu_haut">Tableau de bord : <? drop_down_droit("O","","Id_report", "Id_report", "Lb_report",$Id_report,true, "Id_report","N","activite_tel|activite_ter|portefeuille|fdc","Activité téléphonique|Activité terrain|Portefeuille|Feuille de côte"); ?>
               <td class="menu_haut">Période :
               <? champ_date(Date_d, $Date_d, "periode", "O"); ?> au <? champ_date(Date_f, $Date_f, "periode", "O"); ?></td>
               <td class="menu_haut"> Cible : <? drop_down_droit("O","SELECT id_type, lb_type FROM type",
                                                                  "Id_type", "Id_type", "Lb_type",$Id_type,false,"","N","T","Toutes"); ?></td>
               <td><input class="requet_button" type="submit" value="Appliquer" OnClick="return champ_oblig('report')"></td>
            </tr>
         </table>
</form>
               <?
$temp = explode ("x",$Id_report);
//$Mode = $temp[1];

if ($Id_type != 'T') {
   $clause_type = " inner join client on (fait.id_client = client.id_client and client.id_type = $Id_type ) ";
}

$nb = 2;

if (($_SESSION[id_profil] == 1) or ($_SESSION[id_profil] == 4)) $clause_user = " and vendeur.id_utilisateur =$_SESSION[id_utilisateur] ";
if ($_SESSION[id_profil] == 2) $clause_user = " and chef.id_utilisateur =$_SESSION[id_utilisateur] ";

$req = new db_sql("
   SELECT
      vendeur.id_utilisateur as Id_vendeur,
      vendeur.Nom as Nom_vendeur,
      chef.id_utilisateur as Id_chef,
      chef.Nom    as Nom_chef
   FROM
      utilisateur as vendeur,
      utilisateur as chef,
      autorisation, 
      droit
   WHERE
      vendeur.Id_responsable = chef.Id_utilisateur and
      autorisation.id_profil = vendeur.id_profil and
      autorisation.id_droit = droit.id_droit and
      droit.code = 'vutdb' $clause_user
   ORDER BY
      chef.Nom,
      vendeur.Nom");
include ("report_".$temp[0].".php");
include ("ress/enpied.php");
?>
