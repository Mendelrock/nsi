<?
define('ECHANTILLON',"Echantillon");
//if (!(function_exists("droit_utilisateur"))) {
//include('db_mysql.php');
/*-----------------------------------------------
             BIBLIOTEHQUE
-----------------------------------------------*/
/*-----------------------------------------------
       Gestion des droits Utilisateurs
-------------------------------------------------
  Cette fonction teste si l'utilisateur
  de la session a le droit requis, renvoie TRUE si
  le droit existe.
  $code est le code à tester dans le tableau
  $_SESSION[id_droit]
  Cette fonction peut-être appelée par le code
  applicatif ou par une autre fonction.
-------------------------------------------------*/
function getOrigine($champ,$code) {
   if ($code==1) return $champ;
   else return "";
}
function num_encode($nombre) {
	return str_replace(',','.',$nombre);
}
function droit_utilisateur($code) {
		if ($code == 'O')
			return true;
		if (($_SESSION[id_droit][$code])) {
         return true;
      }
      return false;
}

/*
-------------------------------------------------
  Vérifie si une chaine commence par une valeur précise
-------------------------------------------------*/
function StartsWith($chaine, $val){
   return substr($chaine, 0, strlen($val)) == $val;
}

/*
-------------------------------------------------
  Vérifie si une chaine se termine par une valeur précise
-------------------------------------------------*/
function endsWith($string, $char)
{
    $length = strlen($char);
    $start =  $length *-1; //negative
    return (substr($string, $start, $length) === $char);
}

/*
-------------------------------------------------
  Vérifie si un enregistrement est vide
-------------------------------------------------*/
function isEmpty_Record($record) {
   $vide = true;
   while($record->n()){
      $vide = false;
   }
   return $vide;
}

/*
-------------------------------------------------
  Retourne la valeur du style
-------------------------------------------------*/
function getStyleValue($style) {
   $tab = explode("=",$style);
   return substr($tab[1],1,strlen($tab[1])-2);
}

/*
-------------------------------------------------
  Retourne des critères dans select
-------------------------------------------------*/
function arraytocondition($array,$id_champ,$opebool,$ope) {
   $filtre="";
   foreach ($array as $key => $val) {
      if($filtre=="")
         $filtre.="(".$id_champ.$ope."'".$val."'";
      else
         $filtre.=" ".$opebool." ".$id_champ.$ope."'".$val."'";	
   }	
   return $filtre.")";
}

/*
-------------------------------------------------
  Ajout d'un filtre
-------------------------------------------------*/
function ajouter_filtre($filtre,$id_champ,$valeur) {
   if($filtre=="")
      $filtre .= $id_champ."=".$valeur;
   else
      $filtre .= " and ".$id_champ."=".$valeur;
   return $filtre;
}

/*
-------------------------------------------------
  Retourne le champ en cours
-------------------------------------------------*/
function get_id_champ($id) {
   $tab= explode("_",$id);
   return $tab[2];
}

/*
-------------------------------------------------
  Retourne l'id du champ
-------------------------------------------------*/
function get_field_id($id,$list_id) {
   $tab_list_id = explode(";",$list_id);
   for ($i = 0; $i < count($tab_list_id); $i++) {
      if(get_id_champ($tab_list_id[$i])==$id) return $tab_list_id[$i];
   }
}

