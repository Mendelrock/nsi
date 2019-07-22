<?
/*-----------------PARAMETRES---------------------
/* Internes
/* $Id_utilisateur
/* $Nom
/* $Prenom
/* $Login
/* $Pwd
/* $Droit_connect
/* $Id_responsable
/* $Id_profil
/*
/*------------------------------------------------*/
include("ress/entete.php");

/*---------------------------------------------*/
// Requete, SELECT collecte des données principales
$requete="SELECT
		Id_utilisateur,
		Nom,
		Prenom
	FROM
		utilisateur ";

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);
?>
<body class="application">

<table class="menu_haut_resultat">
	<tr>
		<td class="interne_actif">Liste des utilisateurs</td>
		<td style="float:right">
			<button onclick="creerUtilisateur(<? echo $resultat->f("Id_utilisateur")?>)" class="requeteur_button" name="creer" value="Creer">Nouvel utilisateur</button>
		</td>
	</tr>
</table>
<table class="resultat">
   <thead>
	   <tr>
			<th class="resultat_tittle">Nom</th>
			<th class="resultat_tittle">Prénom</th>
			<th class="resultat_tittle">&nbsp;</th>
	   </tr>
   <thead>
	<?        // Boucle de lecture
		while($resultat->n()){
			
	?>
	<tbody>
		<tr>
			<td class="resultat_list" bgcolor="<? echo alternat($z); ?>" id="nom"><? echo $resultat->f("Nom"); ?></td>
			<td class="resultat_list" bgcolor="<? echo alternat($z); ?>" id="prenom"><? echo $resultat->f("Prenom"); ?></td>
			<td bgcolor="<? echo alternat($z) ?>">
				<button onclick="modifierUtilisateur(<? echo $resultat->f("Id_utilisateur")?>)" class="requeteur_button" name="modifier" value="Modifier">Modifier</button>
			</td>
		</tr>
	</tbody>
	<?
			$z++;
		}
	?>
</table>
<script src="admin_utilisateurs.js"></script>
<?
include ("ress/enpied.php");
?>