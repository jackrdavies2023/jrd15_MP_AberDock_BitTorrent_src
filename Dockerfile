FROM debian:bookworm
WORKDIR /root
RUN apt update && apt upgrade -y && apt-get install -y \
        nginx php-fpm php-mysql
