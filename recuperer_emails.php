
<html>
<head>
<title>NSI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>


<?php
$adresse='anakrys.pop@gmail.com';
$password='Heureux@5';

$server = '{imap.gmail.com:993/ssl}';

/* try to connect */
$inbox = imap_open($server,$adresse ,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'ALL');

/* if emails are returned, cycle through each... */
if($emails) {
	foreach($emails as $email) {
		echo nl2br(getMessageContent($inbox,$email));
	}	
} 

/* close the connection */
imap_close($inbox);



function getMessageContent($mbox,$id)
{

// Get content of text message.
$mid = $id;
$struct = imap_fetchstructure($mbox,$mid);

       $parts = $struct->parts;
       $i = 0;

       if (!$parts)
       {
            /* Simple message, only 1 piece */
         $attachment = array(); /* No attachments */
         $content = imap_body($mbox, $mid);
       }
       else
       {
            /* Complicated message, multiple parts */

         $endwhile = false;

         $stack = array(); /* Stack while parsing message */
         $content = "";    /* Content of message */
         $attachment = array(); /* Attachments */

         while (!$endwhile)
         {
           if (!$parts[$i])
           {
             if (count($stack) > 0)
             {
               $parts = $stack[count($stack)-1]["p"];
               $i    = $stack[count($stack)-1]["i"] + 1;
               array_pop($stack);
             }
             else
             {
               $endwhile = true;
             }
           }

           if (!$endwhile)
           {
             /* Create message part first (example '1.2.3') */
             $partstring = "";
             foreach ($stack as $s)
             {
               $partstring .= ($s["i"]+1) . ".";
             }
             $partstring .= ($i+1);

             if (strtoupper($parts[$i]->disposition) == "ATTACHMENT") { /* Attachment */
               $attachment[] = array("filename" => $parts[$i]->parameters[0]->value,
                                     "filedata" => imap_fetchbody($mbox, $mid, $partstring));
             }
             elseif (strtoupper($parts[$i]->subtype) == "PLAIN")
             { /* Message */
               $content .= imap_fetchbody($mbox, $mid, $partstring);
             }
           }

           if ($parts[$i]->parts)
           {
             $stack[] = array("p" => $parts, "i" => $i);
             $parts = $parts[$i]->parts;
             $i = 0;
           }
           else
           {
             $i++;
           }
         } /* while */
       } /* complicated message */

// Convert quoted-printable characters in text content

$Body_text = quoted_printable_decode($content);
return $Body_text;

}  
?>
</html>
