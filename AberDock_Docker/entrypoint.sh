#!/bin/bash

#########################################################################
# Entrypoint script which is responsible for starting NGINX and PHP-FPM #
# from within the docker container.                                     #
#                                                                       #
# Written by Jack Ryan Davies (jrd15)                                   #
#########################################################################


if [[ "${MYSQL_DATABASE}" == "" ]]; then
    echo "'MYSQL_DATABASE' not defined!"
    exit 1
fi

if [[ "${MYSQL_USER}" == "" ]]; then
    echo "'MYSQL_DATABASE' not defined!"
    exit 1
fi

if [[ "${MYSQL_PASSWORD}" == "" ]]; then
    echo "'MYSQL_PASSWORD' not defined!"
    exit 1
fi

if [[ "${MYSQL_PORT}" == "" ]]; then
    echo "'MYSQL_PORT' not defined!"
    exit 1
fi

if [[ "${MYSQL_SERVER}" == "" ]]; then
    echo "'MYSQL_DATABASE' not defined!"
    exit 1
fi

# Write AberDock database configuration
echo -e "<?php\n\
    // We're going to be using the Medoo namespace.\n\
    use Medoo\Medoo;\n\

    /* SQL credentials */\n\
    \$db = new Medoo([\n\
        'database_type' => 'mysql',\n\
        'database_name' => '${MYSQL_DATABASE}',\n\
        'server' => '${MYSQL_SERVER}',\n\
        'port' => ${MYSQL_PORT},\n\
        'username' => '${MYSQL_USER}',\n\
        'password' => '${MYSQL_PASSWORD}'\n\
    ]);\n\
?>" > "/var/www/html/include/config.php"


# Start NGINX
nginx -g 'daemon off;' &

# Start PHP-FPM. Command line parameters taken from: /usr/lib/systemd/system/php8.2-fpm.service

/usr/lib/php/php-fpm-socket-helper install /run/php/php-fpm.sock /etc/php/8.2/fpm/pool.d/www.conf 82
/usr/sbin/php-fpm8.2 --nodaemonize --fpm-config /etc/php/8.2/fpm/php-fpm.conf &
touch /var/log/fpm-php.www.log

chown www-data:www-data /var/log/fpm-php.www.log
chmod 600 /var/log/fpm-php.www.log


tail -f /var/log/fpm-php.www.log 

