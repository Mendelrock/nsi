<?
if (strpos($_SERVER["SERVER_NAME"],"stores-et-rideaux.com")) {
	$titre = "<img src = \"logo.jpg\" width=\"700\">";
	$soustitre = "Outil de gestion des commandes";
} else {
	$titre = "SODICLAIR";	
	$soustitre = "BDM";
}

?>
<html>
<head>
<title>Document sans titre</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body class="application">
<Table width = 100% height = 100%>
   <tr>
      <td class="menu_haut" valign = "middle" align = "center">
         <SPAN style="font-size:40px; color:darkblue; font-weight:bold; font-family: verdana"><? echo $titre ?></SPAN>
      </td>
   </tr>
   <tr>
      <td class="menu_haut" valign = "middle" align = "center">
         <SPAN style="font-size:30px; color:darkblue; font-weight:bold;	font-family: verdana"><? echo $soustitre ?></SPAN>
      </td>
   </tr>
</Table>
</body>
</html>
