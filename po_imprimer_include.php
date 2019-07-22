<?
include ("ress/var_session.php");
require_once("ress/fpdf/fpdf.php");
require_once("c_o_dataset.php");
require_once ("ress/mail.php");
/*-------------------------------------------------
 Imprime une commande 
-------------------------------------------------*/
class PDF extends FPDF {
	public $x1;//abcisse courante
   public $y1;//ordonnée courante
	function PageWidth(){
		return (int) $this->w-$this->rMargin-$this->lMargin;
   }

      function PageHeight(){
         return (int) $this->h-$this->tMargin-$this->bMargin;
      }
}
function entete(&$pdf) {
	$pdf->SetFont('Arial','',10);
	$y = $pdf->getY();
	$pdf->MultiCell($pdf->PageWidth()*3/4, 5, "Référence", 'LTRB','C');
	$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*3/4, $y);
	$pdf->MultiCell($pdf->PageWidth()/12, 5, "Prix unit", 'LTRB','C');
	$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*10/12, $y);
	$pdf->MultiCell($pdf->PageWidth()/12, 5, "Quantité", 'LTRB','C');
	$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*11/12, $y);
	$pdf->MultiCell($pdf->PageWidth()/12, 5, "Total", 'LTRB','C');
}

function empied(&$pdf) {
	$pdf->SetXY($pdf->GetX(), $GLOBALS[limit_tableau_normal]+45);
	$pdf->MultiCell($pdf->PageWidth(), 5, "SODICLAIR - Siège social : Pontault - 28140 NOTTONVILLE - SAS au Capital de 600.000 €
  RCS CHARTRES : 343 228 987 - APE : 1392 Z
  Tel : 02 37 96 94 94 - Fax : 02 37 96 90 32", 'T', 'C');

}

function po_imprimer($id_po,$send_commande_by_mail) {
	$info_responsable = charge_un("select Id_responsable from utilisateur where Id_utilisateur = $_SESSION[id_utilisateur]");
	$info_po = charge_un("select * from po,fournisseur where id_po = $id_po and po.id_fournisseur=fournisseur.id_fournisseur");
	$lb_po = $info_po[lb_po];
	$date_po = $info_po[date_po];
	$lb_fournisseur = $info_po[lb_fournisseur];
	$id_fournisseur= $info_po[id_fournisseur];
	$adresse_mail = $info_po[adresse_mail];
	$adresse_postale = $info_po[adresse_postale];
	$cond_regle = $info_po[cond_regle];

	$pdf=new PDF("P");
	$pdf->SetFillColor(255);
	$pdf->AddPage();
	$y_start_new_page = $pdf->GetY() ;
	$pdf->Image("client/client.jpg",$pdf->GetX()+10,$pdf->GetY(),40,30);
	$y = $pdf->GetY();
	$pdf->SetXY(70, $pdf->GetY()+10);
	$pdf->SetFont('Arial','B',12);
	$pdf->MultiCell($pdf->PageWidth(),3,"COMMANDE N°".$lb_po ,'');
	$pdf->SetFont('Arial','B',10);
	$pdf->SetXY(10, $pdf->GetY()+15);
	$pdf->MultiCell($pdf->PageWidth(),4,"\nSODICLAIR" ,'');
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell($pdf->PageWidth(),4,"BP 00022 - 28800 BONNEVAL\nN° TVA : FR60 343 228 987 00016" ,'');

	$y1 = $pdf->GetY();
	$pdf->SetXY($pdf->PageWidth()*2/3+10, $pdf->GetY()-25);
	$pdf->MultiCell($pdf->PageWidth(),2,"Date : ".substr($info_po[date_po],8,2)."/".substr($info_po[date_po],5,2)."/".substr($info_po[date_po],0,4) ,'');
	$pdf->SetXY($pdf->PageWidth()*2/3+10, $pdf->GetY()+5);
	$pdf->MultiCell($pdf->PageWidth()/3, 4, $info_po[lb_fournisseur]."\n".$info_po[adresse_postale], 'LTRB','');
	$y2 = $pdf->GetY();
	$pdf->SetXY($pdf->GetX(), max($y1,$y2));
	$pdf->SetDisplayMode(real,'default');
	$pdf->Ln();
	$y =$pdf->GetY();
	$liste_ligne = charge("select qt,pu,reference,lb_article_aff from po_ligne, article,fournisseur_article where id_po = $id_po and po_ligne.id_article=article.id_article and po_ligne.id_article=fournisseur_article.id_article and fournisseur_article.id_fournisseur=$id_fournisseur");
	entete ($pdf);
	$pdf->SetFont('Arial','',8);
	$y =$pdf->GetY();
	$x = $pdf->GetX();
	$y1 = $y;
	$limit_tableau_normal = $pdf->PageHeight()-50;
	$GLOBALS[limit_tableau_normal] = $limit_tableau_normal;
	$y_bordure_tableau_normal = $y;
	$is_one_page = true;
	$bordure_one_page = "LRB";
	$hauteur_bordure_one_page = $limit_tableau_normal-$y;
	$bordure_tableau_normal="LRB";
	$total_general = 0;
	foreach ($liste_ligne as $i => $info) {
		if ($limit_tableau_normal<$pdf->GetY()) {
  			$pdf->SetXY($pdf->GetX(), $y);
  			$pdf->MultiCell($pdf->PageWidth(), 0, "", 'T','R');
			empied($pdf);
			$pdf->AddPage();
			entete($pdf);
	   }
   	$total_general += $info[qt]*$info[pu];
		$y =$pdf->GetY();
		$pdf->MultiCell($pdf->PageWidth()/4*3, 5, $info[reference]." (".$info[lb_article_aff].")", 'LR','L',true);
		$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*3/4, $y);
		$pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($info[pu],2,',',' ')." €", 'LR','R');
		$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*10/12, $y);
		$pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($info[qt],1,',',''), 'LR','R');
		$pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*11/12, $y);
		$pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($info[qt]*$info[pu],2,',',' ')." €", 'LR','R');
	 	$y =$pdf->GetY();
  	}

  $pdf->SetXY($pdf->GetX(), $y);
  $pdf->MultiCell($pdf->PageWidth(), 0, "", 'T','R');

  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*9/12, $y);
  $pdf->MultiCell($pdf->PageWidth()/6, 5, "TOTAL HT ", '','R');
  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*11/12, $y);
  $pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($total_general,2,',',' ')." €", 'LRB','R');

  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*9/12, $y+5);
  $pdf->MultiCell($pdf->PageWidth()/6, 5, "TVA (20%) ", '','R');
  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*11/12, $y+5);
  $pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($total_general*0.2,2,',',' ')." €", 'LRB','R');

  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*9/12, $y+10);
  $pdf->MultiCell($pdf->PageWidth()/6, 5, "TOTAL TTC ", '','R');
  $pdf->SetXY($pdf->GetX()+$pdf->PageWidth()*11/12, $y+10);
  $pdf->MultiCell($pdf->PageWidth()/12, 5, number_format($total_general*1.2,2,',',' ')." €", 'LRB','R');

  $y = $limit_tableau_normal;

  $pdf->SetXY($pdf->GetX(), $pdf->GetY()+3);
  $pdf->SetFont('Arial','',13);
  $pdf->MultiCell($pdf->PageWidth(), 5, "Pour toutes les longueurs, les quantités indiquées sont en mètres linéaires", 'TLRB','C');
  $pdf->SetFont('Arial','',8);
  
  $pdf->SetXY($pdf->GetX(), $y+5);
  $pdf->MultiCell($pdf->PageWidth()/2, 5, "CONDITIONS DE REGLEMENT", 'TLRB','C');
  $pdf->SetXY($pdf->GetX(), $y+10);
  $pdf->MultiCell($pdf->PageWidth()/2, 5, $cond_regle, 'LRB','L');

	$pdf->SetFont('Arial','',8);
	$pdf->SetXY($pdf->GetX(), $pdf->GetY()+5);
	$y =$pdf->GetY();
	$pdf->MultiCell($pdf->PageWidth()/2, 5, "ADRESSE DE LIVRAISON", 'LTRB','C');
	$pdf->SetXY($pdf->PageWidth()/2+12, $y);
	$pdf->MultiCell($pdf->PageWidth()/2-2, 5, "ADRESSE DE FACTURATION", 'LTRB','C');
  //$pdf->SetXY($pdf->GetX(), $y);
	$pdf->MultiCell($pdf->PageWidth()/2, 5, "SODICLAIR\nPONTAULT\n28140 NOTTONVILLE", 'LRB','C');
	$pdf->SetXY($pdf->PageWidth()/2+12, $y+5);
	$pdf->MultiCell($pdf->PageWidth()/2-2, 5, "SODICLAIR\nBP 0022\n28800 BONNEVAL", 'LRB','C');
	empied($pdf);
	if(!$send_commande_by_mail) {
  		$pdf->Output();
	} else {
		$filename = "Commande_".$id_po.".pdf";
		$pdfdoc = $pdf->Output("temp/".$filename, "F");
		$lo_tri = "SOD";	
		$lo_soc = "Sodiclair";
		$to   = $adresse_mail;
		$subject = "Commande $lo_soc numero ".substr($info_po[date_po],0,4)."-".substr($info_po[date_po],5,2)."-".$lo_tri.substr("000000".$id_po,-5);
		$message = "Veuillez trouver ci-joint la commande $lo_soc n° ".substr($info_po[date_po],0,4)."-".substr($info_po[date_po],5,2)."-".$lo_tri.substr("000000".$id_po,-5)."
		Merci de nous accuser réception de cette commande
		<BR>
		<BR>".$_SESSION[nom_utilisateur]."
		<BR>".$_SESSION[mail_utilisateur]."
		<BR>".$lo_soc;
		mail_smtp($adresse_mail, $subject, $message, "temp/".$filename, $filename);
  }
}
?>
