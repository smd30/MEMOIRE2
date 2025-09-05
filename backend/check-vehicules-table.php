<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 Vérification de la structure de la table vehicules...\n\n";

try {
    // Vérifier la structure de la table vehicules
    $columns = DB::select("DESCRIBE vehicules");
    
    echo "📋 Colonnes de la table vehicules:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field}: {$column->Type} " . 
             ($column->Null === 'NO' ? 'NOT NULL' : 'NULL') . 
             ($column->Default ? " DEFAULT '{$column->Default}'" : '') . "\n";
    }

    // Vérifier les valeurs possibles pour la colonne categorie
    echo "\n📋 Valeurs possibles pour categorie:\n";
    $categories = DB::select("SHOW COLUMNS FROM vehicules LIKE 'categorie'");
    if (!empty($categories)) {
        $category = $categories[0];
        echo "Type: {$category->Type}\n";
        if (isset($category->Comment)) {
            echo "Comment: {$category->Comment}\n";
        }
    }

} catch (Exception $e) {
    echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
}

echo "\n--- Vérification terminée ---\n";
