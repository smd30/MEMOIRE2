<?php

echo "🔍 Vérification des logs Laravel...\n\n";

$logFile = 'storage/logs/laravel.log';

if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $lines = explode("\n", $logs);
    
    // Afficher les 20 dernières lignes
    $recentLines = array_slice($lines, -20);
    
    echo "📋 20 dernières lignes du log:\n";
    foreach ($recentLines as $line) {
        if (trim($line) !== '') {
            echo $line . "\n";
        }
    }
} else {
    echo "❌ Fichier de log non trouvé: $logFile\n";
}

echo "\n--- Vérification terminée ---\n";
