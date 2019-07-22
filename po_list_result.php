<?
include("ress/entete.php");
require_once("c_o_dataset.php");
if ($ACTE==1) {
/*-----------------PARAMETRES---------------------
/*  $Libelle
/*  $Reference
/*  $id_fournisseur
/*------------------------------------------------*/

$_SESSION[commande_liste_numero]        = $Numero;
$_SESSION[commande_liste_statut]      = $Id_statut_po;
$_SESSION[commande_liste_fournisseur] = $id_fournisseur;
$_SESSION[commande_liste_article]= $lb_article;
$_SESSION[commande_liste_numero] = $Numero;
$_SESSION[commande_liste_date_d]= $DateCom_d;
$_SESSION[commande_liste_date_f]= $DateCom_f;
$_SESSION[po_liste_go]               = 1;

$champs = "";

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales

// Critere de recherche
if($Numero){
   $requete.="po.lb_po like '%".$Numero."%' AND ";
}

if($id_fournisseur){
   $requete.="fournisseur.Id_fournisseur = '$id_fournisseur' AND ";
}

if($Id_statut_po[0]){
   $requete.="po_statut.Id_statut_po IN ( ".implode(", ", $Id_statut_po)." ) AND ";
}

if($lb_article){
   $requete.="lb_article LIKE '$lb_article%' AND ";
}

if($DateCom_d){
   $requete.="date_po >= '$DateCom_d' AND ";
}

if($DateCom_f){
   $requete.="date_po <= '$DateCom_f' AND ";
}
if($mode_detail_echu) {
	$mode_detail = 1;
	$requete .= " ADDDATE(date_po, article.delai) < CURDATE() and "; 
}

if($mode_detail_reliquat) {
	$mode_detail = 1;
	$requete .= " po_ligne.qt_solde > 0 and "; 
}

if($mode_detail) {
	$champs = " 
		fournisseur_article.reference,
		po_ligne.qt,
		po_ligne.qt_solde,
		ADDDATE(date_po, article.delai) as echeance,
	";
	$join = "
		inner JOIN po_ligne ON po_ligne.id_po = po.id_po
		inner JOIN article ON po_ligne.id_article = article.id_article
		left outer join fournisseur_article on (po_ligne.id_article = fournisseur_article.id_article and po.Id_fournisseur = fournisseur_article.Id_fournisseur)
	";
}

$req="
   SELECT
      po.id_po,
      po.lb_po,
	   date_po,
      lb_fournisseur,
	  ".$champs."
      Lb_statut_po
   FROM
		po
		inner JOIN fournisseur on (fournisseur.Id_fournisseur=po.Id_fournisseur)
		inner JOIN po_statut on (po.id_statut_po = po_statut.Id_statut_po)
		$join
   WHERE 
		$requete 1=1
   ORDER BY
      date_po";
   //LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
   <table style="width:500;background-color:White;border :none;cellpadding:0px;cellspacing:0px;">
      <tr>
         <td nowrap class="resultat_tittle" width="100">Numéro</td>
         <td nowrap class="resultat_tittle" width="100">Date</td>
         <td nowrap class="resultat_tittle" width="200">Fournisseur</td>
         <td nowrap class="resultat_tittle" width="100">Statut</td>
		 <? 
		 if($mode_detail) {
			 ?>
			<td nowrap class="resultat_tittle" width="100">Référence</td>
			<td nowrap class="resultat_tittle" width="100">Quantite</td>
			<td nowrap class="resultat_tittle" width="100">Reliquat</td>
			<td nowrap class="resultat_tittle" width="100">Echeance</td>
			 <?
		 }
		 ?>
      </tr>
<?
$_SESSION[po] = [];
while($z<$Affichage AND $resultat->n() ){
      $z++;
?>
      <tr>
               <td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='po_detail.php?Id_po=<? echo $resultat->f('id_po') ?>' target='droite'><? echo $resultat->f('lb_po') ?></a></td>
               <td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('date_po') ?></td>
               <td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('lb_fournisseur')?></td>
               <td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Lb_statut_po') ?></td>
			    <? 
				 if($mode_detail) {
					 ?>
					<td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('reference') ?></td>
					<td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('qt') ?></td>
					<td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('qt_solde') ?></td>
					<td nowrap class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('echeance') ?></td>
					 <?
				 }
				?>
      </tr>
<?
        $_SESSION[po][]=array(
			"Numéro"=>$resultat->f('lb_po'),
			"Date"=>$resultat->f('date_po'),
			"Fournisseur"=>$resultat->f('lb_fournisseur'),
			"Statut"=>$resultat->f('Lb_statut_po'),
			"Référence"=>$resultat->f('reference'),
			"Quantite"=>$resultat->f('qt'),
			"Reliquat"=>$resultat->f('qt_solde'));

   }
}
if($z){
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='3'>Cliquer sur le numéro pour ouvrir une commande</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
}
echo "</tr></table>";
/*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
