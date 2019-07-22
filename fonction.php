<?

require_once("ress/db_mysql.php");
require_once("ress/util.php");
require_once("c_parm.php");

function getTypeDataset($id_dataset = null, $id_doc = null){
	$req = "select type_dataset from dataset";
	if($id_dataset) {
		$req .= " where id_dataset = ".$id_dataset;
	} else if($id_doc) {
		$req .= ",doc where dataset.id_dataset = doc.id_dataset_entete and doc.id_doc = ".$id_doc;
	}
	$tdataset = charge_un($req);
	return $tdataset[type_dataset];
}

function validateIncompatibilite($record){
	if(!$_POST[produit])
		return "Le champ Produit doit être renseigné";
	else if(!$_POST[prop1])
		return "Le champ Propriété 1 doit être renseigné";
	else if(!$_POST[val1])
		return "Le champ Valeur 1 doit être renseigné";
	else if(!$_POST[prop2])
		return "Le champ Propriété 2 doit être renseigné";
	else if(!$_POST[val2])
		return "Le champ Valeur 2 doit être renseigné";
}

function validateIncompatibiliteSurface($record){
	if(!$_POST[produit])
		return "Le champ Produit doit être renseigné";
	else if(!$_POST[prop1])
		return "Le champ Propriété 1 doit être renseigné";
	else if(!$_POST[val1])
		return "Le champ Valeur 1 doit être renseigné";
	else if(!$_POST[prop2])
		return "Le champ Propriété 2 doit être renseigné";
	else if(!$_POST[prop3])
		return "Le champ Propriété 3 doit être renseigné";   ;
}

function validateIncompatibiliteLimites($record){
	if(!$_POST[produit])
		return "Le champ Produit doit être renseigné";
	else if(!$_POST[prop1])
		return "Le champ Propriété 1 doit être renseigné";
	else if(!$_POST[val1])
		return "Le champ Valeur 1 doit être renseigné";
	else if(!$_POST[prop2])
		return "Le champ Propriété 2 doit être renseigné";
}

/*----------------------------------------------------------
 Duplique un doc
------------------------------------------------------------*/

function dupliquer ($id_doc,$tab_prod_dup) {
	if (!count($tab_prod_dup)) return ($id_doc);
	$doc = charge_un("select * from doc where id_doc = $id_doc");
	$type_doc = $doc[type_doc];
	$id_dataset_entete = $doc[id_dataset_entete];
	charge_doc($type_doc);
	$type_dataset_entete = $GLOBALS[parms][type_docs][$type_doc][type_dataset_entete];
	$dataset = new dataset($type_dataset_entete);
	$dataset->load_from_database($id_dataset_entete);
	$dataset->ochamp['statut']->set('En cours');
	$liste_dataset_to_save = array();
	$liste_dataset_to_save[]=$dataset;

	$liste_produit = charge("select distinct produit from doc_ligne where id_doc = $id_doc");
	foreach ($liste_produit as $i => $liste) {
		$onglet_courant = $liste[produit];
		if($tab_prod_dup[$onglet_courant]) {
			require("param_2_produits/".$onglet_courant.".php");
			$type_dataset_produit = $produits[$onglet_courant][type_dataset_ligne];
			$type_dataset_groupe_ligne = $produits[$onglet_courant][type_dataset_groupe_ligne];
			$liste_ligne = charge("select id_dataset from doc_ligne where id_doc = $id_doc and produit = '$onglet_courant'");
			$liste_groupe_ligne = charge("select id_dataset from doc_groupe_ligne where id_doc = $id_doc and produit = '$onglet_courant'");
			$k = 0;

			foreach ($liste_ligne as $j => $info) {
				$id_dataset_courant = $info[id_dataset];
				$dataset = new dataset($type_dataset_produit);
				$dataset->load_from_database($id_dataset_courant);
				$liste_dataset_to_save[$onglet_courant.'_'.$k]=$dataset;
				$k++;
			}

			foreach ($liste_groupe_ligne as $j => $info) {
				$id_dataset_courant = $info[id_dataset];
				$dataset = new dataset($type_dataset_groupe_ligne);
				$dataset->load_from_database($id_dataset_courant);
				$liste_dataset_to_save[$onglet_courant.'_'.$k]=$dataset;
				$k++;
			}
		}
	}

	$dbres= new db_sql();
	$dbres->begin();
	foreach ($liste_dataset_to_save as $key => $dataset_to_save) {
		$dataset_to_save->id_dataset = -1;
		$id_dataset = $dataset_to_save->id_dataset;
		$dataset_to_save->store($dbres);
		if(!$id_dataset||$id_dataset<0) {
			$val = explode('_',$key);
			$onglet_courant = $val[0];
			if($dataset_to_save->type_dataset ==$type_dataset_entete) {
				$dbres->q("insert into doc(type_doc,id_dataset_entete) values ('$type_doc', $dataset_to_save->id_dataset)");
				$id_doc = db_sql::last_id ();
			} else if(StartsWith($dataset_to_save->type_dataset,"fdcproduit")) {
				$val = explode('_',$key);
				$onglet_courant = $val[0];
				$dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc, '$onglet_courant', $dataset_to_save->id_dataset)");
			} else if(StartsWith($dataset_to_save->type_dataset,"produitentete")) {
				$dbres->q("insert into doc_groupe_ligne(id_doc, produit, id_dataset) values ($id_doc, '$onglet_courant', $dataset_to_save->id_dataset)");
			}
		}
	}

	$dbres->commit();
	return $id_doc;
}

/*----------------------------------------------------------
incompatibilité post
------------------------------------------------------------*/

