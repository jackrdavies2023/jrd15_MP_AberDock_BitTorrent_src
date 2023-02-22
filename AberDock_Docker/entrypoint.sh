#!/bin/bash

#########################################################################
# Entrypoint script which is responsible for starting NGINX and PHP-FPM #
# from within the docker container.                                     #
#                                                                       #
# Written by Jack Ryan Davies (jrd15)                                   #
#########################################################################

# Start NGINX
nginx -g 'daemon off;' &

# Start PHP-FPM. Command line parameters taken from: /usr/lib/systemd/system/php8.2-fpm.service

/usr/lib/php/php-fpm-socket-helper install /run/php/php-fpm.sock /etc/php/8.2/fpm/pool.d/www.conf 82
/usr/sbin/php-fpm8.2 --nodaemonize --fpm-config /etc/php/8.2/fpm/php-fpm.conf
