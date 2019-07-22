<?
/*-----------------------------------------------------------------------------------------------------
 Pour les champs
 - largeur désigne la largeur de la colonne qui contient le champ lors de l'impression en PDF
 - rowspan : lors de l'impression en PDF (ou affichage), la colonne qui contient le champ se comporte comme un <td> qui a un rowspan dans <table> en HTML
 - colspan : lors de l'impression en PDF (ou affichage), la colonne qui contient le champ se comportRIe  comme un <td> qui a un colspan dans <table> en HTML
 - startligne indique qu'on a une nouvelle ligne lors de l'impression en PDF (ou affichage)
 - fonctioninit désigne la formule qui paramètre un champ d'un dataset de produit
 - href indique l'image en infobulle
 - ispersisted indique si le champ est à enregistrer dans la base
 - notapply indique que la règle n'est pas applicable sur le champ
--------------------------------------------------------------------------------------------------------*/

/*
  Pour une raison d'optimisation, on charge en une seule fois les requetes avec des jointures 
*/
$groupelookup[affaire][requete] = '
                select 
                    utilisateur.Nom,commentaire,Lb_statut,Raison_sociale,Id_transac,Num_bcc,concat(civilite.Lb_civilite,\' \', IFNULL(contact.Prenom,\'\'),\' \', (IFNULL(contact.Nom,\'\'))) as Interlocuteur,
                    contact.Telephone,contact.Mobile,contact.Fax,contact.Mail
                from 
                    affaire,utilisateur,statut,client, contact LEFT OUTER join civilite on (civilite.Id_civilite = contact.Id_civilite)
                where 
                    utilisateur.Id_utilisateur=affaire.Id_utilisateur and statut.Id_statut=affaire.Id_statut and affaire.Id_client=client.Id_client and
                    contact.Id_contact = affaire.Id_contact and Id_affaire 
                    ';
$groupelookup[affaire][id_champ] = 'affaire';

$groupelookup[ofclient][requete] = '
				select 
					Raison_sociale,
					Nom 
				from 
					doc, 
					zdataset_ofentete dt,
					zdataset_fdcentete dt1,
					doc doc1,
					affaire,
					client,
					utilisateur 
				where 
					dt.id_dataset = doc.id_dataset_entete and 
					dt1.id_dataset = doc.id_dataset_entete and 
					doc1.id_doc = dt.fdc and 
					affaire.Id_client=client.Id_client and  
					affaire.id_affaire = dt1.affaire and 
					utilisateur.Id_utilisateur=affaire.Id_utilisateur and 
					doc.id_doc ';
$groupelookup[ofclient][id_champ] = 'ofclient';

$groupelookup[statut_commande][requete] = 'SELECT Id_statut_po,Lb_statut_po FROM po_statut ORDER BY Id_statut_po';
$groupelookup[statut_commande][id_champ] = 'statut_commande';

/*------------------------------------------------------------------
 Pour le type lookup 
 - champstocke est le champ passé en paramètre
 - tablecible désigne la table dans laquelle est stocké champstocke
 - champscible est le champ à afficher
--------------------------------------------------------------------*/
/*-----------------------------------------------------------------------
 $regles détermine le type de filtre à appliquer à un dataset 
 - table désigne la table dans laquelle on fait la requête (filtre) 
 - field désigne la liste des champs sur lesquels on applique le filtre
 et ces champs sont interdépendants.
-------------------------------------------------------------------------*/

/*-----------------------------------------------------------------------
 - fieldrequired désigne la liste des champs obligatoires pour une valeur
  du champ statut. Il a pour valeur ALL quand tous les champs sont obligatoire.
  Il comporte un | pour désigner un champ de quel type de dataset (entete de fdc, entete de produit ou ligne de produit) 
 - ordre désigne l'ordre de traitement de la feuille de côte
 - checkvalidate vérifie un dataset lors de l'enregistrement
 - checkload controle le champ statut lors du chargement.
  Le statut permet-il de modifier la commande (en fonction du droit) ?	
 - defaultvalue est la valeur par défaut du champ
-------------------------------------------------------------------------*/

// $regles[filtertringle][table]  ='codeconfigurationtringle';

function charge_doc ($type_doc) {
	if ($type_doc){
		if (!is_array($GLOBALS[parms][type_docs][$type_doc])) {
			require("./param_1_docs/".$type_doc.".php");
			if (is_array($type_docs)) {
				if (is_array ($GLOBALS["parms"]["type_docs"])) {
					$GLOBALS["parms"]["type_docs"] = array_merge($GLOBALS["parms"]["type_docs"],  $type_docs);
				} else {
					$GLOBALS["parms"]["type_docs"] = $type_docs;
				}
			}
			if (is_array($datasets)) {
				if (is_array ($GLOBALS[parms]["dataset"])) {
					$GLOBALS[parms]["datasets"][parms] = array_merge($GLOBALS["parms"]["datasets"], $datasets);
				} else {
					$GLOBALS[parms]["datasets"] = $datasets;
				}
			}
			if (is_array($champs)) {
				if (is_array ($GLOBALS[parms]["champs"])) {
					$GLOBALS[parms]["champs"] = array_merge($GLOBALS[parms]["champs"], $champs);
				} else {
					$GLOBALS[parms]["champs"] = $champs;
				}
			}		
		}
	}	
}

function charge_dataset ($dataset) {
   if (!is_array($GLOBALS[parms][datasets][$dataset])) {
		require("./param_3_datasets/".$dataset.".php");
		if (is_array($datasets)) {
			if (is_array ($GLOBALS[parms]["dataset"])) {
				$GLOBALS[parms]["datasets"][parms] = array_merge($GLOBALS[parms]["datasets"], $datasets);
			} else {
				$GLOBALS[parms]["datasets"] = $datasets;
			}
		}
		if (is_array($champs)) {
			if (is_array ($GLOBALS[parms]["champs"])) {
				$GLOBALS[parms]["champs"] = array_merge($GLOBALS[parms]["champs"], $champs);
			} else {
				$GLOBALS[parms]["champs"] = $champs;
			}
		}		
   }
   return $GLOBALS[parms]["datasets"][$dataset];
}

function charge_champ ($champ) {
   if (!is_array($GLOBALS[parms]["champs"][$champ])) {
		require("./param_4_champs/".$champ.".php");
		if (is_array($champs)) {
			if (is_array ($GLOBALS[parms]["champs"])) {
				$GLOBALS[parms]["champs"] = array_merge($GLOBALS[parms]["champs"], $champs);
			} else {
				$GLOBALS[parms]["champs"] = $champs;
			}
		}
   }
   return $GLOBALS[parms]["champs"][$champ];
}

$parms[groupelookup] = $groupelookup;
$parms[datasets] = $datasets;
$parms[toiles] = $toiles;
$parms[produits] = $produits;
//$parms[regles] = $regles;
$parms[fonctioninit] = $fonctioninit;
$parms[task] = array();

?>