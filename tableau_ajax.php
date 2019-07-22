<?
include ("ress/register_globals.php");
register_globals('gp');
include ("ress/var_session.php");
ini_set("ress/auto_detect_line_endings", true);
include_once 'ress/db_mysql.php';
include_once 'ress/util.php';

recupere ("NUM");
recupere ("draw");
recupere ("start");
recupere ("length");
recupere ("order");

$start = $start+1; //oracle commance à 1

$titres = $_SESSION[$GLOBALS["INSTANCE"]][xl_tableau][$NUM][titres];
if (is_array($order)) {
	$champ_ordre = $titres[$order[0][column]];
	$champ_dir = $order[0][dir];
}

if ($req = $_SESSION[$GLOBALS["INSTANCE"]][xl_tableau][$NUM][req]) {
	if ($champ_ordre and $length!="-1") {
		$liste = req_mul("
			select * from (
				select 
					req1.*
				from 
					($req) as req1
			) as req2
			limit $start, $length
		");
	} else {
		$liste = req_mul($req);		
	}
	$reponse[recordsTotal] = count($liste)*1;
	$reponse[recordsFiltered] = count($liste)*1;
} else {
	$tableau = unserialize(file_get_contents ("./../temp/data_".$NUM.".dat"));
	$i = 1;
	if ($champ_ordre and $length!="-1") {
		foreach($tableau as $value) {
			$index = $value[$champ_ordre];
			if (substr($champ_ordre, 0, 5) == 'date_') {
				$index = formateDate($index,2);
			}		
			$index="I_".$index.'_'.$i;
			$tableau_trie[$index] = $value;
			$i++;
		}
		ksort($tableau_trie);
		if ($champ_dir == "desc") {
			$tableau_trie = array_reverse($tableau_trie);
		}
	} else {
		$tableau_trie = $tableau;
	}

	if ($length == -1) {
		$liste = $tableau_trie;
	} else {
		$liste = array_slice($tableau_trie, $start-1, $length);
	}
	$reponse[recordsTotal] = count($tableau);
	$reponse[recordsFiltered] = count($tableau);
}

$lignes = [];
foreach ($liste as $ligne) {
	unset ($ligne["ploik_c"]);
	foreach ($ligne as $n=>$val) {
		
		if (substr($n, 0, 8) == 'montant_') {
			$val = monetise($val);
		}
		if (substr($n, 0, 5) == 'date_') {
			$val = formateDate($val);
		}
		if (substr($n, 0, 7) == 'number_') {
			$val = numerise($val);
		}
			
		$val = utf8_encode($val);
		$val = str_replace("/","\\/",$val);
		$val = str_replace("\"","\\\"",$val);
		$val = str_replace("\n"," ",$val);
		$val = str_replace("\r"," ",$val);
		$val = str_replace("	"," ",$val);

		$ligne[$n] = $val;
	}
	$lignes[] = ("[\"".implode("\",\"",$ligne)."\"]");
}
$data = "[".implode(",",$lignes)."]";

$reponse[draw] = $draw*1;

$reponse[data] = "DATA";

$reponset = json_encode($reponse);

echo str_replace("\"DATA\"",$data,$reponset);