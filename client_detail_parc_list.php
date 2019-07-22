<?
/*-----------------PARAMETRES---------------------
/*
/* Externes
/*  Id_client
/*
/* Internes
/*  $ACTE
/*  $Id_parc
/*  $Id_client
/*  $Id_gamme
/*  $Produit
/*  $Fournisseur
/*  $Commentaite
/*  $Date_achat
/*  $Maintenance
/*  $Loyer
/*  $Id_transac
/*  $Id_duree
/*  $Qt
/*
/*------------------------------------------------*/
include("ress/entete.php");
?>
<body class="application">
<?

/*--------------- TRAITEMENT DE PROCDUITS --------*/

//Ajouter un parc
if ( $ACTE==1 ) {
   if (!$Id_client) {
      $message = "erreur technique : Client manquant";
   }
   if (!$Qt) {
      $message = "erreur technique : Quantité manquante";
   }
   if (!$Id_gamme) {
      $message = "erreur technique : Gamme manquante";
   }
   if (!$message) {
      $requete = "
         INSERT INTO parc (
            Id_client,
            Id_gamme,
            Produit,
            Fournisseur,
            Commentaire,
            Date_achat,
            Maintenance,
            Loyer,
            Id_transac,
            Id_duree,
            Qt)
         VALUES (
            ".My_sql_format($Id_client).",
            ".My_sql_format($Id_gamme).",
            ".My_sql_format($Produit).",
            ".My_sql_format($Fournisseur).",
            ".My_sql_format($Commentaire).",
            ".My_sql_format($Date_achat).",
            ".My_sql_format($Maintenance).",
            ".My_sql_format($Loyer).",
            ".My_sql_format($Id_transac).",
            ".My_sql_format($Id_duree).",
            ".My_sql_format($Qt).")";
      $resultat= new db_sql("$requete");
   }
}

