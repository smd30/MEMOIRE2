<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== UTILISATEURS EXISTANTS ===\n";

$users = User::all(['id', 'nom', 'prenom', 'email', 'role', 'statut']);

foreach ($users as $user) {
    echo sprintf(
        "ID: %d | %s %s | %s | Rôle: %s | Statut: %s\n",
        $user->id,
        $user->prenom,
        $user->nom,
        $user->email,
        $user->role,
        $user->statut
    );
}

echo "\n=== IDENTIFIANTS DE CONNEXION ===\n";
echo "Admin: admin@example.com / password\n";
echo "Gestionnaire: sophie@example.com / password\n";
echo "Client: test@example.com / password\n";