/*
-------------------------------------------------
  Gère un id unique
-------------------------------------------------*/
function get_unique_id_ajax() {
   return $GLOBALS[id_unique_ajax]++;
}
/*--------------------------------------------------
        Gestion des champs de formulaire
----------------------------------------------------
   Ces fonctions permettent d'appeller des champs
   "ouverts" modifiables ou "fixes" non-modifiable
   dans un formulaire en fonction des droits de
   la session en cours.

1. CHAMPS_OUVERT / CHAMP_OUVERT_FIXE / CHAMPS_OUVERT_DROIT
   Entrée : 1 - nom du champ de formulaire HTML
           2 - valeur du champ par défaut
           3 - longueur du champ
           4 - longueur d'affichage (non obligatoire)
                   5 - saisie obligatoire oui "O" ou non "N"
  Sortie : le code HTML du champ ouvert sur la sortie Standard
  Pour les fonctions avec DROIT, un paramètre est ajouté en ammont des autres :
  si ce paramètre est 'O', la fonction "ouverte" est appelée,
  si ce paramètre est 'N', la fonction "fixe" est appelée.
  si ce paramètre est Autre, la fonction "ouverte" ou "fixe" en fonction du fait que la personne
  connectée a le droit ($droit_1) testé par la fonction DROIT_UTILISATEUR
*/
/* Fonction technique à ne pas utiliser */
function champ_ouvert($nomchamp, $defval, $longueur=1, $long_aff = "vide",$fg_oblig = "N") {
   if($long_aff == "vide") {
      $long_aff = $longueur;
   }
   echo("<input type='text' name=\"$nomchamp\" value=\"$defval\" size=$long_aff maxlength=$longueur>");
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}
function champ_cache ($nomchamp, $valeurchamp) {
    echo("<input type=\"hidden\" name=\"".utf8_encode($nomchamp)."\" value=\"".utf8_encode($valeurchamp)."\">");
}
/* Fonction technique à ne pas utiliser */
function champ_ouvert_fixe($nomchamp, $defval, $longueur= "vide", $long_aff = "vide",$fg_oblig = "N") {
   $defval = strtr($defval,'"',"'");
   if ($long_aff == "vide") {
      $long_aff = strlen($defval);
   }
   $defval=substr($defval,0,$long_aff);
   echo("<input class=\"inactif\" type='text' name=\"$nomchamp\" value=\"$defval\" size=$long_aff maxlength=$longueur onfocus=\"Javascript:blur()\">");
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}
/* Fonction d'affichage à utiliser */
function champ_ouvert_droit($droit_1, $p1, $p2, $p3=1, $p4="vide", $p5="N") {

   if($droit_1 == 'O'){
      return champ_ouvert($p1, $p2, $p3, $p4, $p5);
   }
   elseif($droit_1 == 'N'){
      return champ_ouvert_fixe($p1, $p2, $p3, $p4, $p5);
   }
   elseif(droit_utilisateur($droit_1)){
      return champ_ouvert($p1, $p2, $p3, $p4, $p5);
   }
   else{
      return champ_ouvert_fixe($p1, $p2, $p3, $p4, $p5);
   }
}

/*2. CHAMPS_DATE / CHAMP_DATE_FIXE / CHAMPS_DATE_DROIT
  Fonction d'appel de champ date dans un formulaire
  Entrée : 1 - nom du champ de formulaire HTML
           2 - valeur du champ par défaut
           3 - nom du formulaire
           4 - saisie obligatoire "O" ou non "N"
  Sortie : le code HTML du champ date sur la sortie Standard
*/
/* Fonction technique à ne pas utiliser */
/*
function champ_date ($nomchamp, $defval, $nomform="", $fg_oblig="N"){
	$defvalsaisie=$defval;
	if(!$defval) {
		$defvalsaisie = "00/00/00";
	}
	elseif(ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$defval,$regs)){
		$defvalsaisie=$regs[3]."/".$regs[2]."/".substr($regs[1],2,2);
	}
?>
   <input type='text' name='<? echo $nomchamp ; ?>saisie' value='<? echo $defvalsaisie ; ?>' size=9 maxlength=8
   onfocus ="this.select()"
   onkeyup ="
   if ((this.value.search(/^[0-9]{2}$/)!=-1) || (this.value.search(/^[0-9]{2}\/[0-9]{2}$/)!=-1)) {
      this.value = this.value+'/'
   }"
   onblur ="
   while (this.value.match(/\D/)) {
      this.value = this.value.replace (/\D/,'');
   }
   this.value = this.value.substr(0,6);
   this.value = this.value + '000000'.substring(this.value.length,6);
   this.value = this.value.substr(0,2)+'/'+this.value.substr(2,2)+'/'+this.value.substr(4,2)
   if (this.value=='00/00/00') {
      this.form.<? echo $nomchamp ; ?>.value = '';
      return true;
   } else {
       var dater = new Date(this.value.substr(6,2),this.value.substr(3,2)-1,this.value.substr(0,2));
       if ((dater.getDate()!=this.value.substr(0,2)) || (dater.getMonth()+1!=this.value.substr(3,2)) || (dater.getYear()!=this.value.substr(6,2))) {
          alert('Format de date incorrect (jj/mm/aa)');
          this.focus();
          return false;
       } else {
          this.form.<? echo $nomchamp ; ?>.value ='20'+this.value.substr(6,2)+'-'+this.value.substr(3,2)+'-'+this.value.substr(0,2);
          return true;
       }
   }
   ">
   <input type='hidden' name='<? echo $nomchamp ; ?>' value='<? echo $defval; ?>'>
<?
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}

function champ_date_fixe ($nomchamp, $defval, $nomform="forms[0]", $fg_oblig="N") {
        $defvalsaisie=$defval;
        if(!$defval) {
                $defvalsaisie = "00/00/00";
           }
        if(ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$defval,$regs)){
                $defvalsaisie=$regs[3]."/".$regs[2]."/".substr($regs[1],2,2);
        }
                        ?>
                        <input class="inactif" type="text" name="<? echo $nomchamp ; ?>saisie" VALUE="<? echo $defvalsaisie; ?>"
                        size=9 maxlength=8 onFocus="javascript:blur()">
                        <input type="hidden" name="<? echo $nomchamp; ?>" value="<? echo $defval; ?>">
                        <?
}
*/
/*
function champ_date_droit ($droit_1, $p1, $p2, $p3="forms[0]", $p4="N") {
   if ($droit_1 == 'O') {
       return champ_date ($p1, $p2, $p3, $p4);
   } elseif ($droit_1 == 'N') {
       return champ_date_fixe ($p1, $p2, $p3, $p4);
   } elseif ( droit_utilisateur($droit_1)) {
       return champ_date ($p1, $p2, $p3, $p4);
   } else {
       return champ_date_fixe ($p1, $p2, $p3, $p4);
   }
}
*/
function champ_date ($nomchamp, $defval, $nomform="forms[0]", $fg_oblig="N") {
	champ_date_droit ('O', $nomchamp, $defval, $nomform="", $fg_oblig="N");
}

