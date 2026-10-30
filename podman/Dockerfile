# TAKEN FROM https://oneuptime.com/blog/post/2026-03-18-use-podman-php-development/view
FROM docker.io/library/php:8.5-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*


# Install PHP extensions using the built-in helper
RUN docker-php-ext-install intl

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html