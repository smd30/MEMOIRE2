<?php

echo "🧪 TEST EMAIL DIRECT\n";
echo "===================\n\n";

// Créer une instance de l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Configuration email en mode log
config(['mail.default' => 'log']);
config(['mail.mailers.log.transport' => 'log']);

echo "📧 Configuration email en mode LOG\n";

// Récupérer le dernier contrat
$contrat = \App\Models\Contrat::with(['user', 'vehicule', 'compagnie'])->latest()->first();

if (!$contrat) {
    echo "❌ Aucun contrat trouvé\n";
    exit;
}

echo "📄 Contrat trouvé: {$contrat->numero_attestation}\n";
echo "📧 Email du propriétaire: {$contrat->vehicule->proprietaire_email}\n";

try {
    // Générer le PDF
    $attestationService = new \App\Services\AttestationService();
    $pdfBase64 = $attestationService->genererAttestation($contrat);
    echo "✅ PDF généré\n";
    
    // Test avec EmailService
    $emailService = new \App\Services\EmailService();
    $emailSent = $emailService->envoyerAttestation($contrat, $pdfBase64);
    
    if ($emailSent) {
        echo "✅ Email 'envoyé' avec succès (mode LOG) !\n";
        echo "📁 Vérifiez le fichier storage/logs/laravel.log\n";
        
        // Lire les dernières lignes du log
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logContent = file_get_contents($logFile);
            $lines = explode("\n", $logContent);
            $lastLines = array_slice($lines, -20);
            
            echo "\n📋 Dernières lignes du log:\n";
            echo "============================\n";
            foreach ($lastLines as $line) {
                if (trim($line)) {
                    echo $line . "\n";
                }
            }
        }
    } else {
        echo "❌ L'email n'a pas été envoyé\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: {$e->getMessage()}\n";
    echo "🔍 Trace: {$e->getTraceAsString()}\n";
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";
