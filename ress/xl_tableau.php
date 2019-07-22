<?
//Générique de sortie d'un tableau PHP
ini_set('memory_limit',-1);
set_time_limit (-1);

include_once 'util.php';

if (is_array($tableau_include)) { // Appel direct par include
    $tableau = $tableau_include;
} else {
    session_start();
    $tableau = $_SESSION["xl_tableau_".$_GET[num]];
}

function format_cell($valeur, $csv = false) {
    if(substr($valeur,0,1)=="<") { // Suppression de l'éventuel lien <A>
        $valeur = substr($valeur, strpos($valeur,">")+1);
        $valeur = substr($valeur,0, strpos($valeur,"<"));
    }
    $valeur_numeric = str_replace(",",".",$valeur);
    if (is_numeric($valeur_numeric)) {
        $valeur = $valeur_numeric*1;
        if ($csv) {
            $valeur = str_replace(".",",",$valeur);
        }
    }
    if ($csv) {
        $valeur = str_replace(array(";","\""),array(":","'"),$valeur);
    }
    return $valeur;
}

// Mode CSV

if ($_GET[csv] or $csv) {

	$filename = "extraction_excel_" . date('Ymd') . ".csv";
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-type: application/vnd.ms-excel");

	list($rien,$entete) = each($tableau);
	$i=0;
	foreach ($entete as $titre => $valeur) {
		if (substr($titre,0,6) == "hidden") {
			$hidden[$i] = 1;
		} else {
			if (strpos($titre,"_")) {
				$titre = substr($titre,strpos($titre,"_")+1);
			}
			echo format_cell($titre,1).";";
		}
		$i++;
	}
	echo "\n";
	foreach ($tableau as $ligne) {
		
		$i=0;
		foreach ($ligne as $titre => $valeur) {
			if (!$hidden[$i]) {
				echo str_replace("\r","|",format_cell($valeur,1).";");
			}
			$i++;
		}
		echo "\n";
	}
	exit();

// Mode XL HTML (tableau html interprété par XL)	
	
} else if ($_GET[html] or $html) {

	$filename = "extraction_excel_" . date('Ymd') . ".xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-type: application/vnd.ms-excel");
	
	echo "<table>";

	list($rien,$entete) = each($tableau);
	echo "<tr>";
	$i = 0;
	foreach ($entete as $titre => $valeur) {
		$i++;

		if (substr($titre,0,6) == "hidden") {
			$hidden[$i] = 1;
		} else {
			if (strpos($titre,"_")) {
				$titre = substr($titre,strpos($titre,"_")+1);
			}			
			echo "<td>";
			echo str_replace("\r","<br>",format_cell($titre,1));
			echo "</td>";
		}
	}
	echo "</tr>";
	foreach ($tableau as $ligne) {
		$i=0;
		echo "<tr>";
		foreach ($ligne as $titre => $valeur) {
			$i++;
			if (!$hidden[$i]) {
				echo "<td>";
				echo format_cell($valeur,1);
				echo "</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table>";

// Mode XL natif	
	
} else {

	$file = "../temp/extraction_excel.xls";
	include_once 'PHPExcel.php';
	include_once 'PHPExcel/Writer/Excel2007.php';

	function to_lettre($i) {
		$j=floor($i/26);
		if ($j) {
			$res = chr(ord('A')+$j-1);
			$i = $i-(26*$j);
		}
		$res .= chr(ord('A')+$i);
		return $res;
	}


    function cell($valeur,$color,$i,$j) {
        global $objPHPExcel;
        $objPHPExcel->getActiveSheet()->SetCellValue(to_lettre($i).$j, format_cell($valeur));
        $objPHPExcel->getActiveSheet()->getColumnDimension(to_lettre($i))->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i) . $j)->getAlignment()->applyFromArray(
            array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,)
        );

        $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i).$j)->applyFromArray(
            array(
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => $color)
                )
            )
        );
        if (is_numeric($valeur)) {
            $objPHPExcel->getActiveSheet()->getStyle(to_lettre($i).$j)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        }
    }

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->setActiveSheetIndex(0);
	$j = 1;
	$i = 0;

    list($rien,$entete) = each($tableau);
    foreach ($entete as $titre => $valeur) {
        if(substr($titre,0,7) != 'hidden_' ) {
			if (strpos($titre,"_")) {
				$titre = substr($titre,strpos($titre,"_")+1);
			}
            cell(ucfirst(utf8_encode($titre)),'AAAAAA',$i,$j);
            $i++;
        }
    }

    foreach ($tableau as $ligne) {
        $j++;
        $i=0;
        foreach ($ligne as $titre => $valeur) {
            if($j % 2) $color = 'EEEEEE'; else $color = 'FFFFFF';

            if($titre == 'Date Création') {
                $valeur = date("d/m/y", strtotime($valeur));
            }

            if(substr($titre,0,7) != 'hidden_' ) {
                cell(utf8_encode($valeur), $color, $i, $j);
                $i++;
            }


        }
    }
    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    if (file_exists($file)) {
        unlink ($file);
    }
    $objWriter->save($file);
	
	// Envoi du mail
	/*$destinataire = req_sim ("select lb_mail from dj_utilisateurs where id_utilisateur = ".$_SESSION[session_id_utilisateur])[lb_mail];
	if ($destinataire) {
		include_once './../ress/xl_tableau_via_email.php';
	}*/	
	
    if (!$NO_GO) {
        header("Location:$file");
    }

}
