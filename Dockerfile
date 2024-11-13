FROM php:8.2-apache

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

RUN a2enmod rewrite

CMD ["apache2-foreground"]
