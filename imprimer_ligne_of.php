<?php

if (!function_exists('imprimer_ligne_of')) {
function imprimer_ligne_of(&$pdf, $type_dataset_ligne, $onglet_courant, $produits, $id_doc ){

	if(!$type_dataset_ligne){
		require_once("param_2_produits/".$onglet_courant.".php");  
		$type_dataset_produit = $produits[$onglet_courant][type_dataset_ligne];
	} else {
		$type_dataset_produit = $type_dataset_ligne;
	}
	if (!strpos($type_dataset_produit,"PICKING")) {
	
		$pdf->SetFont('Arial','B',5);
		$pdf->Ln(1);
		$dataset = new dataset($type_dataset_produit);

		//Calcul d'un tableau des colonnes
		//********************************

		$champs = $dataset->liste_champ_ordonnes();
		$spans = $dataset->liste_des_off_sets_de_spans();
		$largeurs = $dataset->liste_des_largeurs();

		$PageWidth = $pdf->PageWidth();
		$col_width = $pdf->PageWidth() / array_sum($largeurs);
		$col_height = 10;

	   if (!$dataset->fulltable) {

			//Impression des entête regroupées
			//********************************

			if(count($spans)){
				$x = $pdf->GetX();
				$y = $pdf->GetY();
				$i=0;
				$j=0;
				$n_ligne = 0;
				foreach ($dataset->champs as $id_champ => $champ) {

					if ($dataset->ochamp[$id_champ]->startligne) $n_ligne++;
					if ($n_ligne>1) break;
					$txt = $dataset->ochamp[$id_champ]->lb_champ_pdf;
					
					if ($dataset->ochamp[$id_champ]->colspan) {
						$largeur = $col_width * $spans[$j++][largeur];
						$i = $i + $dataset->ochamp[$id_champ]->colspan;
						$pdf->SetXY($x, $y);
						$pdf->MultiCell($largeur, $col_height/2, $txt, 'LTRB','C');
						$x += $largeur;
					} else {
						$x += $col_width*$largeurs[$i];
						$i++;
					}
				}
			}

			$pdf->ln(0);

			// Impression de l'entête simple

			//******************************

			$x = $pdf->GetX();
			$y = $pdf->GetY();

		   if (!function_exists('simulation_champ_entete')) {
			   function simulation_champ_entete($champs, $pdf, $dataset, $col_width, $col_height,$largeurs, $x, $y) {
				   foreach ($champs as $i => $id_champ) {
					   $largeur = $col_width * $largeurs[$i];
					   $pdf->SetXY($x, $y);
					   $txt = $dataset->ochamp[$id_champ]->lb_champ_pdf;
					   $pdf->SetFont('Arial','B', (5+$dataset->ochamp[$id_champ]->pdf_fontsize_entete) );
					   $pdf->MultiCell($largeur, $col_height/get_number_of_lines($txt,0,$largeur,$pdf), $txt, 'LTRB','C');
					   $x += $largeur;
				   }
			   }
		   }

		   simulation_champ_entete($champs, $pdf, $dataset, $col_width, $col_height,$largeurs,$x, $y);

		   /* foreach ($champs as $i => $id_champ) {
				$largeur = $col_width * $largeurs[$i];
				$pdf->SetXY($x, $y);
				$txt = $dataset->ochamp[$id_champ]->lb_champ_pdf;
				$pdf->SetFont('Arial','B', (5+$dataset->ochamp[$id_champ]->pdf_fontsize_entete) );
				$pdf->MultiCell($largeur, $col_height/get_number_of_lines($txt,0,$largeur,$pdf), $txt, 'LTRB','C');
				$x += $largeur;
			} */

		} else {

			$emplacements = $dataset->liste_des_emplacements;
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			foreach ($dataset->champs as $id_champ => $champ) {
				$pdf->SetXY($x + ($emplacements[$id_champ][x]*$PageWidth), $y + $emplacements[$id_champ][y]*$col_height);
				$txt = $dataset->ochamp[$id_champ]->lb_champ_pdf;

				$pdf->SetFont('Arial','B', (5+$dataset->ochamp[$id_champ]->pdf_fontsize_entete) );
				$largeur = $emplacements[$id_champ][largeur]*$PageWidth;
				$pdf->MultiCell($largeur, $emplacements[$id_champ][hauteur]*$col_height/get_number_of_lines($txt,0,$largeur,$pdf), $txt, 'LTRB','C');
			}
		}


	//Affichage des lignes
	//*********************

		$y_init = $pdf->GetY();
		$pdf->y1 = $pdf->GetY();
		$pdf->x1 = $pdf->GetX();
		$x_init = $pdf->x1;
		$y_init = $pdf->y1;
		$liste_ligne = charge("select id_dataset from doc_ligne where id_doc = $id_doc and produit = '$onglet_courant'");
		$pdf->SetFont('Arial','B',8);
		foreach ($liste_ligne as $i => $info) {
			$id_dataset_courant = $info[id_dataset];
			$dataset = new dataset($type_dataset_produit);
			$dataset->load_from_database($id_dataset_courant);
			$x = $pdf->GetX();
			$y = $pdf->GetY();
			foreach ($champs as $i => $id_champ) {
				$largeur = $col_width * $largeurs[$i];
				$pdf->SetXY($x, $y);
				$txt = $dataset->ochamp[$id_champ]->getPrintValue();
				//On diminue la taille de la font si bcp de lignes
				$taille_de_font = 8+$dataset->ochamp[$id_champ]->pdf_fontsize;
				if ($col_height/get_number_of_lines($txt,0,$largeur,$pdf)*3 < $taille_de_font) {
					$taille_de_font = $col_height/get_number_of_lines($txt,0,$largeur,$pdf)*3;
				}
				$pdf->SetFont('Arial','B',$taille_de_font);
				$pdf->MultiCell($largeur, $col_height/get_number_of_lines($txt,0,$largeur,$pdf), $txt, 'LTRB','C');
				$x += $largeur;
				$pdf->ln(0);
			}
			if (($pdf->GetY() + $col_height) > 190){

						$pdf->AddPage();
						$pdf->SetY($pdf->GetY() + 25);

						$x = $pdf->GetX();
						$y = $pdf->GetY();
						simulation_champ_entete($champs, $pdf, $dataset, $col_width, $col_height,$largeurs,$x, $y);
			}
				
		}



	//Affichage des commentaires finaux

	//*********************************



		if($type_dataset_produit=="OF - BATEAU - CONFECTION"){

			$pdf->Ln(4);
			
			$pdf->SetFont('Arial','BU',10);
			$pdf->Cell(7,3,' ',0,0);
			$pdf->Image("images/fleche.jpg",$pdf->GetX()-7,$pdf->GetY()-1,8,5);
			$pdf->Cell(22,3,"ATTENTION",0,0);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(150,3,": Avant de COUPER, Vérifier la Disponibilité du Tissu dans les Chutes",0,1);
			$pdf->Ln(1);
			$pdf->Cell(7,3,' ',0,0);
			$pdf->Image("images/fleche.jpg",$pdf->GetX()-7,$pdf->GetY()-1,8,5);
			$pdf->Cell(30,3,"Les chutes de Tissu",0,0);
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(65,3,"supérieures ou égales  à 1,50 m x 0,50 m",0,0);
			$pdf->SetFont('Arial','B',8);
			$pdf->Cell(120,3,"doivent être rangées pour la confection future de Rideaux",0,1);

			$pdf->Ln(1);

			$pdf->Cell(7,3,' ',0,0);
			$pdf->Image("images/fleche.jpg",$pdf->GetX()-7,$pdf->GetY()-1,8,5);
			$pdf->Cell(200,3,"Les autres Chutes de Tissu doivent être stockées sur la plateforme pour la réalisation des Echantillons",0,1);
			$pdf->SetFont('Arial','B',8);
		} else if($type_dataset_produit=="OF - RIDEAU"){
			$pdf->Ln(4);
			$pdf->SetFont('Arial','B',8);
		}
	
	}
	
}
}



?>