<?

/*-----------------PARAMETRES---------------------
/* Internes
/*  $ACTE
/*  $Id_droit
/*  $Id_profil
/*
/*------------------------------------------------*/

include("ress/entete.php");

?>
<body class="application">
<?

/**********************************************************
ACTE MANAGEMENT
***********************************************************/

if ($ACTE == 1) {
   new db_sql("
      delete from
         autorisation
      where
         id_droit = $Id_droit and
         id_profil = $Id_profil");
}

if ($ACTE == 2) {
   new db_sql("
      insert into autorisation (
         id_droit,
         id_profil)
      values (
         $Id_droit,
         $Id_profil)");
}

/**********************************************************
**********************************************************
**********************************************************
MAIN SCREEN
***********************************************************
***********************************************************
***********************************************************/


/**********************************************************
HEAD
***********************************************************/
?>
<table class="menu_haut_resultat">
   <tr>
      <td class="interne_actif">Profils</td>
      <td></td>
   </tr>
</table>

         <table class="menu_haut_resultat">
            <tr>
               <td class="resultat_tittle"><? echo $l_droit_droit?></td>

<?
$g_req = new db_sql("
      select
         profil.lb_profil
      from
         profil
      order by
         id_profil");
while ($g_req->n()) {
?>
               <td class="resultat_tittle"><? echo $g_req->f('lb_profil'); ?></td>
<?
}
?>
            </tr>
<?
$g_req = new db_sql("
      select
         pc.id_droit,
         pc.lb_droit,
         pc.id_profil,
         autorisation.id_droit as ok
      from
         (select * from
         droit,
         profil) pc
         left join autorisation on (autorisation.id_profil = pc.id_profil and autorisation.id_droit = pc.id_droit)
      order by
         pc.lb_droit,
         pc.id_profil");
while ($g_req->n()) {
   if ($g_req->f('id_droit')!=$g_id_droit_courant) {
      $g_id_droit_courant = $g_req->f('id_droit');
?>
            </tr>
            <tr>
               <td class="resultat_tittle"><? echo $g_req->f('lb_droit'); ?></td>
<?
   }
   if ($g_req->f('ok')) {
      $lo_ok = 'OK';
      $lo_acte = '1';
   } else {
      $lo_ok = '-';
      $lo_acte = '2';
   }
?>
               <td class='resultat_list'>
                  <A href = "?Id_droit=<? echo $g_req->f('id_droit') ?>&Id_profil=<? echo $g_req->f('id_profil') ?>&ACTE=<? echo $lo_acte ?>"><? echo $lo_ok ?></A>
               </td>
<?
}
?>
            <tr>
         </table>
<?
include ("ress/enpied.php");
?>
