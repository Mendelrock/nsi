<?php
if(!$type_dataset_ligne){
   //$type_dataset_produit = $GLOBALS[parms][produits][$onglet_courant][type_dataset_ligne];
   require("param_2_produits/".$onglet_courant.".php");  
   $type_dataset_produit =$produits[$onglet_courant][type_dataset_ligne];
}
else
   $type_dataset_produit =$type_dataset_ligne;
$pdf->SetFont('Arial','',6);
$dataset = new dataset($type_dataset_produit);
//Calculer la largeur moyenne d'une colonne
$nb_champ =0;$colspan_exist = false;
if ($dataset->presentation == 'custom_peinture') {
   foreach ($dataset->champsentete as $id_champ => $champ) {
      $nb_champ++;
      if($dataset->ochampentete[$id_champ]->id_type_champ) $nb_champ++;
   }
}
else {
   foreach ($dataset->champs as $id_champ => $champ) {
      if($dataset->ochamp[$id_champ]->id_type_champ) $nb_champ++;
      else   $colspan_exist = true; 
   }
}
$col_width = $pdf->PageWidth()/$nb_champ;
$y1 = $pdf->GetY();
$x = $pdf->GetX(); 
$x_init = $x;
$y_init = $y1;
$col_height = 6;
$numero_ligne = 0;
$position_deuxieme_ligne_affectee = false;
$tab_rowspan_premiere_ligne = array();
//Début impression de l'entête
if ($dataset->presentation == 'custom_peinture') {
   foreach ($dataset->champsentete as $id_champ => $champ) {
      $txt = $dataset->ochampentete[$id_champ]->lb_champ;
      $cell_width = 1;
      if($dataset->ochampentete[$id_champ]->largeur) $cell_width =$dataset->ochampentete[$id_champ]->largeur; 
      $delta_x=$col_width * $cell_width ;
      if($dataset->ochampentete[$id_champ]->id_type_champ){
         $pdf->SetXY($pdf->GetX()+5,$pdf->GetY());
         $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($txt,$col_height,$delta_x,$pdf), $txt, '');
      }
      else
         $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($txt,$col_height,$delta_x,$pdf), $txt, 'LTRB','C');
      $y2 = $pdf->GetY();
      $yH = $y2 - $y1;
      if($yH>$col_height) $col_height = $yH;
      $pdf->SetXY($x + $delta_x, $pdf->GetY() - $yH);
      $y1 = $pdf->GetY();
      $x = $pdf->GetX();
   }
} else {
   foreach ($dataset->champs as $id_champ => $champ) {
      $txt = $dataset->ochamp[$id_champ]->lb_champ;
      $cell_width = 1;
      if($dataset->ochamp[$id_champ]->largeur) $cell_width =$dataset->ochamp[$id_champ]->largeur;
      if($dataset->ochamp[$id_champ]->startligne) {
         $numero_ligne++;
         if($numero_ligne==2) {
            $pdf->SetXY($position_deuxieme_ligne_x,$position_deuxieme_ligne_y);
            $y1 = $pdf->GetY();
            $x = $pdf->GetX();
         }
      }
      //Si l'entête est sur une seule ligne
      if(!$colspan_exist) {
         $delta_x=$col_width* $cell_width;
         $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($txt,$col_height,$delta_x,$pdf), $txt, 'LTRB');
      }
      else {
         if($dataset->ochamp[$id_champ]->rowspan) {
	        //On stocke toutes les colonnes rowspan déjà affichées dans la 1ère ligne
	        if($numero_ligne==1 && $position_deuxieme_ligne_affectee) {
	           $tab_rowspan_premiere_ligne[$x]=$col_width* $cell_width;	
	        }
	        $delta_x=$col_width* $cell_width;
	        $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($txt,$col_height,$delta_x,$pdf), $txt, 'LTRB');
	     }
	     else if ($dataset->ochamp[$id_champ]->colspan) {
	     //Onstocke la poition à la quelle on va afficher la 2ème ligne de l'entête
	        if($numero_ligne==1 && !$position_deuxieme_ligne_affectee) {
	           $position_deuxieme_ligne_affectee =true ;	
	           $position_deuxieme_ligne_x=$x;
	           $position_deuxieme_ligne_y=$y1+$col_height/2;
	        }
	        if($cell_width!=1)
	           $delta_x=$col_width * $cell_width;
	        else
	           $delta_x=$col_width * $dataset->ochamp[$id_champ]->colspan;
	        $pdf->MultiCell($delta_x, $col_height/(2*get_number_of_lines($txt,$col_height/2,$delta_x,$pdf)), $txt, 'LTRB','C');	
	     }
	     else {
	        //S'il y a déjà une colonne affichée à cette cordonnée
	        if($tab_rowspan_premiere_ligne[$x]) {
	           while($tab_rowspan_premiere_ligne[$x]) {
		          $pdf->SetXY($x+$tab_rowspan_premiere_ligne[$x],$y1);
	   	          $y1 = $pdf->GetY();
                  $x = $pdf->GetX();	
		       }	
	        }
	        $delta_x=$col_width* $cell_width;
	        $pdf->MultiCell($delta_x, $col_height/2, $txt, 'LTRB');
	     }
     } 
     $y2 = $pdf->GetY();
     if($y1>$y2) {
        $y1=$y2;
        $yH=$col_height;
     }
     else{
        $yH = $y2 - $y1;
        if($yH>$col_height) $col_height = $yH;
     }
     $pdf->SetXY($x + $delta_x, $pdf->GetY() - $yH);
     $y1 = $pdf->GetY();
     $x = $pdf->GetX();
   }
}
//Fin impression de l'entête
//Affichage des lignes
$y_init = $pdf->GetY();
$pdf->SetXY($x_init,$y_init+$col_height);
$pdf->y1 = $pdf->GetY();
$pdf->x1 = $pdf->GetX();
$x_init = $pdf->x1;
$y_init = $pdf->y1;
$liste_ligne = charge("select id_dataset from doc_ligne where id_doc = $id_doc and produit = '$onglet_courant'"); 
foreach ($liste_ligne as $i => $info) {
   $id_dataset_courant = $info[id_dataset];
   $dataset = new dataset($type_dataset_produit);
   $dataset->load_from_database($id_dataset_courant);
   $tab_valeurs = array();
   $numero_ligne = 0;
   $colspan_exist = false;$col_height=6;
   $colonne_courante = 0;$colonne_affichee = 0; $column_printed = false;
   foreach ($dataset->champs as $id_champ => $champ) {
         $txt = $dataset->ochamp[$id_champ]->getPrintValue();
         $cell_width = 1;
         if($dataset->ochamp[$id_champ]->largeur) $cell_width =$dataset->ochamp[$id_champ]->largeur;
         if($dataset->ochamp[$id_champ]->startligne) $numero_ligne++;
         if($dataset->ochamp[$id_champ]->id_type_champ) {
            $colonne_courante++;
            if(!$colspan_exist || $numero_ligne>1) {
               if($numero_ligne>1 && !$column_printed){
                  $colonne_affichee =0;
			      }
			      $column_printed = true;
               $colonne_affichee++;
               if($tab_valeurs[$colonne_affichee]) {
                  list($val,$largeur) = explode("|",$tab_valeurs[$colonne_affichee]);	
                  $delta_x=$col_width* $largeur;	
                  $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($val,$col_height,$delta_x,$pdf), $val, 'LTRB');	
			         $pdf->RecalculatePosition($delta_x,$col_height);
			         $col = $colonne_affichee;
			         while($tab_valeurs[$col+1]) $col++;
                     $tab_valeurs[$col+1]=$txt.'|'.$cell_width;
               }
               else {
                  $delta_x=$col_width* $cell_width;	
                  $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($txt,$col_height,$delta_x,$pdf), $txt, 'LTRB');
                  $pdf->RecalculatePosition($delta_x,$col_height);
               }
            }
            else
               $tab_valeurs[$colonne_courante]=$txt.'|'.$cell_width;   	
         }
         else {
            $colonne_courante +=$dataset->ochamp[$id_champ]->colspan ;
            $colspan_exist=true ;	
         }

   }
   if($colspan_exist) {
      $colonne_affichee++;
      while($tab_valeurs[$colonne_affichee]){
         list($val,$cell_width) = explode("|",$tab_valeurs[$colonne_affichee]);
         $delta_x=$col_width* $cell_width;	
	      $pdf->MultiCell($delta_x, $col_height/get_number_of_lines($val,$col_height,$delta_x,$pdf), $val, 'LTRB');	
         $pdf->RecalculatePosition($delta_x,$col_height);
         $colonne_affichee++;
	  }	
   }
   $y_init = $pdf->GetY();
   $y_init+=$col_height;
   $pdf->SetXY($x_init,$y_init);
   $pdf->y1 = $pdf->GetY();
   $pdf->x1 = $pdf->GetX();
}
?>