<?php
include("ress/entete.php");
require_once("c_o_dataset.php");
?>
<div id="loading" style="position:fixed; top:10px; left:10px;"><img src="images/loading.gif"></div>
<div id=mess>
Traitement en cours
</div>
<SCRIPT>
$("#loading").show();
setTimeout(fin, 5000);
function fin(){
	$("#loading").hide();
	$("#mess").after(" OK");
}
</SCRIPT>












