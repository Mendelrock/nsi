<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<?
/* Export Excel GESPROD - Par commande(s) affichée(s) - */

if (!is_array($_SESSION)) {
    include ("../ress/var_session.php");
}
require_once("ress/db_mysql.php");
include ("ress/var_session.php");
require_once('ress/fpdf/fpdf.php');
require_once('ress/fpdf_js.php');
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");
include ("register_globals.php");
include 'ress/PHPExcel/IOFactory.php';
//register_globals('gp');
set_time_limit(0);

/** Error reporting */
//error_reporting(E_ALL);

/** Include path **/
ini_set('include_path', ini_get('include_path').';../Classes/');

/** Import PHPExcel */
include 'ress/PHPExcel.php';

/** Import PHPExcel_Writer_Excel2007 */
include 'ress/PHPExcel/Writer/Excel2007.php';

// Req
// Critere de recherche
if(trim($_SESSION[_champ_statut_fdc])){
    $requete.="dt.statut = '".trim($_SESSION[_champ_statut_fdc])."' AND ";
}

if(trim($_SESSION[origine_feuille])){
    $requete.="dt.origine = '".trim($_SESSION[origine_feuille])."' AND ";
}

$Date_com = $_SESSION[Date_com_fdc];
if($Date_com){
    $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) >= STR_TO_DATE('$Date_com', '%Y-%m-%d' ) AND ";
}

$Date_com_f= $_SESSION[Date_com_f_fdc];
if($Date_com_f){
    $requete.="STR_TO_DATE(dt.date_cde, '%Y-%m-%d' ) <= STR_TO_DATE('$Date_com_f', '%Y-%m-%d' )  AND ";
}
if($_SESSION[NumCommande]){
    $requete.="dt.numcommande_fdc like '%".$_SESSION[NumCommande]."%' AND ";
}
if($_SESSION[Affichage]){
    $limit = "LIMIT 0, ".$_SESSION[Affichage];
}

$req="
SELECT doc.id_doc
FROM zdataset_fdcentete dt,
  affaire,
  statut,
  client,
  doc
WHERE $requete
      type_doc = 'fdc'
      AND dt.id_dataset = doc.id_dataset_entete
      AND dt.prix_ht <> 0
      AND affaire.id_affaire = dt.affaire
      AND statut.Id_statut=affaire.Id_statut
      AND affaire.Id_client=client.Id_client
ORDER BY
   dt.numcommande_fdc DESC  $limit ";


// Creation objet  PHPExcel
// Chargement Template Excel
$objPHPExcel = PHPExcel_IOFactory::load("ress/excel/gesprod_model.xlsx");

// Initialisations propertiées
$objPHPExcel->getProperties()->setCreator($_SESSION[nom_utilisateur]);
$objPHPExcel->getProperties()->setLastModifiedBy("AGECLAIR");
$objPHPExcel->getProperties()->setTitle("GESPROD");
$objPHPExcel->getProperties()->setSubject("GESPROD");
$objPHPExcel->getProperties()->setDescription("GESPROD Commandes");


// Ajout de données

$objPHPExcel->setActiveSheetIndex(0);

$end = (int)$_SESSION[Affichage];

$req = new db_sql($req);


$num_rows = $req->num_rows();

$objPHPExcel->getActiveSheet()->insertNewRowBefore(9, $num_rows);

