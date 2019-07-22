<?php
include("ress/db_mysql.php");
require_once("c_o_dataset.php");
require_once("ress/util.php");
$listevaleur=$_POST['listevaleur'];
$list_id=$_POST['list_id'];
$filter=$_POST['filter'];
$id=$_POST['id'];
$tab_listevaleur = explode(";",$listevaleur);
$tab_list_id = explode(";",$list_id);
$filtre="";
$id_to_update="";
for ($i = 0; $i < count($tab_listevaleur); $i++) {
   $tab_champ = explode("=",$tab_listevaleur[$i]);
   if($id==$tab_champ[0]) $valeur = $tab_champ[1];
   //Si le champ contient une valeur saisie 
   if($tab_champ[1]!="") {
      $field  = get_id_champ($tab_champ[0]);
      $filtre = ajouter_filtre($filtre,$field,$tab_champ[1]);
   } else {
   //On ne modifie que les drop down qui sont encore à "choisir"
      if($i<=count($tab_list_id)) {
         if($id_to_update=="") {	
		      $id_to_update.=$tab_list_id[$i];
		   } else {
		      $id_to_update.=";".$tab_list_id[$i];
		   }
	   }
   }
}
//Vérifier si la requête en cours ne ramène à rien
$regle = new regle($filter,$filtre,get_id_champ($tab_list_id[0]));
$resultat = $regle->construire_regle();
if (isEmpty_Record($resultat)) {
   $id_to_update="";
   for ($i = 0; $i < count($tab_listevaleur); $i++) {
      $tab_champ = explode("=",$tab_listevaleur[$i]);
      if($tab_champ[0]==$id)
         $filtre=get_id_champ($id)."=".$tab_champ[1]; 
	   else {
	      if($id_to_update=="") {	
		      $id_to_update.=$tab_list_id[$i];
		   } else {
		      $id_to_update.=";".$tab_list_id[$i];
		   }	
	   }     
   }   	   
}
$tab_id_to_update = explode(";",$id_to_update);
$result ="" ;
for ($i = 0; $i < count($tab_id_to_update); $i++) {
   $id_champ = get_id_champ($tab_id_to_update[$i]);
   if ($id_champ!="") {
      $regle = new regle($filter, $filtre, $id_champ);
      $resultat = $regle->construire_regle();
      $str = 'var valeurs = [{id:"",valeur:"[Choisir]"}, ';
      while ($resultat->n()) {
	      $str.='{id:"'.utf8_encode($resultat->f($id_champ)).'", valeur:"';
	      $str.= utf8_encode($resultat->f($id_champ)).'"},';
      }
 	   if (substr($str, strlen($str)-1, strlen($str)) == ',') {
 	   	$str = substr($str, 0, -1);
		}
		$str .= ']';
		if($result == "")
         $result .= $tab_id_to_update[$i]."[SEPVAL]".$str;
      else
         $result .= "[SEPDIV]".$tab_id_to_update[$i]."[SEPVAL]".$str;
   }
}
echo $result;

?>
