<?
if (strpos($_SERVER["SERVER_NAME"],"stores-et-rideaux.com")) {
?>

   <tr>
      <td class="menu_haut" valign = bottom>
        <img src = "client/logo.jpg" width="140"><br>
      </td>
   </tr>
   <tr>
      <td class="menu_haut" valign = bottom>
        <B><I>Outil de gestion</I><B>
      </td>
   </tr>
<?
} else {
?>   
   <tr>
      <td class="menu_haut" valign = bottom>
        <img src = "client/client.jpg" width="100"><br>
      </td>
   </tr>
   <tr>
      <td class="menu_haut" valign = bottom>
        <B><I>BDM Force de Vente</I><B>
      </td>
   </tr>
<?
}