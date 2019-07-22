<?
/*-----------------PARAMETRES---------------------
/* Internes
/*  $ACTE
/*  $Id_produit
/*  $Lb_produit
/*  $Ref
/*  $Id_produit_pere
/* Externes
/*  $NIVEAU
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

if ($NIVEAU == req_sim("SELECT max(Niveau) as Niveau from niveau_produit","Niveau")) {
 $g_niveau_max = true;
} else {
 $g_niveau_max = false;
}

/*--------------- TRAITEMENT DE PRODUITS --------*/
        //Controle formulaire si envoye
if($ACTE){
   if(empty($Ref)){
      $message = "Référence manquante";
   }
   if(empty($Lb_produit)){
      $message = "Libelle du produit manquant";
   }
   if($Id_produit_pere==0 and !($g_niveau_max)){
      $message = "Père manquant";
   }
}
        //Ajouter des produits
if(!$message){
   if($ACTE==1){
                // Verification de la réference
                        if(req_sim("SELECT count(1) as compte FROM produit WHERE Ref='$Ref'","compte")>0){
                                $message="Cette réference existe deja";
                        }
                        if(!$message){
                          $requete="
                        INSERT INTO
                                produit (
                                Ref,
                                Lb_produit,
                                Id_produit_pere,
                                Niveau)
                        VALUES (
                                ".My_sql_format($Ref).",
                                ".My_sql_format($Lb_produit).",
                                ".My_sql_format($Id_produit_pere).",
                                ".My_sql_format($NIVEAU).")";
                        $resultat= new db_sql("$requete");
            }
   }
//Modifier des produits
   if($ACTE==2) {
      if(req_sim("SELECT count(1) as compte FROM produit WHERE Ref='$Ref' and Id_produit <> $Id_produit","compte")>0){
         $message="Cette réference existe deja";
      }
      if ( !$message ) {
           $requete="
                UPDATE
                   produit
                SET
                   Ref             =".My_sql_format($Ref).",
                   Id_produit_pere =".My_sql_format($Id_produit_pere).",
                   Lb_produit      =".My_sql_format($Lb_produit)."
                WHERE
                   Id_produit='$Id_produit'";
           $resultat = new db_sql("$requete");
      }
   }
}
?>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Référentiel produit <BR>Niveau : <? echo req_sim("SELECT Lb_niveau  from niveau_produit where niveau = $NIVEAU","Lb_niveau") ?></td>
       <td></td>
    </tr>
</table>
<?
        /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="     SELECT
                                produit.Id_produit,
                                produit.Id_produit_pere,
                                produit.Lb_produit,
                                produit.Ref
                        FROM
                                produit
                                left join produit as produit_pere on (produit.Id_produit_pere = produit_pere.Id_produit)

                        WHERE
                                produit.Niveau = $NIVEAU";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
<table class="resultat">
   <tr>
                <td class="resultat_tittle">Père</td>
<?
if (!$g_niveau_max) {
?>
                <td class="resultat_tittle">Référence</td>
<?
}
?>
                <td class="resultat_tittle">Libellé</td>
                <td class="resultat_tittle">&nbsp;</td>
   </tr>
<?
        // Boucle de lecture
                while($resultat->n()){
                   echo "<form method='post' name='produit".$z."' action='admin_produit.php?ACTE=2'>";
                   echo "<tr>";
                   if (!$g_niveau_max) {
                      echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",drop_down_droit("admin", "select Id_produit, Lb_produit from produit where Niveau = ".($NIVEAU+1), "Id_produit_pere", "Id_produit", "Lb_produit",$resultat->f("Id_produit_pere"), false, "produit".$z, "O", "", " "),"</td>";
                   }
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_ouvert_droit("admin", "Ref", $resultat->f("Ref"), 10, 10, "O"),"</td>";
                   echo "<td class='resultat_list' bgcolor='",alternat($z),"'>",champ_ouvert_droit("admin", "Lb_produit", $resultat->f("Lb_produit"), 30, 30, "O"),"</td>\n";
?>
                   <td bgcolor="<? echo alternat($z) ?>">
                      <input class="requeteur_button" type="submit" name="modifier" value="Modifier"   onClick="return champ_oblig('produit<? echo $z ?>')">
                      <input type="hidden" name="Id_produit" value="<? echo $resultat->f("Id_produit");?>">
                      <input type="hidden" name="NIVEAU" value="<? echo $NIVEAU;?>">
                   </td>
   </tr>
   </form>
<?
                   $z++;
                }
?>
                    <form method="post" name="nouveau" action="admin_produit.php?ACTE=1&NIVEAU="<? echo $NIVEAU; ?>>
                     <tr>
<?
if (!$g_niveau_max) {
?>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? drop_down_droit ("admin", "select Id_produit, Lb_produit from produit where Niveau = ".($NIVEAU+1), "Id_produit_pere", "Id_produit", "Lb_produit",'', false, "produit".$z, "O", "", " "); ?></td>
<?
}
?>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_ouvert_droit("admin", "Ref", "", 10, 10, "O"); ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><? champ_ouvert_droit("admin", "Lb_produit", "", 30, 30, "O") ?></td>
                        <td class='resultat_list' bgcolor="<? echo alternat($z) ?>"><input class="requeteur_button" type="submit" value="Ajouter" onClick="return champ_oblig('nouveau')"></td>
                      </tr>
                      <input type="hidden" name="NIVEAU" value="<? echo $NIVEAU;?>">
                      </form>
                      </table>
<?
include ("ress/enpied.php");
?>