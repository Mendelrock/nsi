<?php

header('Content-Type: text/html; charset=utf-8');
require_once("ress/util.php");
require_once("ress/db_mysql.php");
require_once("ress/rsslib.php");
require_once("fonction.php");
require_once("c_o_dataset.php");
require_once("c_parm.php");

include("ress/var_session.php");

if (!$_SESSION[id_droit]["Menu.ImportSR"]) {
    die ("Vous n'avez pas accès à cet écran");
}

if ($_GET[debug]) { ?>
    <link rel="stylesheet" href="./client/style.css" type="text/css">
    <SCRIPT SRC="ress/util.js"></SCRIPT>
    <script type="text/javascript" src="ress/jquery.js"></script>
    <?
    echo "Mode debug<BR>";
}

$allquestions = array();

$listeproduits = getListe("param_2_produits");

$date = date("Y-m-d");
$newdate = strtotime('-3 month', strtotime($date));
$newdate = date('Y-m-d', $newdate);

$title = $_GET["title"];
if (req_sim("SELECT count(1) as compte from zdataset_fdcentete WHERE numcommande_fdc = '$title'", "compte") > 0) {
    echo utf8_encode("Commande déjà importée");
    exit();
}

$origine_import = $_GET["o"];
$link = urldecode($_GET["link"]);
$type_doc = "fdc";
$liste_dataset = array();
charge_doc("fdc");
$type_dataset_entete = $parms[type_docs][$type_doc][type_dataset_entete];
$dataset = new dataset($type_dataset_entete);

$dataset->ochamp["affaire"]->set($origine_import);
$dataset->ochamp["statut"]->set("A produire");
$RSS_Content_ = RSS_Links($link, 2, $origine_import);

if ($origine_import == '2') {
    $dataset->ochamp["agence"]->set("Prosolair");
}
if ($origine_import == '1') {
    $dataset->ochamp["agence"]->set("Stores & Rideaux");
}
if ($origine_import == '4') {
    $dataset->ochamp["agence"]->set("Tende a Tende");
}

if (!is_array($RSS_Content_)) {
    echo utf8_encode("non importee pour cause de format");
    exit();
}

