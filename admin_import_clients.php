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
   return "'".trim(str_replace ("'","''",$x))."'";
}

function renvoie_generique($libelle, $table, $id, $lb) {
   $libelle = trim ($libelle);
   global $resultat;
   if (!$libelle) return "null";
   $resultat= new db_sql();
   $resultat->q("select $id from $table where $lb = ".transcote($libelle));
   if ($resultat->n()) {
      //echo $id."  : ".$resultat->f($id)."<BR>";
      return $resultat->f($id);
   } else {
      //echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion $table : ".$libelle."<BR>";
      echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp $table : ".$libelle." pas trouvï¿½e<BR>";
      /*if(!$_POST['staging']){
         $resultat= new db_sql();
         $resultat->q("insert into $table ($lb) values (".transcote($libelle).")");
         return db_sql::last_id ();
      }*/
   }
}

function renvoie_id_statut($libelle) {
   return renvoie_generique($libelle, 'client_statut', 'Id_statut', 'Lb_statut');
}

function renvoie_id_prio($libelle) {
   return renvoie_generique(substr($libelle,0,30), 'prio', 'Id_prio', 'Lb_prio');
}

function renvoie_id_code_effectif($libelle) {
   return renvoie_generique($libelle, 'code_effectif', 'Code_effectif', 'Lb_effectif');
}

function renvoie_id_type($libelle) {
   return renvoie_generique($libelle, 'type', 'Id_type', 'Lb_type');
}

function renvoie_id_commercial($libelle) {
   return renvoie_generique(substr($libelle,0,10), 'utilisateur', 'Id_utilisateur', 'login');
}

function renvoie_id_source($libelle) {
   return renvoie_generique('Source : '.$libelle, 'marquage', 'Id_marquage', 'Lb_marquage');
}

function renvoie_id_civilite($libelle) {
   return renvoie_generique($libelle, 'civilite', 'Id_civilite', 'Lb_civilite');
}

function renvoie_id_decision($libelle) {
   return renvoie_generique($libelle, 'decision', 'Id_decision', 'Lb_decision');
}

function renvoie_id_fonction($libelle) {
   return renvoie_generique($libelle, 'fonction', 'Id_fonction', 'Lb_fonction');
}

function traiter_field_tel($valeur) {
   $valeur=str_replace(" ","",$valeur);
   $valeur=str_replace(".","",$valeur);      $valeur=substr("000000000000".$valeur,-10,10);   $valeur=substr($valeur,0,2)." ".substr($valeur,2,2)." ".substr($valeur,4,2)." ".substr($valeur,6,2)." ".substr($valeur,8,2);
   return substr($valeur,0,14);
}

