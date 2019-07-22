<?php
/*
-------------------------------------------------
 Retourne Toile Largeur	
-------------------------------------------------*/
function get_toile_largeur($parametres) {
  if($parametres['largeur']=="")
   $ret="";
  else
   $ret=$parametres['largeur']+get_deduction_toile_l($parametres);
  return $ret;
}
/*
-------------------------------------------------
 Retourne Déduction Toile Largeur	
-------------------------------------------------*/
function get_deduction_toile_l($parametres) {
  $produit=$parametres['produit'];	
  if ($produit=="Coffre 55"){
   if($parametres['manoeuvre']=="Chaînette")
     $ret=-40; 
   else if ($parametres['manoeuvre']=="Treuil Renvoi Intérieur") 
	   $ret=-50; 
   else if ($parametres['manoeuvre']=="Treuil sous Coffre") {
     if ($parametres['guidage']=="Coulisses 23 x 50") 
	     $ret=-40; 
     else
      $ret=-50;
   }
  }
  else if ($produit=="Coffre 75 - 3 faces"){
   if ($parametres['guidage']=="Coulisses 23 x 50") 
	   $ret=-40; 
   else
     $ret=-50;
  }
  else if($produit=="Coffre 75 - 4 faces"){
   if ($parametres['guidage']=="Coulisses 23 x 50") 
	   $ret=-40; 
   else if($parametres['guidage']=="Non Guidé") {
     if($parametres['manoeuvre']=="Chaînette")
      $ret=-40; 
     else
      $ret=-50;
   }
   else
     $ret=-50;
  }
  else if ($produit=="Coffre 95 - 3 faces")
   $ret=-60; 
  else if($produit=="Coffre 95 - 4 faces"){
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret=-60; 
   else if ($parametres['manoeuvre']=="Treuil Renvoi Intérieur"||$parametres['manoeuvre']=="Treuil sous Coffre") {
     if($parametres['guidage']=="Coulisses 36 x 21")
      $ret=-60; 
     else
      $ret=-50;
   }
  } 
  else if($produit=="Droit"){
   if($parametres['manoeuvre']=="Cordon"){
     if($parametres['guidage']=="Câble 3 mm")
      $ret=-85; 
     else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Non Guidé")
      $ret=-70;
     else if ($parametres['guidage']=="Coulisses 23 x 50") 
	     $ret=-60;
	  }
   else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"||$parametres['manoeuvre']=="Treuil sous Coffre"){
	   if($parametres['guidage']=="Câble 3 mm")
	     $ret=-60;
	   else if ($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50"||$parametres['guidage']=="Non Guidé") 
	     $ret=-50;
		}
   else if ($parametres['manoeuvre']=="Treuil Renvoi Intérieur")
     $ret=-60;
	}
  else if($produit=="Rouleau"){
   if($parametres['guidage']=="Coulisses 23 x 17")
     $ret=-50;
   else
     $ret=-40;
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Déduction COMMANDE Manivelle (Hauteur + …)
-------------------------------------------------*/
function get_deduction_manivelle($parametres) {
  $produit=$parametres['produit'];		
  if($parametres['manoeuvre']=="Chaînette"||$parametres['manoeuvre']=="Cordon"||$parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio")
   $ret ="-";
  else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") 
   $ret =-400;
  else if($parametres['manoeuvre']=="Treuil sous Coffre"){
   if($produit=="Coffre 55")
	   $ret =-480; 
	 else if ($produit=="Coffre 75 - 4 faces")
	   $ret =-490;
	 else if ($produit=="Coffre 95 - 4 faces"||$produit=="Droit")
	   $ret =-500;	
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Prix M² HT	
-------------------------------------------------*/
function get_prix_m2($parametres) {
  $gammetoile = get_gammetoile($parametres);
  if($gammetoile=="COPACCO NATTE 380")
   $ret = 5.7;	
  else if($gammetoile=="COPACCO SERGE 600")
   $ret = 6.8;
  else if($gammetoile=="COPACCO SERGE OCCULTANT")
   $ret = 12.6;
  else if($gammetoile=="DECO S")
   $ret = 6.45;
  else if($gammetoile=="HEXCEL 1303")
   $ret = 3.5847;
  else if($gammetoile=="HEXCEL LYVERMOON 11891")
   $ret = 9.7591;
  else if($gammetoile=="MERMET - FLOCKE 11201")
   $ret = 11.6;
  else if($gammetoile=="MERMET - KARELIS 11301 & 11302")
   $ret = 5.22;
  else if($gammetoile=="MERMET - M-SCREEN 8503 - 3%")
   $ret = 8.66;
  else if($gammetoile=="MERMET - M-SCREEN 8505 - 5%")
   $ret = 8.02;
  else if($gammetoile=="MERMET - OBION 1123")
   $ret = 7.02;
  else if($gammetoile=="SOLSCAPE A")
   $ret = 5.76;
  else if($gammetoile=="SOLSCAPE E1")
   $ret = 6.88;
  else if($gammetoile=="SOLSCAPE E2")
   $ret = 6.30;
  else if($gammetoile=="SOLTIS 92")
   $ret = 8.42;
  else if($gammetoile=="SOLTIS 99")
   $ret = 7.7;
  else if($gammetoile=="SOLTIS B 92N")
   $ret = 14.83;
  else if($gammetoile=="VEROFLEX 5023")
   $ret = 5.3750;
  else if($gammetoile=="VEROSAFE 12264")
   $ret = 4.8333;
  else if($gammetoile=="VERSO 3")
   $ret = 6.82;
  return $ret;
}

/*
-------------------------------------------------
 Retourne 4° FACE Auvent Face Arrière (L en mm)	
-------------------------------------------------*/
function get_auvent_l($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   $auvent_ref= get_auvent_ref($parametres);
	 if($auvent_ref=="-")
     $ret="-";
   else if($auvent_ref=="Auvent")
     $ret=$parametres['largeur'];
   else
     $ret=$parametres['largeur']+get_deduction_4f($parametres);
  }
  return $ret;
}
/*
-------------------------------------------------
 Retourne Toile Hauteur	
-------------------------------------------------*/
function get_toile_hauteur($parametres) {
  if($parametres['hauteur']=="")
   $ret="";
  else
   $ret=$parametres['hauteur']+get_deduction_toile_h($parametres);
  return $ret;
}

/*
-------------------------------------------------
 Retourne Batiment	
-------------------------------------------------*/
function get_batiment($parametres) {
  return $parametres['batiment'];
}


/*
-------------------------------------------------
 Retourne Etage	
-------------------------------------------------*/
function get_etage($parametres) {
  return $parametres['etage'];
}

/*
-------------------------------------------------
 Retourne Pièce	
-------------------------------------------------*/
function get_piece($parametres) {
  return $parametres['piece'];
}

/*
-------------------------------------------------
 Retourne Fenêtre	
-------------------------------------------------*/
function get_fenetre($parametres) {
  return $parametres['fenetre'];
}

/*
-------------------------------------------------
 Retourne PRIX TOTAL HT	
-------------------------------------------------*/
function get_prix_total_ht($parametres) {
  return round(get_prix_vente_unitaire($parametres) * get_qte($parametres),2);
}

/*
-------------------------------------------------
 Retourne Prix de vente unitaire	
-------------------------------------------------*/
function get_prix_vente_unitaire($parametres) {
  return round(get_total_prix_unitaire($parametres) * 1.1,2);
}

/*
-------------------------------------------------
 Retourne GAMME TOILE	
-------------------------------------------------*/
function get_gammetoile($parametres) {
    if($parametres['gammetoileinterieur']){
        $gammetoile = $parametres['gammetoileinterieur'];
    }elseif ($parametres['gammetoileexterieur']){
        $gammetoile = $parametres['gammetoileexterieur'];
    }elseif ($parametres['gammetoilecoffreinterieur']){
        $gammetoile = $parametres['gammetoilecoffreinterieur'];
    }else{
        $gammetoile = $parametres['gammetoilecoffreexterieur'];
    }

  if($gammetoile){
   list($valeur,$valeur_couleur) = explode("|",$gammetoile);
	 if ($valeur == "*") {
	   $ret = "Autre";	
	 }
	 else {
		 $ret = $valeur;	
	 }
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne GAMME TOILE COLORIS	
-------------------------------------------------*/
function get_gammetoile_coloris($parametres) {
  $gammetoile = $parametres['gammetoile'];
  if($gammetoile){
   list($valeur,$valeur_couleur) = explode("|",$gammetoile);
	 if ($valeur == "*") {
	   $ret = $valeur_couleur;	
	 }
	 else {
		 $ret = $GLOBALS[parms][toiles][$valeur][couleurs][$valeur_couleur][libelle];	
	 }
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne REFERENCE
-------------------------------------------------*/
function get_ref_emballage($parametres) {
  return $parametres['references'];
}

/*
-------------------------------------------------
 Retourne BANDES DE RENFORT
-------------------------------------------------*/
function get_bande_renfort($parametres) {
  return $parametres['bande_renfort'];	
}

/*
-------------------------------------------------
 Retourne COLORIS CONTRE VITRAGE
-------------------------------------------------*/
function get_coloris_vitrage($parametres) {
  return $parametres['coloris_vitrage'];	
}

/*
-------------------------------------------------
 Retourne Qté	
-------------------------------------------------*/
function get_qte($parametres) {
  return $parametres['qte']*1;
}
/*
-------------------------------------------------
 Retourne Confection HAUTE	
-------------------------------------------------*/
function get_confection_haute($parametres) {
  $produit=$parametres['produit'];	
  if ($produit=="Coffre 55" ||$produit=="Coffre 75 - 3 faces"||$produit=="Rouleau")
   $ret="Net de Coupe"; 
  else if($produit=="Coffre 75 - 4 faces"){
   if ($parametres['manoeuvre']=="Treuil Renvoi Intérieur"||$parametres['manoeuvre']=="Treuil sous Coffre") 
     $ret="Fourreau + Jonc 3mm"; 
   else
     $ret="Net de Coupe"; 
  }
  else if ($produit=="Coffre 95 - 3 faces"||$produit=="Coffre 95 - 4 faces")
   $ret="Net de Coupe";
  else if($produit=="Droit"){
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret="Net de Coupe";
   else
     $ret="Fourreau + Jonc 3mm"; 
  }
  return $ret;
}
/*
-------------------------------------------------
 Retourne Confection BASSE	
-------------------------------------------------*/
function get_confection_basse($parametres) {
  if($parametres['guidage']=="Câble 1mm"||$parametres['guidage']=="Coulisses 23 x 50")
   $ret="Net de Coupe";
  else if($parametres['guidage']=="Câble 3 mm"||$parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 36 x 21")
   $ret="Fourreau 40 mm"; 
  else if($parametres['guidage']=="Non Guidé"){
   $barre_charge_ref = get_barre_charge_ref($parametres);
   if($barre_charge_ref=='14 x 28 Alu'||$barre_charge_ref=='11 x 21 Alu')
     $ret="Net de Coupe";
   else if($barre_charge_ref=='Diam 18 Acier')
     $ret="Fourreau 40 mm";
  }  
  return $ret;
}

/*
-------------------------------------------------
 Retourne le produit	
-------------------------------------------------*/
function get_produit($parametres) {
  return $parametres['produit'];
}

/*
-------------------------------------------------
 Retourne manoeuvre	
-------------------------------------------------*/
function get_manoeuvre($parametres) {
  return $parametres['manoeuvre'];
}

/*
-------------------------------------------------
 Retourne Localisation	
-------------------------------------------------*/
function get_localisation($parametres) {
  return $parametres['localisationpose'];
}

/*
-------------------------------------------------
 Retourne TYPE GUIDAGE	
-------------------------------------------------*/
function get_type_guidage($parametres) {
  return $parametres['guidage'];
}

/*
-------------------------------------------------
 Retourne AXE ENROULEMENT 1	
-------------------------------------------------*/
function get_axe_enroulement_1($parametres) {
  $produit=$parametres['produit'];	
  if($produit=="Coffre 55") {
   if($parametres['manoeuvre']=="Chaînette") {
     $ret="20 mm Alu";	
   }
   else
     $ret="25 mm Alu";		
  }
  else if ($produit=="Coffre 75 - 3 faces") {
   $ret="40 mm Acier Lisse";
  }
  else if ($produit=="Coffre 75 - 4 faces") {
	 if($parametres['manoeuvre']=="Chaînette") {
	   $ret="29 mm Alu";	
	 }
	 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
		 $ret="40 mm Acier Lisse";	
	 }
	 else
		 $ret="34 mm Alu";		
  }
  else if ($produit=="Coffre 95 - 3 faces") {
   $ret="50 mm Acier Lisse";
  }
  else if ($produit=="Coffre 95 - 4 faces") {
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret="50 mm Acier Lisse";
	 } 
	 else 
	   $ret="43 mm Alu";	
  }
  else if ($produit=="Droit") {
   if($parametres['manoeuvre']=="Cordon" || $parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
	   $ret="40 mm Acier Goutte";	
	  }
	  else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret="40 mm Acier Lisse";
	  } 
	  else
	   $ret="34 mm Alu";
  }
  else if ($produit=="Rouleau") {
    $ret="29 mm Alu";
  }
  return $ret;		
}
/*
-------------------------------------------------
 Retourne AXE ENROULEMENT 2	
-------------------------------------------------*/
function get_axe_enroulement_2($parametres) {
  $produit=$parametres['produit'];	
  if($produit=="Coffre 55") {
   if($parametres['manoeuvre']=="Chaînette") {
     $ret="20 mm Alu";	
   }
   else
     $ret="25 mm Alu";		
  }
  else if ($produit=="Coffre 75 - 3 faces") {
   $ret="40 mm Acier Lisse";
  }
  else if ($produit=="Coffre 75 - 4 faces") {
	 if($parametres['manoeuvre']=="Chaînette") {
	   if($parametres['guidage']=="Coulisses 23 x 50") {	 
	    $ret="29 mm Alu";
		 }
		 else
		  $ret="43 mm Alu";	
	 }
	 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
		 $ret="40 mm Acier Lisse";	
	 }
	 else
		 $ret="34 mm Alu";		
  }
  else if ($produit=="Coffre 95 - 3 faces") {
   $ret="50 mm Acier Lisse";
  }
  else if ($produit=="Coffre 95 - 4 faces") {
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret="50 mm Acier Lisse";
	 } 
	 else 
	   $ret="43 mm Alu";	
  }
  else if ($produit=="Droit") {
   if($parametres['manoeuvre']=="Cordon" || $parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
	   $ret="40 mm Acier Goutte";	
	  }
	  else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret="40 mm Acier Lisse";
	  } 
	  else
	   $ret="34 mm Alu";
  }
  else if ($produit=="Rouleau") {
    $ret="43 mm Alu";
  }
  return $ret;	
}

/*
-------------------------------------------------
 Retourne le type moteur	
-------------------------------------------------*/
function get_type_moteur($parametres) {
  $produit=$parametres['produit'];	
  if($produit=="Coffre 55" ||$produit=="Rouleau")
   $ret ="-";
  else if ($produit=="Coffre 75 - 3 faces") {
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire")
     $ret ="NS11000SA - 5 Nm";
   else if ($parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
     if($parametres['typebarrecharge']=="Apparente" && $parametres['barre_charge_ref']=='14 x 28 Alu' && $parametres['typepose']=="Face" && $parametres['cotecommande']=="Gauche") 
      $ret ="NS11000SA - 5 Nm";
     else
      $ret ="NS11000MA - 5 Nm";
   }
  }
  else if ($produit=="Coffre 75 - 4 faces"){
   if($parametres['manoeuvre']=="Chaînette" || $parametres['manoeuvre']=="Treuil Renvoi Intérieur" || $parametres['manoeuvre']=="Treuil sous Coffre")
     $ret ="-";
   else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire")
     $ret ="NS11000SA - 5 Nm";
   else if ($parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret ="NS11000MA - 5 Nm";
  }
  else if ($produit=="Coffre 95 - 3 faces"){
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire")
     $ret ="NM15000SA - 8 Nm";
   else if ($parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret ="NM15000MA - 8 Nm";
  }
  else if($produit=="Coffre 95 - 4 faces"){
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret ="NM15000MA - 8 Nm";
   else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur" || $parametres['manoeuvre']=="Treuil sous Coffre")
     $ret ="-";
  }
  else if ($produit=="Droit"){
   if($parametres['manoeuvre']=="Cordon" || $parametres['manoeuvre']=="Treuil Renvoi Intérieur" || $parametres['manoeuvre']=="Treuil sous Coffre")
     $ret ="-";
   else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire")
     $ret ="NS11000SA - 5 Nm";
   else if ($parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret ="NS11000MA - 5 Nm";
  }
  return $ret;
}
/*
-------------------------------------------------
 Retourne Déduction BARRE DE CHARGE
-------------------------------------------------*/
function get_deduction_barre_charge($parametres) {
  $produit=$parametres['produit'];		
  if($parametres['typebarrecharge']=="Apparente") {
   if($parametres['guidage']=="Câble 1mm"){
     if($parametres['manoeuvre']=="Chaînette")
      $ret=-40; 
		  else if($parametres['manoeuvre']=="Treuil sous Coffre")
      $ret=-50;  
     }
	  else if($parametres['guidage']=="Coulisses 23 x 50"){
		  if($produit=="Coffre 55"||$produit=="Rouleau"){
      $ret=-30; 
		  }
		  else if ($produit=="Droit"||$produit=="Coffre 75 - 4 faces"||$produit=="Coffre 75 - 3 faces") 
		   $ret=-20;
		}
	  else if($parametres['guidage']=="Non Guidé"){
		  if($parametres['manoeuvre']=="Chaînette") {
      $ret=-40;
     }
     else if($parametres['manoeuvre']=="Cordon")
      $ret=-70; 
     else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
			  if ($produit=="Coffre 75 - 4 faces"||$produit=="Coffre 75 - 3 faces"||$produit=="Droit") 
		     $ret=-50;	
		   else if ($produit=="Coffre 95 - 3 faces"||$produit=="Coffre 95 - 4 faces")        $ret=-60;	
		  }
		  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
		   $ret=-50;		
			}
	  }
  }else if($parametres['typebarrecharge']=="Fourreau") {
	  if ($produit=="Coffre 55"||$produit=="Coffre 75 - 4 faces"||$produit=="Coffre 75 - 3 faces"){
     $ret=-50;
   }
   else if ($produit=="Coffre 95 - 3 faces") 
     $ret=-60;	
   else if ($produit=="Coffre 95 - 4 faces") {
		  if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio")
      $ret=-60;
     else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
      if($parametres['guidage']=="Câble 3 mm")
        $ret=-50;
      else if($parametres['guidage']=="Coulisses 36 x 21")
        $ret=-60;
     }
     else if($parametres['manoeuvre']=="Treuil sous Coffre") {
      if($parametres['guidage']=="Câble 3 mm" || $parametres['guidage']=="Non Guidé")
        $ret=-50;
      else if($parametres['guidage']=="Coulisses 36 x 21")
        $ret=-60;
     }
	 }
	 else if ($produit=="Droit") {
	   if($parametres['manoeuvre']=="Cordon"){
      if($parametres['guidage']=="Câble 3 mm")
       $ret=-85; 
      else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Non Guidé")
       $ret=-70;
    }
		 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"||$parametres['manoeuvre']=="Treuil Renvoi Intérieur"||$parametres['manoeuvre']=="Treuil sous Coffre"){
      if($parametres['guidage']=="Câble 3 mm")
       $ret=-60; 
      else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Non Guidé")
       $ret=-50;
    }
   }
	 else if ($produit=="Rouleau")
	   $ret=-50;
  }
  return $ret;
}
/*
-------------------------------------------------
 Retourne BARRE DE CHARGE (L en mm)	
-------------------------------------------------*/
function get_barre_charge_l($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   $ret=$parametres['largeur']+get_deduction_barre_charge($parametres);
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Nombre Morceaux 400 mm	
-------------------------------------------------*/
function get_nb_morceau400($parametres) {
  $barre_charge_l = get_barre_charge_l($parametres);
  if($barre_charge_l<1200)
   $ret = 1;
  else
   $ret = 2;
  return $ret;
}

/*
-------------------------------------------------
 Retourne TYPE LEST	
-------------------------------------------------*/
function get_type_lest($parametres) {
  $barre_charge_ref=get_barre_charge_ref($parametres);
  if($barre_charge_ref=="11 x 21 Alu")
   $ret="-";
  else if($barre_charge_ref=="14 x 28 Alu")
   $ret="Carré 10 x 10 Acier";
  else if($barre_charge_ref=="41 x 10 Alu")
   $ret="Plat 25 x 6 Acier";
  else if($barre_charge_ref=="Diam 18 Acier")
   $ret="Diam 14 Acier";
  return $ret;
}

/*
-------------------------------------------------
 Retourne BARRE DE CHARGE (Réf)	
-------------------------------------------------*/
function get_barre_charge_ref($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   if($produit=="Coffre 55"|| $produit=="Rouleau"){
	   if($parametres['typebarrecharge']=="Apparente") 
		  $ret="11 x 21 Alu";
		 else if ($parametres['typebarrecharge']=="Fourreau")
		  $ret="Diam 18 Acier";			
	 }	
	 else if($produit=="Coffre 75 - 3 faces"||$produit=="Coffre 75 - 4 faces"||$produit=="Droit") {
	   if($parametres['typebarrecharge']=="Apparente") {
		  if($parametres['guidage']=="Coulisses 23 x 50")
			  $ret="41 x 10 Alu";
			else if($parametres['guidage']=="Non Guidé")
			  $ret="14 x 28 Alu";	
		 }
		 else if ($parametres['typebarrecharge']=="Fourreau")
		  $ret="Diam 18 Acier";	
	 }
	 else if($produit=="Coffre 95 - 3 faces"||$produit=="Coffre 95 - 4 faces"){
	   if($parametres['typebarrecharge']=="Apparente")
		  $ret="14 x 28 Alu"; 
		 else if ($parametres['typebarrecharge']=="Fourreau")
		  $ret="Diam 18 Acier";	
	 }
  }
  return $ret;
}
/*
-------------------------------------------------
 Retourne CABLE GUIDAGE Qté	
-------------------------------------------------*/
function get_cable_guidage_qte($parametres) {
  if(get_type_cable($parametres)=="-")
   $ret ="-";
  else 
   $ret =$parametres['qte']*2;
  return $ret;
}

/*
-------------------------------------------------
 Retourne CABLE GUIDAGE H en mm	
-------------------------------------------------*/
function get_cable_guidage_h($parametres) {
  if(get_type_cable($parametres)=="-")
   $ret ="-";
  else 
   $ret =get_deduction_cable($parametres)+$parametres['hauteur'];
  return $ret;
}

/*
-------------------------------------------------
 Retourne le type cable	
-------------------------------------------------*/
function get_type_cable($parametres) {
  if($parametres['guidage']=="Câble 1mm")
   $ret ="1mm";
  else if($parametres['guidage']=="Câble 3 mm")
   $ret ="3 mm";
  else 
   $ret ="-";
  return $ret;
}

/*
-------------------------------------------------
 Retourne AXE ENROULEMENT (L en mm)	
-------------------------------------------------*/
function get_axe_enroulement_l($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
  	 $ret=$parametres['largeur']+get_deduction_axe_enroulement($parametres);
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne AXE ENROULEMENT (Réf)	
-------------------------------------------------*/
function get_axe_enroulement_ref($parametres) {
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   if($parametres['largeur']<=1800) {
	   if($parametres['hauteur']<=2200) {
	    $ret=get_axe_enroulement_1($parametres);   
		 } 	
		 else {
		  $ret=get_axe_enroulement_2($parametres); 	
		 }
	 }	
	 else
	   $ret=get_axe_enroulement_2($parametres);
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne la largeur store (en mm)	
-------------------------------------------------*/
function get_largeurstore($parametres) {
  return $parametres['largeur'];
}

/*
-------------------------------------------------
 Retourne Hauteur	
-------------------------------------------------*/
function get_hauteur($parametres) {
  return $parametres['hauteur'];
}

/*
-------------------------------------------------
 Retourne Hauteur de commande	
-------------------------------------------------*/
function get_hauteurcommande($parametres) {
  return $parametres['hauteurcommande'];
}

/*
-------------------------------------------------
 Retourne Côté Cde	
-------------------------------------------------*/
function get_cotecde($parametres) {
  return $parametres['cotecommande'];
}

/*
-------------------------------------------------
 Retourne POSE INT / EXT
-------------------------------------------------*/
function get_pose_emballage($parametres) {
  return get_localisation($parametres)."/".$parametres['typepose'];
}

/*
-------------------------------------------------
 Retourne Référence RAL	
-------------------------------------------------*/
function get_reference_ral($parametres) {
  return $parametres['coloris_laquage'];
}

/*
-------------------------------------------------
 Retourne 4° FACE Auvent Face Arrière (Réf)	
-------------------------------------------------*/
function get_auvent_ref($parametres) {
  $produit=$parametres['produit'];	
  if($parametres['auventstoredroit']=="Oui")
   $ret = "Auvent";
  else {
   $produit=$parametres['produit'];
   if($produit=="Coffre 55" || $produit=="Coffre 75 - 3 faces"|| $produit=="Coffre 95 - 3 faces"|| $produit=="Droit")
     $ret="-";
   else if($produit=="Coffre 75 - 4 faces")
     $ret="4° Face 75";
   else if($produit=="Coffre 95 - 4 faces")
     $ret="4° Face 95";
   else if($produit=="Rouleau")
     $ret="Sup Arr";
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne COFFRE L (en mm)	
-------------------------------------------------*/
function get_coffre_l($parametres) {
  if(get_coffre_ref($parametres)=="-")
   $ret ="-";
  else  
   $ret = $parametres['largeur']-5;
  return $ret;
}

/*
-------------------------------------------------
 Retourne COFFRE Réf	
-------------------------------------------------*/
function get_coffre_ref($parametres) {
  $produit=$parametres['produit'];
  $ret = "-";
  $produit = strtolower($produit);
  if (StartsWith($produit,"coffre"))
   $ret = substr($produit, strlen("coffre")+1, 2);
  return $ret;
}

/*
-------------------------------------------------
 Retourne JOUES COFFRES (Réf)
-------------------------------------------------*/
function get_joues_coffres_ref($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   if ($produit=="Droit"||$produit=="Rouleau")
	   $ret="-";	
   else if($produit=="Coffre 55") {
	   if($parametres['manoeuvre']=="Chaînette"){
		  if($parametres['guidage']=="Non Guidé"||$parametres['guidage']=="Câble 1mm") {
			  if($parametres['typepose']=="Tableau")
	       $ret="55 - JOUE 2";
			  else
			   $ret="55 - JOUE 1";	
			}
			else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50")
			  $ret="55 - JOUE 3";	
		 }	 
		 else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") { 
		  if($parametres['cotecommande']=="Gauche")
			  $ret="55 - JOUE 9"; 
			else if($parametres['cotecommande']=="Droite")
			  $ret="55 - JOUE 8";
		 }
		 else if($parametres['manoeuvre']=="Treuil sous Coffre") { 
		  if($parametres['guidage']=="Non Guidé"||$parametres['guidage']=="Câble 1mm") {
		    if($parametres['typepose']=="Tableau"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="55 - JOUE 6";
				 else
				   $ret="55 - JOUE 7";	
			  }
			  else {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="55 - JOUE 4";
				 else
				   $ret="55 - JOUE 5";	
			  }  
			}
			else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50") {
			  if($parametres['cotecommande']=="Gauche")
			   $ret="55 - JOUE 8";
			  else
				 $ret="55 - JOUE 9";	
			}	
		 }
	 }
	 else if ($produit=="Coffre 75 - 3 faces") {
	   if($parametres['guidage']=="Câble 3 mm") {
		  if($parametres['typepose']=="Tableau")
			  $ret="75 - JOUE 15";	
			else if($parametres['typepose']=="Face") {
			  if($parametres['localisationpose']=="Extérieure")
			   $ret="75 - JOUE 17";	
			  else if($parametres['localisationpose']=="Intérieure")
			   $ret="75 - JOUE 14";
			}
			else if($parametres['typepose']=="Plafond") {
			  if($parametres['localisationpose']=="Extérieure")
			   $ret="75 - JOUE 18";	
			  else if($parametres['localisationpose']=="Intérieure")
			   $ret="75 - JOUE 14";
			}
		 }
		 else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50")
		  $ret="75 - JOUE 16";
		 else if($parametres['guidage']=="Non Guidé"){
		  if($parametres['typepose']=="Tableau")
	      $ret="75 - JOUE 15";
			else
			  $ret="75 - JOUE 14";	
		 }	
	 }
	 else if ($produit=="Coffre 75 - 4 faces") {
	   if($parametres['manoeuvre']=="Chaînette"){
	    if($parametres['guidage']=="Non Guidé"||$parametres['guidage']=="Câble 3 mm") {
	      if($parametres['typepose']=="Tableau")
	       $ret="75 - JOUE 2";
			  else
			   $ret="75 - JOUE 1";		
			}
			else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50")	
			  $ret="75 - JOUE 3";
		 }	
		 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire") {
		  if($parametres['guidage']=="Câble 3 mm") {
		  	if($parametres['typepose']=="Face"){
		  	  if($parametres['localisationpose']=="Extérieure")
			     $ret="75 - JOUE 17";	
			    else if($parametres['localisationpose']=="Intérieure")
			     $ret="75 - JOUE 14";	
		  	}
		  	else if($parametres['typepose']=="Plafond"){
		  	  if($parametres['localisationpose']=="Extérieure")
			     $ret="75 - JOUE 18";	
			    else if($parametres['localisationpose']=="Intérieure")
			     $ret="75 - JOUE 14";	
		  	}
		  	else if($parametres['typepose']=="Tableau")
	       $ret="75 - JOUE 15";
			}
			else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50")	
			  $ret="75 - JOUE 16";	
			else if($parametres['guidage']=="Non Guidé"){
			  if($parametres['typepose']=="Tableau")
	       $ret="75 - JOUE 15";
			  else
			   $ret="75 - JOUE 14";	
			}
		 }
		 else if($parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
		  if($parametres['typepose']=="Tableau")
	      $ret="75 - JOUE 15";
			else
			  $ret="75 - JOUE 14";	  	
		 }
		 else if($parametres['manoeuvre']=="Treuil sous Coffre") {
		  if($parametres['guidage']=="Câble 3 mm") {
		    if($parametres['typepose']=="Face"||$parametres['typepose']=="Plafond"){
			   if($parametres['cotecommande']=="Gauche")
			     $ret="75 - JOUE 4";
			   else
				   $ret="75 - JOUE 5";	
			  }	
			  else if($parametres['typepose']=="Tableau"){
			   if($parametres['cotecommande']=="Gauche")
			     $ret="75 - JOUE 6";
			   else
				   $ret="75 - JOUE 7";	
			  }	
			}	 
		  else if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50"){
			  if($parametres['cotecommande']=="Gauche")
			   $ret="75 - JOUE 8";
			  else
			   $ret="75 - JOUE 9";	
			}
			else if($parametres['guidage']=="Non Guidé"){
			  if($parametres['typepose']=="Face"||$parametres['typepose']=="Plafond"){
			   if($parametres['cotecommande']=="Gauche")
			     $ret="75 - JOUE 4";
			   else
				   $ret="75 - JOUE 5";	
			  }	
			  else if($parametres['typepose']=="Tableau"){
			   if($parametres['cotecommande']=="Gauche")
			     $ret="75 - JOUE 6";
			   else
				   $ret="75 - JOUE 7";	
			  }	
			}
		 }
	 }
	 else if ($produit=="Coffre 95 - 3 faces"){
	   if($parametres['guidage']=="Câble 3 mm") {
	   	if($parametres['typepose']=="Face"){
			  if($parametres['localisationpose']=="Extérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 10";
				 else
				   $ret="95 - JOUE 9";	
			  } 
			  else if($parametres['localisationpose']=="Intérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 9";
				 else
				   $ret="95 - JOUE 10";	
			  } 	
			}
			else if($parametres['typepose']=="Plafond"){
			  if($parametres['localisationpose']=="Extérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 12";
				 else
				   $ret="95 - JOUE 11";	
			  } 
			  else if($parametres['localisationpose']=="Intérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 11";
				 else
				   $ret="95 - JOUE 12";	
			  } 	
			}
			else if($parametres['typepose']=="Tableau"){
			  if($parametres['localisationpose']=="Extérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 14";
				 else
				   $ret="95 - JOUE 13";	
			  } 
			  else if($parametres['localisationpose']=="Intérieure"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 13";
				 else
				   $ret="95 - JOUE 14";	
			  } 	
			}
		 }
		 else if($parametres['guidage']=="Non Guidé"){
		  if($parametres['typepose']=="Face"){
		    if($parametres['cotecommande']=="Gauche")
			   $ret="95 - JOUE 9";
			  else
				 $ret="95 - JOUE 10";	
			}
			else if($parametres['typepose']=="Plafond"){
		    if($parametres['cotecommande']=="Gauche")
			   $ret="95 - JOUE 11";
			  else
				 $ret="95 - JOUE 12";	
			}
			else if($parametres['typepose']=="Tableau"){
		    if($parametres['cotecommande']=="Gauche")
			   $ret="95 - JOUE 13";
			  else
				 $ret="95 - JOUE 14";	
			}	
		 }	
		 else if($parametres['guidage']=="Coulisses 36 x 21"){
		  if($parametres['typepose']=="Face" || $parametres['typepose']=="Tableau"){
		    if($parametres['localisationpose']=="Extérieure"){
		     if($parametres['cotecommande']=="Gauche")
			     $ret="95 - JOUE 16";
			   else
			     $ret="95 - JOUE 15";		
			  }
			  else if($parametres['localisationpose']=="Intérieure"){
		     if($parametres['cotecommande']=="Gauche")
			     $ret="95 - JOUE 15";
			   else
			     $ret="95 - JOUE 16";		
			  }	
		  }
				
		 }
	 }
	 else if($produit=="Coffre 95 - 4 faces"){
	   if($parametres['guidage']=="Câble 3 mm") {
	    if($parametres['typepose']=="Face"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['localisationpose']=="Extérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 10";
				   else
				    $ret="95 - JOUE 9";	
			   } 
			   else if($parametres['localisationpose']=="Intérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 9";
				   else
				    $ret="95 - JOUE 10";	
			   } 	
			  }
			  else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 4";
				 else
				   $ret="95 - JOUE 3";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 1";
				 else
				   $ret="95 - JOUE 2";	
			  }
			}	
			else if($parametres['typepose']=="Plafond"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['localisationpose']=="Extérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 12";
				   else
				    $ret="95 - JOUE 11";	
			   } 
			   else if($parametres['localisationpose']=="Intérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 11";
				   else
				    $ret="95 - JOUE 12";	
			   } 	
			  }
			  else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 2";
				 else
				   $ret="95 - JOUE 1";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 3";
				 else
				   $ret="95 - JOUE 4";	
			  }
			}
			else if($parametres['typepose']=="Tableau"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['localisationpose']=="Extérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 14";
				   else
				    $ret="95 - JOUE 13";	
			   } 
			   else if($parametres['localisationpose']=="Intérieure"){
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 13";
				   else
				    $ret="95 - JOUE 14";	
			   } 	
			  }
			  else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 6";
				 else
				   $ret="95 - JOUE 5";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 5";
				 else
				   $ret="95 - JOUE 6";	
			  }
			}
		 }	
		 else if($parametres['guidage']=="Non Guidé"){
		  if($parametres['typepose']=="Face"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 9";
				 else
				   $ret="95 - JOUE 10";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 1";
				 else
				   $ret="95 - JOUE 2";	
			  }
			}
			else if($parametres['typepose']=="Plafond"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 11";
				 else
				   $ret="95 - JOUE 12";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 3";
				 else
				   $ret="95 - JOUE 4";	
			  }
			}
			else if($parametres['typepose']=="Tableau"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 13";
				 else
				   $ret="95 - JOUE 14";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 5";
				 else
				   $ret="95 - JOUE 6";	
			  }
			}	
		 }
		 else if($parametres['guidage']=="Coulisses 36 x 21"){
		  if($parametres['typepose']=="Face"||$parametres['typepose']=="Tableau"){
	      if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire"||$parametres['manoeuvre']=="Motorisé 220 volts - Radio"){
	       if($parametres['localisationpose']=="Extérieure"){	 
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 16";
				   else
				    $ret="95 - JOUE 15";
				 }	
				 else if($parametres['localisationpose']=="Intérieure"){	 
			     if($parametres['cotecommande']=="Gauche")
				    $ret="95 - JOUE 15";
				   else
				    $ret="95 - JOUE 16";
				 }
			  }
			  else if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 8";
				 else
				   $ret="95 - JOUE 7";	
			  }
			  else if($parametres['manoeuvre']=="Treuil sous Coffre") {
			   if($parametres['cotecommande']=="Gauche")
				   $ret="95 - JOUE 7";
				 else
				   $ret="95 - JOUE 8";	
			  }
			}
	  	 }
	 }
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne JOUES COFFRES (Qté Nbe de Paires)
-------------------------------------------------*/
function get_joues_coffres_qte($parametres) {
  if(get_joues_coffres_ref($parametres)=="-")
   $ret="-";	
  else
   $ret=get_qte($parametres);	
  return $ret;
}

