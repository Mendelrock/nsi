<?php
include "ress/entete.php";
$erreur = [];
unset($_SESSION['clause_orderby']);
if( !$_SESSION[id_droit]["admin"]){
    exit ("Vous n'avez pas le droit à  cet écran");
}
if($_GET['orderby']) {
	foreach($champs as $id => $champ) {
		unset($champs[$id]['order_by']);
	}
	$champs[$_GET['orderby']]['order_by'] = 1;
}
function insertTable($champs, $table, $array, $acte, &$erreur) {
    $primaire = '';
    foreach($array as $chps => $vals) {
		if(isset($champs[$chps]['nullval']) && trim($vals) == '') {
			$array[$chps] = $champs[$chps]['nullval'];
		}
	
		if($champs[$chps]['format'] != 'number' ) {
			$primaire_val = My_sql_format($vals);
		} else {
			$primaire_val = $vals;
		}
		if ($champs[$chps]['clee'] == 1) {
			$primaire_aff .= $chps.' = '. $primaire_val.' and ';
		}
    }
	$primaire_aff = substr($primaire_aff, 0, -4);
	$check = req_sim('SELECT 1 as id FROM '.$table.' WHERE '.$primaire_aff.'', 'id');
	/**
	 * INSERTION
	 */
	$req = '';
	if(!$check and ($acte == 'ADD' or $acte == 'ADDORUPDATE')) {
		$req = "INSERT INTO $table (";
		foreach ($array as $champ => $valeur) {
			$req .= "`$champ`, ";
		}
		$req = substr($req, 0, -2);
		$req .= ") VALUES (";
		foreach ($array as $champ => $valeur) {
			if ($champs[$champ]['format'] == 'number') {
				if(!$valeur) {
					$valeur = "null";
				}
				$req .= "" . $valeur . ",";
			} else {
				$id = utf8_decode($valeur);
				if($champs[$champ]['format'] == 'liste' && $GLOBALS['ACTE_IMPORT'] == 'IMPORT_FORMATE') {
					$id = $champs[$champ]['valeurs'][$id];
					if(!$id) {
						$erreur[] = "La valeur saisie dans le champ $champ n'existe pas dans la base $champ, le système n'a pu donc récupérer la donnée";
					}
				}
				$req .= My_sql_format($id) . ",";
			}
		}
		$req = substr($req, 0, -1);
		$req .= ")";
	} elseif ($check and ($acte == 'UPDATE' or $acte == 'ADDORUPDATE')) {
		/**
		 * UPDATE
		 */
		$req = "UPDATE $table SET ";
		$nb_champs_a_maj = 0;
		foreach ($array as $champ => $valeur) {
			if (is_numeric($valeur)) {
				$valeur = "" . $valeur . "";
			} else {
				$valeur = My_sql_format($valeur);
			}
			if($champs[$champ]['clee'] != 1) {
				$req .= "`$champ` = $valeur, ";
				$nb_champs_a_maj++;
			}
		}
		$req = substr($req, 0, -2);
		
		$req .= ' WHERE '.$primaire_aff.'';
		if (!$nb_champs_a_maj) {
			$req = "";
		}
	} else {
		$erreur[] = 'Clée primaire déjà existante ou mise à jour sans clée primaire';
	}
	if(!$erreur and $req) {
		new db_sql($req);
	}
}
$Nb =  0;
$table_nouv = [];
/**
 * Détermine si mode Import Brut / Formaté
 */
