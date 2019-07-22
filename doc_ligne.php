<?
/*------ Ecran du Requêteur-------*/
//Paramètres d'appels externes 
// $onglet_courant qui contient le produit

//Appels internes
// le même +
// $ACTE (1 pour les lignes de produit)
// $id_data_set + les champs du dataset

//Charger le type de dataset
if(!$type_dataset_ligne) {
?>
	<input type="hidden" id="current_product" name="onglet_courant" value="<? echo $onglet_courant; ?>">
<?
	//var_dump($onglet_courant);
	require("param_2_produits/".$onglet_courant.".php");
   //$type_dataset_produit = $parms[produits][$onglet_courant][type_dataset_ligne];
   //$type_dataset_groupe_ligne = $parms[produits][$onglet_courant][type_dataset_groupe_ligne];
   $type_dataset_produit = $produits[$onglet_courant][type_dataset_ligne];
   $type_dataset_groupe_ligne = $produits[$onglet_courant][type_dataset_groupe_ligne];
   $dataset_groupe_ligne= new dataset($type_dataset_groupe_ligne);
   $doc = charge_un("select id_dataset from doc_groupe_ligne where id_doc = $id_doc and produit='$onglet_courant'"); 
   $id_dataset_produit_empied = $doc[id_dataset];
   if ($ACTE==1 and $message ) {
		if(!$id_dataset_produit_empied) 
			$id_dataset_produit_empied =-1;
		$dataset_groupe_ligne->id_dataset = $id_dataset_produit_empied;
		$dataset_groupe_ligne->load_from_screen();
   } else if ($id_dataset_produit_empied) {
      $dataset_groupe_ligne->load_from_database($id_dataset_produit_empied);
   }
} else {
   $type_dataset_produit =$type_dataset_ligne;
}
//Crée l'objet dataset
$dataset = new dataset($type_dataset_produit);
if($type_dataset_groupe_ligne) {
   if($lignenonmodifiable) $dataset_groupe_ligne->set_modifiable($lignenonmodifiable);
   if(!$dataset_groupe_ligne->id_dataset) $dataset_groupe_ligne->set_id_dataset(-1);
	?><input type="hidden" name="liste_dataset[<? echo $dataset_groupe_ligne->id_dataset*1 ?>]" value="<? echo $dataset_groupe_ligne->type_dataset ?>"><?
   echo($dataset_groupe_ligne->html()); 
}
// *******************************************
// Affichage des entêtes
// *******************************************

echo($dataset->html_entete());

// *******************************************
// Affichage des lignes
// *******************************************
$nb_ligne = 0;
$liste_ligne = charge("select id_dataset from doc_ligne where id_doc = $id_doc and produit = '$onglet_courant'"); 
foreach ($liste_ligne as $i => $info) {

   $nb_ligne++;
   $id_dataset_courant = $info[id_dataset];
   
	$dataset = new dataset($type_dataset_produit);

   if ($ACTE==1 and $message and $message!=FORBIDDEN) {
      $dataset->id_dataset = $id_dataset_courant;
      $dataset->load_from_screen();
   } else {
		if($lignenonmodifiable)
			$dataset->nonmodifiable=$lignenonmodifiable;
		if($statut_value) 
			$dataset->statut_value=$statut_value;
		$dataset->load_from_database($id_dataset_courant);
   }

	?><input type="hidden" name="liste_dataset[<? echo $dataset->id_dataset*1 ?>]" value="<? echo $dataset->type_dataset ?>"><?
   
   $dataset->formname ='formulaire';  
   echo($dataset->html());

}

//Affichage de la nouvelle ligne
if(!$type_dataset_ligne) {
	$dataset = new dataset($type_dataset_produit);
	if ($ACTE==1 and $message and $liste_dataset_before_post['']==$type_dataset_produit ) {
		$dataset->id_dataset = $id_dataset;
		$dataset->load_from_screen();
		echo($dataset->html());
		?><input type="hidden" name="liste_dataset[<? echo $dataset->id_dataset*1 ?>]" value="<? echo $dataset->type_dataset ?>"><?
	} else if (($ADD==1 ||($DELETE == 1 && $nb_ligne==0) ) and !$message){    
		echo($dataset->html());
		//On initialise le filtre pour la nouvelle ligne
		if($lignenonmodifiable)
			$dataset->set_modifiable($lignenonmodifiable);
		if($statut_value)
			$dataset->statut_value=$statut_value;
		?><input type="hidden" name="liste_dataset[<? echo $dataset->id_dataset*1 ?>]" value="<? echo $dataset->type_dataset ?>"><?

	}
if(!$lignenonmodifiable)
	echo"\n <tr>\n  <td><input class=\"requeteur_button\" onclick=\"document.forms['formulaire'].ADD.value='1';\" type=\"submit\" name=\"Submit\" value=\"Ajouter\" ></td>\n </tr>";
}