function champ_date_fixe ($nomchamp, $defval, $nomform="forms[0]", $fg_oblig="N") {
	champ_date_droit ('N', $nomchamp, $defval, $nomform="", $fg_oblig="N");
}

function champ_date_droit ($droit, $nomchamp, $defval, $nomform="", $fg_oblig="N") {
	if (!droit_utilisateur($droit)) {
		$class = " inactif ";
		$disabled = " disabled "; 
	} else {
		$class = " datepicker ";
	}
   if(!$defval) {
		$defvalsaisie = "00/00/00";
   } else {
		$defvalsaisie = substr($defval,8,2)."/".substr($defval,5,2)."/".substr($defval,2,2);
   }
	echo '<input ".$add." type="text" class="'.$class.'" '.$disabled.' name="'.$nomchamp.'saisie" value="'.$defvalsaisie.'" >';
	echo '<input type="hidden" name="'.$nomchamp.'" value="'.$defval.'" >';
	echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}

/* 3. DROP_DOWN / DROP_DOW_FIXE / DROP_DOWN_DROIT
  Fonction d'appel de liste déroulante dans un formulaire
  Entrée : 1 - requête SQL
           2 - nom du champ de formulaire HTML
           3 - nom du champ identifiant de la requete à envoyer dans le formulaire
           4 - nom du champ de la requete à afficher dans la drop down
           5 - valeur par défaut de l'identifiant de la requete (vide s'il n'y en a pas, dans ce cas la première ligne du formulaire renvoie une ligne blanche)
           6 - true si le formulaire doit être lancé immédiatement
           7 - nom du formulaire
           8 - Obligatoire "O" non "N"
           9 - liste de valeur ($val1|$val2|val3...)
           10 - liste de valeur à l'affichage(libelle1|libelle2|libelle3...)

  Sortie : le code HTML de la liste déroulante sur la sortie Standard
*/
/* Fonction technique à ne pas utiliser */
/* fonction drop down*/
function drop_down_save ($requete , $nomchamp, $nomident, $nomaffichage, $defident, $immediat=false, $page, $fg_oblig="N", $val, $val_affichage) {
   if (is_array($defident)) {
      $defiden = $defident;
   } else {
      $defiden[0] = $defident;
   }
   echo '<select name="'.
        $nomchamp.
        '"';
   if ($immediat) {
      //echo " onchange=\"document.location.href='". $page."&".$nomchamp."='+this.options[this.selectedIndex].value\" ";
      echo " onchange=\"document.forms['formulaire'].onglet_apres.value=this.options[this.selectedIndex].value;document.forms['formulaire'].ADD.value='1';document.forms['formulaire'].submit();\"";
   }
   echo '>';

   if ($val or $val_affichage) {
      $arr_val=explode("|",$val);
      $arr_val_affichage=explode("|",$val_affichage);
      for($i=0;$i<count($arr_val);$i++){
         $selected="";
         if (in_array($arr_val[$i],$defiden)){
            $selected="SELECTED";
         }
         echo "<option value='",$arr_val[$i],"'",$selected,">",$arr_val_affichage[$i],"</option>";
      }
   }

   if ($requete) {
      $req = new db_sql($requete);
      While ($req->n()) {
         echo '<option value ="'.$req->f($nomident).'"';
         if (in_array($req->f($nomident),$defiden)) {
           echo ' SELECTED';
         }
         echo ">".($req->f($nomaffichage)).'</option>';
      }
   }

   echo '</select>';
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}

