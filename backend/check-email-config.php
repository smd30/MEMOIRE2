<?php

echo "🔍 Vérification de la configuration email...\n\n";

// Charger la configuration Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📧 Configuration Email :\n";
echo "MAIL_MAILER: " . config('mail.default') . "\n";
echo "MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***configuré***' : '❌ non configuré') . "\n";
echo "MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

echo "📁 Templates d'email :\n";
$templates = [
    'emails.attestation' => 'resources/views/emails/attestation.blade.php',
    'emails.confirmation' => 'resources/views/emails/confirmation.blade.php'
];

foreach ($templates as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name: $path\n";
    } else {
        echo "❌ $name: $path (manquant)\n";
    }
}

echo "\n🔧 Services :\n";
$services = [
    'EmailService' => 'app/Services/EmailService.php',
    'AttestationService' => 'app/Services/AttestationService.php'
];

foreach ($services as $name => $path) {
    if (file_exists($path)) {
        echo "✅ $name: $path\n";
    } else {
        echo "❌ $name: $path (manquant)\n";
    }
}

echo "\n📋 Routes d'email :\n";
$routes = [
    '/test-email' => 'Test d\'email simple',
    '/test-attestation-email' => 'Test d\'email d\'attestation',
    '/complete-souscription' => 'Souscription complète avec email'
];

foreach ($routes as $route => $description) {
    echo "✅ $route: $description\n";
}

echo "\n--- Vérification terminée ---\n";
