<?
// paramètres à passer en millimètre
$largeur_net_coupe = 100;
$hauteur_net_coupe = 150;
$nb_morceaux = 5;
$orientable = true;
$laizes[] = 100;
$laizes[] = 150;
$laizes[] = 250;
$laize_principale = 250;

$pas_laize = 1; // Doit impérativement être entier

function essai($sens,$largeur_de_travail,$hauteur_de_travail,$nb_morceaux,$laize) {
   $nb_par_ligne = floor($laize/$largeur_de_travail);
   if ($nb_par_ligne > 0) {
      $nb_lignes = ceil($nb_morceaux/$nb_par_ligne);
      $taille_a_commander = $nb_lignes*$hauteur_de_travail;
      $solution ['nb de morceaux '.$sens] = $nb_morceaux;
      $solution ['nb de morceaux '.$sens.' par rangée'] = $nb_par_ligne;      
      $solution ['nb de rangées de morcaux '.$sens] = $nb_lignes;      
      $solution [taille_a_commander] = $taille_a_commander;
      return ($solution);
   } else {
      return false;
   }
}

foreach ($laizes as $i => $laize) {
   //essai dans le sens normal
   $solution = essai("droits",$largeur_net_coupe,$hauteur_net_coupe,$nb_morceaux,$laize);
   if (is_array($solution)) {
      $solutions[$laize.''][] = $solution;
   }
   if ($orientable) {
      //essai transverse
      $solution = essai("transverses",$hauteur_net_coupe,$largeur_net_coupe,$nb_morceaux,$laize);
      if (is_array($solution)) {
         $solutions[$laize.''][] = $solution;
      }
      //essais mixtes
      for ($laize_droit = $pas_laize; ($laize_droit + $pas_laize) < $laize; $laize_droit = $laize_droit + $pas_laize) {
         for ($nb_morceaux_droit = 1; $nb_morceaux_droit < $nb_morceaux; $nb_morceaux_droit++) {

            $solution1 = essai("droits",$largeur_net_coupe,$hauteur_net_coupe,$nb_morceaux_droit,$laize_droit);
            if (is_array($solution1)) {
               //echo ($laize_droit)."-".($nb_morceaux_droit)."<BR>";
               $solution2 = essai("transverses",$hauteur_net_coupe,$largeur_net_coupe,$nb_morceaux-$nb_morceaux_droit,$laize - $laize_droit);
               if (is_array($solution2)) {
                  $taille_a_commander = max ($solution1[taille_a_commander], $solution2[taille_a_commander]);
                  $solution = array_merge ($solution1, $solution2);
                  $solution [taille_a_commander] = $taille_a_commander;
                  $solutions[$laize.''][] = $solution;
               }
            }
         }   
      }
   }
}

foreach ($solutions as $laize => $liste_solution) {
   $meilleur_taille_a_commander = 1000000;
   foreach ($liste_solution as $i => $solution) {
      if ($solution[taille_a_commander] < $meilleur_taille_a_commander) {
         $meilleur_taille_a_commander = $solution[taille_a_commander];
         $meilleur_solution = $solution;   
      }
   }
   $meilleur_solutions[$laize] = $meilleur_solution;    
}

print_r($meilleur_solutions);

?>