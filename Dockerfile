FROM dunglas/frankenphp
RUN apt-get update && apt-get install -y \
    zip
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer
RUN install-php-extensions \
    pdo_mysql
WORKDIR /app