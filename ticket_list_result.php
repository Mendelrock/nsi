<?
include("ress/entete.php");

$_SESSION[ticket_liste_Id_Ticket]           = $Id_Ticket;
$_SESSION[ticket_liste_Id_utilisateur_createur]        = $Id_utilisateur_createur;
$_SESSION[ticket_liste_Id_utilisateur_responsable]   = $Id_utilisateur_responsable;
$_SESSION[ticket_liste_Id_statut]             = $Id_statut;
$_SESSION[ticket_liste_Nom]   = $Nom;
$_SESSION[ticket_liste_Telephone]            = $Telephone;
$_SESSION[ticket_liste_Mail]         = $Mail;
$_SESSION[ticket_liste_Texte]            = $Texte;
$_SESSION[ticket_liste_go]                = 1;
$_SESSION[ticket_liste_Id_origine] = $Id_origine;

$Nom = addslashes($Nom);
$Texte          = addslashes($Texte);
if($Id_Ticket){
    $clauses .=" Id_Ticket = '$Id_Ticket' AND ";
}
if($Id_utilisateur_createur){
    $clauses .=" Id_utilisateur_createur = '$Id_utilisateur_createur' AND ";
}
if($Id_utilisateur_responsable){
    $clauses .=" Id_utilisateur_responsable = $Id_utilisateur_responsable AND ";
}
if($Id_statut){
    $clauses .=" ticket_statut.Id_statut = $Id_statut AND ";
}
if($Nom){
    $clauses .="Nom LIKE '%$Nom%' AND ";
}

if($Telephone){
    $clauses .="Telephone LIKE '$Telephone%' AND ";
}

if($Mail){
    $clauses .="Mail LIKE '%$Mail%' AND ";
}
if($Texte){
    $clauses .="Texte LIKE '%$Texte%' AND ";
}
if($Id_origine){
    $clauses .=" id_origine = $Id_origine AND";
}
$requete = "
   SELECT Id_Ticket,u1.Nom as utilisateur_createur,u2.Nom as utilisateur_responsable,ticket_statut.Lb_statut as Statut,ticket.Nom,
   ticket.Prenom,ticket.Telephone,ticket.Mail,ticket.Id_origine
   FROM
      ticket,utilisateur u1,utilisateur u2,ticket_statut
      
    WHERE
      $clauses
      u1.Id_utilisateur=Id_utilisateur_createur and u2.Id_utilisateur=Id_utilisateur_responsable
      and ticket_statut.Id_statut = ticket.Id_statut order by Id_Ticket";
//LIMIT 0, ".($Affichage+2);

/*----------- EXECUTION ---------*/
$resultat = new db_sql($requete);

/*----------- AFFICHAGE ---------*/
// Affichage des entetes
?>
<body class="application">
<form target=droite method="post" name ="liste" action="liste_detail_add.php">


    <table class="resultat">
        <tr>
            <td class="resultat_tittle">Numéro</td>
            <td class="resultat_tittle">Créateur</td>
            <td class="resultat_tittle">Responsable</td>
            <td class="resultat_tittle">Statut</td>
            <td class="resultat_tittle">Nom</td>
            <td class="resultat_tittle">Téléphone</td>
            <td class="resultat_tittle">Mail</td>
            <td class="resultat_tittle">Contenu</td>
            <td class="resultat_tittle">Origine</td>
        </tr>
        <?
        // Boucle de lecture
        while ( $resultat->n() /*AND $z<$Affichage*/ ) {
            ?>
            <tr>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><a class='resultat' href='ticket_detail.php?Id_ticket=<? echo $resultat->f('Id_Ticket') ?>' target='droite'><? echo $resultat->f('Id_Ticket') ?></a></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $resultat->f('utilisateur_createur') ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $resultat->f('utilisateur_responsable') ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo utf8_encode($resultat->f('Statut')) ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $resultat->f('Nom') ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>" nowrap><? echo $resultat->f('Telephone') ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo $resultat->f('Mail') ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo substr(req_sim("SELECT texte from ticket_historique WHERE id_ticket = ".$resultat->f('Id_Ticket')." ORDER BY date_historique DESC ", "texte"),0,100) ?></td>
                <td class="resultat_list" bgcolor="<? echo alternat($z) ?>"><? echo req_sim("SELECT lb_origine FROM ticket_origine WHERE id_origine = " . $resultat->f("Id_origine") . "", 'lb_origine'); ?></td>
            </tr>
            <?
            $z++;
        }
        if ($z) {
        $suiv_text="Fin de liste";
        if($resultat->n()) {
            $suiv_text="Liste non terminée";
        }
        ?>
        <tr>
            <td class='resultat_footer' colspan='5'>Cliquer sur le Numéro de ticket pour ouvrir un ticket</td>
            <td class='resultat_footer' align='center' colspan='4'><span style='font-weight:bold'><? echo $suiv_text ?></span></td>
        </tr>
    </table>
    <script>
        $('#sel').change(function() {
            $('.sel').prop("checked", $('#sel').prop("checked") );
        })
    </script>
    <form>
        <?
        }
        /*-------- Fin Traitement requete ---------------*/
        include ("ress/enpied.php");
        ?>
