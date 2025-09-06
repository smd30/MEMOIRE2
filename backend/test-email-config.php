<?php

echo "🧪 TEST CONFIGURATION EMAIL\n";
echo "===========================\n\n";

// Test avec curl pour être plus robuste
$url = 'http://localhost:8000/api/test-qr-code';

$data = [];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

echo "🚀 Test de génération PDF...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "📊 Code HTTP: $httpCode\n";

if ($error) {
    echo "❌ Erreur cURL: $error\n";
} else {
    echo "✅ Réponse reçue !\n";
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data && isset($data['success']) && $data['success']) {
            echo "🎉 SUCCÈS : PDF généré !\n";
            
            // Maintenant testons l'envoi d'email
            echo "\n📧 Test d'envoi d'email...\n";
            
            // Utiliser le service EmailService directement
            require_once 'vendor/autoload.php';
            
            // Créer une instance de l'application Laravel
            $app = require_once 'bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
            
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
                        echo "✅ Email envoyé avec succès !\n";
                        echo "📧 Vérifiez la boîte email: " . $contrat->vehicule->proprietaire_email . "\n";
                    } else {
                        echo "❌ L'email n'a pas été envoyé\n";
                    }
                    
                } catch (Exception $e) {
                    echo "❌ Erreur envoi email: " . $e->getMessage() . "\n";
                    echo "🔍 Vérifiez la configuration SMTP dans .env\n";
                }
            } else {
                echo "❌ Aucun contrat trouvé\n";
            }
        } else {
            echo "❌ ÉCHEC : " . ($data['message'] ?? 'Erreur inconnue') . "\n";
        }
    } else {
        echo "❌ Erreur HTTP $httpCode\n";
        echo "Réponse: $response\n";
    }
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";
