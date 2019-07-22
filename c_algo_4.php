<?
// paramètres à passer en millimètre

// Doit impérativement être entier

$parm_defaut ='
// mettre autant de array qu il y a de morceaux à couper
$situation[acouper] = array (
                           array("hauteur"=>47,
                                 "largeur"=>60,
                                 "repere"=>"repère 2",
                                 "nombre"=>1),
                           array("hauteur"=>45,
                                 "largeur"=>60,
                                 "repere"=>"repère 3",
                                 "nombre"=>1),
                           array("hauteur"=>120,
                                 "largeur"=>60,
                                 "repere"=>"repère 4",
                                 "nombre"=>1),
                           array("hauteur"=>320,
                                 "largeur"=>320,
                                 "repere"=>"repère 5",
                                 "nombre"=>1)                                 
                                );
                                
// mettre une des valeurs laize, oppose, neutre)
$orientation = "neutre";  

// mettre une des valeurs hauteur, largeur
$sens_des_soudures = "hauteur"; 

// mettre 1 ligne par laize
$laizes[] = 100;
$laizes[] = 150;
$laizes[] = 250;
';
$parm_affiche = stripslashes($_POST[parm]);
$parm = stripslashes($_POST[parm]);
if (!$parm_affiche) $parm_affiche = $parm_defaut;
?>
Paramètres<BR>
**********<BR>
<form method = post><TEXTAREA NAME="parm" ROWS="30" COLS="80"><? echo $parm_affiche ?> </TEXTAREA><input type = submit></form> 
<?
if ($parm) {
?>
Résultats<BR>
*********<BR>
<?
eval($parm);


//Déclinaison du tableau des sens
$sens = array();
switch($orientation) {
case "neutre" :
   $senss[hauteur] = "hauteur";
   $senss[largeur] = "largeur";
   break;
case "laize" :
   $senss[largeur] = "largeur";
   break;
case "oppose" :
   $senss[hauteur] = "hauteur";
   break;
}   

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

function trace($x) {
   //echo "$x <BR>";
}

// Essaye de remplir au maximum le rectange de $hauteur sur $largeur avec les rectangles non encore coupés de $situation
function essaye_de_remplir($largeur,$hauteur,$situation,$alinea_trace="") {

   trace($alinea_trace."Essai de remplir le rectangle de largeur $largeur et de hauteur $hauteur avec un morceau restant à couper");

   // Trouve le 1er morceau qui rentre dans le rectangle
   // si largeur = -1000, c'est une nouvelle coupe
   $senss = array();
   foreach ($situation[acouper] as $nb_morceau => $morceau) {
      if (!$morceau[coupe]) {
         if (( (($morceau[largeur]*1) <= ($largeur*1)) or ($largeur == -1000) ) and ($morceau[hauteur]*1 <= $hauteur*1) and ($GLOBALS[senss][largeur])) {
            $senss[largeur] = "largeur";
            trace($alinea_trace."morceau N° ".$morceau[n]." (l ".$morceau[largeur]." * h ".$morceau[hauteur].") dans le sens largeur");
         }
         if (((($morceau[hauteur]*1) <= ($largeur*1)) or ($largeur == -1000)) and ($morceau[largeur]*1 <= $hauteur*1) and ($GLOBALS[senss][hauteur])) {
            $senss[hauteur] = "hauteur";
            trace($alinea_trace."morceau N° ".$morceau[n]." (l ".$morceau[largeur]." * h ".$morceau[hauteur].") dans le sens hauteur");
         }
         if (count($senss) > 0) {
            break;
         }
      }
   }
   
   // $nb_morceau contient le numéro de morceau le plus gros qui tient dans le rectangle passé en paramètre
   // $senss contient les sens possibles
   
   // Si pas de morceau trouvé
   if (count($senss) == 0) {
      trace($alinea_trace."Rien trouvé");
      return ($situation);
   } else {
      // Sinon j'essaye de remplir les autres en prenant les hypothèses de sens
      foreach ($senss as $sens) {
         $situationbis = $situation;
         // Si largeur = -1000, c'est une nouvelle coupe
         if ($largeur == -1000) {
         	  // Largeur courante = Largeur de la tentative (dans le sens courant)
            $largeur_courante = $morceau[$sens];
            // Initialisation des données de la coupe courante
            $situationbis[surface_active_coupe_courante]=0;
            $situationbis[coupe][$situationbis[coupe_courante]][largeur] = $morceau[$sens];
               // dont la largeur consommée cumulée
               $situationbis[largeur_totale] += $largeur_courante;
         } else {
         	  // Largeur courante = Largeur de la tentative (dans le sens courant)
            $largeur_courante = $largeur;
         }

         trace ($alinea_trace."Largeur du rectangle : ".$largeur_courante);
         // Le morceau n'est plus à couper
         $situationbis[acouper][$nb_morceau][coupe] = true;
         $situationbis[nb_acouper]--;
         // Je cumule la surface active
         $situationbis[surface_active_coupe_courante] += $morceau[hauteur] * $morceau[largeur];
         $situationbis[surface_active] += $morceau[hauteur] * $morceau[largeur];
         $situationbis[coupe][$situationbis[coupe_courante]][contenu][] = array("sens"=>$sens,"morceau"=>$morceau);
         // Essaye de remplir les chutes morceaux restants d'une manière
         $situationter = $situationbis;
         if ($hauteur > $morceau[contre($sens)])
            $situationter = essaye_de_remplir($largeur_courante,$hauteur-$morceau[contre($sens)],$situationter,$alinea_trace."&nbsp;");
         if ($largeur_courante > $morceau[$sens])
            $situationter = essaye_de_remplir($largeur_courante-$morceau[$sens],$morceau[contre($sens)],$situationter,$alinea_trace."&nbsp;");
         $situationter[ratio] = $situationter[surface_active_coupe_courante]/$largeur_courante;    
         $situation_resutat[] = $situationter;
         // Essaye de remplir les chutes morceaux restants de l'autre manière
         $situationter = $situationbis;
         if ($largeur_courante > $morceau[$sens])
            $situationter = essaye_de_remplir($largeur_courante-$morceau[$sens],$hauteur,$situationter,$alinea_trace."&nbsp;");
         if ($hauteur > $morceau[contre($sens)])
            $situationter = essaye_de_remplir($morceau[$sens],$hauteur-$morceau[contre($sens)],$situationter,$alinea_trace."&nbsp;");
         $situationter[ratio] = $situationter[surface_active_coupe_courante]/$largeur_courante;    
         $situation_resutat[] = $situationter;
      }
      // Renvoi de la meilleure situation (meilleur ratio)
      $meilleur_ratio = 0;
      foreach ($situation_resutat as $i => $situation_courante) {
         if ($situation_courante[ratio] > $meilleur_ratio) {
            $meilleur_ratio = $situation_courante[ratio];
            $meilleure_situation = $i; 
         }
      }
      return ($situation_resutat[$meilleure_situation]); 
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
      $GLOBALS[situation][nb_soudures]++;
      $taille = $taille+$GLOBALS[laize];
   }
   $new_morceau = $morceau;
   $new_morceau[$sens_des_soudures] = $morceau[$sens_des_soudures]-$taille;
   $GLOBALS[nouveau_acouper][] = $new_morceau;
   $asouder[] = count($GLOBALS[nouveau_acouper]) - 1;
   $GLOBALS[situation]['asouder'][] = $asouder;
   $GLOBALS[situation][nb_soudures]++;
   $GLOBALS[situation][nb_pieces_soudees]++;
}

$situationb = $situation;

foreach ($laizes as $i => $laize) {
      
   //Découpe les soudures / Coutures
   //*******************************
   $erreur = "";
   $nouveau_acouper = array();
   $situation[nb_soudures] = 0;
   $situation[nb_pieces_soudees] = 0;
   $situation = $situationb;
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
               decoupe($morceau, $sens_des_soudures);
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

   //Classement des morceaux par ordre de taille croissant
   //*****************************************************
   
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

   if ($erreur) {
      echo $erreur;
   }
   
   // Initialisation de la récurrence
   $situation[nb_acouper] = count($situation[acouper]);
   $situation[coupe_courante] = -1;
   $situation[surface_active] = 0;
   $situation[largeur_totale] = 0;

   while ($situation[nb_acouper]>0) {
      $situation[coupe_courante]++;
      $situation = essaye_de_remplir(-1000,$laize,$situation);
   }

   // Calcul des résultat
   $situation[nb_acouper] = count($situation[acouper]);
   $situation[resultat]["Tx de pieces à souder/coudre"] = floor($situation[nb_pieces_soudees]/$situation[nb_acouper]*100)." %";
   $situation[resultat]["Tx de soudure/couture"] = floor($situation[nb_soudures]/$situation[nb_acouper]*100)." %";
   $situation[resultat]["Largeur totale coupée"] = $situation[largeur_totale];
   $situation[resultat]["Surface utilisée"] = $situation[largeur_totale]*$laize;
   $situation[resultat]["Surface utile"] = $situation[surface_active];
   $situation[resultat]["Chute"] = $situation[resultat]["Surface utilisée"] - $situation[resultat]["Surface utile"];
   $situation[resultat]["Tx de chute"] = floor($situation[resultat]["Chute"] / $situation[resultat]["Surface utilisée"] * 100)." %";
   

echo "Laize $laize <BR>";
echo "&nbsp;&nbsp;Coupes : <BR>";
$sit = $situation['coupe'];
foreach ($sit as $i => $coupe) {
   echo "&nbsp;&nbsp;&nbsp;&nbsp;Coupe N° ".$i." de largeur : ".$coupe[largeur];
   echo "<BR>";
   foreach ($coupe["contenu"] as $i => $morc) {
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Morceau N° ";
      echo $morc["morceau"]["n"];
      echo " (repere : ";
      echo $morc["morceau"]["repere"];
      echo ") de hauteur ".$morc["morceau"][hauteur];
      echo " et de largeur ".$morc["morceau"][largeur];
      echo " dans le sens : ".$morc[sens];
      echo "<BR>";

   }
}
echo "&nbsp;&nbsp;Coutures/Soudures : <BR>";
$sit = $situation['asouder'];
if (is_array($sit)) {
foreach ($sit as $i => $coupe) {
   echo "&nbsp;&nbsp;&nbsp;&nbsp;souder les pieces ";
   foreach ($coupe as $x => $y) {
      echo "&nbsp;$y";
   }
   echo "<BR>";
}
}
echo "&nbsp;&nbsp;Conclusions : <BR>";
$sit = $situation['resultat'];
foreach ($sit as $i => $j) {
   echo "&nbsp;&nbsp;&nbsp;&nbsp; $i : $j ";
   echo "<BR>";
}

}
}
?>