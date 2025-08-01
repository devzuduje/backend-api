#!/usr/bin/env bash
set -e

echo "🔧 Instalando dependencias de Composer..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "🧹 Limpiando caché..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "📦 Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

echo "✅ Build completado!"
