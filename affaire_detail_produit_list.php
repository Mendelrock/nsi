<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/* Id_affaire
/*
/* Internes
/*  $ACTE
/*  $Id_affaire_detail
/*  $Id_produit
/*  $Lb_produit
/*  $Qt
/*  $Prix
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PRODUITS --------*/
        //Controle formulaire si envoye
if($ACTE){
   if(empty($Id_produit)){
      $message = "Référence produit manquante";
   }
   if($Qt==0){
      //$message = "Quantité nulle interdite";
   }
   if($Prix==0){
      //$message = "Prix nul interdit";
   }
   if (req_sim("select count(1) as compte from produit where Id_produit = '$Id_produit'","compte")==0) {
      $message = "Produit inexistant";
   }
}
//Ajouter des produits
if(!$message){
        if($ACTE==1){
           $requete="
              INSERT INTO
                 affaire_detail (
                 Id_affaire,
                 Id_produit,
                 Prix,
                 Qt)
              VALUES (
                 ".My_sql_format($Id_affaire).",
                 ".My_sql_format($Id_produit).",
                 ".My_sql_format($Prix).",
                 ".My_sql_format($Qt).")";
           $resultat= new db_sql("$requete");
        }
                //Supprimer des produits
        if($ACTE==2) {
                $resultat= new db_sql("
                DELETE FROM
                        affaire_detail
                WHERE
                        Id_affaire_detail=$Id_affaire_detail");
        }
                //Modifier des produits
        if($ACTE==3) {
           $requete="
                UPDATE affaire_detail
                SET
                        Prix='$Prix',
                        Qt='$Qt'
                WHERE
                        Id_affaire_detail=$Id_affaire_detail";
           $resultat = new db_sql("$requete");
        }
        //Mise à jour Prix
        /*if($ACTE){
                $Total=req_sim("SELECT SUM(Prix*Qt) AS Total from affaire_detail where Id_affaire = '$Id_affaire'","Total");
                $resultat= new db_sql("UPDATE affaire SET Prix='$Total' WHERE Id_affaire='$Id_affaire'");
?>
         <SCRIPT language = "javascript">
            window.parent.document.affaire.Prix.value = '<? echo $Total ?>';
         </SCRIPT>
<?
        }*/
}
?>
<table class="menu_haut_resultat">
   <tr>
      <td class="interne"><a class="interne" href="affaire_detail_interaction_list.php?Id_affaire=<? echo $Id_affaire; ?>">Actions liées</a></td>
      <? if($_SESSION[id_droit]["Menu.Feuillesdecote"]){ ?>
      <td class="interne"><a class="interne" href="affaire_detail_fdc_list.php?Id_affaire=<? echo $Id_affaire; ?>">Feuilles de cotes</a> </td>
      <? } ?>
      <td class="interne_actif">Produits</td>
      <td width="467"></td>
   </tr>
</table>
<?

if (((droit_utilisateur("com") and (req_sim("select Id_utilisateur from affaire where id_affaire = $Id_affaire","id_utilisateur") == $_SESSION[id_utilisateur]))) or droit_utilisateur("ascom")) {
   $g_droit = "O";
} else {
   $g_droit = "N";
}

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales
$requete="SELECT
                                affaire_detail.Id_affaire_detail,
                                affaire_detail.Id_produit,
                                affaire_detail.Qt,
                                affaire_detail.Prix,
                                produit.Lb_produit,
                                produit.Ref
                        FROM
                                affaire_detail, produit
                        WHERE
                                affaire_detail.Id_produit=produit.Id_produit AND
                                affaire_detail.Id_affaire=$Id_affaire";
/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des en_têtes
        ?>
<table class="resultat">
   <tr>
      <!--<td class="resultat_tittle">Référence</td>-->
      <td class="resultat_tittle">Libellé</td>
      <td class="resultat_tittle">Quantité</td>
      <!--<td class="resultat_tittle">Prix Unitaire (K€)</td>-->
<?
if ($g_droit == "O") {
?>
      <td class="resultat_tittle" width="180">&nbsp;</td></tr>
<?
}
?>
   </tr>
<?
        // Boucle de lecture
                while($resultat->n()){
                   if ($Id_affaire_detail and ($Id_affaire_detail == $resultat->f('Id_affaire_detail')) and ($ACTE == 3) and ($message)) {
                      $qt_par_defaut   = $Qt;
                      $prix_par_defaut = $Prix;
                   } else {
                      $qt_par_defaut   = $resultat->f('Qt');
                      $prix_par_defaut = $resultat->f('Prix');
                   }
echo "<form method='post' name='produit".$z."' action='affaire_detail_produit_list.php?Id_affaire=",$Id_affaire,"'>\n";
echo "<tr>\n";
echo "<!--<td class='resultat_list' bgcolor='",alternat($z),"'>".substr($resultat->f('Ref'),0,10)."</td>-->";
echo "<td class='resultat_list' bgcolor='",alternat($z),"'>".substr($resultat->f('Lb_produit'),0,30)."</td>";
echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_numeric_droit($g_droit, "Qt", $qt_par_defaut, 0, 5, "produit".$z, "N"),"</td>\n";
//echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_numeric_droit($g_droit, "Prix", $prix_par_defaut, 2, 12, "produit".$z, "N"),"</td>\n";
                   if ($g_droit == "O") {
?>
   <td bgcolor="<? echo alternat($z) ?>">
      <input class="requeteur_button" type="submit" name="modifier" value="modifier"   onClick="javascript: document.produit<? echo $z; ?>.ACTE.value='3';produit<? echo $z; ?>.submit()">
      <!--<input class="requeteur_button" type="submit" name="supprimer" value="supprimer" onClick="javascript: document.produit<? echo $z; ?>.ACTE.value='2';produit<? echo $z; ?>.submit()">-->
      <input type="hidden" name="Id_affaire_detail" value="<? echo $resultat->f("Id_affaire_detail");?>">
      <input type="hidden" name="Id_produit" value="<? echo $resultat->f("Id_produit");?>">
      <input type="hidden" name="ACTE" value="0">
   </td>
<?                 }
?>
</tr>
</form>
<?
                   $z++;
                }
?>
<?
                   if ($g_droit == "O") {
                   if ($ACTE == 1 and $message) {
                      $qt_par_defaut   = $Qt;
                      $prix_par_defaut = $Prix;
                      $id_produit_defaut = $Id_produit;
                      if ($Id_produit) {
                         $ref_defaut = req_sim("select ref from produit where id_produit = $Id_produit","ref");
                         $lb_produit_defaut = req_sim ("select lb_produit from produit where id_produit = $Id_produit","lb_produit");
                      } else {
                         $ref_defaut = "";
                         $lb_produit_defaut = "";
                      }
                   } else {
                      $qt_par_defaut   = '0';
                      $prix_par_defaut = '0.00';
                      $id_produit_defaut = "";
                      $ref_defaut = "";
                      $lb_produit_defaut = "";
                   }
?>
                    <!--<form method="post" name="nouveau" action="affaire_detail_produit_list.php?ACTE=1&Id_affaire=<? echo $Id_affaire; ?>">
                    <SCRIPT LANGUAGE="javascript">
                       function choix_produit(){window.open('affaire_detail_choix_produit.php?Ref='+document.nouveau.Ref.value,'affaire_detail','toolbar=0,location=0,directories=0,status=0,copyhistory=0,menuBar=0,scrollbars=1,Height=400,Width=400');}
                     </SCRIPT>
                     <tr>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input type='text' name="Ref" value="<? echo $ref_defaut; ?>" size=10 maxlength=10 onchange = "javascript : choix_produit();"><input type='hidden' name='h_Ref' value="O"><A class="oblig" href="javascript:choix_produit()"> ?</a></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_ouvert_droit("N","Lb_produit",$lb_produit_defaut,30, 30, "O"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_numeric_droit($g_droit, "Qt", $qt_par_defaut, 0, 5, "nouveau", "O") ?>
                        <!--<td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_numeric_droit($g_droit, "Prix", $prix_par_defaut, 2, 12, "nouveau", "O") ?>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input type="hidden" name="Id_produit" value="<? echo $id_produit_defaut; ?>"><input type="hidden" name="h_Id_produit" value="O"><input class="requeteur_button" type="submit" value="Ajouter"></td>
                      </tr>
                      </form>-->
<?
                   }
?>
                        </table>
<?
include ("ress/enpied.php");
?>