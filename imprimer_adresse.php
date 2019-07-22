<?php
require('ress/fpdf/fpdf.php');
require_once("ress/db_mysql.php");

define('SR',"Stores & Rideaux");
define('PS',"Prosolair");
define('FDV',"Force de vente");
define('IN',"Internet");
function getOrigine($SR,$PS,$FDV) {
   if ($SR==1) return SR;
   else if ($PS==1) return PS;
   else return FDV;
}
if($_POST['origine_impression']){
   $requete.=" origine = ".$_POST['origine_impression']." AND ";		
}
if($_POST['NonEditee'])	$requete.=" date_edition is null AND ";
if($_POST['Date_import']){
   $Date_import = $_POST['Date_import'];
   $requete.="STR_TO_DATE(date_import, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_import', '%Y-%m-%d' ) AND ";
}
if($_POST['Date_import_f']){
   $Date_import = $_POST['Date_import_f'];
   $requete.="STR_TO_DATE(date_import, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_import', '%Y-%m-%d' ) AND ";
}
if($_POST['Date_edition']){
   $Date_edition = $_POST['Date_edition'];
   $requete.="STR_TO_DATE(date_edition, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_edition', '%Y-%m-%d' ) AND ";
}
if($_POST['Date_edition_f']){
   $Date_edition = $_POST['Date_edition_f'];
   $requete.="STR_TO_DATE(date_edition, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_edition', '%Y-%m-%d' ) AND ";
}
//print_r($_POST);
//die($requete);
imprimer($requete);
/*
-------------------------------------------------
  Imprime un doc	
-------------------------------------------------*/
function imprimer($condition) {
	$interline=5;
	class PDF extends FPDF {
		public $x1;//abcisse courante
		public $y1;//ordonnée courante
		function PageWidth(){
			return (int) $this->w-$this->rMargin-$this->lMargin;
		}
      
	function RecalculatePosition($width,$col_height){
         $y2 = $this->GetY();
         if($this->y1>$y2)
            $yH=$col_height;
         else
            $yH = $y2 - $this->y1;
         $this->SetXY($this->x1 + $width, $this->GetY() - $yH);
         $this->y1 = $this->GetY();
         $this->x1 = $this->GetX();
	}
      
	function Header() {
         $this->SetFont('Arial','',10);
         //Calcul de la largeur du titre et positionnement
         $w=$this->GetStringWidth($this->title)+6;
         $this->SetX(($this->PageWidth()-$w)/2);
         //Couleurs du cadre, du fond et du texte
         //$this->SetDrawColor(0,80,180);
         $this->SetFillColor(255,255,255);
         $this->SetTextColor(0,0,0);
         //Epaisseur du cadre (1 mm)
         //$this->SetLineWidth(1);
         //Titre centré
         $this->Cell($w,9,$this->title,0,1,'C',true);
         //Saut de ligne
         $this->Ln(5);
      }
   }
   $req= "select * from adresse_echantillon WHERE $condition id_adresse_echantillon>0 order by date_import";
   //die($req);
   $requete= new db_sql();
   $requete->q("SET NAMES 'utf8'");
   $requete->q($req);
   $col = 0; 
   $lig = 0;
   $orientation ="P";
   $pdf=new PDF($orientation);
   $pdf->SetAutoPageBreak(false);
   $pdf->AddPage();
   $pdf->SetDisplayMode(real,'default');
   //$pdf->SetFont('Arial','',7);
   $pdf->SetMargins(15,0,0);
   //$pdf->SetX(15);
   $pdf->SetY(15);
   $nbinterline=0;
   $pays[BE] = "Belgique";
   $pays[CH] = "Suisse";
   $pays[DE] = "Allemagne";
   $pays[ES] = "Espagne";
   $pays[FR2] = " Transitaire - France";
   $pays[GB] = "Royaume Uni";
   $pays[IT] = "Italie";
   $pays[LU] = "Luxembourg";
   $pays[NL] = "Pays Bas";

   
   while ($requete->n()) {
	  	if($col == 1)
			$pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()-$nbinterline*$interline);
			
		$pdf->Cell(1,1,utf8_decode($requete->f('shippingcontactfirstname'))." ".utf8_decode($requete->f('shippingcontactlastname')),'');
		if($col==0){
			$nbinterline=0;
			if($requete->f('entreprise')){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,utf8_decode($requete->f('entreprise')),'');
			}
			if($requete->f('shippingaddress1')){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress1')),'');
			}
			if($requete->f('shippingaddress2') and ($requete->f('shippingaddress2') != $requete->f('shippingaddress1'))){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress2')),'');
			}
			if($requete->f('shippingaddress3') and ($requete->f('shippingaddress3') != $requete->f('shippingaddress2'))){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress3')),'');
			}   
			if($requete->f('shippingaddress4') and ($requete->f('shippingaddress4') != $requete->f('shippingaddress3'))){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress4')),'');
			}   
			$pdf->SetY($pdf->GetY()+$interline);
			$nbinterline++;
			$pdf->Cell(1,1,utf8_decode($requete->f('shippingpostalcode'))." ".utf8_decode($requete->f('shippingcity')),'');
			if($requete->f('shippingcountrycode') and $requete->f('shippingcountrycode') != "FR"){
			   $pdf->SetY($pdf->GetY()+$interline);
			   $nbinterline++;
			   $pdf->Cell(1,1,$pays[utf8_decode($requete->f('shippingcountrycode'))],'');
			}			
		} else {
			if($requete->f('entreprise')){
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,utf8_decode($requete->f('entreprise')),'');
			}			
			if($requete->f('shippingaddress1')) {
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress1')),'');
			}
			if($requete->f('shippingaddress2') and ($requete->f('shippingaddress2') != $requete->f('shippingaddress1'))){
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress2')),'');
			}			
			if($requete->f('shippingaddress3') and ($requete->f('shippingaddress3') != $requete->f('shippingaddress2'))){
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress3')),'');
			}
			if($requete->f('shippingaddress4') and ($requete->f('shippingaddress4') != $requete->f('shippingaddress3'))){
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,utf8_decode($requete->f('shippingaddress4')),'');
			}
			$pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			$pdf->Cell(1,1,utf8_decode($requete->f('shippingpostalcode'))." ".utf8_decode($requete->f('shippingcity')),'');
			if($requete->f('shippingcountrycode') and $requete->f('shippingcountrycode') != "FR"){
			   $pdf->SetXY(($pdf->GetPageWidth()/2)+10,$pdf->GetY()+$interline);
			   $pdf->Cell(1,1,$pays[utf8_decode($requete->f('shippingcountrycode'))],'');
			}			
		}
		$col++;
		if($col==2){
			$col = 0;
			$lig++;
		}
		if($lig==8){
			$pdf->AddPage();
			$lig=0;
			$col = 0;
			$pdf->SetY(15);
		} else if($lig>0 && $col==0) {
			$pdf->SetXY(15,$lig*($pdf->GetPageHeight()/8)+10);
		}
   }
   
   $req="
                     UPDATE
                        adresse_echantillon
                     SET
                        date_edition=NOW() WHERE $condition id_adresse_echantillon>0";
    new db_sql($req);
   
   $pdf->Output();
}


?>