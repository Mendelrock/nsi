echo Rafraichissement de la base de test à partir de la prod
echo .......................................................

mysql -uroot -pBarbatruc1 < /var/www/ageclair_pre/copie_base.sq
mysqldump -ubdm_ageclair -pbdm_ageclair bdm_ageclair | mysql -ubdm_ageclair_pre -pbdm_ageclair_pre -C bdm_ageclair_pre

echo fin du traitement