function drop_down_custom($valeurs , $nomchamp, $val, $val_affichage,$onchange,$field,$value) {
   echo '<select name="'.
        $nomchamp.
        '"';
   echo $onchange;
   //echo " onchange='JavaScript:getInfosFournisseur(\"updateStockArticle.php\",1,1,2);'";
   echo '>';
   
   foreach ($valeurs as $i => $valeur_affiche ) {
      echo '<option value ="'.$valeur_affiche[$val].'"';
         if ($valeur_affiche[$field]==$value) {
           echo ' SELECTED';
         }
         echo ">".$valeur_affiche[$val_affichage].'</option>';
   }

   echo '</select>';
   
}

function drop_down ($requete , $nomchamp, $nomident, $nomaffichage, $defident, $immediat=false, $nomform="forms[0]", $fg_oblig="N", $val, $val_affichage, $id=false) {
   if (is_array($defident)) {
      $defiden = $defident;
   } else {
      $defiden[0] = $defident;
   }
   echo '<select name="'.
        $nomchamp.
        '"';
   if ($immediat) {
     	echo " onchange='JavaScript:this.form.submit();'";
   }
   if (is_array($defident)) {
   	echo " multiple size=5 ";
   }
   if($id) echo " id='". $id."'";
   echo '>';

   if ($val or $val_affichage) {
      $arr_val=explode("|",$val);
      $arr_val_affichage=explode("|",$val_affichage);
      for($i=0;$i<count($arr_val);$i++){
         $selected="";
         if (in_array($arr_val[$i],$defiden)){
            $selected="SELECTED";
         }
         echo "<option value='",utf8_encode($arr_val[$i]),"'",$selected,">",$arr_val_affichage[$i],"</option>";
      }
   }
   if ($requete) {
      $req = new db_sql($requete);
      While ($req->n()) {
         echo '<option value ="'.$req->f($nomident).'"';
         if (in_array($req->f($nomident),$defiden)) {
           echo ' SELECTED';
         }
         echo ">".($req->f($nomaffichage)).'</option>';
      }
   }

   echo '</select>';
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}
/* Fonction technique à ne pas utiliser */
function drop_down_fixe ($requete , $nomchamp, $nomident, $nomaffichage, $defident,  $immediat=false, $nomform="forms[0]", $fg_oblig="N", $val, $val_affichage){
    $affiche = $nomaffichage;
    if ($requete) {
      $req = new db_sql($requete);
      while($req->n()) {
         if($defident == $req->f($nomident)) {
            $nomaffichage=$req->f($nomaffichage);
         }
      }
   }
   if($affiche === $nomaffichage) {
       $nomaffichage = '';
   }
   if ($val or $val_affichage) {
      $arr_val=explode("|",$val);
      $arr_val_affichage=explode("|",$val_affichage);
      for($i=0;$i<count($arr_val);$i++){
         if($defident==$arr_val[$i]){
            $nomaffichage = $arr_val_affichage[$i];
         }
      }
   }
   echo "<input class='inactif' type='text' value='".utf8_encode($nomaffichage)."' onFocus='javascript:blur()'>";
   echo "<input type='hidden' name='$nomchamp' value='$defident'>";
}
/* Fonction d'affichage à utiliser */
function drop_down_droit ($droit_1, $p1, $p2, $p3, $p4, $p5, $p6=false, $p7="forms[0]", $p8="N", $p9="", $p10="", $p11=false) {
   if ($droit_1 == 'O') {
       return drop_down ($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);
   }else if ($droit_1 == 'L') {
       return drop_down_save($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);
   } elseif ($droit_1 == 'N') {
       return drop_down_fixe ($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);
   } elseif(droit_utilisateur($droit_1)){
       return drop_down ($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11);
   } else {
       return drop_down_fixe ($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);
   }
}

