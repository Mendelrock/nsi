<script type="text/javascript">
$(document).ready(function() {
	
     $('.btn-primary').click(function() {
     	 var field = $("[name='field']").val();
         var valeur_stockee = $("[name='valeur_stockee']").val();
         var valeur_affichee = $("[name='valeur_affichee']").val();
         var id = $("[name='id']").val();var id_data = $("[name='id_data']").val();
         var IN = $("[name='IN']").prop('checked')==true ? "1" : "0";
         var FDV = $("[name='FDV']").prop('checked')==true ? "1" : "0";
         if(field==""){
         	    alert("Champ est obligatoire !");
				return;
         }
         if(valeur_stockee==""){
         	    alert("Valeur stockée est obligatoire !");
				return;
         }
         if(valeur_affichee==""){
         	    alert("Valeur affichée est obligatoire !");
				return;
         }
         	$.ajax({type:'POST', url: 'ajax_utils.php', data:"field="+field+"&valeur_stockee="+valeur_stockee+"&valeur_affichee="+valeur_affichee+"&IN="+IN+"&FDV="+FDV+"&id="+id+"&id_data="+id_data+"&ope=2", success: function(response) {
                    $("#"+id).html(response);
                    $(".close").trigger("click");
                }});
		 
     });
});
</script>
<?
//include("ress/entete.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="client/style.css" type="text/css">
<link rel="stylesheet" href="ress/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="ress/jquery-ui/jquery-ui.css">
<script src="ress/jquery.js"></script>
<script src="ress/jquery-ui/jquery-ui.js"></script>
<SCRIPT src="ress/util.js"></SCRIPT>
<SCRIPT src="ress/js/bootstrap.min.js"></SCRIPT>
<div class="popup clearfix">
<div class="close-popup close" data-dismiss="modal"><img src="client/close.png"/></div>
        
<?
/*--------------- TRAITEMENT DES UTILISATEURS --------*/
If($ACTE){
                //Verification formulaire
                If( empty($field)){
                        $message="Le champ est absent";
                }
                If( empty($valeur_stockee)){
                        $message="La valeur stockée est absente";
                }
                If( empty($valeur_affichee)){
                        $message="La valeur affichée est absente";
                }
                If( empty($origine)){
                        $message="L'origine est absente";
                }
}
        //Ajouter un utilisateur
if(!$message){
        if($ACTE==1){
                //Verification du login
                        /*if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login'","compte")>0){
                                $message="Ce login existe déjà";
                        }*/
                 if(!$message){
                         $requete="
                        INSERT INTO
                                data(
                                field,
                                valeur_stockee,
                                valeur_affichee,
                                origine)
                        VALUES (
                                ".My_sql_format($field).",
                                ".My_sql_format($valeur_stockee).",
                                ".My_sql_format($valeur_affichee).",
                                ".My_sql_format($origine).")";
                                // Execution
                $resultat= new db_sql("$requete");
                        }
        }
        //Modifier un utilisateur
        if($ACTE==2) {
                //Verification du login
                        /*if(req_sim("SELECT count(1) as compte FROM utilisateur WHERE Login='$Login' AND
                                                Id_utilisateur <> '$Id_utilisateur'","compte")>0){
                                $message="Ce login existe déjà";
                        }*/
                        if(!$message){
                $requete="
                     UPDATE
                        data
                     SET
                        field=".My_sql_format($field).",
                        valeur_stockee=".My_sql_format($valeur_stockee).",
                        valeur_affichee=".My_sql_format($valeur_affichee).",
                        origine=".My_sql_format($origine)."
                     WHERE
                        id_data='$id_data'";
                                // Execution
                $resultat = new db_sql("$requete");
                        }
        }
}
?>
<table class="menu_haut_resultat">
    <tr>
       <td class="interne_actif">Liste des valeurs</td>
       <td></td>
    </tr>
</table>
<?
if(isset($_GET['field']))$field = $_GET['field'];
$id = $_GET['id'];
 /*---------------------------------------------*/
        // Requete, SELECT collecte des données principales
        $requete="SELECT
                     *
                  FROM
                     champ_lov_valeurs WHERE id_data='$field'";
        /*----------- EXECUTION ---------*/
        $resultat = new db_sql($requete);
        /*----------- AFFICHAGE ---------*/
        // Affichage des en_têtes
        ?>
<table class="resultat">
   <tr>
        <!--<td class="resultat_tittle">Champ</td>-->
        <td class="resultat_tittle">Valeur stockée</td>
        <td class="resultat_tittle">Valeur affichée</td>
        <td class="resultat_tittle"><? echo FDV; ?></td>
        <td class="resultat_tittle"><? echo IN; ?></td>
        <td class="resultat_tittle">&nbsp;</td>
   </tr>
                <?       $update=false;
                //if(!$update){
                ?>
                  <form method="post" name="nouveau">
                  <tr>
                  <!--<td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "field", $field, 30,30, "O"); ?></td>-->
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "valeur_stockee", $valeur_stockee, 50,50, "O"); ?></td>
                  <td class='resultat_list' bgcolor="<? echo alternat($z); ?>"><? champ_ouvert_droit("admin", "valeur_affichee",$valeur_affichee, 50, 50, "O"); ?></td>
                  <td class='res_cb_list' bgcolor='<? echo alternat($z) ?>'><input name="FDV" type="checkbox"  /></td>
				   <td class='res_cb_list' bgcolor='<? echo alternat($z) ?>'><input name="IN" type="checkbox"   /></td>
                  <td bgcolor="<? echo alternat($z) ?>">
                   <!--<input class="requeteur_button" type="submit" name="creer" value="Créer"  >-->
                   <input type="hidden" name="id" value="<? echo $id;?>">
                   <input type="hidden" name="id_data" value="">
                   <input type="hidden" name="field" value="">
                   </td>
                   </tr>
                   </form>
                   <?//} ?>
                   </table>
<?
//include ("ress/enpied.php");
?>
<div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
    <button class="btn btn-primary">Enregistrer</button>
  </div>
</div>