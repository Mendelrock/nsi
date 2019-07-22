<?php
require_once('ress/fpdf/fpdf.php');
require_once('ress/fpdf_js.php');
require_once("imprimer_ligne_of.php");
require_once('ress/barCodeC128.class.php');

function entete($pdf, $titre, $dataset, $exemplaire) {
    $largeur_colonne = $pdf->PageWidth() / 10;
    //Titre
    $pdf->AddPage();
    if($exemplaire) {
        $pdf->SetXY(($pdf->PageWidth()/2) - 28, 15);
        $pdf->SetFont('Arial','',15);
        $pdf->Cell(74,10,$exemplaire,'', '', 'C');
    }
    $pdf->SetXY(($pdf->PageWidth()/2) - 28, 25);
    $pdf->SetFont('Arial','',35);
    $pdf->Cell(74,12,$titre,'', '', 'C');
    // Numéro de commande
    $pdf->SetXY(205,10);
    if($dataset->type_dataset == "fdcentete") {
        $num_fdc = $dataset->ochamp["num_fdc"]->get();
    } else {
        $num_fdc = $dataset->ochamp["numcommande_fdc_of"]->get();
    }
    $num_fdc_explode = explode("-", $num_fdc);
    $numero_fdc = $num_fdc_explode[count($num_fdc_explode) - 1];
    $pdf->SetFont('Arial','B',35);
    $pdf->Cell((3 * $largeur_colonne) , 15, $numero_fdc, 1, 0, 'C');
    // Nom du client
    $pdf->SetXY(205,26);
    $nom_client = $dataset->ochamp["nomclient_fdc"]->get();
    $pdf->SetFont('Arial','B',15);
    $pdf->MultiCell((3 * $largeur_colonne) , 7, $nom_client, 1, 'C', 0);
    $pdf->SetXY(0,50);
    $pdf->ln(1);
}

function after_entete($dataset, $pdf, $type_dataset_entete) {
    $pdf->SetFont('Arial','',10);
    $compteur_champs = 0;
    $max_y = $pdf->getY();
    foreach ($dataset->champs as $id_champ => $champ) {
        if(!$dataset->ochamp[$id_champ]->non_html and !$dataset->ochamp[$id_champ]->non_pdf) {
            $saute = $champ[saute_avant][$GLOBALS[origine]];
            if ($saute>0) {
                $pdf->Cell(($pdf->PageWidth()/$dataset->presentation), 5, ' ', 0, '0', 'L', false);
                $compteur_champs++;
            }
            if ($saute>1) {
                $pdf->Cell(($pdf->PageWidth()/$dataset->presentation), 5, ' ', 0, '0', 'L', false);
                $compteur_champs++;
            }
            $type_cell = "Cell";
            if($textarea = $dataset->ochamp[$id_champ]->id_type_champ == "textarea"){
                $type_cell = "MultiCell";
            }
            $pdf->SetFont('Arial','B',8);
            $pdf->Cell(($pdf->PageWidth()/2/$dataset->presentation), 5, $dataset->ochamp[$id_champ]->lb_champ.' : ', '0', '', 'R');
            $pdf->SetFont('Arial','',8);
            $pdf->SetFillColor(240);
            if ($type_cell == "MultiCell") {
                $y_sauve = $pdf->getY();
                $x_sauve = $pdf->getX();
            }
            $pdf->$type_cell(($pdf->PageWidth()/2/$dataset->presentation), 5, $dataset->ochamp[$id_champ]->affiche(), $dataset->ochamp[$id_champ]->pdf_cadre*1, '0', 'L', true);
            $compteur_champs++;
            if ($type_cell == "MultiCell") {
                $max_y = max($max_y,$pdf->getY());
                $pdf->setXY($x_sauve+($pdf->PageWidth()/2/$dataset->presentation),$y_sauve);
            }

        } elseif ($type_dataset_entete == 'fdcentete') {
            $pdf->Cell(($pdf->PageWidth()/$dataset->presentation), 5, ' ', 0, 0, 'L', false);
            $compteur_champs++;
        }
        if ($compteur_champs == $dataset->presentation) {
            $compteur_champs = 0;
            $pdf->Ln(6);
            $pdf->setY(max($max_y+1,$pdf->getY()));
            $max_y = $pdf->getY();
        }
    }
    if ($compteur_champs != $dataset->presentation) {
        $pdf->Ln(6);
        $pdf->setY(max($max_y+1,$pdf->getY()));
    }
}