ob_start($commande["listeArticle"]);
foreach ($RSS_Content_ as $commande) {
	$dbres = new db_sql();
	$dbres->begin();
	$dateCommande = $commande["dateCommande"];
	$dateExpedition = $commande["dateExpedition"];
	$dataset->ochamp["nomclient_fdc"]->set(utf8_decode($commande["clientnom"] . " " . $commande["clientprenom"]));
	$dataset->ochamp["numcommande_fdc"]->set($title);
	$dataset->ochamp["date_creation"]->set(aujourdhui());
	$dataset->ochamp["date_import"]->set(aujourdhui());
	$dataset->ochamp["date_vld_fdc"]->set(aujourdhui());
	$dataset->ochamp["date_cde"]->set(formatterDate($dateCommande));
	$dataset->ochamp["date_exp"]->set(formatterDate($dateExpedition));
	$dataset->ochamp["origine"]->set($origine_import);
	$dataset->ochamp["code_departement"]->set($commande['postalcode']);

	$dataset->ochamp["prix_ht"]->set($commande["montantTtc"]);
	$liste_dataset[$type_dataset_entete] = $dataset;
	$isdataset_entete_inserted = false;
	$nbproduit = 0;
	$erreur = "";
	foreach ($commande["listeArticle"] as $nomproduit => $details) {
        $nbproduit++;
        $val = explode('sepnomproduit', $nomproduit);
        $nomproduit = utf8_decode($val[0]);
        $produit_modele = "";
        if (StartsWith($nomproduit, "Rideaux Enfants")) {
            $tab = explode('-', $nomproduit);
            $nomproduit = utf8_decode(trim($tab[0]));
            $produit_modele = utf8_decode(trim($tab[1]));
        }
        $message_erreur_produit = "";
        $nomproduit = trim($nomproduit);
        $produits_nsi = getProduit($listeproduits, $nomproduit, $title);
        if (!$produits_nsi) {
            $erreur .= "Le produit " . $nomproduit . " n'a pas d'équivalent dans NSI<BR>";
            $questions = "nomproduit=$nomproduit&commande=$title";
            if (!in_array($questions, $allquestions)) {
                $allquestions[] = $questions;
                $id_question++;
                $erreur .= "<div id='$id_question'>";
                $erreur .= "<FORM method=post name=formulaire action=''>";
                $erreur .= "Valeurs possibles :
				<select name='correspondance'>";
                foreach ($listeproduits as $key => $val) $erreur .= "<option value ='val=$val&nomproduit=$nomproduit&commande=$title&ope=8'>$val</option>";
                $erreur .= "</select><input class='requeteur_button' type='button' OnClick='javascript:call(\"updateStockArticle.php\",correspondance.value,$id_question);' value='Valider'></FORM></div><BR>";
            } else {
                $erreur .= 'Question déjà posée<BR>';
            }
        } else if ($produits_nsi == "ambigous") {
            $erreur .= "Le produit " . $nomproduit . " a plus d'un équivalent NSI<BR>";
        } else {
            require("param_2_produits/" . $produits_nsi . ".php");
            $type_dataset_produit = $produits[$produits_nsi][type_dataset_ligne];
            $dataset_produit = new dataset($type_dataset_produit);
            $liste_propriete_site = $details;

            // passe en revue les champs pour les remplir
            foreach ($dataset_produit->champs as $id_champ => $champ) {
                $proprietes_site = getPropriete($liste_propriete_site, $id_champ, $produits_nsi);
                $valeur_site = "";

                // Valeurs par défaut
                if (!$proprietes_site and ($dataset_produit->ochamp[$id_champ]->defaultvalue)) {
                    $valeur_site = $dataset_produit->ochamp[$id_champ]->defaultvalue;
                    // Gestion des erreurs
                } else if (!$proprietes_site) {
                    $valeur_site = "";
                    if ($dataset_produit->ochamp[$id_champ]->obligatoire) {
                        $erreur .= "Produit " . $nomproduit . "<BR>&nbsp;&nbsp;
									  La propriété " . $id_champ . " n'a pas d'équivalent sur le site<BR>";
                        $questions = "id_champ=$id_champ&produits_nsi=$produits_nsi&ope=7";
                        if (!in_array($questions, $allquestions)) {
                            $allquestions[] = $questions;
                            $id_question++;
                            $erreur .= "<div id='$id_question'>";
                            $erreur .= "<FORM method=post name=formulaire action=''>
									Valeurs possibles : <select name='correspondance'>";
                            foreach ($liste_propriete_site as $val => $key) $erreur .= "<option value ='val=$val&id_champ=$id_champ&produits_nsi=$produits_nsi&ope=7'>$val</option>";
                            $erreur .= "</select><input class='requeteur_button' type='button' OnClick='javascript: call(\"updateStockArticle.php\",correspondance.value,$id_question);' value='Valider'></FORM></div><BR>";
                        } else {
                            $erreur .= 'Question déjà posée<BR>';
                        }
                    }
                } else if ($proprietes_site == "ambigous") {
                    $valeur_site = "";
                    $erreur .= "Produit " . $nomproduit . "<BR>&nbsp;&nbsp;
						       La propriété " . $id_champ . " a plus d'un équivalent sur le site<BR>";

                    // Cas general
                } else {
                    $valeur_site = trim($liste_propriete_site[$proprietes_site]);
                }
                if ($valeur_site) {
                    $valeur_nsi = "";
                    $champ = new champ($id_champ);
                    switch ($champ->id_type_champ) {
                        case "ouvert":
                            $valeur_nsi = $valeur_site;
                            if (strpos($valeur_nsi, '|') !== false) {
                                $val = array();
                                $val = explode('|', $valeur_nsi);
                                $valeur_nsi = $val[0];
                            }
                            if ($dataset_produit->ochamp[$id_champ]->formule_import) {
                                $valeur_nsi = floatval($valeur_nsi) * 10;
                            }
                            $dataset_produit->ochamp[$id_champ]->set($valeur_nsi);
                            break;

                        case "toile":
                            //Gestion des rideaux bicolores
                            if ($id_champ == "toile_rideau_bico_tete" || $id_champ == "toile_rideau_bico_corps") {
                                if (strpos($valeur_site, '|') !== false) {
                                    $val = array();
                                    $val = explode('|', $valeur_site);
                                    if ($id_champ == "toile_rideau_bico_tete")
                                        $valeur_site = trim($val[0]);
                                    else if ($id_champ == "toile_rideau_bico_corps")
                                        $valeur_site = trim($val[1]);
                                }
                            }
                            // Cas simplifie d'une toile avec nouvelle methode d'appairement
                            if ($valeur_site == "|" or substr($valeur_site, 0, 7) == "Pas de ") { // Pour les doublures
                                $valeur_nsi = "|";
                                $dataset_produit->ochamp[$id_champ]->set($valeur_nsi);
                                break;
                            }

                            $champ->tablecible = 'select id_toile, lb_toile_sr from toile';
                            $champ->champstocke = 'id_toile';
                            $champ->champscible = 'lb_toile_sr';

                        case "lov":
                           //Rajoute dans les champs d'une lov les parametres d'une table
                           /*
                           if ($dataset_produit->filter) {
                              $field = $GLOBALS[parms][regles][$dataset_produit->filter][field] ;
                              if(in_array($id_champ, $field)){
                                 if(!$champ->notapply){
                                        $regle = new regle($dataset_produit->filter,$filtre,$id_champ);
                                        $resultat = $regle->construire_regle();
                                        $champ->valeurs = array();
                                        while($resultat->n()){
                                            $champ->valeurs[$resultat->f($id_champ)] = $resultat->f($id_champ);
                                        }
                                    }
                              }
                           }
                           */

                           //idem pour une table cible

							if ($champ->tablecible) {
								$champ->valeurs = array();
								if ($champ->champssite) { // champssite est utilisé pour que ce ne soit pas la valeur affichée qui serve à faire le matching avec le site
									$champ_table = $champ->champssite;
								} else {
									$champ_table = $champ->champscible;
								}
								$req = new db_sql($champ->tablecible);
								while ($req->n()) {
									$champ->valeurs[trim($req->f($champ->champstocke))] = trim($req->f($champ_table));
								}
							}

                            if (strpos($valeur_site, '|') !== false) {
                                $val = array();
                                $val = explode('|', $valeur_site);
                                $valeur_site = trim($val[0]);
								$valeur_site_suite = trim($val[1]);
                            } else {
								$valeur_site_suite = "";
							}

                            if ($dataset_produit->ochamp[$id_champ]->formule_import) {
                                $valeur_site = floatval($valeur_site) * 10;
                            }
							
							if ($id_champ == "embouts_tringle") {
								if ($valeur_site == "CHEMIN DE FER - BLANC 24 x 16 TIRAGE DIRECT - BLANC") $valeur_site = 'Embout BLANC 24 x 16 TIRAGE DIRECT';
							}
							
                            //echo $proprietes_site;
							if ($id_champ == "gammetringle") {
								$lo_gamme['Boule - Blanc - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - BLANC ARGENT';
								$lo_gamme['Boule - Bronze - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - BRONZE';
								$lo_gamme['Boule - Etain - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - ETAIN';
								$lo_gamme['Boule - Nickel Mat - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - NICKEL MAT';
								$lo_gamme['Boule - Noir - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - NOIR ARGENT';
								$lo_gamme['Boule - Rouille - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - ROUILLE';
								$lo_gamme['Capuchon - Blanc - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - BLANC ARGENT';
								$lo_gamme['Capuchon - Bronze - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - BRONZE';
								$lo_gamme['Capuchon - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Capuchon - Etain - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - ETAIN';
								$lo_gamme['Capuchon - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Capuchon - Nickel Mat - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - NICKEL MAT';
								$lo_gamme['Capuchon - Nickel Mat - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - NICKEL MAT';
								$lo_gamme['Capuchon - Noir - Argent - FER FORGE TOSCANE - 19m'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - NOIR ARGENT';
								$lo_gamme['Capuchon - Rouille - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - ROUILLE';
								$lo_gamme['Chapeau - Bronze - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - BRONZE';
								$lo_gamme['Chapeau - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Chapeau - Etain - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - ETAIN';
								$lo_gamme['Chapeau - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Chapeau - Nickel Mat - ALLIANCE - 28mm'] = 'DECORATIVES - ALLIANCE - 28mm - NICKEL MAT';
								$lo_gamme['Chapeau - Nickel Mat - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - NICKEL MAT';
								$lo_gamme['Classique - LAITON - 28mm'] = 'DECORATIVES - LAITON - 28mm - DORE';
								$lo_gamme['Contemporain - LAITON - 28mm'] = 'DECORATIVES - LAITON - 28mm - DORE';
								$lo_gamme['Crosse - Blanc - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - BLANC ARGENT';
								$lo_gamme['Crosse - Noir - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - NOIR ARGENT';
								$lo_gamme['Crosse - Rouille - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - ROUILLE';
								$lo_gamme['Double Feuilles - Blanc - Argent - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - BLANC ARGENT';
								$lo_gamme['Double Feuilles - Noir - Argent - FER FORGE TOSCAN'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - NOIR ARGENT';
								$lo_gamme['Double Feuilles - Rouille - FER FORGE TOSCANE - 19mm'] = 'DECORATIVES - FER FORGE TOSCANE - 19mm - ROUILLE';
								$lo_gamme['Flamme - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Flamme - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Flamme - Nickel Mat - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - NICKEL MAT';
								$lo_gamme['Pommeau - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Pommeau - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Pommeau - Nickel Mat - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - NICKEL MAT';
								$lo_gamme['Regency - LAITON - 28mm'] = 'DECORATIVES - LAITON - 28mm - DORE';
								$lo_gamme['Stic - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Stic - Bronze - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - BRONZE';
								$lo_gamme['Stic - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Stic - Etain - NEW CORTINA - 20mm'] = 'CORTINA 20mm ETAIN';
								$lo_gamme['Stic - Nickel Mat - NEW CORTINA - 20mm'] = 'DECORATIVES - NEW CORTINA - 20mm - NICKEL MAT';
								$lo_gamme['Embout BLANC 24 x 16 TIRAGE DIRECT'] = 'CHEMIN DE FER - BLANC 24 x 16 TIRAGE DIRECT - BLANC';
								$lo_gamme['CHEMIN DE FER - BLANC 24 x 16 TIRAGE DIRECT - BLANC'] = 'CHEMIN DE FER - BLANC 24 x 16 TIRAGE DIRECT - BLANC';
								$lo_gamme['Embout BLANC 24 x 16 CORDON'] = 'CHEMIN DE FER - BLANC 24 x 16 CORDON - BLANC';
								if ($valeur_site_suite) {
									$valeur_site = $valeur_site_suite;
								} else if ($lo_gamme[$valeur_site]) {
									$valeur_site = $lo_gamme[$valeur_site];
								}
							}

							$valeur_nsi = getValeur($champ->valeurs, $valeur_site, $proprietes_site, $id_champ, $title);
							
							if ($valeur_nsi == "ambigous") {
                                $erreur .= "Produit " . $nomproduit . "<BR>&nbsp;&nbsp;
											Propriete " . $id_champ . "<BR>&nbsp;&nbsp;&nbsp;&nbsp;
											La valeur " . $valeur_site . " a plus d'un équivalent dans NSI<BR>";
                            } else if ($valeur_nsi == "") {
                                $questions = "'site_valeur=$valeur_site&site_propriete=$proprietes_site&nsi_produit=$produits_nsi&nsi_propriete=$id_champ&ope=5'";
                                if (!in_array($questions, $allquestions)) {
                                    $erreur .= "Produit " . $nomproduit . "<BR>";
                                    $allquestions[] = $questions;
                                    $id_question++;
                                    $erreur .= "<div id='$id_question'>";
                                    $erreur .= "<FORM method=post name=formulaire action=''>
									&nbsp;&nbsp;Propriété '.$id_champ.'<br>&nbsp;&nbsp;&nbsp;&nbsp;
									La valeur '" . $valeur_site . "' n'a pas d'équivalent dans NSI - Valeurs possibles :
									<select name='correspondance'>";
                                    foreach ($champ->valeurs as $key => $val)
                                        $erreur .= "<option value ='site_valeur=$valeur_site&site_propriete=$proprietes_site&nsi_propriete=$id_champ&nsi_valeur=$val&commande=$title&ope=5'>$val</option>";
                                    $erreur .= "</select><input class='requeteur_button' type='button' OnClick='javascript: call(\"updateStockArticle.php\",correspondance.value,$id_question);' value='Valider'></FORM></div><BR>";
                                } else {
                                    $erreur .= "Produit " . $nomproduit . "<BR>
									&nbsp;&nbsp;Propriété '.$id_champ.'<br>&nbsp;&nbsp;&nbsp;&nbsp;
									La valeur '" . $valeur_site . "' n'a pas d'equivalent dans NSI ... Question déja posee<BR>";
                                }
                            } else {
								$dataset_produit->ochamp[$id_champ]->set(array_search($valeur_nsi, $champ->valeurs));
                            }
                            break;

                    }
                }
            }
            $liste_dataset[$produits_nsi . "sepnomproduit" . $nbproduit] = $dataset_produit;
        }
    }
    if ($erreur) {
        $message = substr($erreur, 0, -4);
    } else {
        //S'il n'y a aucune erreur, faire l'import
        foreach ($liste_dataset as $key => $dataset_to_save) {
            $dataset_to_save->store($dbres);
            if ($dataset_to_save->type_dataset == $type_dataset_entete) {
                $dbres->q("insert into doc(type_doc,id_dataset_entete) values ('$type_doc', $dataset_to_save->id_dataset)");
                $id_doc = db_sql::last_id ();
					 maj_num_fdc_import($id_doc, $dataset_to_save, $dbres);
            } else if (StartsWith($dataset_to_save->type_dataset, "fdcproduit")) {
                $val = explode('sepnomproduit', $key);
                $key = utf8_decode($val[0]);
                $dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc, '$key', $dataset_to_save->id_dataset)");
            }
        }
        $message = generer_of($id_doc, $dbres);
    }

    // Envoi du bilan en AJAX
    if (!$message) {
        $dbres->commit();
        echo "<font color = green> importee</font>";
    } else if (substr($message, 19, 27) == "Bande de moins de 10cm pour") {
        $dbres->rollback();
        echo $message;
    } else {
        $dbres->rollback();
        echo "<font color = red> " . $message . "</font><BR><a href=\"javascript:relance('" . $title . "',0);\">Re-essayer</a>";
    }
}

function  simplifie($txt)
{
    $txt = preg_replace('/\s+/', '', $txt);
    $txt = str_replace(" ", "", $txt);
    $txt = str_replace(" ", "", $txt);
    return $txt;
}

function getValeur($listevaleur, $valeursite, $site_propriete, $nsi_propriete, $title) {

    $valeursite_temp = simplifie($valeursite);

    //CAS : rechercher parmi toutes les valeurs de NSI
    foreach ($listevaleur as $valeur) {
        if ($valeursite_temp == simplifie($valeur)) {
            return ($valeur);
        }
    }

    $reponses = array();

    //CAS : recherche dans table de correspondance
    $req = "
		SELECT
			*
		FROM
			correspondance_valeur_temp
		WHERE 
			commande = '$title' and
			site_valeur = '$valeursite' and 
			site_propriete = '$site_propriete' and
			nsi_propriete = '$nsi_propriete'";

    $resultat = new db_sql($req);
    $valnsi = "";
    while ($resultat->n()) {
        if (!$nsi_produit or ($nsi_produit == $resultat->f('nsi_produit'))) {
            if (simplifie($resultat->f('site_valeur')) == $valeursite_temp) {
                return ($resultat->f('nsi_valeur'));
            }
        }
    }

    if (count($reponses) == 1)
        return (array_pop($reponses));
    if (count($reponses) == 0)
        return false;
    if (count($reponses) > 1) {
        return "ambigous";
    }
}

function getPropriete($liste_propriete_site, $id_champ, $nsi_produit) {
	//recherche en direct
    if ($trouve = array_key_tolower_exists($id_champ, $liste_propriete_site)) {
        //new db_sql("delete from correspondance_propriete where LOWER(nsi_propriete) = LOWER('$id_champ') AND nsi_produit='$nsi_produit'");
        return ($trouve);
    }
	if ($id_champ == "qte") {
		if ($trouve = array_key_tolower_exists("quantite", $liste_propriete_site)) {
			return $trouve;
		}
	}

    $reponses = array();
    //recherche dans table des correspondances
    $req = "
		SELECT
			*
		FROM
			correspondance_propriete
		WHERE 
			LOWER(nsi_propriete) = LOWER('$id_champ') AND 
			nsi_produit = '$nsi_produit'";
    $resultat = new db_sql($req);
    while ($resultat->n()) {
        $tab_valeurs = explode(";", $resultat->f('site_propriete'));
        foreach ($tab_valeurs as $i => $valeur) {
            if ($trouve = array_key_tolower_exists($valeur, $liste_propriete_site)) {
                $reponses[$valeur] = $trouve;
            }
        }
    }
    if (count($reponses) == 1)
        return (array_pop($reponses));
    if (count($reponses) == 0)
        return false;
    if (count($reponses) > 1) {
        return "ambigous";
    }
}

function getProduit($listeproduits, $produits, $title)
{

    //rechercher parmi tous les produits de NSI
    $reponses = array();

    if (in_array($produits, $listeproduits)) {
        $reponses[$produits] = $produits;
    }

    //rechercher dans la table de correspondance permanante
    $req = "
		SELECT
			*
		FROM
			correspondance_produit
		WHERE
			'$produits' LIKE site_produit";
    $resultat = new db_sql($req);
    while ($resultat->n()) {
        if (in_array($resultat->f('nsi_produit'), $listeproduits))
            $reponses[$resultat->f('nsi_produit')] = $resultat->f('nsi_produit');
    }

    //rechercher dans la table de correspondance temporaire
    $req = "
		SELECT
			*
		FROM
			correspondance_produit_temp
		WHERE 
			'$produits' LIKE site_produit and commande = '$title'";
    $resultat = new db_sql($req);
    while ($resultat->n()) {
        if (in_array($resultat->f('nsi_produit'), $listeproduits))
            $reponses[$resultat->f('nsi_produit')] = $resultat->f('nsi_produit');
    }

    if (count($reponses) == 1)
        return (array_pop($reponses));
    if (count($reponses) == 0)
        return false;
    if (count($reponses) > 1)
        return "ambigous";

}

echo utf8_encode(ob_get_clean());

?>