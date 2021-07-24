mkdir /var/www/wake-pc
cd /var/www/wake-pc
wget https://raw.githubusercontent.com/szabodanika/wake-pc/master/index.php
wget https://raw.githubusercontent.com/szabodanika/wake-pc/master/config-template.ini
cp config-template.ini config.ini
php -S 0.0.0.0:9010