function verifChampExist($mode, $champs, &$champ) {
	$champ_exist = false;
	foreach($champs as $id_champ => $proprietes) {
		if($mode == 'IMPORT_BRUT') {
			if($id_champ == $champ) {
				$champ_exist = true;
			}
		}
		if($mode == 'IMPORT_FORMATE') {
			if($proprietes["nom"] == $champ) {
				$champ_exist = true;
			}
		}
	}
	// Si n'existe pas, je renvoie une erreur
	if(!$champ_exist) {
		$erreur = "Vous ne pouvez pas utiliser \"Import Formaté\" avec des données \"Exportées en Brut\" - ni \"Import Brut\" avec des données \"Exportées en Formaté\"";
	}
	return $erreur;
}
// AU SUBMIT DU FORMULAIRE
if($ACTE_IMPORT == 'IMPORT_BRUT' || $ACTE_IMPORT == 'IMPORT_FORMATE'){
    foreach($champs as $champ=>$proprietes) {
        if ($proprietes[liste_valeurs]) {
            $req = new db_sql($proprietes[liste_valeurs]);
            while($req->n()) {
                $champs[$champ]['valeurs'][$req->f('lb')] = $req->f('id');
            }
        }
    }
    include 'ress/PHPExcel/IOFactory.php';
    //Variable du fichier upload
    $tempfile_name=$_FILES['userfile']['tmp_name'];
    if(empty($tempfile_name)){
        $erreur[] = 'Aucun fichier uploadé';
    }
    if(!$erreur){
        if($objPHPExcel = PHPExcel_IOFactory::load($tempfile_name)) {
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            $titres = $sheetData[1];
            foreach ($titres as $n=>$libelle) {
                $champ = trim(utf8_decode($libelle));
				$msg = verifChampExist($ACTE_IMPORT, $champs, $champ);
				if($msg && !$erreur) 
					$erreur[] = $msg;
				
				foreach($champs as $id_champ => $props) {
					if($props['nom'] == $champ) {
						$champ = $id_champ;
					}
				}
				
                if (!is_array($champs[$champ])) {
                    $erreur[] = "nom de champ $libelle non attendu";
                }
                $champs[$champ]['present'] = true;
                $ordre[$n] = $champ;
                //$numero[$titre] = $n;
            }
            if(!$erreur) {
                foreach ($champs as $champ => $proprietes) {
                    if ($proprietes['obligatoire'] and !($proprietes['present'])) {
                        $erreur[] = "le champs $champ de la clée n'est pas présent dans le fichier";
                    }
                }
            }
            if(!$erreur) {
                foreach ($sheetData as $i => $ligne) {
                    if ($i != 1) {
                        $valeurs = array();
                        foreach ($ordre as $n => $champ) {
                            $valeur = trim($ligne[$n]);
                            if ($champs[$champ]['format'] == 'number') {
                                $valeur = num_encode($valeur)*1;
                            }
                            $valeurs[$champ] = $valeur;
                        }
                        if(!$erreur) {
                            insertTable($champs, $table, $valeurs, 'ADDORUPDATE', $erreur);
							$Nb++;
                        }
                    }
                }
            }
            $warning[] = "Fichier traité : $Nb lignes ajoutées</td>";
        } else {
            $erreur[] = "Operation impossible, le fichier semble absent";
        }
    }
}
if ($ACTE == 'ADD' or $ACTE == 'UPDATE') {
	
	// Unset tous les valeurs du $_POST qui ne sont pas des champs de la table pour permettre de faire l'INSERT
    unset($_POST['ACTE']);
	foreach($_POST as $chps => $vals) {
		if ($champs[$chps]['clee'] == 1 or $champs[$chps]['obligatoire'] == 1) {
			if($vals == ''){
				$erreur[] = 'Le champ '.$champs[$chps]['nom'].' ne peut pas être vide';
			}
		}
	}
	
    //******************************
    //        SI CREATION OU UPDATE
    //******************************
	if(!$erreur) {
		foreach($_POST as $champ => $val) {
			if(substr($champ, 0, 2) != 'h_') {
				$insert_table[$champ] = $val;
			}
		}
		insertTable($champs, $table, $insert_table, $ACTE, $erreur);
	} else {
		foreach($_POST as $chps => $vals) {
			$table_nouv[$chps] = $vals;
		}
	}
	
} else if ($ACTE == "DEL") {
    //******************************
    // SI SUPPRESION DE DELEGATION
    //******************************
    $colums = explode('|', urldecode($_GET['DELETE']));
    $valls = explode('|', urldecode($_GET['CHAMP']));
    $i= 0;
    foreach ($colums as $colum) {
        $to_del .= $colums[$i]." = ".My_sql_format($valls[$i])." and ";
        $i++;
    }
    $to_del = substr($to_del, 0, -4);
    if ($DELETE &&( is_numeric($_GET['CHAMP']) || strlen($_GET['CHAMP'])) ) {
        $req = new db_sql(" delete from $table where $to_del");
    } else {
        $erreur[] = "Opération de suppréssion impossible";
    }
}
//***************************************
// DELEGATIONS DE L'UTILISATEUR CONNECTE
//***************************************
$clause = '';
$affichage_table = '';
$nb_champs = count($champs)+1;
for ( $i = 1 ; $i < $nb_champs ; $i++){
	foreach( $champs as $lib => $champ ){
		if( $champ['order_by'] and $i == $champ['order_by'] ){
			$liste_orderby .= $lib.", ";						
		}
	}
}
$boucle =1;
foreach($champs as $lib => $champ) {
    $entete[] = $lib;
    foreach($champ as $libelle => $valeur) {
        if($libelle == 'order_by' and $boucle) { 
            $clause .= ' ORDER BY '.substr($liste_orderby,0, -2).' '; 
			$boucle =0;
        }
		
    }
	$affichage_table .= "`".$lib."`, ";
	$affichage_table .= "`".$lib."`, ";
}
$_SESSION['clause_orderby'] = $clause;
$table_champs = [];
$ligne = [];
$req_droits = new db_sql("
	    select 
	        ".substr($affichage_table,0, -2)."
	    from 
	        $table
	    ".$clause."
       ");
$cpteur = 0;
while($req_droits->n()) {
	foreach($champs as $lib => $champ) {
		$table_champs[$cpteur][$lib] = $req_droits->f($lib);
	}
	$cpteur++;
}
	if(is_array($erreur))
		foreach($erreur as $erreur) {
			echo "<h4 style=\"background-color:#e88; padding: 10px; \" > Erreur : " . $erreur.'</h4><br>';
		}
// prépa extraction XL
$num = time();
echo "<form id=\"update\" action='table_param_".$table.".php' method='POST' style='display:none'></form>";
	?>
	
	<table class="menu_haut" style="font-family: verdana">
        <tr>
		<td class="menu_haut"><? echo $titre; ?></td>
			<form name="param_droit_form_xl" method = "POST">
				<td class="externe"><a class="fond_gris import_table externe" id="IMPORT_BRUT">Import Brut</a></td>
				
				<td class="externe"><a class="fond_gris import_table externe" id="IMPORT_FORMATE">Import Formaté</a></td>
				
				<td class="externe"><a class="fond_gris externe" href="ress_export_table.php?table=<?php echo $table ?>" target="xl">Export brut</a></td>
				
				
				<td class="externe"><a href="ress/xl_tableau.php?num=<?php echo $num ?>" target="xl" class="fond_gris externe">Export formaté</a></td>
				
				<? champ_cache ("ACTE","") ?>
			</form>
            
		</tr>
    </table>
    <div class="col-sm-4">
            <table id="table_post" class="resultat"style="font-family: verdana">
                <?php
                echo '
                    <thead>
                        <tr>';
                foreach($champs as $libelle => $valeur) {
                    foreach($valeur as $lib => $val) {
                        if($lib == 'nom') {
                            echo "
                                <td style=\"font-size:10px;\"  data-champ='".$libelle."' data-order='0' class='orders resultat_tittle text-center'>" . $val . "</th>
                            ";
                        }
                    }
                }
                echo " <td  style=\"font-size:10px;\" colspan=2 class=\"resultat_tittle text-center\">Action</th>
					</tr>
				</thead>
				<tbody>";
				$tableau_xl= [];
                foreach($table_champs as $champ ) {
                    $spool = "<tr bgcolor='WhiteSmoke' *** class=\"hover_ligne\">";
					$champ_del = "";
					$primaire = "";
					$ligne_xl = [];
					
                    foreach($champ as $libelle => $valeur) {
						
						if ($champs[$libelle]['clee'] == 1) {
                            $champ_del .= "|".$libelle."";
                            $primaire .= "|".$valeur."";
                        }
						
                        if($champs[$libelle]['format'] == 'number') {
							$add = ' class="resultat_list text-center" ';
                        } else {
							$add = ' class="resultat_list" ';
                        }
						
						if($champs[$libelle]['format'] == 'liste'){
							if (!$liste_dds[$libelle]) {
								$req_list = new db_sql($champs[$libelle]['liste_valeurs']);
								while($req_list->n()) {
									$liste_dds[$libelle][$req_list->f('id')] = $req_list->f('lb');
								}
							}
							$valeur = $liste_dds[$libelle][$valeur];
						}
							
						$spool .= "<td $add nowrap>";
						$spool .= $valeur;
						$spool .= "</td>";
						$ligne_xl[$champs[$libelle]['nom']] = $valeur;
                    }
					$spool .= 	'<td class="resultat_list" colspan = 2></td></tr>';
					echo str_replace("***"," DELETE='".substr($champ_del, 1)."' CHAMP='".urlencode(substr($primaire, 1))."'",$spool);				
					$tableau_xl[] = $ligne_xl;
                }
				
                ?>
                <tr>
					<form name="param_droit_form" method = "POST">
				<?php
					$liste_dd = [];
                    foreach($champs as $libelle => $valeur) {
						if(empty($table_nouv)){
							$table_nouv[$libelle] = '';
						}
                        if($valeur['format'] == 'number') {
                            $add = ' class="text-center" ';
                            $isNumeric = " class='numeric' ";
                        } else {
                            $add = '';
                            $isNumeric = '';
                        }
                        ($valeur['obligatoire'] == 1)?$mandatory = "O":$mandatory="";
						$mandatory="";
                        echo "<td $add nowrap=''>";
						if($valeur['format'] == 'liste'){
							echo drop_down_droit('O',$champs[$libelle]['liste_valeurs'], $libelle, "id", "lb", $table_nouv[$libelle], "","O", "", " ");
						} elseif($valeur['format'] == 'texte_area'){
							echo text_area('O', $libelle, $table_nouv[$libelle], 70, 2, 500);
						} else {
							echo champ_ouvert($libelle, $table_nouv[$libelle] ,200, 30, $mandatory);							
						}
                        // champ_ouvert('O', $libelle, '',20, 10, $mandatory, "style='border: 1px solid grey;' ".$isNumeric);
                        echo "</td>";
                    }
                    ?>
                    <td class="text-center">
                        <img class='buttons' id="add_droit" src='images/add.png'>
                    </td>
					<td>
					<?
					echo champ_cache ("ACTE","ADD");
					?>
					</td>
					</form>
                </tr>
				</form>
			</tbody>
            </table>
			
			<?php 
				$_SESSION["xl_tableau_" . $num] = $tableau_xl;
			?>
		</div>
		<div class="modal fade imp-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div style="margin:15px" class="container-fluid">
						<form name="upload_params" action="table_param_<?php echo $table; ?>.php" method="post" ENCTYPE="multipart/form-data" >
							<table class="menu_haut_resultat" style="font-family: verdana">
								<tr>
									<td class="menu_haut">
										Fichier à importer (xlsx) :
										<input type="file" name="userfile" enctype="multipart/form-data">
										<? champ_cache ("ACTE_IMPORT","") ?>
										<input id="upload_send" type="submit" value="Importer">
									</td>
								</tr>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	
    </div>
	</body>
    <script>
		$('.orders').click(function(e) {
			var champ = $(this).data('champ');
			
			document.location="table_param_<?php echo $table;?>.php?orderby="+champ;
		});
		
		$(".hover_ligne").click(function(e) {
			$(this).addClass("encoursderafraichissement");
			$.ajax({
				method: "POST",
				url: "ress_parametrage_tables_ajax.php",
				data: { TABLE: "<? echo $table?>", DELETE: $(this).attr('DELETE'), CHAMP: $(this).attr('CHAMP')  }
			}).done(function(msg) {
				$(".encoursderafraichissement").unbind();
				$(".encoursderafraichissement").html(msg);
				$(".encoursderafraichissement").find("input[value='Enregistrer']").click(function(e) {
					$("#update").html($(this).parents("tr")).html();
				});
				$(".encoursderafraichissement").removeClass("encoursderafraichissement");
			});
		});
		
		$('.import_table').click(function(e) {
			$('.imp-example-modal-md').modal('show');
			$('[name="ACTE_IMPORT"]').val($(this).attr('id'));
        });
		
        $('#importer_table').click(function(e) {
            $('.imp-example-modal-md').modal('show');
        });
        $('#add_droit').click(function() {
			$('[name="ACTE"]').val('ADD');
            window.param_droit_form.submit();
        });
		
		/*$('#modif_droit').click(function() {
            $('[name="ACTE"]').val('ADD');
            window.param_droit_form.submit();
        }); */
    </script>
<? include "ress/enpied.php";

