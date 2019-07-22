<?php
require_once("c_o_dataset.php");
include ("ress/var_session.php");
require_once ("ress/mail.php");

if ($_GET['id_doc']){


	if ($commentaire = $_GET['commentaire']){
            $message = "Commentaire du mail d'accompagnement <br />";
            $message .= urldecode($commentaire)."<br />";
            $message .= "<br />";
	}

	include_once 'doc_generer.php';
	$id_doc = $_GET['id_doc'];
	$doc = charge_un("select * from doc where id_doc = $id_doc");
	$id_dataset_entete = $doc[id_dataset_entete];
	$list[] = $id_doc;
	$pdf = generer_doc($list);

	$num_cde_imp = req_sim("select d.numcommande_fdc_commande from zdataset_commandeentete d where d.id_dataset = ".$id_dataset_entete, "numcommande_fdc_commande");
		
	$filename = "Commande_" . $num_cde_imp . ".pdf";
	$pdfdoc = $pdf->Output("temp/".$filename, "F");

	$info_po = charge_un("select * from zdataset_commandeentete d ,fournisseur f where d.id_dataset = ".$id_dataset_entete." and d.fournisseur_commande=f.id_fournisseur");
	send_mail($filename, $num_cde_imp, $info_po);

	$commande_statut = charge_un("select 
                                    dv.statut_commande
                                  from 
                                    zdataset_commandeentete dv,
                                    doc d 
                                  where 
                                    dv.id_dataset = d.id_dataset_entete and 
                                    d.id_doc = ".$id_doc);

	if ($commande_statut[statut_commande] == 1) {
        $requete = "
			UPDATE
			   doc
			inner join zdataset_commandeentete dt1 on (
               dt1.id_dataset = doc.id_dataset_entete)
			SET
			   dt1.statut_commande = '2'
		    where
		   	   doc.id_doc = ".$id_doc;
        new db_sql($requete);
	} else {
        echo "<h1>Aucun enregistrement";
	}
}

function send_mail($filename ,$num_cde_imp, $info_po){

	//************** Mail ***************
	//***********************************
    
	global $message;
	$adresse_mail = $info_po[adresse_mail];

	$subject = 'Commande Sodiclair SF n° ' . $num_cde_imp;
	$message .= "Veuillez trouver ci-joint la commande Sodiclair n° " . $num_cde_imp . "
				<BR>Merci de nous accuser réception de cette commande
				<BR>
				<BR>Annabel FLAMAND
				<BR>a.flamand@sodiclair.com				
				<BR>SODICLAIR SAS";

	mail_smtp($adresse_mail, $subject, $message, "temp/".$filename, $filename);
}
echo "<html><script type='text/javascript'>document.location.href='doc.php?id_doc=$id_doc'; </script></html>";
exit();
?>
