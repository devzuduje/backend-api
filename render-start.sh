#!/usr/bin/env bash
echo "🚀 Iniciando servidor Laravel en Render..."

# Generar key si no existe
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Configurar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Iniciar servidor Laravel
php artisan serve --host=0.0.0.0 --port=$PORT
