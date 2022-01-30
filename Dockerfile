FROM php:7.3.18-apache
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_MEMORY_LIMIT=-1 \
  COMPOSER_HOME=/composer

# composer
COPY --from=composer:1.10.6 /usr/bin/composer /usr/bin/composer
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/userdir.conf /etc/apache2/mods-available/userdir.conf
COPY src /var/www/html

# create .env
RUN mv .env.prod .env

# base libs
RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev unzip locales && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen en_US.UTF-8 && \
  localedef -f UTF-8 -i en_US en_US.UTF-8 && \
  a2enmod rewrite && \
  docker-php-ext-install intl pdo_mysql zip bcmath && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org

# laravel
RUN composer install && \
  chmod -R 777 storage && \
  chmod -R 777 bootstrap

# execute phpunit
RUN ./vendor/bin/phpunit

# generate key
RUN php artisan key:generate

# composer 2 update
# RUN composer self-update --2

# Debugbar
# RUN composer require barryvdh/laravel-debugbar

WORKDIR /var/www/html
