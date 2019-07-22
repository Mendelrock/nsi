<?
include("ress/entete.php");
require_once("fonction.php");
if ($ACTE==1) {
$_SESSION[feuille_liste_go] = 1;
$_SESSION[NumCommande] = trim($_POST[NumCommande]);
$Date_com    = $_POST['Date_com'];
$Date_com_f = $_POST['Date_com_f'];

// Critere de recherche
$_SESSION[_champ_statut_fdc] = $_POST['_champ_statut'];
if(trim($_SESSION[_champ_statut_fdc])){
   $requete.="dt.statut = '".trim($_SESSION[_champ_statut_fdc])."' AND ";
}
$_SESSION[Date_com_fdc]   = $Date_com;
$_SESSION[Date_com_f_fdc] = $Date_com_f;
if($Date_com){
   //$Date_com = formatterDate(substr($Date_com,0,6).'20'.substr($Date_com,6,2)) ;	
   $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_com', '%Y-%m-%d' ) AND ";
}
if($Date_com_f){
   $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_com_f', '%Y-%m-%d' )  AND ";
}
if($_SESSION[NumCommande]){
   $requete.="dt.numcommande_fdc like '%".$_SESSION[NumCommande]."%' AND ";
}

   $req="
   SELECT
      doc.id_doc,
	  commentaire,
      Raison_sociale,
      Lb_statut,
      dt.nomclient_fdc as nomclient_fdc,
      dt.statut as statut,
      dt.numcommande_fdc as numcommande_fdc,
      dt.date_cde as date_cde,
      dt.date_creation as date_creation
   FROM
      zdataset_fdcentete dt,
      affaire,
      statut,
      client,
      doc
   WHERE $requete
      type_doc='fdc' AND
      dt.id_dataset = doc.id_dataset_entete AND
      affaire.id_affaire = dt.affaire AND statut.Id_statut=affaire.Id_statut AND
      affaire.Id_client=client.Id_client
   ORDER BY
      dt.numcommande_fdc DESC
   LIMIT 0, ".($Affichage+2);
/*----------- EXECUTION ---------*/
$resultat = new db_sql($req);

/*----------- AFFICHAGE ---------*/
/* Affichage des en_têtes */
?>
<body class="application">
   <table class="resultat">
      <tr>
         <td class="resultat_tittle">Nom affaire</td>
         <td class="resultat_tittle">Statut de l'affaire</td>
         <td class="resultat_tittle">Nom du client</td>
         <td class="resultat_tittle">Statut</td>
         <td class="resultat_tittle">N° de commande</td>
         <td class="resultat_tittle">Date de commande</td>
      </tr>
<?
while($z<$Affichage AND $resultat->n() ){
   $z++
?>
      <tr>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('commentaire') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('Lb_statut') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('nomclient_fdc') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><a class='resultat' href='doc.php?id_doc=<? echo $resultat->f('statut') ?>' target='droite'><? echo $resultat->f('id_doc') ?></a></td>;
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>'><? echo $resultat->f('numcommande_fdc') ?></td>
         <td class='resultat_list' bgcolor='<? echo alternat($z) ?>' align = 'right'><? echo $resultat->f('date_cde') ?></td>
      </tr>
<?
}
if($z){
   $total_e = explode('.',$total);
   $total = $total_e[0].'.'.substr($total_e[1].'00',0,2);
   $suiv_text="Fin de liste";
   if($resultat->n()){
      $suiv_text="Liste non terminée";
   }
   echo "<tr>";
   echo"<td class='resultat_footer' colspan='5'>Cliquer sur statut pour ouvrir une commande</td>";
   echo"<td class='resultat_footer' align='center' colspan='2'><span style='font-weight:bold'>",$suiv_text,"</span></td>";
}
echo "</tr></table>";
}
        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
