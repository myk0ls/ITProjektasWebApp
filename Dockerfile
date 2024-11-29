FROM php:8.2-apache

# Ensure clean package management
RUN apt-get update && apt-get install -y \
    git \
    curl \
    ffmpeg \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html/

# Clear Composer cache and install dependencies
RUN composer require php-ffmpeg/php-ffmpeg -v

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www/html

RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 777 /var/www/html/uploads

# Modify php.ini settings
RUN echo "upload_max_filesize = 512M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size = 512M" >> /usr/local/etc/php/conf.d/custom.ini

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
