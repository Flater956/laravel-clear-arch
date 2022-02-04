FROM php:8.0-fpm-alpine

RUN apk update && apk add --no-cache git \
                                     zip postgresql-dev \
                                     libxslt-dev \
                                     freetype-dev \
                                     libjpeg-turbo-dev \
                                     libpng-dev && \
    docker-php-ext-install pdo_pgsql opcache bcmath sockets

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/5.3.4.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis xsl gd
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
RUN apk --no-cache add shadow

COPY ./.docker/php/php.ini /usr/local/etc/php
WORKDIR /app
COPY ./ .
RUN composer install --ignore-platform-reqs --optimize-autoloader --no-scripts && rm -rf /root/.composer

RUN chown www-data:www-data -R /app
RUN userdel www-data
RUN groupadd -g 1000 www-data
RUN useradd -u 1000 -g 1000 www-data
RUN usermod -a -G 1000 www-data
USER www-data

CMD ["php-fpm"]
