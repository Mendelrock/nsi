<?php
header('Content-type: text/html; charset=utf-8');
include("ress/entete.php");
if($_POST['ope']=="1"){
	$req="SELECT
                     *
                  FROM
                     champ_lov_valeurs WHERE id_data='".$_POST['id_data']."'";

	$requete= new db_sql();
	$requete->q("SET NAMES 'utf8'");
	$requete->q($req);
	while($requete->n()){
	 	echo "_SEP_" .$requete->f('field'). "_SEP_" .$requete->f('valeur_stockee'). "_SEP_" .$requete->f('valeur_affichee'). "_SEP_" . $requete->f('FDV'). "_SEP_" . $requete->f('IN');
	}
	
}else if($_POST['ope']=="2"){
	$IN = $_POST['IN'];
	$FDV = $_POST['FDV'];
	if(!$_POST['id_data']){
		$req="
                        INSERT INTO
                                champ_lov_valeurs(
                                field,
                                valeur_stockee,
                                valeur_affichee,
                                `IN`,
								FDV)
                        VALUES (
                                ".My_sql_format($_POST['field']).",
                                ".My_sql_format($_POST['valeur_stockee']).",
                                ".My_sql_format($_POST['valeur_affichee']).",
                                ".$IN.",
                                ".$FDV.")";
        $requete= new db_sql();
        $requete->q("SET NAMES 'utf8'");
	  	$requete->q($req);
  		
	}else{
		$req="
                     UPDATE
                        champ_lov_valeurs
                     SET
                        field=".My_sql_format($_POST['field']).",
                        valeur_stockee=".My_sql_format($_POST['valeur_stockee']).",
                        valeur_affichee=".My_sql_format($_POST['valeur_affichee']).",
                        `IN`=".$IN.",
                        FDV=".$FDV."
                     WHERE
                        id_data='$id_data'";
                                // Execution
        $requete= new db_sql();
        $requete->q("SET NAMES 'utf8'");
	  	$requete->q($req);
	}
	$req= "select * from champ_lov_valeurs where field='".$_POST['field']."'";
	$requete= new db_sql();
	$requete->q("SET NAMES 'utf8'");
	$requete->q($req);
	echo "<table width='50%'>";
	while ($requete->n()) {
	  	//echo "<tr><td class='resultat_list'><a href='#'  onclick=\"showData('".$requete->f('id_data')."_SEP_".$_POST['id']."')\">" . $requete->f('valeur_affichee'). "</a></td><td class='resultat_list'>" . getOrigine(SR,$requete->f('SR')). "</td><td class='resultat_list'>" . getOrigine(PS,$requete->f('PS')). "</td><td class='resultat_list'>" . getOrigine(FDV,$requete->f('FDV')). "</td></tr>";
	  	echo "<tr><td class='resultat_list'>&nbsp&nbsp&nbsp&nbsp;&nbsp&nbsp&nbsp&nbsp;<a href='#myModal' role='button' class='btn btn-info' data-id='". $_POST['id']."' data-idligne='". $requete->f('id_data')."' data-toggle='modal'>" . $requete->f('valeur_affichee'). "</a></td><td class='resultat_list'>" . getOrigine(FDV,$requete->f('FDV')). "</td><td class='resultat_list'>" . getOrigine(IN,$requete->f('IN')). "</td></tr>";
	}
	echo "</table>";

}
	    
?>