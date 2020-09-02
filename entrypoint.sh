#!/bin/bash

source /etc/apache2/envvars
#tail -F /var/log/apache2/* &
chown www-data:www-data -R /home/container
exec apache2 -D FOREGROUND