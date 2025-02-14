FROM php:8.2
WORKDIR /app/pastelaria-api

# Instala PHP e dependencias
RUN apt-get update -y && apt-get install -y openssl zip unzip git curl sqlite3 libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev libonig-dev libxml2-dev libpq-dev libsqlite3-dev\
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql mbstring zip bcmath xml \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY . /app/pastelaria-api

# Instala o composer e instala as depencias do laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer  
RUN composer install --no-dev --optimize-autoloader  

EXPOSE 8181