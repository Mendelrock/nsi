<?


//$to      = 'ma@dethoor.fr';
$to      = 'dethoor@gmail.com';
$subject = '1';
$message = '1';
	$headers = 'From: marcalexandre.dethoor@orange.fr' . "\r\n" .
	'Reply-To: marcalexandre.dethoor@orange.fr' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);

 
?>
envoi fait