while($req->n()){
    $docs[] = $req->f("id_doc");
}
$dataset = new dataset($type_dataset_groupe_ligne);
$d = 0;
$i = 8;
$k=0;
foreach ($docs as $numcommande => $id_doc) {

    $numcmd_i = $numcommande+1;
    echo'<div class="alert alert-success"><strong>Info : </strong> Commande : '.$numcmd_i.'|'.$num_rows.' générée</div>';
    flush();

    $liste_produits = charge("
            SELECT
              *
            FROM
              zdataset_commandeentete
            WHERE
              zdataset_commandeentete.id_dataset
            IN (
                SELECT id_dataset_entete as id_ds
                FROM
                 doc
                WHERE
                 doc.id_doc = $id_doc
                UNION
                SELECT
                  id_dataset
                FROM
                  doc_ligne
                WHERE
                  doc_ligne.id_doc = $id_doc
                )");

    foreach ($liste_produits as $key => $champ) {

        if ($champ[champ] == 'numcommande_fdc') {
            if(strpos($champ[valeur], '-') == 3) {
                $numcommande = str_replace(substr($champ[valeur], 3, 12), " ", $champ[valeur]);
            }
            elseif(strpos($champ[valeur], '-') == 2) {
                $numcommande = str_replace(substr($champ[valeur], 2, 12), " ", $champ[valeur]);
            }
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . (string)$i, $numcommande);
        }
        if ($champ[champ] == 'date_cde') {
            $string=substr(str_replace('-','/',$champ[valeur]), 2,10);
            $date_tab = explode('/' , $string);
            $string  = $date_tab[2].'/'.$date_tab[1].'/'.$date_tab[0];
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . (string)$i, $string);
        }
        if ($champ[champ] == 'nomclient_fdc') {
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . (string)$i, utf8_encode($champ[valeur]));
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . (string)$i, '');
        if ($champ[champ] == 'qte_venitienalu25') {
            $value_va = $champ[valeur]+$value_va;
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . (string)$i, $value_va);
        }
        if ($champ[champ] == 'qte_venitienbois50') {
            $value_vb = $champ[valeur]+$value_vb;
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . (string)$i, $value_vb);
        }
        if ($champ[champ] == 'qte_enrouleur') {
            $value_enr1 = $champ[valeur]+$value_enr1;
            $objPHPExcel->getActiveSheet()->SetCellValue('H' . (string)$i, $value_enr1);
        }
        if ($champ[champ] == 'qte_enrouleur') {
            $value_enr2 = $champ[valeur]+$value_enr2;
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . (string)$i, $value_enr2);
        }
        if ($champ[champ] == 'fdc_velux_qte') {
            $value_velux1 = $champ[valeur]+$value_velux1;
            $objPHPExcel->getActiveSheet()->SetCellValue('J' . (string)$i, $value_velux1);
        }
        if ($champ[champ] == 'fdc_velux_qte') {
            $value_velux2 = $champ[valeur]+$value_velux2;
            $objPHPExcel->getActiveSheet()->SetCellValue('K' . (string)$i, $value_velux2);
        }
        if ($champ[champ] == 'qte_slv') {
            $value_slv1 = $champ[valeur]+$value_slv1;
            $objPHPExcel->getActiveSheet()->SetCellValue('L' . (string)$i, $value_slv1);
        }
        if ($champ[champ] == 'qte_slv') {
            $value_slv2 = $champ[valeur]+$value_slv2;
            $objPHPExcel->getActiveSheet()->SetCellValue('M' . (string)$i, $value_slv2);
        }
        if ($champ[champ] == 'qte_storebateau') {
            $value_bconf = $champ[valeur]+$value_bconf;
            $objPHPExcel->getActiveSheet()->SetCellValue('N' . (string)$i, $value_bconf);
        }
        if ($champ[champ] == 'qte_storebateau') {
            $value_bmeca = $champ[valeur]+$value_bmeca;
            $objPHPExcel->getActiveSheet()->SetCellValue('O' . (string)$i, $value_bmeca);
        }
        if ($champ[champ] == 'fdc_pa_qte') {
            $value_parois_j = $champ[valeur]+$value_parois_j;
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . (string)$i, $value_parois_j);
        }
        if ($champ[champ] == 'fdc_moustiquaire_qte') {
            $value_moust = $champ[valeur]+$value_moust;
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . (string)$i, $value_moust);
        }
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . (string)$i, '');
        if ($champ[champ] == 'qte_rideau_mono' || $champ[champ] == 'qte_rideau_of_bi') {
            $value_rideaux_coupe = $champ[valeur]+$value_rideaux_coupe;
            $objPHPExcel->getActiveSheet()->SetCellValue('T' . (string)$i, $value_rideaux_coupe);
        }
        if ($champ[champ] == 'qte_rideau_mono' || $champ[champ] == 'qte_rideau_of_bi') {
            $value_rideaux_couture = $champ[valeur]+$value_rideaux_couture;
            $objPHPExcel->getActiveSheet()->SetCellValue('U' . (string)$i, $value_rideaux_couture);
        }
        if ($champ[champ] == 'qte_tringle') {
            $value_tringle = $champ[valeur]+$value_tringle;
            $objPHPExcel->getActiveSheet()->SetCellValue('V' . (string)$i, $value_tringle);
        }
        if ($champ[champ] == 'qte_coussin') {
            $value_cousin = $champ[valeur]+$value_cousin;
            $objPHPExcel->getActiveSheet()->SetCellValue('W' . (string)$i, $value_cousin);
        }

        $objPHPExcel->getActiveSheet()->SetCellValue('X' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('Y' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('Z' . (string)$i, '');

        $objPHPExcel->getActiveSheet()->SetCellValue('AA' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AB' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AC' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AD' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AE' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AF' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AG' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AH' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AI' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AJ' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AK' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AL' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AM' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AN' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AO' . (string)$i, '');
        $objPHPExcel->getActiveSheet()->SetCellValue('AP' . (string)$i, 'E');
        $objPHPExcel->getActiveSheet()->SetCellValue('AQ' . (string)$i, 'FAB');
        if ($champ[champ] == 'prixventettc') {

            $price_ht = (float) $champ[valeur];

            $price_ht = ($price_ht*80/100);
            $price_ht = round($price_ht,2);

            $objPHPExcel->getActiveSheet()->SetCellValue('AR' . (string)$i, $price_ht);
        }
        if ($champ[champ] == 'agence') {
            $objPHPExcel->getActiveSheet()->SetCellValue('AS' . (string)$i, strtoupper($champ[valeur]));
        }
        if ($champ[champ] == 'date_exp') {
            $string=substr(str_replace('-','/',$champ[valeur]), 2,10);
            $date_tab = explode('/' , $string);
            $string  = $date_tab[2].'/'.$date_tab[1].'/'.$date_tab[0];
            $objPHPExcel->getActiveSheet()->SetCellValue('AT' . (string)$i, $string);
        }
    }
    unset($value_va);
    unset($value_vb);
    unset($value_enr1);
    unset($value_enr2);
    unset($value_velux1);
    unset($value_velux2);
    unset($value_slv1);
    unset($value_slv2);
    unset($value_bconf);
    unset($value_bmeca);
    unset($value_parois_j);
    unset($value_moust);
    unset($value_rideaux_coupe);
    unset($value_rideaux_couture);
    unset($value_tringle);
    unset($value_cousin);
    $i++;
}


// Nom feuille
$objPHPExcel->getActiveSheet()->setTitle('Gesprod');


// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("temp/Gesprod.xlsx");


// Redirection Téléchargement
echo '<script language="Javascript">
<!--
document.location.replace("temp/Gesprod.xlsx");
// -->
</script>';
?>