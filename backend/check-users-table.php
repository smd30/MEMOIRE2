<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🔍 Vérification de la structure de la table users...\n\n";

try {
    // Vérifier la structure de la table users
    $columns = DB::select("DESCRIBE users");
    
    echo "📋 Colonnes de la table users:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type} " . 
             ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . 
             ($column->Default ? " DEFAULT '{$column->Default}'" : '') . "\n";
    }

    // Vérifier le modèle User
    echo "\n📋 Modèle User fillable:\n";
    $user = new \App\Models\User();
    echo "Fillable: " . implode(', ', $user->getFillable()) . "\n";

} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n--- Vérification terminée ---\n";
