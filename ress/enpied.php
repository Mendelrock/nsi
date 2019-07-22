<div class="modal fade" id="popup_modal" tabindex="-1" role="dialog" style="width: auto">
	<div class="modal-dialog" role="document" style="display: table;">
		<div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title"></h2>
			</div>				
			<div class="modal-body ui-front"></div>			
			<div class="modal-footer">
				<img style="display:none; width : 25px; height: auto" id="loading_modal" src="./../ress/img/loading_small.gif">
				<img style="display:none; width : 15px; height: auto; vertical-align: -0.4em" id="save_finish" src="./../ress/img/ok-sign.png">
				<button type="button" class="btn btn-primary" id="enregistrer_modal"><span class="glyphicon glyphicon-ok"></span> Enregistrer</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Fermer</button>
			</div>
		</div>
	</div>
</div>
<?
if ($message) {
?>
<div id="dialog" title="Alert"></div>
<?
}
?>
<SCRIPT>
	<?
		if ($message) {
	?>
		afficherMessage({error : <? echo json_encode($message) ?>})
		$( "#dialog" ).dialog();
	<?
		}
	?>
	$( ".datepicker" ).datepicker({
		altField: "#datepicker",
		firstDay: 1,
		closeText: 'Fermer',
		prevText: 'Précédent',
		nextText: 'Suivant',
		currentText: 'Aujourd\'hui',
		monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
		dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
		dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
		weekHeader: 'Sem.',
		dateFormat: 'dd/mm/y'
	});
	//$(".datepicker").datepicker({dateFormat:'dd/mm/y'});
	$(".datepicker").change(function(){
	   while ($(this).val().match(/\D/)) {
		  $(this).val($(this).val().replace (/\D/,''));
	   }
	   $(this).val($(this).val().substr(0,6));
	   $(this).val($(this).val() + '000000'.substring($(this).val().length,6));
	   $(this).val($(this).val().substr(0,2)+'/'+$(this).val().substr(2,2)+'/'+$(this).val().substr(4,2));

	   if (($(this).val() == '00/00/00') || ($(this).val() == '')) {
		   $(this).next().val('');
		   return true;
	   } else {
			var dater = new Date($(this).val().substr(6,2),$(this).val().substr(3,2)-1,$(this).val().substr(0,2))
			if ((dater.getDate()!=$(this).val().substr(0,2)) || (dater.getMonth()+1!=$(this).val().substr(3,2)) || (dater.getYear()!=$(this).val().substr(6,2))) {
				alert('Format de date incorrect (jj/mm/aa)');
				$(this).focus();
				return false;
			} else {
				$(this).next().val('20'+$(this).val().substr(6,2)+'-'+$(this).val().substr(3,2)+'-'+$(this).val().substr(0,2));
				return true;
			}
		}
	});
	
	//Affiche les messages d'erreur, de warning ou d'information ($ok)

	function afficherMessage(messages, zone = "#dialog"){
		console.log(messages);
		var selector = $(zone);
		if(zone != "#dialog"){
			selector.css('visibility', 'hidden');
		}
		$('.message-erreur').remove();
		$('.message-warning').remove();
		$('.message-ok').remove();
		for(var key in messages){
			if(Array.isArray(messages[key])){
				for(x in messages[key]) {
					selector.after("<p class=\'message-"+key+"\'></p>");
					selector.next().html(messages[key][x] + "<br>");
				};
			} else {
				selector.after("<p class=\'message-"+key+"\'></p>");
				selector.next().html(messages[key] + "<br>");
			}
			
		}
		if(zone == "#dialog"){
			selector.dialog();
		} else {
			selector.css('visibility', 'visible');
		}
	}

	//Action standard du bouton enregistrer

	function sauvegardeStandard(){
		$("#save_finish").hide();
		$(".message-erreur").hide("fast");
		$("#loading_modal").show();
		url_save = $("#popup_modal #enregistrer_modal").attr("enregistrerVia");
		if(url_save){
			$.ajax({
				cache: false,
				type: 'POST',
				url: url_save,
				data: $('.modal-body input, textarea, select').serialize() + "&ACTION=submit",
				dataType:"json",
				success:function(data) {
					console.log(data);
					if(data){
						afficherMessage({ "erreur" : data }, "#message-erreur");
						$("#save_finish").hide();
					} else {
						$("#save_finish").show('fast');
						setTimeout(function() {
							$("#popup_modal").modal('hide');
							location.reload();
						}, 1000);
					}
				},
				error:function(data, status, error){
					afficherMessage({ "erreur" : [error.message] }, "#message-erreur");
				},
				complete : function(data, status){
					$("#loading_modal").hide();
				}
			});
		}
	}

	//Charge et ouvre la modale

	function ouvrirModal(titre, contenu, data, editable = "", callback = function(){}, btn_enregistrer="Enregistrer", fnctSauvegarde = sauvegardeStandard){
		$("#popup_modal .modal-title").text(titre);
		$("#loading_modal").hide();
		$("#save_finish").hide();
		$(".message-erreur").hide();
		$("#popup_modal #enregistrer_modal").attr("enregistrerVia", editable);
		if(editable){
			$("#popup_modal #enregistrer_modal").html('<span class="glyphicon glyphicon-ok"></span> '+btn_enregistrer);
			$("#popup_modal #enregistrer_modal").show();
		} else {
			$("#popup_modal #enregistrer_modal").hide();
		}
		$("#popup_modal .modal-body").load(contenu, data, function(){
			callback();
			$("#popup_modal .modal-body").prepend('<p id="message-erreur"></p>');
			$("#popup_modal #enregistrer_modal").off("click"); 
			$("#popup_modal #enregistrer_modal").click(fnctSauvegarde);
			$("#popup_modal").modal('show');
		});
		return false;
	}
</SCRIPT>
</body>
</html>
