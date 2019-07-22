<?php
include("ress/util.php");
include("ress/db_mysql.php");
include("ress/rsslib.php");
require_once("fonction.php");
require_once("c_o_dataset.php");
require_once("c_parm.php");

set_time_limit(0);
include ("ress/var_session.php");


if (!$_SESSION[id_droit]["Menu.ImportSR"]){
	die ("Vous n'avez pas accès à cet écran");
}
$nbcommande_limit = 100000;
?>
<html>
<head>
<meta >
<meta http-equiv="Content-Type" content="text/html; charset=latin1">
<link rel="stylesheet" href="./client/style.css" type="text/css">
<SCRIPT SRC="ress/util.js"></SCRIPT>
<script type="text/javascript" src="ress/jquery.js"></script>
</head>
<body>
<? 
$debug=$_GET[debug];
//$debug="SR-2015-08-06-46398";
//$debug="SR-2015-08-18-46772";
$origine_import=$_GET[o];
if (!$debug) { ?>
<div id="loading" style="position:fixed; top:10px; left:10px;"><img src="images/loading.gif"></div>
<? } ?>
<script type="text/javascript">
function call(url,donnees,id){
	donnees = donnees.replace(/(\r\n|\n|\r)/gm," ");
	updateCorrespondance(url,donnees);
	document.getElementById(id).innerHTML ="";
	alert("Mise à jour effectuée !!!");
}
function callToileUpdate(url,donnees,idouvert,id,valeur) {
	if($('#'+donnees+'').val()=="" || $('#'+idouvert+'').val()=="") {
		alert("Veuillez renseigner les champs !!!");
	} else {
		var valeursite = id+'_';
		var data = $('#'+valeursite+'').val();
		data = data.replace(/(\r\n|\n|\r)/gm," ");
		updateCorrespondance(url,'site_valeur='+data+'&'+valeur+'&gamme='+$('#'+donnees+'').val()+'&couleur='+$('#'+idouvert+'').val()+'&ope=6');
		document.getElementById(id).innerHTML ="";
		alert("Mise à jour effectuée !!!");
	}
}
</script>
<?php

