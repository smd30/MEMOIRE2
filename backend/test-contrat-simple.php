<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contrat;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Compagnie;
use Carbon\Carbon;

echo "🔍 Test de création de contrat simple...\n\n";

try {
    // Récupérer un utilisateur existant
    $user = User::first();
    echo "✅ Utilisateur trouvé: {$user->id}\n";
    
    // Récupérer un véhicule existant
    $vehicule = Vehicule::first();
    echo "✅ Véhicule trouvé: {$vehicule->id}\n";
    
    // Récupérer une compagnie existante
    $compagnie = Compagnie::first();
    echo "✅ Compagnie trouvée: {$compagnie->id}\n";
    
    // Créer un contrat simple
    $contrat = Contrat::create([
        'user_id' => $user->id,
        'vehicule_id' => $vehicule->id,
        'compagnie_id' => $compagnie->id,
        'numero_police' => 'TEST' . time(),
        'numero_attestation' => 'SN' . time(),
        'cle_securite' => 'TEST' . time(),
        'date_debut' => Carbon::now(),
        'date_fin' => Carbon::now()->addMonth(),
        'periode_police' => 1,
        'garanties_selectionnees' => json_encode(['Test']),
        'prime_rc' => 1000,
        'garanties_optionnelles' => 100,
        'accessoires_police' => 500,
        'prime_nette' => 1600,
        'taxes_tuca' => 304,
        'prime_ttc' => 1904,
        'statut' => 'actif',
        'date_souscription' => Carbon::now(),
    ]);
    
    echo "✅ Contrat créé avec succès: {$contrat->id}\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n--- Test terminé ---\n";