//Supprimer des produits
if ( $ACTE==2 ) {
   $resultat= new db_sql("
   DELETE FROM
      parc
   WHERE
      Id_parc = $Id_parc");
}

//Modifier des produits
if( $ACTE==3 ) {
   if (!$Id_parc) {
      $message = "erreur technique : Identifiant parc manquant";
   }
   if (!$Qt) {
      $message = "erreur technique : Quantité manquante";
   }
   if (!$Id_gamme) {
      $message = "erreur technique : Gamme manquante";
   }
   if (!$message) {
      $requete = "
         UPDATE parc SET
            Id_gamme    = ".My_sql_format($Id_gamme).",
            Produit     = ".My_sql_format($Produit).",
            Fournisseur = ".My_sql_format($Fournisseur).",
            Commentaire = ".My_sql_format($Commentaire).",
            Date_achat  = ".My_sql_format($Date_achat).",
            Maintenance = ".My_sql_format($Maintenance).",
            Loyer       = ".My_sql_format($Loyer).",
            Id_transac  = ".My_sql_format($Id_transac).",
            Id_duree    = ".My_sql_format($Id_duree).",
            Qt          = ".My_sql_format($Qt)."
         WHERE
            Id_parc = $Id_parc";
            $resultat = new db_sql("$requete");
   }
}
?>
   <table class="menu_haut_resultat">
      <tr>
         <td class="interne">      <a class="interne" href="client_detail_contact_list.php?Id_client=<? echo $Id_client; ?>">Interlocuteurs</A></td>
         <td class="interne">     <a class="interne" href="client_detail_interac_list.php?Id_client=<? echo $Id_client; ?>">Actions</A></td>
         <td class="interne">      <a class="interne" href="client_detail_affaire_list.php?Id_client=<? echo $Id_client; ?>">Affaires</A></td>
         <td class="interne_actif">Parc concurrent</td>
         <td class="interne">      <a class="interne" href="client_detail_marquage.php?Id_client=<? echo $Id_client; ?>">Marquages</a></td>
         <td class="interne">      <a class="interne" href="client_detail_affectation.php?Id_client=<? echo $Id_client; ?>">Affectation</a></td>
         <td class="interne">      <a class="interne" href="client_detail_commentaire.php?Id_client=<? echo $Id_client; ?>">Commentaire</a></td>
         <td width"467"></td>
      </tr>
   </table>
<?
$requete = "
         SELECT
            Id_parc,
            Id_gamme,
            Produit,
            Fournisseur,
            Commentaire,
            Date_achat,
            Maintenance,
            Loyer,
            Id_transac,
            Id_duree,
            Qt
         FROM
            parc
         WHERE
            Id_client = $Id_client";
$resultat = new db_sql($requete);
while ($resultat->n()) {
?>
<table class="requeteur">
   <form method="post" name = "parc<? echo $resultat->f('Id_parc') ?>" action="client_detail_parc_list.php">
        <tr>
           <td class="requet_right"><Span class="oblig">Gamme</span></td>
           <td class="requet_left" ><? drop_down_droit ("O", "SELECT Id_produit, Lb_produit FROM produit where niveau = 2", "Id_gamme", "Id_produit", "lb_produit", $resultat->f('Id_gamme'), false, "parc<? echo $resultat->f('Id_parc') ?>", "O", "", " "); ?></td>
           <td class="requet_right">Produit</td>
           <td class="requet_left" ><? champ_ouvert_droit("O","Produit", $resultat->f('Produit'),20, 20,"N"); ?></td>
           <td class="requet_right"><Span class="oblig">Quantité</span></td>
           <td class="requet_left" ><? champ_numeric_droit("O", "Qt", $resultat->f('Qt'), 0, 5, "parc".$resultat->f('Id_parc'), "O") ?></td>
        </tr>
        <tr>
           <td class="requet_right">Fournisseur</td>
           <td class="requet_left" ><? champ_ouvert_droit("O","Fournisseur", $resultat->f('Fournisseur'),20, 20,"N"); ?></td>
           <td class="requet_right">Date achat</td>
           <td class="requet_left" ><? echo champ_date_droit ("O", "Date_achat", $resultat->f('Date_achat'), "parc".$resultat->f('Id_parc'), "N")?></td>
           <td class="requet_right">Maintenance</td>
           <td class="requet_left" ><? champ_binaire_droit ("O","Maintenance", "O", $resultat->f('Maintenance')) ?></td>
        </tr>
        <tr>
           <td class="requet_right">Transaction</td>
           <td class="requet_left" ><? drop_down_droit("O","SELECT Id_transac, Lb_transac FROM transac","Id_transac", "Id_transac", "Lb_transac", $resultat->f('Id_transac'), false, "parc".$resultat->f('Id_parc'),"N",""," "); ?></td>
           <td class="requet_right">Loyer</td>
           <td class="requet_left" ><? champ_numeric_droit("O", "Loyer", $resultat->f('Loyer'), 2, 12, "parc".$resultat->f('Id_parc'), "N") ?></td>
           <td class="requet_right">Durée</td>
           <td class="requet_left" ><? drop_down_droit("O","SELECT Id_duree, Lb_duree FROM duree","Id_duree", "Id_duree", "Lb_duree", $resultat->f('Id_duree'), false, "parc".$resultat->f('Id_parc'),"N",""," "); ?></td>
        </tr>
        <tr>
           <td class="requet_right" valign = top>Commentaire</td>
           <td class="requet_left" colspan = 4><? text_area_droit ("O","Commentaire", 80, 2, 255, $resultat->f('Commentaire'),"N"); ?></td>
           <td class="requet_button">
              <input class="requeteur_button" type="submit" value="Supprimer" OnClick="Javascript:this.form.ACTE.value='2'; this.form.submit();">
              <input type="hidden" name="ACTE" value="3">
              <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
              <input type="hidden" name="Id_parc" value="<? echo $resultat->f('Id_parc') ?>">
              <input class="requeteur_button" type="submit" name="Submit" value="Enregistrer" OnClick="return champ_oblig('parc<? echo $resultat->f('Id_parc'); ?>')">
           </td>
        </tr>
   </form>
</table>
<?
}
?>
<table class="requeteur">
   <form method="post" name = "parcnew" action="client_detail_parc_list.php">
        <tr>
            <td class="requet_right"><Span class="oblig">Gamme</span></td>
           <td class="requet_left" ><? drop_down_droit ("O", "SELECT Id_produit, Lb_produit FROM produit where niveau = 2", "Id_gamme", "Id_produit", "Lb_produit", "", false, "parcnew", "O", "", " "); ?></td>
           <td class="requet_right">Produit</td>
           <td class="requet_left" ><? champ_ouvert_droit("O","Produit", "",20, 20,"N"); ?></td>
           <td class="requet_right"><Span class="oblig">Quantité</span></td>
           <td class="requet_left" ><? champ_numeric_droit("O", "Qt","", 0, 5, "parcnew", "O") ?></td>
        </tr>
        <tr>
           <td class="requet_right">Fournisseur</td>
           <td class="requet_left" ><? champ_ouvert_droit("O","Fournisseur", "",20, 20,"N"); ?></td>
           <td class="requet_right">Date achat</td>
           <td class="requet_left" ><? echo champ_date_droit ("O", "Date_achat", "", "parcnew", "N")?></td>
           <td class="requet_right">Maintenance</td>
           <td class="requet_left" ><? champ_binaire_droit ("O","Maintenance", "O", "") ?></td>
        </tr>
        <tr>
           <td class="requet_right">Transaction</td>
           <td class="requet_left" ><? drop_down_droit("O","SELECT Id_transac, Lb_transac FROM transac","Id_transac", "Id_transac", "Lb_transac", "", false, "parcnew","N",""," "); ?></td>
           <td class="requet_right">Loyer</td>
           <td class="requet_left" ><? champ_numeric_droit("O", "Loyer", "", 2, 12, "parcnew", "N") ?></td>
           <td class="requet_right">Durée</td>
           <td class="requet_left" ><? drop_down_droit("O","SELECT Id_duree, Lb_duree FROM duree","Id_duree", "Id_duree", "Lb_duree", "", false, "parcnew","N",""," "); ?></td>
        </tr>
        <tr>
           <td class="requet_right" valign = top>Commentaire</td>
           <td class="requet_left" colspan = 4><? text_area_droit ("O","Commentaire", 80, 2, 255, "","N"); ?></td>
           <td class="requet_button">
              <input class="requeteur_button" type="reset" value="Rafraîchir">
              <input type="hidden" name="Id_client" value="<? echo $Id_client ?>">
              <input type="hidden" name="ACTE" value="1">
              <input class="requeteur_button" type="submit" value="Créer" OnClick="return champ_oblig('parcnew')">
           </td>
        </tr>
   </form>
</table>
<?
include ("ress/enpied.php");
?>
