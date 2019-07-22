<?php
include "ress/register_globals.php";

// Nécessaire car si on ajoute ce code dans le fichier d'où il est appelé, du fait que l'entête soit appelé avant, les headeurs seront déjà envoyés au moment où on arrive ici
// du coup le fichier excel ne s'ouvrira pas
if(isset($_GET['tableau'])) {
	$tableau = $_GET['tableau'];
	include 'ress/xl.php';
}