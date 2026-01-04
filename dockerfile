FROM trafex/php-nginx:latest

USER root
RUN apk add --no-cache php84-pdo_pgsql php84-pgsql

USER nobody