#!/bin/bash

#########################################################################
# Entrypoint script which is responsible for starting NGINX and PHP-FPM #
# from within the docker container.                                     #
#                                                                       #
# Written by Jack Ryan Davies (jrd15)                                   #
#########################################################################

# Start NGINX
nginx -g 'daemon off;'

# Start PHP-FPM



