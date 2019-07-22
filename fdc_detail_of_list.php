<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*   Id_affaire
/*
/*------------------------------------------------*/
//include("ress/entete.php");
require_once("c_parm.php");
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
$requete="
   SELECT
      doc.type_doc,
      dt.date_cde as date,
      doc.id_doc
   FROM
      zdataset_commandeentete dt,
      doc
   WHERE
      dt.id_dataset = doc.id_dataset_entete and 
      dt.fdc= $id_doc union all
   SELECT
      doc.type_doc,
      dt.date_cde as date,
      doc.id_doc
   FROM
      zdataset_bonentete dt,
      doc
   WHERE
      dt.id_dataset = doc.id_dataset_entete and 
      dt.fdc= $id_doc union all
   SELECT
      doc.type_doc,
      dt.date_cde as date,
      doc.id_doc
   FROM
      zdataset_ofentete dt,
      doc
   WHERE
      dt.id_dataset = doc.id_dataset_entete and 
      dt.fdc= $id_doc 	  
	  ";
        /*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
    /*----------- AFFICHAGE ---------*/
    // Affichage des en_têtes
        ?>
<table class="resultat">
  <tr>
    <td class="resultat_tittle">Type OF</td>
    <td class="resultat_tittle">Date de commande</td>
  </tr>       <?
while($resultat->n()) {
// Boucle de lecture
   $z++;
   echo "</tr>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'><a class='resultat' href='doc.php?id_doc=",$resultat->f('id_doc'),"&champ_ofclient=",$resultat->f('id_doc'),"' target='droite'>",$resultat->f('type_doc'),"</a></td>\n";
   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",$resultat->f('date'),"</td>\n";
   echo "</tr>\n";
}
echo "</table>";

        /*-------- Fin Traitement requete ---------------*/
include ("ress/enpied.php");
?>
