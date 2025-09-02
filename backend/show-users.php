<?php

require_once 'vendor/autoload.php';

use App\Models\User;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🔍 LISTE DES UTILISATEURS CRÉÉS :\n";
    echo "================================\n\n";

    $users = User::all();

    foreach ($users as $user) {
        echo "👤 " . strtoupper($user->role) . " :\n";
        echo "   Nom : {$user->prenom} {$user->nom}\n";
        echo "   Email : {$user->email}\n";
        echo "   Mot de passe : password\n";
        echo "   Rôle : {$user->role}\n";
        echo "   Statut : {$user->statut}\n";
        echo "   Téléphone : {$user->Telephone}\n";
        echo "   Adresse : {$user->adresse}\n";
        echo "   ----------------------------------------\n\n";
    }

    echo "🎯 RÉSUMÉ DES IDENTIFIANTS DE CONNEXION :\n";
    echo "========================================\n\n";
    echo "📧 CLIENT :\n";
    echo "   Email : client@example.com\n";
    echo "   Mot de passe : password\n";
    echo "   Interface : Client (vehicles)\n\n";
    
    echo "👨‍💼 GESTIONNAIRE :\n";
    echo "   Email : gestionnaire@example.com\n";
    echo "   Mot de passe : password\n";
    echo "   Interface : Gestionnaire (/gestionnaire)\n\n";
    
    echo "👑 ADMINISTRATEUR :\n";
    echo "   Email : admin@example.com\n";
    echo "   Mot de passe : password\n";
    echo "   Interface : Admin (/admin)\n\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
