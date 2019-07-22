<?
session_start();
//var_dump($datasets[fdcentete][origines_invisibles]);

require_once("ress/util.php");
require_once("c_o_dataset.php");
//require_once("set_global.php");

var_dump($datasets[fdcentete][champs][nomclient_fdc][origines_invisibles]='|1|2|3');

echo "</br></br></br>origine";
//var_dump(getOrigine($SR,$PS,$FDV));
//var_dump(test());

echo "Global Origine :";
var_dump($GLOBALS[origine]);
echo "</br> Champs Origines";
var_dump($champs[origines]);
echo "</br> test (test()) :";
var_dump($test);
echo "</br> Champs fdc non_html:";
var_dump($champs[fdc][non_html]);
echo "</br> PWD :";
var_dump($PWD);
echo "</br> Cookies :";
var_dump($_COOKIE);
echo "</br> _GET :";
var_dump($_GET);
echo "</br> _Session : <pre>";
var_dump($_SESSION);
echo "</pre>";
echo("</br></br></br>");
echo "</br> Gobals :";
echo "<pre>";
var_dump($GLOBALS);
echo "</pre>";
