FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

ENV XDEBUG_MODE=off \
    XDEBUG_START_WITH_REQUEST=trigger \
    XDEBUG_CLIENT_HOST=host.docker.internal \
    XDEBUG_CLIENT_PORT=9003 \
    PHP_IDE_CONFIG=serverName=BEAR.Skeleton

WORKDIR /app
EXPOSE 8080

CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]
