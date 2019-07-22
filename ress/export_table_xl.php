<?

session_start();

$file = "temp/extraction_table_$table.xlsx";
include 'ress/PHPExcel.php';
include 'ress/PHPExcel/Writer/Excel2007.php';

function to_lettre($i) {
    $j = floor($i / 26);
    if ($j) {
        $res = chr(ord('A') + $j - 1);
        $i = $i - (26 * $j);
    }
    $res .= chr(ord('A') + $i);
    return $res;
}

function format_cell($valeur, $csv = false) {
    if (substr($valeur, 0, 1) == "<") { // Suppression de l'�ventuel lien <A>
        $valeur = substr($valeur, strpos($valeur, ">") + 1);
        $valeur = substr($valeur, 0, strpos($valeur, "<"));
    }
    $valeur_numeric = str_replace(",", ".", $valeur);
    if (is_numeric($valeur_numeric)) {
        $valeur = $valeur_numeric * 1;
        if ($csv) {
            $valeur = str_replace(".", ",", $valeur);
        }
    }
    if ($csv) {
        $valeur = str_replace(array(";", "\""), array(":", "'"), $valeur);
    }
    return $valeur;
}

function cell($valeur, $color, $i, $j, $is_entete = false, $currency = false, $adresse = false) {
    global $objPHPExcel;
    $objPHPExcel->getActiveSheet()->SetCellValue(to_lettre($i) . $j, utf8_encode(format_cell($valeur)));
    $objPHPExcel->getActiveSheet()->getColumnDimension(to_lettre($i))->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getRowDimension($j)->setRowHeight(-1);

    if($is_entete) {
        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
        );
    }

    $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $color)
            )
        )
    );

    if (is_numeric($valeur)) {
        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
    }
    if($currency) {
        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->getNumberFormat()->setFormatCode(html_entity_decode("€ 0,0.00", ENT_QUOTES, 'UTF-8'));
    }
    if($adresse) {
        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->getAlignment()->setWrapText(true);
    }
}

// ____________________________________________________________________

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$j = 1;
$i = 0;
//list($rien, $entete) = each($tableau);
foreach ($_SESSION['export_table']['entete'] as $titre => $valeur) {
    $is_entete = true;
    cell($valeur, 'AAAAAA', $i, $j, $is_entete);
    $i++;
}


foreach ($_SESSION['extparm'] as $ligne) {
    $j++;
    $i = 0;
    foreach ($ligne as $titre => $valeur) {
        $is_entete = false;
        if ($j % 2) $color = 'ff9f54'; else $color = 'FFFFFF';
        cell($valeur, $color, $i, $j);
        $i++;
        $currency = false;
        $adresse = false;
    }
}


$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
if (file_exists($file)) unlink ($file);
$objWriter->save($file);
header("Location:$file");

?>
