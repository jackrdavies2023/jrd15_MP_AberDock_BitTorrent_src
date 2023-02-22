FROM debian:bookworm

WORKDIR /root

RUN apt update && apt upgrade -y && apt-get install -y \
        nginx php-fpm php-mysql

COPY "./entrypoint.sh" "/"
COPY "./config/nginx" "/etc/nginx"

CMD ["/entrypoint.sh"]
