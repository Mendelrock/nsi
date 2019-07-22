<?
require_once ("ress/var_session.php");
require_once("ress/fpdf/fpdf.php");
require_once("c_o_dataset.php");
require_once ("ress/mail.php");
echo mail_smtp("dethoor@gmail.com", "Mail de test2", "Mail de test2", ".\mail_test.php", "smail_test.php");
echo "<BR>Fin du traitement";
?>