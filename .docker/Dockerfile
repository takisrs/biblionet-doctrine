FROM php:7.4-apache-buster

RUN a2enmod rewrite ssl headers

RUN apt-get update && apt-get install -y \
        nano \
        wget \
        git \
        zlib1g-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN docker-php-ext-install \
    zip \
    mysqli \ 
    pdo_mysql \
    gettext \
    bcmath \
    exif 

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /~

RUN openssl req -new -newkey rsa:4096 -days 3650 -nodes -x509 -subj \
    "/C=GR/ST=ATTIKI/L=ILION/O=PANOS/CN=localhost" \
    -keyout ./ssl.key -out ./ssl.crt

RUN mkdir /etc/apache2/ssl && \
    cp ./ssl.crt /etc/apache2/ssl/ssl.crt && \
    cp ./ssl.key /etc/apache2/ssl/ssl.key

RUN echo '\n\n<VirtualHost *:443>\n \
  SSLEngine on\n \
  SSLCertificateFile /etc/apache2/ssl/ssl.crt\n \
  SSLCertificateKeyFile /etc/apache2/ssl/ssl.key\n \
    </VirtualHost>\n' >> /etc/apache2/sites-enabled/000-default.conf

WORKDIR /var/www/html