/* 4. TEXTE_AREA / TEXTE_AREA_FIXE / TEXTE_AREA_DROIT
  Fonction d'appel d'une zone de texte dans un formulaire (doit être appelé dans un formulaire)
  Entrée : 1 - nom du champ de formulaire HTML
           2 - nombre de colonnes affichables dans la zone de texte
           3 - nombre de lignes affichables dans la zone de texte
           4 - nombre de caractères maximum que peut contenir la zone de texte
           5 - valeur par défaut de la zone de texte
                   6 - saisie obligatoire "O" ou non "N"
  Sortie : le code HTML de la zone de texte sur la sortie Standard
*/
/* Fonction technique à ne pas utiliser */
function text_area($nomchamp,$col,$row,$char_max,$defval,$fg_oblig="N")
{
   echo("<textarea wrap=\"physical\" name = '".$nomchamp."' cols = ".$col." rows = ".$row." onblur='Javascript:if(this.value.length > ".$char_max."){this.value = this.value.substring(0,".$char_max."-1);window.alert(\"La zone de texte ne peut contenir plus de\"+".$char_max." +\"caractères\")}'>");
   echo($defval);
   echo("</textarea>");
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}
/* Fonction technique à ne pas utiliser */
function text_area_fixe($nomchamp,$col,$row,$char_max,$defval,$fg_oblig="N")
{
   $defval = strtr($defval,'"',"'");
   echo("<textarea wrap='physical' name = '".$nomchamp."' cols = ".$col." rows = ".$row." onfocus='Javascript:blur()'>");
         echo($defval);
   echo("</textarea>");
}
/* Fonction d'affichage à utiliser */
function text_area_droit ($droit_1, $p1, $p2, $p3, $p4, $p5, $p6="N") {
   if ($droit_1 == 'O') {
       return text_area ($p1, $p2, $p3, $p4, $p5, $p6);
   } elseif ($droit_1 == 'N') {
       return text_area_fixe ($p1, $p2, $p3, $p4, $p5, $p6);
   } elseif(droit_utilisateur($droit_1)){
       return text_area ($p1, $p2, $p3, $p4, $p5, $p6);
   } else {
       return text_area_fixe ($p1, $p2, $p3, $p4, $p5, $p6);
   }
}

/* 5. CHAMPS_BINAIRE / CHAMP_BINAIRE_FIXE / CHAMP_BINAIRE_DROIT
  Fonction Champ binaire dans un formulaire
  Entrée : 1 - nom du champ de formulaire HTML
           2 - Valeur du champ Oui
           3 - Valeur par défaut
  Sortie : le code HTML de la liste déroulante sur la sortie Standard
*/
/* Fonction technique à ne pas utiliser */
function champ_binaire ($nomchamp, $valeur_oui, $valeur_defaut) {
        if ($valeur_oui == $valeur_defaut) {
      $checked="checked";
   }
?>
   <input type ="checkbox" name ="<? echo $nomchamp ?>" value="<? echo $valeur_oui ?>" <? echo $checked ?>>
<?
}
/* Fonction technique à ne pas utiliser */
function champ_binaire_fixe ($nomchamp, $valeur_oui, $valeur_defaut) {
   if ($valeur_oui == $valeur_defaut) {
      ?><img src = 'ress/check_selected.gif'><?
   } else {
      ?><img src = 'ress/check_unselected.gif'><?
   }
}
/* Fonction d'affichage à  utiliser */
function champ_binaire_droit ($droit_1, $p1, $p2, $p3) {
   if ($droit_1 == 'O') {
       return champ_binaire ($p1, $p2, $p3);
   } elseif ($droit_1 == 'N') {
       return champ_binaire_fixe ($p1, $p2, $p3);
   } elseif(droit_utilisateur($droit_1)){
       return champ_binaire ($p1, $p2, $p3);
   } else {
       return champ_binaire_fixe ($p1, $p2, $p3);
   }
}

/* 4. NUMERIC_OUVERT / NUMERIC_FIXE/ NUMERIC_DROIT
                    1 - nom du champ de formulaire HTML
           2 - valeur par defaut
           3 - nombre de decimal (0 pour un entier)
                   4 - longueur
           5 - nom du formulaire
                   6 - Obligatoire "O" non "N"
/* Fonction technique à ne pas utiliser */
function champ_numeric_ouvert($nomchamp, $defval, $nbdec=2, $longueur=1, $nomform="forms[0]", $fg_oblig = "N"){
?>
   <input type='text' name="<? echo $nomchamp ?>" value="<? echo $defval ?>" size=<? echo $longueur ?> maxlength = <? echo $longueur ?> onchange ="
      var verif = this.value;
   <?
   if($nbdec>0){
   ?>
      if (verif.search(/^[0-9]*\.{0,1}[0-9]{0,<? echo $nbdec; ?>}$/)==-1){
         alert('Format numérique incorrect\n<? echo $nomchamp ; ?>, doit avoir : <? echo $nbdec; ?> decimales');
         this.focus();
         return false;
      }
   <?
   } else {
   ?>
      if (verif.search(/^[0-9]*$/)==-1){
         alert('Format numérique incorrect\n<? echo $nomchamp ; ?>, doit être un entier');
         this.focus();
         return false;
      }
   <?
   }
   ?>
   return true;">
<?
   echo("<input type='hidden' name='h_".$nomchamp."' value=\"".$fg_oblig."\">");
}
/* Fonction technique à ne pas utiliser */
function champ_numeric_fixe($nomchamp, $defval, $nbdec, $longueur=1, $nomform="forms[0]",$fg_oblig = "N"){
   $defval = strtr($defval,'"',"'");
   echo("<input class=\"inactif\" type='text' name='$nomchamp' value='$defval' size='".strlen($defval)."' onFocus=\"javascript:blur()\">");
}
/* Fonction d'affichage à utiliser */
function champ_numeric_droit($droit_1, $p1, $p2, $p3, $p4=1, $p5, $p6) {
   if($droit_1 == 'O'){
      return champ_numeric_ouvert($p1, $p2, $p3, $p4, $p5, $p6);
   }
   elseif($droit_1 == 'N'){
      return champ_numeric_fixe($p1, $p2, $p4, $p5, $p6);
   }
   elseif(droit_utilisateur($droit_1)){
      return champ_numeric_ouvert($p1, $p2, $p3, $p4, $p5, $p6);
   }
   else{
      return champ_numeric_fixe($p1, $p2, $p4, $p5, $p6);
   }
}
/*-----------------------------------------------
               FONCTION DIVERSES
------------------------------------------------*/

