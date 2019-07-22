<?
include("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE==1) {
/*-----------------PARAMETRES---------------------
/*  $Libelle
/*  $Reference
/*  $id_fournisseur
/*
/*------------------------------------------------*/

$_SESSION[correspondance_liste_site_produit]           = $site_produit;
$_SESSION[correspondance_liste_site_propriete]          = $site_propriete;
$_SESSION[correspondance_liste_site_valeur]          = $site_valeur;
$_SESSION[correspondance_liste_nsi_produit]           = $nsi_produit;
$_SESSION[correspondance_liste_nsi_propriete]          = $nsi_propriete;
$_SESSION[correspondance_liste_nsi_valeur]          = $nsi_valeur;
$_SESSION[correspondance_liste_go]               = 1;

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales


if($site_produit){
   $requete.="site_produit like '%".$site_produit."%' AND ";
}

if($site_propriete){
   $requete.="site_propriete like '%".$site_propriete."%' AND ";
}

if($site_valeur){
   $requete.="site_valeur like '%".$site_valeur."%' AND ";
}

if($nsi_produit){
   $requete.="nsi_produit like '%".$nsi_produit."%' AND ";
}

if($nsi_propriete){
   $requete.="nsi_propriete like '%".$nsi_propriete."%' AND ";
}

if($nsi_valeur){
   $requete.="nsi_valeur like '%".$nsi_valeur."%' AND ";
}

$req="
   SELECT
      *
   FROM
      correspondance
   WHERE $requete
      1=1
   ORDER BY
      nsi_produit";
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Site produit</td>
         <td class="resultat_tittle">Site propriété</td>
         <td class="resultat_tittle">Site valeur</td>
         <td class="resultat_tittle">NSI produit</td>
         <td class="resultat_tittle">NSI propriété</td>
         <td class="resultat_tittle">NSI valeur</td>
      </tr>
<?
// Boucle de lecture
while ( $resultat->n() AND $z<$Affichage ) {
?>
      <tr>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='#' onclick="showFenetreCorrespondance('1',<? echo $resultat->f('id_correspondance') ?>);"><? echo $resultat->f('site_produit') ?></a></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='#' onclick="showFenetreCorrespondance('1',<? echo $resultat->f('id_correspondance') ?>);"><? echo $resultat->f('site_propriete') ?></a></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='#' onclick="showFenetreCorrespondance('1',<? echo $resultat->f('id_correspondance') ?>);"><? echo $resultat->f('site_valeur') ?></a></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('nsi_produit') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('nsi_propriete') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('nsi_valeur') ?></td>
      </tr>
<?
$z++;
}
if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='2'>Cliquer sur Site produit pour ouvrir une correspondance</td>";
   echo"<td class='resultat_footer' align='center'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
   
}
echo "</tr></table>";
}
        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
