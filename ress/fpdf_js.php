<?php
class PDF extends FPDF {

    var $javascript;
    var $n_js;
    public $x1;//abcisse courante
    public $y1;//ordonnÚe courante
    public $origine_logo;

    function IncludeJS($script) {
        $this->javascript=$script;
    }

    function _putjavascript() {
        $this->_newobj();
        $this->n_js=$this->n;
        $this->_out('<<');
        $this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R ]');
        $this->_out('>>');
        $this->_out('endobj');
        $this->_newobj();
        $this->_out('<<');
        $this->_out('/S /JavaScript');
        $this->_out('/JS '.$this->_textstring($this->javascript));
        $this->_out('>>');
        $this->_out('endobj');
    }

    function _putresources() {
        parent::_putresources();
        if (!empty($this->javascript)) {
            $this->_putjavascript();
        }
    }

    function _putcatalog() {
        parent::_putcatalog();
        if (isset($this->javascript)) {
            $this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
        }
    }

    //ordonnÚe courante
    function PageWidth() {
        return (int) $this->w-$this->rMargin-$this->lMargin;
    }

    function Header() {
        $largeur_colonne = $this->PageWidth() / 6;
		if($this->getOrigineLogo() > 0) {
			$this->Image("images/logo_origine_" . $this->getOrigineLogo() . ".jpg", $this->GetX(), $this->GetY(), $largeur_colonne);
		}
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
		  //Calcul de la largeur du titre et positionnement
        $w=$this->GetStringWidth($this->title)+6;		  
        $this->SetX(($this->PageWidth()-$w)/2);	
        //Couleurs du cadre, du fond et du texte
        //$this->SetDrawColor(0,80,180);
        $this->SetFillColor(255,255,255);
        $this->SetTextColor(0,0,0);
        //Epaisseur du cadre (1 mm)
        //$this->SetLineWidth(1);
        //Titre centrÚ
        $this->Cell($w,9,$this->title,0,1,'C',true);
        //Saut de ligne
    }

    function Footer() {
        // Positionnement Ó 1,5 cm du bas
        $this->SetY(-8);
        // font
        $this->SetFont('Arial','I',10);
        // Adresse
        $this->Cell(0,5,'Page '.$this->PageNo().'/{nb}',0,0,'C');
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

    function AutoPrint($dialog=false) {
        //Embed some JavaScript to show the print dialog or start printing immediately
        $param=($dialog ? 'true' : 'false');
        $script="print($param);";
        $this->IncludeJS($script);
    }


    public function getOrigineLogo(){
        return $this->origine_logo;
    }

    public function setOrigineLogo($origine_logo){
        $this->origine_logo = $origine_logo;
    }

    public function set_underline($underline){
        $this->underline = $underline;
    }

    public function get_underline(){
        return $this->underline;
    }

}

function get_number_of_lines($txt,$x,$column_width,$pdf) {
    $pdf_bis = clone $pdf;
    $y=$pdf_bis->GetY();
    $pdf_bis->MultiCell($column_width, 1, $txt, "");
    return $pdf_bis->GetY()-$y;
}
?>