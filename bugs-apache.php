<VirtualHost *:80>

    DocumentRoot /var/www/html

    <Directory /var/www/html>
        AllowOverride all
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
