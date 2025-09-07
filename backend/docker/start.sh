#!/bin/bash

# Script de démarrage pour l'application Laravel

echo "🚀 Démarrage de l'application KDS Assurance..."

# Attendre que la base de données soit prête
echo "⏳ Attente de la base de données..."
while ! nc -z mysql 3306; do
  sleep 1
done
echo "✅ Base de données connectée!"

# Générer la clé d'application si elle n'existe pas
if [ ! -f /var/www/html/.env ]; then
    echo "📝 Création du fichier .env..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Générer la clé d'application
echo "🔑 Génération de la clé d'application..."
php artisan key:generate --force

# Exécuter les migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Créer le lien symbolique pour le stockage
echo "🔗 Création du lien symbolique de stockage..."
php artisan storage:link

# Optimiser l'application
echo "⚡ Optimisation de l'application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear

# Créer les répertoires nécessaires
echo "📁 Création des répertoires..."
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views

# Configurer les permissions
echo "🔐 Configuration des permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "✅ Application prête! Démarrage des services..."

# Démarrer Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

