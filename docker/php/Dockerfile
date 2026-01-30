FROM php:8.4-fpm

WORKDIR /var/www

COPY php_timezone.ini "$PHP_INI_DIR/conf.d/php_timezone.ini"
ENV TZ=Asia/Novosibirsk
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt update && apt install -y git subversion unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY xdebug.ini "$PHP_INI_DIR/xdebug.ini"

ARG ENV

RUN \
  mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" ; \
fi