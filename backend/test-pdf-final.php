<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\AttestationService;
use App\Models\Contrat;

echo "=== Test PDF Final avec QR Code ===\n\n";

try {
    // Trouver un contrat récent
    $contrat = Contrat::latest()->first();
    
    if (!$contrat) {
        echo "❌ Aucun contrat trouvé en base de données\n";
        exit(1);
    }
    
    echo "✅ Contrat trouvé: {$contrat->numero_attestation}\n";
    echo "   - Assuré: {$contrat->user->prenom} {$contrat->user->nom}\n";
    echo "   - Véhicule: {$contrat->vehicule->marque_vehicule} {$contrat->vehicule->modele}\n";
    echo "   - Immatriculation: {$contrat->vehicule->immatriculation}\n\n";
    
    // Générer l'attestation
    $attestationService = new AttestationService();
    $pdfBase64 = $attestationService->genererAttestation($contrat);
    
    if ($pdfBase64) {
        echo "✅ Attestation générée avec succès!\n";
        echo "   - Taille du PDF: " . strlen($pdfBase64) . " caractères\n";
        
        // Sauvegarder le PDF pour vérification
        $pdfContent = base64_decode($pdfBase64);
        $filename = 'test-attestation-final-' . time() . '.pdf';
        file_put_contents($filename, $pdfContent);
        
        echo "✅ PDF sauvegardé: $filename\n";
        echo "   Ouvrez ce fichier pour vérifier que le QR Code s'affiche correctement.\n\n";
        
        // Vérifier le contenu du PDF pour voir s'il contient des références au QR Code
        $pdfText = $pdfContent;
        if (strpos($pdfText, 'QR Code') !== false) {
            echo "✅ Le PDF contient des références au QR Code\n";
        } else {
            echo "⚠️  Le PDF ne semble pas contenir de références au QR Code\n";
        }
        
    } else {
        echo "❌ Erreur lors de la génération de l'attestation\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n=== Test terminé ===\n";
