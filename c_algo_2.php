<?
// paramètres à passer en millimètre

// Doit impérativement être entier

// paramètres à passer en millimètre
//acouper
//coupe
//chutes

$situation['acouper'] = array (
                           array("hauteur"=>"48",
                                 "largeur"=>"60",
                                 "repere"=>"repère 2",
                                 "nombre"=>1),
                           array("hauteur"=>"47",
                                 "largeur"=>"60",
                                 "repere"=>"repère 2",
                                 "nombre"=>1),
                           array("hauteur"=>"45",
                                 "largeur"=>"60",
                                 "repere"=>"repère 2",
                                 "nombre"=>1),
                           array("hauteur"=>"120",
                                 "largeur"=>"60",
                                 "repere"=>"repère 2",
                                 "nombre"=>20)
                                );
                                
              
$orientation = "neutre"; // (laize/oppose/neutre)
$sens_des_soudures = "hauteur"; // (hauteur/largeur)
$sens[] = "hauteur";
$sens[] = "largeur";
$laizes[] = 100;
//$laizes[] = 150;
//$laizes[] = 250;
//$laize_principale = 250;

//Multiplication des morceaux
//***************************
$nouveau_acouper = array();
foreach ($situation['acouper'] as $i => $morceau) {
   for ($j = 1 ; $j <= $morceau[nombre] ; $j++) {
      $nouveau_acouper[] = $morceau; 
   }
}
$situation['acouper'] = $nouveau_acouper;

function contre($sens) {
   if ($sens == "largeur") {
      return ("hauteur");
   } else {
      return ("largeur");
   }
}

function recure($situation) {
   $morceaux_essaye = false;
   foreach ($situation[acouper] as $i => $morceau) {
      if (!$morceau[coupe]) {
         $morceaux_essaye = true;
         foreach ($GLOBALS["sens"] as $sens) {
            $situationbis = $situation;
            if ( $morceau[$sens] > ($GLOBALS[laize] - $situationbis[largeur_de_laize_courante_utilise]) ) {
               // Saute à la coupe suivante
               $situationbis[coupe_courante]++;
               $situationbis[largeur_de_laize_courante_utilise] = 0;
               $situationbis[largeur_laize_utilise] = $situationbis[largeur_laize_utilise] + $situationbis[largeur_coupe_courante];
               $situationbis[largeur_coupe_courante] = 0;
            }
            if ( $morceau[$sens] <= ($GLOBALS[laize] - $situationbis[largeur_de_laize_courante_utilise]) ) {
               $situationbis[acouper][$i][coupe] = true;
               $situationbis[largeur_de_laize_courante_utilise] += $morceau[$sens];
               $situationbis[coupe][$situationbis[coupe_courante]][] = array("n"=>$i,"sens"=>$sens);
               $situationbis[largeur_coupe_courante] = max($situationbis[largeur_coupe_courante],$morceau[contre($sens)]);
               recure($situationbis);
            }           
         }           
      }
   }   
   if (!$morceaux_essaye) {
      if ( (!$GLOBALS[meilleure_situation][largeur_laize_utilise]) or $GLOBALS[meilleure_situation][largeur_laize_utilise] > ($situation[largeur_laize_utilise]+$situation[largeur_coupe_courante])) {
         $situation[largeur_laize_utilise] += $situation[largeur_coupe_courante];
         $GLOBALS[meilleure_situation] = $situation;
      }
   }
}

function decoupe($morceau, $sens_des_soudures) {
   $sens_des_soudures = contre($sens_des_soudures);
   $asouder = array();
   for ($taille = 0 ; $morceau[$sens_des_soudures]-$taille > $GLOBALS[laize] ; "") {
      $new_morceau = $morceau;
      $new_morceau[$sens_des_soudures] = $GLOBALS[laize];
      $GLOBALS[nouveau_acouper][] = $new_morceau;
      $asouder[] = count($GLOBALS[nouveau_acouper]) - 1;
      $taille = $taille+$GLOBALS[laize];
   }
   $new_morceau = $morceau;
   $new_morceau[$sens_des_soudures] = $morceau[$sens_des_soudures]-$taille;
   $GLOBALS[nouveau_acouper][] = $new_morceau;
   $asouder[] = count($GLOBALS[nouveau_acouper]) - 1;
   $GLOBALS[situation]['asouder'][] = $asouder;
}

foreach ($laizes as $i => $laize) {
      
   //Découpe les soudures / Coutures
   //*******************************
   $erreur = "";
   $nouveau_acouper = array();
   foreach ($situation['acouper'] as $i => $morceau) {
      if ($orientation == "laize") {
         if ($morceau[hauteur] > $laize) {
            if ($sens_des_soudures == "hauteur") {
               $erreur = "Pas possible";
            } else {
               decoupe($morceau, $sens_des_soudures);
            }
         } else {
            $nouveau_acouper[] = $morceau;
         }         
      }
      if ($orientation == "oppose") {
         if ($morceau[largeur] > $laize) {
            if ($sens_des_soudures == "largeur") {
               $erreur = "Pas possible";
            } else {
               decoupe($morceau, $sens_des_soudures)  ;
            }
         } else {
            $nouveau_acouper[] = $morceau;
         }         
      }
      if ($orientation == "neutre") {
         if ( ($morceau[largeur] <= $laize) or ($morceau[hauteur] <= $laize) ) {
            $nouveau_acouper[] = $morceau;
         } else {
            decoupe($morceau, $sens_des_soudures);
         }
      }
   }
   $situation['acouper'] = $nouveau_acouper;

   $nouveau_acouper = array();
   $acouper = $situation['acouper'];
   while (count($nouveau_acouper)<count($acouper)) {
      $superficie = 0;
      foreach ($acouper as $i => $morceau) {
         if (!$morceau[trie]) {
            if (($morceau[hauteur]*$morceau[largeur]) > $superficie) {
               $superficie = ($morceau[hauteur]*$morceau[largeur]);
               $j = $i;
            }
         }
      }
      $acouper[$j][n] = $j;
      $nouveau_acouper[] = $acouper[$j];
      $acouper[$j][trie] = true;                
   }
   $situation['acouper'] = $nouveau_acouper;

   if ($erreur) {echo $erreur;}
   
   //recurence
   $situation[largeur_de_laize_courante_utilise] = 0;
   $situation[largeur_laize_utilise] = 0;
   $situation[largeur_coupe_courante] = 0;
   $situation[coupe_courante] = 0;
   recure($situation);
   
}

print_r($meilleure_situation);


?>