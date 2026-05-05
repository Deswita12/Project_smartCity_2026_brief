# FROM ubuntu:22.04

# ENV DEBIAN_FRONTEND=noninteractive
# ENV TZ=Asia/Jakarta

# RUN apt-get update && apt-get install -y \
#     software-properties-common \
#     curl git unzip zip nano \
#     && add-apt-repository ppa:ondrej/php \
#     && apt-get update && apt-get install -y \
#     php8.2-fpm \
#     php8.2-cli \
#     php8.2-mysql \
#     php8.2-mbstring \
#     php8.2-xml \
#     php8.2-bcmath \
#     php8.2-curl \
#     php8.2-zip \
#     php8.2-gd \
#     php8.2-intl \
#     php8.2-tokenizer \
#     nodejs npm \
#     && curl -sS https://getcomposer.org/installer | php \
#     && mv composer.phar /usr/local/bin/composer \
#     && apt-get clean

# COPY docker/php/php.ini /etc/php/8.2/fpm/conf.d/99-custom.ini

# WORKDIR /var/www/html

# EXPOSE 9000
# CMD ["php-fpm8.2", "-F"]

FROM php:8.2-fpm

ENV TZ=Asia/Jakarta

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    nano \
    npm \
    nodejs \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    gd \
    zip \
    intl \
    exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]


# FROM ubuntu:22.04

# ENV DEBIAN_FRONTEND=noninteractive
# ENV TZ=Asia/Jakarta

# RUN apt-get update && apt-get install -y \
#     software-properties-common \
#     curl \
#     git \
#     unzip \
#     zip \
#     tzdata \
#     && add-apt-repository ppa:ondrej/php -y \
#     && apt-get update && apt-get install -y \
#     php8.3-fpm \
#     php8.3-cli \
#     php8.3-mbstring \
#     php8.3-xml \
#     php8.3-bcmath \
#     php8.3-curl \
#     php8.3-zip \
#     php8.3-mysql \
#     php8.3-gd \
#     php8.3-intl \
#     php8.3-tokenizer \
#     php8.3-fileinfo \
#     && apt-get clean && rm -rf /var/lib/apt/lists/*


# RUN mkdir -p /run/php && chown -R www-data:www-data /run/php  
# RUN docker-php-ext-install pdo_sqlite 

# # Install Composer
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# # Install Node.js & npm (untuk Tailwind)
# RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
#     && apt-get install -y nodejs

# COPY docker/php/php.ini /etc/php/8.3/fpm/conf.d/99-custom.ini

# WORKDIR /var/www/html

# EXPOSE 9000

# CMD ["php-fpm8.3", "-F"]


















# WORKDIR /var/www/html

# COPY php-fpm.conf /etc/php/8.3/fpm/pool.d/www.conf

# EXPOSE 9000