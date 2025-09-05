<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Compagnie;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Contrat;

echo "🔍 Test minimal de la souscription...\n\n";

try {
    // Test 1: Vérifier la compagnie
    echo "1️⃣ Test compagnie...\n";
    $compagnie = Compagnie::find(1);
    echo "✅ Compagnie: {$compagnie->nom}\n";

    // Test 2: Créer utilisateur minimal
    echo "\n2️⃣ Test création utilisateur...\n";
    $user = User::create([
        'nom' => 'Test',
        'prenom' => 'User',
        'email' => 'test.user.' . time() . '@test.com',
        'Telephone' => '+221777777777',
        'adresse' => 'Test Address',
        'role' => 'client',
        'statut' => 'actif',
        'MotDePasse' => bcrypt('password123'),
    ]);
    echo "✅ Utilisateur créé: {$user->id}\n";

    // Test 3: Créer véhicule minimal
    echo "\n3️⃣ Test création véhicule...\n";
    $vehicule = Vehicule::create([
        'user_id' => $user->id,
        'marque_vehicule' => 'TEST',
        'modele' => 'TEST',
        'immatriculation' => 'TEST' . time(),
        'puissance_fiscale' => 6,
        'date_mise_en_circulation' => '2010-01-15',
        'valeur_vehicule' => 5000000,
        'energie' => 'essence',
        'places' => 5,
        'numero_chassis' => 'TEST' . time(),
        'categorie' => 'voiture_particuliere',
        'proprietaire_nom' => $user->nom,
        'proprietaire_prenom' => $user->prenom,
        'proprietaire_adresse' => $user->adresse,
        'proprietaire_telephone' => $user->Telephone,
        'proprietaire_email' => $user->email,
    ]);
    echo "✅ Véhicule créé: {$vehicule->id}\n";

    // Test 4: Créer contrat minimal
    echo "\n4️⃣ Test création contrat...\n";
    $contrat = Contrat::create([
        'user_id' => $user->id,
        'vehicule_id' => $vehicule->id,
        'compagnie_id' => $compagnie->id,
        'numero_police' => 'TEST' . time(),
        'numero_attestation' => 'TEST' . time(),
        'cle_securite' => 'TEST' . time(),
        'date_debut' => '2025-01-20',
        'date_fin' => '2025-02-20',
        'periode_police' => 1,
        'garanties_selectionnees' => json_encode(['Test']),
        'prime_rc' => 1000,
        'garanties_optionnelles' => 100,
        'accessoires_police' => 500,
        'prime_nette' => 1600,
        'taxes_tuca' => 304,
        'prime_ttc' => 1904,
        'statut' => 'actif',
        'date_souscription' => now(),
    ]);
    echo "✅ Contrat créé: {$contrat->id}\n";

    echo "\n🎉 SUCCÈS: Tous les tests ont réussi!\n";

} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n--- Test terminé ---\n";
