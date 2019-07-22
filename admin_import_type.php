<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------*/
/* Externe
/* userfile
/* ACTE
/*------------------------------------------------*/
function decote($x) {
   $x = trim ($x);
   if ((substr($x,0,1) == '"') and (substr($x,-1) == '"')) {
      $x = substr ($x,1,-1);
   }
   return $x;
}
if (droit_utilisateur("admin")) {
?>
<body class="application">
<form method="post" action="admin_import_type.php?ACTE=1" ENCTYPE="multipart/form-data" >
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Type</td>
         <td class="menu_haut">Fichier à importer :
         <input type="file" name="userfile" enctype="multipart/form-data">
         <input type="submit" value="Importer"></td>
         <td class="interne"><A class='interne' href = "admin_import_type.php?ACTE=2">Remise à zéro</A><td>
      </tr>
   </table>
</form>
<?
if($ACTE==1){
   //Variable du fichier uploade
   $tempfile_name=$HTTP_POST_FILES['userfile']['tmp_name'];
   $size=$HTTP_POST_FILES['userfile']['size'];
   $type=$HTTP_POST_FILES['userfile']['type'];
   //Vérification
   if(empty($userfile)){
      $message="Il n'y a pas de fichier !";
   }
   //extension
   /*if($type<>"text/plain"){
      $message="Le fichier n'est pas un fichier texte!";
   }*/
   if(!$message){
   //Ouverture du fichier d'import
   if($fp=@fopen($tempfile_name,"r")){
      $resultat= new db_sql();
      //Update de la table client
      //$resultat->q("UPDATE client SET Prio='N'");
      $Nb   = 0;
      while (!feof($fp)) {
         list($Siret,$id_type)=split(";",fgets($fp));
         $Siret   = decote($Siret);
         $id_type = decote($id_type);
         if (!req_sim("select count(1) as compte from type where id_type = ".My_sql_format($id_type),"compte")) {
            $id_type="";
         }
         $resultat->q("UPDATE client SET id_type = ".My_sql_format($id_type)." WHERE Siret = ".My_sql_format($Siret));
         $Nb++;
      }
      //Effacement du fichier
      $message_fin="Traitement terminé";
   } else {
      $message="Operation impossible, le fichier semble absent";
   }
   }
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Fichier traité : <? echo $Nb ?> lignes traitées</td>
      </tr>
   </table>
<?
}
if($ACTE==2){
   $resultat= new db_sql();
   $resultat->q("UPDATE client SET id_type=null");
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Types effacés</td>
      </tr>
   </table>
<?
}
}
include ("ress/enpied.php");
?>