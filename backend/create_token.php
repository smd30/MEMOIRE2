<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Trouver l'utilisateur de test
$user = User::where('email', 'test@test.com')->first();

if (!$user) {
    echo "Utilisateur de test non trouvé\n";
    exit(1);
}

// Créer un token Sanctum
$token = $user->createToken('test-token')->plainTextToken;

echo "Token d'authentification créé:\n";
echo $token . "\n";
echo "\nUtilisez ce token dans l'en-tête Authorization: Bearer <token>\n";


