<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\ClientProfile;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // 1. Créer l'utilisateur CLIENT
    $client = User::create([
        'nom' => 'Dupont',
        'prenom' => 'Jean',
        'email' => 'client@example.com',
        'MotDePasse' => Hash::make('password'),
        'Telephone' => '0123456789',
        'adresse' => '123 Rue de la Paix, Paris',
        'role' => 'client',
        'statut' => 'actif',
    ]);

    // Créer le profil client
    ClientProfile::create([
        'user_id' => $client->id,
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

    echo "✅ CLIENT créé avec succès !\n";
    echo "Email: client@example.com\n";
    echo "Mot de passe: password\n\n";

    // 2. Créer l'utilisateur GESTIONNAIRE
    $gestionnaire = User::create([
        'nom' => 'Martin',
        'prenom' => 'Sophie',
        'email' => 'gestionnaire@example.com',
        'MotDePasse' => Hash::make('password'),
        'Telephone' => '0987654321',
        'adresse' => '456 Avenue des Gestionnaires, Lyon',
        'role' => 'gestionnaire',
        'statut' => 'actif',
    ]);

    echo "✅ GESTIONNAIRE créé avec succès !\n";
    echo "Email: gestionnaire@example.com\n";
    echo "Mot de passe: password\n\n";

    // 3. Créer l'utilisateur ADMINISTRATEUR
    $admin = User::create([
        'nom' => 'Admin',
        'prenom' => 'Super',
        'email' => 'admin@example.com',
        'MotDePasse' => Hash::make('password'),
        'Telephone' => '0555666777',
        'adresse' => '789 Boulevard de l\'Administration, Marseille',
        'role' => 'admin',
        'statut' => 'actif',
    ]);

    echo "✅ ADMINISTRATEUR créé avec succès !\n";
    echo "Email: admin@example.com\n";
    echo "Mot de passe: password\n\n";

    echo "🎉 Tous les utilisateurs ont été créés avec succès !\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de la création des utilisateurs: " . $e->getMessage() . "\n";
}
