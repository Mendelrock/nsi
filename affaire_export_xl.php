<?php
include ("ress/var_session.php");
require_once('ress/fpdf/fpdf.php');
require_once('ress/fpdf_js.php');
require_once("c_o_dataset.php");
require_once("ress/db_mysql.php");
require_once("c_parm.php");
require_once("ress/util.php");


// Critere de recherche
if($req = trim($_SESSION[affaire_requete])){
    $requete = $req;
}

$champs = array (
    "commentaire" => array (
        "clee" => 1,
        "format" => "varchar",
        "libelle" => "Nom affaire"
    ),
    "Id_transac" => array (
        "clee" => 1,
        "format" => "varchar",
        "libelle" => "Numero de devis"
    ),
    "raison_sociale" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Raison sociale"
    ),
    "nom" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Nom"
    ),
    "telephone" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Téléphone"
    ),
    "nom_consultant" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Commercial"
    ),
    "date_crea" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Date Création"
    ),
    "date_prev" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Date Prévision"
    ),
    "lb_statut" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Statut"
    ),
    "Prix" => array (
        "clee" => 0,
        "format" => "varchar",
        "libelle" => "Montant HT"
    )

);

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