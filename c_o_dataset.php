<?
require_once("fonction.php");

function charge($requete, &$req = 0) {
	if (!$req) $req = new db_sql();
	$req->q($requete);
	$reponse = array();
	while($req->n()){
		$reponse[] = $req->Record;
	}
	return $reponse;
}

function charge_un($requete, &$req = 0) {
	$table = charge($requete, $req);
	return $table[0];
}

require_once("c_parm.php");

class champ {
	public $non_pdf; // N'apparait pas dans le PDF
	//public $toupdate;
	public $id_champ;
	public $lb_champ;
	public $lb_champ_pdf;
	public $id_type_champ;
	public $nb_longueur_champ;
	public $obligatoire;
	public $valeurs = '' ;
	public $valeurs_init = array(); // Besoin interne
	public $lov_request; // Indique pour une lov, s'il faut aller chercher dans la table générique ou dans tablecible, champstocke, champscible
	public $tablecible;
	public $champsite;
	public $champstocke;
	public $champscible; // Champ de la requete à afficher qu'il s'agisse d'un groupe lookup ou d'un lov
	public $nonmodifiable;
	public $rowspan;
	public $colspan;
	public $attribut_html;
	private $valeur;
	public $id;
	public $id_unique_ajax;
	public $startligne; // Indique qu'on a une nouvelle ligne lors de l'impression en PDF (ou affichage)
	public $largeur;
	public $hauteur;
	public $checkvalidate; // Fonction de validation du champ
	public $checkload; // Indique une fonction à exécuter au chargement. Exécute le CODE renvoyé par cette procédure
	public $filtrer_valeurs; // Filtre les valeurs d'une LOV à travers la fonction dont le nom est $filtrer_valeurs
	public $non_html;
	public $rows;
	public $cols;
	public $fonction; // Fonction appelée lors de l'affichage et l'impression d'un champ. Dans ce cas, il faut passer tout l'objet champ. Champcible peut être utilise s'il y a une requête. On affiche directement le résultat 
	public $pdf_fontsize;
	public $pdf_fontsize_entete;
	public $pdf_cadre;
	public $defaultvalue;  
	public $id_dataset;
	public $title;
	public $href; // Indique l'image en infobulle
	//public $notapply; 
	public $verifZeroNonsignif; // Enlève les 0 non significatif à l'affichage
	// public $getprevious;
	public $datalookup = array(); // Fonctionne avec le groupelookup du dataset : sert à stocker les valeurs des champs ramenés par la requete exécuté via grouplookup pour récupération lors de l'affichage (car champs virtuels non stockés dans la base)
	public $ispersisted; // Marque une exception au groupelookup : Ce champ est stocké dans la base tout de même, donc n'est pas virtuel
	public $padding; // Nombre de 0 après la virgule
	public $formule_import; // Marque les champs qui sont x par 10 à l'import
	public $origines_invisibles; // Liste les origines pour les quels ce champ ne doit pas être considéré

	function __construct($id_champ) {

		$this->id_champ	= $id_champ;
		// Charge les proprietes du champ
		$champ = charge_champ($id_champ);
		$this->lb_champ				= $champ[libelle];
		$this->lb_champ_pdf			= strtoupper($champ[libelle]);
		$this->id_type_champ			= $champ[type];
		$this->nb_longueur_champ	= $champ[longueur];
		$this->verifZeroNonsignif	= $champ[verifZeroNonsignif];
		$this->champstocke			= $champ[champstocke];
		$this->tablecible				= $champ[tablecible];
		$this->champscible			= $champ[champscible];
		$this->champssite				= $champ[champssite]; // Champ utilisé pour recoller avec le site
		$this->nonmodifiable			= $champ[nonmodifiable];
		$this->rowspan					= $champ[rowspan];
		$this->colspan					= $champ[colspan];
		$this->startligne				= $champ[startligne];
		if ($champ[largeur])
			$this->largeur				= $champ[largeur];
		else
			$this->largeur				= 1; // Valeur par defaut
		if ($champ[hauteur])
			$this->hauteur				= $champ[hauteur];
		else
			$this->hauteur				= 1; // Valeur par defaut
		$this->checkvalidate		= $champ[checkvalidate];
		$this->checkload			= $champ[checkload]; //controle le champ statut lors du chargement.
		$this->filtrer_valeurs	= $champ[filtrer_valeurs];
		$this->defaultvalue		= $champ[defaultvalue];
		$this->rows					= $champ[rows];
		$this->cols					= $champ[cols];
		$this->title				= $champ[title];
		$this->href					= $champ[href];
		$this->ispersisted		= $champ[ispersisted];
		$this->isnumeric			= $champ[isnumeric];
		// $this->notapply			= $champ[notapply];
		// $this->getprevious		= $champ[getprevious];
		$this->fonction			= $champ[fonction];
		$this->obligatoire		= $champ[obligatoire];
		$this->non_html			= $champ[non_html];
		if ($this->non_html)
			$this->obligatoire	= false;
		$this->attribut_html		= $champ[attribut_html];
		$this->non_pdf				= $champ[non_pdf];
		//$this->toupdate			= $champ[toupdate];
		$this->formule_import	= $champ[formule_import];
		$this->pdf_fontsize		= $champ[pdf_fontsize];
		$this->pdf_fontsize_entete = $champ[pdf_fontsize_entete];
		$this->pdf_cadre			= $champ[pdf_cadre];
		$this->id_unique_ajax		= get_unique_id_ajax();
		$this->id = 'champ_'.$this->id_unique_ajax.'_'.$id_champ;
		$this->origines_invisibles = $champ[origines_invisibles];
		$this->lov_request			= $champ[lov_request];

		if ($GLOBALS[origine]=="3") $where = " and FDV=1";
		else if ($GLOBALS[origine]) $where = " and `IN`=1";
		
		// Charge les valeurs (seulement s'il s'agit d'une liste de valeurs)
		if ($this->id_type_champ == 'lov') {
			$this->valeurs = array();
			if($this->lov_request==1) {
				$req = new db_sql("select " . $this->champscible . " , " . $this->champstocke . " from " . $this->tablecible);
				while ($req->n()) {
					$this->valeurs[$req->f($this->champstocke)] = $req->f($this->champscible);
				}
			} else {
				$req = new db_sql("select * from champ_lov_valeurs where field = '$this->id_champ' $where order by id_data");
				while ($req->n()) {
					$this->valeurs[$req->f('valeur_stockee')] = $req->f('valeur_affichee');
				}
				if ($this->tablecible and ($this->id_type_champ == 'lov')) {
					$req = new db_sql($this->tablecible . " $where");
					while ($req->n()) {
						$this->valeurs[$req->f($this->champstocke)] = $req->f($this->champscible);
					}
				}
				$this->valeurs_init = $this->valeurs;
			}
			// S'il y a * (champ ouvert), on la met à la fin.
			if ($lo_champ_couvert = $this->valeurs["*"]) {
				unset ($this->valeurs["*"]);
				$this->valeurs["*"] = $lo_champ_couvert;
			}
		}
		// Transformation d'un binaire en liste de valeurs
		if ($this->id_type_champ == 'bin') {
			$this->valeurs = array(1 => 'Oui', 0 => 'Non');
			$this->id_type_champ = 'lov';
		}

		// Chargement des toiles
		if ($this->id_type_champ == 'toile') {
			$this->valeurs = array();
			$req = new db_sql('select distinct gamme from toile where '.$this->champstocke.'=1 '.$where);
			while ($req->n()) {
				$this->valeurs[$req->f('gamme')] = $req->f('gamme');
			}
			//$this->valeurs['*'] = 'Autre';
		}

		// Affectation de la valeur par défaut
		if (!$this->defaultvalue) {
			$this->defaultvalue = "";
		}
		// Ne pas affecter la valeur par défaut si elle n'est pas dans la liste
		if (!is_array($this->valeurs) or $this->valeurs[$this->defaultvalue]) {
			$this->set($this->defaultvalue);
		} else {
			$this->set("");
		}
	}

