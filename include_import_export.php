<?

function transcote($x) {
    if ($x=="") return "null";
    return "'".trim(str_replace ("'","''",$x))."'";
}

if (droit_utilisateur("admin")) {
    ?>
    <body class="application">
    <table class="menu_haut_resultat">
        <tr>
            <td class="menu_haut">
                <? echo $titre ?>
            </td>
        </tr>
    </table>
    <form method="post" action="?ACTE=1&TABLE=<? echo $table ?>" ENCTYPE="multipart/form-data">
        <table class="menu_haut_resultat">
            <tr>
                <td class="menu_haut">
                    Fichier à  importer (xlsx) :
                    <input type="file" name="userfile" enctype="multipart/form-data">
                    <input type="submit" value="Importer">
                    <a href="admin_export.php?TABLE=<? echo $table ?>" target="xl">Exporter </a>
                </td>
            </tr>
        </table>
    </form>
    <pre>
		</pre>
    <?
    if($ACTE==1){
		$insered = 0;
        foreach($champs as $champ=>$proprietes) {
            if ($proprietes[req]) {
                $req = new db_sql($proprietes[req]);
                while($req->n()) {
                    $champs[$champ]['valeurs'][$req->f('lb')]	= $req->f('id');
                }
            }
        }
        include 'ress/PHPExcel/IOFactory.php';
        //Variable du fichier upload
        $tempfile_name=$_FILES['userfile']['tmp_name'];
        if(empty($tempfile_name)){
            $tempfile_name="C:\\Users\\tlcn6661\\Desktop\\data.xlsx";
        }
        if(!$message){
            if($objPHPExcel = PHPExcel_IOFactory::load($tempfile_name)) {
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                $titre = $sheetData[1];
                foreach ($titre as $n=>$libelle) {
                    $champ = trim(utf8_decode($libelle));
                    if (!is_array($champs[$champ])) {
                        die("nom de champ $libelle non attendu");
                    }
                    $champs[$champ][present] = true;
                    $ordre[$n] = $champ;
                    //$numero[$titre] = $n;
                }
                foreach($champs as $champ=>$proprietes) {
                    if ($proprietes[clee] and !($proprietes[present])) {
                        die("le champs $champ de la clÃ©e n'est pas prÃ©sent dans le fichier");
                    }
                }
                foreach ($sheetData as $i=>$ligne) {
                    if ($i != 1) {
                        $valeurs = array();
                        foreach ($ordre as $n=>$champ) {
                            $valeur = trim(utf8_decode($ligne[$n]));
                            if ($champs[$champ][req]) {
                                if (!isset($champs[$champ][valeurs][$valeur])) {
                                    $erreur = "valeur $valeur pour $champ non trouvÃ©e";
                                } else {
                                    $valeur = $champs[$champ][valeurs][$valeur];
                                }
                            }
                            if ($champs[$champ][format] == 'decimal') {
                                $valeur = str_replace(",",".",$valeur);
                                $valeur = $valeur*1;
                            }
                            $valeurs[$champ] = $valeur;
                        }

                        $req = "INSERT INTO $table (";
                        foreach ($valeurs as $champ=>$valeur) {
                            $req .= "$champ,";
                        }
                        $req = substr($req,0,-1);
                        $req .= ") VALUES (";
                        foreach ($valeurs as $champ=>$valeur) {
                            $req .= "'".$valeur."',";
                        }
                        $req = substr($req,0,-1);
                        $req .= ") on duplicate key update ";
                        foreach ($valeurs as $champ => $valeur) {
                            if (!$champs[$champ][clee]) {
                                $req .= " $champ = '".$valeur."',";
                            }
                        }
                        $req = substr($req,0,-1);
                        if ($erreur) {
                            echo $erreur."<BR>";
                        } else {
                            new db_sql($req);
                            $insered = 1;
                        }
                        $Nb++;
                    }
                }
				if($insered) {
                ?>
					<table class="requeteur">
						<tr>
							<td class='requeteur' height='40' valign='middle'>Fichier traité : <? echo $Nb ?> lignes traitées</td>
						</tr>
					</table>
                <?
				}
            } else {
                $message="Operation impossible, le fichier semble absent";
            }
        }
    }
}

?>