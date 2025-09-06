<?php

require_once 'vendor/autoload.php';

use App\Services\AttestationService;
use App\Models\Contrat;
use App\Models\User;
use App\Models\Vehicule;
use App\Models\Compagnie;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test de génération d'attestation avec QR Code ===\n\n";

try {
    // Trouver un contrat existant
    $contrat = Contrat::with(['user', 'vehicule', 'compagnie'])->first();
    
    if (!$contrat) {
        echo "❌ Aucun contrat trouvé dans la base de données\n";
        exit(1);
    }
    
    echo "✅ Contrat trouvé: {$contrat->numero_attestation}\n";
    echo "   - Assuré: {$contrat->user->nom} {$contrat->user->prenom}\n";
    echo "   - Véhicule: {$contrat->vehicule->immatriculation}\n";
    echo "   - Compagnie: {$contrat->compagnie->nom}\n\n";
    
    // Générer l'attestation
    $attestationService = new AttestationService();
    $pdfBase64 = $attestationService->genererAttestation($contrat);
    
    if (empty($pdfBase64)) {
        echo "❌ Erreur: L'attestation n'a pas été générée\n";
        exit(1);
    }
    
    echo "✅ Attestation générée avec succès!\n";
    echo "   - Taille du PDF: " . strlen($pdfBase64) . " caractères\n";
    
    // Sauvegarder le PDF pour vérification
    $pdfContent = base64_decode($pdfBase64);
    file_put_contents('test-attestation-qr.pdf', $pdfContent);
    
    echo "✅ PDF sauvegardé: test-attestation-qr.pdf\n";
    echo "   Vous pouvez ouvrir ce fichier pour vérifier que le QR Code s'affiche correctement.\n\n";
    
    echo "=== Test terminé avec succès ===\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . "\n";
    echo "   Ligne: " . $e->getLine() . "\n";
    exit(1);
}