/* Formate la date du jour */
$date_jour = strtoupper(date("d-M-y"));

/*
  Fonction qui renvoie blanc si une chaine est vide (ou plaine de blancs)
  et la laisse inchangée sinon (pour mise en forme dans un tableau)
  Entrée : 1 -  Chaine
  Sortie : La chaine ou un blanc sur la sortie standard
*/

function echo_tableau ($entree)
{
   if (trim($entree)=="")
   {
      echo '&nbsp;';
   }
   else
   {
      echo $entree;
   }
}

/*
  Fonction qui renvoie date sous forme 'dd/mm/yy' que la chaine soit vide ou non
  (pour mise en forme dans un tableau)
  Entrée : 1 -  Chaine
  Sortie : La date sur la sortie standard
*/
function echo_tableau_date($entree)
{
   if(!$entree) {
      echo("00/00/00");
   } else {
      echo(date("d/m/y",strtotime($entree)));
   }
}

/*
  Requete simple sur la base de donnée
  Entrée : 1 - requête SQL
           2 - nom du champ à récupérer
  Sortie : La valeur comme résultat de la fonction
*/

function req_sim ($requete , $nomchamp) {
   $reql = new db_sql($requete);
   if ($reql->n()) {
      return $reql->f($nomchamp);
   }
}

function req_mul ($requete, &$req = false) {
    if (!$req) {
        $req = new db_sql($requete);
    } else {
        $req->q($requete);
    }
    $table = [];
    while ($res = $req->n()) {
        $table[] = $res;
    }
    return $table;
}

function req_pre ($requete, &$req = false) {
    $tab = req_mul($requete,$req);
    $res = array();
    if($tab){
        foreach ($tab as $ligne) {
            $res[$ligne["id"]] = $ligne["lb"];
        }
    }
    return $res;
}
// fonctions de date mysql
// de date vers mysql
function aujourdhui(){
        return date("Y-m-d");
}

function decale_date($d,$n){
        return date("Y-m-d",strtotime($d)+($n*24*60*60)+3600);
}


function recupere ($l_nom) {
   if (isset($_POST[$l_nom])) {
      $msg = $_POST[$l_nom];
   } else {
      $msg = $_GET[$l_nom] ;
   }
   if (ini_get("magic_quotes_gpc")) {
      $msg = stripslashes($msg);
   }
   $GLOBALS[$l_nom] = $msg;
}

function My_sql_format($msg) {
   if (!ini_get("magic_quotes_gpc")) {
      $msg = addslashes($msg);
   }
   if ($msg) {
      return "'".$msg."'";
   } else {
      return "null";
   }
}

function Egal_my_sql_format ($msg) {
   $t = My_sql_format($msg);
   if ($t == "null") {
      return "is null";
   } else {
      return "= ".$msg;
   }
}

function js_alert($msg) {
echo (
     "\n<SCRIPT LANGUAGE='Javascript'>\n" .
     " <!-- \n" .
     " alert (\"$msg\");\n" .
     " // --> \n" .
     "</SCRIPT>\n"
  );
}

/*----------------- ALTERNAT ---------------*/
/* fonction qui change la couleur de lignes */
function alternat($z){
                if($z % 2 ==0){
                        $bgcolor="#D3DCE3";
                }
                else{
                        $bgcolor="WhiteSmoke";
                }
                return $bgcolor;
}
//}

function flux_ouvre($destination = 'temp/temp.xls') {
   global $flux,$filename;
   $filename = $destination;
   if (file_exists($filename)) unlink ($filename);
   $flux=fopen($filename, 'w');
}

function flux_ecrit($x) {
   global $flux;
   fputs ($flux,$x);
}

function flux_ferme() {
   global $flux,$filename;
   fclose($flux);
   header("Location:$filename");
}

function numerise($val){
	$val = trim ($val);
	$val = ($val?$val:0.00);
	$val = number_format($val,2,","," ");
	return($val);
}

