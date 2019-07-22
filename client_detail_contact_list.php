<?
include("ress/entete.php");
?>
<body class="application">

<?
include 'client_detail_menugenerique.php'; echo get_menu(1);
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
$requete="
   SELECT
      contact.Id_contact,
      contact.Id_civilite,
      contact.Mobile,
      contact.Mail,
      contact.Id_civilite,
      civilite.Lb_civilite,
      contact.Id_fonction,
      fonction.Lb_fonction,
      contact.Nom,
      contact.Prenom,
      contact.Telephone
   FROM
      contact
   left outer join civilite on (civilite.Id_civilite = contact.Id_civilite)
   left outer join  fonction on (fonction.Id_fonction = contact.Id_fonction)
   WHERE
      contact.Id_client='$Id_client' and contact.Fg_supp is null
   ORDER BY 
      -contact.Id_contact ";
        /*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
?>
   <table class='resultat'>
      <tr>
         <td class='resultat_tittle'>Civilité</td>
         <td class='resultat_tittle'>Nom</td>
         <td class='resultat_tittle'>Prénom</td>
         <td class='resultat_tittle'>Fonction</td>
         <td class='resultat_tittle'>Téléphone</td>
         <td class='resultat_tittle'>Mobile</td>
         <td class='resultat_tittle'>Mail</td>
      </tr>
<?
while($resultat->n()){
?>
      <tr>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("lb_civilite"),0,4) ?></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><a class="resultat" href="contact_detail.php?Id_contact=<? echo $resultat->f('id_contact') ?>" target="droite"><? echo substr($resultat->f("nom"),0,30) ?></a></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("prenom"),0,30) ?></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("lb_fonction"),0,30) ?></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("telephone"),0,20) ?></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("mobile"),0,20) ?></td>
			<td class = "resultat_list" bgcolor = "<? echo alternat($z) ?>"><? echo substr($resultat->f("mail"),0,30) ?></td>
      </tr>
<?
   $z++;
}
?>
      <tr>
         <td class='resultat_footer' colspan='7'>Cliquer sur le Nom pour ouvrir un contact</td>
      </tr>
   </table>
<?
include ("ress/enpied.php");
?>
