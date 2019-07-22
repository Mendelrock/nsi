<?php

$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][libelle] = "Bon de COMMANDE - STORE - BANNE - COFFRE - ELISSE";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][delai] = 12;
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][type_dataset_entete] = "commandeentete";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][type_dataset_empied] = "commandeempied_simple";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][type_dataset_ligne] = "COMMANDE - STORE - BANNE - COFFRE - ELISSE";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][orientation] = "L";// 'L' paysage, 'P' portrait
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][annuler][php]   = "doc.php?id_doc=. id_doc&ACTE=2";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][annuler][libelle] = "Annuler la commande";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][annuler][droit] = '($_SESSION["id_droit"]["commande"] && (req_sim ("SELECT statut_commande FROM zdataset_commandeentete WHERE id_dataset = ".$doc[id_dataset_entete], "statut_commande") == 1 || (req_sim ("SELECT statut_commande FROM zdataset_commandeentete WHERE id_dataset = ".$doc[id_dataset_entete], "statut_commande")) == 2 ))';
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][besoin][php]   = "besoin_list.php?ACTE=2&of=. id_doc";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][besoin][libelle] = "Voir les consommations";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][besoin][droit] = " \$_SESSION['id_droit']['Menu.Besoin'] ";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][fdc][php]   = "doc.php?onglet_courant=of&id_doc=. id_doc_fdc";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][fdc][libelle] = "Ouvrir la feuille de cote";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][imprimer][php]   = "javascript:window.open('imprimer_ofs.php?id_doc=. id_doc');void(0);";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][imprimer][libelle] = "Imprimer";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][envoyer_mail][libelle] = "Envoyer par mail";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][envoyer_mail][php] = "javascript:email_commentaire()";
//$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][envoyer_mail][php] = "commande_achat_sf_mail.php?ACTE=1&id_doc=. id_doc";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][envoyer_mail][droit] = '(req_sim ("SELECT statut_commande FROM zdataset_commandeentete WHERE id_dataset = ".$doc[id_dataset_entete], "statut_commande") == 1 )';
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][solder][libelle] = "Solder";
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][solder][droit] = '(req_sim ("SELECT statut_commande FROM zdataset_commandeentete WHERE id_dataset = ".$doc[id_dataset_entete], "statut_commande") == 2 )';
$type_docs['COMMANDE - STORE - BANNE - COFFRE - ELISSE'][btn][solder][php] = 'javascript:document.location.replace(\'doc.php?id_doc=. id_doc&champ_ofclient=. id_doc&req_btn_update=1\');';
