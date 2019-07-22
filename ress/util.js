Array.prototype.contains = function(elem)
{
   for (var i in this)
   {
       if (this[i] == elem) return true;
   }
   return false;
}
function sortSelectOptions(selectElement) {
	var options = $(selectElement + " option");

	options.sort(function(a,b) {
		if (a.text.toUpperCase().indexOf('[VIDE]')>-1 || b.text.toUpperCase().indexOf('[VIDE]')>-1) return 0;
		if (a.text.toUpperCase() > b.text.toUpperCase()) return 1;
		else if (a.text.toUpperCase() < b.text.toUpperCase()) return -1;
		else return 0;
	});

	$(selectElement).empty().append( options );
}
/*
   Ouvre la fenêtre de création d'une correspondance
*/
function showFenetreCorrespondance(ope,url){
   if(ope==1){	
   	var left = (screen.width/2)-(600/2);
      var top = (screen.height/2)-(600/2);
      var targetWin = window.open(url, 'Correspondance', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=530, height=330, top='+top+', left='+left);
   }
   else {
      var left = (screen.width/2)-(600/2);
      var top = (screen.height/2)-(600/2);
      var targetWin = window.open(url, 'Correspondance', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=530, height=330, top='+top+', left='+left);	
   }
}
/*
   Teste si une valeur est un entier
*/
function is_int(value){
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else {
      return false;
  }
}
/*
   Fonction qui ouvre un popup centré
*/
function popupCenter(pageURL, title,w,h) {
  var left = (screen.width/2)-(w/2);
  var top = (screen.height/2)-(h/2);
  var targetWin = window.open(pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 

/*
   Aucune saisie n'est valide
*/
function skip(evt) { 
   var theEvent = evt || window.event; 
   theEvent.returnValue = false;
} 

/*
   Fonction qui vérifie si la valeur saisie est numérique
*/
function validate(evt,value) { 
   var theEvent = evt || window.event; 
   var key = theEvent.keyCode || theEvent.which;
   var keyVal = String.fromCharCode( key ); 
   var regex = /[0-9]|\./; 
   if(( !regex.test(keyVal) ||((value.indexOf('.') !=-1 ||value=="") && keyVal==".")) && (key!=46)) { 
      theEvent.returnValue = false;
	   if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) 
         theEvent.preventDefault(); 
   } 
} 

function retour(http_request) {
   alert(http_request.responseText);
   
}

function updateCorrespondance(url,donnees){
   //alert(donnees);
   var http_request = false;
   if (window.XMLHttpRequest) {
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
         http_request.overrideMimeType('text/xml');
      }
   } else if (window.ActiveXObject) { 
      try {
         http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
         try {
            http_request = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {}
      }
   }
   if (!http_request) {
      alert('Abandon :( Impossible de créer une instance XMLHTTP');
      return false;
   }
   //http_request.onreadystatechange = function() { retour(http_request); } 
   http_request.open('POST', url, true);
   http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   http_request.send(donnees);	
}
/*
   Met à jour la quantité en stock d'un article
*/
function updateStockArticle(url,id_article,qtestock,ope){
   var http_request = false;
   if (window.XMLHttpRequest) {
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
         http_request.overrideMimeType('text/xml');
      }
   } else if (window.ActiveXObject) { 
      try {
         http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
         try {
            http_request = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {}
      }
   }
   if (!http_request) {
      alert('Abandon :( Impossible de créer une instance XMLHTTP');
      return false;
   }
   //http_request.onreadystatechange = function() { updateStock(http_request); } 
   //http_request.onreadystatechange = function() { alert('toto')); }
   http_request.open('POST', url, true);
   http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   data="id_article="+id_article+"&qtestock="+qtestock+"&ope="+ope;
   http_request.send(data);	
}
/*
   Met à jour la quantité à acheter
*/
function updateAacheter(url,id_article,id_fournisseur,ope,qtestock,numligne){
   var http_request = false;
   var quotite=document.getElementById(id_fournisseur.toString()+id_article.toString()+"_quotite").innerHTML;
   var qt_max=document.getElementById(id_fournisseur.toString()+id_article.toString()+"_qt_max").innerHTML;
   var qt_mini=document.getElementById(id_fournisseur.toString()+id_article.toString()+"_qt_mini").innerHTML;
   if (window.XMLHttpRequest) {
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
         http_request.overrideMimeType('text/xml');
      }
   } else if (window.ActiveXObject) { 
      try {
         http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
         try {
            http_request = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {}
      }
   }
   if (!http_request) {
      alert('Abandon :( Impossible de créer une instance XMLHTTP');
      return false;
   }
   http_request.onreadystatechange = function() { updateLigne(http_request); } 
   http_request.open('POST', url, true);
   http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   data="id_article="+id_article+"&id_fournisseur="+id_fournisseur+"&ope="+ope+"&quotite="+quotite+"&qtestock="+qtestock+"&qt_max="+qt_max+"&qt_mini="+qt_mini+"&numligne="+numligne;
   http_request.send(data);	
}

/*
   Retourne les infos d'un fournisseur
*/
function getInfosFournisseur(url,id_article,id_fournisseur,ope,id_fournisseurold){
   var qt_max=document.getElementById(id_fournisseurold.toString()+id_article.toString()+"_qt_max").innerHTML;
   var divqtestock=document.getElementById(id_fournisseurold.toString()+id_article.toString()+"_qtestock");
   var qt_mini=document.getElementById(id_fournisseurold.toString()+id_article.toString()+"_qt_mini").innerHTML;
   var a = divqtestock.getElementsByTagName('a');
   var qtestock;
   if(a[0]==undefined) {
      divqtestock = document.getElementById(id_fournisseurold.toString()+id_article.toString()+"_qtestock");
      a = divqtestock.getElementsByTagName('input');
      qtestock = a[0].value;	
   }
   else qtestock = a[0].firstChild.nodeValue; 
   var http_request = false;
   if (window.XMLHttpRequest) {
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
         http_request.overrideMimeType('text/xml');
      }
   } else if (window.ActiveXObject) { 
      try {
         http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch (e) {
         try {
            http_request = new ActiveXObject("Microsoft.XMLHTTP");
         } catch (e) {}
      }
   }
   if (!http_request) {
      alert('Abandon :( Impossible de créer une instance XMLHTTP');
      return false;
   }
   http_request.onreadystatechange = function() { updateFournisseur(http_request); } 
   http_request.open('POST', url, true);
   http_request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
   data="id_article="+id_article+"&id_fournisseur="+id_fournisseur+"&ope="+ope+"&id_fournisseurold="+id_fournisseurold+"&qtestock="+qtestock+"&qt_max="+qt_max+"&qt_mini="+qt_mini;
   http_request.send(data);	
}

/*
   Met à jour un champ
*/
function updateField(valeur,id){
	document.getElementById(id).value =valeur;
}

function updateFournisseur(http_request) {
   if (http_request.readyState == 4) {
      if (http_request.status == 200) {
      	 if(http_request.responseText!="")
      	 {
		      var data = http_request.responseText.split('[SEPVAL]');
		      document.getElementById(data[0]+data[1]+"_quotite").innerHTML ='<div id="'+data[0]+data[1]+'_quotite">'+data[2]+'</div>';
			  document.getElementById(data[0]+data[1]+"_aacheter").value =data[3];
         }	 
      }else{
	      alert('Un problème est survenu avec la requête.');
      }
   }
}

function updateLigne(http_request) {
   if (http_request.readyState == 4) {
      if (http_request.status == 200) {
      	 if(http_request.responseText!="")
      	 {
		      var data = http_request.responseText.split('[SEPVAL]');
		      document.getElementById(data[0]+data[1]+"_aacheter").value =data[2];
		      var trelt=document.getElementById(data[3]);
		      var i =data[3]/100;
		      var color;
		      if(data[2]==0){
      	         if(i % 2 ==0){
                    color="#D3DCE3";
                 }
                 else{
                    color="WhiteSmoke";
                 }
		      }
              else color='#FF6347';
              var cellsLength = trelt.cells.length;
			  for (var j=0; j < cellsLength; j++) 
	          {
		         trelt.cells.item(j).style.backgroundColor=color;
	          } 
	          var divqtestock = document.getElementById(data[0]+data[1]+"_qtestock");
              var qtestock = divqtestock.getElementsByTagName('input')[0].value;
              document.getElementById(data[0]+data[1]+"_qtestock").innerHTML ="<div id="+data[0]+data[1]+"_qtestock"+"><a href='#' class='resultat' onclick='showTextBox("+data[0]+","+data[1]+","+qtestock+","+data[3]+")'>"+qtestock+"</a></div>";
         }	 
      } else {
	      alert('Un problème est survenu avec la requête.');
      }
   }
}

function add(text,chaine)
{
   if(text=="")
      text=text+chaine;
   else
      text=text+';'+chaine;
   return text;
}

function traitementReponse(http_request) {
   if (http_request.readyState == 4) {
      if (http_request.status == 200) {
      	//document.write(http_request.responseText);
         if(http_request.responseText!="") {
		      var data = http_request.responseText.split('[SEPDIV]');
		      for (var i=0; i < data.length; ++i) {
		 	      var val = data[i].split('[SEPVAL]');
         	   eval(val[1]);
		         document.getElementById(val[0]).options.length = 0;
               for (var j = 0; j < valeurs.length; ++j) {
                  document.getElementById(val[0]).options[j] = new Option(valeurs[j].valeur, valeurs[j].id);
               }
               if (valeurs.length==2) {
               	document.getElementById(val[0]).options[1].selected = true;
               }
            }
		   }	 
	   } else {
	      alert('Un problème est survenu avec la requête de contrôle');
      }
   }
}
  
function form_ref(){
   var tab_val = new Array();
   var k = 1;
   tab_val[0] = document.forms.length;
   for(i=0;i<tab_val[0];i++){
      for(j=0;j<document.forms[i].elements.length;j++){
         if(document.forms[i].elements[j].type=="select-multiple" || document.forms[i].elements[j].type=="select-one"){
            tab_val[k++]=document.forms[i].elements[j].options[document.forms[i].elements[j].selectedIndex].value;
         }else{
            tab_val[k++]=document.forms[i].elements[j].value;
         }
      }
   }
   return(tab_val);
}

/*Fonction basée sur le résultat fourni par la fonction précédente.
Elle compare les valeurs finales des champs d'un formulaire par 
rapport aux valeurs initiales des champs.
En cas de différence :
	un message d'alerte est envoyé et la fonction  renoie FALSE
le cas échéant :
	elle renvoie true
	
Rmq : cette fonction peut être appellée depuis un gestionnaire d'évènements*/

function test_modif(){
   var k = 1;
   var erreur  = -1;
   for(i=0;i<tab_val[0];i++){
      for(j=0;j<document.forms[i].elements.length;j++){
         if(document.forms[i].elements[j].type=="select-multiple" || document.forms[i].elements[j].type=="select-one"){
            if(document.forms[i].elements[j].options[document.forms[i].elements[j].selectedIndex].value != tab_val[k]){
               erreur  = 1;
            }
         }else{
            if(document.forms[i].elements[j].value != tab_val[k]){
               erreur  = 1;
            }
         }
  	k++;
      }
   } 
   if(erreur == 1){
      alert("'Vous devez valider les modifications du formulaire \n avant de quitter");
      return false;
   }
   return true;
}
/*Fonction qui vérifie qu'un champ obligatoire est correctement saisi
 ¨Paramètre d'entrée : nom du formulaire [ex : champ_oblig('essai')]
*/
function champ_oblig(f){
   var nom_champ;     
   for(j=0;j<document.forms[f].elements.length;j++){ 
      if(document.forms[f].elements[j].type=="hidden" && document.forms[f].elements[j].name.substring(0,2)=="h_"){
         nom_champ = document.forms[f].elements[j].name;
         nom_champ = nom_champ.substring(2,nom_champ.length);
         if(document.forms[f].elements[j].value == "O"){            
            if(document.forms[f].elements[nom_champ].type=="select-multiple" || document.forms[f].elements[nom_champ].type=="select-one"){
               if(document.forms[f].elements[nom_champ].options[document.forms[f].elements[nom_champ].selectedIndex].value ==0){;
                  alert('La saisie du champ ' + nom_champ + ' est obligatoire');
                  document.forms[f].elements[nom_champ].focus();
                  return false;          
               }              
            } 
            if(document.forms[f].elements[nom_champ].type=="text" || document.forms[f].elements[nom_champ].type=="textarea"){
               if(document.forms[f].elements[nom_champ].value.length==0){
                  alert('La saisie du champ ' + nom_champ + ' est obligatoire');
                  document.forms[f].elements[nom_champ].focus();			
		  return false;
               }
            }
            if(document.forms[f].elements[nom_champ].type=="hidden"){
               if(document.forms[f].elements[nom_champ].value.length==0){ 
                  alert('La saisie du champ ' + nom_champ + ' est obligatoire');
                  document.forms[f].elements[nom_champ+"saisie"].focus();			
                  return false;
               }
            }
         }
      }
   }
   return true
}

