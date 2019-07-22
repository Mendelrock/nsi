<?php
set_time_limit (0);
echo "<pre>";
echo "veuillez patienter 5 minutes pendant l'exécution du traitement";
echo "</pre>";
flush();
echo "<pre>";
echo exec ("sudo /var/www/ageclair_pre/copie_base.sh",$output);
print_r($output);
echo "fin du traitement";
echo "</pre>";
?>

