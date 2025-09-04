<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

// Créer un utilisateur de test
$user = User::create([
    'nom' => 'Test',
    'prenom' => 'User',
    'email' => 'test@test.com',
    'MotDePasse' => bcrypt('password'),
    'role' => 'client',
    'statut' => 'actif'
]);

echo "Utilisateur créé avec l'ID: " . $user->id . "\n";
echo "Nom: " . $user->nom . " " . $user->prenom . "\n";
echo "Email: " . $user->email . "\n";
echo "Mot de passe: password\n";
