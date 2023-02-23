FROM debian:bookworm

WORKDIR /root

RUN apt update && apt upgrade -y && apt-get install -y \
        nginx php-fpm php-mysql

COPY "./AberDock_Docker/entrypoint.sh" "/"
COPY "./AberDock_Docker/config/nginx" "/etc/nginx"
COPY "./www/" "/var/www/html/"

CMD ["/entrypoint.sh"]
