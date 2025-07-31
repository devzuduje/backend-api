FROM php:8.2-cli

# Instalar dependencias básicas
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar archivos
COPY . .

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permisos
RUN chmod -R 775 storage bootstrap/cache

# Script de inicio que maneja el puerto correctamente
RUN echo '#!/bin/bash\nset -e\necho "Starting Laravel..."\nPORT_NUM=$(echo ${PORT:-8000} | sed "s/[^0-9]*//g")\necho "Using port: $PORT_NUM"\nexec php artisan serve --host=0.0.0.0 --port=$PORT_NUM' > /app/start.sh && chmod +x /app/start.sh

# Usar el script de inicio
CMD ["/app/start.sh"]
