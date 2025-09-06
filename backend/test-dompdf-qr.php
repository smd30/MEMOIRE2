<?php

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use SimpleSoftwareIO\QrCode\QrCode;

echo "=== Test DomPDF avec QR Code ===\n\n";

try {
    // Générer le QR Code
    $data = "Test QR Code - Contrat: TEST123 - Date: " . date('Y-m-d H:i:s');
    echo "📱 Génération du QR Code...\n";
    
    $qrCodeGenerator = new QrCode();
    $qrCode = $qrCodeGenerator->format('png')
        ->size(150)
        ->margin(1)
        ->generate($data);
    
    $qrBase64 = 'data:image/png;base64,' . base64_encode($qrCode);
    echo "✅ QR Code généré: " . strlen($qrBase64) . " caractères\n\n";
    
    // Créer le HTML avec le QR Code
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Test Attestation avec QR Code</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .header { text-align: center; margin-bottom: 30px; }
            .qr-section { text-align: center; margin: 30px 0; padding: 20px; border: 2px solid #333; }
            .qr-code img { width: 150px; height: 150px; border: 1px solid #ccc; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>ATTESTATION D\'ASSURANCE</h1>
            <h2>N° TEST123</h2>
        </div>
        
        <div class="content">
            <p><strong>Contrat:</strong> TEST123</p>
            <p><strong>Date:</strong> ' . date('d/m/Y H:i:s') . '</p>
            <p><strong>Véhicule:</strong> PEUGEOT 206</p>
            <p><strong>Immatriculation:</strong> TEST123</p>
        </div>
        
        <div class="qr-section">
            <h3>QR Code de vérification</h3>
            <div class="qr-code">
                <img src="' . $qrBase64 . '" alt="QR Code de vérification">
                <p>Code QR de vérification</p>
            </div>
        </div>
        
        <div class="footer">
            <p><em>Ceci est un test de génération PDF avec QR Code</em></p>
        </div>
    </body>
    </html>';
    
    echo "📄 Génération du PDF avec DomPDF...\n";
    
    // Configuration DomPDF
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Sauvegarder le PDF
    $filename = 'test-dompdf-qr-' . time() . '.pdf';
    file_put_contents($filename, $dompdf->output());
    
    echo "✅ PDF généré avec succès: $filename\n";
    echo "   Ouvrez ce fichier pour vérifier si le QR Code s'affiche.\n\n";
    
    // Vérifier la taille du PDF
    $pdfSize = filesize($filename);
    echo "📊 Informations du PDF:\n";
    echo "   - Taille: " . number_format($pdfSize) . " bytes\n";
    echo "   - QR Code inclus: " . (strpos($html, 'data:image/png;base64,') !== false ? 'OUI' : 'NON') . "\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Test terminé ===\n";