function addCondition($valeur,$init) {
   if($init)
      return ",".$valeur;
   else
      return $valeur;
}
?>
<body class="application">
<form method="post" action="admin_import_clients.php?ACTE=1" ENCTYPE="multipart/form-data" >
   <table class="menu_haut_resultat">
      <tr>
         <td class="title" colspan = 12>Client</td>
      </tr>
      <tr>
         <td class="requet_right">SIRET</td>
         <td class="requet_left"><select name="champ_Siret"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">RAISON SOCIALE</td>
         <td class="requet_left"><select name="champ_Raison_sociale"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ADRESSE 1</td>
         <td class="requet_left"><select name="champ_Adresse1"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ADRESSE 2</td>
         <td class="requet_left"><select name="champ_Adresse2"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ADRESSE 3</td>
         <td class="requet_left"><select name="champ_Adresse3"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">CODE POSTAL</td>
         <td class="requet_left"><select name="champ_Cp"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
      </tr>
      <tr>
         <td class="requet_right">VILLE</td>
         <td class="requet_left"><select name="champ_Ville"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">TELEPHONE</td>
         <td class="requet_left"><select name="champ_Telephone"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">TELECOPIE</td>
         <td class="requet_left"><select name="champ_Fax"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">CODE NAF</td>
         <td class="requet_left"><select name="champ_Code_naf"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">STATUT</td>
         <td class="requet_left"><select name="champ_Tva_intra"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ACTIVITE</td>
         <td class="requet_left"><select name="champ_Prio"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
      </tr>
      <tr>
         <td class="requet_right">EFFECTIF</td>
         <td class="requet_left"><select name="champ_Code_effectif"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">CIBLE</td>
         <td class="requet_left"><select name="champ_Id_type"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">GROUPE</td>
         <td class="requet_left"><select name="champ_Groupe"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">SOURCE</td>
         <td class="requet_left"><select name=" "><option value ="1" SELECTED>laisser</option><option value ="2">changer</option><option value ="3">ajouter</option></td>
         <td class="requet_right">COMMERCIAL</td>
         <td class="requet_left"><select name="champ_Id_utilisateur"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option><option value ="3">ajouter</option></td>
         <td class="requet_right" colspan="2"></td>
      </tr>
      <tr>
         <td class="title" colspan = 12>Interlocuteur</td>
      </tr>
      <tr>
         <td class="requet_right">CIVILITE</td>
         <td class="requet_left"><select name="champ_Id_civilite"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select>
         <td class="requet_right">NOM</td>
         <td class="requet_left"><select name="champ_Nom"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">PRENOM</td>
         <td class="requet_left"><select name="champ_Prenom"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">TELEPHONEI</td>
         <td class="requet_left"><select name="champ_TelephoneI"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">TELECOPIEI</td>
         <td class="requet_left"><select name="champ_FaxI"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">MAILI</td>
         <td class="requet_left"><select name="champ_Mail"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
      </tr>
      <tr>
         <td class="requet_right">SERVICE</td>
         <td class="requet_left"><select name="champ_Id_decision"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">FONCTION</td>
         <td class="requet_left"><select name="champ_Id_fonction"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">ADRESSE 1I</td>
         <td class="requet_left"><select name="champ_Adresse1I"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ADRESSE 2I</td>
         <td class="requet_left"><select name="champ_Adresse2I"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">ADRESSE 3I</td>
         <td class="requet_left"><select name="champ_Adresse3I"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">CPI</td>
         <td class="requet_left"><select name="champ_CpI"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
      </tr>
      <tr>
         <td class="requet_right">VILLEI</td>
         <td class="requet_left"><select name="champ_VilleI"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right">MOBILEI</td>
         <td class="requet_left"><select name="champ_Mobile"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></select></td>
         <td class="requet_right">DECIDEUR</td>
         <td class="requet_left"><select name="champ_Decideur"><option value ="1" SELECTED>laisser</option><option value ="2">changer</option></td>
         <td class="requet_right" colspan="6"></td>
      </tr>
      <tr>
         <td class="title" colspan = 12>Import</td>
      </tr>
		<tr>
			<td class="interne_actif" colspan="2">Mode Staging<input type="checkbox" name="staging" value="1"></td>
			<td class="menu_haut" colspan="10">Fichier a importer :
			<input type="file" name="userfile" >
			<input type="submit" value="Importer"></td>
		</tr>
		<tr>
			<td colspan = 12>
<BR>Exemple de fichier a traiter : 
<BR>SIRET;RAISON SOCIALE;GROUPE;SOURCE;CIBLE;ACTIVITE;CODE NAF;STATUT;EFFECTIF;ADRESSE 1;ADRESSE 2;ADRESSE 3;CODE POSTAL;VILLE;COMMERCIAL;TELEPHONE;TELECOPIE;NOM;PRENOM;FONCTION;MAILI
<BR>31745343900026;SOCIETE LAONNOISE DE TRAVAUX PUBLICS;;PROSPEC 2016-1;PME - PMI;CONSTRUCTION - BATIMENT - TRAV;4221Z;Prospect;100 à 199 salaries;13 Rue DE LA RIVIERE;;;02000;ETOUVELLES;DZIDZIAN;03.23.26.30.00;03.23.20.67.00;MIGOT;Jacques;President;sltp@sltp.fr
<BR>43031309800013;ENSIVAL-MORET FRANCE;;PROSPEC 2016-1;PME - PMI;INDUSTRIE - PRODUCTION - FABRI;2813Z;Prospect;250 à 499 salaries;1 Chemin DES PONTS ET CHAUSSEES;;;02100;SAINT QUENTIN;DZIDZIAN;03.23.62.91.00;03.23.62.02.30;DUPREZ;Jerome François Denis;Président;emstquentin@em-pumps.com
<BR>
<BR>La reconnaissance du client se fait sur le siret ou le couple (Raison Sociale / Code postal)
<BR>La reconnaissance de l'interlocuteur se fait sur le nom
			</td>
		</tr>
	</table>
