<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");

//Déclaration fonction getProduits
function getProduits($origine){
	if($origine=="3") $where = " FDV=1";
	else if($origine) $where = " `IN`=1";
    $res = array();
    $req = new db_sql("select * from produit_origine where $where");
    while ($req->n()) {
        $res[]=$req->f('produit');
    }
    return $res;
}
//show_directive_coupe(333);
//die();
/*------ Ecran du Requêteur-------*/
//Paramètres d'appels externes
// $id_doc : Mise à jour d'un doc
// ou $type_doc : création (d'un doc de type type_doc)
//Appels internes
// les mêmes +
// ACTE
//A shooter
// $type_doc = 'fdc';
// Si j'ai un doc, je charge ses propriétés
if($dupliquer){
    $tab_prod_dup = array();
    foreach($_GET as $clee=>$valeur) {
        if (substr($clee,0,21) == "produits_a_dupliquer_") {
            $tab_prod_dup[$valeur] = 1;
        }
    }
    if (count($tab_prod_dup)) {
        if ($_GET[mode] == 'dupliquer') {
            $id_doc = dupliquer($id_doc,$tab_prod_dup);
            $dbre = new db_sql();
            $dbre->q("select zdataset_fdcentete.numcommande_fdc from zdataset_fdcentete, doc where id_doc = $id_doc and doc.id_dataset_entete = zdataset_fdcentete.id_dataset");
            $dbre->n();
            $num_cde = $dbre->f("numcommande_fdc");
            $num_cde_explode = explode("_",$num_cde);
            if (count($num_cde_explode)>1) {
                $index=$num_cde_explode[count($num_cde_explode)-1]+1;
            } else {
                $index=1;
            }
            $num_cde = $num_cde_explode[0].'_'.$index;
            $dbre->q("
                    update 
                        zdataset_fdcentete dt,
                        doc 
                    set 
                        dt.num_fdc = \"".$id_doc."\" ,
                        dt.numcommande_fdc = \"".$num_cde."\" 
                    where 
                        id_doc = $id_doc and 
                        doc.id_dataset_entete = dt.id_dataset");
        } else if ($_GET[mode] == 'modifier') {
            $dbre = new db_sql();
            delete_ofs_de_fcd($id_doc,$dbre,$tab_prod_dup);
            $dbre->q("update zdataset_fdcentete, doc set zdataset_fdcentete.statut = \"En cours\" where id_doc = $id_doc and doc.id_dataset_entete = zdataset_fdcentete.id_dataset");
        }
    } else {
        echo "<SCRIPT language=javascript>alert(\"Vous n'avez selectionné aucun produit\");</SCRIPT>";
    }
    $dupliquer ="";
}
//Si doc, charge dataset entete / enpied
if ($id_doc) {
    $doc = charge_un("select * from doc where id_doc = $id_doc");
    //list($type_doc,$id_doc_fdc) = explode("|",$doc[type_doc]);
    $type_doc = $doc[type_doc];
    $id_dataset_entete = $doc[id_dataset_entete];
    $id_dataset_empied = $doc[id_dataset_empied];
}
//Chargement des propriétés du type de doc
charge_doc($type_doc);
$type_dataset_entete	= $parms[type_docs][$type_doc][type_dataset_entete];
$type_dataset_empied	= $parms[type_docs][$type_doc][type_dataset_empied];
$titre 					= $parms[type_docs][$type_doc][libelle];
$type_dataset_ligne	= $parms[type_docs][$type_doc][type_dataset_ligne];
$list_btn 				= $parms[type_docs][$type_doc][btn];
if($ACTE==2 && $id_doc) {
	if(substr($type_doc, 0, 8) == 'COMMANDE') {
		$req_db = new db_sql("UPDATE zdataset_commandeentete SET statut_commande = 6 WHERE id_dataset = (select id_dataset_entete from doc where id_doc = ".$id_doc.")"); 
	}
}
//Crée l'objet dataset
$dataset = new dataset($type_dataset_entete);
$dbres= new db_sql();
$dbres->begin();
chk_date_vdl($id_doc);
isOfAgerer($id_doc);
if ($_POST['nonmodifiable'] && !StartsWith($_POST['type_doc'],"OF - ")) {
	$message = "";
	$liste_dataset = charge_liste_dataset();
	//Si liste de dataset
	if(is_array($liste_dataset)) {
		foreach ($liste_dataset as $key => $type) {
			if($type=="fdcentete"){
				$dataset = new dataset($type);
				$dataset->id_dataset = $key;
				$dataset->load_from_screen();
				foreach ($dataset->champs as $id_champ => $champ) {
					if($id_champ=='statut'){
						$valeur_recuperee = $_POST[$dataset->id_dataset."_champ_".$id_champ];
						if($valeur_recuperee){
							$dbres->q("replace into zdataset_fdcentete (id_dataset, ".$id_champ.") values ($dataset->id_dataset, '".str_replace("'","\'",$valeur_recuperee)."' )");
						}
					}
				}
				// Opérer les tasks (Generer OF si celui ci n'existe pas, et maj date)
				foreach ($GLOBALS[parms][task] as $key => $taskname) {
					$message = call_user_func($taskname,$id_doc,$dbres);
					if ($message)
						break;
				}
				$GLOBALS[parms][task]=array();
			}
		}
	}
	if ($message) {
		$dbres->rollback();
	} else {
		$dbres->commit();
	}
	//Charger de dataset à partir de la BDD
	$dataset->load_from_database($id_dataset_entete);
	if($dataset->nonmodifiable)
		$nonmodifiable = $dataset->nonmodifiable;
	else
		$nonmodifiable = 0;
	$onglet_courant = $onglet_apres;
//Sinon ACTE = 1
} else if ($ACTE == 1) {
	$dbres= new db_sql();
	$dbres->begin();
	$message = "";
	$liste_dataset = charge_liste_dataset();
	$liste_dataset_before_post = $liste_dataset;
	if(is_array($liste_dataset)) {
		$liste_dataset_to_save = array();
		foreach ($liste_dataset as $key => $type) {
			if(!StartsWith($_POST['type_doc'],"OF - ") || (StartsWith($_POST['type_doc'],"OF - ") && $type == $type_dataset_entete)) {
				$dt = new dataset($type);
				$dt->id_dataset = $key;
				$dt->load_from_screen();
				if(!$message){
					if($dt->type_dataset == $type_dataset_entete)
					  $dataset = $dt;
					if(!StartsWith($_POST['type_doc'],"OF - ")) {
						check_sav($dt);
						$retour = $dt->validate();
					} else if($dt->type_dataset == $type_dataset_entete)
						$retour = $dt->validate();
						if ($retour) {
							$message = $retour;
					} else {
						if(!StartsWith($_POST['type_doc'],"OF - "))
								$liste_dataset_to_save[] = $dt;
						else if($dt->type_dataset ==$type_dataset_entete)
								$liste_dataset_to_save[] = $dt;
	
					}
				}
			}
		}
		if (!$message) {
			$dbres= new db_sql();
			$dbres->begin();
			foreach ($liste_dataset_to_save as $key => $dataset_to_save) {
				if($dupliquer) $dataset_to_save->id_dataset = -1;
				$id_dataset = $dataset_to_save->id_dataset;
				//Si on a fait une suppression
				if($DELETE == 1 && $_POST['id_dataset_to_delete']==$id_dataset){
					if(StartsWith($dataset_to_save->type_dataset,"fdcproduit")){
						$lo_req = new db_sql ("select id_doc, produit from doc_ligne where id_dataset = $id_dataset");
						$lo_req->n();
						$id_doc = $lo_req->f(id_doc);
						$produit = $lo_req->f(produit);
						delete_dataset($id_dataset);
						$lo_req = new db_sql ("delete from doc_ligne where id_dataset = $id_dataset");
						// On supprime l'entete de produit s'il n'y a plus de produit
						$lo_req = new db_sql ("select 1 from doc_ligne where id_doc = $id_doc and produit = '$produit' ");
						if (!$lo_req->n()) {
							$lo_req = new db_sql ("delete from doc_groupe_ligne where id_doc = $id_doc and produit = '$produit' ");
						}
					}
				} else {
					$dataset_to_save->store($dbres);
					//Si le dataset n'existe pas encore, alors il faut ajouter son enregistrement du document
					if(!$id_dataset||$id_dataset<0) {
						if($dataset_to_save->type_dataset == $type_dataset_entete) {
							$dbres->q("insert into doc(type_doc,id_dataset_entete) values ('$type_doc', $dataset_to_save->id_dataset)");
							$id_doc = db_sql::last_id ();
							maj_num_fdc_import($id_doc, $dataset_to_save, $dbres);
						} else if(StartsWith($dataset_to_save->type_dataset,"produit")) {
							$dbres->q("insert into doc_groupe_ligne(id_doc, produit, id_dataset) values ($id_doc, '$onglet_courant', $dataset_to_save->id_dataset)");
						} else if(StartsWith($dataset_to_save->type_dataset,"fdcproduit")) {
							$dbres->q("insert into doc_ligne(id_doc, produit, id_dataset) values ($id_doc, '$onglet_courant', $dataset_to_save->id_dataset)");
						}
					}
				}
			}
			foreach ($GLOBALS[parms][task] as $key => $taskname) {
				$message = call_user_func($taskname,$id_doc,$dbres);
				if ($message)
					break;
			}
			$GLOBALS[parms][task]=array();
			if($dataset->nonmodifiable)
				$nonmodifiable = $dataset->nonmodifiable;
			else
				$nonmodifiable = 0;
      } else {
			//S'il y a erreur et passage au statut suivant, on remet le statut d'avant
			if($_POST['NEXT_STATUT']){
				 foreach ($dataset->champs as $id_champ => $champ) {
					  if($id_champ=='statut'){
							$valeur_recuperee = $_POST[$dataset->id_dataset."_champ_".$id_champ];
							$dataset->ochamp[$id_champ]->set($valeur_recuperee);
					  }
				 }
			}
      }
   }
   if ($onglet_apres && (!$message||strtolower($message)=="cancel")) {
		$onglet_courant = $onglet_apres;
		$message="";
   }
   if ($message) {
		$dbres->rollback();
		if($id_dataset_entete) $dataset->load_from_database($id_dataset_entete);
   } else {
		$dbres->commit();
   }
	if(strtolower($message)=="cancel"){
		$message="";
		$dataset->load_from_database($id_dataset_entete);
	}
	if($origine_ = $dataset->ochamp['origine']){
		$GLOBALS[origine] = $origine_->get();
	}
} else if ($id_doc) {
	
   $message="";
   //Sinon, chargement des données dans la base
   if($dupliquer)
		$dataset->ochamp['statut']->checkload = '';
   $dataset->load_from_database($id_dataset_entete);
	if($dupliquer){
		$dataset->ochamp['statut']->set('En cours');
	}
   if($dataset->ochamp[commentaire_cmd_sf]){
		$dataset->ochamp[commentaire_cmd_sf]->nonmodifiable=0;
      $dataset->ochamp[commentaire_cmd_sf]->set("");
   }
   if($dataset->nonmodifiable) $nonmodifiable = $dataset->nonmodifiable;
   if($dataset->statut_value) $statut_value = $dataset->statut_value;
   if($dataset->ochamp['origine']){
		unset($_SESSION[incompatibilite]);
   }
} else {
	$dataset->load_from_get();
}
loadIncompatibilite ();
$liste_dataset = array();
?>
<!---------------------- Affichage suivant les conditions précédentes -------------------------!-->
<body class="application">
<script type="text/javascript" src="ress/main.js"></script>
<form name="formulaire" id="formulaire" method="post" action="doc.php?ACTE=1">
    <input type="hidden" name="onglet_apres" value="">
    <input type="hidden" name="ADD" value="">
    <input type="hidden" name="NEXT_STATUT" value="">
    <input type="hidden" name="DELETE" value="">
    <input type="hidden" name="type_dataset" value="">
    <input type="hidden" name="id_dataset_to_delete" value="">
    <?
// *******************************************
// Début de l'affichage de l'entete
// *******************************************
    ?>
    <table class="cadre_application_auto">
        <tr>
            <td class="cadre_application">
                <table class="menu_haut">
                    <tr>
                        <td class="menu_haut"><? echo $titre?></td>
                        <?
                        // Affichage des boutons
                        // ------------------------------------------
                        if($id_doc){
                            if($list_btn && is_array($list_btn)) {
                                foreach ($list_btn as $i => $btn) {
                                    if(strpos($btn[php],'id_doc_fdc')!==false){
                                        $id_doc_fdc=$dataset->ochamp['fdc']->get();
                                        $btn[php]=str_replace(". id_doc_fdc","$id_doc_fdc",$btn[php],$nb) ;
                                        if($req_btn_update == 1){
                                            $req = new db_sql("UPDATE `zdataset_commandeentete` SET `statut_commande` = '4' WHERE `id_dataset` = ".$doc[id_dataset_entete]);
                                            $req->commit();
														  echo "<script type='text/javascript'>document.location.replace('doc.php?id_doc=$id_doc&champ_ofclient=$id_doc'); </script>";
                                        }
                                    }
                                    if(strpos($btn[php],'id_affaire')!==false){
                                        $id_affaire=$dataset->ochamp['affaire']->get();
                                        $btn[php]=str_replace(". id_affaire","$id_affaire",$btn[php],$nb) ;
                                    }
                                    $btn[php]=str_replace(". id_doc","$id_doc",$btn[php],$nb);
                                    $affiche_le_bouton = true;
                                    if($btn[droit]) {
                                        eval(' if (!('.$btn[droit].')) $affiche_le_bouton = false; ');
                                    }
                                    if ($affiche_le_bouton) {
                                        ?>
                                        <td class='externe'><A <? echo $btn[complement_dedans]?> class='externe' href="<? echo $btn[php]?>" onclick = "<? echo $btn[onclick]?>"><? echo $btn[libelle]?></A></td>
                                        <?
                                        if ($btn[complement_dehors]) $add_on_html[] = $btn[complement_dehors];
                                    }
                                }
                            }
                        }
                        ?>
                    </tr>
					</table>
					<input type="hidden" name="liste_dataset[<? echo $dataset->id_dataset*1 ?>]" value="<? echo $type_dataset_entete ?>">
               <?
					
               // Des les feuilles de cote,
               // si le statut a une valeur pour lequel le user à le droit de modifier le profil,
               // alors on débloque le champ... et le bouton enregistrer plus loin.
               if (($dataset->type_dataset=="fdcentete") and ($dataset->nonmodifiable==1)) {
						$champs['statut'] = charge_champ('statut');
						if (droit_feuille_de_cote(getFieldValue('statut',$dataset))>=1) {
							$droit_modifier_le_statut = true;
							$dataset->ochamp[statut]->nonmodifiable = 0;
						}
               }
               // en création, une commande est au statut "En cours" obligatoirement
                if (($dataset->type_dataset=="fdcentete") and (!$id_doc)) {
                    $dataset->ochamp[statut]->valeurs = array("En cours" => "En cours");
               }
               
					// Affichage des champs de l'entête
               // ------------------------------------------
               echo($dataset->html());
               ?>
			</td>
		</tr>
	</table>
	<?
// *******************************************
// Fin de l'affichage de l'entete
// *******************************************
// *******************************************
// Début de l'affichage des onglets
// *******************************************
    if ($id_doc) {
        $statut_en_cours = getFieldValue('statut',$dataset);
        ?>
        <table class="requeteur_auto">
        <tr>
        <?
        if(!$type_dataset_ligne) {
            //Création de la liste des onglets
            //Produits déjà sélectionné
            $produits_deja_utilises = charge("select distinct produit from doc_ligne where id_doc = $id_doc");
            foreach ($produits_deja_utilises as $i => $onglet) {
                $onglets[$onglet[produit]][php]     = "doc_ligne.php";
                require("param_2_produits/".$onglet[produit].".php");
                //$onglets[$onglet[produit]][libelle] = $GLOBALS[parms][produits][$onglet[produit]][libelle];
                $onglets[$onglet[produit]][libelle] = $produits[$onglet[produit]][libelle];
                if (!$onglet_courant) $onglet_courant = $onglets[$onglet[produit]][libelle];
            }
            //Nouveau éventuel produit
            if ($onglet_courant != "Empied") {
                $onglets[$onglet_courant][php]     = "doc_ligne.php";
                //$onglets[$onglet_courant][libelle] = $GLOBALS[parms][produits][$onglet_courant][libelle];
                if($onglet_courant){
                    if($onglet_courant!="of")
                        require("param_2_produits/".$onglet_courant.".php");
                    $onglets[$onglet_courant][libelle] = $produits[$onglet_courant][libelle];
                }
            }
            if(is_document_lie_cree($id_doc) && !$dupliquer){
                //Liste des OF
                $onglets[of][php] = "fdc_detail_of_list.php";
                $onglets[of][libelle] = "Documents liés";
            }
            //Création de la liste de produits non déjà en onglets
            $onglets_manquant_adresse = "";
            $onglets_manquant_libelle = "Choisir";
            $allproduits = getProduits($origine);
            foreach ($allproduits as $i => $produit) {
                $fichier = $produit.".php";
                require("./param_2_produits/".$fichier);
                $infos_produit = $produits[$produit];
                if (!array_key_exists ($produit,$onglets)) {
                    $onglets_manquant_adresse .= "|".$produit;
                    $onglets_manquant_libelle .= "|".$infos_produit[libelle];
                }
            }
            //Affichage des onglets
            if($onglet_courant){
                foreach ($onglets as $onglet => $infos_onglet) {
                    if ($onglet == $onglet_courant) {
                        echo "\n  <td class=\"interne_actif\">".$infos_onglet[libelle]."</td>";
                        $adresse_courante = $adresse;
                    } else {
                        echo "\n  <td class=\"interne\"><a class=\"interne\" href=\"#\" onclick=\"document.forms['formulaire'].onglet_apres.value='".$onglet."';document.forms['formulaire'].submit();\">".$infos_onglet[libelle]."</A></td>";
                    }
                }
            }
            //Affichage de la liste produits non encore en onglet
            if ($onglets_manquant_adresse && !$nonmodifiable) {
                echo "\n  <td class=\"interne_actif\">Ajouter produit";
                drop_down_droit ("L","","onglet_courant", "", "", "", $immediat=true, $page="doc.php?id_doc=". $id_doc, $fg_oblig="N", $onglets_manquant_adresse, $onglets_manquant_libelle);
                echo "</td>";
            }
// *******************************************
// Fin de l'affichage des onglets
// *******************************************
// *******************************************
// Début de l'affichage des lignes
// *******************************************
            ?>
            </tr>
            </table>
            <?
            //S'il existe un OF sur le produit, l'onglet n'est pas modifiable
            $lignenonmodifiable = $nonmodifiable;
            $req = new db_sql("
					SELECT
						1
					FROM
						zdataset_ofentete
					WHERE
						zdataset_ofentete.fdc = $id_doc and
						zdataset_ofentete.produit_entete_of = '$onglet_courant'");
            if($req->n()) {
				$lignenonmodifiable = true;
            }
            // la variable $onglet_courant est importante : elle contient le produit
            if($onglet_courant) {
               include ($onglets[$onglet_courant][php]);
            }
		} else {
            $onglet_courant='-';
            include("doc_ligne.php");
		}
    }
// *******************************************
// Fin de l'affichage des lignes
// *******************************************
// *******************************************
// Affichage de l'empied
// *******************************************
	if ($onglet_courant == '-' or $onglets[$onglet_courant][php] == "doc_ligne.php") {
		echo($dataset->html_empied());
		if(StartsWith($type_doc,"OF - ") && droit_utilisateur("OF")) {
			?>
			<input class="requeteur_button" type="submit" name="Submit" value="Enregistrer" >
			<input class="requeteur_button" type="button" name="Annuler" value="Annuler" onclick="document.location.href='doc.php?id_doc=<? echo $id_doc; ?>';" >
			<?
		}
		if($type_dataset_empied) include("doc_empied.php");
	}
	if((!$type_dataset_ligne && !$nonmodifiable) or ($droit_modifier_le_statut)){
		?><input class="requeteur_button" type="submit" name="Submit" value="Enregistrer" ><?
		if ($id_doc) {
			?><input class="requeteur_button" type="button" name="Annuler" value="Annuler" onclick="document.location.href='doc.php?id_doc=<? echo $id_doc; ?>';" ><?
		}
	}
    ?>
	<input type="hidden" name="type_doc" value="<? echo $type_doc; ?>">
	<input type="hidden" name="id_doc" value="<? echo $id_doc; ?>">
	<input type="hidden" name="dupliquer" value="<? echo $dupliquer; ?>">
</form>
<script>
	function email_commentaire() {
		// Recupération du commentaie saisi
		var commentaire_saisie = $( "textarea[name*='commentaire_cmd_sf']" ).val();
		param = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(location.href);
			return results[1] || 0;
		};
		var id_doc = param("id_doc");
		window.open('commande_achat_sf_mail.php?ACTE=1&id_doc='+id_doc+'&commentaire='+commentaire_saisie);
	}
    $(document).ready(function(){
		$('#formulaire .automatisme').each(function() {
			if($(this).val()){
				maj($(this), 'event');
			}
		});
		$('#formulaire').on('submit', function(e) {
            var err = '';
			$('#formulaire .automatisme').each(function() {
                if($(this).val()){
                    var ret = maj($(this), 'event');
                    if(ret){
						err = ret;
                    }
                }
            });
			if (err) {
				alert(err);
			    e.preventDefault();
                return false;
			} else {
			//$(":submit").each(function(){alert ('1')});
				$(":submit").remove();
				$(":button").remove();
			//$(":submit").prop(disabled, true).addClass(ui-state-disabled);
			}
        });
    });
	function maj(champ, mode) {
		var incompatibilites = [],
			incompatibilites_limites = [],
			incompatibilites_liste = [],
			incompatibilites_surface = [],
			incompatibilites_rapport = [],
			ret='';
		var current_product = $('#current_product').val();
		var name_declancheur = champ.prop('name');
		var lo_pos = name_declancheur.indexOf('_');
		var dataset = name_declancheur.substr(0,lo_pos);
		var nom_champ = name_declancheur.substr(lo_pos+7);
		var valeur_courante = champ.val();
		if (typeof $(champ).attr('name') !== 'undefined' ) {
				var field = $(champ).attr('name').replace('_champ_', '');
            field = nom_champ;
            <? if (count($_SESSION[incompatibilite])) { ?>
            var tmpIncompatibilite = <?php echo json_encode($_SESSION[incompatibilite]); ?>;
            for(var i=0;i<tmpIncompatibilite.length;i++){
                if(tmpIncompatibilite[i][0]==current_product && (tmpIncompatibilite[i][1]==field)){
                    array_push = [tmpIncompatibilite[i][1], tmpIncompatibilite[i][2], tmpIncompatibilite[i][3], tmpIncompatibilite[i][4]];
                    incompatibilites.push(array_push);
                }
            }
            var a_supprimer = new Object();
            var a_verifier = new Object();
            // Création d'un tableau des ajouts et des suppressions
            incompatibilites.forEach(function(incompatibilite) {
                if (incompatibilite[0] == nom_champ) {
                    if (incompatibilite[1] == valeur_courante) {
                        if (!a_supprimer[incompatibilite[2]]) {a_supprimer[incompatibilite[2]] = new Object}
                        a_supprimer[incompatibilite[2]][incompatibilite[3]] = 1;
                    } else {
                        if (!a_verifier[incompatibilite[2]]) {a_verifier[incompatibilite[2]] = new Object}
                        //a_verifier[incompatibilite[2]][incompatibilite[3]] = 1;
                    }
                }
            });
            // Gestion des ajouts d'abord (car les suppressions priment)
            for(var champ_a in a_verifier) {
                var nomF = dataset+'_champ_'+champ_a;
                var sel_cour = $("[name='"+nomF+"']");
                var valeur_actuelle = sel_cour.val();
                sel_cour.html(sel_cour.prev().html());
                valeur_actuelle = sel_cour.val(valeur_actuelle);
            }
            // Gestion des suppressions
            for(var champ_a in a_supprimer) {
					var valeur_actuelle = $("[name='"+dataset+"_champ_"+champ_a+"']").val();
					//console.log (a_supprimer[champ_a]);
					for(var valeur_a in a_supprimer[champ_a]) {
						//console.log ("je dois supprimer :".valeur_a);
						//console.log (dataset+'_champ_'+champ_a);
						var trouve = false;
						var champai = document.getElementsByName (dataset+'_champ_'+champ_a)[0];
						if (valeur_a == '*') {
							champai.options.length=0;
							valeur_actuelle = "disparu";
						}
						if ((valeur_actuelle == valeur_a) && (mode == 'event')) {
							valeur_actuelle = "disparu";
						}
						for (i = 0; i < Number(champai.length); i++){
							if ((champai.options[i].value == valeur_a) || (trouve == true)) {
								 trouve = true;
								 if (!(i==Number(champai.length)-1)) {
									  champai.options[i] = new Option((champai.options[ Number(i+1) ].text),(champai.options[ Number(i+1) ].value));
								 }
							}
						}
						if (trouve == true) {
							champai.options.length--;
						}
					}
					if (valeur_actuelle!="disparu") {
						$("[name='"+dataset+"_champ_"+champ_a+"']").val(valeur_actuelle); // Les ajouts aussi
					}
            }
            <? } ?>
            <? if (count($_SESSION[incompatibilites_limites])) { ?>
            tmpIncompatibilite = <?php echo json_encode($_SESSION[incompatibilites_limites]); ?>;
            for(var i=0;i<tmpIncompatibilite.length;i++){
                if(tmpIncompatibilite[i][0]==current_product && (tmpIncompatibilite[i][1]==field || tmpIncompatibilite[i][3]==field)){
                    array_push = [tmpIncompatibilite[i][1], tmpIncompatibilite[i][2], tmpIncompatibilite[i][3], tmpIncompatibilite[i][4], tmpIncompatibilite[i][5]];
                    incompatibilites_limites.push(array_push);
                }
            }
            incompatibilites_limites.forEach(function(incompatibilite) {
                if ( (incompatibilite[0] == nom_champ && incompatibilite[1] == valeur_courante) ||
                    incompatibilite[1] == '*' ||
                    incompatibilite[0] == '*' ||
                    (incompatibilite[2] == nom_champ && incompatibilite[1] == $("[name='"+dataset+"_champ_"+incompatibilite[0]+"']").val()) // En train de changer le champs controlé et le déclancheur est OK
                ) {
                    var valeur_actuelle = $("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").val();
                    if (valeur_actuelle != '') {
                        if (!$.isNumeric(valeur_actuelle)){
                            ret = 'La valeur de '+incompatibilite[2]+' n est pas numérique';
                        } 
						else if (incompatibilite[3]!='' && parseInt(incompatibilite[3])>parseInt(valeur_actuelle)){
                            ret = 'La valeur mini pour '+incompatibilite[2]+' est '+incompatibilite[3];
                        }
                        else if (incompatibilite[4]!='' && parseInt(incompatibilite[4])<parseInt(valeur_actuelle)){
                            ret = 'La valeur maxi pour '+incompatibilite[2]+' est '+incompatibilite[4];
                        }
                    }
                }
            });
            <? } ?>
            <? if (count($_SESSION[incompatibilites_liste])) { ?>
            tmpIncompatibilite = <?php echo json_encode($_SESSION[incompatibilites_liste]); ?>;
            for(var i=0;i<tmpIncompatibilite.length;i++){
                if(tmpIncompatibilite[i][0]==current_product && (tmpIncompatibilite[i][1]==field)){
                    array_push = [tmpIncompatibilite[i][1], tmpIncompatibilite[i][2], tmpIncompatibilite[i][3], tmpIncompatibilite[i][4]];
                    incompatibilites_liste.push(array_push);
                }
            }
            var liste=[],trouve=false;
            incompatibilites_liste.forEach(function(incompatibilite) {
                if ( (incompatibilite[0] == nom_champ && incompatibilite[1] == valeur_courante) ||
                    incompatibilite[1] == '*' ||
                    incompatibilite[0] == '*'
                ) {
							trouve=true;
							var id = $("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('id'),
								name=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('name'),
								size=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('size'),
								value=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").val(),
								maxlength=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('maxlength'),
								style=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('style');
							if ($("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").is('select')) {
								size=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('dsize');
								maxlength=$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").attr('dmaxlength');
							}
							var select = $("<select id='"+id+"' name='"+name+"' style='"+style+"' dsize='"+size+"' dmaxlength='"+maxlength+"'/>");
							tab = incompatibilite[3].split(',');
							for(var i=0;i<tab.length;i++){
                        if(tab[i]!='') select.append($('<option></option>').val(tab[i]).html(tab[i]));
							}
							var parent = $("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").parent();
							$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").remove();
							parent.append(select);
							$("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").val(value);
							var i = liste.indexOf(incompatibilite[2]);
							if (i != -1) {
                        liste.splice(i, 1);
							}
                } else if (liste.indexOf(incompatibilite[2]) <= -1 && !trouve) {
                    liste.push(incompatibilite[2]);
                }
            });
			//Liste contient la liste des champs select à repasser en champ ouvert
            for(var i=0;i<liste.length;i++){
					if($("[name='"+dataset+"_champ_"+liste[i]+"']").prop('tagName').toLowerCase()=='select'){
						var id = $("[name='"+dataset+"_champ_"+liste[i]+"']").attr('id'),
						name=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('name'),
						size=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('size'),
						maxlength=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('maxlength'),
						value=$("[name='"+dataset+"_champ_"+liste[i]+"']").val(),
						style=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('style');
						if ($("[name='"+dataset+"_champ_"+liste[i]+"']").is('select')) {
							size=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('dsize');
							maxlength=$("[name='"+dataset+"_champ_"+liste[i]+"']").attr('dmaxlength');
						}
						//if(typeof(value)  !== 'undefined') value=0;
						var input = $("<input type='text' type='automatisme' id='"+id+"' name='"+name+"' style='"+style+"' size='"+size+"' maxlength='"+maxlength+"' value='' />");
						var parent = $("[name='"+dataset+"_champ_"+liste[i]+"']").parent();
						$("[name='"+dataset+"_champ_"+liste[i]+"']").remove();
						parent.append(input);
					}
            }
            <? } ?>
            <? if (count($_SESSION[incompatibilites_surface])) { ?>
            tmpIncompatibilite = <?php echo json_encode($_SESSION[incompatibilites_surface]); ?>;
            for(var i=0;i<tmpIncompatibilite.length;i++){
                if(tmpIncompatibilite[i][0]==current_product && (tmpIncompatibilite[i][1]==field || tmpIncompatibilite[i][3]==field || tmpIncompatibilite[i][4]==field)){
                    array_push = [tmpIncompatibilite[i][1], tmpIncompatibilite[i][2], tmpIncompatibilite[i][3], tmpIncompatibilite[i][4], tmpIncompatibilite[i][5], tmpIncompatibilite[i][6]];
                    incompatibilites_surface.push(array_push);
                }
            }
            incompatibilites_surface.forEach(function(incompatibilite) {
                if ( (incompatibilite[0] == nom_champ && incompatibilite[1] == valeur_courante) ||
                    incompatibilite[1] == '*' ||
                    incompatibilite[0] == '*' ||
                    (incompatibilite[2] == nom_champ && incompatibilite[1] == $("[name='"+dataset+"_champ_"+incompatibilite[0]+"']").val()) || // En train de changer le champs controlé et le déclancheur est OK
                    (incompatibilite[3] == nom_champ && incompatibilite[1] == $("[name='"+dataset+"_champ_"+incompatibilite[0]+"']").val()) // En train de changer le champs controlé et le déclancheur est OK
                ) {
                    var dim1 = $("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").val(),dim2 = $("[name='"+dataset+"_champ_"+incompatibilite[3]+"']").val();
                    if(dim1!='' && dim2!=''){
                        if(incompatibilite[4]!='' && parseInt(dim1) * parseInt(dim2)/1000000<parseInt(incompatibilite[4])){
                            ret = 'La surface mini est '+Math.round(incompatibilite[4]*10)/10+' m2';
                        }
                        else if(incompatibilite[5]!='' && parseInt(dim1) * parseInt(dim2)/1000000>parseInt(incompatibilite[5])){
                            ret = 'La surface maxi est '+Math.round(incompatibilite[5]*10)/10+' m2';
                        }
                    }
                }
            });
            <? } ?>
            <? if (count($_SESSION[incompatibilites_rapport])) { ?>
            tmpIncompatibilite = <?php echo json_encode($_SESSION[incompatibilites_rapport]); ?>;
            for(var i=0;i<tmpIncompatibilite.length;i++){
                if(tmpIncompatibilite[i][0]==current_product && (tmpIncompatibilite[i][1]==field || tmpIncompatibilite[i][3]==field || tmpIncompatibilite[i][4]==field)){
                    array_push = [tmpIncompatibilite[i][1], tmpIncompatibilite[i][2], tmpIncompatibilite[i][3], tmpIncompatibilite[i][4], tmpIncompatibilite[i][5], tmpIncompatibilite[i][6]];
                    incompatibilites_rapport.push(array_push);
                }
            }
            trouve= false;
            incompatibilites_rapport.forEach(function(incompatibilite) {
                if ( (incompatibilite[0] == nom_champ && incompatibilite[1] == valeur_courante) ||
                    incompatibilite[1] == '*' ||
                    incompatibilite[0] == '*' ||
                    (incompatibilite[2] == nom_champ && incompatibilite[1] == $("[name='"+dataset+"_champ_"+incompatibilite[0]+"']").val()) || // En train de changer le champs controlé et le déclancheur est OK
                    (incompatibilite[3] == nom_champ && incompatibilite[1] == $("[name='"+dataset+"_champ_"+incompatibilite[0]+"']").val()) // En train de changer le champs controlé et le déclancheur est OK
                ) {
                    var dim1 = $("[name='"+dataset+"_champ_"+incompatibilite[2]+"']").val(),dim2 = $("[name='"+dataset+"_champ_"+incompatibilite[3]+"']").val();
                    if(dim1!='' && dim2!=''){
                        if(incompatibilite[4]!='' && parseInt(dim1) <Math.round(parseInt(incompatibilite[4])*parseInt(dim2)/100)){
                            ret = 'La valeur mini de '+incompatibilite[2]+' est '+Math.round(parseInt(incompatibilite[4])*parseInt(dim2)/100);
                        }
                        else if(incompatibilite[5]!='' && parseInt(dim1)>Math.round(parseInt(incompatibilite[5])*parseInt(dim2)/100)){
                            ret = 'La valeur maxi de '+incompatibilite[2]+' est '+Math.round(parseInt(incompatibilite[5])*parseInt(dim2)/100);
                        }
                    }
                }
            });
            <? } ?>
        }
        return ret;
    }
    function relance(title ,forcer) {
        $('#formulaire').attr('action',$('#formulaire').attr('action')+"&forcer="+forcer);
        $("select[title='Saisir le statut de la feuille de côte']").val("A produire");
        document.forms[0].submit();
    };
    function alert_statut_fdc(valeur) {
        if($(valeur).val() == "Validée"){
            $( "#dialog_fdc" ).attr('style', 'visibility: visible');
            $( "#dialog_fdc" ).dialog();
				$( "#dialog_fdc_ok" ).click(function() {
					$( "#dialog_fdc" ).dialog("close");	
					document.forms[0].submit();
				});
        }else {
            document.forms[0].submit();
        }
    }
	 
</script>
<div id="dialog_fdc" title="Confirmation" style="visibility: hidden">
	<p>Avez-vous bien relu votre feuille de cotes ?</p>
	<input id="dialog_fdc_ok" type="button" value="Oui">
</div>
<?
if (is_array($add_on_html)) {
	foreach($add_on_html as $x=>$include) {
		include_once($include);
	}
}
include ("ress/enpied.php");
?>
