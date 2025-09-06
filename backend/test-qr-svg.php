<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use SimpleSoftwareIO\QrCode\Facades\QrCode;

echo "=== Test QR Code SVG ===\n\n";

try {
    // Test de génération QR Code SVG
    $data = "Test QR Code SVG - Contrat: TEST123 - Date: " . date('Y-m-d H:i:s');
    
    echo "📱 Génération du QR Code SVG...\n";
    echo "   Données: $data\n\n";
    
    // Générer le QR Code en SVG
    $qrCodeSvg = QrCode::format('svg')
        ->size(200)
        ->margin(1)
        ->generate($data);
    
    echo "✅ QR Code SVG généré avec succès!\n";
    echo "   - Taille: " . strlen($qrCodeSvg) . " caractères\n";
    echo "   - Début: " . substr($qrCodeSvg, 0, 50) . "...\n\n";
    
    // Créer un HTML simple pour tester
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test QR Code SVG</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .qr-section { text-align: center; margin: 30px 0; padding: 20px; border: 2px solid #333; }
        </style>
    </head>
    <body>
        <h1>Test QR Code SVG</h1>
        <p>Données: ' . htmlspecialchars($data) . '</p>
        
        <div class="qr-section">
            <h3>QR Code de vérification</h3>
            ' . $qrCodeSvg . '
            <p>Code QR de vérification</p>
        </div>
    </body>
    </html>';
    
    // Sauvegarder le HTML de test
    file_put_contents('test-qr-svg.html', $html);
    echo "✅ HTML de test sauvegardé: test-qr-svg.html\n";
    echo "   Ouvrez ce fichier dans votre navigateur pour voir le QR Code SVG.\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "=== Test terminé ===\n";
