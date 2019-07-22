<?

$champs[numcommande_fdc][libelle] = 'N° de Commande ou BCC';
$champs[numcommande_fdc][type] = 'ouvert';
$champs[numcommande_fdc][longueur] = 20;
$champs[numcommande_fdc][tablecible] = 'affaire';
$champs[numcommande_fdc][champscible] = 'Num_bcc';
$champs[numcommande_fdc][nonmodifiable] = (($_SESSION[id_profil]==11) or ($_SESSION[id_profil]==3))?0:1;

?>