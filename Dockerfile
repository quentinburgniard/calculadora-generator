FROM php:8-apache
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY index.php .
RUN chown -R www-data:www-data .