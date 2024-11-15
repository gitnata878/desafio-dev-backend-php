# Use uma imagem base do PHP
FROM php:8.1-fpm

# Instala dependências necessárias
RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www

# Copia os arquivos do projeto
COPY . .

# Instala dependências do Laravel
RUN composer install --optimize-autoloader --no-dev

# Define permissões para o diretório storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copia o arquivo swagger.json gerado para o diretório público
RUN cp /var/www/storage/api-docs/api-docs.json /var/www/public/api-docs.json

# Expõe a porta 8000 para o servidor embutido
EXPOSE 8000

# Comando para iniciar o servidor embutido do Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
