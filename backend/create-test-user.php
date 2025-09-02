<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\ClientProfile;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Créer l'utilisateur de test
    $user = User::create([
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'test@example.com',
        'MotDePasse' => Hash::make('password'),
        'Telephone' => '0123456789',
        'adresse' => '123 Rue de la Paix, Paris',
        'role' => 'client',
        'statut' => 'actif',
    ]);

    // Créer le profil client
    ClientProfile::create([
        'user_id' => $user->id,
        'address' => '123 Rue de la Paix, Paris',
        'city' => 'Paris',
        'postal_code' => '75001',
        'country' => 'France',
        'birth_date' => '1990-01-01',
        'driving_license_number' => '123456789',
        'driving_license_date' => '2020-01-01',
        'driving_experience_years' => 5,
        'has_garage' => true,
    ]);

    echo "Utilisateur de test créé avec succès !\n";
    echo "Email: test@example.com\n";
    echo "Mot de passe: password\n";

} catch (Exception $e) {
    echo "Erreur lors de la création de l'utilisateur: " . $e->getMessage() . "\n";
}
