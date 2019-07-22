<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Id_client
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

include 'client_detail_menugenerique.php'; echo get_menu(7);
?>

<?php

/*---------------------------------------------*/
// Requete, SELECT collecte des donn?es principales
$requete="
   SELECT
	  dt.date_cde as date,
	  dt.nomclient_fdc as interlocuteur,
	  dt.numcommande_fdc as libelle,
	  dt.statut as statut,
	  id_doc,
	  concat(u.nom,' ' ,u.prenom) as commercial
   FROM
      zdataset_fdcentete dt,
	  doc,
	  utilisateur u,
	  affaire a
   WHERE
      a.id_affaire = dt.affaire and 
      u.id_utilisateur = a.id_utilisateur and 
      dt.id_dataset = doc.id_dataset_entete and 
	  type_doc='fdc' and 
	  dt.affaire in (SELECT id_affaire FROM affaire WHERE Id_client = ".$Id_client.") ";
/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
/*----------- AFFICHAGE ---------*/
// Affichage des en_t?tes
?>

<table class="resultat">
    <tr>
        <td class="resultat_tittle">N° Feuille de cote</td>
        <td class="resultat_tittle">Nom du client</td>
        <td class="resultat_tittle">N° de commande ou BCC </td>
        <td class="resultat_tittle">Statut</td>
        <td class="resultat_tittle">Commercial</td>
    </tr>
    <?
    $lastid_doc="";
    while($resultat->n()){
// Boucle de lecture
        $z++;
        echo "<tr>\n";
        echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('id_doc'),"</td>\n";
        echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('interlocuteur'),"</td>\n";
        echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('libelle'),"</td>\n";
        echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='doc.php?id_doc=",$resultat->f('id_doc'),"' target='droite'>",$resultat->f('statut'),"</a></td>\n";
        echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('commercial'),"</td>\n";

        echo "</tr>\n";
    }
    echo "<tr>\n";
    echo "</tr></table>";

    /*-------- Fin Traitement requete ---------------*/

include ("ress/enpied.php");
?>
