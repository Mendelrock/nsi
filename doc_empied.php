<?
/*------ Ecran du Requ�teur-------*/

//Param�tres d'appels externes
//    $id_doc : Mise � jour d'un doc

//Appels internes
// le m�me +
// ACTE

$doc = charge_un("select * from doc where id_doc = $id_doc");
//Charge le N�dataset s'il existe
$id_dataset_empied = $doc[id_dataset_empied];
//Charge le type de doc
$type_doc = $doc[type_doc];
//pour en d�duire le type de dataset
charge_doc($type_doc);
$type_dataset_empied = $parms[type_docs][$type_doc][type_dataset_empied];
//Cr�e l'objet dataset
$dataset = new dataset($type_dataset_empied);

// Si sauvegarde, je le fais
if ($ACTE == 1 && !StartsWith($_POST['type_doc'],"OF - ")) {
   $dataset->load_from_screen();
   $retour = $dataset->validate();
   if ($retour) {
      $message = $retour;
   } else {
      if($id_dataset_empied) {
         $dataset->store();
      } else {
         $id_dataset_empied = $dataset->store();
         new db_sql("update doc set id_dataset_empied = $id_dataset_empied where id_doc = $id_doc");
      }
   }
} else if ($id_dataset_empied) {
//Sinon, chargement des donn�es dans la base
   $dataset->load_from_database($id_dataset_empied);
}
echo $type_doc;

if(!StartsWith($type_doc,"OF - ") and !StartsWith($type_doc,"BON")){
?>
<form method="post" action="doc_iframe.php?ACTE=1&id_doc=<? echo $id_doc; ?>&onglet_courant=<? echo $onglet_courant; ?>">
<?
}
if($nonmodifiable)
	$dataset->set_modifiable($nonmodifiable);
echo($dataset->html());
if(!StartsWith($type_doc,"OF - ") and !StartsWith($type_doc,"BON")){
?>
</form>
<?
}
?>