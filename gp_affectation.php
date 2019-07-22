<?
require_once("ress/entete.php");
//require_once("c_o_dataset.php");

//Gestion des actions
if (!$axeh) {
	$axeh = 'dt_jour';
	$axev = 'id_type_poste';
	$axei = 'id_utilisateur';
}
if ($dda and $ddb) {
	$temp = ${'axe'.$dda};
	${'axe'.$dda} = ${'axe'.$ddb};
	${'axe'.$ddb} = $temp;	
}

//remplissage des axes
$id_type_poste = req_pre("select id_type_poste as id, lb_type_poste as lb from gp_type_poste");
$id_utilisateur = req_pre("select id_utilisateur as id, nom as lb from utilisateur where id_profil = 10");
$dt_jour = req_pre("select CURDATE() as id, CURDATE() as lb")+["2019-04-06"=>"2019-04-06"]+["2019-04-07"=>"2019-04-07"];

//remplissage du tableau
$req = new db_sql("
	select
		*
	from
		gp_affectation");
while ($ligne = $req->n()) {
	$affectation[$ligne[$axev]][$ligne[$axeh]][$ligne[$axei]] = $ligne[nb_heure];
}

function affiche_total ($total,$axea,$valaxea,$axeb,$valaxeb) {
	//Je les remets dans l'ordre
	if($axea>$axeb) {
		$temp = $axea;
		$axea = $axeb;
		$axeb = $temp;
		$temp = $valaxea;
		$valaxea = $valaxeb;
		$valaxeb = $temp;
	}
	$lb_tot = "<div class = \"case_inactive\">".$total."</div>";
	if ($axea == "dt_jour"){
		if ($axeb == "id_type_poste"){
			$pre = ($prev_poste[$valaxea][$valaxeb]*1)."/";
		} else if ($axeb == "id_utilisateur"){
			$post = "/".($capa_util[$valaxea][$valaxeb]*1);
		}
	}
	return "<td> $pre </td><td> $lb_tot </td><td> $post </td>";
}

?>
<body class="application">
<style>
	td {
	    font-size: 10px;
		white-space: nowrap		
	}
	.case_inactive {
		border-style: solid; 
		border-width: 1px; 
		width: 20px; 
		background-color: Gainsboro;
	}
</style>
<table>
<thead>
	<tr>
		<td></td><?
		foreach ($$axeh as $id_h=>$lb_h){?>
		<td class="resultat_tittle dd" axe = "h"><?
			echo $lb_h?>
		</td><?
		}?>
	</tr>
</thead>
<tbody><?
	foreach ($$axev as $id_v=>$lb_v){?>
	<tr  bgcolor="<? echo alternat($z++) ?>"><td class="dd" axe="v"><? echo $lb_v ?></td><?
		foreach ($$axeh as $id_h=>$lb_h){
			// Cellule centrale ?>
			<td class="dd" axe="i"><?
			if (is_array($affectation[$id_v][$id_h])){?>
			<table><?
				$total_interne = 0;
				foreach ($affectation[$id_v][$id_h] as $id_i=>$nb_heure) {?>
					<tr>
						<td><?
							echo $$axei[$id_i];?>
						</td>
						<td></td>
						<td><?
							echo champ_ouvert_droit("O","nb_heure",$nb_heure);
							$total_vertical[$id_h][$id_i] += $nb_heure;
							$total_horizontal[$id_v][$id_i] += $nb_heure;
							$total_interne += $nb_heure;
							$total_interne_global[$id_i] += $nb_heure;?>
						</td>
						<td></td>
					<tr><?
				}?>
				<tr>
					<td></td><?
					echo affiche_total ($total_interne,$axev,$id_v,$axeh,$id_h);?>
				<tr>			
			</table><?
			}?>
			</td><?
		} 
		// Cellule de total de droite ?>
		<td class="resultat_tittle"><?
		if (is_array($total_horizontal[$id_v])) {?>
		<table><?
			$total_interne = 0;
			foreach ($total_horizontal[$id_v] as $id_i=>$nb_heure) {?>
				<tr>
					
					<td><?
						echo $$axei[$id_i];?>
					</td><?
					echo affiche_total ($nb_heure,$axev,$id_v,$axei,$id_i);
					$total_interne += $nb_heure;?>
				<tr><?
			}?>
			<tr>
				<td
				</td>
				<?
				echo affiche_total ($total_interne,$axev,$id_v,"","");?>
			<tr>
		</table><?
		}?>
		</td>
	</tr><?
	}
	// Ligne de total du bas
	?>	
	<tr>
		<td></td><?
		foreach ($$axeh as $id_h=>$lb_h){?>
			<td class="resultat_tittle"><?
			if (is_array($total_vertical[$id_h])) {?>
			<table><?
				$total_interne = 0;
				foreach ($total_vertical[$id_h] as $id_i=>$nb_heure) {?>
					<tr>
						<td><?
							echo $$axei[$id_i];?>
						</td><?
						echo affiche_total ($nb_heure,$axeh,$id_h,$axei,$id_i);
						$total_interne += $nb_heure;?>
					<tr><?
				}?>
				<tr>
					<td>
					</td>
					<?
					echo affiche_total ($total_interne,$axeh,$id_h,"","");?>
				<tr>
			</table><?
			}?>
			</td><?
		}?>
		<td class="resultat_tittle"><?
		if (is_array($total_interne_global)) {?>
		<table><?
			$total_interne = 0;
			foreach ($total_interne_global as $id_i=>$nb_heure) {?>
			<tr>
				<td>
				<?
				echo $$axei[$id_i];
				?>
				</td><?
				echo affiche_total ($nb_heure,$axei,$id_i,"","");
				$total_interne += $nb_heure;?>
			<tr><?
			}?>
			<tr>
				<td>
				</td><?
				echo affiche_total ($total_interne,"","","","");?>
			<tr>
		</table><?
		}?>
		</td>
	</tr>
</tbody>
</table>


<form id="drag_drop" method="post">
<? 
champ_cache ("axeh",$axeh); 
champ_cache ("axev",$axev); 
champ_cache ("axei",$axei); 

champ_cache ("dda",""); 
champ_cache ("ddb","");?>
</form>
<script>
	$(document).ready(function() {
		$(".dd").draggable({
			revert: true,
			revertDuration: 10,
			start: function() {

				$("[name='dda']").val($(this).attr("axe"));
			}
		});
		$(".dd").droppable({
			drop: function() {
				$("[name='ddb']").val($(this).attr("axe"));
				$("#drag_drop").submit();
			}
		});
	});
</script>

</body>
<?
include ("ress/enpied.php");
?>