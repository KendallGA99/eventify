# Imagen multi-arquitectura fijada por digest; actualizar con verificación.
FROM php:8.4-apache@sha256:901b0dbcd2419cc9cd307ea05e57403449ce722ccd5b32335da6e8d39f2b1ee0

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite headers \
    && mkdir -p /var/www/html/storage \
    && chown www-data:www-data /var/www/html/storage

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/eventify.ini
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN curl -fsSL https://getcomposer.org/download/2.10.3/composer.phar -o /tmp/composer.phar \
    && echo '7a2d379d5b8ffdaa028580ef26494c36d2feef4b178d3dd1473a4dbc5e17c8d6  /tmp/composer.phar' | sha256sum -c - \
    && php /tmp/composer.phar install --no-dev --no-plugins --no-scripts --prefer-dist --no-interaction --no-progress --optimize-autoloader \
    && php /tmp/composer.phar check-platform-reqs --no-dev \
    && rm /tmp/composer.phar
COPY app/ app/
COPY config/ config/
COPY database/ database/
COPY public/ public/
COPY bin/ bin/
COPY tests/ tests/

EXPOSE 80
