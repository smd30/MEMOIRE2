<?php

echo "📋 VÉRIFICATION DES LOGS\n";
echo "========================\n\n";

$logFile = 'storage/logs/laravel.log';

if (file_exists($logFile)) {
    echo "📁 Fichier de log trouvé: $logFile\n";
    
    $logContent = file_get_contents($logFile);
    $lines = explode("\n", $logContent);
    
    echo "📊 Nombre total de lignes: " . count($lines) . "\n\n";
    
    // Afficher les dernières 20 lignes
    $lastLines = array_slice($lines, -20);
    
    echo "📋 Dernières 20 lignes du log:\n";
    echo "===============================\n";
    
    foreach ($lastLines as $line) {
        if (trim($line)) {
            echo $line . "\n";
        }
    }
    
    // Chercher les erreurs d'email
    $emailErrors = array_filter($lines, function($line) {
        return strpos($line, 'email') !== false || strpos($line, 'Email') !== false;
    });
    
    if (!empty($emailErrors)) {
        echo "\n📧 Erreurs liées à l'email:\n";
        echo "============================\n";
        foreach (array_slice($emailErrors, -10) as $error) {
            echo $error . "\n";
        }
    }
    
} else {
    echo "❌ Fichier de log non trouvé: $logFile\n";
}

echo "\n==========================================\n";
echo "🏁 Vérification terminée\n";