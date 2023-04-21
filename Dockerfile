FROM debian:bookworm

WORKDIR /root

RUN apt update && apt upgrade -y && apt-get install -y \
        nginx php-fpm php-mysql

RUN rm -f /var/www/html/index.nginx-debian.html

COPY "./AberDock_Docker/entrypoint.sh" "/"
COPY "./AberDock_Docker/config/" "/etc/"

# Temprarily disable copying web directory contents,
# as we will be mapping a volume during development.
#COPY "./www/" "/var/www/html/"

CMD ["/entrypoint.sh"]