function generer_doc($list) {
    if(is_array($list)){
        $num_of = 1;
        foreach ($list as $id_doc){
            $doc = charge_un("select * from doc where id_doc = $id_doc");
            list($type_doc, $id_doc_fdc) = explode("|", $doc[type_doc]);
            $id_dataset_entete = $doc[id_dataset_entete];
            $id_dataset_empied = $doc[id_dataset_empied];
            //Chargement des propriétés du type de doc
            charge_doc($type_doc);
            $type_dataset_entete = $GLOBALS[parms][type_docs][$type_doc][type_dataset_entete];
            $type_dataset_empied = $GLOBALS[parms][type_docs][$type_doc][type_dataset_empied];
            $titre = $GLOBALS[parms][type_docs][$type_doc][libelle];
            $type_dataset_ligne = $GLOBALS[parms][type_docs][$type_doc][type_dataset_ligne];
            $orientation = $GLOBALS[parms][type_docs][$type_doc][orientation];
            if ($num_of == 1) {
                $pdf = new PDF($orientation);
            }
            $pdf->AutoPrint(true);
            $num_of++;

				if (!function_exists('impression')) {
					function impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_empied,
                                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, $exemplaire=""){
                    $titre = str_replace("<br>", " ", $titre);
                    $dataset = new dataset($type_dataset_entete);
                    $dataset->load_from_database($id_dataset_entete);
                    // Affichage du logo
                    $origine = $origine_base = $dataset->ochamp["origine"]->get();
                    if (!$origine) {
						$origine = 1;
					}
					// Distinction de Sodiclair et Ageda sur les OF
					if ($origine == 3 and $dataset->ochamp["agence"]){
						$origine = 6;
                    }
					$pdf->setOrigineLogo($origine);
                    $pdf->AliasNbPages();


						if ($type_doc != 'BON DE POSE' or $exemplaire == "Exemplaire dossier" or $exemplaire == "Exemplaire poseur") {

                        //*************************
                        //*************************
                        // ENTETE
                        //*************************
                        //*************************
							
						//--------------------
                        // ENTETE : OF
                        //--------------------

                        if (($type_dataset_entete == 'ofentete')) {

                            $largeur_colonne = $pdf->PageWidth() / 6;
                            $pdf->AddPage();
							$pdf->setOrigineLogo(0);
                            // Logo
							if ($GLOBALS[parms][type_docs][$type_doc] == 0) {
								$pdf->Image("client/client.jpg", $pdf->GetX() + 5, $pdf->GetY(), 40, 30);	
							}							
                            // Titre
                            $pdf->SetXY($pdf->GetX() + $largeur_colonne, $pdf->GetY() + 3);
                            $pdf->SetFont('Arial', 'B', 10);
                            $pdf->Cell(3 * $largeur_colonne, 5, $titre, 1, 0, "C");
                            // N° de commande à droite
                            $pdf->SetFont('Arial', 'B', 48);
                            $pdf->SetXY($pdf->GetX() + 3, $pdf->GetY());
                            $num_cde = $dataset->ochamp["numcommande_fdc_of"]->get();
                            if($origine_base !=3){
                                $num_cde_explode = explode("-", $num_cde);
                                $num_cde = $num_cde_explode[count($num_cde_explode) - 1];
                            }
                            $pdf->Cell((2 * $largeur_colonne) - 3, 17, $num_cde, 1, 0, 'C');
                            // Date d'expédition à droite
                            $date_expe = $dataset->ochamp["date_exp"]->get();
                            $pdf->SetXY($pdf->GetX() - (2 * $largeur_colonne) + 3, $pdf->GetY() + 19);
                            $pdf->Cell((2 * $largeur_colonne) - 3, 17, $date_expe, 1, 0, 'C');
					
							$code_departement = $dataset->ochamp["depchantier"]->get();
                            $pdf->SetXY($pdf->GetX() - (2*$largeur_colonne) + 3, $pdf->GetY() + 19);
                            $pdf->Cell(($largeur_colonne) - 3, 17, $code_departement, 1, 0, 'C');
							
							$code = new codeBarreC128($num_cde);
							$code->setTitle();
							$code->setFormat('PNG');
							$code->setFramedTitle(true);
							$code->setHeight(60);
							$bar = "temp/bar".time()."_".($GLOBALS[compteur_codes_barres]++).".png";
							$code->Output($bar);
							$pdf->Image($bar, $pdf->GetX() + 3, $pdf->GetY(), 0, 0);
                            // $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 20);
							
                            $pdf->Ln(8);
                            $pdf->SetXY($pdf->GetX(), $pdf->GetY() - 35);
                            $pdf->SetDisplayMode(real, 'default');
                            $compteur_champs = 0;
                            foreach ($dataset->champs as $id_champ => $champ) {
                                $type_cell = "Cell";
                                $longeur = 1;
                                if($dataset->ochamp[$id_champ]->id_type_champ =="textarea"){
                                    $type_cell = "MultiCell";
                                    $longeur = 3;
                                }
                                if (!$dataset->ochamp[$id_champ]->non_pdf and !$dataset->ochamp[$id_champ]->non_html) {
                                    $pdf->SetFont('Arial', 'B', 8);
                                    if ($dataset->ochamp[$id_champ]->lb_champ == "Commentaires pour fabrication")
                                        $pdf->ln(5);
                                    $pdf->Cell($largeur_colonne, 4, ($dataset->ochamp[$id_champ]->lb_champ) . ' : ', '0', '', 'R');
                                    if ($type_cell == "MultiCell") {
                                        $x_sauve = $pdf->getX();
                                        $y_sauve = $pdf->getY();
                                    }
                                    $pdf->$type_cell($largeur_colonne*$longeur, 4, $dataset->ochamp[$id_champ]->affiche(), '1', 'L', '');
                                    if ($type_cell == "MultiCell") {
                                        $pdf->setXY($x_sauve+$largeur_colonne,$y_sauve);
                                    }
                                    $compteur_champs++;
                                    if ($compteur_champs == $dataset->presentation) {
                                        $compteur_champs = 0;
                                        $pdf->Ln(5);
                                    }
                                }
                            }
							
                        }
                        //--------------------
                        // ENTETE : Commandes
                        //--------------------
                        if (($type_dataset_entete == 'commandeentete')) {
                            $pdf->AddPage();
							$pdf->setOrigineLogo(0);
                            $largeur_colonne = $pdf->PageWidth() / 6;
                            $pdf->SetFont('Arial', 'I', 10);
                            /*
                             * Recupérattion du numero de la feuille de cote,
                             * du produit et du fournisseur
                             */
                            $info_bc = charge_un("
								SELECT
									dt.fdc as fdc,
									dt.produit_entete_commande as produit,
									dt.fournisseur_commande as id_fournisseur 
								FROM
									doc
								LEFT JOIN zdataset_commandeentete dt on (dt.id_dataset = doc.id_dataset_entete)
								WHERE
									id_doc = $id_doc");
                            $numero_fdc = $info_bc[fdc];
                            $produit = $info_bc[produit];
                            $id_fournisseur = $info_bc[id_fournisseur];
                            /*
                             * Récupération de l'agence et du commercial
                             */
                            $info_bc2 = charge_un("
								SELECT
									dt.agence as agence,
									utilisateur.Id_utilisateur as commercial
								FROM
									affaire,
									utilisateur,
									doc
								LEFT JOIN
									zdataset_fdcentete dt on (dt.id_dataset = doc.id_dataset_entete)
								WHERE
									Id_affaire = dt.affaire and 
									utilisateur.Id_utilisateur = affaire.Id_utilisateur and
									doc.id_doc = $numero_fdc ");
                            $agence = $info_bc2[agence];
                            $commercial = $info_bc2[commercial];
                            /*
                             * Récupération de l'adresse
                             */
                            $_adresse = charge_un("
								SELECT
									adresse_livraison.adresse
								FROM
									adresse_livraison
								WHERE
									(adresse_livraison.agence = '".$agence."' or adresse_livraison.agence is null) and
									(adresse_livraison.commercial = '".$commercial."' or adresse_livraison.commercial is null) and
									(adresse_livraison.fournisseur = '".$id_fournisseur."' or adresse_livraison.fournisseur is null) ");
                            $addresse_livraison = $_adresse[adresse];
                            $informations_sodiclair = 	'SODICLAIR
		PONTAULT
		RUE DU DOLMEN
		28140 NOTTONVILLE';
                            $pdf->SetXY($pdf->GetX() + 6, $pdf->GetY() + 20);
                            $pdf->SetFont('Arial', 'I', 8);
                            $pdf->MultiCell(123.5, 3.7, $informations_sodiclair, '', 'L');
                            $informations_sodiclair = 	'Contact : Annabel FLAMAND
		Email : a.flamand@sodiclair.com
		N° Téléphone : 02 37 96 94 94';
                            $pdf->SetXY($pdf->GetX() + 6, $pdf->GetY() + 5);
                            $pdf->SetFont('Arial', 'I', 7);
                            $pdf->MultiCell(123.5, 3.7, $informations_sodiclair, '', 'L');
                            $pdf->SetXY($pdf->GetX() + $largeur_colonne, 10);
                            $pdf->SetFont('Arial', 'B', 10);
                            $pdf->Cell(4 * $largeur_colonne, 5, $titre, 1, 0, "C");
                            $num_cde_imp = $dataset->ochamp["numcommande_fdc_commande"]->get();
                            $pdf->SetFont('Arial', 'B', 12);
                            $pdf->SetXY($pdf->GetX() + 1, 10);
                            $pdf->Cell((1 * $largeur_colonne) - 3, 8, 'N° de Commande', 1, 0, 'C');
                            $pdf->Ln(10);
                            $pdf->SetFont('Arial', 'B', 15);
                            $pdf->SetXY($pdf->GetX() + 1 + 5 * $largeur_colonne, $pdf->GetY());
                            $pdf->MultiCell((1 * $largeur_colonne) - 3, 17, $num_cde_imp, 1, 'C');
                            $pdf->SetXY($pdf->GetX() - $largeur_colonne, $pdf->GetY() + 19);
                            $pdf->SetFont('Arial', 'B', 12);
                            $pdf->SetXY($pdf->GetX() - (2 * $largeur_colonne) + 3, $pdf->GetY() + 5);
                            $pdf->Ln(2);
                            $pdf->SetDisplayMode(real, 'default');
                            $compteur_champs = 0;
                            $pdf->SetXY($pdf->GetX() + $largeur_colonne, 20);
                            foreach ($dataset->champs as $id_champ => $champ) {
                                if (!$dataset->ochamp[$id_champ]->non_pdf) {
                                    if ($dataset->ochamp[$id_champ]->id_type_champ == "checkbox" && !$dataset->ochamp[$id_champ]->non_html) {
                                        $stringWidth = 0;
                                        $txt = ($dataset->ochamp[$id_champ]->lb_champ) . ' : ';
                                        $pdf->SetFont('Arial', '', 7);
                                        $stringWidth += $pdf->GetStringWidth($txt);
                                        $pdf->Cell($pdf->GetStringWidth($txt), 1, $txt, '');
                                        if ($dataset->ochamp[$id_champ]->get() != '') $liste = explode("|", $dataset->ochamp[$id_champ]->get());
                                        foreach ($dataset->ochamp[$id_champ]->valeurs as $valeur => $valeur_affiche) {
                                            $stringWidth += $pdf->GetStringWidth($valeur);
                                            if (is_array($liste) && in_array($valeur, $liste))
                                                $pdf->Image("images/case.jpg", $pdf->GetX() + 2, $pdf->GetY() - 1, 3);
                                            else
                                                $pdf->Image("images/case_vide.jpg", $pdf->GetX() + 2, $pdf->GetY() - 1, 3);
                                            $pdf->SetX($pdf->GetX() + 3 + $pdf->GetStringWidth($valeur));
                                            $pdf->Cell($pdf->GetStringWidth($valeur), 1, $valeur, '');
                                            $stringWidth += 3 + $pdf->GetStringWidth($valeur);
                                        }
                                        $pdf->SetX($pdf->GetX() + $pdf->PageWidth() / $dataset->presentation - $stringWidth);
                                    } else {
                                        $txt = ($dataset->ochamp[$id_champ]->lb_champ) . ' : ' . $dataset->ochamp[$id_champ]->affiche();
                                        $pdf->SetFont('Arial', 'B', 8);
                                        if (!$dataset->ochamp[$id_champ]->non_html) {

                                            $saute = (int)$dataset->champs[$id_champ][saute_avant][$GLOBALS[origine]];
                                            if ($saute>0) {
                                                $pdf->Cell($largeur_colonne*$saute, 4, ' ', 0, '0', 'L', false);
                                                $compteur_champs += $saute;
                                                if($compteur_champs >= $dataset->presentation) {
																	$compteur_champs = $compteur_champs-$dataset->presentation;
                                                   $pdf->Ln(5);
                                                   $pdf->SetXY($pdf->GetX() + ($largeur_colonne*$compteur_champs), $pdf->GetY());
                                                }
                                            }

                                            $pdf->Cell($largeur_colonne, 4, ($dataset->ochamp[$id_champ]->lb_champ) . ' : ', '0', '', 'R');

                                            $type_cell = "Cell";
                                            $longeur = 1;
                                            if($textarea = $dataset->ochamp[$id_champ]->id_type_champ =="textarea") {
                                                $type_cell = "MultiCell";
                                                $longeur = 3;
                                            }
                                            $pdf->$type_cell($largeur_colonne*$longeur, 4, $dataset->ochamp[$id_champ]->affiche(), '1', 'L');

                                            $compteur_champs++;
                                            if ($compteur_champs == $dataset->presentation) {
                                                $compteur_champs = 0;
                                                $pdf->Ln(5);
                                                $pdf->SetXY($pdf->GetX() + $largeur_colonne, $pdf->GetY());
                                            }
                                        }
                                    }
                                }
                            }
                            $pdf->Ln(10);
                            $pdf->SetXY($pdf->GetX() + $largeur_colonne, $pdf->GetY());
                            $pdf->SetFont('Arial', 'B', 10);
                            $pdf->Cell((2 * $largeur_colonne) - 8, 10, 'Adresse Livraison', 1, 0, '');
                            $pdf->SetXY($pdf->GetX() + 10, $pdf->GetY());
                            $pdf->SetFont('Arial', 'B', 10);
                            $pdf->Cell((2 * $largeur_colonne) - 8, 10, 'Adresse Facturation', 1, 0, '');
                            $pdf->Ln(12);
                            /*$addresse_livraison = 'SODICLAIR
                      5 RUE DU DOLMEN - PONTAULT
                      28140 NOTTONVILLE';*/
                            $top = $pdf->GetY();
                            $pdf->SetXY($pdf->GetX() + $largeur_colonne, $pdf->GetY());
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->MultiCell(123.5, ($addresse_livraison)?3.7:8, $addresse_livraison, 'L', 'L');
                            $bottom = $pdf->GetY();
                            $addresse_facturation = 'SODICLAIR
		5 RUE DU DOLMEN - PONTAULT
		28140 NOTTONVILLE';
                            $pdf->SetXY($pdf->GetX() + 2 + (3 * $largeur_colonne), $pdf->GetY() - ($bottom - $top) );
                            $pdf->SetFont('Arial', '', 8);
                            $pdf->MultiCell(123.5, 3.7, $addresse_facturation, 'L', 'L');
                            $pdf->SetXY($pdf->GetX(), $pdf->GetY() + 10);
                        }

                        //-----------------------------------------//
                        // ENTETE :  Feuille de cote ***********//
                        //-----------------------------------------//
                        if (($type_dataset_entete == 'fdcentete' or $type_dataset_entete == 'bonentete')) {
                            entete($pdf, $titre, $dataset, $exemplaire);
                            after_entete($dataset, $pdf, $type_dataset_entete);

                            if($type_dataset_entete == 'fdcentete'){
                                /* synthèse quantités produits */
                                    $req = "
                                        SELECT 
                                            *
                                        FROM
                                            doc_ligne,
                                            doc
                                        WHERE
                                            doc_ligne.id_doc = doc.id_doc AND
                                            doc.id_dataset_entete = $dataset->id_dataset";

                                    $resultat = new db_sql($req);
                                    $tablisteproduits = array();
                                    $listeproduits="";
                                    while($resultat->n()){
                                        $champ_qte = "qte";
                                        $id_dataset = $resultat->f('id_dataset');
										$type_dataset_imp = getTypeDataset($id_dataset);
                                        $Total = req_sim("SELECT SUM($champ_qte) AS Total from `zdataset_".$type_dataset_imp."` where id_dataset = '$id_dataset'","Total");
                                        $tablisteproduits[$resultat->f('produit')] += $Total;
                                    }
                                    foreach ($tablisteproduits as $produit => $nb) {
                                        $listeproduits .= $produit." : ".$nb."\n";
                                    }

                                    $pdf->MultiCell(($pdf->PageWidth()/4), 5, $listeproduits, 1, 'L', true);
                                
                            }
                        }
                        // *********************
                        // *********************
                        // Impression des lignes
                        // *********************
                        // *********************
                        if (!function_exists('edite_bloc_ligne')) {
                            function edite_bloc_ligne($type_dataset,$onglet_courant,$id_doc,$produits,$pdf) {
										  $pdf->SetFont('Arial', '', 10);
                                $pdf->Cell($pdf->PageWidth(), 6, $produits[$onglet_courant][libelle], 1, 1, 'L', 1);
                                $pdf->Ln(2);
                                $dataset = new dataset($type_dataset);
										  $largeur_colonne = $pdf->PageWidth()/$dataset->presentation;
                                $doc = charge_un("select id_dataset from doc_groupe_ligne where id_doc = $id_doc and produit='$onglet_courant'");
                                $id_dataset = $doc[id_dataset];
                                if ($id_dataset) {
                                    $compteur_champs = 0;
                                    $dataset->load_from_database($id_dataset);
                                    foreach ($dataset->champs as $id_champ => $champ) {
                                        if(!$dataset->ochamp[$id_champ]->non_html) {
														  $saute = (int)$dataset->champs[$id_champ][saute_avant][$GLOBALS[origine]];
                                            if ($saute>0) {
																$pdf->$type_cell($largeur_colonne*$saute, 5, ' ', 0, '0', 'L', false);														  
                                                $compteur_champs += $saute;
                                                if($compteur_champs >= $dataset->presentation) {
																	$compteur_champs = $compteur_champs-$dataset->presentation;
                                                   $pdf->Ln(6);
                                                   $pdf->SetXY($pdf->GetX() + ($largeur_colonne*$compteur_champs), $pdf->GetY());
                                                }
															}
													  
														  $pdf->SetFont('Arial','B',8);
                                            $pdf->Cell(($pdf->PageWidth()/2/$dataset->presentation), 5, trim($dataset->ochamp[$id_champ]->lb_champ) .' : ', '0', '', 'R');
                                            $pdf->SetFont('Arial','',8);
                                            $pdf->SetFillColor(240);
                                            $type_cell = "Cell";
                                            $longeur = 1;
                                            if($textarea = $dataset->ochamp[$id_champ]->id_type_champ =="textarea") {
                                                $longeur = 3;
                                                $type_cell = "MultiCell";
                                            }
                                            $pdf->$type_cell(($pdf->PageWidth()/2/$dataset->presentation)*$longeur, 5, trim($dataset->ochamp[$id_champ]->getPrintValue()), '0', '0', 'L', true);
                                            $compteur_champs++;
                                            if($textarea) {
																$pdf->setY($pdf->getY()-5);
														  }
														  if ($dataset->ochamp[$id_champ]->colspan) {
																$compteur_champs = $dataset->presentation;
														  }
                                        }
                                        if ($compteur_champs >= $dataset->presentation) {
                                            $compteur_champs = 0;
                                            $pdf->Ln(6);
                                        }
                                    }
                                    if ($compteur_champs != 0) $pdf->Ln(5);
                                }
                            }
                        }
                        $pdf->Ln(4);
                        $pdf->SetXY($pdf->GetX() - 190, $pdf->GetY());
                        if (!$type_dataset_ligne) {
                            $liste_produits = charge("select distinct produit from doc_ligne where id_doc = $id_doc");
                            foreach ($liste_produits as $i => $produits) {
                                $onglet_courant = $produits[produit];
                                require("param_2_produits/" . $onglet_courant . ".php");
                                $type_dataset_groupe_ligne = $produits[$onglet_courant][type_dataset_groupe_ligne];
                                $pdf->Ln(3);
                                if (!function_exists('simulation')) {
                                    function simulation($pdf, $orientation, $origine, $type_dataset_groupe_ligne,$onglet_courant,$id_doc,$produits, $type_dataset_ligne){
                                        $pdf_simul = new PDF($orientation);
                                        $pdf_simul->setOrigineLogo($origine);
                                        $pdf_simul->AliasNbPages();
                                        $pdf_simul->AddPage();
                                        $pdf_simul->SetY($pdf->getY());
                                        edite_bloc_ligne($type_dataset_groupe_ligne,$onglet_courant,$id_doc,$produits,$pdf_simul);
                                        imprimer_ligne_of($pdf_simul, $type_dataset_ligne, $onglet_courant, $produits, $id_doc);
                                        return $pdf_simul->PageNo();
                                    }
                                }
                                if(simulation($pdf, $orientation, $origine, $type_dataset_groupe_ligne,$onglet_courant,$id_doc,$produits, $type_dataset_ligne)>1){
                                    entete($pdf, $titre, $dataset, $exemplaire);
                                }
                                edite_bloc_ligne($type_dataset_groupe_ligne,$onglet_courant,$id_doc,$produits,$pdf);
                                imprimer_ligne_of($pdf, $type_dataset_ligne, $onglet_courant, $produits, $id_doc);
                                $pdf->Ln(5);

                            }
                        } else {
                            $onglet_courant = '-';
                            imprimer_ligne_of($pdf, $type_dataset_ligne, $onglet_courant, "", $id_doc);
                        }
					}

					// **********************
					// **********************
					// Impression de l'empied
					// **********************
					// **********************

					if ($type_dataset_empied or $type_dataset_entete == 'bonentete') {
                        $pdf->Ln(3);
                        $dataset = new dataset($type_dataset_empied);
                        $dataset->load_from_database($id_dataset_empied);
                        $y = $pdf->GetY();
                        $x = $pdf->GetX();

                        if ($dataset->presentation == 'ofempied') {
							if ($type_dataset_empied) {
								$pdf->Ln(3);
								$dataset = new dataset($type_dataset_empied);
								$dataset->load_from_database($id_dataset_empied);
								$y = $pdf->GetY();
								$x = $pdf->GetX();
								foreach ($dataset->champs as $id_champ => $champ) {
									if ($dataset->ochamp[$id_champ]->non_pdf) {
										unset($dataset->champs[$id_champ]);
									}
								}
								//-----------------------------------------//
								// EMPIED :  OF et commande     ***********//
								//-----------------------------------------//
								$largeur_cellule = $pdf->PageWidth() / count($dataset->champs);
								$pdf->ln();
								$liste_ligne = array();
								$profondeur = 0;
								$pdf->SetFillColor(200, 200, 200);
								$pdf->SetFont('Arial', '', 10);
								// Affichage des premières lignes
								foreach ($dataset->champs as $id_champ => $champ) {
									 $liste_lignes[$id_champ] = explode("<BR>", $dataset->ochamp[$id_champ]->get());
									 $profondeur = max($profondeur, count($liste_lignes[$id_champ]));
									 $pdf->Cell($largeur_cellule, 6, $liste_lignes[$id_champ][0], 1, 0, "C", 1);
								}
								$pdf->ln();
								$pdf->SetFont('Arial', '', 9);
								// Affichage des lignes suivantes
								for ($i = 1; $i < $profondeur; $i++) {
									 foreach ($dataset->champs as $id_champ => $champ) {
										  if ($i < count($liste_lignes[$id_champ])) {
												$chaine = $liste_lignes[$id_champ][$i];
												$chaine = str_replace('&nbsp;', "  ", $chaine);
												if ($i == count($liste_lignes[$id_champ]) - 1) $bottom = "B"; else $bottom = "";
												$liste_chaine = explode(":", $chaine);
												// remplacement des : par 2 colonnes
												if (count($liste_chaine) > 1) {
													 $pdf->Cell(20, 4, trim($liste_chaine[0]), "LR" . $bottom, 0, 'R', 0);
													 $pdf->Cell($largeur_cellule - 20, 4, trim($liste_chaine[1]), "LR" . $bottom, 0, 'L', 0);
													 // remplacement des * par des lignes tramées
												} else if (strpos($chaine, '*')) {
													 $chaine = str_replace("*", "", $chaine);
													 $pdf->Cell($largeur_cellule, 4, trim($chaine), "LR" . $bottom, 0, 'C', 1);
												} else {
													 $pdf->Cell($largeur_cellule, 4, trim($chaine), "LR" . $bottom, 0, 'L', 0);
												}
										  } else {
												if($i == $profondeur-1){
													 $pdf->Cell($largeur_cellule, 4, " ", "LRB", 0);
												}else{
													 $pdf->Cell($largeur_cellule, 4, " ", "LR", 0);
												}
										  }
									 }
									 $pdf->ln();
								}
							}
                        }
                        if($type_dataset_entete == 'bonentete'){
									$dataset = new dataset('bonentete');
									$dataset->load_from_database($id_dataset_empied);
									entete($pdf, $titre, $dataset, $exemplaire);
									after_entete($dataset, $pdf, $type_dataset_entete);
									if (!function_exists('ligne_saisie')) {
										function ligne_saisie($pdf, $texte, $width, $height){
											$pdf->SetFont('Arial','B',15);
											$pdf->Cell(($pdf->PageWidth()/3), 5, $texte, '0', '', 'R');
											$pdf->SetFont('Arial','',20);
											$pdf->Cell(($width), $height, '', 1, '0', 'L', true);
											$pdf->Ln(10);
										}
									}
									$pdf->Ln(1);
									$pdf->SetFillColor(255);
									if ($type_doc == 'BON DE LIVRAISON'){
										$pdf->Ln(10);
										ligne_saisie($pdf, 'Date et Heure de Réception : ',180,8);
										$pdf->SetX(($pdf->PageWidth()/2)-18);
										$pdf->SetFont('Arial','B',15);
										$pdf->Cell(($pdf->PageWidth()/3), 5, 'Cachet et Signature', '0', '', 'R');
										$pdf->Cell(($pdf->PageWidth()/4), 50, '', 1, '0', 'L', true);
                           } else {
                                ligne_saisie($pdf, 'Date et Heure d\'Arrivée : ', $pdf->PageWidth()/2, 8);
                                ligne_saisie($pdf, 'Date et Heure de Départ : ', $pdf->PageWidth()/2, 8);
                                $pdf->SetFont('Arial','B',15);
                                $pdf->Cell(($pdf->PageWidth()/3), 5, " Prestation terminée :", '0', '', 'R');
                                $pdf->Cell(20, 5, "Oui", '0', '', 'R');
                                $pdf->Cell(10, 8, " ", '1', '', 'L');
                                $pdf->Cell(20, 5, "Non", '0', '', 'R');
                                $pdf->Cell(10, 8, " ", '1', '', 'L');
                                $pdf->Ln(10);
                                ligne_saisie($pdf, 'Travaux restant à réaliser :', $pdf->PageWidth()/2, 20);
                                $pdf->Ln(13);
                                $pdf->SetX(($pdf->PageWidth()/2)-60);
                                $pdf->SetFont('Arial','B',15);
                                $pdf->Cell(($pdf->PageWidth()/3), 5, 'Cachet et Signature', '0', '', 'R');
                                $pdf->Cell(($pdf->PageWidth()/4), 30, '', 1, '0', 'L', true);
                                $pdf->SetX(($pdf->PageWidth()/2)-125);
                                $pdf->SetFont('Arial','',10);
                                $pdf->SetFillColor(240);
                                $pdf->MultiCell(($pdf->PageWidth()/4), 5, $dataset->ochamp[synthese_produit]->get(), 1, 'L', true);
                           }
                        }
							}
							return $pdf;
					}
            }
            if ($type_doc == 'BON DE POSE') { // les bons de pose sont imprimés en 3 exemplaires
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_empied,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire dossier");
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_empied,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire poseur");
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_empied,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire client");
            } elseif ($type_doc == 'BON DE LIVRAISON'){
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_entete,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire dossier");
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_entete,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire client");
				impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_entete,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne, "Exemplaire livreur");
            } else {
                impression($pdf, $orientation, $titre, $type_dataset_entete, $type_dataset_empied,
                    $id_dataset_entete, $id_dataset_empied, $type_doc, $id_doc, $type_dataset_ligne);
            }
        }
        return $pdf;
    }
}
?>