</form>
<?
if($ACTE==1){
   //Variable du fichier uploadï¿½
   $tempfile_name=$_FILES['userfile']['tmp_name'];
   $dest_file="temp/".$_FILES['userfile']['name'];
   move_uploaded_file($tempfile_name, $dest_file); 
   //$size=$HTTP_POST_FILES['userfile']['size'];
   //$type=$HTTP_POST_FILES['userfile']['type'];
   //Vï¿½rification
   /*echo $_FILES['userfile']['tmp_name'] ;
   if(empty($userfile)){
      die("Il n'y a pas de fichier !");
      return;
   }*/
   //Ouverture du fichier d'import
   if($fp=@fopen($dest_file,"r")){
   //if($fp=fopen("C:\\xampp\htdocs\matb\import.csv","r")){
      $resultat= new db_sql();
      /**$resultat->q("delete from client");
      $resultat->q("delete from client_marquage");
      $resultat->q("delete from client_commentaire");
      $resultat->q("delete from client_statut"); 
      $resultat->q("delete from marquage");
      $resultat->q("delete from portefeuille"); 
      $resultat->q("delete from type");  
      $resultat->q("delete from prio");  
      $resultat->q("delete from civilite");  
      $resultat->q("delete from fonction");  
      $resultat->q("delete from contact");  
      $resultat->q("delete from affaire");  
      $resultat->q("delete from affaire_detail");  
      $resultat->q("delete from affaire_histo");  
      $resultat->q("delete from histo_connect");  
      $resultat->q("delete from interaction"); */ 
           
      $ligne = fgets($fp);
      $titre = split(";",$ligne);
      foreach ($titre as $n=>$libelle) {
         $numero[trim($libelle)] = $n;
      }
      while (!feof($fp)) {
         $ligne=fgets($fp);
         if ($ligne) {
            $champ = split(";",$ligne);
            /*$id_statut = renvoie_id_statut($champ[$numero['STATUT']]); 
            $id_prio=renvoie_id_prio($champ[$numero['ACTIVITE']]); 
            $id_code_effectif =renvoie_id_code_effectif($champ[$numero['EFFECTIF']]);
            $id_type =renvoie_id_type($champ[$numero['CIBLE']]);
            $id_source = renvoie_id_source($champ[$numero['SOURCE']]);
            $id_commercial=renvoie_id_commercial($champ[$numero['COMMERCIAL']]);
            $id_civilite = renvoie_id_civilite(substr($champ[$numero['CIVILITE']],0,4));
            $id_decision = renvoie_id_decision($champ[$numero['SERVICE']]);
            $id_fonction = renvoie_id_fonction($champ[$numero['FONCTION']]);
            if (!$id_statut || !$id_prio || !$id_code_effectif || !$id_type || !$id_source || !$id_commercial || !$id_civilite || !$id_decision || !$id_fonction ){
               echo "La ligne ".$ligne." n'a pas ï¿½tï¿½ traitee<BR>";
               continue;
            }*/
            //if ( $champ[$numero['RAISON SOCIALE']] != $ancienne_raison_sociale ) {
               //nouveau client 
               //$ancienne_raison_sociale = $champ[$numero['RAISON SOCIALE']];
               $requete="
                  SELECT
	                  Id_client
                  FROM
                     client
                  WHERE
                     (Raison_sociale = ".transcote($champ[$numero['RAISON SOCIALE']])."
                     and Cp = ".transcote($champ[$numero['CODE POSTAL']]).") or (
					 Siret = ".transcote($champ[$numero['SIRET']]).")"; 
               $res = new db_sql($requete);
               if(isEmpty_Record($res)){
                  echo "Insertion Client ".$champ[$numero['RAISON SOCIALE']]."<BR>";
                  $id_statut = renvoie_id_statut($champ[$numero['STATUT']]); 
                  $id_prio=renvoie_id_prio($champ[$numero['ACTIVITE']]); 
                  $id_code_effectif =renvoie_id_code_effectif($champ[$numero['EFFECTIF']]);
                  $id_type =renvoie_id_type($champ[$numero['CIBLE']]);
                  $id_source = renvoie_id_source($champ[$numero['SOURCE']]);
                  $id_commercial=renvoie_id_commercial($champ[$numero['COMMERCIAL']]);
                  if (!$id_statut || !$id_prio || !$id_code_effectif || !$id_type || !$id_source || !$id_commercial ){
                     echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 0(".$ligne.") ( $id_statut | $id_prio | $id_code_effectif | $id_type | $id_source | $id_commercial )<BR>";
                     continue;
                  }
                  if(!$_POST['staging']){
                     //$Id_client_courant++;
                     $resultat->q("
                        INSERT INTO client (
                           Siret, 
                           Raison_sociale, 
                           Adresse1, 
                           Adresse2, 
                           Adresse3, 
                           Cp, 
                           Ville, 
                           Telephone, 
                           Fax, 
                           Code_naf, 
                           Tva_intra, 
                           Prio, 
                           Code_effectif, 
                           Id_type, 
                           Groupe)                
                        VALUES (
                           "//.$Id_client_courant.",
                           .transcote($champ[$numero['SIRET']]).", 
                           ".transcote($champ[$numero['RAISON SOCIALE']]).", 
                           ".transcote($champ[$numero['ADRESSE 1']]).", 
                           ".transcote($champ[$numero['ADRESSE 2']]).", 
                           ".transcote($champ[$numero['ADRESSE 3']]).", 
                           ".transcote($champ[$numero['CODE POSTAL']]).", 
                           ".transcote($champ[$numero['VILLE']]).", 
                           ".transcote(traiter_field_tel($champ[$numero['TELEPHONE']])).", 
                           ".transcote(traiter_field_tel($champ[$numero['TELECOPIE']])).", 
                           ".transcote(substr($champ[$numero['CODE NAF']],0,4)).", 
                           ".$id_statut.", 
                           ".$id_prio.", 
                           ".transcote($id_code_effectif).", 
                           ".$id_type.", 
                           ".transcote($champ[$numero['GROUPE']])." 
                     )");
                     $Id_client_courant = db_sql::last_id (); 
                  } else {
                     $Id_client_courant = 1000000;
                  }
                  if ($id_source != "null") { 
                     echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion source : ".$champ[$numero['SOURCE']]."<BR>";
                     if(!$_POST['staging']){
                        $resultat= new db_sql();                  
                        $resultat->q("INSERT INTO client_marquage values (".$Id_client_courant.",".$id_source.")");
                     }
                  }
                  if ($id_commercial != "null") {
                     echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion affectation : ".$champ[$numero['COMMERCIAL']]."<BR>";
                     if(!$_POST['staging']){
                        $resultat= new db_sql(); 
                        $resultat->q("INSERT INTO portefeuille values (".$id_commercial.",".$Id_client_courant.")");
                     }
                  }
                  //echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp OK<BR>";
                  flush();
               } else {
                  echo "Client ".$champ[$numero['RAISON SOCIALE']]." deja existant<BR>";
                  if($_POST['champ_Tva_intra']!="1"){
                     $id_statut = renvoie_id_statut($champ[$numero['STATUT']]); 
                     if (!$id_statut){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 1(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  if($_POST['champ_Prio']!="1"){
                     $id_prio=renvoie_id_prio($champ[$numero['ACTIVITE']]); 
                     if (!$id_prio){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 2(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  if($_POST['champ_Code_effectif']!="1"){
                     $id_code_effectif =renvoie_id_code_effectif($champ[$numero['EFFECTIF']]);
                     if (!$id_code_effectif){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 3(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  if($_POST['champ_Id_type']!="1"){
                     $id_type =renvoie_id_type($champ[$numero['CIBLE']]);
                     if (!$id_type){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 4(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  if($_POST['champ_Id_marquage']!="1"){
                     $id_source = renvoie_id_source($champ[$numero['SOURCE']]);
                     if (!$id_source || $id_source == "null"){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 5(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  if($_POST['champ_Id_utilisateur']!="1"){
                     $id_commercial=renvoie_id_commercial($champ[$numero['COMMERCIAL']]);
                     if (!$id_commercial|| $id_commercial == "null"){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 6(".$ligne.")<BR>";
                        continue;
                     }
                  }
                  $Id_client_courant =$res->f('Id_client');
                  if($_POST['champ_Siret']=="2" || $_POST['champ_Raison_sociale']=="2"|| $_POST['champ_Adresse1']=="2"|| $_POST['champ_Adresse2']=="2"|| $_POST['champ_Adresse3']=="2"|| $_POST['champ_Cp']=="2"||$_POST['champ_Ville']=="2" || $_POST['champ_Telephone']=="2"|| $_POST['champ_Fax']=="2"|| $_POST['champ_Code_naf']=="2"|| $_POST['champ_Tva_intra']=="2"|| $_POST['champ_Prio']=="2"|| $_POST['champ_Code_effectif']=="2"|| $_POST['champ_Id_type']=="2"|| $_POST['champ_Groupe']=="2"){
                     $msg = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Mise a jour des champs ";
                     $requete="
                        UPDATE
	                        client
                           SET "; 
                      $init = false;
                      if($_POST['champ_Siret']=="2"){
                         $requete.="Siret=".transcote($champ[$numero['SIRET']]);
                         $msg .= "Siret";
                         $init = true;
                      }
                      if($_POST['champ_Raison_sociale']=="2"){
                         $requete.=addCondition("Raison_sociale=".transcote($champ[$numero['RAISON SOCIALE']]),$init);
                         $msg.=addCondition("Raison Sociale",$init);
                         $init = true;
                      }
                      if($_POST['champ_Adresse1']=="2"){
                         $requete.=addCondition("Adresse1=".transcote($champ[$numero['ADRESSE 1']]),$init);
                         $msg.=addCondition("Adresse",$init);
                         $init = true;
                      }
                      if($_POST['champ_Adresse2']=="2"){
                         $requete.=addCondition("Adresse2=".transcote($champ[$numero['ADRESSE 2']]),$init);
                         $msg.=addCondition("Adresse 2",$init);
                         $init = true;
                      }
                      if($_POST['champ_Adresse3']=="2"){
                         $requete.=addCondition("Adresse3=".transcote($champ[$numero['ADRESSE 3']]),$init);
                         $msg.=addCondition("Adresse 3",$init);
                         $init = true;
                      }
                      if($_POST['champ_Cp']=="2"){
                         $requete.=addCondition("Cp=".transcote($champ[$numero['CODE POSTAL']]),$init);
                         $msg.=addCondition("Code postal",$init);
                         $init = true;
                      }
                      if($_POST['champ_Ville']=="2"){
                         $requete.=addCondition("Ville=".transcote($champ[$numero['VILLE']]),$init);
                         $msg.=addCondition("Ville",$init);
                         $init = true;
                      }
                      if($_POST['champ_Telephone']=="2"){
                         $requete.=addCondition("Telephone=".transcote(traiter_field_tel($champ[$numero['TELEPHONE']])),$init);
                         $msg.=addCondition("Telephone",$init);
                         $init = true;
                      }
                      if($_POST['champ_Fax']=="2"){
                         $requete.=addCondition("Fax=".transcote(traiter_field_tel($champ[$numero['TELECOPIE']])),$init);
                         $msg.=addCondition("Fax",$init);
                         $init = true;
                      }
                      if($_POST['champ_Code_naf']=="2"){
                         $requete.=addCondition("Code_naf=".transcote(substr($champ[$numero['CODE NAF']],0,4)),$init);
                         $msg.=addCondition("Code Naf",$init);
                         $init = true;
                      }
                      if($_POST['champ_Tva_intra']=="2"){
                         $requete.=addCondition("Tva_intra=".$id_statut,$init);
                         $msg.=addCondition("Statut",$init);
                         $init = true;
                      }
                      if($_POST['champ_Prio']=="2"){
                         $requete.=addCondition("Prio=".$id_prio,$init);
                         $msg.=addCondition("Activitï¿½",$init);
                         $init = true;
                      }
                      if($_POST['champ_Code_effectif']=="2"){
                         $requete.=addCondition("Code_effectif=".$id_code_effectif,$init);
                         $msg.=addCondition("Effectif",$init);
                         $init = true;
                      }
                      if($_POST['champ_Id_type']=="2"){
                         $requete.=addCondition("Id_type=".$id_type,$init);
                         $msg.=addCondition("Cible",$init);
                         $init = true;
                      } 
                      if($_POST['champ_Groupe']=="2"){
                         $requete.=addCondition("Groupe=".transcote($champ[$numero['GROUPE']]),$init);
                         $msg.=addCondition("Groupe",$init);
                         $init = true;
                      }
                      $requete.=" WHERE Id_client=".$Id_client_courant;
                      if(!$_POST['staging']){
                         $resultat= new db_sql();                  
                         $resultat->q($requete);
                      }
                      $msg.=" du client ".$champ[$numero['RAISON SOCIALE']]."<BR>";
                      echo $msg;
                      echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp OK<BR>";
                  }
                  if($_POST['champ_Id_marquage']=="2" && $id_source != "null"){
                     $nb = req_sim("SELECT count(1) as compte FROM client_marquage WHERE Id_client=".$Id_client_courant,"compte");
                     if($nb==0||$nb>1){
                        if($nb>1){
                           echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Suppression des sources existantes<BR>";
                           if(!$_POST['staging']){
                              $resultat= new db_sql();                  
                              $resultat->q("DELETE FROM client_marquage WHERE Id_client=".$Id_client_courant);
                           }
                        }
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion source : ".$champ[$numero['SOURCE']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q("INSERT INTO client_marquage values (".$Id_client_courant.",".$id_source.")");
                        }
                     }else{
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Mise a jour source : ".$champ[$numero['SOURCE']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q("UPDATE client_marquage SET Id_marquage=".$id_source." WHERE Id_client=".$Id_client_courant);
                        }
                     }
                  }
                  if($_POST['champ_Id_marquage']=="3" && $id_source != "null"){
                     $nb = req_sim("SELECT count(1) as compte FROM client_marquage WHERE Id_client=".$Id_client_courant." AND Id_marquage=".$id_source,"compte");
                     if($nb==0){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion source : ".$champ[$numero['SOURCE']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q("INSERT INTO client_marquage values (".$Id_client_courant.",".$id_source.")");
                        }
                     }
                     else{
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Pas d'ajout car source : ".$champ[$numero['SOURCE']]." dï¿½jï¿½ existante<BR>";
                     }
                  }
                  if($_POST['champ_Id_utilisateur']=="2" && $id_commercial != "null"){
                     $nb = req_sim("SELECT count(1) as compte FROM portefeuille WHERE Id_client=".$Id_client_courant,"compte");
                     if($nb==0||$nb>1){
                        if($nb>1){
                           echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Suppression des affectations existantes<BR>";
                           if(!$_POST['staging']){
                              $resultat= new db_sql();                  
                              $resultat->q("DELETE FROM portefeuille WHERE Id_client=".$Id_client_courant);
                           }
                        }
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion affectation : ".$champ[$numero['COMMERCIAL']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q("INSERT INTO portefeuille values (".$id_commercial.",".$Id_client_courant.")");
                        }
                     }else{
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Mise ï¿½ jour affectation : ".$champ[$numero['COMMERCIAL']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q("UPDATE portefeuille SET Id_utilisateur=".$id_commercial." WHERE Id_client=".$Id_client_courant);
                        }
                     }
                  }
                  if($_POST['champ_Id_utilisateur']=="3" && $id_commercial != "null"){
                     $nb = req_sim("SELECT count(1) as compte FROM portefeuille WHERE Id_client=".$Id_client_courant." AND Id_utilisateur=".$id_commercial,"compte");
                     if($nb==0){
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Insertion affectation : ".$champ[$numero['COMMERCIAL']]."<BR>";
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                            $resultat->q("INSERT INTO portefeuille values (".$id_commercial.",".$Id_client_courant.")");
                        }
                     }
                     else{
                        echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Pas d'ajout car affectation : ".$champ[$numero['COMMERCIAL']]." dï¿½jï¿½ existante<BR>";
                     }
                  }
						/*$id_source = renvoie_id_source($champ[$numero['SOURCE']]);
						if ($id_source && $id_source != "null") {
						   $requete="
                        SELECT
	                        Id_client
                        FROM
                           client_marquage
                        WHERE
                           Id_client=".$Id_client_courant." AND  
									Id_marquage=".$id_source;
                     $res = new db_sql($requete);
                     //lorsqu'un client est reconnu, on lui met quand mï¿½me la source s'il ne l'a pas dï¿½jï¿½ 
                     if(isEmpty_Record($res) && !$_POST['staging']){
                        $resultat= new db_sql();                  
                        $resultat->q("INSERT INTO client_marquage values (".$Id_client_courant.",".$id_source.")");
                     }
                  } */
               }
            //}
                  if ($champ[$numero['NOM']]) {
                     $requete="
                        SELECT
	                         Id_contact
                        FROM
                           contact
                        WHERE
                           Nom= ".transcote($champ[$numero['NOM']])." and
                           Id_client = ".$Id_client_courant;
                     $res = new db_sql($requete);
                     if(isEmpty_Record($res)){
                        echo "Insertion interlocuteur ".$champ[$numero['NOM']]."<BR>";
                        $id_civilite = renvoie_id_civilite(substr($champ[$numero['CIVILITE']],0,4));
                        $id_decision = renvoie_id_decision($champ[$numero['SERVICE']]);
                        $id_fonction = renvoie_id_fonction($champ[$numero['FONCTION']]);
                        if (!$id_civilite || !$id_decision || !$id_fonction ){
                           echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 7(".$ligne.")<BR>";
                           continue;
                        }
                        if(!$_POST['staging']){
                           //$Id_contact_courant++;
                           $resultat= new db_sql();
                           $resultat->q("
                              INSERT INTO contact (
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
                              VALUES (
                                 "//.$Id_contact_courant.",
                                 .$Id_client_courant.", 
                                 ".$id_civilite.",
                                 ".transcote($champ[$numero['NOM']]).",
                                 ".transcote($champ[$numero['PRENOM']]).",
                                 ".transcote(traiter_field_tel($champ[$numero['TELEPHONEI']])).", 
                                 ".transcote(traiter_field_tel($champ[$numero['TELECOPIEI']])).", 
                                 ".transcote(str_replace ($champ[$numero['MAILI']]," ","")).", 
                                 ".$id_decision.",
                                 ".$id_fonction.",
                                 ".transcote($champ[$numero['ADRESSE 1I']]).",
                                 ".transcote($champ[$numero['ADRESSE 2I']]).",
                                 ".transcote($champ[$numero['ADRESSE 3I']]).",
                                 ".transcote($champ[$numero['CPI']]).",
                                 ".transcote($champ[$numero['VILLEI']]).",
                                 ".transcote(str_replace (str_pad($champ[$numero['MOBILEI']],10,'0',STR_PAD_LEFT)," ","")).", null)");
                        flush();
                     }
                     
                  } else {
                     echo "Interlocuteur ".$champ[$numero['NOM']]." deja existant<BR>";
                     if($_POST['champ_Id_civilite']=="2" || $_POST['champ_Nom']=="2"|| $_POST['champ_Prenom']=="2"|| $_POST['champ_TelephoneI']=="2"|| $_POST['champ_FaxI']=="2"|| $_POST['champ_Mail']=="2"||$_POST['champ_Id_decision']=="2" || $_POST['champ_Id_fonction']=="2"|| $_POST['champ_Adresse1I']=="2"|| $_POST['champ_Adresse2I']=="2"|| $_POST['champ_Adresse3I']=="2"|| $_POST['champ_CpI']=="2"|| $_POST['champ_VilleI']=="2"|| $_POST['champ_Mobile']=="2"|| $_POST['champ_Decideur']=="2"){
                        $Id_contact_courant =$res->f('Id_contact');
                        $msg = "&nbsp&nbsp&nbsp Mise a jour des champs ";
                        $requete="
                           UPDATE
	                           contact
                           SET "; 
                        $init = false;
                        if($_POST['champ_Id_civilite']=="2"){
                           $id_civilite = renvoie_id_civilite(substr($champ[$numero['CIVILITE']],0,4));
                           if (!$id_civilite){
                              echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 8(".$ligne.")<BR>";
                              continue;
                           } else {
                              $requete.="Id_civilite=".$id_civilite;
                              $msg .= "Civilitï¿½";
                              $init = true;
                           }
                        }
                        if($_POST['champ_Id_decision']=="2"){
                           $id_decision = renvoie_id_decision($champ[$numero['SERVICE']]);
                           if (!$id_decision){
                              echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 9(".$ligne.")<BR>";
                              continue;
                           } else {
                              $requete.=addCondition("Id_decision=".$id_decision,$init);
                              $msg.=addCondition("Service",$init);
                              $init = true;
                           }
                        }
                        if($_POST['champ_Id_fonction']=="2"){
                           $id_fonction = renvoie_id_fonction($champ[$numero['FONCTION']]);
                           if (!$id_fonction){
                              echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp -> Ligne non traitee 10(".$ligne.")<BR>";
                              continue;
                           } else {
                              $requete.=addCondition("Id_fonction=".$id_fonction,$init);
                              $msg.=addCondition("Fonction",$init);
                              $init = true;
                           }
                        }
                        if($_POST['champ_Nom']=="2"){
                           $requete.=addCondition("Nom=".transcote($champ[$numero['NOM']]),$init);
                           $msg.=addCondition("Nom",$init);
                           $init = true;
                        }
                        if($_POST['champ_Prenom']=="2"){
                           $requete.=addCondition("Prenom=".transcote($champ[$numero['PRENOM']]),$init);
                           $msg.=addCondition("Prenom",$init);
                           $init = true;
                        }
                        if($_POST['champ_TelephoneI']=="2"){
                           $requete.=addCondition("Telephone=".transcote(traiter_field_tel($champ[$numero['TELEPHONEI']])),$init);
                           $msg.=addCondition("Tï¿½lï¿½phone",$init);
                           $init = true;
                        }
                        if($_POST['champ_FaxI']=="2"){
                           $requete.=addCondition("Fax=".transcote(traiter_field_tel($champ[$numero['TELECOPIEI']])),$init);
                           $msg.=addCondition("Fax",$init);
                           $init = true;
                        }
                        if($_POST['champ_Mail']=="2"){
                           $requete.=addCondition("Mail=".transcote(str_replace($champ[$numero['MAILI']]," ","")),$init);
                           $msg.=addCondition("Mail",$init);
                           $init = true;
                        }
                        if($_POST['champ_Adresse1I']=="2"){
                           $requete.=addCondition("Adresse1=".transcote($champ[$numero['ADRESSE 1I']]),$init);
                           $msg.=addCondition("Adresse",$init);
                           $init = true;
                        }
                        if($_POST['champ_Adresse2I']=="2"){
                           $requete.=addCondition("Adresse2=".transcote($champ[$numero['ADRESSE 2I']]),$init);
                           $msg.=addCondition("Adresse 2",$init);
                           $init = true;
                        }
                        if($_POST['champ_Adresse3I']=="2"){
                           $requete.=addCondition("Adresse3=".transcote($champ[$numero['ADRESSE 3I']]),$init);
                           $msg.=addCondition("Adresse 3",$init);
                           $init = true;
                        }
                        if($_POST['champ_CpI']=="2"){
                           $requete.=addCondition("Cp=".transcote($champ[$numero['CPI']]),$init);
                           $msg.=addCondition("Code postal",$init);
                           $init = true;
                        }
                        if($_POST['champ_VilleI']=="2"){
                           $requete.=addCondition("Ville=".transcote($champ[$numero['VILLEI']]),$init);
                           $msg.=addCondition("Ville",$init);
                           $init = true;
                        }
                        if($_POST['champ_Mobile']=="2"){
                           $requete.=addCondition("Mobile=".transcote(str_replace (str_pad($champ[$numero['MOBILEI']],10,'0',STR_PAD_LEFT)," ","")),$init);
                           $msg.=addCondition("Mobile",$init);
                           $init = true;
                        } 
                        if($_POST['champ_Decideur']=="2"){
                           $requete.=addCondition("Decideur=null",$init);
                           $msg.=addCondition("Decideur",$init);
                           $init = true;
                        }
                        $requete.=" WHERE Id_contact=".$Id_contact_courant;
                        if(!$_POST['staging']){
                           $resultat= new db_sql();                  
                           $resultat->q($requete);
                        }
                        $msg.=" de l'interlocuteur ".$champ[$numero['NOM']]."<BR>";
                        echo $msg;
                     }
                  }
               }
               
            //}   
         }
		 echo '<br>';
      }
      echo "FIN DU FICHIER<BR>";
   } else {
      echo "Pas reussi a ouvrir le fichier";             
   }
}
include ("ress/enpied.php");
?>
