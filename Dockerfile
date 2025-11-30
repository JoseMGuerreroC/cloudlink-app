# Usamos la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalamos extensiones necesarias (PDO para MySQL y PostgreSQL)
# Render suele usar PostgreSQL gratis, así que instalamos ambos drivers
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Habilitamos el módulo rewrite de Apache (buena práctica)
RUN a2enmod rewrite

# Copiamos los archivos de nuestra app al servidor
COPY . /var/www/html/

# Ajustamos permisos
RUN chown -R www-data:www-data /var/www/html/

# Exponemos el puerto 80
EXPOSE 80