function monetise($val) {
	$val = trim ($val);
	if ($val == "") return "";
	strtoupper ($devise = substr($val,-3));
	switch ($devise) {
		case "EUR"	: 
			$val = substr($val,0,-3);
			$dev =" &euro;";
			break;
		case "USD"	: 
			$val = substr($val,0,-3);
			$dev.=" $";
			break;
		default		:
			$dev =" &euro;";
			break;
	}
	$val = numerise($val);
	return($val.$dev);
}

/**********************************************************/
/*        Objet de gestion de tableau (1 seul par ecran)  */
/**********************************************************/
/* Mode d'emploi :

- creer un tableau
  $tableau = new tableau();

- parametrer le tableau

   $tableau->req               = requete
   
   OU
   
   $tableau->tableau			= table

- Afficher le tableau
   echo $tableau->html();

*/
class tableau {
	
    //Proprietes
    
	var $tools = 1;
	var $nb_ligne = 25; // Nombre de ligne à afficher sur un écran
    var $table = array();
	var $alignement = array(); //Alignement de la cellule
	var $class = array(); //Class à ajouter à la ligne
	var $format  = array(); //Format
	var $nowrap  = array(); //nowrap
	var $css  = array(); //style
    var $req = "";
	var $tri = "";

    //Fabricant
    function tableau($req = null, $table = null, $classes = []) {
        if($req) {
            $this->req = $req;
        } else {
            $this->table = $table;
            $this->class = $classes;
        }
    }
	
    function tri($tri) {
        $this->tri = $tri;
    }	

