<?
include("ress/entete.php");
/*-----------------PARAMETRES---------------------*/
/* Externe
/* userfile
/* ACTE
/*------------------------------------------------*/
?>
<body class="application">
<?

set_time_limit(0);
function transcote($x) {
   if ($x=="") return "null"; 
   return "'".str_replace ("'","''",$x)."'";
}


   if($fp=@fopen("C:\wamp\www\sfa\berret2.csv","r")){
      $resultat= new db_sql();

      $resultat->q("delete from contact");
           
      $ligne=fgets($fp);
      $ligne=fgets($fp);
      while (!feof($fp)) {
         $ligne=fgets($fp);
         if ($ligne) {
            $par = split(";",$ligne);
            //print_r($par);
          
            if (($par[1]!=$ancien[1]) or
                ($par[2]!=$ancien[2]) or
($par[3]!=$ancien[3]) or
($par[4]!=$ancien[4]) or
($par[5]!=$ancien[5]) or
($par[6]!=$ancien[6]) or
($par[7]!=$ancien[7]) or
($par[8]!=$ancien[8]) or
($par[9]!=$ancien[9]) or
($par[10]!=$ancien[10]) or
($par[11]!=$ancien[11]) or
($par[12]!=$ancien[12]) or
($par[13]!=$ancien[13]) or
($par[14]!=$ancien[14]) or
($par[15]!=$ancien[15])) {
      
              
            $resultat->q("
            INSERT INTO contact (
            Id_contact, 
            Id_client, 
            Id_civilite, 
            Nom, 
            Prenom, 
            Telephone, 
            Fax, 
            Mail, 
            Id_decision, 
            Id_fonction, 
            Adresse1, 
            Adresse2, 
            Adresse3, 
            Cp, 
            Ville, 
            Mobile, 
            Decideur)                
               VALUES 
               (
               ".transcote($par[0]).",
               ".transcote($par[0]).", 
               ".transcote($par[1]).", 
               ".transcote($par[3]).",
               ".transcote($par[2]).", 
               ".transcote($par[12]).", 
               ".transcote($par[14]).", 
               ".transcote($par[15]).", 
               ".transcote($par[5]).", 
               ".transcote($par[4]).", 
               ".transcote($par[7]).", 
               ".transcote($par[8]).", 
               ".transcote($par[9]).", 
               ".transcote($par[10]).", 
               ".transcote($par[11]).", 
               ".transcote($par[14]).", 
               null) 
            ");
            
            $ancien = $par; 
             }
            flush();
            //if ($x++ > 10000) die();
         }
      }
   }
include ("ress/enpied.php");
?>
