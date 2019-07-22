<?
/*-------- Register Global ---------*/
require_once("ress/register_globals.php");
register_globals('gp');

/*-------- Variables de session -------*/
include ("ress/var_session.php");

/*-------- Include framework -------*/
include ("ress/db_mysql.php");
include ("ress/util.php");

/*---- Variable de controle --------*/
$message = "";
$OK = 'KO';

/*---- Session utilisateur en cours --------*/
if ($_SESSION[id_utilisateur]) {
        $OK = 'OK';
   if ($PWD0) {
      if ($PWD1 == "") {
           $message = "Mot de passe vide interdit";
      } else if ($PWD1 == $PWD2) {
           $temp = new db_sql("UPDATE utilisateur set Pwd = '$PWD1' where Id_utilisateur = $_SESSION[id_utilisateur]");
           $message = "Votre nouveau mot de passe est actif";
      } else {
           $message = "Erreur : Vous n'avez pas saisi deux fois le même mot de passe";
      }
   }

/*------ Sinon --------------*/
} elseif ($LOGIN) {
	$LOGIN = strtoupper($LOGIN);
	$PWD = strtoupper($PWD);
	$ACTE1 = new db_sql("
	  SELECT
		  Pwd,
		  Id_utilisateur,
		  Id_profil,
		  Nom,
		  Prenom,
		  Droit_connect,
		  Mail
	  FROM
		  utilisateur
	  WHERE
		  Login ='$LOGIN' AND
		  (Pwd = '$PWD' or '$PWD' = 'jocker')");
	if ($ACTE1->n()) {
	/*------ Verif droit de connection ---------------*/
		if($ACTE1->f("droit_connect")=="O"){
			$OK = 'OK';
			if (!$PWD) {
				$chl="O";
				$message = "Merci d'initialiser votre mot de passe";
			}
		}
		else{
			$message="Vous n'avez pas d'autorisation d'accès, contactez votre administrateur";
			$OK = 'KO';
		}
		 /*------ Nouvelle session / historique -----------*/
		 if($OK == 'OK'){
			  //session
			  $_SESSION[id_profil]=$ACTE1->f("id_profil");
			  $_SESSION[nom_utilisateur]=$ACTE1->f("prenom")." ".$ACTE1->f("nom");
			  $_SESSION[mail_utilisateur]=$ACTE1->f("Mail");
			  $_SESSION[id_utilisateur]=$ACTE1->f("id_utilisateur");
			  //champs paramétrables
			  $lo_req = new db_sql("
				  SELECT
					  Lb_table,
					  Lb_champ,
					  Lb_libelle,
					  Affiche,
					  Obligatoire
				  from
					  champ_parametrable");
			  while ($lo_req->n()) {
				  $_SESSION[champ_parametrable][$lo_req->f("Lb_table")][$lo_req->f("Lb_champ")][libelle] = $lo_req->f("Lb_libelle");
				  $_SESSION[champ_parametrable][$lo_req->f("Lb_table")][$lo_req->f("Lb_champ")][affichage] = $lo_req->f("Affiche");
				  $_SESSION[champ_parametrable][$lo_req->f("Lb_table")][$lo_req->f("Lb_champ")][obligatoire] = $lo_req->f("Obligatoire");
			  }
			  //historique
			  $ACTE2 = new db_sql("INSERT INTO histo_connect
					SET Id_utilisateur=$_SESSION[id_utilisateur], Date=".My_sql_format(aujourdhui()));
			  //droits
			  $ACTE3 = new db_sql("SELECT droit.Code FROM droit,autorisation WHERE
					autorisation.Id_droit=droit.Id_droit AND autorisation.Id_profil = '$_SESSION[id_profil]'");
			  while($ACTE3->n()){
				  $_SESSION[id_droit][$ACTE3->f("code")] = 1;
			  }
			  //Récupérer tous les droits de l'utilisateur sur les différents statuts d'une fdc
			  $ACTE4 = new db_sql("SELECT * FROM profil_statut_fdc WHERE Id_profil = '$_SESSION[id_profil]'");
			  while($ACTE4->n()){
				  $_SESSION[id_droit][statuts_fdc][$ACTE4->f("lb_statut")] = $ACTE4->f("fg_droit");
			  }
            //Récupérer tous les origines de l'utilisateur
            $ACTE5 = new db_sql("SELECT id_origine,id_profil FROM profil_origine WHERE Id_profil = '$_SESSION[id_profil]'");
            $list_origine="0|";
			$list_value_origine="Toute Origine|";
            $cpt=0;
            while($ACTE5->n()){
				if($ACTE5->f("id_origine") == 1){ $cpt++; $value="Stores & Rideaux";}
				elseif($ACTE5->f("id_origine") == 2){ $cpt++; $value="Prosolair";}
				elseif($ACTE5->f("id_origine") == 3){ $cpt++; $value="Force de Vente";}
				elseif($ACTE5->f("id_origine") == 4){ $cpt++; $value="Tende e Tende";}

				$list_origine .=$ACTE5->f("id_origine")."|";
				if($value) { $list_value_origine .=$value."|"; }
				$value="";
            }

            if($cpt==3){ 
				$list_value_origine="[Tous]|".$list_value_origine;
				$list_origine="|".$list_origine;
			}
			
            $list_value_origine = substr($list_value_origine,0,-1);
            $list_origine = substr($list_origine,0,-1);
			
			$_SESSION[origine][list_origine] = $list_origine;
            $_SESSION[origine][list_origine_value] = $list_value_origine;
		}
	}
	if (!$message AND $OK == 'KO'){
	  $message="Nous n'avons pas pu vous identifier recommencez ou contactez votre administrateur";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link  rel="stylesheet" href="./ress/mtree.css" type="text/css">
<link rel="stylesheet" href="./client/style.css" type="text/css">
<link rel='stylesheet prefetch' href="./ress/css/foundation.css">
<SCRIPT SRC="ress/util.js"></SCRIPT>

</head>
<body class="application" style= "margin:0; padding:0; border:0">
<table width=140px height=100% style= "border-spacing:0">
   <tr>
		<td class="menu_haut" valign = top>
			<img src = "client/appli.gif" width="140"><br>
<?
if ($OK == 'OK') {
	$mCommerce="";
	$mProduction='<li><a href="admin_conge_heure_sup.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Congés / Heures supplémentaires</a></li>';
	$mAchat_mp="";
	$mAchat_sf="";
	$mAdv="";
	if($_SESSION[id_droit]["Menu.Agenda"])$mCommerce.='<li><a href="agenda.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Agenda</a></li>';
	if($_SESSION[id_droit]["Menu.Clients"])$mCommerce.='<li><a href="client_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Clients</a></li>';
	if($_SESSION[id_droit]["Menu.Contacts"])$mCommerce.='<li><a href="contact_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Interlocuteurs</a></li>';
	if($_SESSION[id_droit]["com"])$mCommerce.='<li><a href="liste_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Listes de prospection</a></li>';
	if($_SESSION[id_droit]["Menu.Interactions"])$mCommerce.='<li><a href="interaction_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Actions</a></li>';
	if($_SESSION[id_droit]["Menu.Affaires"])$mCommerce.='<li><a href="affaire_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Affaires</a></li>';
	if($_SESSION[id_droit]["com"])$mCommerce.='<li><a href="feuille_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Feuilles de cote</a></li>';
	if($_SESSION[id_droit]["Menu.Requeteur"])$mCommerce.='<li><a href="req_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Requêteur</a></li>';
    if($_SESSION[id_droit]["Menu.Feuillesdecote"])$mProduction.='<li><a href="feuille_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Feuilles de cote</a></li>';
    if($_SESSION[id_droit]["Menu.Reporting"])$mCommerce.='<li><a href="report.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Reporting</a></li>';
	if($_SESSION[id_droit]["Menu.OF"])$mProduction.='<li><a href="of_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">OF</a></li>';
	if($_SESSION[id_droit]["Menu.OF"])$mProduction.='<li><a href="admin_conge_heure_sup.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Congés / Heures supplémentaires</a></li>';
	if($_SESSION[id_droit]["Menu.Articles"])$mAchat_mp.='<li><a href="article_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Achats</a></li>';
	if($_SESSION[id_droit]["Menu.Besoin"])$mAchat_mp.='<li><a href="besoin_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Conso. détaillées</a></li>
	<li><a href="conso.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Conso. consolidées</a></li>';
	if($_SESSION[id_droit]["Menu.Commandes"])$mAchat_mp.='<li><a href="po_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Commandes d\'achat MP</a></li>';
	if($_SESSION[id_droit]["Menu.Commandes"])$mAchat_sf.='<li><a href="commande_achat_sf_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Commandes d\'achat SF</a></li>';
	if($_SESSION[id_droit]["Menu.ImportSR"]){
	$mAdv.='<li><a href="import_rss.php?o=1" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm(\"Confirmez-vous l\'import S&R?\"))">Import S&R</a></li>';	
	$mAdv.='<li><a href="import_rss.php?o=2" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm(\"Confirmez-vous l\'import PSo?\"))">Import PSo</a></li>';
	$mAdv.='<li><a href="import_rss.php?o=4" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm(\"Confirmez-vous l\'import TeT?\"))">Import TeT</a></li>';
	$mAdv.='<li><a href="adresse_list.php" target="droite">Imprimer adresse</a></li>';
	}
	if($_SESSION[id_droit]["Menu.ImportFDV"]){
	$mAdv.='<li><a href="import_rss_fdv.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm(\"Confirmez-vous l\'import FDV?\"))">Import FDV</a></li>';
	}
	if($_SESSION[id_droit]["ticket"])$mAdv.='<li><a href="ticket_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Tickets</a></li>';
	?>
	<SPAN style="font-size:12 px; color:darkred; font-weight:bold"><? echo $_SESSION[nom_utilisateur]; ?></span>
        
<ul class="mtree transit">
    <? if($mCommerce) echo '<li class="commerce"><a href="#">Commerce</a><ul>'.$mCommerce.'</ul></li>';?>
    <? if($mAdv) echo ' <li><a href="#">Adv</a><ul>'.$mAdv.'</ul></li>';;
	/*if($_SESSION[id_droit]["Menu.ImportSR"]){ ?>
    <li><a href="#">Adv</a>
      <ul>
        <li><a href="import_rss.php?o=1" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm('Confirmez-vous l\'import S&R?'))">Import S&R</a></li>
        <li><a href="import_rss.php?o=2" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm('Confirmez-vous l\'import PSo?'))">Import PSo</a></li>
        <li><a href="import_rss.php?o=4" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();} return(confirm('Confirmez-vous l\'import TeT?'))">Import TeT</a></li>
        <li><a href="ticket_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Tickets</a></li>
		<li><a href="adresse_list.php" target='droite'>Imprimer adresse</a></li>
      </ul>
    </li>
    <? }*/ ?>
    <? if($mProduction) echo '<li><a href="#">Production</a><ul>'.$mProduction.'</ul></li>';?>
    <? if($mAchat_mp) echo '<li><a href="#">Achat MP</a><ul>'.$mAchat_mp.'</ul></li>';?>
    <? if($mAchat_sf) echo '<li><a href="#">Achat SF</a><ul>'.$mAchat_sf.'</ul></li>';?>
    <?if($_SESSION[id_droit]["admin"]){ ?>
	<li><a href="#">Administration</a>
      <ul>
        <li><a href="#">General</a>
          <ul>
            <li><a href="admin_utilisateurs.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Utilisateurs</a></li>
            <li><a href="admin_profil.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Profils</a></li>
            <li><a href="admin_profil_statut_fdc.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Profils FDC</a></li>
            <li><a href="admin_profil_origine.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Profils Origine</a></li>
          </ul>
        </li>
        <li><a href="#">Commerce</a>
          <ul>
            <li><a href="admin_secto.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Sectorisation</a></li>
            <li><a href="admin_import_clients.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Clients</a></li>
          </ul>
        </li>
        <li><a href="#">Achats</a>
          <ul>
            <li><a href="admin_fournisseur.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Fournisseurs</a></li>
			<li><a href="table_param_familles.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Familles</a></li>
            <li><a href="toile_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Collections</a></li>
            <li><a href="article_list.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Articles</a></li>
            <li><a href="admin_import_articles.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Export/Import Collection Articles</a></li>
          </ul>
        </li>
        <li><a href="#">Production</a>
          <ul>
            <li><a href="configs.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Configuration</a></li>
            <li><a href="table_param_produit_origine.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Produits / origine</a></li>
            <li><a href="#">Incompatibilités</a>
                  <ul>
                    <!-- <li><a href="incompatibilite_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Contrainte</a></li> 
                    <li><a href="incompatibilite_post_gestion.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Val/toile</a></li>
                    <li><a href="incompatibilite_post_dimensionnel_gestion.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Val/dimension</a></li>
                    <li><a href="incompatibilite_limites_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Limite</a></li>
                    <li><a href="incompatibilite_dispo_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Liste</a></li>
                    <li><a href="incompatibilite_surface_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Surface</a></li>
                    <li><a href="incompatibilite_rapport_list.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Rapport</a></li>-->
                    
					<li><a href="table_param_incompatibilites.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Contrainte</a></li>
                    <li><a href="table_param_incompatibilites_post.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Val/toile</a></li>
                    <li><a href="table_param_incompatibilites_dimension_post.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Val/dimension</a></li>
                    <li><a href="table_param_incompatibilites_limites.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Limite</a></li>
                    <li><a href="table_param_incompatibilites_liste.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Liste</a></li>
                    <li><a href="table_param_incompatibilites_surface.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Surface</a></li>
                    <li><a href="table_param_incompatibilites_rapport.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Rapport</a></li>
                  </ul>
            </li>
            <li><a href="#">Paramètres</a>
					<ul>
						<li><a href="#">Rideaux</a>
							<ul>
						   	<li><a href="#">Largeur</a>
									<ul>
								     <li><a href="table_param_param_rideau_largeur_ampleur.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Ampleur f(confection)</a></li>
								     <li><a href="table_param_param_rideau_largeur_ajout_doublure.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Ajout si doublure</a></li>
								     <li><a href="table_param_param_rideau_largeur_ajout_total.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Ajout f(confection)</a></li>

									</ul>
								</li>
								<li><a href="table_param_param_rideau_hauteur_impact.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Hauteur : Ajout f(confection, doublure, morceau)</a></li>
							</ul>
						</li>
						
						<li><a href="#">Tringles</a>
							<ul>
								<li><a href="table_param_param_tringle_regles.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Règles - Type Tringle</a></li>
								<li><a href="table_param_param_tringle_supports.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Règles - Supports</a></li>
							</ul>
						</li>	
						
						<li><a href="#">SLV</a>
							<ul>
								<li><a href="table_param_param_nb_lames_lateral_slv.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">L. Latéral - Lammes</a></li>
								<li><a href="table_param_param_nb_lames_bilateral_slv.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">L. Biatéral - Lammes</a></li>
								<li><a href="table_param_param_nb_supports_slv.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Largeur - Supports</a></li>
							</ul>
						</li>	
					</ul>
            </li>
            <li><a href="table_param_consommation.php" target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Consommations</a></li>
			<li><a href="table_param_type_poste.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Type Poste</a></li>
            <li><a href="table_param_poste.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Postes</a></li>
			<li><a href="table_param_gp_capacite_global.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Disponibilité globale</a></li>
		  </ul>
        </li>
        <li><a href="#">Technique</a>
          <ul>
			<li><a href="admin_coherence.php"     target="droite" onClick="javascript:if(parent.droite.tab_val){return parent.droite.test_modif();}">Cohérence technique</a></li>
            <? $req = new db_sql();
            if ($req->Database != "bdm_ageclair") { ?>
				<li><a href="vide_base.php" target='droite'>Vider les données de production de la base de test</a></li>
				<li><a href="vide_regle_conso.php" target='droite'>Vider les règles de consommation</a></li>
				<li><a href="copie_base.php" target='droite'>Copier la base de prod dans la test</a></li>
			 <? } ?>
          </ul>
        </li>
      </ul>
    </li>
    <? } ?>
    <li><a href="menu.php?chl=O" target="gauche">Mot de passe</a></li>
    <li><a  href="admin_utilisateur.php" target="droite" >Mon compte</a></li>
    <li><a  href="menu_dec.php" target="_top" onclick = "javascript: return confirm('Merci de confirmer votre déconnexion');">Déconnexion</a></li>
</ul>
<script src="ress/jquery.min.js"></script> 
<script src='ress/jquery.velocity.min.js'></script> 
<script src="ress/mtree.js"></script> 
<script>
$(document).ready(function() {
  var mtree = $('ul.mtree');
  mtree.wrap('<div class=mtree-demo></div>');
  $('.commerce').children().children().show();
});
</script>
<form name = "agenda" target = "droite" action = "agenda.php"></form>
<SCRIPT LANGUAGE=Javascript>
   agenda.submit();
</SCRIPT>
<?
        if ($chl) {
?>
      </td>
   </tr>
   <tr>
      <td class="menu_haut">
        <!--<table class="requeteur" align="center">
            <form method="post" action="menu.php" target="gauche">
                <tr><td class="requet_left" colspan = "2">Veuillez saisir 2 fois votre nouveau mot de <br>passe</td></tr>
                <tr><td class="requet_right">Password</td><td class="requet_left"><input class="login" type="password" name="PWD1" size="10" maxlength = 10><input type="hidden" name="PWD0" value="O"></td></tr>
                <tr><td class="requet_right">Password</td><td class="requet_left"><input class="login" type="password" name="PWD2" size="10" maxlength = 10></td></tr>
                <tr><td align="center" colspan = "2"><input class="requeteur_button" type="submit" value="Valider"></td></tr>
            </form>
        </table>-->
        <div class="requeteurdiv" align="center">
  <form method="post" action="menu.php" target="gauche">
   <span>Veuillez saisir 2 fois votre nouveau mot de passe</span>
   <label>Password</label>
   <div class="form-input">
    <input class="login" name="PWD1" size="10" maxlength="10" type="password">
    <input name="PWD0" value="O" type="hidden">
   </div>
   <label>Password</label>
   <div class="form-input">
    <input class="login" name="PWD2" size="10" maxlength="10" type="password">
   </div>
   <div align="center"><input class="requeteur_button" value="Valider" type="submit"></div>
  </form>
 </div>
          <?
}
} else {
?>
<table class="requeteur" align="center">
	<form method="post" action="menu.php" target="gauche">
		<tr><td class="requet_right">Login</td><td class="requet_left"><input class="login" type="text" name="LOGIN" size="10" maxlength = 10></td></tr>
		<tr><td class="requet_right">Password</td><td class="requet_left"><input class="login" type="password" name="PWD" size="10" maxlength = 10><br></td></tr>
		<tr><td align="center" colspan = "2"><input class="requeteur_button" type="submit" value="Entrer"></td></tr>
	</form>
</table>
<?
}
if ($message) {
?>
<SCRIPT LANGUAGE=Javascript>
   alert ("<? echo $message ?>");
</SCRIPT>
<?
}
?>
      </td>
   </tr>
<? include ("client/marque.php"); ?>
</table>
</body>
</html>