    //Methode html
    function html($xl_direct=false) {
		$x = $_SESSION[$GLOBALS["INSTANCE"]][compteur_tableau]++;
		$num = time()."_".$x;
		$this->alignement = array_change_key_case($this->alignement);
		$this->format = array_change_key_case($this->format);
		$this->class = array_change_key_case($this->class);
		$this->nowrap = array_change_key_case($this->nowrap);
		$this->css = array_change_key_case($this->css);
		
		if($this->req) {
			$_SESSION[$GLOBALS["INSTANCE"]][xl_tableau][$num][req] = $this->req;
			$lo_req = new db_sql("select * from (". $this->req .") res LIMIT 1");
			$lo_req->n();
			$premiere_ligne = $lo_req->Record_brut;
		} else {
			file_put_contents ("./../temp/data_".$num.".dat", serialize($this->table));
			if (count($this->table)) {
				reset ($this->table);
				$premiere_ligne = current($this->table);
			}
		}
		if ($premiere_ligne) {
			$titres = (array_keys($premiere_ligne));
			$_SESSION[$GLOBALS["INSTANCE"]][xl_tableau][$num][titres] = $titres;
		}

		if (count($titres)) {
			$spool = "
			<table class = 'liste' id = 'list_".$num."'>
				<thead>
					<tr>";
			$js_ligne = "";
			foreach ($titres as $i => $valeur) {
				
				$lo_hidden = "";
				$alignement = $this->alignement[strtolower($valeur)];
				$format = $this->format[strtolower($valeur)];
				$class = $this->class[strtolower($valeur)];
				$nowrap = $this->nowrap[strtolower($valeur)];
				$css = $this->css[strtolower($valeur)];
				
				if (strtolower($valeur) == "hidden_style_ligne") {
					$js_ligne .= "
						if (data[".$i."]!='') { 
							$('tr',row).css(data[".$i."]); 
						};
					";
				}
				if (strtolower($valeur) == "hidden_bgcolor") {
					$js_ligne .= "
						if (data[".$i."] != '') {
							$('td:eq(1)', row).parent().css('background-color',data[".$i."]); 
						};
					";
				}
				if (strtolower($valeur) == "hidden_lien") {
					$js_ligne .= "
						if (data[".$i."] != '') {
							$('td:eq(1)', row).parent().attr('data-link',data[".$i."]); 
							$('td:eq(1)', row).parent().click(function() {
								document.location.href = $(this).attr('data-link');
							});
						};
					";
				}
				if (strtolower($valeur) == "hidden_popup") {
					$js_ligne .= "
						if (data[".$i."] != '') {
							$('td:eq(1)', row).parent().each(function(){
								var tr = $(this);
								var requete = data[".$i."];
								//tr.attr('popup',data[".$i."]);
								tr.mouseenter(function () {
									$('html').append('<div id=\"pop_up_list\" title=\"Configs\"><div id=\"ventilateur\" style=\"text-align:center; vertical-align:middle; z-index:1000;\"><img width=\"100px\" height=\"100px\" src=\"./../ress/loading.gif\"></div></div>');
									$.get(
										requete,
										function(contenu) {
											$('#pop_up_list').html(contenu);
										});
									var position = tr.position();
									$('#pop_up_list').show();
									$('#pop_up_list').css('position', 'absolute');
									$('#pop_up_list').css('background-color', 'white');
									$('#pop_up_list').css('top', position.top+129);
									$('#pop_up_list').css('left', 0);
								});
							
								tr.mouseleave(function () {
									$('#pop_up_list').remove();
								});
							});
						};
					";
				}			

				
				if (strtolower(substr($valeur, 0, 7)) == "nowrap_") {
					$valeur = substr($valeur,strpos($valeur,"_")+1);
					$nowrap = 1;
				} else {
					$nowrap = 0;
				}
				if (strtolower(substr($valeur, 0, 8)) == "montant_") {
					$valeur = substr($valeur,strpos($valeur,"_")+1);
					$format = "montant";
					$nowrap = 1;
					$alignement = "right";
				}
				if (strtolower(substr($valeur, 0, 5)) == 'date_') {
					$valeur = substr($valeur,strpos($valeur,"_")+1);
					$format = "date";
					$nowrap = 1;
					$alignement = "center";
				}
				if (strtolower(substr($valeur, 0, 7)) == 'number_') {
					$valeur = substr($valeur,strpos($valeur,"_")+1);
					$format = "number";
					$nowrap = 1;
					$alignement = "center";
				}
				if (strtolower(strtolower(substr($valeur, 0, 7)) == "hidden_") or strtolower((substr($valeur, 0, 7) == "cacher_")))  {
					//$valeur = substr($valeur,strpos($valeur,"_")+1);
					$format = "hidden";
					$lo_hidden .= " style='display:none' ";
					$js_ligne .= "$('td:eq(".$i.")', row).css('display','none'); ";
				}

				if ($nowrap) {
					$js_ligne .= "$('td:eq(".$i.")', row).attr(\"nowrap\",\"nowrap\"); ";
				}
				
				if ($class) {
					$js_ligne .= "$('td:eq(".$i.")', row).addClass('" . $class . "'); ";
				}
				
				if ($alignement) {
					$js_ligne .= "$('td:eq(".$i.")', row).css({'text-align':'".$alignement."'});";
				}
				
				if ($css) {
					$js_ligne .= "$('td:eq(".$i.")', row).css({".$css."});";
				}
								
				$spool .= "<th $lo_hidden>" . utf8_encode($valeur) . "</th>";
			}	
			$spool .= "
					</tr>
				</thead>
			</table>";
			
			if (!$this->tools) $notools = "
				\"paging\": false,
				\"ordering\": false,
				\"info\": false,
			";
			$spool .= "
					</tr>
				</thead>
			</table>
			<script>
			var table = $('#list_".$num."').DataTable({
				\"searching\": false,
				\"info\": true,
				". $notools ."
				\"processing\": true,
				\"serverSide\": true,
				\"ajax\": \"tableau_ajax.php?NUM=".$num."\",
				\"rowCallback\": function( row, data ) {".$js_ligne."},
				\"iDisplayLength\": ".$this->nb_ligne.",
				\"aLengthMenu\": [[10, 25, 50, 100], [10, 25, 50, 100]],
				language: {
					\"decimal\":        \"\",
					\"emptyTable\":     \"Aucune donnée disponible\",
					\"info\":           \"Affichage de _START_ à _END_ des _TOTAL_ résultats\",
					\"infoEmpty\":      \"Afficher 0 de 0 à 0 résultats\",
					\"infoFiltered\":   \"(filtré parmi _MAX_ résultats)\",
					\"infoPostFix\":    \"\",
					\"thousands\":      \",\",
					\"lengthMenu\":     \"Voir _MENU_ résultats\",
					\"loadingRecords\": \"Chargement...\",
					\"processing\":     \"Processing...\",
					\"search\":         \"Recherche :\",
					\"zeroRecords\":    \"Aucun enregistrement correspondant trouvé\",
					\"paginate\": {
						\"first\":      \"Premier\",
						\"last\":       \"Dernier\",
						\"next\":       \"Suivant\",
						\"previous\":   \"Précédent\"
					},
					\"aria\": {
							\"sortAscending\":  \": Activer pour trier en ordre croissant\",
							\"sortDescending\": \": Activer pour trier en ordre décroissant\"
					}
				}
			});
			</script>";
			if ($this->tools) {
				$spool .= "
					<a href='ress/xl_tableau.php?html=1&num=" . $num . "' target='xl'>XL simplifié</a>
					<a href='ress/xl_tableau.php?csv=1&num=" . $num . "' target='xl'>CSV</a>";	
			}
		}
		return $spool;
	}
}
?>