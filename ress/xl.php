<?
/* module générique de sortie excel d'un tableau */

/* paramètre : $tableau */

if (!$tableau) $tableau=$_GET[tableau];

if (!is_array($_SESSION)) {
	include ("var_session.php");
}

set_time_limit(0);

function to_lettre($i) {
	$j=floor($i/26);
	if ($j) {
	   $res = chr(ord('A')+$j-1);
		$i = $i-(26*$j);
	}
	$res .= chr(ord('A')+$i);
	return $res;
}

if (!count($_SESSION[$tableau])) {
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   </head>
   <body>
      Pas de donnees a afficher.
   </body>
</html>
<?
   exit();
}

	$file = 'temp/temp.xlsx';
	include 'PHPExcel.php';
	include 'PHPExcel/Writer/Excel2007.php';
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$j = 1;
$i = 0;

foreach ($_SESSION[$tableau][0] as $titre => $valeur) {
	$ii[$titre] = $i;
	$objPHPExcel->getActiveSheet()->SetCellValue(to_lettre($i++).$j, utf8_encode($titre));
}
foreach ($_SESSION[$tableau] as $ligne) {
	$j++;
	$i = 0;
	foreach ($ligne as $titre => $valeur) {
		if (substr($valeur,0,1) == "=") {
			$valeur = ' '.$valeur;
		}

		$objPHPExcel->getActiveSheet()->getColumnDimension(to_lettre($ii[$titre]))->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->SetCellValue(to_lettre($ii[$titre]).$j, utf8_encode($valeur));
	}
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
if (file_exists($file)) unlink ($file);
$objWriter->save($file);
header("Location: $file");
?>