function check_incompatibilité($dataset){
	$resultat = charge_un("SELECT produit FROM doc_ligne WHERE id_dataset ='".$dataset->id_dataset."'");
	$produit =  $resultat[produit];

	if(!$produit){
		$produit = $GLOBALS[onglet_courant];
	}

	$list_values = charge("
		select
			prop1,
			val1,
			prop2,
			val2
		from 
			incompatibilites_post
		where 
			produit = '".$produit."'");

	foreach ($list_values as $i => $value) {
		if ($value[val1] == "*" or $value[val1] == $dataset->ochamp[$value[prop1]]->get()) {
			if($value[val2] == "*" or $value[val2] == $dataset->ochamp[$value[prop2]]->get()) {
				return  "La valeur <b>".$value[val2]."</b> sur le champ <b>".$dataset->ochamp[$value[prop2]]->lb_champ."</b> est incompatible avec la valeur <b>".$value[val1]."</b> sur le champ <b>".$dataset->ochamp[$value[prop1]]->lb_champ."</b>";
			}
		}
	}

}

/*----------------------------------------------------------
incompatibilité dimension post
------------------------------------------------------------*/

function check_incompatibilite_dimension($dataset){
    $resultat = charge_un("SELECT produit FROM doc_ligne WHERE id_dataset ='".$dataset->id_dataset."'");
    $produit =  $resultat[produit];

    if(!$produit){
        $produit = $GLOBALS[onglet_courant];
    }

    $list_values = charge("
		select
			prop1,
			val1,
			prop2,
			val2,
			prop3,
			val3
		from 
			incompatibilites_dimension_post
		where 
			produit = '".$produit."'");

	foreach ($list_values as $i => $value) {
		$champ1 = $dataset->ochamp[$value[prop1]];
		if (is_object($champ1)) {
		if (eval("return (\"".$champ1->get()."\" ".$value[val1].");")) {
			$champ2 = $dataset->ochamp[$value[prop2]];
			if (is_object($champ2)) {
			if (eval("return (\"".$champ2->get()."\" ".$value[val2].");")) {
				$champ3 = $dataset->ochamp[$value[prop3]];
				if (is_object($champ3)) {
				if (eval("return (\"".$champ3->get()."\" ".$value[val3].");")) {
               return  "La combinaison :<br /><b>".$dataset->ochamp[$value[prop1]]->lb_champ."</b> ".$value[val1]." et <br />
               <b>".$dataset->ochamp[$value[prop2]]->lb_champ."</b> ".$value[val2]." et <br />   
               <b>".$dataset->ochamp[$value[prop3]]->lb_champ."</b> ".$value[val3].
					"<br /> est impossible.";
            }
				}
         }
			}
      }
		}
   }
}

/*----------------------------------------------------------
 Traite des valeurs particulières // A dégager asap
------------------------------------------------------------*/

function get_value ($produit, $produit_modele,$valeur) {
	return $valeur;
}

/*----------------------------------------------------------
 Retourne si une clé en miniscule existe
------------------------------------------------------------*/

function array_key_tolower_exists ($cle,$tableau)  {
	$ret= "";
	foreach ($tableau as $key=>$value) {
		if(strtolower($key)==strtolower($cle)) return $key;
	}
	return $ret;
}

/*----------------------------------------------------------
 Retourne l'équivalent d'une valeur du site dans NSI
------------------------------------------------------------*/

function getEquivalentValeur ($listevaleur,$valeursite,$site_produit,$site_propriete,$type, $nsi_propriete="") {

	//rechercher parmi toutes les valeurs de NSI
	$valeursite = str_replace(" "," ",$valeursite) ;
	$ret ="" ;
	if (in_array(trim($valeursite),$listevaleur)) $ret = trim($valeursite);
	//recherche dans table de correspondance
	else{
		//$valeursite = get_value ($valeursite);
		$valeursite_temp = preg_replace('/\s+/', '',$valeursite);
		$req="
			SELECT
				*
			FROM
				correspondance
			WHERE
				/*site_valeur LIKE '%$valeursite%'*/
				REPLACE(site_valeur, ' ', '' ) LIKE '%$valeursite_temp%'";

		if($site_produit) $req.=" AND site_produit LIKE '%$site_produit%'";
		if($nsi_propriete) $req.=" AND nsi_propriete = '$nsi_propriete'";
		if($site_propriete) {
			$site_propriete = strtolower($site_propriete);
			$req.=" AND LOWER(site_propriete) = '$site_propriete'";
		}

		$resultat = new db_sql($req); //echo $req;
		$valnsi="";
		while($resultat->n() ){

			//echo $resultat->f('nsi_valeur');

			$tab_produits = explode(";",$resultat->f('site_produit'));
			if($site_produit){

				//echo "1";

				for ($i = 0; $i < count($tab_produits); $i++) {
					if($tab_produits[$i]==$site_produit){
						$tab_valeurs = explode(";",$resultat->f('site_valeur'));
						for ($j = 0; $j < count($tab_valeurs); $j++) {
							//echo strtolower(preg_replace('/\s+/', '',$tab_valeurs[$j]))."==".strtolower(preg_replace('/\s+/', '',$valeursite));
							if(strtolower(preg_replace('/\s+/', '',$tab_valeurs[$j]))==strtolower(preg_replace('/\s+/', '',$valeursite))){
								$valnsi = $resultat->f('nsi_valeur');
								if($type=="toile" && $valnsi=="|") return $valnsi;
							}
						}
					}
				}
			}
			else {

				//echo "2";
				$tab_valeurs = explode(";",$resultat->f('site_valeur'));
				for ($j = 0; $j < count($tab_valeurs); $j++) {
					//echo preg_replace('/\s+/', '',$tab_valeurs[$j])."==".preg_replace('/\s+/', '',$valeursite);
					if(strtolower(preg_replace('/\s+/', '',$tab_valeurs[$j]))==strtolower(preg_replace('/\s+/', '',$valeursite))){
						$valnsi = $resultat->f('nsi_valeur');
						if($type=="toile" && $valnsi=="|") return $valnsi;
					}
				}
			}
		}

		//$valnsi = $resultat->f('nsi_valeur');

	}

	//echo $valnsi;

	if($type=="lov"){
		if($valnsi!=""){
			if (in_array($valnsi,$listevaleur)) $ret = $valnsi;
		}
	}

	else if($type=="toile"){
		if($valnsi!=""){
			if($valnsi=="|") $ret =$valnsi;
			else {
				$req = new db_sql("select * from toile where lb_toile_sr='$valnsi'");
				while ($req->n()) {
					$ret = $req->f('id_toile');
				}
			}
		}
	}

	return $ret ;
}

/*----------------------------------------------------------
 Retourne l'équivalent d'une propriété de NSI dans le site
------------------------------------------------------------*/

function getEquivalentPropriete ($liste_propriete_site,$id_champ,$nsi_produit) {

	//rechercher parmi toutes les propriétés du site

	$ret ="" ;
	if (array_key_tolower_exists($id_champ,$liste_propriete_site)) $ret = $id_champ;
	//recherche dans table de correspondance
	else{

		$req="
			SELECT
				*
			FROM
				correspondance
			WHERE
				LOWER(nsi_propriete) = '$id_champ'";

		if($nsi_produit)$req.=" AND nsi_produit='$nsi_produit'";
		$resultat = new db_sql($req);//echo $req;
		$valsite="";

		while($resultat->n() ){
			$tab_valeurs = explode(";",$resultat->f('site_propriete'));
			for ($i = 0; $i < count($tab_valeurs); $i++) {
				$valsite = array_key_tolower_exists($tab_valeurs[$i],$liste_propriete_site);
				if($valsite!="") return $valsite;
			}
			//$valsite = $resultat->f('site_propriete');
		}

		/*if($valsite!=""){
			return array_key_tolower_exists($valsite,$liste_propriete_site);
		}*/

	}
	return $ret ;
}



/*----------------------------------------------------
 Retourne l'équivalent d'un produit du site dans NSI
------------------------------------------------------*/

function getEquivalentProduit ($listeproduits,$produits) {

	//rechercher parmi tous les produits de NSI

	$ret ="" ;

	if(in_array($produits, $listeproduits)) $ret = $produits;

	//recherche dans table de correspondance

	else{

		$req="
		 SELECT
		  *
		 FROM
		  correspondance
		 WHERE
		  site_produit LIKE '%$produits%'";

		$resultat = new db_sql($req);
		$valnsi="";

		while($resultat->n() ){
			$tab_valeurs = explode(";",$resultat->f('site_produit'));
			for ($i = 0; $i < count($tab_valeurs); $i++) {
				if($tab_valeurs[$i]==$produits){
					if (in_array($resultat->f('nsi_produit'),$listeproduits)){
						return $resultat->f('nsi_produit');
					}
				}
			}

		}
	}

	return $ret ;

}

/*-------------------------------------------------
 Retourne la liste des produits ou propriétés
-------------------------------------------------*/

function getListe ($type) {

	$results = array();
	$handler = opendir($type);
	while ($file = readdir($handler)) {
		if ($file != "." && $file != "..") {
			$results[]= str_replace(".php","",$file);
		}
	}

	closedir($handler);
	return $results;
}

/*-------------------------------------------------
 Formater une date
-------------------------------------------------*/

function formatterDate ($date) {
	return substr($date,6,4).'-'. substr($date,3,2).'-'. substr($date,0,2);
}

function ajoutejourscalendaires ($date,$jours) {
	return date('Y-m-d',mktime(0,0,0,substr($date,5,2),substr($date,8,2)+$jours,substr($date,0,4)));
}

function calculedatelivraison ($dataset, $of) {

	charge_doc($of);
	$jours = $GLOBALS[parms][type_docs][$of][delai];
	$date_decale = $dataset->ochamp[date_cde]->get();
	$date_decale = ajoutejourscalendaires($date_decale,-1);
	$decale=-1;
	$GLOBALS[jours_feries]["2013-06-18"] = 1;

	while ($decale < $jours) {
		$date_decale = ajoutejourscalendaires($date_decale,1);
		if (	(date('w',strtotime($date_decale))!=0) and
			(date('w',strtotime($date_decale))!=6) and
			(!$GLOBALS[jours_feries][$date_decale])
		) {
			$decale++;
		}
	}

	return ($date_decale);
}

/*------------------------------------------------
 Affiche le coloris pour tringle
-------------------------------------------------*/

function getColorisTringle($champ) {

	$valeur="";
	$id_article =$champ->get();
	$req = new db_sql("select * from article where id_article = $id_article");

	while ($req->n()) {
		$valeur = $req->f($champ->champscible);
	}

	return $valeur;
}

/*-------------------------------------------------
 Filtre les valeurs d'un champ
-------------------------------------------------*/

function onchange($parametres) {

	$valeur = $parametres['valeur'];
	$result = $parametres['result'];
	$fieldtocontrol = $parametres['fieldtocontrol'];
	$list_id_tocontrol = $parametres['list_id_tocontrol'];

	foreach ($fieldtocontrol as $key => $value) {
		$champ = new champ($value);
		if($value=='colorispieces') {
		if($valeur == '' || $valeur==utf8_encode('Chaînette')) {
			$str = 'var valeurs = [{id:"",valeur:"[Choisir]"}, ';
			foreach ($champ->valeurs as $val => $valeur_affiche ) {
				$str.='{id:"'.utf8_encode($val).'", valeur:"';
				$str.= utf8_encode($valeur_affiche).'"},';
			}
			if (substr($str, strlen($str)-1, strlen($str)) == ',') $str = substr($str, 0, -1);
				$str .= ']';
		} else {
			$str = 'var valeurs = [{id:"-",valeur:"-"}]';
		}

		$id = get_field_id($value,$list_id_tocontrol);
		if($result =="")
			$result.=$id."[SEPVAL]".$str;
		else
			$result.="[SEPDIV]".$id."[SEPVAL]".$str;
		}
	}

	return $result;
}

/*-------------------------------------------------
 Retourne un div personnalisé pour une liste de checkbox
-------------------------------------------------*/

function get_custom_div($valeur,$listevaleurs,$style) {

	if(!$valeur || $valeur=="") {
		$width =32 * count($listevaleurs);
		return substr($style,0,strlen($style)-1)."; width:".$width."'";
	}

	return "";
}

/*-------------------------------------------------

 Determine si l'utilisateur peut modifier la commande en fonction de son statut
 Renvoie

 0 : On ne peut pas modifier, on a juste accès au statut comme futur statut
 1 : On peut modifier le statut
 2 : On peut modifier l'intégralité de la feuille de côte

-------------------------------------------------*/

function droit_feuille_de_cote($libelle_statut) {
	return ($_SESSION[id_droit][statuts_fdc][$libelle_statut]);
}

function filtrer_statut_fdc($valeurs) {
	if(!is_array($_SESSION[id_droit][statuts_fdc])) return;
	foreach($valeurs as $key =>$valeur) {
		if(key_exists($key,$_SESSION[id_droit][statuts_fdc]))
			$result[$key] = $valeur;
	}
	return $result;
}





/*-------------------------------------------------
 Controle le champ statut lors du chargement
 La statut permet-il de modifier la commande (en fonction du droit) ?
-------------------------------------------------*/

function set_dataset_modifiable_fct_statut($valeur) {
	return (droit_feuille_de_cote($valeur)==2) ? "" : '$this->nonmodifiable = 1;' ;
}

/*

-------------------------------------------------
 Retourne la propriété d'un article
-------------------------------------------------*/

function get_article_propriete($id_article, $propriete) {

	$requete="
	  SELECT
		*
	  FROM
		article_propriete
	  WHERE
		id_article = $id_article AND propriete='$propriete'";

	$resultat = charge_un($requete);
	return $resultat[valeur];
}

/*

-------------------------------------------------
 Retourne le nombre de fournisseurs par article
-------------------------------------------------*/

function get_nb_fournisseur_by_article($id_article) {
	return req_sim("SELECT count(1) as principal FROM fournisseur,fournisseur_article WHERE fournisseur.Id_fournisseur=fournisseur_article.Id_fournisseur AND id_article=" . $id_article,"principal");
}

/*

-------------------------------------------------
 Retourne le statut d'un po
-------------------------------------------------*/

function get_Id_statut_po($envoi_automatique) {
	if($envoi_automatique==1)
		return 2;
	else
		return 1;
}

/*-------------------------------------------------
 Affiche le coloris pour monocolore
-------------------------------------------------*/

function getColorisMonocolore($champ) {
	return getColoris($champ);
}

function getColoris($champ) {
	
	//list($gamme,$reference_gamme_couleur) = explode("|",$champ->get());

	$valeur="";

	//$req = new db_sql("select * from toile where gamme = '$gamme' AND concat(reference_gamme, '-', reference_couleur ) = '$reference_gamme_couleur'");

	if($champ->get() && is_numeric($champ->get())){
		$req = new db_sql("select * from toile where id_toile = ".$champ->get());
		while ($req->n()) {
			$valeur = $req->f($champ->champscible);
		}
	}

	return $valeur;
}

function getValeurLov($champ) {

	//list($gamme,$reference_gamme_couleur) = explode("|",$champ->get());
	$valeur="";
	//$req = new db_sql("select * from toile where gamme = '$gamme' AND concat(reference_gamme, '-', reference_couleur ) = '$reference_gamme_couleur'");

	if($champ->get() && is_numeric($champ->get()) && $champ->tablecible){
		$req = new db_sql("select ".$champ->champscible." from (".$champ->tablecible.") as a where ".$champ->champstocke." = ".$champ->get());
		while ($req->n()) {
			$valeur = $req->f($champ->champscible);
		}
	} else {
		$valeur = $champ->get();
	}

	return $valeur;
}

/*-------------------------------------------------
 Vérifie s'il y a des 0 non significatifs
-------------------------------------------------*/

function existeZeroNonsignif($valeur) {

	if(strpos($valeur,'.') == false) {
		return false;
	}
	else {
		$pos = strpos($valeur,'.');
		$valeur = substr($valeur,$pos+1,strlen($valeur)-$pos-1);
		while(strlen($valeur)>0){
			if(StartsWith($valeur,"0"))
				$valeur = substr($valeur,1,strlen($valeur)-1);
			else
				return false;
		}
		return true;
	}
	
}

function formate_troiszeroapresvirgule($data) {

	if (is_object($data)) {
		$chiffre_a_convertir = $data->get();
	} else {
		$chiffre_a_convertir = $data;
	}

	$chiffre_virgule = str_replace(",",".",$chiffre_a_convertir);

	if (is_numeric($chiffre_virgule) and $chiffre_virgule) {
		return number_format($chiffre_virgule*1,3,',',' ');
	} else {
		return($chiffre_a_convertir);
	}

}

function formate_deuxzeroapresvirgule($data) {
	$chiffre_a_convertir = str_replace(",",".",$data->get());
	if (is_numeric($chiffre_a_convertir) and $chiffre_a_convertir) return number_format($chiffre_a_convertir*1,2,',',' ');
	return("");
}

function formate_unzeroapresvirgule($data) {
	$chiffre_a_convertir = str_replace(",",".",$data->get());
	if (is_numeric($chiffre_a_convertir) and $chiffre_a_convertir) return number_format($chiffre_a_convertir*1,1,',',' ');
	return("");
}

function dateenfrancais($data) {

	$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre");
	$date = $data->get();
	if (!$date) return("");

	return (substr($date,8,2).
		' '.
		$mois[substr($date,5,2)*1].
		' '.
		substr($date,0,4));

}

/*-------------------------------------------------
 Affiche le coloris pour bicolore
-------------------------------------------------*/

function getColorisBicolore($champ) {

	$valeur = $champ->get();
	if(strpos($valeur,';') !== false){
		$tab_valeur = explode(";",$champ->get());
		$valeur="Tête";
		for ($i = 0; $i < count($tab_valeur); $i++) {
			//list($gamme,$reference_gamme_couleur) = explode("|",$tab_valeur[$i]);
			//$req = new db_sql("select * from toile where gamme = '$gamme' AND concat(reference_gamme, '-', reference_couleur ) = '$reference_gamme_couleur'");
			$req = new db_sql("select * from toile where id_toile = $tab_valeur[$i]");
			while ($req->n()) {
				if($i==0)
					$valeur = $valeur . " ".$req->f('couleur');
				else
					$valeur = $valeur . " - ".$req->f('couleur');
			}
		}
		return $valeur;
	}
	else return getColorisMonocolore($champ);
}

/*-------------------------------------------------
 Vérifie l'affichage
-------------------------------------------------*/

function onload($dataset,$valeurs) {

	$statut = $valeurs['statut'];
	$champ_statut = charge_champ('statut');
	$ordre = $champ_statut[ordre][$statut];

	//$ordre = $GLOBALS[parms][champs]['statut'][ordre][$statut];

	//On n'affiche ces champs que lorsque le statut est supérieur à Validé

	if($ordre<3) {
		$dataset->ochamp['ncommande']->non_html = 1;
		$dataset->ochamp['datepose']->non_html = 1;
	}

}

/*-------------------------------------------------
 Retourne le statut suivant
-------------------------------------------------*/

function get_next_statut($statut) {

	$champ = charge_champ('statut');
	$champ_statut = $champs['statut'];
	$ordre = $champ_statut[ordre][$statut];

	foreach ($champ_statut[ordre] as $key => $valeur) {
		if($valeur>$ordre){
			$next_statut =$key;
			break;
		}
	}

	return $next_statut;

}



/*
  Vérifie si un champ toile est vide
*/

function isEmptyToile($valeur) {
	list($cle,$valeur) = explode("|",$valeur);
	return $cle=="" || $valeur=="";
}

function check_sav($dataset){
    if ($num_bcc = $dataset->ochamp[numcommande_fdc] and count(explode("_",$num_bcc->get())) >= 2){
       $dataset->ochamp[prix_ht]->set_obligatoire(false) ;
    }
}

function checkvalidate_fdcentete($parametres){
    $dataset_courant = $parametres['dataset'];
    if( $dataset_courant->ochamp[prestation]->get() == "Fourniture & pose" ){
        if($num_bcc = $dataset_courant->ochamp[numcommande_fdc] and count(explode("_",$num_bcc->get())) <=1 ) {
            if ($dataset_courant->ochamp[pose_ht]->get() <= 0 or $dataset_courant->ochamp[pose_ht]->get() == " ") {
                return "Le champ Dont Pose est obligatoire";
            }
        }
    }
}

function checkvalidate($parametres) {

	$dataset_courant = $parametres['dataset'];

	/* Vérification des incompatibilités */
	if(StartsWith($dataset_courant->type_dataset,"fdcproduit")) {
		if ($tx =  check_incompatibilité($parametres['dataset']) )
			return $tx;

		if ($tx =  check_incompatibilite_dimension($parametres['dataset']) )
			return $tx;
	}

	return false;

}

/*------------------------------------------------------
 Valide une fiche toile
--------------------------------------------------------*/

function validateToile($record){
	if($record[typerecord]=="entete"){
		if(!$_POST[$record[lb_toile_atelier]])
			return "Le champ Libellé doit être renseigné";
	}
}

/*------------------------------------------------------
 Valide une entête ou une fiche article
--------------------------------------------------------*/

function validateArticle($record){

	if($record[typerecord]=="entete"){
		if(!$_POST[$record[lb_article]])
			return "Le champ Libellé doit être renseigné";
		else if(!$_POST[$record[type]])
			return "Le champ Type doit être renseigné";
		$_POST[$record[qt_stock]] = $_POST[$record[qt_stock]]*1;
		$_POST[$record[qt_mini]] = $_POST[$record[qt_mini]]*1;
		$_POST[$record[qt_max]] = $_POST[$record[qt_max]]*1;
	}

	else if($record[typerecord]=="ligneprincipal" || $record[typerecord]=="lignesecondaire"){
		if(!$_POST[$record[id_fournisseur]])
			return "Le champ Fournisseur doit être renseigné";
		else if(!$_POST[$record[reference]])
			return "Le champ Référence doit être renseigné";
		else if(!$_POST[$record[quotite]])
			return "Le champ Quotité doit être renseigné";
		/*else if(!$_POST[$record[prix]])

          return "Le champ Prix doit être renseigné"; */
	}

	else if($record[typerecord]=="lignepropriete"){
		if(!$_POST[$record[propriete]])
			return "Le champ Propriété doit être renseigné";
		else if(!$_POST[$record[valeur]])
			return "Le champ Valeur doit être renseigné";
	}

}

/*------------------------------------------------------
 Valide une entête ou une ligne de commande
--------------------------------------------------------*/

function validateCommande($record){
	if($record[type]=="entete"){
		if(!$_POST[$record[date_po]])
			return "Le champ Date doit être renseigné";
		else if(!$_POST[$record[id_fournisseur]])
			return "Le champ Fournisseur doit être renseigné";
		else if(!$_POST[$record[id_statut_po]])
			return "Le champ Statut doit être renseigné";
	}
	else{
		if(!$_POST[$record[id_article]])
			return "Le champ Article doit être renseigné";
		else if(!$_POST[$record[qt]])
			return "Le champ Qté doit être renseigné";
		else if(!$_POST[$record[pu]])
			return "Le champ Prix doit être renseigné";
	}
}

/*-------------------------------------------------
 Contrôle un champ de type toile lors de l'enregistrement
-------------------------------------------------*/

function controler_champ_toile($champ) {
	list($gamme,$couleur) = explode("|",$champ->get());
	if($gamme!="" && $couleur=="")
		return "la couleur doit être renseignée pour ".$champ->lb_champ;
	return false;
}

function controler_champ_hauteurpose($champ) {
	list($gamme,$couleur) = explode("|",$champ->get());
}

function chk_date_vdl($id_doc) {
    if(!$id_doc)
        return false;
    $statut = getPostFieldEnteteValue('statut');
    $champ_statut = charge_champ('statut');
    $champ_date = getPostFieldEnteteValue('date_vld_fdc');
    $ordre = $champ_statut[ordre][$statut];
    if ($ordre>=2 and !$champ_date){
        $GLOBALS[parms][task][]='maj_date_vld';
    }
}

function maj_date_vld($id_doc, $dbres) {
	$requete = "
		UPDATE
			zdataset_fdcentete,
			doc
		SET
			zdataset_fdcentete.date_vld_fdc = '".aujourdhui()."'
		WHERE
			zdataset_fdcentete.id_dataset = doc.id_dataset_entete and
			doc.type_doc = 'fdc' and
			doc.id_doc = ".$id_doc;

	$dbres->q($requete);
	$GLOBALS[dataset]->load_from_database();
}

function maj_num_fdc_import($id_doc, &$dataset, &$dbres) {
	$doc = charge_un("select * from doc where id_doc = $id_doc", $dbres);
	$type_doc = $doc[type_doc];

	if($type_doc == "fdc"){
		$id_dataset_entete = $dataset->id_dataset;
		$dataset->ochamp['num_fdc']->set($id_doc);
		$dbres->q("
			update	
				zdataset_fdcentete
			set
				num_fdc = $id_doc
			where 
				id_dataset = $id_dataset_entete
		");
	}
}

function isOfAgerer($id_doc) {

	if(!$id_doc)
		return false;

	$statut_avant = getstatutbyiddoc($id_doc);
	$statut = getPostFieldEnteteValue('statut');

	// if ($statut == "Validée") {
		// $affaire = getPostFieldEnteteValue('affaire');
		// if(req_sim(" select id_statut from affaire where id_affaire = $affaire ", "id_statut")==6) {
			// $statut = "A produire";
			// setPostFieldEnteteValue('statut',$statut);
		// }
	// }

	$champ_statut = charge_champ('statut');
	$ordre = $champ_statut[ordre][$statut];
	$ordre_avant = $champ_statut[ordre][$statut_avant];
	if ($ordre && $ordre_avant && $ordre!=$ordre_avant && (($ordre<5 && $ordre_avant>=5) || ($ordre>=5 && $ordre_avant<5))){
		if($ordre>=5 && $ordre_avant<5){
			if(is_document_lie_cree($id_doc)) {
				return false;
			} else {
				return true;
			}
		} else if(is_document_lie_cree($id_doc) && $ordre<5 && $ordre_avant>=5) {
			$GLOBALS[parms][task][]='delete_ofs_de_fcd';
			return true;
		}
	} else {
		return false;
	}
}

function charge_liste_dataset() {
	$liste_dataset = $_POST['liste_dataset'];
	if ($liste_dataset[0]) {
		$liste_dataset[""] = $liste_dataset[0];
		unset ($liste_dataset[0]);
	}
	return $liste_dataset;
}

function getPostFieldEnteteValue($field) {
	$liste_dataset = charge_liste_dataset();
	if(is_array($liste_dataset)) {
        foreach ($liste_dataset as $key => $type) {
            if($type=="fdcentete"){
                $dataset = new dataset($type);
                $dataset->id_dataset = $key;
                $dataset->load_from_screen();
                return $dataset->ochamp[$field]->get();
            }
        }
    }
}

function setPostFieldEnteteValue($field, $value) {
	$liste_dataset = charge_liste_dataset();
	if(is_array($liste_dataset)) {
		foreach ($liste_dataset as $key => $type) {
			if($type=="fdcentete"){
				$_POST[$key.'_champ_'.$field] = $value;
			}
		}
	}
}

function controle_exception($dtset, $produit){

	$tabexception = array();
	$reslt_incom = new db_sql("select * from incompatibilites_liste where produit = '$produit' and val2 = ','");

	while($reslt_incom->n()){
		foreach($dtset->ochamp as $champ => $tab){
			if ( 	($reslt_incom->f('prop1') == $champ) and 
					($reslt_incom->f('val1') == $dtset->ochamp[$champ]->get()) ) {
				$tabexception[] = $reslt_incom->f('prop2');
			}
		}
	}

	$reslt_incom = new db_sql("select * from incompatibilites where produit = '$produit' and val2 = '*'");
	while($reslt_incom->n()){
		foreach($dtset->ochamp as $champ => $tab){
			if ( 	($reslt_incom->f('prop1') == $champ) and 
			($reslt_incom->f('val1') == $dtset->ochamp[$champ]->get()) ) {
				$tabexception[] = $reslt_incom->f('prop2');
			}
		}
	}

	// Pour les entetes de produit : Les champs ouverts ne doivent pas devenir obligatoires

	$tabexception[] = "quantite";
	$tabexception[] = "quantite_e";
	$tabexception[] = "commentaires_fabrication";
	$tabexception[] = "commentaires_pose";

	$tabexception[] = "qte_telecmd_rad_1canal";
	$tabexception[] = "qte_telecmd_radio_4canaux";
	$tabexception[] = "qte_inter_radio_1canal";
	$tabexception[] = "qte_inter_fil_unit";

	return $tabexception;
}

/*-------------------------------------------------
 Contrôle le champ statut lors de l'enregistrement
-------------------------------------------------*/

function controler_statut($parametres) {
	$statut = $parametres['statut']->get();
	$id_doc = $_POST["id_doc"];
	$dataset = $parametres['dataset'];

	$champ_statut = charge_champ('statut');

	if($id_doc) {
		$statut_avant = getstatutbyiddoc($id_doc);
	}	

	$ordre = $champ_statut[ordre][$statut];

	$ordre_avant = 1;
	if ($statut_avant) {
		$ordre_avant = $champ_statut[ordre][$statut_avant];
	} else {
		$ordre_avant = 1;
	}	
	//if(!droit_feuille_de_cote($ordre,false) && $statut_avant==$statut) return "Cancel" /*"Vous n'avez pas le droit sur ce statut"*/;
	//if($ordre_avant>$ordre && $id_doc) return "On ne peut pas faire de retour arrière pour le champ statut";
	//S'il y a changement de statut

	if ($statut_avant != $statut and $id_doc and $GLOBALS[origine]==3) {
		$liste_dt = charge_liste_dataset();
		//Contrôle des champs obligatoires pour tous les datasets de lignes de produit
		$requete = "
			SELECT
				doc_ligne.id_dataset,
				doc_ligne.produit,
				'type_dataset_ligne' as type
			FROM
				doc_ligne
			WHERE
				doc_ligne.id_doc	= $id_doc and
				doc_ligne.produit != '' and
				doc_ligne.produit is not null
			union
			SELECT
				doc_groupe_ligne.id_dataset,
				doc_groupe_ligne.produit,
				'type_dataset_groupe_ligne' as type
			FROM
				doc_groupe_ligne
			WHERE
				doc_groupe_ligne.id_doc = $id_doc and
				doc_groupe_ligne.produit != '' and
				doc_groupe_ligne.produit is not null";
				
		$resultat = new db_sql($requete);
		$liste_dataset = [];
		while($resultat->n()) {
			$liste_dataset[] = array(
				'id_dataset'=>$resultat->f('id_dataset'),
				'produit'=>$resultat->f('produit'),
				'type'=>$resultat->f('type')
			);
		}

		foreach ($liste_dataset as $dts) {
			$id_dataset = $dts['id_dataset'];
			$produit = $dts['produit'];
			$type = $dts['type'];

			require("param_2_produits/".$produit.".php");
			//$type_dataset = $GLOBALS[parms][produits][$resultat->f('produit')][type_dataset_ligne];
			$type_dataset = $produits[$produit][$type];
			charge_dataset($type_dataset);
			
			// Genere_erreur permet de savoir si on veut éviter de passer par les vérifications de champs obligatoires
 			// Si 1 = alors on vérifie les champs obligatoires, si 0 on ne vérifie pas 
		
 			$shunte_obligatoire	= 0;
 			// Si fourniture seule, je ne vérifie pas pour les dataset d'entete de produit
			if($GLOBALS[type_prest] and substr($type_dataset, 0, 13) == 'produitentete') {
 				// Si fourniture seule, je nérifie pas 
 				$shunte_obligatoire = 1;
 			}
 
			$dataset = new dataset($type_dataset);

			if ($liste_dt[$id_dataset]) {
				$dataset->id_dataset = $id_dataset;
				$dataset->load_from_screen($id_dataset);
			} else {
				$dataset->load_from_database($id_dataset);
			}

			$tabexception_bis = controle_exception($dataset,$produit);
			foreach ($dataset->ochamp as $champ) {
				$valeur = $champ->get();
				if (!$valeur or substr($valeur,-1,1)=='|') {

					//parce que la consigne n'est mise qu'une fois pour le 1er statut
					for($i=1;$i<=$ordre;$i++){

						//$tab = $champ_statut[fieldrequired][$type_dataset][$ordre];
						//$tabexception =$champ_statut[exceptionfield][$type_dataset][$ordre];

						$tab = $champ_statut[fieldrequired][$type_dataset][$i];
						$tabexception = $champ_statut[exceptionfield][$type_dataset][$i];
						if(!is_array($tabexception)) $tabexception = array();
						if(is_array($tab)) {
							foreach ($tab as $cle => $idchamp){
								if (
										( $idchamp == $champ->id_champ or $idchamp == "ALL")
										and
										!(in_array($champ->id_champ, $tabexception_bis) or in_array($champ->id_champ, $tabexception))
										and 
										!$shunte_obligatoire
									) {
									return "le champ ".$champ->lb_champ." doit etre renseigné pour ".$produit." ";
								}
							}
						}
					}
				}
			}
		}
	}

	//Si le statut d'une commande est A traiter et l'affaire est signée, le statut passe automatiquement A produire
	//if($statut=="Validé"){
	//A Decommenter en même temps que PAGES

	if($statut=="Validée"){
		$Id_affaire = $parametres['affaire']->get();
		$requete = "select * from affaire where Id_affaire = $Id_affaire";
		$resultat = new db_sql($requete);
		while($resultat->n()){
			if($resultat->f('Id_statut')==6) {
				//$statut = "A traiter";
				$statut = "A produire";
				$parametres['statut']->set($statut);
				$ordre = 5;
			}
		}
	}
	if (($parametres['statut']->get() == "A produire") and !$parametres['date_cde']->get()) {
		$parametres['date_cde']->set(date("Y-m-d"));
	}
	//Si la commande passe au statut « A produire »
	if ($ordre >= 5 and $ordre_avant < 5 and $id_doc) {
		$GLOBALS[parms][task][]='generer_of';
	} else if ($ordre < 5 and $ordre_avant >= 5 and $id_doc) {
		$GLOBALS[parms][task][]='delete_ofs_de_fcd';
	}

	//Interdit la modification de la commande si le nouveau statut ne l autorise pas
	if( droit_feuille_de_cote($statut)!=2 ) {
		$parametres['dataset']->set_modifiable(1);
	}

	//$_SESSION["champ_statut_avant"]=$statut;
	return false;
}

/*

-------------------------------------------------
 Récupérer le stock actuel d'un article
-------------------------------------------------*/

function get_stock_article($id_article) {
	if (!is_array($GLOBALS[STOCK])) {
		$req = new db_sql("
			SELECT
				article.id_article,
				article.qt_stock,
				po_ligne.qt_com,
				po_ligne.qt_solde,
				besoin.qt_bes,
				article.qt_stock_mois
			FROM
				article
				LEFT OUTER JOIN (SELECT sum(qt) as qt_bes, besoin.id_article FROM besoin group by besoin.id_article) besoin on (besoin.id_article = article.id_article)
				LEFT OUTER JOIN (SELECT sum(qt) as qt_com, sum(qt_solde) as qt_solde, po_ligne.id_article FROM po_ligne group by po_ligne.id_article) po_ligne on (po_ligne.id_article = article.id_article)");
		while ($curs = $req->n()) {
			$GLOBALS[STOCK][$req->f('id_article')] = $req->Record;
		}
	}	


	$resultat = $GLOBALS[STOCK][$id_article];
	$res[physique] = $resultat[qt_stock] - $resultat[qt_bes] + ($resultat[qt_com] - $resultat[qt_solde]) ;
	$res[achete] = $resultat[qt_solde];
	$res[vendu] = 0;
	$res[logique] = $res[physique] + $res[achete] - $res[vendu];
	$res[logique_mois] = 0;
	// Quantite de stock disponible en comptant la quantite utilisée ces 3 derniers mois
	if($resultat[qt_stock_mois] > 0 && $res[logique] > 0) {
		$res[logique_mois] = ($res[logique] * 3) / $resultat[qt_stock_mois];
	}
	return $res;
}

include_once ('c_algo_5.php');

/*
-------------------------------------------------
 Retourne le nom du champ largeur d'un produit
-------------------------------------------------*/

function get_largeur_fieldname($produit) {
    if($produit=="Store Banne Coffre COMPACTO" || $produit=="Store Banne Coffre ELISSE" ||
        $produit=="Store Banne Monobloc HAWAI" || $produit=="Store Plisse Horizontal" ||
        $produit=="Store Plisse Vertical") return "largeur";
    else if ($produit=="Store Banne") return "largeur_storebanne";
    else if ($produit=="Store Bateau") return "largeur_storebateau";
    else if ($produit=="Store Lames Verticales") return "largeur_slv";
    else if ($produit=="Rideau bicolore") return "larg_rideau_mono";
    else if ($produit=="Rideau monocolore") return "larg_rideau_mono";
}



/*
-------------------------------------------------
 Retourne le nom du champ hauteur d'un produit
-------------------------------------------------*/

function get_hauteur_fieldname($produit) {
    if($produit=="Store Banne Coffre COMPACTO") return "avancee_comptacto";
    else if($produit=="Store Banne Coffre ELISSE" || $produit=="Store Plisse Horizontal" ||
        $produit=="Store Banne Monobloc HAWAI") return "avancee";
    else if($produit=="Store Banne") return "avancee_storebanne";
    else if($produit=="Store Bateau") return "hauteur_storebateau";
    else if($produit=="Store Lames Verticales") return "hauteur_slv";
    else if($produit=="Store Plisse Vertical") return "hauteur";
    else if($produit=="Rideau bicolore") return "long_rideau_mono";
    else if($produit=="Rideau monocolore") return "long_rideau_mono";
}



/*
-------------------------------------------------
 Retourne le nom du champ coté de commande d'un produit
-------------------------------------------------*/

function get_cote_de_cmd_fieldname($produit) {
    if($produit=="Store Banne Coffre COMPACTO") return "cotedecommande_sbcc";
    else if($produit=="Store Banne Coffre ELISSE") return "cotedecommande_sbcl";
    else if($produit=="Store Banne Monobloc HAWAI") return "cote_de_commande_sbmo";
    else if($produit=="Store Banne") return "cote_commande_banne";
    else if($produit=="Store Bateau") return "cotecommande_storebateau";
    else if($produit=="Store Lames Verticales") return "cotecommande_slv";
    else if($produit=="Store Plisse Vertical") return "cote_commande";
}



/*

-------------------------------------------------
 Retourne le nom du champ type commande d'un produit
-------------------------------------------------*/

function get_type_cmde_fieldname($produit) {
    if($produit=="Store Banne Coffre COMPACTO") return "typedecommande_sbcc";
    else if($produit=="Store Banne Coffre ELISSE") return "typedecommande_sbcl";
    else if($produit=="Store Banne Monobloc HAWAI") return "typedecommande_sbmo";
    else if($produit=="Store Banne") return "type_commande_banne";
    else if($produit=="Store Bateau") return "typecommande_storebateau";
    else if($produit=="Store Lames Verticales") return "typecommande_slv";
    else if($produit=="Store Plisse Horizontal") return "type_commande_st_pl_h";
    else if($produit=="Store Plisse Vertical") return "type_commande_st_pl_v";
}



/*
-------------------------------------------------
 Retourne le nom du champ type de pose  d'un produit
-------------------------------------------------*/

function get_type_pose_fieldname($produit) {
    if($produit=="Store Banne Coffre COMPACTO") return "typedepose_sbcc";
    else if($produit=="Store Banne Coffre ELISSE") return "typedepose_sbcl";
    else if($produit=="Store Banne Monobloc HAWAI") return "typedepose_sbmo";
    else if($produit=="Store Banne") return "typepose_banne";
    else if($produit=="Store Bateau") return "typepose_storebateau";
    else if($produit=="Store Lames Verticales") return "typepose_slv";
    else if($produit=="Store Plisse Horizontal") return "type_pose_st_pl_h";
    else if($produit=="Store Plisse Vertical") return "type_pose_st_pl_v";
}



/*

-------------------------------------------------
 Retourne le nom du champ guidage d'un produit
-------------------------------------------------*/

function get_guidage_fieldname($produit) {
    if($produit=="Store Plisse Horizontal") return "guidage_st_pl";
    else if($produit=="Store Plisse Vertical") return "guidage_st_pl";
}


/*

-------------------------------------------------
 Supprimer les besoins matière d'1 fdc
-------------------------------------------------*/

function delete_ofs_de_fcd($id_doc,$dbres,$tab_prod_dup=array()) {
    $liste_ofs = charge("
	SELECT
		doc.id_doc,
		doc.id_dataset_entete,
		zdataset_ofentete.produit_entete_of as produit,
		doc.type_doc
	FROM
		doc
	INNER JOIN zdataset_ofentete on (doc.id_dataset_entete = zdataset_ofentete.id_dataset and zdataset_ofentete.fdc = $id_doc)
	WHERE
		type_doc like 'OF%' 
	UNION 
		
	SELECT
		doc.id_doc,
		doc.id_dataset_entete,
		zdataset_commandeentete.produit_entete_commande as produit,
		doc.type_doc
	FROM
		doc
	INNER JOIN zdataset_commandeentete on (doc.id_dataset_entete = zdataset_commandeentete.id_dataset and zdataset_commandeentete.fdc = $id_doc)
	WHERE
		type_doc like 'COMMANDE%' 
	UNION 
		
	SELECT
		doc.id_doc,
		doc.id_dataset_entete,
		'' as produit,
		doc.type_doc
	FROM
		doc
	INNER JOIN zdataset_bonentete on (doc.id_dataset_entete = zdataset_bonentete.id_dataset and zdataset_bonentete.fdc = $id_doc)
	WHERE
		type_doc like 'BON%'");
    foreach ($liste_ofs as $i => $of) {
        if ((!count($tab_prod_dup)) or $tab_prod_dup[$of[produit]]) {
			if(substr($of['type_doc'], 0, 8) == 'COMMANDE') {
				$entete_commande = new dataset('commandeentete');
				$entete_commande->load_from_database($of['id_dataset_entete']);
				$entete_commande->ochamp['statut_commande']->set(6);
				$entete_commande->store();
			} else {
				delete_doc($of[id_doc],$dbres);
			} 
        }
    }
}

/*-------------------------------------------------
 Générer les besoins matière d'1 fdc // Pour les toiles (2D)
-------------------------------------------------*/

function generer_besoins_matiere($id_doc,$liste_ofs,$dbres) {

	//****************************************
	// Calcul du champ : liste des produits
	//****************************************

	$req = "
		SELECT
			*
		FROM
			doc_ligne
		WHERE
			id_doc=$id_doc
		ORDER BY
			produit";

	$resultat = new db_sql($req);
	$tablisteproduits = array();
	$listeproduits="LISTE COMPLETE DES PRODUITS DE LA COMMANDE<BR>";


	while($resultat->n()){
		$champ_qte = "qte";
		$id_dataset = $resultat->f('id_dataset');
		$type_dataset = getTypeDataset($id_dataset);
		$Total = req_sim("SELECT SUM($champ_qte) AS Total from `zdataset_".$type_dataset."` where id_dataset = '$id_dataset'","Total");
		if(!array_key_exists($resultat->f('produit'), $tablisteproduits))
			$tablisteproduits[$resultat->f('produit')]= $Total;
		else
			$tablisteproduits[$resultat->f('produit')]= $tablisteproduits[$resultat->f('produit')]+$Total;
	}

	foreach ($tablisteproduits as $produit => $nb) {
		$listeproduits .= $nb." : ".$produit."<BR>";
	}

	if (count($tablisteproduits) > 1) {
		$listeproduits .= " *****************************************************<BR> * A T T E N T I O N <BR> * C O M M A N D E   M U L T I - P R O D U I T S <BR> *****************************************************<BR>";
	}

	$listeproduits = substr($listeproduits, 0, -4);

	//****************************************
	// Calcul des directives de coupe
	//****************************************

	//On recupere les id_docs des differents of

	if (is_array($liste_ofs)) {

		foreach ($liste_ofs as $id_doc_of=>$type_doc) {
			$GLOBALS[pb_bande_5] = 0;
			$tableau_solution_vide = false;
			charge_doc($type_doc);

			$directive_coupe="DIRECTIVES DE COUPE<BR>";
			$consommation="CONSOMMATION TOTALE<BR>";
			$GLOBALS[remarque_conso_raccordable]=false;
			$remarque_conso_ajustable=false;
			
			//On recupere toutes les lignes de l'of
			$liste_ligne = charge("select id_dataset, type_doc from doc_ligne,doc where doc.id_doc = doc_ligne.id_doc and doc.id_doc = $id_doc_of");
			$repere=0;

			//On stocke dans un array les dataset par gamme + couleur
			$liste_dataset_by_gamme_couleur = array();

			//Et les autres articles non 3D mais geres en stock
			$liste_autres_articles = array();
			$GLOBALS[laizebicolore] = array();

			foreach ($liste_ligne as $i => $res) {
				$repere++;
				$type_doc = $res[type_doc];
				charge_doc($type_doc);
				$dataset = new dataset($GLOBALS[parms][type_docs][$type_doc][type_dataset_ligne]);
				$dataset->load_from_database($res[id_dataset]);
				
				if ($type_doc=='OF - RIDEAU') {
					if ($dataset->get('coloris_corps_rideau_of_bi')) {
						// gestion des toiles a motif
						$dbres->q("select toile.amotif_raccordable, toile.amotif_ajustable from toile where toile.id_toile = ".$dataset->get('coloris_corps_rideau_of_bi'));
						$dbres->n();
						$remarque_conso_raccordable_lo = false;
						if ($dbres->f('amotif_raccordable')) {
							$remarque_conso_raccordable_lo = true;
						}

						$nombre = $dataset->get('qte_rideau_of_bi');
						if ($dbres->f('amotif_ajustable') and $nombre>1) {
							$remarque_conso_ajustable = true;
						}

						$tableau = array( "hauteur"=>$dataset->get('hauteur_corps_rideau_of_bi')*1000,
							"largeur"=>$dataset->get('largeur_corps_rideau_of_bi')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('qte_rideau_of_bi'),
							"remarque_conso_raccordable"=>$remarque_conso_raccordable_lo,
							"pere_bicolore"=>$res['id_dataset'],
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"   => $res['type_doc'],
								"hauteur"    => "hauteur_corps_rideau_of_bi",
								"largeur"    => "largeur_corps_rideau_of_bi",
								"qt"         => $dataset->get('qte_rideau_of_bi'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('coloris_corps_rideau_of_bi')][]=$tableau;
					}

					if ($dataset->get('coloris_tete_rideau_of_bi')) {
						$tableau = array(	"hauteur" => $dataset->get('hauteur_tete_rideau_of_bi')*1000,
							"largeur" => $dataset->get('largeur_tete_rideau_of_bi')*1000,
							"repere"  => $repere,
							"nombre"  => $dataset->get('qte_rideau_of_bi'),
							"fils_bicolore"=>$res['id_dataset'],
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"   => $res['type_doc'],
								"hauteur"    => "hauteur_tete_rideau_of_bi",
								"largeur"    => "largeur_tete_rideau_of_bi",
								"qt"         => $dataset->get('qte_rideau_of_bi'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('coloris_tete_rideau_of_bi')][]=$tableau;
					}

					if ($dataset->get('doublure_rideau_ivoire_bi') && $dataset->get('doublure_rideau_ivoire_bi')!="|") {
						$tableau = array( "hauteur"=>$dataset->get('hauteur_doublure_rideau_of_bi')*1000,
							"largeur"=>$dataset->get('largeur_doublure_rideau_of_bi')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('qte_rideau_of_bi'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_doublure_rideau_of_bi",
								"largeur"     => "largeur_doublure_rideau_of_bi",
								"qt"          => $dataset->get('qte_rideau_of_bi'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('doublure_rideau_ivoire_bi')][]=$tableau;
					}

				} else if ($type_doc == 'OF - BATEAU - CONFECTION') {
					if ($dataset->get('colorisconf_storebateauof')){
						$tableau = array( "hauteur"=>$dataset->get('hauteurcoupe_storebateau_of')*1000,
							"largeur"=>$dataset->get('largeurcoupe_storebateau_of')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('qteconf_storebateau_of'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteurcoupe_storebateau_of",
								"largeur"     => "largeurcoupe_storebateau_of",
								"qt"          => $dataset->get('qteconf_storebateau_of'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('colorisconf_storebateauof')][]=$tableau;
					}
				} else if($type_doc == 'OF - ENROULEUR - TOILES') {
					if ($dataset->get('coloris_enrouleur_of')) {
						$tableau = array( 
							"hauteur"=>$dataset->get('hauteur_coupet_enrouleur_of')*1000,
							"largeur"=>$dataset->get('largeur_coupet_enrouleur_of')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('qte_enrouleur_of'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_coupet_enrouleur_of",
								"largeur"     => "largeur_coupet_enrouleur_of",
								"qt"          => $dataset->get('qte_enrouleur_of'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('coloris_enrouleur_of')][]=$tableau;
					}

				} else if ($type_doc == 'OF - COUSSIN') {
					if ($dataset->get('coloris_coussin_of')) {
						$tableau = array(	"hauteur"=>$dataset->get('hauteur_coussin_of')*1000,
							"largeur"=>$dataset->get('largeur_coussin_of')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('qte_coussin_of'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_coussin_of",
								"largeur"     => "largeur_coussin_of",
								"qt"          => $dataset->get('qte_coussin_of'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('coloris_coussin_of')][]=$tableau;
					}
				} else if ($type_doc == 'OF - PAROI - TOILE') {
					if ($dataset->get('of_coloris')) {
						$tableau = array(	"hauteur"=>$dataset->get('of_pa_toile_hauteur')*1000,
							"largeur"=>$dataset->get('of_pa_toile_largeur')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('of_qte')*$dataset->get('of_pa_toile_quantite'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "of_pa_toile_hauteur",
								"largeur"     => "of_pa_toile_largeur",
								"qt"          => $dataset->get('of_qte')*$dataset->get('of_pa_toile_quantite'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('of_coloris')][]=$tableau;
					}
				} else if ($type_doc == 'OF - VELUX - TOILE') {
					if ($dataset->get('of_coloris')) {
						$tableau = array(	"hauteur"=>$dataset->get('of_hauteur')*1000,
							"largeur"=>$dataset->get('of_largeur')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('of_qte'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "of_hauteur",
								"largeur"     => "of_largeur",
								"qt"          => $dataset->get('of_qte'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('of_coloris')][]=$tableau;
					}
				} else if ($type_doc == 'OF - ENROULEURS - EXTERIEURS - TOILES') {
					if ($dataset->get('coloris')) {
						$tableau = array(	
							"hauteur"=>$dataset->get('hauteur_coupe_toile')*1000,
							"largeur"=>$dataset->get('largeur_coupe_toile')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('quantite'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_coupe_toile",
								"largeur"     => "largeur_coupe_toile",
								"qt"          => $dataset->get('quantite'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('coloris')][]=$tableau;
					}
				} else if ($type_doc == 'OF - FILM - INTERIEUR') {
					if ($dataset->get('reference')) {
						$tableau = array(	
							"hauteur"=>$dataset->get('hauteur_coupe_filmsinterieur')*1000,
							"largeur"=>$dataset->get('largeur_coupe_filmsinterieur')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('quantite'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_coupe_filmsinterieur",
								"largeur"     => "largeur_coupe_filmsinterieur",
								"qt"          => $dataset->get('quantite'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('reference')][]=$tableau;
					}
				} else if ($type_doc == 'OF - FILM - EXTERIEUR') {
					if ($dataset->get('reference')) {
						$tableau = array(	
							"hauteur"=>$dataset->get('hauteur_coupe_filmsexterieur')*1000,
							"largeur"=>$dataset->get('largeur_coupe_filmsexterieur')*1000,
							"repere"=>$repere,
							"nombre"=>$dataset->get('quantite'),
							"retrouve_le_dataset" => array("id_dataset" => $res['id_dataset'],
								"type_doc"    => $res['type_doc'],
								"hauteur"     => "hauteur_coupe_filmsexterieur",
								"largeur"     => "largeur_coupe_filmsexterieur",
								"qt"          => $dataset->get('quantite'))
						);
						$liste_dataset_by_gamme_couleur[$dataset->get('reference')][]=$tableau;
					}
				}
			}

			// Parcours des articles non toile geres en stock
			if (count($liste_autres_articles)) {
				foreach ($liste_autres_articles as $article => $conso) {
					$curs = new db_sql("SELECT lb_article_aff, coef_chute FROM article WHERE id_article = ".$article." ");
					$curs->n();
					if($curs->f(coef_chute))
						$conso = $conso * (1 + $curs->f(coef_chute) / 100);
					$consommation .= ($conso)." m : ".$titre[$article].$curs->f(lb_article_aff) ."<BR>";
					$GLOBALS[consommations_unitaires_consommees][$type_doc][$article] += $conso;

				}
			}
			
			if (is_array($GLOBALS[consommations_unitaires_consommees][$type_doc])) {
				foreach ($GLOBALS[consommations_unitaires_consommees][$type_doc] as $article => $conso) {
					$dbres->q("
						INSERT INTO besoin (
							of,
							id_article,
							qt,
							statut)
						VALUES (
							".$id_doc_of.",
							".$article.",
							".$conso.",
							''
						)");
				}
			}

			// parcours des toiles pour application de l'algorithme
			foreach ($liste_dataset_by_gamme_couleur as $gamme_couleur => $tab) {
				if ($_GET["debug"]) {
					echo "<pre>";
					echo "\n******************************************* Toile ***************************************\n";
					print_r($gamme_couleur);
					echo "</pre>";
				}

				//On récupère tous les laizes par gamme + couleur
				//list($gamme,$reference_gamme_couleur) = explode("|",$gamme_couleur);
				//$req = new db_sql("select * from article,toile where gamme = '$gamme' AND toile.id_toile = article.id_toile AND concat(reference_gamme, '-', reference_couleur ) = '$reference_gamme_couleur'");
				$req = new db_sql("select * from article, toile where toile.id_toile = '$gamme_couleur' AND toile.id_toile = article.id_toile AND article.type = 'surface' AND article.actif in (1,2) ");
				$tablaizes= array();
				//Stocke toutes les infos concernant un article
				$infoarticle = array();
				while ($req->n()) {
					if(!in_array($req->f('laize'), $tablaizes)) {
						$tablaizes[] = $req->f('laize');
						$orientation = $req->f('orientation');
						//Le lin ne se tourne pas sur les bateaux
						if (substr($req->f('lb_toile_atelier'),0,5) == 'LINS ' and $bateau) {
							$orientation = "oppose";
						}

						//On calcule le stock de tous les articles
						$lo_qtestockt = get_stock_article($req->f('id_article'));
						$req->Record[stock] = $lo_qtestockt['logique'];
						$infoarticle[$req->f('laize')] = $req->Record;
					}
				}

				if (!count($tablaizes)) return ("Pas de laize disponible pour la toile demandée ".$gamme_couleur);
				$acouper = array();
				foreach ($tab as $b => $infos) {
					$acouper[]=array(
						"hauteur"=>$infos[hauteur],
						"largeur"=>$infos[largeur],
						"repere"=>$infos[repere],
						"fils_bicolore"=>$infos[fils_bicolore],
						"pere_bicolore"=>$infos[pere_bicolore],
						"remarque_conso_raccordable"=>$infos[remarque_conso_raccordable],
						"nombre"=>$infos[nombre],
						"retrouve_le_dataset"=>$infos[retrouve_le_dataset]);
				}

				//$orientation = "neutre";
				if($type_doc=='OF - RIDEAU') {
					$sens_des_soudures = "hauteur";
				} else if ($type_doc=='OF - BATEAU - MECANISME' || $type_doc=='OF - BATEAU - CONFECTION'|| $type_doc=='OF - ENROULEUR - TOILES'|| $type_doc=='OF - ENROULEUR - MECANISME') {
					$sens_des_soudures = "largeur";
				}

				$result = calcul_besoin_matiere($acouper, $orientation, $sens_des_soudures, $tablaizes, $laizebicolore);
				//Supprime les laizes qui génèrent une coupe.
				foreach ($result as $x=>$coupe) {
					if ($coupe["nb_soudures"]>0) {
						$result[$x]["Tx de chute"] = 1000 * $coupe["nb_soudures"];
					}
				}


				//Calcule la laize qui génère le moins de chute
				$best_laize = -1;
				$best_chute = 10000000;
				foreach ($result as $x=>$coupe) {
					if (($coupe["Tx de chute"]*1) <= ($best_chute*1)) {
						$best_chute = $coupe["Tx de chute"];
						$best_laize = $x;
					}
				}

				$article = $result[$best_laize];
				$laize = $article[laize];

				if ($_GET["debug"]) {
					echo "<pre>";
					echo "\n*** best_laize\n";
					print_r($best_laize);
					echo "</pre>";
				}

				if (is_array($laizebicolore[$laize])) {
					$GLOBALS[laizebicolore] = $GLOBALS[laizebicolore] + $laizebicolore[$laize];
				}

				$result = array(	"laize"=>$laize,
					"id_article"=>$infoarticle[$laize][id_article],
					"qte"=>$article["Largeur totale coupée"]/1000,
					"directive"=>$infoarticle[$laize]["lb_article"]." ".$article["Directive"],
					"lb_article"=>$infoarticle[$laize]["lb_article"],
					"lb_article_aff"=>$infoarticle[$laize]["lb_article_aff"],
					"coef_chute"=>$infoarticle[$laize]["coef_chute"],
					"consommation"=>$article["Largeur totale coupée"]);

				// "result" et "article" contiennent LA solution
				// mise des parenthèses

				$nb_morceau_dans_la_coupe = array();
				$dataset = array();

				foreach($article[acouper] as $morceau) {
					$dataset[$morceau[retrouve_le_dataset][id_dataset]][$morceau[retrouve_le_dataset][sens].'|'.$morceau[retrouve_le_dataset][hauteur].'|'.$morceau[retrouve_le_dataset][largeur].'|'.$morceau[retrouve_le_dataset][qt]][$morceau[hauteur].'|'.$morceau[largeur]][$morceau[retrouve_le_dataset][coupe]] += 1;
				}

				$nb_morceau_dans_la_coupe = array();
				$coupe_a_etoiler = array();
				foreach($article[coupe] as $ncoupe => $coupe) {
					foreach($coupe[contenu] as $coupe_morceau) {
						//var_dump($coupe_morceau);
						if($coupe[largeur] != $coupe_morceau[morceau][$coupe_morceau[sens]]) {
							$coupe_a_etoiler[$ncoupe] = $coupe[largeur];
						}
						// Pour repérer (pavé de code juste au dessous) les coupes qui n'ont pas besoin d'être repérées car elles concernent une seule ligne
						$nb_morceau_dans_la_coupe[$ncoupe] +=1;
					}
				}

				// Les coupes sont numérotés.
				// Le numéro de coupe est va êtr mis à "*" quand la coupe est sur une seule ligne
				// $amettre est le tableau qui va permettre de transformer les cellules

				$amettre = array();
				foreach($dataset as $id_dataset=>$senss) {
					foreach($senss as $sens=>$dimentions) {
						foreach($dimentions as $dimention=>$coupes) {
							foreach($coupes as $ncoupe=>$nombre) {
								if ($nb_morceau_dans_la_coupe[$ncoupe] == $nombre) {
									$ncoupe = "*"; //  Pas besoin de spécifier la coupe puisqu'elle concerne une seule ligne
								}
								$amettre[$id_dataset][$sens][$dimention][$ncoupe][$nombre] += 1;
								//$ncoupe : numéro de coupe
							}
						}
					}
				}

				if ($_GET["debug"]) {
					echo "<pre>";
					echo "\n*** article\n";
					print_r($article);
					echo "\n*** dataset\n";
					print_r($dataset);
					echo "\n*** nb_morceau_dans_la_coupe\n";
					print_r($nb_morceau_dans_la_coupe);
					echo "\n***amettre\n";
					print_r($amettre);
					echo "\n***coupe_a_etoiler\n";
					print_r($coupe_a_etoiler);
					echo "</pre>";
				}

				$odataset = new dataset($GLOBALS[parms][type_docs][$type_doc][type_dataset_ligne]);
				foreach($amettre as $id_dataset=>$senss) {
					$odataset->load_from_database($id_dataset);
					$hauteur = "";
					$largeur = "";
					foreach($senss as $sens=>$dimentions) {
						$tab = explode('|',$sens);
						$sens = $tab[0];
						$champ[hauteur] = $tab[1];
						$champ[largeur] = $tab[2];
						$qt = $tab[3];
						$odataset->load_from_database($id_dataset);
						$odataset->ochamp[$champ[hauteur]]->set('');
						$odataset->ochamp[$champ[largeur]]->set('');
						foreach($dimentions as $dimention=>$modes) {
							
							$tab = explode('|',$dimention);
							$valeur[hauteur] = formate_troiszeroapresvirgule($tab[0]/1000);
							$valeur[largeur] = formate_troiszeroapresvirgule($tab[1]/1000);
							
							foreach($modes as $nbcoupe => $nombres) {
								foreach($nombres as $nombre => $fois) {
									$libelle = "";
									if ((($nbcoupe.'') != "*") or ($nombre > 1)) {
										$libelle = " ".$nombre." en lé";
										if (($nbcoupe.'') != "*") {
											if (!$lettre[$gamme_couleur][$nbcoupe]) {
												$compteurlettre++;
												if ($compteurlettre == 27) {
													$compteurlettre = 1;
													$compteurrangdeux++;
													$rangdeux = chr($compteurrangdeux+ord('A')-1);
												} 
												$lettre[$gamme_couleur][$nbcoupe] = $rangdeux.chr($compteurlettre+ord('A')-1);
											}
											$libelle .= " ".$lettre[$gamme_couleur][$nbcoupe];
										}
									}

									$qt_par_qt = $fois/$qt;
									if ($qt_par_qt > 1) {
										$libelle .= " x ".$qt_par_qt;
									}

									${contre($sens)} .= "(".$valeur[contre($sens)].")".$libelle."\n";

									//echo $coupe_a_etoiler[$nbcoupe]/100;
									//echo $valeur[$sens];
									//echo 'v';

									if ($coupe_a_etoiler[$nbcoupe]/1000 == strtr($valeur[$sens],',','.')*1) {
										$etoile = " *";
									} else {
										$etoile = "";
									}

									${$sens} .= $valeur[$sens].$etoile."\n";

								}
							}
						}
					}
					
					$odataset->ochamp[$champ[hauteur]]->set(substr($hauteur,0,-1));
					$odataset->ochamp[$champ[largeur]]->set(substr($largeur,0,-1));
					$odataset->store($dbre);

				}


				if($result[id_article]) {
					if($result['coef_chute'])
						$result['consommation'] = $result['consommation'] * (1 + $result['coef_chute'] / 100);
					$consommation .= ($result['consommation']/1000)." m : ".$result['lb_article_aff']."<BR>";
					$directive_coupe .= $result[directive];
					//On insère dans la table besoin
					$dbres->q("
					   INSERT INTO besoin (
						of,
						id_article,
						qt,
						statut)
					   VALUES (
						".$id_doc_of.",
						".$result[id_article].",
						".($result[consommation]/1000).",
						''
					   )");

				} else {
					$tableau_solution_vide = true;
					break;
				}
			}

			if (is_array($GLOBALS[consommations_unitaires_affichees][$type_doc])) {
				foreach ($GLOBALS[consommations_unitaires_affichees][$type_doc] as $id_article => $qt) {
					$curs = new db_sql("SELECT lb_article_aff FROM article WHERE id_article = ".$id_article." ");
					$curs->n();
					if($qt and $curs->f(lb_article_aff) ) {
                        $consommation .= ($qt)." : ".$curs->f(lb_article_aff) ."<BR>";
                    }
				}
			}


			$type_dataset_empied = $GLOBALS[parms][type_docs][$type_doc][type_dataset_empied];
			$dataset_empied = new dataset($type_dataset_empied);
			foreach ($dataset_empied->champs as $id_champ => $champ) {
				if($dataset_empied->ochamp[$id_champ]->id_champ=="directive_coupe") {
					if ($directive_coupe != "DIRECTIVES DE COUPE<BR>") {
						$directive_coupe = substr($directive_coupe, 0, -4);
						$dataset_empied->ochamp[$id_champ]->set($directive_coupe);
					}
				} else if($dataset_empied->ochamp[$id_champ]->id_champ=="listeproduits") {
					$dataset_empied->ochamp[$id_champ]->set($listeproduits);
				} else if($dataset_empied->ochamp[$id_champ]->id_champ=="of_consommation") {
					if ($consommation != "CONSOMMATION TOTALE<BR>") {
						if ($GLOBALS[remarque_conso_raccordable]) {
							$remarque_conso  = ' ******************************************<BR>';
							$remarque_conso .= ' * Attention RIDEAUX à MOTIFS RACCORDABLES <BR>';
							$remarque_conso .= ' * LES MOTIFS DOIVENT ETRE RACCORDES       <BR>';
							$remarque_conso .= ' * impacte coupe et couture                <BR>';
							$remarque_conso .= ' ******************************************<BR>';
						}

						if ($remarque_conso_ajustable) {
							$remarque_conso .= ' *********************************************<BR>';
							$remarque_conso .= ' * Attention RIDEAUX à MOTIFS AJUSTABLES      <BR>';
							$remarque_conso .= ' * LES MOTIFS DOIVENT ÊTRE ALIGNES PAR PAIRE  <BR>';
							$remarque_conso .= ' *********************************************<BR>';
						}

						$consommation = substr($consommation.$remarque_conso, 0, -4);
						$dataset_empied->ochamp[$id_champ]->set($consommation);
					}

				} else if ($fonction = $dataset_empied->parms[fonction_init][$id_champ]) {
					$valeur = call_user_func($fonction);
					$dataset_empied->ochamp[$id_champ]->set($valeur);
				}
			}

			$id_dataset_empied = $dataset_empied->store($dbres);
			$dbres->q("update doc set id_dataset_empied = $id_dataset_empied where id_doc = $id_doc_of");
			
			if ($GLOBALS[pb_bande_5]) {

				//$dbres->rollback();

				$set = charge_un("select id_dataset_entete from doc where id_doc = $id_doc");
				$id_dataset_entete = $set[id_dataset_entete];
				$dataset_entete = new dataset('fdcentete');
				$dataset_entete->load_from_database($id_dataset_entete);
				$title = $dataset_entete->ochamp[numcommande_fdc]->get();
				return "<font color = red> Bande de moins de 10cm pour " . $type_doc."</font><BR><a href=\"javascript:relance('".$title."',1);\">Forcer</a>&nbsp;<a href=\"javascript:relance('".$title."',2);\">Raccourcir</a>";
			}

			if($tableau_solution_vide) {
				//$dbres->rollback();
				return "Directives de coupe non calculable pour " . $type_doc;
			}
		}
	}

	//$dbres->commit();

	return false;

}

/*-------------------------------------------------
 Retourne la laize qui minimise la chute
-------------------------------------------------*/

function getLaizeMinimiseChute($listelaize){

	$result = array();
	$laizemin = 0; $lastChute =0;

	foreach ($listelaize as $laize => $article) {
		if($laizemin==0){
			$laizemin = $laize;
			$lastChute = $article["Chute"];
		}

		else if($lastChute>$article["Chute"]){
			$laizemin = $laize;
			$lastChute = $article["Chute"];
		}
	}

	//On récupère toutes les infos de la laize

	foreach ($listelaize as $laize => $article) {
		if($laize==$laizemin)
			$result = array("laize"=>$laize,"id_article"=>$article[id_article],"qte"=>$article["Largeur totale coupée"]/100,"directive"=>$article["lb_article"]." ".$article["Directive"], "lb_article"=>$article["lb_article"], "consommation"=>$article["Largeur totale coupée"]);
	}
	return $result;
}

/*

-------------------------------------------------
 Retourne l'url de la page courante
-------------------------------------------------*/

function CurrentPageURL() {
	$pageURL = $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';
	$pageURL .= $_SERVER['SERVER_PORT'] != '80' ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	return $pageURL;
}


/*-------------------------------------------------
 Création du bon de pose ou de bon de livraison
$id_doc = id de la feuille de cote
-------------------------------------------------*/

function create_bon($id_doc, $dbres) {

	/* Recupération des informations sur le doc(le dataset entête/empied), le type de prestation */

	$requete="
        SELECT
          dt.prestation as prestation,
          doc.id_doc,
          doc.id_dataset_entete,
          doc.id_dataset_empied,
          dataset.type_dataset
        FROM
          zdataset_fdcentete dt,
          doc,
          dataset
        WHERE
          doc.type_doc='fdc' and
          dt.id_dataset = doc.id_dataset_entete and
          dataset.id_dataset = doc.id_dataset_entete and 
          dt.num_fdc= $id_doc ";

	$result = charge_un($requete, $dbres);

	if($result[prestation]) {

	/* Recupération des informations de l'entête de feuille de cote */
	$dataset_fdc = new dataset($result[type_dataset]);
	$dataset_fdc->load_from_database($result[id_dataset_entete]);

	/* Alimentation du bon d'entête */
	$dataset_tete = new dataset("bonentete");
	$dataset_empied = new dataset("bonentete");

	/* Récupération de toutes les informations de l'entête de FDC */
	foreach ($dataset_tete->champs as $champ => $nom){
		if($val = $dataset_fdc->ochamp[$champ]) {
			$dataset_tete->ochamp[$champ]->set($val->get());
			$dataset_empied->ochamp[$champ]->set($val->get());
		} else if ($champ == "statut_of") {
			$dataset_tete->ochamp[$champ]->set('Créé');
		} else if ($val = $dataset_fdc->ochamp[substr($champ,0,strlen($champ)-3)]) {
			$dataset_tete->ochamp[$champ]->set($val->get());
			$dataset_empied->ochamp[$champ]->set($val->get());
		}	
   }

	$dataset_tete->ochamp[fdc]->set($dataset_fdc->ochamp["num_fdc"]->get());
	$dataset_empied->ochamp[fdc]->set($dataset_fdc->ochamp["num_fdc"]->get());

	/** Création de l'entete du bon **/

	/* Synthese bon de pose */
	$id_dataset_entete = $dataset_tete->store();
	$req = "
		SELECT
			*
		FROM
			doc_ligne
		WHERE
			id_doc=$id_doc
		ORDER BY
			produit";

	$resultat = new db_sql($req);
	$tablisteproduits = array();
	$listeproduits="";
	while($resultat->n()){
		$champ_qte = "qte";
		$id_dataset = $resultat->f('id_dataset');
		$type_dataset = getTypeDataset($id_dataset);
		$Total = req_sim("SELECT SUM($champ_qte) AS Total from `zdataset_".$type_dataset."` where id_dataset = '$id_dataset'","Total");
		$tablisteproduits[$resultat->f('produit')] += $Total;
	}

	foreach ($tablisteproduits as $produit => $nb) {
		$listeproduits .= $produit." : ".$nb."\n";
	}

    $resultat = charge("
        SELECT 
            doc_groupe_ligne.id_dataset, 
            type_dataset
        FROM 
            doc_groupe_ligne,
            dataset 
        WHERE 
            dataset.id_dataset = doc_groupe_ligne.id_dataset and 
            id_doc = $id_doc");

    $cpt_my_lev = $cpt_dep_pre = $cpt_com_pos =  0;
    foreach ($resultat as $i => $res) {
        $dataset = new dataset($res[type_dataset]);
        $dataset->load_from_database($res[id_dataset]);

        foreach($dataset->ochamp as $champ => $tab){

            // Moyen de levage
            if( ($moy_lev = $dataset->ochamp['moyens_levage']) and ($moy_lev->get() != "Aucun" and $moy_lev->get() != "") ){
                $cpt_my_lev++;
            }

            // Depose prealable
            if( ($dep_pre = $dataset->ochamp['depose_prealable']) and ($dep_pre->get() == "Oui") ){
                $cpt_dep_pre++;
            }

            // Commentaires Pose
            if( ($com_pos = $dataset->ochamp['commentaires_pose']) and ($com_pos->get() and $com_pos->get() != "") ){
                $cpt_com_pos++;
            }
        }
    }

    $listeproduits .= ($cpt_my_lev>0) ? "Moyens de Levage : OUI \n" : "Moyens de Levage : NON \n";
    $listeproduits .= ($cpt_dep_pre>0) ? "Dépose : OUI \n" : "Dépose : NON \n";
    $listeproduits .= ($cpt_com_pos>0) ? "Commentaires Pose : OUI voir Produits \n" : "Commentaires Pose : NON \n";

	$dataset_empied->ochamp[synthese_produit]->set($listeproduits);
	$id_dataset_empied = $dataset_empied->store();

	$type_prestation = $result[prestation];
	$type_doc="BON DE LIVRAISON";
	$synthese_livraison="OF - BON - DE - LIVRAISON";

	if($type_prestation == "Fourniture & pose"){
		$type_doc="BON DE POSE";
	}

	/** Création du doc qui est relé à l'entete du bon**/
	$dbres->q("insert into doc(type_doc,id_dataset_entete,id_dataset_empied) values ('$type_doc', $id_dataset_entete, $id_dataset_empied)");
	$id_doc_bon = db_sql::last_id ();

   $list_produit = charge("
        select
            produit,
            id_dataset
        from
            doc_groupe_ligne
        where
            id_doc =$id_doc and 
        exists(
            SELECT 
                1
            FROM 
                doc_ligne 
            WHERE 
                id_doc = $id_doc and 
                produit = doc_groupe_ligne.produit) ");

    /* Traitement du Bon de pose */

    if($type_doc == "BON DE POSE"){

        foreach ($list_produit as $i => $res) {
			
            require("./param_2_produits/".$res[produit].".php");

            if (is_array($produits)) {

					$type_dataset_groupe_ligne = $produits[$res[produit]][type_dataset_groupe_ligne];
					$type_dataset_ligne = $produits[$res[produit]][type_dataset_ligne];

					/* Entête de produit */

					$dataset = new dataset($type_dataset_groupe_ligne);
					$dataset_ente_pr = new dataset($type_dataset_groupe_ligne);
					$dataset_ente_pr->load_from_database($res[id_dataset]);

					foreach($dataset->ochamp as $champ => $tab){
						if($val = $dataset_ente_pr->ochamp[$champ]){
							$dataset->ochamp[$champ]->set($val->get());
						}
					}

					$id_dataset = $dataset->store();
					$dbres->q("insert into doc_groupe_ligne(id_doc, produit, id_dataset) values ($id_doc_bon, '$res[produit]', $id_dataset)");

					/* Ligne de produit */
					$produit_lignes= charge("
									  select 
											produit,
											doc_ligne.id_dataset,
											type_dataset
									  from 
											doc_ligne,
											dataset 
									  where 
											dataset.id_dataset = doc_ligne.id_dataset and
											produit ='".$res[produit]."' and  
											id_doc = $id_doc ");

					foreach($produit_lignes as $produit_ligne) {

					$dataset_ligne = new dataset($type_dataset_ligne);
					$dataset_ligne_produit = new dataset($produit_ligne[type_dataset]);
					$dataset_ligne_produit->load_from_database($produit_ligne[id_dataset]);

					foreach($dataset_ligne->ochamp as $champ => $tab){
						if($val = $dataset_ligne_produit->ochamp[$champ]){
                            $valeur = $val->get();

                            if (StartsWith($champ,"reference")) {
                                $champs = new champ($champ);
                                if(is_numeric($valeur) && $champs->id_type_champ == "toile"){
                                    $req = charge_un("select lb_toile_atelier from toile where id_toile = ".$valeur);
                                    $valeur = $req[lb_toile_atelier];
                                }
                            }
                            $dataset_ligne->ochamp[$champ]->set($valeur);
                            }
					}


					$id_dataset = $dataset_ligne->store();
					$dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc_bon, '$res[produit]', $id_dataset)");

					}

				}
        }
    }


    /* Traitement du Bon de livraison */

    if($type_doc == 'BON DE LIVRAISON') {
			$list_produit= charge("
								select 
									 produit,
									 doc_ligne.id_dataset, 
									 dataset.type_dataset 
								from 
									 doc_ligne,
									 dataset 
								where 
									 doc_ligne.id_dataset = dataset.id_dataset and
									 id_doc = $id_doc ");

			$tab = array();
			foreach ($list_produit as $i => $res) {
				$dataset = new dataset($res[type_dataset]);
				$dataset->load_from_database($res[id_dataset]);

				$champ_quantite = "qte";
				$tab[$res[produit]] += $dataset->ochamp[$champ_quantite]->get();
			}

			foreach ($tab as $produit => $quantite){
				$dataset = new dataset($synthese_livraison);
				$dataset->ochamp[type_produit]->set($produit);
				$dataset->ochamp[quantite]->set($quantite);

				$id_dataset = $dataset->store();
				$dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc_bon, '-', $id_dataset)");
			}
		}
	}
}



/*-------------------------------------------------
 Générer les différents OF
-------------------------------------------------*/

function generer_of($id_doc, $dbres="") {

	$GLOBALS[consommations_unitaires_affichees] = [];
	
	require_once("param_5_fonctions/tous_produits.php");

	create_bon($id_doc, $dbres);

	$liste_ligne = charge("
		select
			doc_ligne.id_dataset,
			doc_ligne.produit,
			doc.type_doc,
			doc.id_dataset_entete,
			doc.id_dataset_empied
		from
			doc_ligne,
			doc
		where
			doc.id_doc = doc_ligne.id_doc and
			doc.id_doc = $id_doc");

	$tab_of = array();
	$data = array();

	// Génération des dataset de ligne (en RAM)
	// Pour chaque ligne de produit de la fdc initiale

	$parametres_entete_of = array();
	foreach ($liste_ligne as $i => $res) {
		$type_doc = $res[type_doc];

		require("param_2_produits/".$res[produit].".php");
				
		//Initialisation de $parametres (tableau de paramètres du dataset source)
		$parametres = array();
		$parametres['produit'] = $res[produit];
		
		// à partir de l'entete de feuille de cote
		$dataset = new dataset("fdcentete");
		$dataset->load_from_database($res[id_dataset_entete]);
		
		foreach ($dataset->champs as $id_champ => $champ) {
			$parametres[$id_champ] = $dataset->ochamp[$id_champ]->get();
		}		
		
		// à partir de la ligne de feuille de cote
		$dataset = new dataset($produits[$res[produit]][type_dataset_ligne]);
		$dataset->load_from_database($res[id_dataset]);

		foreach ($dataset->champs as $id_champ => $champ) {
			$parametres[$id_champ] = $dataset->ochamp[$id_champ]->get();
		}
		
		// Pour ne consommer qu'une fois les articles qui sont dans l'entete de produit
		if ($deja_vu[$res[produit]]) {
			$parametres[premier] = 0;
		} else {
			$parametres[premier] = 1;
			$deja_vu[$res[produit]] = 1;
		}
		
		/*if($dataset->ochamp['origine']->get() == 3) {
			$adr = explode(PHP_EOL, $dataset->ochamp['adresse']->get());
			$parametres['code_departement'] = substr(end($adr), 0, 2);
		}*/

		// à partir des informations d'entete de produit
		$type_dataset_groupe_ligne = $produits[$res[produit]][type_dataset_groupe_ligne];
		if($type_dataset_groupe_ligne){
			$dataset_groupe_ligne= new dataset($type_dataset_groupe_ligne);
			$doc = charge_un("select id_dataset from doc_groupe_ligne where id_doc = $id_doc and produit='$res[produit]'");
			$id_dataset_groupe_ligne = $doc[id_dataset];
			if ($id_dataset_groupe_ligne) {
				$dataset_groupe_ligne->load_from_database($id_dataset_groupe_ligne);
			}
			foreach ($dataset_groupe_ligne->champs as $id_champ => $champ) {
				$parametres[$id_champ] = $dataset_groupe_ligne->ochamp[$id_champ]->get();
				$parametres_entete_of[$parametres['produit']][$id_champ] = $dataset_groupe_ligne->ochamp[$id_champ]->get();
			}
		}

		// Récupération de la liste des of à générer pour le produit
		$liste_of = $produits[$res[produit]][of];
		if (is_array($liste_of)) {
			foreach ($liste_of as $j => $of) {
				// Remplit ce tableau pour pouvoir rattacher les of aux produits précisément à la fin
				$produit_d_of[$of] = $res[produit];
				//Généreration des datasets de ligne
				charge_doc($of);

				$type_dataset_ligne = $GLOBALS[parms][type_docs][$of][type_dataset_ligne];
				$ofdataset = new dataset($type_dataset_ligne);
				//Si c'est l'of de début d'un produit
				if($j==1) 
					$data_ligne = array();
				$parametres[type_doc] = $of;
				
				/*
				if($fonction_conso = $GLOBALS[parms][type_docs][$of][consommation]) {
					call_user_func($fonction_conso, $parametres);
				}
				*/
				// A supprimer : les [consommation] dans les OK
				
				foreach ($ofdataset->champs as $id_champ => $champ) {
					if($ofdataset->ochamp[$id_champ]->id_type_champ) {
						//if($GLOBALS[parms][fonctioninit][$res[produit]][$type_dataset_ligne][$id_champ]) {
						$fonction_init = $champ[fonction_init];
						if (!$fonction_init)
							$fonction_init = $produits[$res[produit]][fonction_init][$type_dataset_ligne][$id_champ];
						if ($fonction_init) {
							if (substr($fonction_init,0,6) == "copie|") {
								$ofdataset->ochamp[$id_champ]->set($parametres[substr($fonction_init,6)]);
							} else {
								require_once($produits[$res[produit]][fonction]);
								$valeur =call_user_func($fonction_init,$parametres);
								$ofdataset->ochamp[$id_champ]->set($valeur);
								//Si c'est l'of de début d'un produit
								if($j==1) $data_ligne[$id_champ] = $valeur;
							}
						}
					}
				}
				
				// Calcul des consommations 
				$liste_regle = charge("
					select 
						consommation.article,
						cond,
						quantite,
						consommation,
						consommation.id,
						affichage,
						article.coef_chute
					from
						consommation
						left outer join article on article.id_article = consommation.article
					where
						of = '".$of."'");
				foreach($liste_regle as $regle) {
					$article = $regle[article];
					$condition = $regle[cond];
					$quantite = $regle[quantite];
					$consommation = $regle[consommation];
					$affichage = $regle[affichage];
					$coef_chute = $regle[coef_chute];
					if (!function_exists('traduit_param')) {
						function traduit_param(&$string,$parametres) {
							$string = " ".$string;
							while ($deb = strpos($string,"[")) {
								$fin = strpos($string,"]",$deb);
								$champ=substr($string,$deb+1,$fin-$deb-1);
								if (substr($champ,0,3)=='get') {
									$lo_valeur=$champ($parametres);
								} else {
									$lo_valeur = $parametres[$champ];
								}
								if (strlen($lo_valeur) == 0) {
									$lo_valeur = "''"; 
								} else if ((string)$lo_valeur != (string)($lo_valeur*1)) {
									$lo_valeur = "'".$lo_valeur."'";
								}
								$string = substr($string,0,$deb).$lo_valeur.substr($string,$fin+1);
							}
							$string = substr($string,1);
						}
					}

					traduit_param($article,$parametres);
					traduit_param($condition,$parametres);
					traduit_param($quantite,$parametres);

					$ret = @eval ("\$article_ = (".$article.")*1; return 1;");
					if ($ret == false) {
						return "Erreur d'exécution de la formule de calcul article de consommation ( $article ) pour $of. Règle n° ".$regle[id]." : ".$regle[consommation];
					}
					
					$ret = @eval ("\$condition_ = (".$condition."); return 1;");
					if ($ret == false) {
						return "Erreur d'exécution de la formule de calcul condition de consommation ( $condition ) pour $of. Règle n° ".$regle[id]." : ".$regle[cond];
					}
					
					$ret = @eval ("\$quantite_ = (".$quantite.")*1; return 1;");
					if($coef_chute) 
						$quantite_ = $quantite_ * (1 + $coef_chute / 100);
					
					if ($ret == false) {
						return "Erreur d'exécution de la formule de calcul quantite de consommation ( $quantite ) pour $of. Règle n° ".$regle[id]." : ".$regle[quantite];
					}
					
					if ($condition_ and $affichage) {
						$GLOBALS[consommations_unitaires_affichees][$of][$article_] += $quantite_;
					}

					if ($condition_ and $consommation) {
						$GLOBALS[consommations_unitaires_consommees][$of][$article_] += $quantite_;
					}

				}
				// Fin du calcul des consommations

				$tab_of[$of][]=$ofdataset;
				if($j==1)
					$data[]=$data_ligne;
			}
		}
	}
	// Ici $tab_of[$of] (tableau des lignes d'OF est rempli
 
	//Chargement en RAM, du dataset d'entete de la fdc : $datasetentete 
	$doc = charge_un("select * from doc where id_doc = $id_doc");
	$id_dataset_entete = $doc[id_dataset_entete];
	$type_doc = $doc[type_doc];
	charge_doc($type_doc);

	$type_dataset_entete = $GLOBALS[parms][type_docs][$type_doc][type_dataset_entete];
	$datasetentete = new dataset($type_dataset_entete);
	$datasetentete->load_from_database($id_dataset_entete);

	// Mise à jour de la date d'import
	$datasetentete->ochamp['date_import']->set(aujourdhui());
	$datasetentete->store($dbres);

	$parametres = array();
	foreach ($datasetentete->champs as $id_champ2 => $champ) {
		$parametres[$id_champ2] = $datasetentete->ochamp[$id_champ2]->get();
	}	

	if(!$dbres){
		$dbres= new db_sql();
		$dbres->begin();
	}

	//Génération des OF par regroupement des lignes de $tab_of
	foreach ($tab_of as $of => $tab_dataset) {
		//Génération du dataset d'entete
		$type_dataset_entete = $GLOBALS[parms][type_docs][$of][type_dataset_entete];
		//Ajout type DS entête dans parametres
		$parametres['type_dataset_entete'] = $GLOBALS[parms][type_docs][$of][type_dataset_entete];
		$parametres['produit'] = $produit_d_of[$of];
		$parametres['id_dataset_entete'] = $id_dataset_entete ;

		$ofdatasetentete = new dataset($parametres['type_dataset_entete']);

		// Remplissage des champs d'entete de l'of
		foreach ($ofdatasetentete->champs as $id_champ => $champ) {
			$fonction = $ofdatasetentete->parms[fonction_init][$id_champ];
			$fonction_init = $champ[fonction_init];
			if($fonction_init) {
				$valeur = call_user_func($fonction_init,$parametres);
				$ofdatasetentete->ochamp[$id_champ]->set($valeur);
			} else if ($id_champ == 'produit_entete_of') {
				$valeur = $produit_d_of[$of];
		} else if ($id_champ == 'produit_entete_commande') {
				$valeur = $produit_d_of[$of];				
			} else if (is_object($datasetentete->ochamp[$id_champ])){
				$valeur = $datasetentete->ochamp[$id_champ]->get() ;
			} else {
				$id_champ_entete = substr($id_champ,0,strlen($id_champ)-3);
				if(is_object($datasetentete->ochamp[$id_champ_entete]))	 {
					if($id_champ_entete!="statut")
						$valeur = $datasetentete->ochamp[$id_champ_entete]->get() ;
					else
						$valeur = "Créé" ;
				}
			}
			$ofdatasetentete->ochamp[$id_champ]->set($valeur);
		}

		$ofdatasetentete->ochamp['fdc']->set($id_doc);
	
		
		if($ofdatasetentete->ochamp['commentaires_fabrication'] ){
			$ofdatasetentete->ochamp['commentaires_fabrication']->set($parametres_entete_of[$parametres['produit']][commentaires_fabrication]);
		}

		if($ofdatasetentete->ochamp['commentaire_fabrication_sf'] ){
			$ofdatasetentete->ochamp['commentaire_fabrication_sf']->set($parametres_entete_of[$parametres['produit']][commentaires_fabrication]);
		}

		$id_dataset_entete = $ofdatasetentete->store($dbres);
		// Génération physiquement du doc OF
		$dbres->q("insert into doc(type_doc,id_dataset_entete) values ('$of', $id_dataset_entete)");
		$id_doc_of = db_sql::last_id ();
		$liste_ofs[$id_doc_of] = $of;
		foreach ($tab_dataset as $key => $dataset) {
			//Stockage du dataset calculé précédemment
			$id_dataset = $dataset->store($dbres);
			$dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc_of, '-', $id_dataset)");
		}
	}

	return generer_besoins_matiere($id_doc,$liste_ofs,$dbres);
}

/*-------------------------------------------------------------
 Supprime un dataset
-------------------------------------------------------------*/

function delete_dataset($id_dataset, $dbres = "") {
	if (!$dbres) {
		$dbres=new db_sql();
	}
	$type_dataset_del = getTypeDataset($id_dataset);
	if ($type_dataset_del) {
		$dbres->q("DELETE FROM `zdataset_".$type_dataset_del."` where id_dataset = ".$id_dataset);
	}
	$dbres->q("DELETE FROM dataset where id_dataset = ".$id_dataset);
}

/*-------------------------------------------------------------
 Supprime un doc (OF-FDC...)
------------------------------------------------------------*/

function delete_doc($id_doc,$dbres) {

	//Chargement des infos du doc
	$doc = charge_un("select id_dataset_entete, id_dataset_empied from doc where id_doc = $id_doc");
	$liste_ligne = charge("select id_dataset from doc_ligne where id_doc = $id_doc");

	//Suppression des besoins matière si besoin;
	if (substr($doc[type_doc],0,2)=="OF") {
		$dbres->q("DELETE FROM besoin where of = $id_doc");
	}

	//Suppression du doc lui-même
	$dbres->q("DELETE FROM doc_ligne where id_doc = $id_doc");
	$dbres->q("DELETE FROM doc_groupe_ligne where id_doc = $id_doc");
	$dbres->q("DELETE FROM doc where id_doc = $id_doc");

	//Suppression des dataset de ligne
	foreach ($liste_ligne as $i => $ligne) {
		delete_dataset($ligne[id_dataset],$dbres);
	}

	//Suppression des datasets d'entete et d'empied
	delete_dataset($doc[id_dataset_entete],$dbres);
	delete_dataset($doc[id_dataset_empied],$dbres); // A réparer

}

/*

-------------------------------------------------------------
 Vérifie si on a déjà généré les documents liés pour un document
-------------------------------------------------------------*/

function is_document_lie_cree($id_doc) {
	$requete="
		SELECT
			doc.id_doc
		FROM
			(
				select id_dataset, fdc from zdataset_bonentete
				union
				select id_dataset, fdc from zdataset_commandeentete
				union
				select id_dataset, fdc from zdataset_ofentete
			) as dt,
			doc
		WHERE
			dt.id_dataset = doc.id_dataset_entete and
			dt.fdc= $id_doc ";
	return !isEmpty_Record(new db_sql($requete));
}

/*
-------------------------------------------------------------
 Retourne la valeur d'un champ dans l'entete d'un document
-------------------------------------------------------------*/

function getstatutbyiddoc($id_doc) {
	$requete="
		SELECT
			zdataset_fdcentete.statut
		FROM
			zdataset_fdcentete,
			doc
		WHERE
			zdataset_fdcentete.id_dataset = doc.id_dataset_entete and
			doc.id_doc= $id_doc 
	";
	$resultat = new db_sql($requete);
	while($resultat->n()){
		return $resultat->f('statut');
	}
}

/*
-------------------------------------------------------------
 Retourne le statut en cours
-------------------------------------------------------------*/

function getStatut() {
	$liste_dt = charge_liste_dataset();
	if(is_array($liste_dt)) {
		foreach ($liste_dt as $key => $type) {
			if($type=="fdcentete"){
				$dset = new dataset($type);
				$dset->id_dataset = $key;
				$dset->load_from_screen();
				foreach ($dset->champs as $id_champ => $champ) {
					if($id_champ=="statut") {
						return $dset->ochamp[$id_champ]->get();
						break;
					}
				}
				break;
			}
		}
	}
}

/*
------------------------------------------------------------
 Retourne la valeur d'un champ dans un dataset
-------------------------------------------------------------*/

function getFieldValue($idchamp,$dset) {
	foreach ($dset->champs as $id_champ => $champ) {
		if($id_champ==$idchamp) {
			return $dset->ochamp[$id_champ]->get();
			break;
		}
	}
}

/*
----------------------------------------------------------------------------------
 Retourne le dataset de l'entete de produit correspondant à une ligne de produit
----------------------------------------------------------------------------------*/

function getDatasetProduitEntete($dset) {
	if($dset->type_dataset=="fdcproduitdroit"||$dset->type_dataset=="fdcproduitrouleau"){
		$type_dataset_to_search =substr($dset->type_dataset,3,strlen($dset->type_dataset)-2).'entete';
	}
	else if($dset->type_dataset=="fdcproduit55"){
		$type_dataset_to_search =substr($dset->type_dataset,3,strlen($dset->type_dataset)-3).'entete';
	}
	else if($dset->type_dataset=="fdcproduit753"||$dset->type_dataset=="fdcproduit754"||$dset->type_dataset=="fdcproduit953"||$dset->type_dataset=="fdcproduit954"){
		$type_dataset_to_search =substr($dset->type_dataset,3,strlen($dset->type_dataset)-4).'entete';
	}
	$liste_dt = charge_liste_dataset();
	if(is_array($liste_dt)) {
		foreach ($liste_dt as $key => $type) {
			if($type==$type_dataset_to_search){
				$dtset = new dataset($type);
				$dtset->id_dataset = $key;
				$dtset->load_from_screen();
				return $dtset;
				break;
			}
		}
	}
}

function check_statut_en_cours($id_doc){
$req =charge_un("
    SELECT 
        id_doc
    FROM 
        doc, zdataset_fdcentete
    WHERE
        zdataset_fdcentete.id_dataset = doc.id_dataset_entete and
        id_doc = $id_doc AND
        zdataset_fdcentete.statut = 'En Cours' ");

    return ($req[id_doc])? true : false;
}

?> 

