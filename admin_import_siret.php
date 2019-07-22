<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------*/
/* Externe
/* userfile
/* ACTE
/*------------------------------------------------*/
if (droit_utilisateur("admin")) {
?>
<body class="application">
<form method="post" action="admin_import_siret.php?ACTE=1" ENCTYPE="multipart/form-data" >
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Priorités</td>
         <td class="menu_haut">Fichier à importer :
         <input type="file" name="userfile" enctype="multipart/form-data">
         <input type="submit" value="Importer"></td>
         <td class="interne"><A class='interne' href = "admin_import_siret.php?ACTE=2">Remise à zéro</A><td>
      </tr>
   </table>
</form>
<?
if($ACTE==1){
   //Variable du fichier uploade
   $tempfile_name=$HTTP_POST_FILES['userfile']['tmp_name'];
   $dest_file="upload/siret.txt";
   $size=$HTTP_POST_FILES['userfile']['size'];
   $type=$HTTP_POST_FILES['userfile']['type'];
   //Vérification
   if(empty($userfile)){
      $message="Il n'y à pas de fichier !";
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
      $resultat->q("UPDATE client SET Prio='N'");
      $Nb   = 0;
      while (!feof($fp)) {
         $Siret=fgets($fp);
         $resultat->q("UPDATE client SET Prio='O' WHERE Siret = ".My_sql_format($Siret));
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
         <td class='requeteur' height='40' valign='middle'>Fichier traité : <? echo $Nb ?> lignes traitées, <? echo req_sim ("select count(1) as compte from client where prio = 'O'","compte")?> clients passés prioritaires</td>
      </tr>
   </table>
<?
}
if($ACTE==2){
   $resultat= new db_sql();
   $resultat->q("UPDATE client SET Prio='N'");
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Priorités remises à 0</td>
      </tr>
   </table>
<?
}
}
include ("ress/enpied.php");
?>