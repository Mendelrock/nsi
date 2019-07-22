<?
if (!$call_by_include) {
   require_once("ress/register_globals.php");
   register_globals('gp');
}

require_once("c_parm.php");
require_once("ress/util.php");
require_once("ress/db_mysql.php");

if(!$call_by_include || ($call_by_include && $val=="")) $stylevide = "style='background-color:#FF8C8C'";
$html .= '<select ' . $stylevide .' ' .$modifiable.' name="champ_toile_sous'.$lb_champ.'" onchange= "getElementById('."'".'champ_ouvert_'.$id_unique_ajax.'_'.$lb_champ.''."'".').value = this.options[this.selectedIndex].value;">';
//Affichage des valeurs
$html .= "<option value=''>Choisissez</option'>";

if($origine=="3") $where = " and FDV=1";
else if($origine) $where = " and `IN`=1";

$req = new db_sql("select couleur from toile where gamme = '$gamme' $where");
while ($req->n()) {
	 //$val_courante = $req->f('reference_gamme').'-'.$req->f('reference_couleur');
   	 $val_courante = $req->f('couleur');
   if($val == $val_courante) {
      $selected = "SELECTED";
   } else {
      $selected = "";
   }
   $html .= "<option value='".$val_courante."' $selected>".$req->f('couleur')."</option'>";
}
$html .= '</select>';
if (!$call_by_include) echo $html;

?>
