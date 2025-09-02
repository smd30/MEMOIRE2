<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Contract;
use App\Models\Sinistre;
use App\Models\Garantie;
use App\Models\Compagnie;
use Illuminate\Support\Facades\Hash;

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🔧 Création des données de test...\n\n";

    // Récupérer les utilisateurs existants
    $client = User::where('email', 'client@example.com')->first();
    $gestionnaire = User::where('email', 'gestionnaire@example.com')->first();
    $admin = User::where('email', 'admin@example.com')->first();

    if (!$client) {
        echo "❌ Utilisateur client non trouvé\n";
        exit;
    }

    // Créer des garanties
    $garanties = [
        ['name' => 'Responsabilité Civile', 'display_name' => 'RC', 'description' => 'Garantie obligatoire', 'coefficient' => 1.0, 'is_required' => true],
        ['name' => 'Vol', 'display_name' => 'Vol', 'description' => 'Protection contre le vol', 'coefficient' => 1.2, 'is_required' => false],
        ['name' => 'Incendie', 'display_name' => 'Incendie', 'description' => 'Protection contre l\'incendie', 'coefficient' => 1.1, 'is_required' => false],
        ['name' => 'Bris de glace', 'display_name' => 'Bris de glace', 'description' => 'Protection des vitres', 'coefficient' => 1.05, 'is_required' => false],
    ];

    foreach ($garanties as $garantieData) {
        Garantie::firstOrCreate(['name' => $garantieData['name']], $garantieData);
    }

    // Créer des compagnies
    $compagnies = [
        ['nom' => 'AXA', 'description' => 'Compagnie AXA'],
        ['nom' => 'Allianz', 'description' => 'Compagnie Allianz'],
        ['nom' => 'Groupama', 'description' => 'Compagnie Groupama'],
        ['nom' => 'MAIF', 'description' => 'Compagnie MAIF'],
    ];

    foreach ($compagnies as $compagnieData) {
        Compagnie::firstOrCreate(['nom' => $compagnieData['nom']], $compagnieData);
    }

    // Créer des véhicules
    $vehicles = [
        [
            'user_id' => $client->id,
            'marqueVehicule' => 'Renault',
            'modèle' => 'Clio',
            'immatriculation' => 'AB-123-CD',
            'categorie' => 'Citadine',
            'puissanceFiscale' => 5,
            'dateMiseEnCirculation' => '2020-01-15',
            'valeurVéhicule' => 15000,
            'carteGrise' => 'CG123456',
            'color' => 'Blanc'
        ],
        [
            'user_id' => $client->id,
            'marqueVehicule' => 'Peugeot',
            'modèle' => '208',
            'immatriculation' => 'EF-456-GH',
            'categorie' => 'Citadine',
            'puissanceFiscale' => 6,
            'dateMiseEnCirculation' => '2021-03-20',
            'valeurVéhicule' => 18000,
            'carteGrise' => 'CG789012',
            'color' => 'Rouge'
        ]
    ];

    foreach ($vehicles as $vehicleData) {
        Vehicle::firstOrCreate(['immatriculation' => $vehicleData['immatriculation']], $vehicleData);
    }

    // Créer des contrats
    $contracts = [
        [
            'user_id' => $client->id,
            'vehicle_id' => Vehicle::where('immatriculation', 'AB-123-CD')->first()->id,
            'contract_number' => 'CTR-2024-001',
            'start_date' => '2024-01-01',
            'end_date' => '2025-01-01',
            'duration_months' => 12,
            'base_premium' => 800,
            'total_premium' => 960,
            'taxes' => 160,
            'status' => 'actif'
        ],
        [
            'user_id' => $client->id,
            'vehicle_id' => Vehicle::where('immatriculation', 'EF-456-GH')->first()->id,
            'contract_number' => 'CTR-2024-002',
            'start_date' => '2024-02-01',
            'end_date' => '2025-02-01',
            'duration_months' => 12,
            'base_premium' => 900,
            'total_premium' => 1080,
            'taxes' => 180,
            'status' => 'actif'
        ]
    ];

    foreach ($contracts as $contractData) {
        Contract::firstOrCreate(['contract_number' => $contractData['contract_number']], $contractData);
    }

    // Créer des sinistres
    $sinistres = [
        [
            'user_id' => $client->id,
            'contract_id' => Contract::where('contract_number', 'CTR-2024-001')->first()->id,
            'sinistre_number' => 'SIN-2024-001',
            'type' => 'collision',
            'incident_date' => '2024-06-15',
            'location' => 'Paris, France',
            'description' => 'Accident de la circulation',
            'estimated_damage' => 2500,
            'status' => 'nouveau',
            'manager_notes' => 'Sinistre en cours d\'évaluation'
        ],
        [
            'user_id' => $client->id,
            'contract_id' => Contract::where('contract_number', 'CTR-2024-002')->first()->id,
            'sinistre_number' => 'SIN-2024-002',
            'type' => 'vol',
            'incident_date' => '2024-07-20',
            'location' => 'Lyon, France',
            'description' => 'Vol du véhicule',
            'estimated_damage' => 18000,
            'status' => 'en_cours',
            'manager_notes' => 'Enquête en cours'
        ]
    ];

    foreach ($sinistres as $sinistreData) {
        Sinistre::firstOrCreate(['sinistre_number' => $sinistreData['sinistre_number']], $sinistreData);
    }

    echo "✅ Données de test créées avec succès !\n\n";
    echo "📊 RÉSUMÉ :\n";
    echo "   - Garanties : " . Garantie::count() . "\n";
    echo "   - Compagnies : " . Compagnie::count() . "\n";
    echo "   - Véhicules : " . Vehicle::count() . "\n";
    echo "   - Contrats : " . Contract::count() . "\n";
    echo "   - Sinistres : " . Sinistre::count() . "\n\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de la création des données de test: " . $e->getMessage() . "\n";
}
