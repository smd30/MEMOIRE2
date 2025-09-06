<?php

echo "🧪 TEST EMAIL EN MODE LOG\n";
echo "========================\n\n";

// Créer une instance de l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Configurer l'email en mode log pour tester
config(['mail.default' => 'log']);
config(['mail.mailers.log.transport' => 'log']);

echo "📧 Configuration email en mode LOG\n";
echo "📁 Les emails seront sauvegardés dans storage/logs/laravel.log\n\n";

// Récupérer le contrat créé
$contrat = \App\Models\Contrat::latest()->first();

if ($contrat) {
    echo "📄 Contrat trouvé: " . $contrat->numero_attestation . "\n";
    echo "📧 Email du propriétaire: " . $contrat->vehicule->proprietaire_email . "\n";
    
    // Générer le PDF
    $attestationService = new \App\Services\AttestationService();
    $pdfBase64 = $attestationService->genererAttestation($contrat);
    
    // Tester l'envoi d'email
    $emailService = new \App\Services\EmailService();
    
    try {
        $emailSent = $emailService->envoyerAttestation($contrat, $pdfBase64);
        
        if ($emailSent) {
            echo "✅ Email 'envoyé' avec succès (mode LOG) !\n";
            echo "📁 Vérifiez le fichier storage/logs/laravel.log\n";
            
            // Lire les dernières lignes du log
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $logContent = file_get_contents($logFile);
                $lines = explode("\n", $logContent);
                $lastLines = array_slice($lines, -10);
                
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
        echo "❌ Erreur envoi email: " . $e->getMessage() . "\n";
        echo "🔍 Trace: " . $e->getTraceAsString() . "\n";
    }
} else {
    echo "❌ Aucun contrat trouvé\n";
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";
