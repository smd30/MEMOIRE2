<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\AttestationService;
use App\Models\Contrat;

echo "=== Test HTML QR Code ===\n\n";

try {
    // Trouver un contrat récent
    $contrat = Contrat::latest()->first();
    
    if (!$contrat) {
        echo "❌ Aucun contrat trouvé en base de données\n";
        exit(1);
    }
    
    echo "✅ Contrat trouvé: {$contrat->numero_attestation}\n\n";
    
    // Générer l'attestation
    $attestationService = new AttestationService();
    
    // Utiliser la réflexion pour accéder à la méthode privée
    $reflection = new ReflectionClass($attestationService);
    $method = $reflection->getMethod('genererQRCodeBase64');
    $method->setAccessible(true);
    
    $data = "Test QR Code - Contrat: {$contrat->numero_attestation} - Date: " . date('Y-m-d H:i:s');
    $qrCodeHtml = $method->invoke($attestationService, $data);
    
    echo "📱 QR Code HTML généré:\n";
    echo "   - Taille: " . strlen($qrCodeHtml) . " caractères\n";
    echo "   - Contient 'QR Code': " . (strpos($qrCodeHtml, 'QR Code') !== false ? 'OUI' : 'NON') . "\n";
    echo "   - Contient '█': " . (strpos($qrCodeHtml, '█') !== false ? 'OUI' : 'NON') . "\n\n";
    
    // Créer un HTML simple pour tester
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Test QR Code HTML</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
        </style>
    </head>
    <body>
        <h1>Test QR Code HTML</h1>
        <p>Données: ' . htmlspecialchars($data) . '</p>
        
        <div>
            <h3>QR Code généré:</h3>
            ' . $qrCodeHtml . '
        </div>
    </body>
    </html>';
    
    // Sauvegarder le HTML de test
    file_put_contents('test-qr-html.html', $html);
    echo "✅ HTML de test sauvegardé: test-qr-html.html\n";
    echo "   Ouvrez ce fichier dans votre navigateur pour voir le QR Code.\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "=== Test terminé ===\n";
