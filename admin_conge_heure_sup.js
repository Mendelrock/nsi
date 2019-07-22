$(function () {
	var isMouseDown = false;
	var isSelected = false;
	$("#tab_chs tbody td")
		.mousedown(function () { 
			isMouseDown = true;
			if($(this).attr("data-date")!=null){
				$(this).toggleClass("highlighted");
			}
			return false;
		})
		.mouseover(function () {
			if (isMouseDown) {
				if($(this).attr("data-date")!=null){
					$(this).toggleClass("highlighted");
				}
			}
		});

	$(document)
		.mouseup(function () {
			isMouseDown = false;
		});
	
	$("input[name='type_mod']").change(function(){
		var radioValue = $("input[name='type_mod']:checked").val();
		if (radioValue == "C") {
			$("#HS input").attr("disabled", true).val("");
			$("#C select").attr("disabled", false);
		} else {
			$("#C select").attr("disabled", true).val("");
			$("#HS input").attr("disabled", false);
		}
	});
	$("input[name='type_mod']").change();
	
	$("#appliquer_mod").click(function(e){
		e.preventDefault();
		var data = {};
		var type = $("input[name='type_mod']:checked").val();
		var mod = {};
		if(type == "C"){
			mod[type] = $('select[name="motif"]').val();
		} else {
			mod[type] = $('input[name="heures_sup"]').val();
			if(!mod[type]){
				mod[type] = "0";
			}
		}
		$('.highlighted').each(function(){
			var user = $(this).parent('tr').data("utilisateur");
			var date = $(this).data("date");
			if(!data[user]){
				data[user] = {};
			}
			data[user][date] = mod;
		});
		$.post("admin_conge_heure_sup_action.php", { "data" : data }, function(donnees){
			location.reload(true);
		});
	});
});

