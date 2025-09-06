<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

echo "🧪 TEST EMAIL SIMPLE\n";
echo "===================\n\n";

// Configuration email de base
Config::set('mail.default', 'smtp');
Config::set('mail.mailers.smtp.transport', 'smtp');
Config::set('mail.mailers.smtp.host', 'smtp.gmail.com');
Config::set('mail.mailers.smtp.port', 587);
Config::set('mail.mailers.smtp.encryption', 'tls');
Config::set('mail.mailers.smtp.username', 'your-email@gmail.com');
Config::set('mail.mailers.smtp.password', 'your-app-password');

Config::set('mail.from.address', 'your-email@gmail.com');
Config::set('mail.from.name', 'Test Insurance');

try {
    echo "📧 Test d'envoi d'email...\n";
    
    Mail::raw('Test email from Laravel', function ($message) {
        $message->to('test@example.com')
                ->subject('Test Email');
    });
    
    echo "✅ Email envoyé avec succès !\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "🔍 Vérifiez votre configuration SMTP\n";
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";