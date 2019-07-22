<?php
include ("ress/var_session.php");
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");

$list_id_doc = array();

if($_GET[id_fdc]){
    $requete="
	   SELECT DISTINCT
	      doc.id_doc
	   FROM
	      zdataset_ofentete dt2,
	      doc
	   WHERE 
	      dt2.fdc=".$_GET[id_fdc]." AND
	      dt2.id_dataset = doc.id_dataset_entete 
	   ORDER BY
	      doc.id_doc DESC ";

}elseif(!$_GET[id_doc]){
    $list_id_doc = $_SESSION['tab_id_doc'];
}else{
    $list_id_doc[] = $_GET[id_doc] ;
}

imprimer($list_id_doc, $requete);

/*
-------------------------------------------------
 Imprime un doc	
-------------------------------------------------*/
function imprimer($list_id_doc="",$requete="") {

    if(!$list_id_doc){
        $liste_of_cree = new db_sql($requete);
        $list_id_doc = array();
        while($liste_of_cree->n()) {
            $list_id_doc[] = $liste_of_cree->f('id_doc');
        }
    }
	
	if(!empty($list_id_doc)){
		
	include_once 'doc_generer.php';
	$pdf = generer_doc($list_id_doc);

		// Mise א jour du statut א imprimer

	if (is_array($list_id_doc)) {
		foreach ($list_id_doc as $of) {
            $requete = "
					UPDATE
						doc
						inner join zdataset_ofentete dt1 on (
						dt1.id_dataset = doc.id_dataset_entete)
					SET
						dt1.statut_of = 'Imprimי'
					where
						doc.id_doc = $of and
						dt1.statut_of = 'Crיי'";
							new db_sql($requete);
            $requete = "
					UPDATE
						doc
						inner join zdataset_bonentete dt1 on (
						dt1.id_dataset = doc.id_dataset_entete)
					SET
						dt1.statut_of = 'Imprimי'
					where
						doc.id_doc = $of and
						dt1.statut_of = 'Crיי'";
							new db_sql($requete);
		}
	}

	$pdf->Output();
	
	} else {
		echo "Aucun OF א afficher ";	
	}
}
?>