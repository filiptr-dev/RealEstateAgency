# syntax=docker/dockerfile:1

# --- Stage 1: PHP dependencies -------------------------------------------
FROM php:8.4-cli-alpine AS vendor
RUN apk add --no-cache git unzip curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev

# --- Stage 2: frontend assets ---------------------------------------------
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY . .
RUN npm run build

# --- Stage 3: runtime -------------------------------------------------------
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
        nginx \
        supervisor \
        gettext \
        bash \
        postgresql-libs \
        libzip \
        libpng \
        libjpeg-turbo \
        freetype \
        icu-libs \
        oniguruma \
    && apk add --no-cache --virtual .build-deps \
        postgresql-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        icu-dev \
        oniguruma-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_pgsql \
        pgsql \
        gd \
        zip \
        intl \
        mbstring \
        bcmath \
    && apk del .build-deps

WORKDIR /var/www/html

COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=frontend /app/public/build ./public/build

COPY docker/nginx.conf.template /etc/nginx/http.d/default.conf.template
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
