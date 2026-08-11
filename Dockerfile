# Build stage: install Composer dependencies
FROM composer:2.7 AS builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --classmap-authoritative
COPY . .

# Runtime stage: PHP with required extensions
FROM php:8.2-cli
WORKDIR /var/www/html
RUN apt-get update \
    && apt-get install -y --no-install-recommends zip unzip git \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=builder /app .

ENV PORT=8000
EXPOSE 8000

CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT} -t public public/router.php"]
