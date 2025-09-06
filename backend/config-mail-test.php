<?php

echo "🔧 CONFIGURATION EMAIL TEMPORAIRE\n";
echo "==================================\n\n";

// Configuration email temporaire pour test
$mailConfig = [
    'MAIL_MAILER' => 'log',  // Mode log pour tester
    'MAIL_HOST' => 'smtp.gmail.com',
    'MAIL_PORT' => 587,
    'MAIL_USERNAME' => 'test@example.com',
    'MAIL_PASSWORD' => 'test-password',
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_FROM_ADDRESS' => 'test@example.com',
    'MAIL_FROM_NAME' => 'Test Insurance',
];

echo "📧 Configuration email en mode LOG\n";
echo "📁 Les emails seront sauvegardés dans storage/logs/laravel.log\n\n";

// Appliquer la configuration
foreach ($mailConfig as $key => $value) {
    putenv("$key=$value");
    echo "✅ $key = $value\n";
}

echo "\n🎯 Configuration appliquée !\n";
echo "📧 Testez maintenant l'envoi d'email depuis votre application Angular\n";
echo "📁 Vérifiez le fichier storage/logs/laravel.log pour voir les emails\n";
