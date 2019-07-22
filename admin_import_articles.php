<?
include("ress/entete.php");
 
/*-----------------PARAMETRES---------------------*/
/* Externe
/* userfile
/* ACTE */
/*------------------------------------------------*/
function transcote($x) {
	$x = trim($x);
   if ($x=="") return "null"; 
   return "'".str_replace ("'","''",$x)."'";
}
if (droit_utilisateur("admin")) {
	?>
	<body class="application">
	<form method="post" action="admin_import_articles.php?ACTE=1" ENCTYPE="multipart/form-data" >
	   <table class="menu_haut_resultat">
	      <tr>
	         <td class="menu_haut">
	         	Fichier à importer (xlsx) :
	         <input type="file" name="userfile" enctype="multipart/form-data">
	         <input type="submit" value="Importer">
	         <a href="admin_export_articles.php" target="xl">Exporter </a>
	         </td>
	      </tr>
	   </table>
	</form>
<pre>
*** Format :

Pour les toiles :

lb_toile_atelier * : Clé de reconnaissance principale pour mise a jour
lb_toile_sr * : Clé de reconnaissance alternative(1) pour mise à jour + Clé de reconnaissance pour les sites
amotif_raccordable
amotif_ajustable
selecteur_rideau
selecteur_doublure
selecteur_store
selecteur_enrouleur_exterieur
selecteur_enrouleur_interieur
selecteur_coffre_interieur
selecteur_coffre_interieur
selecteur_film_exterieur
selecteur_film_interieur
selecteur5
selecteur6
selecteur7
selecteur8
selecteur9
selecteur_coussin
selecteur_paroi
couleur * : Clé composée (avec la gamme) de reconnaissance alternative(2)
gamme   *
orientation
toile_FDV

Pour les articles :

lb_article * Clé composée (avec le sélecteur) de reconnaissance pour mise a jour + Cle de reconnaissance pour les sites
selecteur  *
lb_article_aff :  Libellé qui est affiché dans l'écran de stock et d'appro pour distinguer par exemple les articles qui arrivent des sites avec une simple couleur comme libellé
type
poids 
laize
qt_stock (physique)
qt_mini
qt_max
actif
FDV
delai

Pour les fournisseurs :
 
lb_fournisseur * : Clé de reconnaissance pour mise a jour
adresse_postale
adresse_mail
cond_regle
envoi_automatique

Pour les données fournisseur :

principal
reference
quotite
prix

Autres paramètres :

decote
</pre>

	<?
	if($ACTE==1){
		include 'ress/PHPExcel/IOFactory.php';
	   //Variable du fichier upload
	   $tempfile_name=$_FILES['userfile']['tmp_name'];
	   if(empty($tempfile_name)){
	      $tempfile_name="C:\\Users\\tlcn6661\\Desktop\\data.xlsx";
	   }
	   if(!$message){
	   	if($objPHPExcel = PHPExcel_IOFactory::load($tempfile_name)) {
	   		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			   $titre = $sheetData[1];
		      foreach ($titre as $n=>$libelle) {
		         $titre[$n] = trim(utf8_decode($libelle));
		         $numero[trim(utf8_decode($libelle))] = $n;
		      }
			   foreach ($sheetData as $i=>$ligne) {
			      if ($i != 1) {
						foreach ($titre as $n=>$libelle) {
							${$libelle} = trim(utf8_decode($ligne[$n]));	
							if (${$libelle}=="NULL") unset(${$libelle});
						}	
	                  //******************************************
	                  // TOILE
	                  //******************************************
	                  $id_toile = "";
							if ($lb_toile_atelier or $lb_toile_sr or $couleur or $gamme) {
					         $requete="SELECT 
					                      id_toile
					                   FROM
					                      toile
					                   WHERE
					                      lb_toile_atelier = ".My_sql_format(trim($lb_toile_atelier))." or 
					                      lb_toile_sr = ".My_sql_format(trim($lb_toile_sr))." or
					                      (gamme = ".My_sql_format(trim($gamme))." and couleur = ".My_sql_format(trim($couleur)).")";
					         $req = new db_sql($requete);
					         if ($req->n()) {
					         	$id_toile = $req->f('id_toile');
					            echo "&nbsp;&nbsp;&nbsp;Mise à jour de la toile $lb_toile_atelier ( $id_toile ) <BR>";
					            $req = "update toile set ";
								if (isset($lb_toile_atelier)) $req .= "lb_toile_atelier = ".My_sql_format(trim($lb_toile_atelier)).",";
								if (isset($lb_toile_sr)) $req .= "lb_toile_sr = ".My_sql_format(trim($lb_toile_sr)).",";
								if (isset($selecteur_rideau)) $req .= "selecteur_rideau = ".($selecteur_rideau?"1":"0").",";
								if (isset($selecteur_doublure)) $req .= "selecteur_doublure =  ".($selecteur_doublure?"1":"0").",";
								if (isset($selecteur_voilage)) $req .= "selecteur_voilage =  ".($selecteur_voilage?"1":"0").",";
								if (isset($selecteur_store)) $req .= "selecteur_store = ".($selecteur_store?"1":"0").",";
								if (isset($selecteur_enrouleur_exterieur)) $req .= "selecteur_enrouleur_exterieur = ".($selecteur_enrouleur_exterieur?"1":"0").",";
								if (isset($selecteur_enrouleur_interieur)) $req .= "selecteur_enrouleur_interieur = ".($selecteur_enrouleur_interieur?"1":"0").",";
								if (isset($selecteur_coffre_exterieur)) $req .= "selecteur_coffre_exterieur = ".($selecteur_coffre_exterieur?"1":"0").",";
								if (isset($selecteur_coffre_interieur)) $req .= "selecteur_coffre_interieur = ".($selecteur_coffre_interieur?"1":"0").",";
								if (isset($selecteur_film_exterieur)) $req .= "selecteur_film_exterieur = ".($selecteur_film_exterieur?"1":"0").",";
								if (isset($selecteur_film_interieur)) $req .= "selecteur_film_interieur = ".($selecteur_film_interieur?"1":"0").",";
								if (isset($selecteur5)) $req .= "selecteur5 = ".($selecteur5?"1":"0").",";
								if (isset($selecteur6)) $req .= "selecteur6 = ".($selecteur6?"1":"0").",";
								if (isset($selecteur7)) $req .= "selecteur7 = ".($selecteur7?"1":"0").",";
								if (isset($selecteur8)) $req .= "selecteur8 = ".($selecteur8?"1":"0").",";
								if (isset($selecteur9)) $req .= "selecteur9 = ".($selecteur9?"1":"0").",";
								if (isset($selecteur_coussin)) $req .= "selecteur_coussin = ".($selecteur_coussin?"1":"0").",";
								if (isset($selecteur_paroi)) $req .= "selecteur_paroi = ".($selecteur_paroi?"1":"0").",";
								if (isset($amotif_raccordable)) $req .= "amotif_raccordable = ".($amotif_raccordable?"1":"0").",";
								if (isset($amotif_ajustable)) $req .= "amotif_ajustable = ".($amotif_ajustable?"1":"0").",";
								if (isset($toile_FDV)) $req .= "FDV = ".($toile_FDV?"1":"0").",";
								if (isset($couleur)) $req .= "couleur = ".My_sql_format(trim($couleur)).",";
								if (isset($gamme)) $req .= "gamme = ".My_sql_format(trim($gamme)).",";
								if (isset($orientation)) $req .= "orientation = ".My_sql_format($orientation).",";
								$req .= "id_toile = $id_toile  ";
								$req .= "where id_toile = $id_toile ";
								$req = new db_sql($req);
					         } else {
					         	echo "&nbsp;&nbsp;&nbsp;Création d'une nouvelle toile $lb_toile_atelier <BR>";
					            $req = new db_sql("INSERT INTO toile (
													 id_toile ,
													 lb_toile_atelier ,
													 lb_toile_sr ,
													 selecteur_rideau ,
													 selecteur_doublure ,
													 selecteur_store ,
													 selecteur_enrouleur_exterieur ,
													 selecteur_enrouleur_interieur ,
													 selecteur_coffre_exterieur ,
													 selecteur_coffre_interieur ,
													 selecteur_film_exterieur ,
													 selecteur_film_interieur ,
													 selecteur5 ,
													 selecteur6 ,
													 selecteur7 ,
													 selecteur8 ,
													 selecteur9 ,
													 selecteur_coussin ,
													 selecteur_paroi ,
													 amotif_raccordable ,
													 amotif_ajustable ,
													 FDV,													 
													 couleur ,
													 gamme ,
													 orientation
									             )
										       VALUES (
													 NULL ,  
													 ".My_sql_format(trim($lb_toile_atelier)).",  
													 ".My_sql_format(trim($lb_toile_sr)).",  
													 '".($selecteur_rideau?"1":"0")."', 
													 '".($selecteur_doublure?"1":"0")."',  
													 '".($selecteur_store?"1":"0")."', 
													 '".($selecteur_enrouleur_exterieur?"1":"0")."',  
													 '".($selecteur_enrouleur_interieur?"1":"0")."',  
													 '".($selecteur_coffre_exterieur?"1":"0")."',  
													 '".($selecteur_coffre_interieur?"1":"0")."',  
													 '".($selecteur_film_exterieur?"1":"0")."',  
													 '".($selecteur_film_interieur?"1":"0")."',  
													 '".($selecteur5?"1":"0")."',  
													 '".($selecteur6?"1":"0")."',  
													 '".($selecteur7?"1":"0")."',  
													 '".($selecteur8?"1":"0")."',  
													 '".($selecteur9?"1":"0")."',  
													 '".($selecteur_coussin?"1":"0")."',  
													 '".($selecteur_paroi?"1":"0")."', 
													 '".($amotif_raccordable?"1":"0")."',  
													 '".($amotif_ajustable?"1":"0")."',  
													 '".($toile_FDV?"1":"0")."', 
													 ".My_sql_format(trim($couleur)).",  
													 ".My_sql_format(trim($gamme)).",  
													 ".My_sql_format($orientation).")");								
									$id_toile = db_sql::last_id ();
					         }
							}
	
	                  //******************************************
	                  // ARTICLE
	                  //******************************************
				        $id_article = "";
							$selecteur = "'".str_replace ("'","''",trim($selecteur))."'";	
						
							$temp_fam = new db_sql("select id_famille from familles where lb_famille = '".$lb_famille."'");
							$temp_fam->n();
							$id_fam = $temp_fam->f('id_famille');
							
							if ($lb_article) {
				         	if ($id_toile and !$type) $type = "surface";
								$requete="	SELECT 
												id_article
												FROM
												article
												WHERE
												lb_article = ".transcote($lb_article)." and 
												selecteur = ".$selecteur." ";
								$req = new db_sql($requete);
								if ($req->n()) {
									$id_article = $req->f('id_article');
									echo "&nbsp;&nbsp;&nbsp;Mise à jour de l'article $lb_article ( $id_article ) <BR>";
									$req = "update article set ";
									//if (isset($lb_article)) $req .= "lb_article = ".transcote($lb_article).", "; Pas besoin de maj la clee
									if (isset($lb_article_aff)) $req .= "lb_article_aff = ".transcote($lb_article_aff).", ";
									if (isset($type)) $req .= "type = '$type', ";
									//if (isset($selecteur)) $req .= "selecteur = $selecteur , ";
									if (isset($poids)) $req .= "poids = null, ";
									if (isset($laize)) $req .= "laize = ".transcote($laize*1).", ";
									if (isset($lb_toile_atelier)) $req .= "id_toile = ".transcote($id_toile).", ";
									if (isset($qt_stock)) $req .= "qt_stock = ".($qt_stock*1)." + ifnull((select sum(qt) from besoin where id_article = $id_article ),0) - ifnull((select sum(qt - qt_solde) from po_ligne where id_article = $id_article ),0) , ";
									if (isset($qt_mini)) $req .= "qt_mini = ".($qt_mini*1).", ";
									if (isset($qt_max)) $req .= "qt_max = ".($qt_max*1).", ";
									if (isset($actif)) $req .= "actif = ".($actif*1).", ";
									if (isset($delai)) $req .= "delai = ".($delai*1).", ";
									if (isset($FDV)) $req .= "FDV = ".($FDV?"1":"0").",";
									if (isset($Coef_Chute)) $req .= "coef_chute = ".($Coef_Chute*1).",";
									if (isset($lb_famille)) $req .= "id_famille = ".($id_fam*1).",";
									$req .= "id_article = ".($id_article*1)." ";
									$req .= "where id_article = $id_article"; 
									$req = new db_sql($req);
								} else {
									echo "&nbsp;&nbsp;&nbsp;Création d'un nouvel article $lb_article <BR>";
									$req = new db_sql("INSERT INTO article (
														lb_article,
														lb_article_aff,
														type, 
														selecteur, 
														poids, 
														laize, 
														id_toile, 
														qt_stock, 
														qt_mini, 
														qt_max,
														actif,
													 	FDV,
														id_famille,
														delai,
														coef_chute) 
													VALUES (
														".transcote($lb_article).",
														".transcote($lb_article_aff).",
														'$type', 
														".$selecteur.", 
														null, 
														".transcote($laize*1).", 
														".transcote($id_toile).",
														".($qt_stock*1).", 
														".($qt_mini*1).", 
														".($qt_max*1).",
														".($actif*1).",
													 	'".($FDV?"1":"0")."',
													 	".($id_fam*1).",
														".($delai*1).",
														".($Coef_Chute*1)."
														)");								
										$id_article = db_sql::last_id ();
								}
							}
							
	                  //******************************************
	                  // DONNEES FOURNISSEUR
	                  //******************************************
				         $id_fournisseur = "";
							if ($lb_fournisseur) {
					         $requete="SELECT 
					                      id_fournisseur
					                   FROM
					                      fournisseur
					                   WHERE
					                      lb_fournisseur = ".transcote($lb_fournisseur)." " ;
					         $req = new db_sql($requete);
					         if ($req->n()) {
					            $id_fournisseur = $req->f('id_fournisseur');
					         	echo "&nbsp;&nbsp;&nbsp;Mise à jour du fournisseur $lb_fournisseur <BR>";
								$req = "update fournisseur set ";
								if (isset($adresse_postale)) $req .= "adresse_postale = '".str_replace('<br>',"
	",$adresse_postale)."', ";
		  						if (isset($adresse_postale)) $req .= "adresse_mail = '".$adresse_mail."', ";
		  						if (isset($adresse_postale)) $req .= "cond_regle = '".$cond_regle."', ";
		  						if (isset($adresse_postale)) $req .= "envoi_automatique = '".$envoi_automatique."', ";
								$req .= "id_fournisseur = ".$id_fournisseur." ";
		  						$req .= "where id_fournisseur = ".$id_fournisseur ;
								$req = new db_sql($req);
												
							} else {
					         	echo "&nbsp;&nbsp;&nbsp;Création du fournisseur $lb_fournisseur <BR>";
					            $req = new db_sql("INSERT INTO fournisseur (
					            						id_fournisseur,
															lb_fournisseur,
		  													adresse_postale,
		  													adresse_mail,
		  													cond_regle,
		  													envoi_automatique) 
					            						VALUES (
															null, 
					            						".transcote($lb_fournisseur).", 
					            						'".str_replace('<br>',"
					            						",$adresse_postale)."',
					            						".transcote($adresse_mail).",
					            						".transcote($cond_regle).",
					            						'".$envoi_automatique."')");								
									$id_fournisseur = db_sql::last_id ();
					        }
						}
	                 
						//******************************************
						// DONNEES FOURNISSEUR/ARTICLES
						//******************************************
						if ($id_article and $id_fournisseur) {
							echo "&nbsp;&nbsp;&nbsp;Mise à jour des données fournisseur <BR>";
							//$req = new db_sql("update fournisseur_article set principal = null where id_article = $id_article and id_fournisseur != $id_fournisseur");
							if ($reference) {
								$req = "INSERT INTO fournisseur_article (
														id_article,
														id_fournisseur,
														principal,
														reference,
														quotite,
														prix) 
														VALUES (
															$id_article, 
															$id_fournisseur, 
															".My_sql_format($principal).",
															".My_sql_format($reference).",
															'".strtr($quotite,",",".")."',
															'".strtr($prix,",",".")."')
														on duplicate key update ";												
									if (isset($principal)) $req .= " principal = ".My_sql_format($principal).", ";
									if (isset($reference)) $req .= " reference = ".My_sql_format($reference).", ";
									if (isset($quotite)) $req .= "quotite = '".(1*strtr($quotite,",","."))."', ";
									$req .= "prix = '".(1*strtr($prix,",","."))."'";
								} else {
									$req = "delete from 
												fournisseur_article 
												where
													id_article = $id_article and
													id_fournisseur = $id_fournisseur";
								}
								$req = new db_sql($req);
						}	
						//******************************************
						// DECOTE
						//******************************************
						if ((isset($decote) and $id_article)) {
							echo "&nbsp;&nbsp;&nbsp;Mise à jour des données de décote <BR>";
							if ($decote) {
								$req = new db_sql("INSERT INTO article_propriete (
												id_article,
												propriete,
												valeur) 
												VALUES (
												$id_article, 
													'decote', 
													'".strtr($decote,",",".")."')
												on duplicate key update
													 valeur = '".strtr($decote,",",".")."'");	
							} else {	                     	
								$req = new db_sql("DELETE from article_propriete where
													id_article = $id_article and
													propriete = 'decote'");
							}
						}
						$Nb++;
					}
			   }
		?>
		   <table class="requeteur">
		      <tr>
		         <td class='requeteur' height='40' valign='middle'>Fichier traité : <? echo $Nb ?> lignes traitées</td>
		      </tr>
		   </table>
		<?
		   } else {
		      $message="Operation impossible, le fichier semble absent";
		   }
	   }
	}
}
include ("ress/enpied.php");
?>