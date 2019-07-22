<?
	include_once "ress/util.php";
	include_once "ress/db_mysql.php";
	include_once "ress/register_globals.php";
	register_globals('gp');
	
	$no_load = 1;
	
	if($ID_UTILISATEUR){
		champ_cache("Id_utilisateur", $ID_UTILISATEUR);
		/*---------------------------------------------*/
		// Requete, SELECT collecte des données principales
		$requete="
			SELECT
				Nom,
				Prenom,
				Login,
				Pwd,
				Droit_connect,
				Id_responsable,
				Id_profil,
				Mail
			FROM
				utilisateur
			WHERE
				Id_utilisateur = ".$ID_UTILISATEUR
		;

		/*----------- EXECUTION ---------*/
		$resultat = new db_sql($requete);
		$resultat->n();
		$Nom = utf8_encode($resultat->f("Nom"));
		$Prenom = utf8_encode($resultat->f("Prenom"));
		$Login = $resultat->f("Login");
		// Traitement password
		if($resultat->f("Pwd")) {
			$Pwd_ref="O";
		} else {
			$Pwd_ref="";
		}
		$Droit_connect = $resultat->f("Droit_connect");
		$Id_responsable = $resultat->f("Id_responsable");
		$Id_profil = $resultat->f("Id_profil");
		$Mail = $resultat->f("Mail");
		
		$postes = req_mul('
			SELECT
				gtp.id_type_poste,
				gtp.lb_type_poste,
				gtpu.nb_efficacite,
				gtpu.fg_principal
			FROM
				gp_type_poste gtp,
				gp_type_poste_utilisateur gtpu
			WHERE
				gtp.id_type_poste = gtpu.id_type_poste AND
				gtpu.id_utilisateur = '. $ID_UTILISATEUR.'
			ORDER BY gtp.id_type_poste
		');
	}
?>
<table>
	<tbody>
		<tr>
			<th>Nom</th>
			<td><? champ_ouvert_droit("O", "Nom", $Nom, 30, 30, "O"); ?></td>
			<th>Prénom</th>
			<td><? champ_ouvert_droit("O", "Prenom", $Prenom, 30, 30, "N"); ?></td>
		</tr>
		<tr>
			<th>Login</th>
			<td><? champ_ouvert_droit("O", "Login", $Login, 10, 10 , "O"); ?></td>
		</tr>
		<tr>
			<th>Pwd</th>
			<td><? champ_binaire_droit ("O", "Pwd_ref", "O", $Pwd_ref); ?></td>
			<th>Connexion</th>
			<td><? champ_binaire_droit ("O", "Droit_connect", "1", $Droit_connect); if($ACTE!=1){?><A href="#" onClick="window.open('admin_utilisateur_historique.php?Id_utilisateur=<? echo $ID_UTILISATEUR ?>&Nom=<? echo $resultat->f('Nom') ?>','affaire_detail','toolbar=0,location=0,directories=0,status=0,copyhistory=0,menuBar=0,scrollbars=1,Height=400,Width=200')">Histo</a><?}?></td>
		</tr>
		<tr>
			<th>Responsable</th>
			<td><? drop_down_droit ("O", "select Id_utilisateur, Nom from utilisateur".($ACTE==1?"":" where Id_utilisateur <> ".$ID_UTILISATEUR), "Id_responsable", "Id_utilisateur", "Nom",$Id_responsable, false, "utilisateur".$z, "N", "", " "); ?></td>
		</tr>
		<tr>
			<th>Profil</th>
			<td><? drop_down_droit ("O", "select Id_profil, Lb_profil from profil", "Id_profil", "Id_profil", "Lb_profil",$Id_profil, false, "utilisateur".$z, "O", "", ""); ?></td>
			<th>Mail</th>
			<td><? champ_ouvert_droit("O", "Mail", $Mail, 100, 30, "O"); ?></td>
		</tr>
		<tr><td colspan=4>&nbsp;</td></tr>
		<tr>
			<td colspan=4>
				<table>
					<thead>
						<tr>
							<th>Poste</th>
							<th>Efficacité</th>
							<th>Principal</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="tab_postes">
						<?
							foreach($postes as $poste){
							?>
								<tr id="<? echo $poste[id_type_poste]; ?>">
									<td><? echo $poste[lb_type_poste]; champ_cache("ID_TYPE_POSTE[]", $poste[id_type_poste]);?></td>
									<td><? champ_ouvert_droit("O", "NB_EFFICACITE[".$poste[id_type_poste]."]", $poste[nb_efficacite], 100, 30, "O");  ?></td>
									<td><? champ_binaire_droit("O", "FG_PRINCIPAL[".$poste[id_type_poste]."]", 1, $poste[fg_principal]); ?></td>
									<td><button onclick="supprimerLigne(<? echo $poste[id_type_poste]; ?>)" class="requeteur_button" name="supprimer" value="Supprimer">Supprimer</button></td>
								</tr>
							<?
							}
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan=4>
				Ajouter : <? drop_down_droit ("O", "SELECT id_type_poste AS id, lb_type_poste AS lb FROM gp_type_poste WHERE id_type_poste <> 18 UNION SELECT '' as id, '' as lb ORDER BY id", "TYPE_POSTE", "id", "lb",$type_poste, false, "utilisateur".$z, "O", "", ""); ?>
			</td>
		</tr>
	</tbody>
</table>