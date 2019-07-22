<?

/*-------- Anciennes variables de session -------*/
session_set_cookie_params(65535);
session_cache_expire(24*60);
ini_set("session.gc_maxlifetime",65535);
//ini_set(session.save_path,"");
session_start();
$session_client_liste_id_utilisateur = $_SESSION[client_liste_id_utilisateur];
$session_client_liste_prio = $_SESSION[client_liste_prio];
$session_client_liste_raison_sociale = $_SESSION[client_liste_raison_sociale];
$session_client_liste_siret = $_SESSION[client_liste_siret];
$session_client_liste_code_naf = $_SESSION[client_liste_code_naf];
$session_client_liste_ville = $_SESSION[client_liste_ville];
$session_client_liste_telephone = $_SESSION[client_liste_telephone];
$session_client_liste_cp = $_SESSION[client_liste_cp];
$session_client_liste_code_effectif = $_SESSION[client_liste_code_effectif];
$session_client_liste_code_effectif_o = $_SESSION[client_liste_code_effectif_o];
$session_client_liste_id_gamme = $_SESSION[client_liste_id_gamme];
$session_client_liste_id_type = $_SESSION[client_liste_id_type];
$session_client_liste_id_marquage = $_SESSION[client_liste_id_marquage];

$session_contact_liste_raison_sociale = $_SESSION[contact_liste_raison_sociale];
$session_contact_liste_ville = $_SESSION[contact_liste_ville];
$session_contact_liste_cp = $_SESSION[contact_liste_cp];
$session_contact_liste_id_civilite = $_SESSION[contact_liste_id_civilite];
$session_contact_liste_nom = $_SESSION[contact_liste_nom];
$session_contact_liste_prenom = $_SESSION[contact_liste_prenom];
$session_contact_liste_telephone = $_SESSION[contact_liste_telephone];
$session_contact_liste_mail = $_SESSION[contact_liste_mail];
$session_contact_liste_id_decision = $_SESSION[contact_liste_id_decision];
$session_contact_liste_id_fonction = $_SESSION[contact_liste_id_fonction];
$session_contact_liste_id_utilisateur = $_SESSION[contact_liste_id_utilisateur];
$session_contact_liste_id_type = $_SESSION[contact_liste_id_type];

$session_interaction_liste_id_utilisateur = $_SESSION[interaction_liste_id_utilisateur];
$session_interaction_liste_date_crea_d = $_SESSION[interaction_liste_date_crea_d];
$session_interaction_liste_date_crea_f = $_SESSION[interaction_liste_date_crea_f];
$session_interaction_liste_date_prev_d = $_SESSION[interaction_liste_date_prev_d];
$session_interaction_liste_date_prev_f = $_SESSION[interaction_liste_date_prev_f];
$session_interaction_liste_notes = $_SESSION[interaction_liste_notes];
$session_interaction_liste_id_media = $_SESSION[interaction_liste_id_media];
$session_interaction_liste_id_teneur = $_SESSION[interaction_liste_id_teneur];
$session_interaction_liste_id_secteur = $_SESSION[interaction_liste_id_secteur];
$session_interaction_liste_id_marquage = $_SESSION[interaction_liste_id_marquage];
$session_interaction_liste_id_statut = $_SESSION[interaction_liste_id_statut];
$session_interaction_liste_commentaire = $_SESSION[interaction_liste_commentaire];

$session_affaire_liste_id_affaire = $_SESSION[affaire_liste_id_affaire];
$session_affaire_liste_id_utilisateur = $_SESSION[affaire_liste_id_utilisateur];
$session_affaire_liste_date_crea_d = $_SESSION[affaire_liste_date_crea_d];
$session_affaire_liste_date_crea_f = $_SESSION[affaire_liste_date_crea_f];
$session_affaire_liste_date_prev_d = $_SESSION[affaire_liste_date_prev_d];
$session_affaire_liste_date_prev_f = $_SESSION[affaire_liste_date_prev_f];
$session_affaire_liste_id_statut = $_SESSION[affaire_liste_id_statut];
$session_affaire_liste_id_statut_adv = $_SESSION[affaire_liste_id_statut_adv];
$session_affaire_liste_id_secteur = $_SESSION[affaire_liste_id_secteur];
$session_affaire_liste_id_marquage = $_SESSION[affaire_liste_id_marquage];
$session_affaire_liste_id_type = $_SESSION[affaire_liste_id_type];
$session_affaire_liste_commentaire = $_SESSION[affaire_liste_commentaire];

?>
