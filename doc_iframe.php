<?
require_once("ress/entete.php");
require_once("c_o_dataset.php");
require_once("fonction.php");
if(!$type_dataset_ligne) {
   //if (!$onglet_courant) $onglet_courant = "Empied";
   //Création de la liste des onglets
   //Produits déjà sélectionné
   $produits_deja_utilises = charge("select distinct produit from doc_ligne where id_doc = $id_doc");
   foreach ($produits_deja_utilises as $i => $onglet) {
      $onglets[$onglet[produit]][php]     = "doc_ligne.php";
      require("param_2_produits/".$onglet[produit].".php");  
      //$onglets[$onglet[produit]][libelle] = $GLOBALS[parms][produits][$onglet[produit]][libelle];
      $onglets[$onglet[produit]][libelle] = $produits[$onglet[produit]][libelle];
      if (!$onglet_courant) $onglet_courant = $onglets[$onglet[produit]][libelle];
   }

   //Empied
   //$onglets[Empied][php] = "doc_empied.php";
   //$onglets[Empied][libelle] = "Empied";
   
   //Nouveau éventuel produit
   if ($onglet_courant != "Empied") {
      require("param_2_produits/".$onglet_courant.".php");  
      $onglets[$onglet_courant][php]     = "doc_ligne.php";
      //$onglets[$onglet_courant][libelle] = $GLOBALS[parms][produits][$onglet_courant][libelle];
      $onglets[$onglet_courant][libelle] = $produits[$onglet_courant][libelle];
   }
   if(is_document_lie_cree($id_doc)){
      //Liste des OF
      $onglets[of][php] = "fdc_detail_of_list.php";
      $onglets[of][libelle] = "Documents liés";
   }

   //Création de la liste de produits non déjà en onglets
   $onglets_manquant_adresse = "";
   $onglets_manquant_libelle = "Choisir";
   $files = scandir("produits");
   foreach ($files as $i => $fichier) {	
   //foreach ($GLOBALS[parms][produits] as $produit => $infos_produit) {
   	  if($fichier!="." && $fichier!=".."){
      	 require("param_2_produits/".$fichier);
         $produit = substr($fichier,0,strlen($fichier)-4);
         $infos_produit = $produits[$produit];	
         if (!array_key_exists ($produit,$onglets)) {
            $onglets_manquant_adresse .= "|".$produit;
            $onglets_manquant_libelle .= "|".$infos_produit[libelle];
         }
      }
   }
?>
   <body class="application">
   <table class="cadre_application"><tr><td  align="center"     valign="middle">
      <table align = left>
         <tr>
<?
      //Affichage des onglets
      if($onglet_courant){
         foreach ($onglets as $onglet => $infos_onglet) {
            if ($onglet == $onglet_courant) {
               echo "<td class=\"interne_actif\">".$infos_onglet[libelle]."</td>";
               $adresse_courante = $adresse;
            } else {
               //echo "<td class=\"interne\"><a class=\"interne\" href=\"?onglet_courant=".$onglet."&id_doc=".$id_doc."&nonmodifiable=".$nonmodifiable."&ACTE=1\" onclick=\"document.forms['formulaire'].submit();\">".$infos_onglet[libelle]."</A></td>";
               echo "<td class=\"interne\"><a class=\"interne\" href=\"#\" onclick=\"document.forms['formulaire'].onglet_apres.value='".$onglet."';document.forms['formulaire'].submit();\">".$infos_onglet[libelle]."</A></td>";
            }
         }
      }
      //Affichage de la liste produits non encore en onglet
      if ($onglets_manquant_adresse) {
         echo "<td class=\"interne_actif\">Ajouter produit";
         drop_down_droit ("L","","onglet_courant", "", "", "", $immediat=true, $page="doc.php?id_doc=". $id_doc, $fg_oblig="N", $onglets_manquant_adresse, $onglets_manquant_libelle);
         echo "</td>";
      }
?>
         </tr>
      </table>
   </td></tr>
   <tr><td class="cadre_application">
    <?
	   // la variable $onglet_courant est importante : elle contient le produit
      if($onglet_courant)   
         include ($onglets[$onglet_courant][php]);
    ?>
     </td></tr></table>
<?
   include ("ress/enpied.php");
}
else {
   $onglet_courant='-';
   include("doc_ligne.php"); 
}
?>

