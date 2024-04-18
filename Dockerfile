FROM composer:2 AS deps

WORKDIR /srv

COPY ["./composer.json", "./composer.lock", "/srv/"]

RUN composer install --dev --no-scripts --no-interaction

FROM php:8.3-alpine AS server

WORKDIR /srv

RUN apk add --no-cache --virtual .deps \
      bash \
    && apk add --no-cache \
      libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && wget https://get.symfony.com/cli/installer -O - | bash \
    && mv ~/.symfony5/bin/symfony /usr/local/bin/symfony \
    && apk del .deps

COPY --from=deps /srv/vendor /srv/vendor
COPY . /srv

EXPOSE 8000/tcp

ENTRYPOINT ["/srv/docker/php/entrypoint.sh"]
CMD ["symfony", "server:start"]
