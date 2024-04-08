FROM php:8.1-fpm
ARG ENV
WORKDIR /var/www

RUN apt-get -y update \
    && apt-get install -y libicu-dev librabbitmq-dev git\
    && pecl install amqp \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-enable amqp

RUN pecl install redis
RUN docker-php-ext-enable redis

ADD . /var/www

ADD ./docker/php/conf.d/xdebug.ini /home/xdebug.ini
ADD ./docker/php/xdebug-entrypoint.sh /home/xdebug-entrypoint

RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN if [ "$ENV" = "dev" ]; then \
  composer install --prefer-dist --no-scripts --no-interaction; else \
  composer install --no-dev --prefer-dist --no-scripts --no-interaction; \
    fi

RUN mkdir -p /var/www/var/cache /var/www/var/log
RUN chown -R www-data:www-data /var/www/var

RUN chmod +x /home/xdebug-entrypoint

ENTRYPOINT ["/home/xdebug-entrypoint"]
