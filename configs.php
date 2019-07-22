<?
include_once("ress/entete.php");
require_once("c_o_dataset.php");
?>
<link rel="stylesheet" type="text/css" href="ress/css/bootstrap-combined.min.css">
<script type="text/javascript">
$(document).on("click", ".btn-info", function () {
	 if (typeof $(this).data('id') !== 'undefined') {
        $("[name='id']").val($(this).data('id'));
    }
    if (typeof $(this).data('idligne') !== 'undefined') {
    	var id_data = $(this).data('idligne');
        $("[name='id_data']").val(id_data);
        $.ajax({type:'POST', url: 'ajax_utils.php', data:"id_data="+id_data+"&ope=1", success: function(response) {
				var tab = response.split('_SEP_');
				$("[name='field']").val(tab[1]);  
				$("[name='valeur_stockee']").val(tab[2]);
				$("[name='valeur_affichee']").val(tab[3]);
				$("[name='FDV']").prop('checked', tab[4]=="1"); 
				$("[name='IN']").prop('checked', tab[5]=="1");    
        }});
    }
    else {
    	$("[name='id_data']").val("");
    	$("[name='field']").val(""); $("[name='valeur_stockee']").val(""); $("[name='valeur_affichee']").val("");
    	$("[name='IN']").prop('checked', false); $("[name='FDV']").prop('checked', false);  
    }
    $("[name='field']").val($(this).data('field'));
});
//<![CDATA[
$(window).load(function(){

$('#myModal').on('shown', function (e) {
	var numItems = $('.modal-backdrop').length;
	while(numItems>1){
    	$('.modal-backdrop').remove();numItems = $('.modal-backdrop').length;
    }
    $('body').on('wheel.modal mousewheel.modal', function () {return false;});
}).on('hidden', function () {
    $('body').off('wheel.modal mousewheel.modal');
});

});//]]> 
</script>
<script type="text/javascript">
function showData(val){
   var tab = val.split('_SEP_');
   var popURL = "admin_data.php?field="+tab[0]+"&id="+tab[1];
   var popID = '#admindata'; 
   $(popID).load(popURL, function() {
			$(popID).removeClass("invisible");
			$(popID).modal({ backdrop: 'static', keyboard: false}) ;
   });
}
</script>
<body class="application">
<?
$d = dir("param_2_produits");
while (false !== ($entry = $d->read())) {
   if (strlen($entry)>2) {
   	if (file_exists("param_2_produits/".$entry))
   		include ("param_2_produits/".$entry);
	}
}
$d->close();
$id_div=1;
ksort($produits);
foreach($produits as $produit=>$type_dataset_ligne) {
	echo "<p><a class='accordeon' id='".str_replace(' ', '_', $produit)."'><B>".$produit."</B></a></p>";

	if (file_exists("param_3_datasets/".$type_dataset_ligne[type_dataset_ligne].".php"))
		@include ("param_3_datasets/".$type_dataset_ligne[type_dataset_ligne].".php");
	if (is_array($datasets[$type_dataset_ligne[type_dataset_ligne]][champs])) {
		echo '<div style="display:none" id ="_'.str_replace(' ', '_', $produit).'" check_vis="0">';
		echo '<b>&nbsp&nbsp;Champs de la feuille de cote</b>';
		foreach($datasets[$type_dataset_ligne[type_dataset_ligne]][champs] as $champ => $infos) {
			if (file_exists("param_4_champs/".$champ.".php")) {
				@include ("param_4_champs/".$champ.".php");
			}
			$affich="";
			if($champs[$champ][type] =='lov' ) {
				if ($champs[$champ][tablecible]) {
					echo "<BR>&nbsp&nbsp&nbsp&nbsp;".$champ;
					$req= new db_sql($champs[$champ][tablecible]);
					while ($req->n()) {
						echo "<BR>&nbsp&nbsp&nbsp&nbsp;&nbsp&nbsp&nbsp&nbsp;".$req->f($champs[$champ][champscible])." (".$req->f($champs[$champ][champstocke]).")" ;
					}
				} else {
					$affich="<BR>&nbsp&nbsp&nbsp&nbsp;<a href='#myModal' role='button' class='btn btn-info' data-id='". $id_div."' data-field='". $champ."' data-toggle='modal'>".$champ."</a><div id='".$id_div."'><table width='50%'>";
					$req= new db_sql("select * from champ_lov_valeurs where field='$champ'");
					while ($req->n()) {
						$affich.="<tr><td class='resultat_list'>&nbsp&nbsp&nbsp&nbsp;&nbsp&nbsp&nbsp&nbsp;<a href='#myModal' role='button' class='btn btn-info' data-id='". $id_div."' data-field='". $champ."' data-idligne='". $req->f('id_data')."' data-toggle='modal'>" . $req->f('valeur_affichee'). "</a></td><td class='resultat_list'>" . getOrigine(FDV,$req->f('FDV')). "</td><td class='resultat_list'>" . getOrigine(IN,$req->f('IN')). "</td></tr>";
					}
					$affich.="</table></div>";
					$id_div++;
					echo $affich;
				}
			} else {
				echo "<BR>&nbsp&nbsp&nbsp&nbsp;".$champ;
			}
		}
		foreach($produits[$produit]['of'] as $nb => $of) {
			if($of) {
				if (file_exists("param_3_datasets/".$of.".php")) {
					include ("param_3_datasets/".$of.".php");
					if(is_array($datasets)) {
						foreach($datasets[$of]['champs'] as $id => $donnes) {
							if(trim($donnes['fonction_init'])) {
								$produits[$produit]['fonction_init'][$of][$id] = $donnes['fonction_init'];
							}
						}
					}
				}
			}
		}
		if( count($produits[$produit]['fonction_init']) > 0) {
			echo '<br><b>&nbsp&nbsp;Fonctions par OFs</b>';
		
			foreach($produits[$produit]['fonction_init'] as $nom => $of) {
				echo "<br><b>&nbsp&nbsp&nbsp&nbsp;".$nom."</b>";
				foreach($of as $champ_tech => $fonction_init) {
					echo "<BR>&nbsp&nbsp&nbsp&nbsp;&nbsp&nbsp&nbsp&nbsp;".$fonction_init;
				}
			}
		}
		echo '</div>';
	}
}
?>
<script>
	$('body').on('click', '.accordeon',  function() {
		var produit = $(this).attr('id');
		if($('#_'+produit).attr('check_vis') == 0) {
			$('#_'+produit).css('display', 'block');
			$('#_'+produit).attr('check_vis', '1');
		} else {
			$('#_'+produit).css('display', 'none');
			$('#_'+produit).attr('check_vis', '0');
		}
	});
</script>
<!--<div class="modal invisible" style="width: auto!important;position: fixed;left:30%;" id="admindata"></div>  -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto!important;position: fixed;left:30%;display: none;">
  <!--<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>-->
  <? include_once ("admin_data.php"); ?>
</div>