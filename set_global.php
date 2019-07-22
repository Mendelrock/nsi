<?php
require_once("ress/register_globals.php");
register_globals('gp');

/*-------- Variables de session -------*/
include ("ress/var_session.php");

/*-------- Include framework -------*/
include ("ress/db_mysql.php");
include ("ress/util.php");
if(isset($_POST[origine])){
  unset($_SESSION[incompatibilite]);	
  $GLOBALS[origine] = $_POST['origine']; 
}
?>