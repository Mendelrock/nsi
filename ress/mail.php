<?php
require_once "ress/PHPMailer/PHPMailerAutoload.php";
require_once "ress/db_mysql.php";

function mail_smtp($adresse_mail, $subject, $message, $pdfdoc, $filename) {

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = "smtp.odiso.net";
	//$mail->Host = "in.mailjet.com";
	
	$mail->Port = 25;
	
	$mail->SMTPAuth = true;
	
	$mail->Username = "a.flamand@sodiclair.com";
	//$mail->Username = "7ffd92e6e307524d7214521c518f632b";
	
	$mail->Password = "huuK9Aiw";
	//$mail->Password = "64337826b147e1f7a0087e858c37c01d";
	
	$mail->setFrom('a.flamand@sodiclair.com', 'Annabel Flamand');
	$mail->addReplyTo('a.flamand@sodiclair.com', 'Annabel Flamand');


	$req = new db_sql();

   if ($req->Database != "bdm_ageclair") { 
		$adresse_mail = "sberretti@stores-et-rideaux.com";
		$subject = "TEST : ".$subject;
	}

	$mail->addAddress($adresse_mail);
	$mail->addBCC('dethoor@gmail.com');
	if (substr($subject, 0, strlen('Commande Sodiclair SF n° ')) == 'Commande Sodiclair SF n° ') {
		$mail->addBCC('d.jullich@sodiclair.com');
		$mail->addBCC('aberkani@ageda.fr');
	}
	if ($_SESSION[mail_utilisateur]) {
		$mail->addBCC($_SESSION[mail_utilisateur]);
	}

	$mail->Subject = $subject;
	$mail->msgHTML($message);
	$mail->AltBody = 'This is a plain-text message body';
	if ($pdfdoc) {
		$mail->addAttachment($pdfdoc, $filename);
	}
	//send the message, check for errors
	if (!$mail->send()) {
		 echo "Erreur d'envoi de mail : " . $mail->ErrorInfo;
	} 
}
?>
