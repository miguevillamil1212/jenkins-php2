FROM php:8.2-apache
# Habilita mod_rewrite (opcional)
RUN a2enmod rewrite
# Copia el sitio
COPY src/ /var/www/html/
EXPOSE 80
