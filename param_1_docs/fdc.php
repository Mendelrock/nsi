<?
$type_docs[fdc][libelle] = "Feuille de cotes";

$type_docs[fdc][type_dataset_entete] = "fdcentete";

//$type_docs[fdc][btn][email][php]   = "javascript:window.open('fdc_send_mail.php?id_doc=. id_doc');void(0);";
//$type_docs[fdc][btn][email][libelle] = "Envoyer la Feuille de cotes";
//$type_docs[fdc][btn][email][droit] = '($_SESSION[id_droit]["com"])';

$type_docs[fdc][btn][delete][php]   = "affaire_detail.php?Id_affaire=. id_affaire&id_doc=. id_doc&supr=yes";
$type_docs[fdc][btn][delete][libelle] = "Supprimer la Feuille de cotes";
$type_docs[fdc][btn][delete][droit] = 'check_statut_en_cours($id_doc)';

$type_docs[fdc][btn][imprimer][php]   = "javascript:window.open('imprimer_fdc.php?id_doc=. id_doc');void(0);";
$type_docs[fdc][btn][imprimer][libelle] = "Imprimer la Feuille de cotes";

$type_docs[fdc][btn][imprimer_of][php]   = "javascript:window.open('imprimer_ofs.php?id_fdc=. id_doc');void(0);";
$type_docs[fdc][btn][imprimer_of][libelle] = "Imprimer les OF";
$type_docs[fdc][btn][imprimer_of][droit] = ' (droit_utilisateur("Menu.OF")) and (($GLOBALS[parms][champs][statut][ordre][$dataset->ochamp[statut]->get()])>=5)';

$type_docs[fdc][btn][sav][php]   = "fdc_dupliquer.php?mode=dupliquer&id_doc=. id_doc";
$type_docs[fdc][btn][sav][libelle] = "Dupliquer partiellement pour SAV";
$type_docs[fdc][btn][sav][complement_dedans] = " data-toggle=\"modal\" data-target=\"#myModal\" ";
$type_docs[fdc][btn][sav][complement_dehors] = "fdc_dupliquer_modal.php";
$type_docs[fdc][btn][sav][droit] = "(droit_feuille_de_cote('En cours')==2) and (droit_utilisateur('fdc_dupliquer_sav'))";

$type_docs[fdc][btn][modifier][php]   = "fdc_dupliquer.php?mode=modifier&id_doc=. id_doc";
$type_docs[fdc][btn][modifier][libelle] = "Modifier partiellement pour correction";
$type_docs[fdc][btn][modifier][complement_dedans] = " data-toggle=\"modal\" data-target=\"#myModal\" ";
$type_docs[fdc][btn][modifier][complement_dehors] = "fdc_dupliquer_modal.php";//$type_docs[fdc][btn][dupliquer][onclick] = "javascript: return confirm('Voulez vous vraiment dupliquer ?');";
$type_docs[fdc][btn][modifier][droit] = '(droit_feuille_de_cote("En cours")==2) and (droit_feuille_de_cote($dataset->ochamp[statut]->get())>=1) and (($GLOBALS[parms][champs][statut][ordre][$dataset->ochamp[statut]->get()])>=5) and droit_utilisateur("fdc_dupliquer_cor")';

$type_docs[fdc][btn][affaire][php]   = "affaire_detail.php?Id_affaire=. id_affaire";
$type_docs[fdc][btn][affaire][libelle] = "Ouvrir l'affaire";
$type_docs[fdc][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs[fdc][doclies][1]="OF - DEPART PEINTURE";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][1]="Coffre 55";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][2]="Coffre 75 - 3 faces";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][3]="Coffre 75 - 4 faces";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][4]="Coffre 95 - 3 faces";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][5]="Coffre 95 - 4 faces";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][6]="Droit";
$type_docs[fdc][doclies]['OF - DEPART PEINTURE'][produits][7]="Rouleau";
?>