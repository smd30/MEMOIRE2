#!/bin/bash

# Script de démarrage pour Render
echo "🚀 Démarrage de l'application MEMOIRE2 sur Render..."

# Générer la clé d'application si elle n'existe pas
if [ -z "$APP_KEY" ]; then
    echo "🔑 Génération de la clé d'application..."
    php artisan key:generate --force
fi

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer PHP-FPM
echo "🌐 Démarrage du serveur..."
php-fpm
