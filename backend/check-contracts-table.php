<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Vérification de la table insurance_contracts...\n\n";

try {
    // Vérifier si la table existe
    $tableExists = Schema::hasTable('insurance_contracts');
    echo "Table insurance_contracts existe: " . ($tableExists ? 'OUI' : 'NON') . "\n";
    
    if ($tableExists) {
        // Afficher la structure de la table
        $columns = DB::select("DESCRIBE insurance_contracts");
        echo "\nStructure de la table:\n";
        foreach ($columns as $column) {
            echo "- {$column->Field}: {$column->Type}\n";
        }
        
        // Compter les enregistrements
        $count = DB::table('insurance_contracts')->count();
        echo "\nNombre d'enregistrements: $count\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}

echo "\n--- Vérification terminée ---\n";
