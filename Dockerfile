FROM php:fpm-alpine

RUN apk --update --no-cache add git

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./src /var/www

RUN composer install --no-scripts

CMD ["php", "anchorage.php"]
