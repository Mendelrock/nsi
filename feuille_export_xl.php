<?php
include ("ress/var_session.php");
require_once('ress/fpdf/fpdf.php');
require_once('ress/fpdf_js.php');
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");


$champs = array (
    "id_doc" => array ( 
        "clee" => 1,
        "format" => "varchar",
        "libelle" => "Numero Feuille de cote"
    ),
    "Raison_sociale" => array ( 
        "clee" => 1,
        "format" => "varchar",
        "libelle" => "Raison sociale"
    ),
    "numcommande_fdc" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Numero de commande ou BCC"
    ),
    "Lb_statut" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Statut Affaire"
    ),
    "commentaire" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Nom Affaire"
    ),
    "statut" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Statut Feuille de Cotes"
    ),
    "commercial" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Commercial"
    )

);

$req="
	SELECT
		doc.id_doc,
		commentaire,
		dt.nomclient_fdc as Raison_sociale,
		Lb_statut,
		dt.statut as statut,
		dt.date_cde as date_cde,
		dt.date_creation as date_creation,
		dt.numcommande_fdc as numcommande_fdc,
		concat(u.nom,' ' ,u.prenom) as commercial
	FROM
		affaire,
		statut,
		zdataset_fdcentete dt,
		utilisateur u,
		contact,
		doc 
	WHERE ".trim($_SESSION[fdc_critere])."
		type_doc='fdc' AND
		dt.id_dataset = doc.id_dataset_entete AND
		affaire.id_affaire = dt.affaire AND
		statut.Id_statut=affaire.Id_statut AND
		affaire.id_contact = contact.id_contact AND
		u.id_utilisateur = affaire.id_utilisateur 
	ORDER BY
	   doc.id_doc DESC	
	LIMIT 0, ".($_SESSION[fdc_affichage]+2);

$req = new db_sql($req);
while($req->n()){
    $rec = array();
    foreach($champs as $champ=>$proprietes) {
        $valeur = $req->f($champ);
        $rec[$proprietes[libelle]]=$valeur;
    }
    $extparm[] = $rec;
}

if(!$extparm){
    $rec = array();
    foreach ($champs as $champ => $name){
        $rec[$name[libelle]]=" ";
    }
    $extparm[] = $rec;
}

$_SESSION['extparm'] = $extparm;
$tableau="extparm";
$nodecale=true;
include("./ress/xl.php");
?>