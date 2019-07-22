<?php
require_once("po_imprimer_include.php");

if ($_GET[id_po]) {
   po_imprimer($_GET[id_po],false) ;
}
?>