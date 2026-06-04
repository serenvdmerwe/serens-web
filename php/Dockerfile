FROM php:8.4-fpm-alpine

LABEL maintainer="TurboPress"
LABEL email="barry@turbopress.pro"

ARG PUSER=semantica
ARG PUID=1000
ARG PGID=1000

# Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV BUILD_DEPS="\
    $PHPIZE_DEPS \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    libxml2-dev \
    oniguruma-dev \
    imagemagick-dev \
    libwebp-dev"

RUN apk add --no-cache --virtual .build-deps ${BUILD_DEPS} \
    && apk add --no-cache \
         imagemagick \
         libjpeg \
         libzip \
         icu-libs \
         libwebp \
         curl \
         git \
         unzip \
         bash \
         less \
         tini \
         nodejs \
         npm \
         mariadb-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
         gd pdo_mysql mysqli zip opcache intl soap mbstring exif \
    && pecl install redis apcu imagick igbinary zstd \
    && docker-php-ext-enable \
         gd intl zip pdo_mysql mysqli opcache soap mbstring exif redis apcu imagick igbinary zstd \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/* /var/tmp/*

# Custom configs
COPY php-fpm.ini $PHP_INI_DIR/conf.d/
COPY www.conf    /usr/local/etc/php-fpm.d/

# WP-CLI + wait-for-it
RUN curl -sSLo /usr/local/bin/wp https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x /usr/local/bin/wp \
    && curl -sSLo /usr/local/bin/wait-for-it.sh https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh \
    && chmod +x /usr/local/bin/wait-for-it.sh

# Host-aligned user (evict anything sitting on PUID/PGID first — newer
# alpine bases sometimes ship a default user at 1000).
RUN set -eux; \
    existing_user=$(getent passwd ${PUID} | cut -d: -f1 || true); \
    existing_group=$(getent group  ${PGID} | cut -d: -f1 || true); \
    [ -n "$existing_user"  ] && deluser  "$existing_user"  || true; \
    [ -n "$existing_group" ] && delgroup "$existing_group" || true; \
    addgroup -g ${PGID} ${PUSER} \
    && adduser -u ${PUID} -G ${PUSER} -s /bin/sh -D ${PUSER} \
    && chown -R ${PUSER}:${PUSER} /var/www/html \
    && mkdir -p /var/www/.opcache /tmp \
    && chown -R ${PUSER}:${PUSER} /var/www/.opcache /tmp

USER ${PUSER}
WORKDIR /var/www/html
EXPOSE 9000

ENTRYPOINT ["tini", "--"]
CMD ["sh", "-c", "/usr/local/bin/wait-for-it.sh serens-web-mariadb-service:3306 -- php-fpm"]
