function bindAction(){
	$('[name=TYPE_POSTE]').off('change').change(function(){
		if($(this).val()){
			ajouterLigne($(this).val(), $(this).find("option:selected").text());
			$(this).val("");
		}
	});
}

function ajouterLigne(id, lb){
	var html = '<tr id="'+id+'"><td>'+lb+'<input type="hidden" name="ID_TYPE_POSTE[]" value="'+id+'"></td>';
	html += '<td><input type="text" name="NB_EFFICACITE['+id+']" value="100" size="30" maxlength="100"></td>';
	html += '<td><input type="checkbox" name="FG_PRINCIPAL['+id+']" value="1"></td>';
	html += '<td><button onclick="supprimerLigne('+id+')" class="requeteur_button" name="supprimer" value="Supprimer">Supprimer</button></td></tr>'
	$('#tab_postes').append(html);
}

function supprimerLigne(id){
	$('#'+id).remove();
}

function creerUtilisateur(){
	ouvrirModal("Nouvel utilisateur", "./admin_utilisateurs_detail.php", {}, "./admin_utilisateurs_action.php", bindAction);
}

function modifierUtilisateur(id){
	ouvrirModal("Edition utilisateur", "./admin_utilisateurs_detail.php", { ID_UTILISATEUR : id }, "./admin_utilisateurs_action.php", bindAction);
}