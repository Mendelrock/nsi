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

$_SESSION[toile_liste_libelle]        = $Libelle_atelier;
$_SESSION[toile_liste_libelle_sr]      = $Libelle_sr;
$_SESSION[toile_liste_Selecteur_toile] = $Selecteur_toile;
$_SESSION[toile_liste_Orientation]        = $Orientation;
$_SESSION[toile_liste_Couleur]      = $Couleur;
$_SESSION[toile_liste_Gamme] = $Gamme;
$_SESSION[article_liste_go]               = 1;

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales


// Critere de recherche
if($Libelle_atelier){
   $requete.="lb_toile_atelier like '%$Libelle_atelier%' AND ";
}
if($Libelle_sr){
   $requete.="lb_toile_sr like '%$Libelle_sr%' AND ";
}
if($Couleur){
   $requete.="Couleur like '%$Couleur%' AND ";
}
if($Gamme){
   $requete.="Gamme like '%$Gamme%' AND ";
}

if($Orientation){
   $requete.="Orientation like '%$Orientation%' AND ";
}

if($Selecteur_toile){
   if($Selecteur_toile=="Rideaux")
      $requete.="selecteur_rideau=1 AND ";
   else if($Selecteur_toile=="Doublure")
      $requete.="selecteur_doublure=1 AND ";
   else if($Selecteur_toile=="Store")
      $requete.="selecteur_store=1 AND ";
   else if($Selecteur_toile=="Enrouleur Exterieur")
      $requete.="selecteur_enrouleur_exterieur=1 AND ";
   else if($Selecteur_toile=="Enrouleur Interieur")
       $requete.="selecteur_enrouleur_interieur=1 AND ";
   else if($Selecteur_toile=="Coffre Interieur")
       $requete.="selecteur_coffre_interieur=1 AND ";
   else if($Selecteur_toile=="Coffre Exterieur")
       $requete.="selecteur_coffre_exterieur=1 AND ";
   else if($Selecteur_toile=="Coussin")
      $requete.="selecteur_coussin=1 AND ";
   else if($Selecteur_toile=="Paroi")
      $requete.="selecteur_paroi=1 AND ";
}

$req="
   SELECT
      *
   FROM
      toile
   WHERE $requete
      1=1
   ORDER BY
      lb_toile_atelier";
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">id_toile</td>
         <td class="resultat_tittle">Libellé atelier</td>
         <td class="resultat_tittle">Libellé SR</td>
         <td class="resultat_tittle">Orientation</td>
         <td class="resultat_tittle">Couleur</td>
         <td class="resultat_tittle">Gamme</td>
      </tr>
<?
while($z<$Affichage AND $resultat->n() ){
      $z++;
?>
      <tr id="<? echo $z * 100?>">
         <?
               $bgcolor=alternat($z);	
         ?>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('id_toile') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='toile_detail.php?Id_toile=<? echo $resultat->f('id_toile') ?>' target='droite'><? echo $resultat->f('lb_toile_atelier') ?></a></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_toile_sr') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Orientation') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Couleur') ?></td>
               <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Gamme') ?></td>
         
	  </tr>
<?
}
if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='4'>Cliquer sur le libellé atelier pour ouvrir une toile</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
   ?><?
}
echo "</tr></table>";
}
        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