if ($debug) {
	$ids_doc_to_delete = array();
	
	$req = new db_sql("
		select
			id_doc
		from
			doc,
			zdataset_fdcentete dt
		where
			dt.numcommande_fdc = '$debug' AND
			doc.id_dataset_entete = dt.id_dataset
		;
		");
	while ($req->n()) {
		$ids_doc_to_delete[] = $req->f('id_doc');
	}
	if (count($ids_doc_to_delete)) {
		$ids_doc_to_delete_copy = $ids_doc_to_delete;
		foreach ($ids_doc_to_delete_copy as $id_doc) {
			$req = new db_sql("
								SELECT
									id_doc
								FROM
									doc,
									(SELECT id_dataset FROM zdataset_bonentete WHERE fdc = '".$id_doc."'
										UNION
									SELECT id_dataset FROM zdataset_commandeentete WHERE fdc = '".$id_doc."'
										UNION
									SELECT id_dataset FROM zdataset_ofentete WHERE fdc = '".$id_doc."') AS dt
								WHERE
									doc.id_dataset_entete = dt.id_dataset
								");
			while ($req->n()) {
				$ids_doc_to_delete[] = $req->f('id_doc');
			}
			foreach ($ids_doc_to_delete as $id_doc_to_delete) {
				$erase = new db_sql("delete from doc where id_doc = $id_doc_to_delete");
				$erase = new db_sql("delete from dataset where not exists (select 1 from doc where id_dataset = id_dataset_empied) and
																		not exists (select 1 from doc where id_dataset = id_dataset_entete) and
																		not exists (select 1 from doc_ligne where dataset.id_dataset = doc_ligne.id_dataset) and
																		not exists (select 1 from doc_groupe_ligne where dataset.id_dataset = doc_groupe_ligne.id_dataset)");	
				$zdatasets = charge("select distinct type_dataset from dataset");
				foreach($zdatasets as $type_zdataset){
					if ($type_zdataset) {
						$erase = new db_sql("delete from `zdataset_".$type_zdataset."` where not exists (select 1 from dataset where dataset.id_dataset = `zdataset_".$type_zdataset."`.id_dataset)");
					}
				}
			}
		}
	}
}

/*

Nettoyage normalement plus utile depuis la Contrainte d'intégrité

delete from doc where id_doc = ***

Normalement plus utile depuis la Contrainte d'intégrité  :  delete from doc_ligne where not exists (select 1 from doc where doc.id_doc = doc_ligne.id_doc)
Normalement plus utile depuis la Contrainte d'intégrité : delete from doc_groupe_ligne where not exists (select 1 from doc where doc.id_doc = doc_groupe_ligne.id_doc)


Nettoyage des OF non attachés à des feuilles de cotes (fonctionne pas)

*/

$_SESSION[import_allquestions] = array();
$_SESSION[import_id_question] = 0;

$url_or[1]="https://www.stores-et-rideaux.com/cgi-bin/WebObjects/StoresEtRideauxCom.woa/wa/net.ingencys.indeXys.IndeXysSyndicationDirectAction/listForSyndicationFeed?feed=2e0d035e-0a0f-47ee-a771-f3078b93faff";
$url_or[2]="http://www.prosolair.com/cgi-bin/WebObjects/ProSolairCom.woa/wa/net.ingencys.indeXys.IndeXysSyndicationDirectAction/listForSyndicationFeed?feed=eebb563a-5f43-4c49-a091-093c003b6cb7";
$url_or[4]="http://www.tende-e-tende.it/cgi-bin/WebObjects/TendeETendeIt.woa/wa/net.ingencys.indeXys.IndeXysSyndicationDirectAction/listForSyndicationFeed?feed=2e0d035e-0a0f-47ee-a771-f3078b93faff";
$url = $url_or[$origine_import];
$RSS_Content = RSS_Links($url, 1, $origine_import);

foreach($RSS_Content as $commande_) {
	$title = $commande_["title"];
	$link = $commande_["link"];
	if($nbcommande_limit and $nbcommande++ >= $nbcommande_limit) break;
	if ($debug and $debug != $title) continue;
	if ($debug and $debug == $title) {
		echo ("<a href = 'import_rss_ajax.php?debug=1&o=".$origine_import."&title=".$title."&link=".urlencode($link)."' target = '_new'>".$title."</a>");
		die();
	}
	if(req_sim("SELECT count(1) as compte from zdataset_fdcentete WHERE numcommande_fdc = '$title'","compte")>0) continue;
	echo "<div style= \"display:inline-block;vertical-align:top\">".$title."&nbsp;</div><div id=\"".$title."\" class=\"commande\" name=\"".urlencode($link)."\" style=\"display:inline-block\"></div><BR>";
	flush();
}
?>
<div id="fin"></div>
<script type="text/javascript">
	
function utf8_decode (str_data) {
  // http://kevin.vanzonneveld.net
  // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
  // +      input by: Aman Gupta
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Norman "zEh" Fuchs
  // +   bugfixed by: hitwork
  // +   bugfixed by: Onno Marsman
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: kirilloid
  // *     example 1: utf8_decode('Kevin van Zonneveld');
  // *     returns 1: 'Kevin van Zonneveld'

  var tmp_arr = [],
    i = 0,
    ac = 0,
    c1 = 0,
    c2 = 0,
    c3 = 0,
    c4 = 0;

  str_data += '';

  while (i < str_data.length) {
    c1 = str_data.charCodeAt(i);
    if (c1 <= 191) {
      tmp_arr[ac++] = String.fromCharCode(c1);
      i++;
    } else if (c1 <= 223) {
      c2 = str_data.charCodeAt(i + 1);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
      i += 2;
    } else if (c1 <= 239) {
      // http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
      i += 3;
    } else {
      c2 = str_data.charCodeAt(i + 1);
      c3 = str_data.charCodeAt(i + 2);
      c4 = str_data.charCodeAt(i + 3);
      c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
      c1 -= 0x10000;
      tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1>>10) & 0x3FF));
      tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
      i += 4;
    }
  }
  return tmp_arr.join('');
}	
	
function traite_prochaine_commande() {
	var prochain = $(".commande").first();
	var title = prochain.attr("id");
	if (prochain.length >= 1) {
		prochain.html("<font color = red>En cours ...</font>");
		$.ajax({ 
			type: "GET", 
		    url: "import_rss_ajax.php",
		    data: "o=<?echo $origine_import;?>&title="+title+"&link="+prochain.attr("name"),
		    success: function(msg){
		   	prochain.html(msg);
		   	prochain.removeClass("commande");
		   	traite_prochaine_commande();
			}
		})
	} else {
      $("#fin").html("<b>******** Fin du traitement ********</b>");
      $("#loading").hide();
   }
}

function relance(title, forcer) {
   var prochain = $("#"+title).first();
   prochain.html("<font color = red>En cours ...</font>");
   $("#loading").show();
   $("#fin").html("<b>******** Nouvelle tentative "+title+" ********</b>");
	$.ajax({ 
		type: "GET", 
	   url: "import_rss_ajax.php",
	   data: "o=<?echo $origine_import;?>&title="+title+"&link="+prochain.attr("name")+"&forcer="+forcer,
	   success: function(msg){ 
		   prochain.html(msg);
		   $("#fin").html("<b>******** Fin du traitement ********</b>");
		   $("#loading").hide();
		}
	})
}

$("#loading").show();
traite_prochaine_commande();        
</script>
</body>
</html>
