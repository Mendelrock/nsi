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
<form method="post" action="admin_marquage_import.php?ACTE=1" ENCTYPE="multipart/form-data" >
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne_actif">Affectation de marquage</td>
         <td class="menu_haut">Fichier à importer :
         <input type="file" name="userfile" enctype="multipart/form-data">
         <input type="submit" value="Importer"></td>
         <td class="menu_haut"><? drop_down_droit("O","
         SELECT
            marquage.Lb_marquage,
            marquage.Id_marquage
         FROM
            marquage","Id_marquage", "Id_marquage", "Lb_marquage", "", false, "O","",""," "); ?>
         <? drop_down_droit("O","","Fg_action", "", "", "A", false, "O","","A|R","(A)jout|(R)etrait"); ?>
         </td>
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
   if($fp=@fopen($tempfile_name,"r")){
      $resultat= new db_sql();
      //Update de la table client
      $Nb   = 0;
      while (!feof($fp)) {

         $lo_siret = "";
         $lo_lb_marquage = "";
         $lo_fg_action = "";
         $lo_erreur = "";

         list($lo_siret, $lo_lb_marquage, $lo_fg_action) = split(";",fgets($fp));
         $lo_fg_action = trim(decote($lo_fg_action));
         if (!$lo_fg_action)
             $lo_fg_action = $Fg_action;

         $lo_id_marquage = "";
         if ($lo_lb_marquage) {
            $lo_req = new db_sql("select Id_marquage from marquage where Lb_marquage = ".My_sql_format(trim(decote($lo_lb_marquage))).";");
            if ($lo_req->n()) {
               $lo_id_marquage = $lo_req->f("Id_marquage");
            }
         } else {
            $lo_id_marquage = $Id_marquage;
         }
         if (!$lo_id_marquage) {
            $lo_ret['Marquages non reconnus']++;
            $lo_erreur = 1;
         }

         $lo_id_client = "";
         $lo_req = new db_sql("select Id_client from client where Siret = ".My_sql_format(trim(decote($lo_siret))).";");
         if ($lo_req->n())
            $lo_id_client = $lo_req->f("Id_client");
         if (!$lo_id_client) {
            $lo_ret['Clients non reconnus']++;
            $lo_erreur = 1;
         }

         if (!$lo_erreur) {
               $lo_req = new db_sql ("delete from client_marquage where id_client = $lo_id_client and id_marquage = $lo_id_marquage");
            if ($lo_fg_action == "A") {
               $lo_req = new db_sql ("delete from client_marquage where id_client = $lo_id_client and id_marquage = $lo_id_marquage");
               $lo_req = new db_sql ("insert into client_marquage(id_client, id_marquage) values ($lo_id_client,$lo_id_marquage)");
               $lo_ret['Lignes ajoutées']++;
            } else if ($lo_fg_action == "R") {
               $lo_req = new db_sql ("delete from client_marquage where id_client = $lo_id_client and id_marquage = $lo_id_marquage");
               $lo_ret['Lignes retirées']++;
            } else {
               $lo_ret['Actions demandées non reconnues']++;
            }
         }
      }
      reset ($lo_ret);
      while (list ($i,$j) = each ($lo_ret)) {
         $lo_cr .= "<BR>".$i." : ".$j;
      }
?>
   <table class="requeteur">
      <tr>
         <td class='requeteur' height='40' valign='middle'>Fichier traité : <BR><? echo $lo_cr ?></td>
      </tr>
   </table>
<?
   } else {
      $message="Operation impossible, le fichier semble absent";
   }
}
}
include ("ress/enpied.php");
?>