FROM php:8.1-fpm

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory 
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev \
    zip \
    sudo \
    unzip \
    npm \
    nodejs
    
RUN apt-get update \  
    && apt-get install cron -y \
    && apt-get install vim -y  \
    && apt-get install wget \
    && apt-get install supervisor


COPY ./docker/php-server/crontab /etc/cron.d/crontab
RUN crontab /etc/cron.d/crontab
RUN chmod 0644 /etc/cron.d/crontab
# RUN crontab /etc/cron.d/crontab


# Create the log file to be able to run tail
RUN touch /var/log/cron.log
 # running our crontab using the binary from the package we installed
RUN echo "* * * * * root php /var/www/artisan schedule:run >> /var/log/cron.log 2>&1" >> /etc/crontab
RUN echo "* * * * * root echo "Khmer" >> /var/www/cron.log 2>&1" >> /etc/crontab

# Create the log file to be able to run tail
RUN touch /var/log/cron.log
# RUN apt update && apt add curl && \
#   curl -sS https://getcomposer.org/installer | php \
#   && chmod +x composer.phar && mv composer.phar /usr/local/bin/composer
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# Install PHP extensions
# RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
#     && docker-php-ext-install pdo pdo_pgsql pgsql
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
RUN docker-php-ext-install bcmath
# RUN  docker-php-ext-install pdo pdo_pgsql pgsql 

# # Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Add user for laravel application
# RUN groupadd -g 1000 www
# RUN useradd -u 1000 -ms /bin/bash -g www www
# RUN echo 'root:Docker!' | chpasswd

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
# COPY --chown=www:www . /var/www

# Change current user to www
# USER www
#RUN  chmod -R 755 /var/www/.
# Expose port 9000 and start php-fpm server
EXPOSE 9000
# CMD ["php-fpm"]
# CMD ["php-fpm" ,"cron && tail -f /var/log/cron.log"]
CMD bash -c "cron && php-fpm"