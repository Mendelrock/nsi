<?
/*
Paramètres :
$nb : nombre de niveaux
$req : requete de rupture

Variables de travail :
$critere [0..$nb] (0 peut servir de critère de base)
$valeur  [0..?]

Fonctions à définir :
affect_valeur ();
coeur()
autant de fonctions
   rupture_hn
   rupture_bn
qu'il y a de niveaux de rupture
*/

function ouvre($rupture) {
   for ($i=$rupture; $i>0; $i--) {
      $proc = 'rupture_h'.$i;
      $proc();
   }
}

function ferme($rupture) {
   for ($i=1; $i<=$rupture; $i++) {
      $proc = 'rupture_b'.$i;
      $proc();
   }
}

$critere = array();
$first_rupture = true;
While ($req->n()) {
   $critere_sauve = $critere;
   $valeur_sauve  = $valeur;
   affect_valeur ();
   $rupture=$nb;
   while (($rupture>0) and ($critere[$rupture] == $critere_sauve[$rupture])) {
      $rupture--;
   }
   if ($rupture > 0) {
      $critere_sauve_sauve = $critere;
      $valeur_sauve_sauve  = $valeur;
      $critere = $critere_sauve;
      $valeur  = $valeur_sauve;
      if (!$first_rupture) {
         ferme($rupture);
      }
      $first_rupture = false;
      $critere = $critere_sauve_sauve;
      $valeur  = $valeur_sauve_sauve;
      ouvre($rupture);
   }
   coeur();
}
ferme ($nb);
?>