/*
-------------------------------------------------
 Retourne TIGES MANIVELLES (Réf)
-------------------------------------------------*/
function get_tiges_manivelles_ref($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   if($parametres['manoeuvre']=="Treuil Renvoi Intérieur"||$parametres['manoeuvre']=="Treuil sous Coffre")
     $ret="Diam 13";
   else
     $ret="-";
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne TIGES MANIVELLES (H en mm)
-------------------------------------------------*/
function get_tiges_manivelles_h($parametres) {
  $produit=$parametres['produit'];	
  if(get_coffre_ref($parametres)=="")
   $ret="";
  else {
   if(get_tiges_manivelles_ref($parametres)=="-")
	   $ret="-";
	 else
	   $ret=$parametres['hauteurcommande']+get_deduction_manivelle($parametres);	
  }
  return $ret;	
}

/*
-------------------------------------------------
 Retourne L tube 13 en mm
-------------------------------------------------*/
function get_l_tube_13($parametres) {
  if($parametres['batiment']=="")
   $ret ="";
  else if(get_tiges_manivelles_ref($parametres)=="Diam 13")
   $ret =get_tiges_manivelles_h($parametres);
  else  
   $ret ="-";
  return $ret;
}

/*
-------------------------------------------------
 Retourne Type Treuil
-------------------------------------------------*/
function get_type_treuil($parametres) {
  $produit=$parametres['produit'];
  if($parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
   if($produit=="Coffre 55")
	   $ret ="48 nu"; 
	 else
	   $ret ="58 nu";
  }
  else if($parametres['manoeuvre']=="Treuil sous Coffre"){
   if($produit=="Coffre 55")
	   $ret ="48 cardan 9,9 monté"; 
	 else
	   $ret ="58 cardan 9,9 monté";
  }
  else  
   $ret ="-";
  return $ret;
}

/*
-------------------------------------------------
 Retourne Coloris Manivelle
-------------------------------------------------*/
function get_coloris_manivelle($parametres) {
  $reference_ral = get_reference_ral($parametres);
  if(get_type_treuil($parametres)=="-")
   $ret = "-";
  else {
   if($reference_ral) { 
     $ral = charge_un("select treuil from ral where ref_ral = '$reference_ral'"); 
     $ret = $ral[treuil];
   }
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Coloris Pièces Stores ROULEAUX
-------------------------------------------------*/
function get_coloris_pieces_rouleaux($parametres) {
  if($parametres['colorispieces']=="")
   $ret = "-";
  else 
   $ret = $parametres['colorispieces'];
  return $ret;
}

/*
-------------------------------------------------
 Retourne Manœuvre Cordon Qté
-------------------------------------------------*/
function get_manoeuvre_cordon_qte($parametres) {
  if(get_manoeuvre_cordon_l($parametres)=="-")
   $ret = "-";
  else {
   $ret = get_qte($parametres);
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Manœuvre CORDON L en mm 
-------------------------------------------------*/
function get_manoeuvre_cordon_l($parametres) {
  if($parametres['manoeuvre']=="Cordon"){
   $ret =$parametres['hauteurcommande']+ $parametres['hauteur']+200;
  }
  else {
   $ret = "-";
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Manœuvre Chainette Qté
-------------------------------------------------*/
function get_manoeuvre_chainette_qte($parametres) {
  if(get_manoeuvre_chainette_l($parametres)=="-")
   $ret = "-";
  else {
   $ret = get_qte($parametres);
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Coloris chainette
-------------------------------------------------*/
function get_coloris_chainette($parametres) {
  if($parametres['manoeuvre']!="Chaînette")
   $ret = "-";
  else if($parametres['colorispieces']!="-") 
   $ret = $parametres['colorispieces'];
  else {
   $reference_ral = get_reference_ral($parametres);
   if($reference_ral) { 
     $ral = charge_un("select chainette from ral where ref_ral = $reference_ral"); 
     $ret = $ral[chainette];
   }
  }
  return $ret;
}


/*
-------------------------------------------------
 Retourne Déduction AXE ENROULEMENT	
-------------------------------------------------*/
function get_deduction_axe_enroulement($parametres) {
  $produit=$parametres['produit'];	
  if($produit=="Coffre 55") {
   if($parametres['manoeuvre']=="Chaînette") {
     $ret=-32;	
   }
   else
     $ret=-35;
  }else if ($produit=="Coffre 75 - 3 faces") {
   $ret=-31;
  }else if ($produit=="Coffre 75 - 4 faces") {
	 if($parametres['manoeuvre']=="Chaînette") {
	   $ret=-34;	
	 }
	 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
		 $ret=-31;	
	 }
	 else
		 $ret=-40;		
  }
  else if ($produit=="Coffre 95 - 3 faces") {
   $ret=-43;
  }
  else if ($produit=="Coffre 95 - 4 faces") {
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret=-43;
	 } 
	 else 
	   $ret=-40;	
  }
  else if ($produit=="Droit") {
   if($parametres['manoeuvre']=="Cordon" || $parametres['manoeuvre']=="Treuil Renvoi Intérieur") {
	   $ret=-55;	
	 }
	 else if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio") {
	   $ret=-40;
	 } 
	 else
	   $ret=-45;
  }
  else if ($produit=="Rouleau") {
   $ret=-30;
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Déduction 4° FACE / ARRIERE	
-------------------------------------------------*/
function get_deduction_4f($parametres) {
  $produit=$parametres['produit'];	
  if($produit=="Rouleau")	
   $ret=0;
  else if ($produit=="Coffre 55" || $produit=="Droit")
   $ret="";
  else
   $ret=-5; 
  return $ret;
}

/*
-------------------------------------------------
 Retourne Déduction COULISSES
-------------------------------------------------*/
function get_deduction_coulisses($parametres) {
  $produit=$parametres['produit'];	
  if($parametres['guidage']=="Câble 1mm"||$parametres['guidage']=="Câble 3 mm"||$parametres['guidage']=="Non Guidé")
   $ret="";
  else {
   if($parametres['guidage']=="Coulisses 23 x 17"||$parametres['guidage']=="Coulisses 23 x 50") {
	   if($produit=="Coffre 55")
		  $ret=-55;
		 else if ($produit=="Coffre 75 - 4 faces"||$produit=="Coffre 75 - 3 faces") 
		  $ret=-80;
		 else if ($produit=="Droit")
		  $ret=-95;
		 else if ($produit=="Rouleau")
		  $ret=-45;	
	 }
	 else if($parametres['guidage']=="Coulisses 36 x 21") {
	   if ($produit=="Coffre 95 - 3 faces"||$produit=="Coffre 95 - 4 faces")          $ret=-100;	
	 }
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Déduction Toile Hauteur	
-------------------------------------------------*/
function get_deduction_toile_h($parametres) {
  $produit=$parametres['produit'];
  if ($produit=="Coffre 55"||$produit=="Rouleau")
   $ret=150; 
  else if ($produit=="Coffre 75 - 3 faces"||$produit=="Coffre 95 - 3 faces"||$produit=="Coffre 95 - 4 faces"||$produit=="Droit")
   $ret=200;
  else if($produit=="Coffre 75 - 4 faces"){
   if($parametres['manoeuvre']=="Motorisé 220 volts - Filaire" || $parametres['manoeuvre']=="Motorisé 220 volts - Radio")
     $ret=200; 
   else
     $ret=150;
  }
  return $ret;
}


/*
-------------------------------------------------
 Retourne Manœuvre CHAINETTE L en mm 
-------------------------------------------------*/
function get_manoeuvre_chainette_l($parametres) {
  if($parametres['manoeuvre']=="Chaînette"){
   $ret =($parametres['hauteurcommande']-50)* 2;
  }
  else {
   $ret = "-";
  }
  return $ret;
}

/*
-------------------------------------------------
 Retourne Manœuvre TREUIL Qté
-------------------------------------------------*/
function get_manoeuvre_treuil_qte($parametres) {
	if(get_coloris_manivelle($parametres)=="-")
   $ret = "-";
	else {
   $ret = get_qte($parametres);
	}
	return $ret;
}
function get_prixVenteTtc($parametres) {
	return strtr($parametres['prixventettc'],",",".");
}

/*
--------------------------------------------------------------------------------------------------
************************ IMPLEMENTATION DE CHAMPS POUR COMMANDES & OF ****************************
--------------------------------------------------------------------------------------------------*/

/*
-------------------------------------------------
 Retourne nomclient_fdc
-------------------------------------------------*/
function get_nomclient_fdc($parametres) {
    return $parametres['nomclient_fdc'];
}

/*
-------------------------------------------------
 Retourne num_commande
-------------------------------------------------*/
function get_num_commande_fdc($parametres) {
    return $parametres['numcommande_fdc'];
}

/*
-------------------------------------------------
 Retourne date_cde
-------------------------------------------------*/
function get_date_cde($parametres) {
    return $parametres['date_cde'];
}

/*
-------------------------------------------------
 Retourne statut (Créé/Imprimé)
-------------------------------------------------*/
function get_statut($parametres) {
    return $parametres['statut'];
}

/*
-------------------------------------------------
 Retourne date_exp
-------------------------------------------------*/
function get_date_exp($parametres) {
    return $parametres['date_exp'];
}

/*
-------------------------------------------------
 Retourne origine
-------------------------------------------------*/
function get_origine($parametres) {
    return $parametres['origine'];
}

/*
-------------------------------------------------
Genere num_commande_fdc_commande
-------------------------------------------------*/
function set_num_commande_fdc_commande($parametres) {
    return (substr(get_date_cde($parametres),0,4)."-".substr(get_date_cde($parametres),5,2)."-SOD".substr("0000000".$parametres['id_dataset_entete'],-7));
}

/*
-------------------------------------------------
 Retourne statut_commande
-------------------------------------------------*/
function set_statut_commande($parametres) {
    $produit=$parametres['produit'];
    $tab = array("Store Banne", "Store Plisse Vertical", "Store Plisse Horizontal", "Coffre 53 exterieur", "Coffre 53 interieur", "Coffre 70 exterieur",
        "Coffre 70 interieur", "Coffre 95 exterieur", "Coffre 95 interieur", "Rail SLV Motorise", "Store Banne Coffre ELISSE",
        "Store Banne Coffre COMPACTO", "Store Banne Monobloc HAWAI", "Film Exterieur", "Film Interieur", "Coussin", "Embrases",
        "Enrouleur exterieur", "Enrouleur", "Moustiquaire", "Paroi", "Rideau bicolore", "Rideau monocolore", "SLV lames seules",
        "SLV rails seuls", "SLV trapeze", "Store Bateau", "Store Lames Verticales", "Tringle", "Velux", "Venitien ALU 25 mm",
        "Venitien BOIS 50 mm");
    if(in_array($produit, $tab))
        $ret=1;
    else
        $ret='0';
    return $ret;
}

/*
-------------------------------------------------
 Retourne fournisseur_commande
-------------------------------------------------*/
function set_fournisseur_commande($parametres) {
   return req_sim("
		select
			id_fournisseur
		from 
			produit_origine
		where
			produit = '".$parametres[produit]."'", "id_fournisseur");
}

/*
-------------------------------------------------
 Retourne Repere 
-------------------------------------------------*/
function get_repere($parametres) {
    if($parametres['repere'] != "") {
        return $parametres['repere'];
    }else{
        return "-";
    }
}



/** Bon de commande  **/

/* Retourne le produit */
function get_produit_bc($parametres){
    return $parametres['produit'];
}

/* Retourne le repère */
function get_repere_bc($parametres){
    return $parametres['repere'];
}

/* Retourne la largeur */
function get_largeur_bc($parametres){
    return $parametres['largeur'];
}

/* Retourne la hauteur */
function get_hauteur_bc($parametres){
    return $parametres['hauteur'];
}


/* Retourne le type de commande */
function get_type_commande_bc($parametres){

    if($parametres['manoeuvre_70_interieur']) {
        return $parametres['manoeuvre_70_interieur'];
    }elseif ($parametres['manoeuvre_70_exterieur']) {
        return $parametres['manoeuvre_70_exterieur'];
    }elseif ($parametres['manoeuvre_53_exterieur']) {
        return $parametres['manoeuvre_53_exterieur'];
    }elseif ($parametres['manoeuvre_53_interieur']) {
        return $parametres['manoeuvre_53_interieur'];
    }elseif ($parametres['manoeuvre']) {
        return $parametres['manoeuvre'];
    }elseif ($parametres['typedecommande_rsm']) {
        return $parametres['typedecommande_rsm'];
    }elseif ($parametres['typedecommande_sbcc']) {
        return $parametres['typedecommande_sbcc'];
    }elseif ($parametres['typedecommande_sbcl']) {
        return $parametres['typedecommande_sbcl'];
    }elseif ($parametres['typedecommande_sbmo']) {
        return $parametres['typedecommande_sbmo'];
    }elseif ($parametres['type_commande_st_pl_h']) {
        return $parametres['type_commande_st_pl_h'];
    }elseif ($parametres['type_commande_st_pl_v']) {
        return $parametres['type_commande_st_pl_v'];
    }

}

/* Retourne le coté de commande */
function get_cote_commande_bc($parametres){

    if($parametres['cotecommande_70_interieur']) {
        return $parametres['cotecommande_70_interieur'];
    }elseif ($parametres['cotecommande_70_exterieur']) {
        return $parametres['cotecommande_70_exterieur'];
    }elseif ($parametres['cotecommande_53_exterieur']) {
        return $parametres['cotecommande_53_exterieur'];
    }elseif ($parametres['cotecommande_53_interieur']) {
        return $parametres['cotecommande_53_interieur'];
    }elseif ($parametres['cotecommande']) {
        return $parametres['cotecommande'];
    }elseif ($parametres['cotedecommande_rsm']) {
        return $parametres['cotedecommande_rsm'];
    }elseif ($parametres['cotedecommande_sbcc']) {
        return $parametres['cotedecommande_sbcc'];
    }elseif ($parametres['cotedecommande_sbcl']) {
        return $parametres['cotedecommande_sbcl'];
    }elseif ($parametres['cote_de_commande_sbmo']) {
        return $parametres['cote_de_commande_sbmo'];
    }elseif ($parametres['cote_commande']) {
        return $parametres['cote_commande'];
    }

}

/* Retourne la hauteur de la commande*/
function get_hauteur_commande_bc($parametres){

    if($parametres['hauteurcommande']) {
        return $parametres['hauteurcommande'];
    }elseif ($parametres['hauteur_commande']) {
        return $parametres['hauteur_commande'];
    }

}

/* Retourne le type de pose*/
function get_type_pose_bc($parametres){

    if($parametres['typepose_70_interieur']) {
        return $parametres['typepose_70_interieur'];
    }elseif ($parametres['typepose_70_exterieur']) {
        return $parametres['typepose_70_exterieur'];
    }elseif ($parametres['typepose_53_exterieur']) {
        return $parametres['typepose_53_exterieur'];
    }elseif ($parametres['typepose_53_interieur']) {
        return $parametres['typepose_53_interieur'];
    }elseif ($parametres['typepose']) {
        return $parametres['typepose'];
    }elseif ($parametres['typedepose_rsm']) {
        return $parametres['typedepose_rsm'];
    }elseif ($parametres['typedepose_sbcc']) {
        return $parametres['typedepose_sbcc'];
    }elseif ($parametres['typedepose_sbcl']) {
        return $parametres['typedepose_sbcl'];
    }elseif ($parametres['typedepose_sbmo']) {
        return $parametres['typedepose_sbmo'];
    }elseif ($parametres['type_pose_st_pl_h']) {
        return $parametres['type_pose_st_pl_h'];
    }elseif ($parametres['type_pose_st_pl_v']) {
        return $parametres['type_pose_st_pl_v'];
    }

}

/* Retourne le guidage */
function get_guidage_bc($parametres){

    if($parametres['guidage_70_interieur']) {
        return $parametres['guidage_70_interieur'];
    }elseif ($parametres['guidage_70_exterieur']) {
        return $parametres['guidage_70_exterieur'];
    }elseif ($parametres['guidage_53_exterieur']) {
        return $parametres['guidage_53_exterieur'];
    }elseif ($parametres['guidage_53_interieur']) {
        return $parametres['guidage_53_interieur'];
    }elseif ($parametres['guidage']) {
        return $parametres['guidage'];
    }elseif ($parametres['guidage_st_pl']) {
        return $parametres['guidage_st_pl'];
    }

}

/* Retourne le type de barre de charge */
function get_type_barre_charge_bc($parametres){

    if($parametres['typebarrecharge_70_interieur']) {
        return $parametres['typebarrecharge_70_interieur'];
    }elseif ($parametres['typebarrecharge_70_exterieur']) {
        return $parametres['typebarrecharge_70_exterieur'];
    }elseif ($parametres['typebarrecharge_53_exterieur']) {
        return $parametres['typebarrecharge_53_exterieur'];
    }elseif ($parametres['typebarrecharge_53_interieur']) {
        return $parametres['typebarrecharge_53_interieur'];
    }elseif ($parametres['typebarrecharge']) {
        return $parametres['typebarrecharge'];
    }

}

/* Retourne la toile */
function get_toile_bc($parametres){

    if($parametres['gammetoilecoffreinterieur']) {
        $champ = $parametres['gammetoilecoffreinterieur'];
    }elseif ($parametres['gammetoilecoffreexterieur']) {
        $champ = $parametres['gammetoilecoffreexterieur'];
    }elseif ($parametres['referencetoile']) {
        return $parametres['referencetoile'];
    }elseif ($gamme = $parametres['gamme']) {
        return $gamme;
    }

    $valeur="-";
    if($champ && is_numeric($champ)){
        $req = new db_sql("select * from toile where id_toile = ".$champ);
        while ($req->n()) {
            $valeur = $req->f(lb_toile_atelier);
        }
    }
    return $valeur;

}

/* Retourne le type de la toile */
function get_type_toile_bc($parametres){

    if($parametres['typetoile_70i']) {
        return $parametres['typetoile_70i'];
    }elseif ($parametres['typetoile_70e']) {
        return $parametres['typetoile_70e'];
    }elseif ($parametres['typetoile_53i']) {
        return $parametres['typetoile_53i'];
    }

}

/* Retourne la sortie cable moteur*/
function get_sortie_cable_moteur_bc($parametres){

    if($parametres['sortiecablemoteur_70i']) {
        return $parametres['sortiecablemoteur_70i'];
    }elseif ($parametres['sortiecablemoteur_70e']) {
        return $parametres['sortiecablemoteur_70e'];
    }elseif ($parametres['sortiecablemoteur_95e']) {
        return $parametres['sortiecablemoteur_95e'];
    }elseif ($parametres['sortiecablemoteur_95i']) {
        return $parametres['sortiecablemoteur_95i'];
    }

}

/* Retourne l'enroulement de la toile*/
function get_enroulement_toile_bc($parametres){

    if($parametres['enroulement_toiles_70_interieur']) {
        return $parametres['enroulement_toiles_70_interieur'];
    }elseif ($parametres['enroulement_toiles_70_exterieur']) {
        return $parametres['enroulement_toiles_70_exterieur'];
    }elseif ($parametres['enroulement_toiles_53_interieur']) {
        return $parametres['enroulement_toiles_53_interieur'];
    }elseif ($parametres['enroulement_toiles']) {
        return $parametres['enroulement_toiles'];
    }

}

/* Retourne RAL */
function get_coloris_laquage_bc($parametres){

    if($parametres['coloris_laquage']) {
        return $parametres['coloris_laquage'];
    }

}

/* Retourne la quantité */
function get_quantite($parametres) {
	if($parametres['qte']){
		return $parametres['qte'];
    }elseif ($parametres['quantite_e']) {
	    return $parametres['quantite_e'];
    }else {
        return "-";
    }

}

/* Retourne la quantité */
function get_quantite_e($parametres) {
    if($parametres['quantite_e']) {
        return $parametres['quantite_e'];
    }else {
        return "-";
    }
}

/* Retourne la quantité */
function get_moto4($parametres) {
    return $parametres['moto4'];
}

/* Retourne le type de refoulement  */
function type_de_refoulement_bc($parametres) {
    if($parametres['typederefoulement_rsm']){
        return $parametres['typederefoulement_rsm'];
    }
}

/* Retourne le numéro de département */
function get_departement($parametres){
	if($parametres['origine'] == 3) {
		if($departement = $parametres['affaire']){

			$valeur="-";
			if($departement && is_numeric($departement)){
				$req = new db_sql("
						SELECT 
						  client.Cp
						FROM 
						  affaire, 
						  client, 
						  contact
						WHERE 
						  affaire.Id_contact = contact.Id_contact AND
						  contact.Id_client = client.Id_client
						AND 
						  Id_affaire = ".$departement);
				while ($req->n()) {
					$valeur = $req->f(Cp);
				}
			}
			return substr($valeur, 0, 2) ;
		}
	} else {
		return substr($parametres['code_departement'], 0, 2);
	}
}


/*
-------------------------------------------------
 Retourne DOUBLURE RIDEAU IVOIRE
-------------------------------------------------*/

function get_doublure_rideau($parametres) {
	return $parametres['doublure_rideau'];
}

/*
-------------------------------------------------
 Retourne CONFECTION RIDEAU
-------------------------------------------------*/

function get_confection_rideau_of($parametres) {

	$confection = $parametres['confection_rideau'];
	//S'il s'agit bien de rideau
	if ($confection == 'PRE PLISSE 40%' and $parametres['long_rideau_mono']) {
		$grande_laize = charge('select max(laize) from article where id_toile = '.$parametres['toile_rideau_bico_corps'].$parametres['toile_rideau_mono']);
		$grande_laize = $grande_laize['0']['max(laize)']/100;
		$ajout_total = 3/100;
		if (substr($parametres['produit'],0,8)=="Voilages")
			$ajout_total += 5/100;
		if ($parametres['doublure_rideau']!="|" and $parametres['doublure_rideau']!="") {
			$ajout_doublure = 3/100;
		}


		if ($GLOBALS[origine]!=3) {
			$largeur = 1.4 * get_largeur_rideau($parametres) + $ajout_total + $ajout_doublure;
			if ($largeur > $grande_laize) {
				$largeur = 1.2 * get_largeur_rideau($parametres) + $ajout_total + $ajout_doublure;
				// if ($largeur <= $grande_laize and $parametres["origine"]==1) { // La décote de 40% à 20% ne doit avoir lieu que pour stores et rideaux
				if ($largeur <= $grande_laize) { // "C'est une connerie" dixit Stéphane le 01/06/2015
					$confection = 'PRE PLISSE 20%';
				}
			}
		}
	}
	return $confection;
}

/*-------------------------------------------------
 Retourne Déduction CABLES
-------------------------------------------------*/

function get_deduction_cable($parametres) {
	if($parametres['guidage']=="Câble 1mm"||$parametres['guidage']=="Câble 3 mm")
		$ret ="150";
	else
		$ret ="-";
	return $ret;
}

/*

-------------------------------------------------
 Retourne ENROULEMENT ET FOUREAU (en mètres)
-------------------------------------------------*/

function get_enroulement_fourreau() {
	return 0.2;
}

/*

-------------------------------------------------
 Retourne Main d'Oeuvre COUPE TOILE
-------------------------------------------------*/

function get_coupe_toile_main_doeuvre($parametres) {
	return round((get_hauteur_m($parametres)+get_largeur_m($parametres))* get_coupe_toile_cout_unitaire(),2);
}

/*

-------------------------------------------------
 Retourne Main d'Oeuvre CONFECTION HAUTE TOILE
-------------------------------------------------*/

function get_confection_haute_main_doeuvre($parametres) {
	if(get_confection_haute($parametres)=="Fourreau + Jonc 3mm"){
		$ret= round(get_largeur_m($parametres)*(get_preparation_fourreau_cout_unitaire()+get_soudure_fourreau_cout_unitaire()),2);
	}
	else
		$ret=0;
	return $ret;
}

/*

-------------------------------------------------
 Retourne Main d'Oeuvre CONFECTION BASSE TOILE
-------------------------------------------------*/

function get_confection_basse_main_doeuvre($parametres) {
	if(get_confection_basse($parametres)=="Fourreau 40 mm"){
		$ret= round(get_largeur_m($parametres)*(get_preparation_fourreau_cout_unitaire()+get_soudure_fourreau_cout_unitaire()),2);
	}
	else
		$ret=0;
	return $ret;
}

/*

-------------------------------------------------
 Retourne ACHAT TOILE
-------------------------------------------------*/

function get_achat_toile($parametres) {

	if(get_hauteur_m($parametres)+get_enroulement_fourreau()<= get_laize_calcul($parametres)/1000)
		$ret=get_largeur_m($parametres) * get_laize_calcul($parametres)/1000 * get_prix_m2($parametres);
	else if(get_largeur_m($parametres)<= get_laize_calcul($parametres)/2000){
		$ret=get_laize_calcul($parametres)/2000*(get_enroulement_fourreau()+get_hauteur_m($parametres))*get_prix_m2($parametres);
	}
	else if(get_largeur_m($parametres)<= get_laize_calcul($parametres)/1000){
		$ret=(get_hauteur_m($parametres)+get_enroulement_fourreau())*get_laize_calcul($parametres)/1000*get_prix_m2($parametres);
	}
	else if(get_hauteur_m($parametres)+get_enroulement_fourreau()<=1.5*get_laize_calcul($parametres)/1000)
		$ret=get_largeur_m($parametres)*1.5*get_laize_calcul($parametres)/1000*get_prix_m2($parametres);
	else
		$ret=(get_hauteur_m($parametres)+get_laize_calcul($parametres)/1000)*get_laize_calcul($parametres)/1000*get_largeur_m($parametres)*get_prix_m2($parametres);

	return round($ret,2);
}

/*

------------------------------------------------
 Retourne Montant SODICLAIR
-------------------------------------------------*/

function get_montant_sodiclair() {
	return 0.36;
}

/*

-------------------------------------------------
 Retourne Préparation Fourreau- Coût Unitaire
-------------------------------------------------*/

function get_preparation_fourreau_cout_unitaire() {
	return get_preparation_fourreau_duree() * get_montant_sodiclair();
}

/*

-------------------------------------------------
 Retourne Soudure Fourreau - Coût Unitaire
-------------------------------------------------*/

function get_soudure_fourreau_cout_unitaire() {
	return get_soudure_fourreau_duree() * get_montant_sodiclair();
}

/*

-------------------------------------------------
 Retourne TOTAL PRS Unitaire TOILE + MAIN ŒUVRE
-------------------------------------------------*/

function get_total_prix_unitaire($parametres) {
	return get_achat_toile($parametres) + get_coupe_toile_main_doeuvre($parametres)+get_confection_haute_main_doeuvre($parametres) + get_confection_basse_main_doeuvre($parametres)+ get_emballage_cout_unitaire();
}

/*

-------------------------------------------------
 Retourne Coupe Toile - Coût Unitaire
-------------------------------------------------*/

function get_coupe_toile_cout_unitaire() {
	return get_coupe_toile_duree() * get_montant_sodiclair();
}

/*

-------------------------------------------------
 Retourne Emaballage - Coût Unitaire
-------------------------------------------------*/

function get_emballage_cout_unitaire() {
	return get_emballage_duree() * get_montant_sodiclair();
}

/*

-------------------------------------------------
 Retourne Emballage - Durée
-------------------------------------------------*/

function get_emballage_duree() {
	return 1;
}

/*

-------------------------------------------------
 Retourne Coupe Toile - Durée
-------------------------------------------------*/

function get_coupe_toile_duree() {
	return 1;
}

/*
-------------------------------------------------
 Retourne Préparation Fourreau - Durée
-------------------------------------------------*/

function get_preparation_fourreau_duree() {
	return 2;
}

/*

-----------------------------------------------
 Retourne Soudure Fourreau - Durée
------------------------------------------------*/

function get_soudure_fourreau_duree() {
	return 1.5;
}

/*

-------------------------------------------------
 Retourne Laize Calcul
-------------------------------------------------*/

function get_laize_calcul($parametres) {

	$gammetoile = get_gammetoile($parametres);
	if($gammetoile=="COPACCO NATTE 380")
		$ret = 2500;
	else if($gammetoile=="COPACCO SERGE 600")
		$ret = 2500;
	else if($gammetoile=="COPACCO SERGE OCCULTANT")
		$ret = 1500;
	else if($gammetoile=="DECO S")
		$ret = 2500;
	else if($gammetoile=="HEXCEL 1303")
		$ret = 1830;
	else if($gammetoile=="HEXCEL LYVERMOON 11891")
		$ret = 1370;
	else if($gammetoile=="MERMET - FLOCKE 11201")
		$ret = 2000;
	else if($gammetoile=="MERMET - KARELIS 11301 & 11302")
		$ret = 2600;
	else if($gammetoile=="MERMET - M-SCREEN 8503 - 3%")
		$ret = 2500;
	else if($gammetoile=="MERMET - M-SCREEN 8505 - 5%")
		$ret = 2500;
	else if($gammetoile=="MERMET - OBION 1123")
		$ret = 2000;
	else if($gammetoile=="SOLSCAPE A")
		$ret = 1800;
	else if($gammetoile=="SOLSCAPE E1")
		$ret = 1800;
	else if($gammetoile=="SOLSCAPE E2")
		$ret = 1800;
	else if($gammetoile=="SOLTIS 92")
		$ret = 1770;
	else if($gammetoile=="SOLTIS 99")
		$ret = 1770;
	else if($gammetoile=="SOLTIS B 92N")
		$ret = 1770;
	else if($gammetoile=="VEROFLEX 5023")
		$ret = 2400;
	else if($gammetoile=="VEROSAFE 12264")
		$ret = 2350;
	else if($gammetoile=="VERSO 3")
		$ret = 2500;
	return $ret;
}

/*

-------------------------------------------------
 Retourne COULISSES (Perçage)
-------------------------------------------------*/

function get_coulisses_percage($parametres) {
	$produit=$parametres['produit'];
	if(get_coffre_ref($parametres)=="")
		$ret="";
	else {
		if(get_coulisses_ref($parametres)=="-")
			$ret="-";
		else if (StartsWith(strtolower($parametres['guidage']),"coulisses")) {
			if($parametres['typepose']=="Face")
				$ret="Face";
			else if($parametres['typepose']=="Tableau")
				$ret="Fond";
			else if($parametres['typepose']=="Plafond")
				$ret="Vérifier";
		}
		else
			$ret="-";
	}
	return $ret;
}

/*

-------------------------------------------------
 Retourne COULISSES (Qté)
-------------------------------------------------*/

function get_coulisses_qte($parametres) {

	$produit=$parametres['produit'];
	if(get_coffre_ref($parametres)=="")
		$ret="";
	else {
		if(get_coulisses_ref($parametres)=="-")
			$ret="-";
		else
			$ret = get_qte($parametres) * 2;
	}
	return $ret;
}



/*-------------------------------------------------
 Retourne COULISSES (H en mm)
-------------------------------------------------*/

function get_coulisses_h($parametres) {
	
	$produit=$parametres['produit'];
	if(get_coffre_ref($parametres)=="")
		$ret="";
	else {
		if(get_coulisses_ref($parametres)=="-")
			$ret="-";
		else
			$ret = $parametres['hauteur']+get_deduction_coulisses($parametres);	 }
	return $ret;
}

/*-------------------------------------------------
 Retourne COULISSES (Réf)
-------------------------------------------------*/

function get_coulisses_ref($parametres) {
	
	$produit=$parametres['produit'];
	if(get_coffre_ref($parametres)=="")
		$ret="";
	else {
		if($parametres['guidage']=="Câble 1mm"||$parametres['guidage']=="Non Guidé"||$parametres['guidage']=="Câble 3 mm")
			$ret="-";
		else if (StartsWith(strtolower($parametres['guidage']),"coulisses"))
			$ret = substr($parametres['guidage'], strlen("coulisses")+1, strlen($parametres['guidage'])- strlen("coulisses"));
	}
	return $ret;
}



/*-------------------------------------------------
 Retourne Largeur en m
-------------------------------------------------*/

function get_largeur_m($parametres) {
	return $parametres['largeur']/1000;
}



/*-------------------------------------------------
 Retourne Hauteur en m
-------------------------------------------------*/

function get_hauteur_m($parametres) {
	return $parametres['hauteur']/1000;
}

?>