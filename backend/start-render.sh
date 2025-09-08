#!/bin/bash

# Script de démarrage pour Render
echo "🚀 Démarrage de l'application MEMOIRE2 sur Render..."

# Générer la clé d'application si elle n'existe pas
echo "🔑 Génération de la clé d'application..."
php artisan key:generate --force

# Créer le fichier .env si nécessaire
if [ ! -f .env ]; then
    echo "📝 Création du fichier .env..."
    cp .env.example .env
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
