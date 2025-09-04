<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST MODÈLE DEVIS ===\n\n";

try {
    echo "1. Test - Vérifier l'existence du modèle Devis\n";
    $devisModel = new \App\Models\Devis();
    echo "✅ Modèle Devis chargé avec succès\n";
    
    echo "\n2. Test - Vérifier la table devis\n";
    $devis = \App\Models\Devis::all();
    echo "✅ Table devis accessible - " . count($devis) . " devis trouvés\n";
    
    echo "\n3. Test - Vérifier les relations\n";
    if (count($devis) > 0) {
        $firstDevis = $devis->first();
        echo "   Premier devis ID: " . $firstDevis->id . "\n";
        echo "   Montant: " . $firstDevis->montant . "\n";
        echo "   Statut: " . $firstDevis->statut . "\n";
    } else {
        echo "   Aucun devis dans la base de données\n";
    }
    
    echo "\n4. Test - Vérifier les constantes\n";
    echo "   PERIODES: " . implode(', ', \App\Models\Devis::PERIODES) . "\n";
    echo "   STATUT_EN_ATTENTE: " . \App\Models\Devis::STATUT_EN_ATTENTE . "\n";
    echo "   STATUT_ACCEPTE: " . \App\Models\Devis::STATUT_ACCEPTE . "\n";
    echo "   STATUT_REJETE: " . \App\Models\Devis::STATUT_REJETE . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
}

echo "\n=== FIN DU TEST ===\n";