	function set($valeur) {
		$this->valeur = $valeur;
		if ($this->id_champ == 'origine'){
			$GLOBALS[origine] = $this->valeur;
		}
		if ($this->id_champ == 'prestation'){
			$GLOBALS[prestation] = $this->valeur;
		}
		if ($GLOBALS[origine] and strpos($this->origines_invisibles, $GLOBALS[origine])){
			$this->non_html = 1;
			$this->set_obligatoire(false);
		}

		// Vérifie entête non oblig
		if ($this->id_champ == 'prestation' and $this->valeur == 'Fourniture seule'){
			$GLOBALS[type_prest] = 1;
		}
		
	}

	function get() {
		return($this->valeur);
	}

	function set_obligatoire($obligatoire) {
		if ($this->non_html)
			$obligatoire = false;
		$this->obligatoire = $obligatoire;
	}

	function get_obligatoire() {
		return $this->obligatoire;
	}

	function get_stylevide() {
		if($this->get()!="") {
			return "";
		} else if($this->obligatoire) {
			return("style='background-color:#FF8C8C'");
		} else {
			return "";
		}
	}

	function getPrintValue() {
		if($this->fonction) $this->valeur=call_user_func($this->fonction,$this);
		if($this->verifZeroNonsignif && existeZeroNonsignif($this->valeur)) $this->valeur = round($this->valeur,0) ;
		if($this->padding) {
			if(is_numeric($this->valeur) && strpos($this->valeur,'.') == true) {
				$this->valeur = str_pad ($this->valeur , strlen($this->valeur)+$this->padding - (strlen($this->valeur)-strpos($this->valeur,'.'))+1 , "0" );
			}
		}
		if ($this->tablecible and ($this->id_type_champ == 'lov') and (!$this->fonction)) {
			return $this->valeurs[$this->valeur];
		}
		return $this->valeur;
	}

	function get_modifiable() {
		if ($this->nonmodifiable==1) {
			return " style=\" background-color:#BBBBBB\" readonly ";
		} else {
			return "";
		}
	}

	function load_from_screen() {
		$valeur_recuperee = $_POST[$this->id_dataset."_champ_".$this->id_champ];
		if($this->id_champ=='statut' && $_POST['NEXT_STATUT']){
				$valeur_recuperee = get_next_statut($valeur_recuperee);
		}
		if ($this->isnumeric) {
			$valeur_recuperee = str_replace(",",".",$valeur_recuperee);
			$valeur_recuperee = str_replace(" ","",$valeur_recuperee);
		}
		$this->set($valeur_recuperee);
		if ($this->id_type_champ == "date") {
				$this->set($_POST["date_".$this->id_dataset."_champ_".$this->id_champ]);
		} else if ($this->id_type_champ == "toile") {
				$this->set($valeur_recuperee."|".$_POST[$this->id_dataset."_champ_ouvert_".$this->id_champ]);
		} else if ($this->id_type_champ == "lov") {
				if ($valeur_recuperee=="*") {
					$valeur_recuperee = $_POST[$this->id_dataset."_champ_ouvert_".$this->id_champ];
					$this->set($valeur_recuperee);
				}
		} else if ($this->id_type_champ == "checkbox") {
				if(is_array($_POST[$_POST[$this->id_dataset."_listechamp_".$this->id_champ]])) {
					$valeur_recuperee = implode('|',$_POST[$_POST[$this->id_dataset."_listechamp_".$this->id_champ]]);
					$this->set($valeur_recuperee);
				}
				else $this->set('');
		}
	}

	function load_from_get() {
		$valeur_recuperee = $_GET["champ_".$this->id_champ];
		if(isset($valeur_recuperee)) {
				$this->set($valeur_recuperee);
		}
	}

	function affiche() {
		switch ($this->id_type_champ) {
				case "groupelookup":
					foreach ($this->datalookup as $id_lookup=>$resultat) {
						if($id_lookup==$this->tablecible){
								return $resultat[strtolower($this->champscible)];
						}
					}
					break;
				case "lov":
					return ($this->valeurs_init[$this->valeur]);
					break;
				default:
					if($this->fonction)
						return (call_user_func($this->fonction,$this));
					return $this->valeur;
		}
	}

