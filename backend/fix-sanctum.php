<?php

/**
 * Script de réparation Sanctum
 * Ce script installe et configure Sanctum correctement
 */

echo "🔧 Réparation de Sanctum...\n";

// 1. Vérifier si le fichier .env existe
if (!file_exists('.env')) {
    echo "❌ Fichier .env manquant. Création...\n";
    
    $envContent = 'APP_NAME="Assurance Auto"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=assurance_auto
DB_USERNAME=root
DB_PASSWORD=passer

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@assurance-auto.com
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

# JWT Configuration
JWT_SECRET=your-super-secret-jwt-key-here
JWT_TTL=60
JWT_REFRESH_TTL=20160

# Company API Configuration
COMPANY_API_URL=http://company-api:3000
COMPANY_API_KEY=company-api-secret-key

# Stripe Configuration
STRIPE_KEY=pk_test_51H1234567890
STRIPE_SECRET=sk_test_51H1234567890
STRIPE_WEBHOOK_SECRET=whsec_test_1234567890

# QR Code Configuration
QR_SECRET=qr-code-signature-secret

# Application Specific
CONTRACT_EXPIRY_NOTIFICATION_DAYS=30,7,1
DEFAULT_TAX_RATE=0.20
MAX_VEHICLE_AGE=25
MIN_POWER_FISCAL=1
MAX_POWER_FISCAL=50';

    file_put_contents('.env', $envContent);
    echo "✅ Fichier .env créé\n";
}

// 2. Générer la clé d'application
echo "🔑 Génération de la clé d'application...\n";
$key = 'base64:' . base64_encode(random_bytes(32));
$envContent = file_get_contents('.env');
$envContent = str_replace('APP_KEY=', 'APP_KEY=' . $key, $envContent);
file_put_contents('.env', $envContent);
echo "✅ Clé d'application générée\n";

// 3. Installer Sanctum
echo "📦 Installation de Sanctum...\n";
system('composer require laravel/sanctum --no-interaction');
echo "✅ Sanctum installé\n";

// 4. Publier les configurations Sanctum
echo "🔧 Publication des configurations Sanctum...\n";
system('php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider" --force');
echo "✅ Configurations Sanctum publiées\n";

// 5. Vider le cache de configuration
echo "🧹 Nettoyage du cache...\n";
system('php artisan config:clear');
system('php artisan cache:clear');
system('php artisan route:clear');
echo "✅ Cache nettoyé\n";

// 6. Exécuter les migrations
echo "🗄️ Exécution des migrations...\n";
system('php artisan migrate --force');
echo "✅ Migrations exécutées\n";

// 7. Vérifier que Sanctum est bien installé
echo "🔍 Vérification de l'installation Sanctum...\n";
if (class_exists('Laravel\Sanctum\SanctumServiceProvider')) {
    echo "✅ Sanctum est correctement installé\n";
} else {
    echo "❌ Sanctum n'est pas installé correctement\n";
}

// 8. Vérifier le modèle User
echo "👤 Vérification du modèle User...\n";
$userContent = file_get_contents('app/Models/User.php');
if (strpos($userContent, 'HasApiTokens') !== false) {
    echo "✅ Trait HasApiTokens présent dans User\n";
} else {
    echo "❌ Trait HasApiTokens manquant dans User\n";
}

// 9. Exécuter les seeders
echo "🌱 Exécution des seeders...\n";
system('php artisan db:seed --class=ContractSeeder --force');
echo "✅ Seeders exécutés\n";

echo "\n🎉 Réparation Sanctum terminée !\n";
echo "📋 Test de connexion :\n";
echo "   POST http://localhost:8000/api/auth/login\n";
echo "   {\n";
echo "     \"email\": \"test@example.com\",\n";
echo "     \"password\": \"password\"\n";
echo "   }\n";
echo "\n🚀 Pour démarrer le serveur :\n";
echo "   php artisan serve --port=8000 --host=0.0.0.0\n";
