<?php

function getItemValue($item,$balise) {
   $tnl = $item->getElementsByTagName($balise);
   $tnl = $tnl->item(0);
   return html_entity_decode($tnl->firstChild->textContent);
}

function isUnEchantillon($y) {
	$nbart = 0;$nbEch = 0;
	foreach($y["listeCodeArticle"] as $val) {
		if( stripos("*".$val ,"echantillon") ) $nbEch++;
		else if(!stripos("*".$val ,"livraison")) $nbart++;
	}
	return (($nbEch>0) and (!$nbart));
}

function import_adress($y) {
	$shippingcontactlastname = $y["clientnom"] ;
    $shippingcontactfirstname = $y["clientprenom"];
    $entreprise = $y["entreprise"] ;
    $shippingaddress1 = $y["address1"] ;
    $shippingaddress2 = $y["address2"];
    $shippingaddress3 = $y["address3"];
    $shippingaddress4 = $y["address4"];
    $shippingpostalcode = $y["postalcode"];
    $shippingcity = $y["city"];
    $shippingcountrycode = $y["countrycode"];
    $date_import=aujourdhui();

    $req="insert adresse_echantillon (entreprise,shippingcontactfirstname,shippingcontactlastname,shippingaddress1,shippingaddress2,shippingaddress3,shippingaddress4,shippingpostalcode,shippingcity,shippingcountrycode,date_import,origine) values (
		".My_sql_format($entreprise).",
		".My_sql_format($shippingcontactfirstname).",
		".My_sql_format($shippingcontactlastname).",
		".My_sql_format($shippingaddress1).",
		".My_sql_format($shippingaddress2).",
		".My_sql_format($shippingaddress3).",
		".My_sql_format($shippingaddress4).",
		".My_sql_format($shippingpostalcode).",
		".My_sql_format($shippingcity).",
		".My_sql_format($shippingcountrycode).",
		'$date_import',
		".$y["origine"].")";
	//new db_sql($requete);
	
	$requete= new db_sql();
	$requete->q("SET NAMES 'utf8'");
	$requete->q($req);
}

function RSS_Tags($item,$size, $type, $origine) {

	$y = array();
	if($type==2){
		$y["dateCommande"] = getItemValue($item,"dateCommande");
		$y["dateValidation"] = getItemValue($item,"dateValidation");
		$y["dateExpedition"] = getItemValue($item,"dateExpeditionPrevisionnelle");
		$y["clientemail"] = getItemValue($item,"clientemail");
		$y["montantTtc"] = getItemValue($item,"montantTtc");
		//$y["clientnom"] = getItemValue($item,"clientnom");
		//$y["clientprenom"] = getItemValue($item,"clientprenom");
		$y["clientnom"] = getItemValue($item,"shippingContactLastname");
		$y["clientprenom"] = getItemValue($item,"shippingContactFirstname");
		$y["entreprise"] = getItemValue($item,"shippingAddressName");
		$y["address1"] = getItemValue($item,"shippingAddress1");
		if(!$y["address1"]) $y["address1"] = getItemValue($item,"shippingAddress1Final");
		$y["address2"] = getItemValue($item,"shippingAddress2");
		if(!$y["address2"]) $y["address2"] = getItemValue($item,"shippingAddress2Final");
		$y["address3"] = getItemValue($item,"shippingAddress3");
		if(!$y["address3"]) $y["address3"] = getItemValue($item,"shippingAddress3Final");
		$y["address4"] = getItemValue($item,"shippingAddress4");
		if(!$y["address4"]) $y["address4"] = getItemValue($item,"shippingAddress4Final");
		$y["postalcode"] = getItemValue($item,"shippingPostalCode");
		$y["city"] = getItemValue($item,"shippingCity");
		if(!$y["city"]) $y["city"] = getItemValue($item,"shippingCityFinal");
		$y["countrycode"] = getItemValue($item,"shippingCountryCode");
		$y["origine"] = $origine;		
		
		$indeXyss = $item->getElementsByTagName("IndeXysCommerceLigneVente");
		$y["listeArticle"]= array();
		$nbproduit = 1;
		foreach($indeXyss as $indeXys) {
			$codeArticle = getItemValue($indeXys,"codeArticle");
			$produit="";
			if($codeArticle!=""){
				$y["listeCodeArticle"][]=$codeArticle;
				$quantite = getItemValue($indeXys,"quantite");
				$prixventettc = getItemValue($indeXys,"prixVenteTtc");
				$additionalFields = $indeXys->getElementsByTagName("additionalFields");
				$tab = array();
				foreach($additionalFields as $additionalField) {
					$IndeXysXMLAdditionalFields = $additionalField->getElementsByTagName("IndeXysXMLAdditionalFields");
					$tabIndeXysXMLAdditionalFields   = array();
					foreach($IndeXysXMLAdditionalFields as $IndeXysXMLAdditionalField) {
					   $liste = $IndeXysXMLAdditionalField->getElementsByTagName("additionalField");
					   foreach($liste as $additional) {
						  if (getItemValue($additional,"key") == "produit")
								$produit = getItemValue($additional,"value");
						  $tab[utf8_decode(getItemValue($additional,"key"))] = utf8_decode(getItemValue($additional,"value"));
						}
					}
				}
				$tab["prixventettc"] 	= $prixventettc;
				$tab["quantite"] 		= $quantite;
				$tab["article"] 		= $codeArticle;
				//$produit = $tab["produit"];
				//print_r($tabadditionalFields);
				if($produit=="" and (strtoupper(substr($codeArticle,0,6)) == "EMBRAS")) {
					$produit="Embrases"; // Les embrasses sont des produits différents dans le site mais le même dans NSI
				}
				if($produit!="") {
					 $y["listeArticle"][$produit."sepnomproduit".$nbproduit] = $tab;
						$nbproduit++;
				}
			}
		}
		if(isUnEchantillon($y)) import_adress($y);
	} else if($type==1) {
		$y["title"] = getItemValue($item,"title");
		$y["link"] = getItemValue($item,"link");
	}
	return $y;
}