	function html() {
		if ($GLOBALS[origine] and strpos($this->origines_invisibles, $GLOBALS[origine])){
			$this->non_html = 1;
		}
		$l_nom_champ = 'name="'.$this->id_dataset.'_champ_'.$this->id_champ.'"';
		if($this->non_html){
			$html ="<input type='hidden' $l_nom_champ value=\"$this->valeur\">";
			return $html;
		}

		switch ($this->id_type_champ) {
		
				case "groupelookup":
				
					//Si l'affichage ne provient pas de l'appel de load_from_screen
					if($this->datalookup){
						foreach ($this->datalookup as $id_lookup=>$resultat) {
								if($id_lookup==$this->tablecible){
									$valeur = $resultat[strtolower($this->champscible)];
									if(!$this->ispersisted)$this->valeur=$valeur;
									$html .="<input type=text ".$this->get_modifiable()." value=\"".$valeur."\"><input type='hidden' name=\"".$this->id_dataset."_champ_$this->id_champ\" value=\"$this->valeur\">";
								}
						}
					} else {
						if($this->ispersisted) {
							$resultat = charge_un("select " . $this->champscible . " from ". $this->tablecible . " where " . $this->champstocke . "='$this->valeur'");
							$valeur = $resultat[strtolower($this->champscible)];
						} else
							$valeur =$this->valeur;
						$html .="<input type=text ".$this->get_modifiable()." value=\"".$valeur."\"><input type='hidden' name=\"".$this->id_dataset."_champ_$this->id_champ\" value=\"$this->valeur\">";
					}
					break;
				
				case "lookup":
				
					$html .="<input type='hidden' name=\"".$this->id_dataset."_champ_$this->id_champ\" value=\"$this->valeur\">";
					$resultat = charge_un("select " . $this->champscible . " from ". $this->tablecible . " where " . $this->champstocke . "='$this->valeur'");
					$value = $resultat[strtolower($this->champscible)];
					$html .= "<input type='text' name=\"champ_lookup_$this->id_champ\" id=\"champ_lookup_$this->id_champ\" value=\"$value\" ". $this->get_modifiable() . ">";
					break;
				
				case "toile":
				
					$valeur_sauve = $this->valeur;
					//C'est id_toile qui est stocké
					if(is_numeric($this->valeur)){
						$req = new db_sql('select * from toile where id_toile='.$this->valeur);
						while ($req->n()) {
								$this->valeur = $req->f('gamme');
								$gamme = $req->f('gamme');
								$this->valeur_couleur = $req->f('couleur');
								$couleur = $req->f('couleur');
						}
					} else {
						list($this->valeur,$this->valeur_couleur) = explode("|",$this->valeur);
					}
					if ($this->valeur == "*") {
						$this->valeur = $this->valeur_couleur;
						$this->valeur_couleur="";
					}

				case "lov": //Liste de valeurs

					// A quoi sert ce truc ?
					if (is_array($this->valeurs)) {
						foreach($this->valeurs as $valeur_stockee => $x) {
								$tab[] = utf8_encode($valeur_stockee);
						}
						unset($_SESSION[lov][$this->id_dataset.'_champ_'.$this->id_champ]);
						$_SESSION[lov][$this->id_dataset.'_champ_'.$this->id_champ][] = $tab;
					}

					// Gestion du pop-up (logo)
					if ($this->href) {
						$html_1 .= '<a name="'.$this->href.'" class="preview" >';
					}
					
					// Champs non modifiable
					if ($this->get_modifiable()) { // Veut dire que ce n'est pas modifiable comme son nom ne l'indique pas
						$html_2 .= '<input type="hidden" '.$l_nom_champ.' value ="'.$this->valeur.'">';
						$l_nom_champ = ""; //il ne faut qu'un champ avec le nom qui porte la valeur
						$disabled = "disabled";
					}
					
					$html_2 .= '<select title="'.$this->title.'" ' . $this->get_stylevide() .' '. $this->get_modifiable().' '.$disabled.' '.$l_nom_champ.' id="'.$this->id.'" class = "automatisme" '.$this->attribut_html.'>';
					// Gestion de l'option obligatoire (vide autorise)
					if (!$this->obligatoire or $this->valeur=="") {
						if ($this->obligatoire || (!$this->obligatoire && $this->id_type_champ=="toile")) {
							$vide = "[Choisir]";
						} else {
							$vide = "[Vide]";
						}
						$this->valeurs = array("" => $vide)+$this->valeurs;
					}
					//Pour le champ Statut dans la recherche de commande
					if ($this->valeur==" ") $this->valeur="";

					//Si la valeur du champ n'est pas dans la liste et qu'il n'y pas pas d'* (champ ouvert), ca doit etre une valeur desactivee
					if (!$this->valeurs["*"] and !$this->valeurs[$this->valeur] and $this->valeur) {
						$this->valeurs[$this->valeur] = $this->valeur;
					}

					//Affichage des valeurs
					if(is_array($this->valeurs)){
						foreach ($this->valeurs as $valeur => $valeur_affiche ) {
							if(($this->valeur."") == ($valeur."")) {
								$selected = "selected";
								$valeur_trouvee_dans_la_liste = true;
							} else {
								$selected = "";
							}
							// Attention la valeur * doit toujours etre en fin de liste
							if ($valeur == "*" and ($this->valeur != '') and !$valeur_trouvee_dans_la_liste ) {
								$selected = "selected";
							}
							$html .= '<option value="'.$valeur.'" '.$selected.'>'.$valeur_affiche.'</option>';
						}
					}
					$html_3 .= '</select>';
					$html = '<select style = "visibility:hidden; width:0">'.$html.'</select>'.$html_2.$html.$html_3;

					if ($this->href) $html_4 .= '</a>';
					// Affichage du champ ouvert
					if ($this->valeurs["*"] or (!$valeur_trouvee_dans_la_liste and $this->valeur != '') ) {
						if ( ($this->valeur != '') and !$valeur_trouvee_dans_la_liste ) {
							$defval = $this->valeur;
							$hidden = "";
						} else {
							$defval = "";
							$hidden = "style ='display:none'";
						}
						if ($this->id_type_champ != "toile"){
							$html_4 .= "<input ". $this->get_modifiable() ." type='text' name=\"".$this->id_dataset."_champ_ouvert_$this->id_champ\" id=\"champ_ouvert_".$this->id_unique_ajax."_$this->id_champ\" value=\"$defval\" size=$this->nb_longueur_champ maxlength=$this->nb_longueur_champ $hidden>";
						}
						// Jquery qui fait apparaitre et disparaitre le champ ouvert
						$html_4 .= "<script type=\"text/javascript\">$(\"#champ_".$this->id_unique_ajax."_".$this->id_champ."\").change(
										function() {
											if ($(\"#champ_".$this->id_unique_ajax."_".$this->id_champ." option:selected\").val() == '*') {
												$(\"#champ_ouvert_".$this->id_unique_ajax."_".$this->id_champ."\").val('');
												$(\"#champ_ouvert_".$this->id_unique_ajax."_".$this->id_champ."\").show();
											} else {
												$(\"#champ_ouvert_".$this->id_unique_ajax."_".$this->id_champ."\").hide();
											}
										});</script>";
						
					}
					$html = $html_1.$html.$html_4;

					// gestion des toiles
					if ($this->id_type_champ == "toile") {
						$this->valeur = $valeur_sauve;
						if(is_numeric($this->valeur)) {
								$this->valeur = $gamme;
								$this->valeur_couleur = $couleur;
						} else {
								list($this->valeur,$this->valeur_couleur) = explode("|",$this->valeur);
						}
						
						$hidden = "style ='".getStyleValue("style ='display:none ")."'";
						$html .= "<input ". $this->get_modifiable() ." type='hidden' name=\"".$this->id_dataset."_champ_ouvert_$this->id_champ\" id=\"champ_ouvert_".$this->id_unique_ajax."_$this->id_champ\" value=\"$this->valeur_couleur\" size=$this->nb_longueur_champ maxlength=$this->nb_longueur_champ $hidden>";
						$html .= "<span id=\"champ_".$this->id_unique_ajax."_couleur_".$this->id_champ."\">";

						if ($this->valeur!='' and $this->valeur!="*") {
								$lb_champ = $this->id_champ;
								$val = $this->valeur_couleur;
								$gamme = $this->valeur;
								$call_by_include = true;
								$modifiable=$this->get_modifiable() ;
								$id_unique_ajax = $this->id_unique_ajax;
								include ("c_o_dataset_ajax.php");
						}

						$html .= "</span>";
						$html .= "	<script type=\"text/javascript\">$(\"#".$this->id."\").change(
											function() {
												$(\"#champ_ouvet_".$this->id_unique_ajax."_".$this->id_champ."\").val('');
												if (($(\"#".$this->id." option:selected\").val() != '*') &&
													($(\"#".$this->id." option:selected\").val() != '') ) {
													$.ajax({
														type: \"POST\",
														url: \"c_o_dataset_ajax.php\",
														data: \"gamme=\"+$(\"#".$this->id." option:selected\").val()+\"&id_unique_ajax=".$this->id_unique_ajax."&origine=".$GLOBALS[origine]."&lb_champ=".$this->id_champ."\",
														success: function(msg){
															$(\"#champ_".$this->id_unique_ajax."_couleur_".$this->id_champ."\").html(msg);
															$(\"#champ_ouvert_".$this->id_unique_ajax."_".$this->id_champ."\").val('');
														}
													});
												} else {
													$(\"#champ_".$this->id_unique_ajax."_couleur_".$this->id_champ."\").html('');
													$(\"#champ_ouvert_".$this->id_unique_ajax."_".$this->id_champ."\").val('');
												}
											});
										</script>";
						$this->valeur = $valeur_sauve;
					}
					break;

				case "label":
					$this->nonmodifiable	 = 1;					
				case "ouvert": // Champs libre
					if($this->fonction) $this->valeur = call_user_func($this->fonction,$this);
					if($this->verifZeroNonsignif && existeZeroNonsignif($this->valeur)) $this->valeur = round($this->valeur,0) ;

					if($this->padding){
						if(is_numeric($this->valeur) && strpos($this->valeur,'.') == true){
								$this->valeur = str_pad ($this->valeur , strlen($this->valeur)+$this->padding - (strlen($this->valeur)-strpos($this->valeur,'.'))+1 , "0" );
						}
					}
					$html .= "<input ".$this->get_modifiable()." type='text' title=\"".$this->title."\" ". $this->get_stylevide() ." name=\"".$this->id_dataset."_champ_$this->id_champ\" class = 'automatisme' id=\"champ_ouvert_$this->id_champ\" value=\"$this->valeur\" size=$this->nb_longueur_champ maxlength=$this->nb_longueur_champ ". $this->get_modifiable();
					//if($this->isnumeric) $html .= "onkeypress='validate(event,this.value)'";
					//if($this->toupdate) $html .= "onkeypress='skip(event)'";
					$html .= ">";
					break;

				case "literal": //Champs litteraux
					$html .= $this->valeur;
					break;

				case "textarea": // Champs commentaire
					$html .= "<textarea title=\"".$this->title."\" name=\"".$this->id_dataset."_champ_$this->id_champ\" ". $this->get_stylevide() ." id=\"champ_ouvert_$this->id_champ\" rows=$this->rows cols=$this->cols ". $this->get_modifiable() .">$this->valeur</textarea>";
					break;

				case "date": // Champ date
					$nomchamp = "date_".$this->id_dataset."_champ_$this->id_champ";
					$defvalsaisie=$this->valeur;
					if(!$this->valeur) {
						$defvalsaisie = "00/00/00";
					} else if (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$this->valeur,$regs)){
						$defvalsaisie=$regs[3]."/".$regs[2]."/".substr($regs[1],2,2);
					}
					$html .= "<input title=\"".$this->title."\" ".$this->get_modifiable()." type=\"text\"" . $this->get_stylevide() ." name=\"".$nomchamp."_saisie\" value='$defvalsaisie' size=9 maxlength=8
					onfocus =\"this.select()\"
					onkeyup =\"
					if ((this.value.search(/^[0-9]{2}$/)!=-1) || (this.value.search(/^[0-9]{2}\/[0-9]{2}$/)!=-1)) {
						this.value = this.value+'/'
					}\"
					onblur =\"
					while (this.value.match(/\D/)) {
						this.value = this.value.replace (/\D/,'');
					}
					this.value = this.value.substr(0,6);
					this.value = this.value + '000000'.substring(this.value.length,6);
					this.value = this.value.substr(0,2)+'/'+this.value.substr(2,2)+'/'+this.value.substr(4,2)
					if (this.value=='00/00/00') {
						this.form.".$nomchamp.".value = '';
						return true;
					} else {
						var dater = new Date(this.value.substr(6,2),this.value.substr(3,2)-1,this.value.substr(0,2));
						if ((dater.getDate()!=this.value.substr(0,2)) || (dater.getMonth()+1!=this.value.substr(3,2)) || (dater.getYear()!=this.value.substr(6,2))) {
						alert('Format de date incorrect (jj/mm/aa)');
						this.focus();
						return false;
						} else {
						this.form.".$nomchamp.".value ='20'+this.value.substr(6,2)+'-'+this.value.substr(3,2)+'-'+this.value.substr(0,2);
						return true;
						}
					}\"
					>";
					$html .= "<input type='hidden' name='$nomchamp' value='$this->valeur'>";

		}
		if ($erreur) {
				return ("ERREUR ! : ".$erreur);
		} else {
				return ($html);
		}
	}
}
/*
class regle	{

	private $regles;
	private $type_regle;
	private $filter;
	private $id_champ;
	public $utf8_decode;
	function __construct($type_regle = '',$filter,$id_champ) {
		if ($type_regle != '') {
				$this->type_regle = $type_regle;
				$this->id_champ=$id_champ;
				// Charge les proprietes de la regle
				$this->regles	= $GLOBALS[parms][regles][$type_regle];
				$this->filter	= $filter;
		}
	}

	function construire_regle() {
		return $this->filtrer_valeur();
	}

	function formater_critere() {
		$texte="";
		$tab_critere = explode(" and ",$this->filter);
		for ($i = 0; $i < count($tab_critere); $i++) {
				$tab = explode("=",$tab_critere[$i]);
				if($tab[1]!=""){
					if($texte=="") {
						$texte.=$tab[0]."='".$tab[1]."'";
					} else {
						$texte.=" and ".$tab[0]."='".$tab[1]."'";
					}
				}
		}
		return $texte;
	}

	function filtrer_valeur() {
		$requete = " select distinct ".$this->id_champ." from ".$this->regles[table];
		if($this->filter) {
				$condition = $this->formater_critere();
				if($condition)
					$requete .= " where ". $condition;
		}
		if(!$this->utf8_decode)
				return new db_sql(utf8_decode($requete));
		else
				return new db_sql($requete);
	}
}
*/

class dataset {

	public $type_dataset;
	public $id_dataset;
	public $presentation;
	public $champs = array();
	//private $regles = array();
	public $nonmodifiable;
	//public $filter;
	private $list_id;
	public $checkvalidate; // Fonction de validation du dataset
	public $champsentete;
	public $onload; // Fonction exécutée au chargement du dataset
	//public $;
	public $formname;
	public $groupelookup; // Un group look up indique qu'une requete exécutée au chargement (from get et database) dont le résultat est stocké dans chaque champ (virtuel car non stocké en base) pour être utilisé lors de l'affichage, donc ne permet pas l'initialisation
	public $statut_value; // Usage interne qui permet de gérer les champs obligatoires en fonction des statuts
	public $padding; // Défini le padding de tous les champs du dataset en une fois
	public $parms; // Tableau de tous les propriétées
	public $liste_champ_ordonnes;
	public $liste_des_off_sets_de_spans;
	public $liste_des_largeurs;
	public $fulltable; // Serge, peux tu regarder comment s'impriment les OF - VELUX - MECANISME

	function __construct($type_dataset = '') {
		if ($type_dataset != '') {
			$this->type_dataset		= $type_dataset;
			$dataset = charge_dataset($type_dataset);

			//Charge les proprietes du champ
			//$dataset = $GLOBALS[parms][datasets][$type_dataset];
			$this->presentation	= $dataset[presentation];
			$this->groupelookup	= $dataset[groupelookup];
			$this->champs			= $dataset[champs];
			//$this->regles			= $dataset[regles];
			//$this->filter			= $dataset[filter];
			$this->nonmodifiable	= $dataset[nonmodifiable];
			$this->checkvalidate	= $dataset[checkvalidate];
			$this->champsentete	= $dataset[champsentete];
			$this->onload			= $dataset[onload];
			//$this->		= $dataset[];
			$this->padding			= $dataset[padding];
			$this->fulltable		= $dataset[fulltable];
			$this->parms			= $dataset;
			//$this->html_empied		= $dataset[html_empied];
			$this->list_id=array();
			$filtre ="";
			if(is_array($this->champsentete)) {
				foreach ($this->champsentete as $id_champ => $champ) {
					$this->ochampentete[$id_champ] = new champ($id_champ);
				}
			}

			if(is_array($this->champs)){
				foreach ($this->champs as $id_champ => $champ) {				
					$this->ochamp[$id_champ] = new champ($id_champ);
					// Surimpression des propriétés des champs au niveau global dataset
					if ($this->nonmodifiable) $this->ochamp[$id_champ]->nonmodifiable=$this->nonmodifiable;
					//if ($this->) $this->ochamp[$id_champ]->=$this->;
					if ($this->padding) $this->ochamp[$id_champ]->padding=$this->padding;
					// Surimpression des propriétés des champs pour chaque champ
					if ($champ[obligatoire]) $this->ochamp[$id_champ]->set_obligatoire(true);
					if ($champ[attribut_html]) $this->ochamp[$id_champ]->attribut_html=$champ[attribut_html];
					if ($champ[rowspan]) $this->ochamp[$id_champ]->rowspan=$champ[rowspan];
					if ($champ[colspan]) $this->ochamp[$id_champ]->colspan=$champ[colspan];
					if ($champ[startligne]) $this->ochamp[$id_champ]->startligne=1;
					if ($champ[largeur]) $this->ochamp[$id_champ]->largeur=$champ[largeur];
					if ($champ[hauteur]) $this->ochamp[$id_champ]->hauteur=$champ[hauteur];
					if ($champ[pdf_fontsize]) $this->ochamp[$id_champ]->pdf_fontsize = $champ[pdf_fontsize];
					if ($champ[pdf_fontsize_entete]) $this->ochamp[$id_champ]->pdf_fontsize_entete = $champ[pdf_fontsize_entete];
					// Priorité du lb_champ (lb_champ du dataset, id_champ du dataset, lb_champ du champ) ici, ce n'est que la surrimpression par rapport à l'init du champ lui même
					if (!$this->ochamp[$id_champ]->lb_champ) $this->ochamp[$id_champ]->lb_champ = $champ[id_champ];
					if (!$this->ochamp[$id_champ]->lb_champ) $this->ochamp[$id_champ]->lb_champ = $champ[lb_champ];
					// Priorité du lb_champ_pdf (lb_champ_pdf du dataset, lb_champ_pdf du champ, lb_champ précédement défini) ici, ce n'est que la surrimpression par rapport à l'init du champ lui même
					if (!$champ[lb_champ_pdf]) $this->ochamp[$id_champ]->lb_champ_pdf = $champ[lb_champ_pdf];
					if (!$this->ochamp[$id_champ]->lb_champ_pdf) $this->ochamp[$id_champ]->lb_champ_pdf = strtoupper($this->ochamp[$id_champ]->lb_champ);	
					if ($champ[origines_invisibles]) {
						$this->ochamp[$id_champ]->origines_invisibles = $champ[origines_invisibles];
					}
				}
			}
		}
	}

	function html() {
		foreach ($this->champs as $id_champ => $champ) {
			if($id_champ=="statut") $statut_value = $this->ochamp[$id_champ]->get();
		}
		if($statut_value){
				//Affecte le style si le champ est obligatoire pour le statut en cours
				$champ_statut = charge_champ('statut');
				$ordre = $champ_statut[ordre][$statut_value];
				for($i=1;$i<=$ordre;$i++){
					//$tab = $champ_statut[fieldrequired][$this->type_dataset][$ordre];
					$tab = $champ_statut[fieldrequired][$this->type_dataset][$i];
					if(is_array($tab)) {
						foreach ($this->champs as $id_champ => $champ) {
								foreach ($tab as $cle => $idchamp){
									if($idchamp==$id_champ || $idchamp=="ALL") {
										$this->ochamp[$id_champ]->set_obligatoire(true);
									}
								}
						}
					}
				}
				//Affecte le style si le champ est obligatoire pour le statut d'après
				for($i=1;$i<=$ordre+1;$i++){
					//$tab = $champ_statut[fieldrequired][$this->type_dataset][$ordre+1];
					$tab = $champ_statut[fieldrequired][$this->type_dataset][$i];
					if(is_array($tab)) {
						foreach ($this->champs as $id_champ => $champ) {
								foreach ($tab as $cle => $idchamp) {
									if($idchamp==$id_champ || $idchamp=="ALL") {
										if(!$this->ochamp[$id_champ]->obligatoire) {
												$this->ochamp[$id_champ]->set_obligatoire(true);
												//$this->ochamp[$id_champ]->="style='background-color: #AAEEEE'";
										}
									}
								}
						}
					}
				}
		}
		if ($this->presentation == 'ofempied') {
				$html = "<table style='font-family: verdana;font-size: 10px;color :#000066;'>";
				foreach ($this->champs as $id_champ => $champ) {
					$html .="<td valign='top'>" . ($this->ochamp[$id_champ]->html())."</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
				}
				$html .="</tr></table>" ;
		} else if ($this->presentation <> 'l') {
			// Champs dans un tableau
			$html = "";
			$html .= "\n<table class=\"requeteur\">";
			$html .= "\n <tr>";
			$compteur_champs = 0;
			foreach ($this->champs as $id_champ => $champ) {
				$saute = $champ[saute_avant][$GLOBALS[origine]];
				$span = 1+$saute*2;
				$compteur_champs += $saute;
				if ($GLOBALS[origine] and strpos($this->ochamp[$id_champ]->origines_invisibles, $GLOBALS[origine])){
					$this->ochamp[$id_champ]->non_html = 1;
				}
				if(!$this->ochamp[$id_champ]->non_html) {
					//Entête
					$html .= "\n <td colspan = ".$span." class=\"requet_right\">";
					$html .= ($this->ochamp[$id_champ]->lb_champ);
					$html .= "</td>";
					//Champs lui même
					$span = $this->ochamp[$id_champ]->colspan;
					if($span){
						$colspan = " colspan = \"".$span."\" ";
						$compteur_champs += ($span-1)/2;
					} else {
						$colspan = "";
					}
					$compteur_champs++;
					$html .= "\n <td $colspan class=\"requet_left\">";
					$html .= ($this->ochamp[$id_champ]->html());
					$html .= "</td>";
				} else {
					// On affiche tout de même la valeur cachée
					$html .= $this->ochamp[$id_champ]->html();
				}
				//$compteur_champs++;
				if ($compteur_champs >= $this->presentation) {
					$compteur_champs = 0;
					$html .= "\n </tr>";
					$html .= "\n <tr>";
				}
			}
			$html .= "\n <td colspan = ".(($this->presentation-$compteur_champs)*2)." class=\"requet_button\" >";
			//$html .= "<input class=\"requeteur_button\" type=\"reset\" name=\"Reset\" value=\"Rafraichir\">";
			//$html .= "<input type=\"hidden\" name=\"id_dataset\" value=\"".$this->id_dataset."\">";
			//$html .= "<input class=\"requeteur_button\" type=\"submit\" name=\"Submit\" value=\"Enregistrer\" OnClick=\"return champ_oblig('client')\">";
			$html .= "</td>";
			$html .= "\n</tr>";
			$html .= "\n</table>";
		} else {
			// Champs en ligne ***********************************************************************************
			$html .= "\n <tr>";
			$liste_champ_ordonnes = $this->liste_champ_ordonnes();
			$dataset = new dataset($type_dataset_produit);
			foreach ($liste_champ_ordonnes as $i => $id_champ) {
				$html .= "\n <td nowrap>".$this->ochamp[$id_champ]->html()."</td>";
			}
			if(!$this->nonmodifiable) {
				$html .= "\n <td class=\"requet_button\" nowrap>";
				if($this->id_dataset && $this->id_dataset>0)
					$html .= "<input class=\"requeteur_button\" type=\"submit\" name=\"Supprimer\" value=\"Supprimer\" onclick=\"
								if (confirm('Voulez vous supprimer cette ligne ?'))
								{
									document.forms['".$this->formname."'].DELETE.value='1';
									document.forms['".$this->formname."'].id_dataset_to_delete.value='".$this->id_dataset."';
									document.forms['".$this->formname."'].type_dataset.value='".$this->type_dataset."';
								}
								else
								{
									return false;
								} \">";
				//$html .= "<input class=\"requeteur_button\" type=\"reset\" name=\"Reset\" value=\"Rafraichir\">";
				//$html .= "<input type=\"hidden\" name=\"id_dataset\" value=\"".$this->id_dataset."\">";

				if (!$GLOBALS[automatisme]) {
					$GLOBALS[automatisme] = true;

					$html .= "
					<script type=\"text/javascript\"> $( document ).ready(function() {
						$(\".automatisme\").change ( function() {
							var ret = maj($(this), 'event');
							if(ret)alert(ret);
						});
						$(\".automatisme\").each ( function() {
							//maj($(this), 'init');
						});
					})</script>";

				}
			}
			$html .= "\n</td></tr>";
		}

		if ($erreur) {
				return ("ERREUR ! : ".$erreur);
		} else {
				return ($html);
		}
	}
	function html_entete() {
		//$html .= "<table class=\"resultat\">";
		if ($this->presentation == 'custom_peinture') {
				$html .= "<table width=\"50%\">";
				$html .= "<tr>";
				foreach ($this->champsentete as $id_champ => $champ) {
					if($this->ochampentete[$id_champ]->id_type_champ){
						$html .= "<td class=\"custom_left\">";
						$html .= ($this->ochampentete[$id_champ]->lb_champ);
						$html .= "</td>";
						$html .= "<td>";
						$html .= ($this->ochampentete[$id_champ]->html());
						$html .= "</td>";
					}
					else {
						$html .= "<td class=\"resultat_tittle\">";
						$html .= ($this->ochampentete[$id_champ]->lb_champ);
						$html .= "</td>";
					}
				}
				$html .= "</tr>";
		} else {
				//$html .= "\n<table class=\"resultat\">";
				$html .= "\n<table class=\"requeteur\">";
				$nb_ligne=0;
				foreach ($this->champs as $id_champ => $champ) {
					if($this->ochamp[$id_champ]->startligne and $nb_ligne++) $html .= "\n </tr>";
					if($this->ochamp[$id_champ]->startligne)	$html .= "\n <tr>";
					$rowspan ="";
					$colspan ="";
					if($this->ochamp[$id_champ]->rowspan)
						$rowspan ="rowspan =".$this->ochamp[$id_champ]->rowspan;
					if($this->ochamp[$id_champ]->colspan)
						$colspan ="align='center' colspan =".$this->ochamp[$id_champ]->colspan;
					$html .= "\n <td ".$rowspan." ".$colspan." class=\"resultat_tittle\">";
					$html .= $this->ochamp[$id_champ]->lb_champ;
					$html .= "</td>";
				} $html .= "\n </tr>";
		}
		return ($html);
	}
	function html_empied() {
		$html .= "\n </tr>";
		$html .= "\n</table>";
		//if($this->html_empied) $html .= call_user_func($this->html_empied,$this->id_doc);
		return ($html);
	}

	function load_from_screen($id_dataset = 0) {
		if ($id_dataset)
			$this->id_dataset = $id_dataset;	
		$valeurs = array();
		if (is_array($this->champs)){
				foreach ($this->champs as $id_champ => $champ) {
					$this->ochamp[$id_champ]->id_dataset = $this->id_dataset;
					$this->ochamp[$id_champ]->load_from_screen();
					$valeurs[$id_champ] =$this->ochamp[$id_champ]->get() ;

					if($this->ochamp[$id_champ]->checkload && $this->ochamp[$id_champ]->get()) {
						$ret=call_user_func($this->ochamp[$id_champ]->checkload,$this->ochamp[$id_champ]->get());
						if($ret) eval($ret);
					}

					if($this->ochamp[$id_champ]->filtrer_valeurs) {
						$this->ochamp[$id_champ]->valeurs=call_user_func(
								$this->ochamp[$id_champ]->filtrer_valeurs,
								$this->ochamp[$id_champ]->valeurs_init
						);
					}

				}
		}
		//Vérifie l'affichage
		if($this->onload)
			call_user_func($this->onload,$this,$valeurs);
	}

	function load_from_get() {
		$valeurs = array();
		foreach ($this->champs as $id_champ => $champ) {
				$this->ochamp[$id_champ]->load_from_get();
				$valeurs[$id_champ] =$this->ochamp[$id_champ]->get() ;
		}
		//Vérifie l'affichage
		if($this->onload)
				call_user_func($this->onload,$this,$valeurs);
		$this->load_lookup_data();
	}

	function get($idchamp) {
		foreach ($this->champs as $id_champ => $champ) {
				if($id_champ==$idchamp)
					return($this->ochamp[$id_champ]->get());
		}
	}

	function load_lookup_data() {
		if($this->groupelookup){
				foreach ($this->groupelookup as $groupe) {
					$groupelookup = $GLOBALS[parms][groupelookup][$groupe];
					$id_champ =$this->get($groupelookup[id_champ]);
					if(!$id_champ)$id_champ = $_GET["champ_".$groupelookup[id_champ]];
					$resultat = charge_un($groupelookup[requete] . "='$id_champ'");
					$datalookup[$groupelookup[id_champ]]=$resultat;
				}
				foreach ($this->champs as $id_champ => $champ) {
					if($this->ochamp[$id_champ]->id_type_champ=='groupelookup') $this->ochamp[$id_champ]->datalookup =$datalookup;
				}
		}
	}

	function load_from_database($id_dataset = 0) {
		if (!$id_dataset)
			$id_dataset = $this->id_dataset;
		$this->id_dataset = $id_dataset;
		
		//LECTURE DANS LES ZDATASET_*
		$resultat = new db_sql("select * from `zdataset_".$this->type_dataset."` where id_dataset = ".$this->id_dataset);
		while($resultat->n()){
			foreach($resultat->Record as $lb => $val){
				$valeurs[$lb]=$val;
			}
		}
		
		foreach ($this->champs as $id_champ => $champ) {
			$this->ochamp[$id_champ]->set($valeurs[$id_champ]);
			$this->ochamp[$id_champ]->id_dataset = $id_dataset;
			$ret = '';
			if($this->ochamp[$id_champ]->checkload) {
				$ret=call_user_func($this->ochamp[$id_champ]->checkload,$this->ochamp[$id_champ]->get());
				if($ret) eval($ret);
			}
			if($this->ochamp[$id_champ]->filtrer_valeurs) {
				$this->ochamp[$id_champ]->valeurs=call_user_func(
					$this->ochamp[$id_champ]->filtrer_valeurs,
					$this->ochamp[$id_champ]->valeurs_init,
					$this->ochamp[$id_champ]->get());
			}
		}
		//Vérifie l'affichage
		if($this->onload)
				call_user_func($this->onload,$this,$valeurs);
		if($this->nonmodifiable) {
				foreach ($this->champs as $id_champ => $champ) {
					$this->ochamp[$id_champ]->nonmodifiable = $this->nonmodifiable;
				}
		}
		$this->load_lookup_data();
	}

	function validate() {
		$filtre="";
		$idchamp="";
		$parametres = array();
		$parametres['dataset']=$this;

		//Contrôle des champs obligatoires
		if(is_array($this->champs)){
				foreach ($this->champs as $id_champ => $champ) {
					$parametres[$id_champ] = $this->ochamp[$id_champ];
					if(($this->ochamp[$id_champ]->obligatoire) and (!$this->ochamp[$id_champ]->get())) {
						return "le champ ".$this->ochamp[$id_champ]->lb_champ." doit etre renseigné (1)";
					}
					//S'il y a un filtre
					// MAD 14/11 : A priori, on vire les filter
					// if ($this->ochamp[$id_champ]->id_type_champ == 'lov' && $this->filter) {
						// if((in_array($id_champ, $GLOBALS[parms][regles][$this->filter][field])) && !$this->ochamp[$id_champ]->notapply) {
								// $idchamp=$id_champ;
								// $filtre = ajouter_filtre($filtre,$id_champ,$this->ochamp[$id_champ]->get());
						// }
					// }
				}
		}

		if($this->checkvalidate) {
				$ret=call_user_func($this->checkvalidate,$parametres);
				if($ret)	return $ret;
		}
		
		if(is_array($this->champs)){
				foreach ($this->champs as $id_champ => $champ) {
					if($this->ochamp[$id_champ]->checkvalidate) {
						if($this->ochamp[$id_champ]->id_type_champ=="toile")
							$ret=call_user_func($this->ochamp[$id_champ]->checkvalidate,$this->ochamp[$id_champ]);
						else 
							$ret=call_user_func($this->ochamp[$id_champ]->checkvalidate,$parametres);
						if($ret)	return $ret;
					}
				}
		}

		//Contrôle des règles
		/*
		if (is_array($this->regles)) {
			foreach ($this->regles as $regle => $un) {
				$deb = strpos($regle, "*");
				while ($deb) {
					$fin = strpos($regle, "*",$deb+1);
					$regle_b = substr($regle,0,$deb)."\"".$this->ochamp[substr($regle,$deb+1,$fin-$deb-1)]->get()."\"".substr($regle,$fin+1);
					$regle = $regle_b;
					$deb = strpos($regle, "*");
				}
				eval ($regle);
				if ($ret) return $ret;
			}
		}
		*/
		
		//Verifie si les donnees saisies sont compatibles
		// MAD 14/11 : A priori, on vire les filtres
		// if($this->filter && $this->filter!="filtertringle") {
				// $regle = new regle($this->filter,$filtre,$idchamp);
				// $regle->utf8_decode=1;
				// $resultat = $regle->construire_regle();
				// if (isEmpty_Record($resultat))
					// return "Les donnÚes saisies ne sont pas compatibles";
		// }
		return false;
	}

	function set_id_dataset($id_dataset) {
		$this->id_dataset =$id_dataset;
		foreach ($this->champs as $id_champ => $champ) {
				$this->ochamp[$id_champ]->id_dataset=$id_dataset;
		}
	}

	function set_modifiable($nonmodifiable) {
		$this->nonmodifiable =$nonmodifiable;
		foreach ($this->champs as $id_champ => $champ) {
				$this->ochamp[$id_champ]->nonmodifiable=$nonmodifiable;
		}
	}

	function store($res="") {
		if (!$this->id_dataset||$this->id_dataset<0) {
			if ($res) {
				$res->q("insert into dataset(type_dataset) values ('".$this->type_dataset."')");
			} else {
				new db_sql("insert into dataset(type_dataset) values ('".$this->type_dataset."')");
			}
			$this->id_dataset = db_sql::last_id ();
			foreach ($this->champs as $id_champ => $champ) {
				$this->ochamp[$id_champ]->id_dataset=$this->id_dataset;
			}
		}
		if(is_array($this->champs)){
			$champ_query = "id_dataset";
			$valeur_query = $this->id_dataset;
			foreach ($this->champs as $id_champ => $champ) {
				if($this->ochamp[$id_champ]->id_type_champ!="groupelookup" || $this->ochamp[$id_champ]->ispersisted) {
					$val = $this->ochamp[$id_champ]->get();
					if($this->ochamp[$id_champ]->id_type_champ=="toile") {
						list($gamme,$couleur) = explode("|",$val);
						if($gamme=="" && $couleur!="") $val="|";
						if($gamme!="" && $couleur!=""){
							$req = new db_sql("select * from toile where gamme='$gamme' and couleur='$couleur'");
							while ($req->n()) {
								$val = $req->f('id_toile');
							}
						}
					}
					$champ_query .= ", `".$id_champ."`";
					$valeur_query .= ", '".str_replace("'","\'",$val)."'";
				}
			}
			if ($res)
				$res->q("replace into `zdataset_".$this->type_dataset."` (".$champ_query.") values (".$valeur_query.")");
			else
				new db_sql("replace into `zdataset_".$this->type_dataset."` (".$champ_query.") values (".$valeur_query.")");
		}
		return ($this->id_dataset);
	}



	//Retourne le tableau d'affichage des champs ($n => $id_champ)
	//Reste à factoriser...
	function computemaj() {
		$n_ligne=1;
		$i=0; //Compteur de champs
		$j=0; //Compteur de spans
		$z=-1; //Compteur d'itÚration
		$champs=array();
		foreach ($this->champs as $id_champ => $champ) {
			$z++;
			if (($this->ochamp[$id_champ]->startligne) and ($z)) { // and $z : s'il n'y a pas de start ligne sur le 1er champ, on en met un d'office
				$n_ligne++;
			}
			if ($n_ligne >= 2 ) {
				foreach ($spans as $k => $span) {
					if ($span[debut] < $span[fin]) {
						$champs[$span[debut]] = $id_champ;
						if($this->ochamp[$id_champ]->largeur) {
							$largeurs[$span[debut]] = $this->ochamp[$id_champ]->largeur;
						} else {
							$largeurs[$span[debut]] = 1;
						}
						$spans[$k][largeur] += $largeurs[$span[debut]];
						$spans[$k][debut]++;
						break; // Sort du foreach (en fait ne prend que le premier span)
					}
				}
			} else {
				if(!$this->ochamp[$id_champ]->colspan) {
					$champs[$i] = $id_champ;
					if($this->ochamp[$id_champ]->largeur) {
							$largeurs[$i] = $this->ochamp[$id_champ]->largeur;
					} else {
							$largeurs[$i] = 1;
					}
					$i++;
				} else {
					$colspan[$j] = $id_champ;
					$spans[$j][debut]=$i;
					$i=$i+$this->ochamp[$id_champ]->colspan;
					$spans[$j][fin]=$i;
					$j++;
				}
			}
		}
		ksort($champs);

		$this->liste_champ_ordonnes			= $champs;
		$this->liste_des_off_sets_de_spans = $spans;
		$this->liste_des_largeurs			= $largeurs;

		$x = 0;
		$y = 0;
		foreach ($this->champs as $id_champ => $champ) {
			if ($this->ochamp[$id_champ]->startligne) {
				$y += 1;
				$x = 0;
			}
			//Cherche la première place libre
			while ($occupe[$x][$y]) {
				$x++;
			}
			//Marque l'emplacement
			$emplacement[$id_champ][x] = $x;
			$emplacement[$id_champ][y] = $y;
			//Noircit la matrice
			$colspan = $this->ochamp[$id_champ]->colspan;
			if (!$colspan) $colspan=1;
			$rowspan = $this->ochamp[$id_champ]->rowspan;
			if (!$rowspan) $rowspan=1;
			$emplacement[$id_champ][rowspan] = $rowspan;
			$largeur = $this->ochamp[$id_champ]->largeur; // ne se produit que pour des cases simples
			if (!$largeur) $largeur=1;
			$hauteur = $this->ochamp[$id_champ]->hauteur;
			if ($hauteur != 1) {
				$hauteurs[$y] = $hauteur;
			} else if (!$hauteurs[$y]) {
				$hauteurs[$y] = 1;
			}
			for ($i = 0; $i < $colspan; $i++) {
				for ($j = 0; $j < $rowspan; $j++) {
					$occupe[$x+$i][$y+$j] = $largeur;
				}
			}
		}
		//Calcule les largeurs
		foreach ($occupe as $x=>$ligne) {
				foreach ($ligne as $y=>$largeur) {
					if ($largeur <> 1) {
						$largeurs[$x] = $largeur;
					} else if (!$largeurs[$x]) {
						$largeurs[$x] = 1;
					}
				}
		}
		//Calcule les dÚcallages
		ksort($largeurs);
		$ordonnee=0;
		foreach ($largeurs as $x=>$largeur) {
				$ordonnees[$x] = $ordonnee;
				$ordonnee += $largeur;
		}
		ksort($hauteurs);
		$abs=0;
		foreach ($hauteurs as $x=>$hauteur) {
				$abss[$x] = $abs;
				$abs += $hauteur;
		}
		foreach ($this->champs as $id_champ => $champ) {
				$colspan = $this->ochamp[$id_champ]->colspan;
				if (!$colspan) $colspan=1;
				$largeur = 0;
				for ($i = 0; $i < $colspan; $i++) {
					$largeur += $largeurs[$emplacement[$id_champ][x]+$i];
				}
				$rowspan = $this->ochamp[$id_champ]->rowspan;
				if (!$rowspan) $rowspan=1;
				$hauteur = 0;
				for ($i = 0; $i < $rowspan; $i++) {
					$hauteur += $hauteurs[$emplacement[$id_champ][y]+$i];
				}
				$emplacement[$id_champ][hauteur] = $hauteur;
				$emplacement[$id_champ][largeur] = $largeur/$ordonnee;;
				$emplacement[$id_champ][x] = $ordonnees[$emplacement[$id_champ][x]]/$ordonnee;
				$emplacement[$id_champ][y] = $abss[$emplacement[$id_champ][y]];
		}
		$this->liste_des_emplacements = $emplacement;
	}

	function liste_champ_ordonnes() {
		if (!is_array($this->liste_champ_ordonnes)) {
				$this->computemaj();
		}
		return $this->liste_champ_ordonnes;
	}

	function liste_des_off_sets_de_spans() {
		if (!is_array($this->liste_des_off_sets_de_spans)) {
				$this->computemaj();
		}
		return $this->liste_des_off_sets_de_spans;
	}

	function liste_des_largeurs() {
		if (!is_array($this->liste_des_largeurs)) {
				$this->computemaj();
		}
		return $this->liste_des_largeurs;
	}

	function liste_des_emplacements() {
		if (!is_array($this->liste_des_emplacements)) {
				$this->computemaj();
		}
		return $this->liste_des_emplacements;
	}
}
?>
