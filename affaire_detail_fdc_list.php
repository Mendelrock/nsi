<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*   Id_affaire
/*
/*------------------------------------------------*/
include("ress/entete.php");
require_once("c_parm.php");
?>
<body class="application">
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne"><a class="interne" href="affaire_detail_interaction_list.php?Id_affaire=<? echo $Id_affaire; ?>">Actions liées</a></td>
         <td class="interne_actif">Feuilles de cotes</td>
         <td class="interne"><a class="interne" href="affaire_detail_produit_list.php?Id_affaire=<? echo $Id_affaire; ?>">Produits</a> </td>
         <td width="467"></td>
      </tr>
   </table>
<?
/*---------------------------------------------*/
// Requete, SELECT collecte des données principales
$requete="
	SELECT
		dt.date_cde as date,
		dt.nomclient_fdc as raison_sociale,
		dt.date_cde as libelle,
		dt.statut as statut,
		id_doc,
		concat(u.nom,' ' ,u.prenom) as commercial,
		a.commentaire as nom_affaire,
		statut.Lb_statut as statut_affaire
	FROM
		zdataset_fdcentete dt,
		doc,
		utilisateur u,
		affaire a,
		statut,
		client
	WHERE
		type_doc='fdc' and
		dt.id_dataset = doc.id_dataset_entete and 
		dt.affaire= $Id_affaire and
		statut.Id_statut=a.Id_statut and
		a.id_affaire = dt.affaire and 
		a.Id_client = client.Id_client and 
		u.id_utilisateur = a.id_utilisateur 
	ORDER BY
		doc.id_doc DESC";
        /*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
    /*----------- AFFICHAGE ---------*/
    // Affichage des en_têtes
?>
<table class="resultat">
  <tr>
     <td class="resultat_tittle">N° Feuille de cote</td>
     <td class="resultat_tittle">Raison sociale</td>
     <td class="resultat_tittle">N° de commande ou BCC </td>
     <td class="resultat_tittle">Statut Feuille de cotes</td>
     <td class="resultat_tittle">Nom Affaire</td>
     <td class="resultat_tittle">Statut Affaire</td>
     <td class="resultat_tittle">Commercial</td>
  </tr>       
<?
$lastid_doc="";
while($resultat->n()){
// Boucle de lecture
   $z++;
   echo "<tr>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('id_doc'),"</td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('raison_sociale'),"</td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('libelle'),"</td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='doc.php?id_doc=",$resultat->f('id_doc'),"' target='droite'>",$resultat->f('statut'),"</a></td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('nom_affaire'),"</td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('statut_affaire'),"</td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('commercial'),"</td>\n";
   echo "</tr>\n";
}
echo "<tr>\n";
echo"<td class='resultat_footer' colspan='7'>Cliquer sur la date de création pour ouvrir un contact</td>";
echo "</tr></table>";

        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
