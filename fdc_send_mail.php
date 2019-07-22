<?php

require_once("c_o_dataset.php");
include ("ress/var_session.php");

if ($_GET['id_doc']) {
    $list_id_doc = array();
    $list_id_doc[] = $_GET['id_doc'];
    include_once 'doc_generer.php';
    $pdf = generer_doc($list_id_doc);

	 $filename = "feuilledecote_".$_GET['id_doc'].".pdf";
    $pdfdoc = $pdf->Output("", "S");
    $attachment = chunk_split(base64_encode($pdfdoc));

    $separator = md5(time());

    $eol = PHP_EOL;

    $adresse_mail = "claudine@sodiclair.com,sberretti@stores-et-rideaux.com,ma@dethoor.com";

    $to = $adresse_mail;
    $subject = 'Feuille de cotes N ' . $_GET['id_doc'];
    $message = "Veuillez trouver ci-joint la feuille de cotes n " . $_GET['id_doc'] . "
				<BR>
				<BR>SODICLAIR SAS";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . "\r\n";
    $headers .= "From: AGECLAIR <noreply@ageclair.com>" . "\r\n";

    $body = "--" . $separator . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $body .= $message . $eol;
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol . $eol;
    $body .= $attachment . $eol;
    $body .= "--" . $separator . "--";

     mail($to, $subject, $body, $headers);


}

?>
<script type="application/javascript" >
    setTimeout("window.close()",3000)
</script>























?>