function retire(&$fichier, $balise) {
	while ($place_deb = strpos($fichier,$balise)) {
		$place_fin = strpos($fichier,">",$place_deb+strlen($balise))+1;
		$fichier = substr($fichier,0,$place_deb).substr($fichier,$place_fin);
	}	
}

function getContent($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$content = curl_exec($ch);
	curl_close($ch);
	return ($content);
}	


/*------------------------------------------------------------------------
  Si $type =1, on récupère le titre de la commande 
  Si $type =2, on récupère les détails de la commande + détail du produit
--------------------------------------------------------------------------*/

function RSS_Links($url, $type, $origine) {
	$size = 15;
	$RSS_Content = array();
	$doc  = new DOMDocument();
	
	$fichier = GetContent($url);
	//$fichier = file_get_contents($url);
	//echo ord(substr($fichier,0,1));
	//echo ord(substr($fichier,1,1));
	//exit;
	$fichier = str_replace (array("<br/>","</br>","<br/r>","<br>","<strong>","</strong>"),"",$fichier);

	//while (substr($fichier,0,1)!="<") {
	//	$fichier = substr($fichier,1);
	//}
	
	retire($fichier, "<div");
	retire($fichier, "<span");
	retire($fichier, "</div");
	retire($fichier, "</span");
	//echo "<pre>";
	//echo $fichier;
	//echo "</pre>";

	$xml = simplexml_load_string($fichier);
	if(!$xml) return "";
	$xml = $xml->asXML();
    $doc->loadXML($xml);
	if($type==1){
    	$channels = $doc->getElementsByTagName("channel");
	    $i = 1;
	    $nombre_produit =0;
	    $j = 0;
	    foreach($channels as $channel) {
		   $items = $channel->getElementsByTagName("item");
		   foreach($items as $item) {
				$title = getItemValue($item,"title");
				$y = RSS_Tags($item,$size, $type, $origine);
				array_push($RSS_Content, $y);
	       }
	    }
	} else if($type==2){
    	$items = $doc->getElementsByTagName("item");
        foreach($items as $item) {
	       $y = RSS_Tags($item,$size, $type, $origine);	
	       array_push($RSS_Content, $y);
        }
	}
	return $RSS_Content ;
}





?>
