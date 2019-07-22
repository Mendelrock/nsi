<?php
include("ress/entete.php");

// Filtre sur les dates
if (!$date_debut) $date_debut = aujourdhui();
if (!$date_fin) $date_fin = decale_date($date_debut,30);
$clause_date = " dt_date >= '".$date_debut."' AND dt_date <= '".$date_fin."'";

$capacite_globale = req_pre("SELECT dt_date as id, nb_heure as lb FROM gp_capacite_global WHERE $clause_date");

// Filtre sur les utilisateurs
if(!$profil_utilisateur) $profil_utilisateur = 10;
if($profil_utilisateur != "*"){
	$clause_profil = " and utilisateur.id_profil = ".$profil_utilisateur;
}
if($type_poste and $type_poste != "*"){
	$join = " inner join gp_type_poste_utilisateur on (gp_type_poste_utilisateur.id_type_poste = $type_poste and gp_type_poste_utilisateur.id_utilisateur = utilisateur.id_utilisateur) ";
}
$resultat = req_mul("
	SELECT 
		utilisateur.id_utilisateur as id,
		CONCAT(prenom, ' ', nom) as nom,
		id_profil as profil
	FROM
		utilisateur
		$join
	WHERE
		1=1
	$clause_profil
");
foreach($resultat as $row){
	$personnes[$row[id]] = ["nom"=>$row[nom], "profil"=>$row[profil]];
}
$tab = new db_sql("SELECT * FROM gp_conge_heure_sup WHERE id_utilisateur in (".implode(array_keys($personnes),",").") and $clause_date ");
while ($ligne = $tab->n()) {
	$conge[$ligne[id_utilisateur]][$ligne[dt_date]] = $ligne[lb_motif_absence];
	$heures_sup[$ligne[id_utilisateur]][$ligne[dt_date]] = $ligne[nb_heure_sup];
}

?>

<body class="application">
<style>
	table td.highlighted {
		background-image: linear-gradient(
			45deg,
			rgb(128,128,128, 0.90),
			rgb(128,128,128, 0.90)
		);
		background-blend-mode: screen;
	}
	input:disabled, select:disabled {
		background-color : #DDDDDD;
	}
	.we, .ferie {
		background-color : darkgray;
		color: lightgrey;
	}
	.conge {
		background-color : blue;
		color: white;
	}
	.heure_sup {
		background-color : red;
		color: white;
	}	
	
</style>
<table class="menu_haut">
    <tbody>
		<tr>
            <td class="menu_haut">Gestion des congés et heures supplémentaires</td>
        </tr>
    </tbody>
</table>
<form action="" method="post">

	<table class="requeteur">
		<tbody>
		<tr>
			<td class="requet_right">Profil</td>
			<td class="requet_left"><? drop_down ("select '*' as id, '[Tous]' as lb UNION select Id_profil as id, Lb_profil as lb from profil", "profil_utilisateur", "id", "lb", $profil_utilisateur, false, "forms[0]", "N", "", "", false); ?></td>
			<td class="requet_right">Type de poste</td>
			<td class="requet_left"><? drop_down ("select '*' as id, '[Tous]' as lb UNION select Id_type_poste as id, Lb_type_poste as lb from gp_type_poste", "type_poste", "id", "lb", $type_poste, false, "forms[0]", "N", "", "", false); ?></td>
			<td class="requet_right">
				Date de <? champ_date("date_debut", $date_debut) ?> à <? champ_date("date_fin", $date_fin) ?> 
			</td>
			<td class="requet_right">
			<button class="requeteur_button" >Chercher</button>
			</td>
		</tr>
		</tbody>
	</table>
	<?
	
	// Entete
	$header = "<tr>";
	$header.= "<th></th>";
	for($date = $date_debut; strtotime($date)<=strtotime($date_fin) ; $date = decale_date($date, 1)){
		$header.= "<td class = 'resultat_tittle'>".date("d/m", strtotime($date))."</td>";
	}
	$header.= "</tr>";
	
	foreach($personnes as $id => $val){
		$body.= '<tr data-utilisateur = "'.$id.'">';
		$body.= "<td class = 'resultat_list' align = right nowrap>".$val[nom]."</td>";
		for($date = $date_debut; strtotime($date)<=strtotime($date_fin) ; $date = decale_date($date, 1)){
			$plus = 'data-date = "'.$date.'"';
			$class = "";
			if( (date("w", (strtotime($date)))>5) or (date("w", (strtotime($date)))<1) ) {
				$plus = "";
				$class = "we";
				$text = "WE";
			} else if (!$capacite_globale[$date]) {
				$plus = "";
				$class = "ferie";
				$text = "Ferié";			
			} else if ($conge[$id][$date]) {
				$class = "conge";
				$text = $conge[$id][$date];	
			} else if ($heures_sup[$id][$date]) {
				$class = "heure_sup";
				$text = $capacite_globale[$date]+$heures_sup[$id][$date];			
			} else {
				$text = $capacite_globale[$date];				
			}
			$body.='<td '.$plus.' class="resultat_list '.$class.'" style="text-align : center">'.$text.'</td>';
		}
		$body.= "</tr>";
	}
	?>
	<table class="resultat" id="tab_chs">
		<thead><? echo $header; ?></thead>
		<tbody><? echo $body; ?></tbody>
	<table>
	
	<table class="requeteur" length= 200>
		<tbody>
			<tr>
				<td class="requet_right">Absences <input type="radio" name="type_mod" value="C" checked></td>
				<td class="requet_left" id="C"><label for="motif">Motif : </label><? drop_down("", "motif", "", "", $motif, false, "", "N",  "1|2|3|4|5","Congé|Maladie|Injustifié|Formation|Autre") ?></td>
			</tr>
			<tr>			
				<td class="requet_right">Heures supplémentaires <input type="radio" name="type_mod" value="HS"></td>
				<td class="requet_left" id="HS"><? champ_numeric_ouvert("heures_sup", "", 2, 2) ?><label for="heures_sup"> heures/jour</label></td>
			</tr>
			<tr>
				<td></td>
				<td><button class="requeteur_button" id="appliquer_mod">Enregistrer</button></td>
			<tr>
		</tbody>
	</table>
</form>

<script src="admin_conge_heure_sup.js"></script>
<?
include ("ress/enpied.php");
?>