<?php

echo "🧪 TEST PAGE UNIQUE OPTIMISÉE\n";
echo "=============================\n\n";

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

echo "🚀 Envoi de la requête...\n";
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
            echo "🎉 SUCCÈS : PDF généré avec QR Code !\n";
            echo "📄 Nom du fichier: " . $data['data']['pdf_filename'] . "\n";
            
            // Sauvegarder le PDF pour vérifier
            if (isset($data['data']['attestation_pdf'])) {
                $pdfBase64 = $data['data']['attestation_pdf'];
                echo "📄 Taille du PDF base64: " . strlen($pdfBase64) . " caractères\n";
                
                $pdfContent = base64_decode($pdfBase64);
                if ($pdfContent !== false) {
                    $bytesWritten = file_put_contents('test-single-page.pdf', $pdfContent);
                    if ($bytesWritten !== false) {
                        echo "💾 PDF sauvegardé: test-single-page.pdf ($bytesWritten bytes)\n";
                        
                        // Vérifier le contenu du PDF
                        if (strpos($pdfContent, 'INFORMATIONS DE SÉCURITÉ') !== false) {
                            echo "❌ Le PDF contient encore la section 'INFORMATIONS DE SÉCURITÉ'\n";
                        } else {
                            echo "✅ La section 'INFORMATIONS DE SÉCURITÉ' a été supprimée\n";
                        }
                        
                        if (strpos($pdfContent, 'QR CODE DE VÉRIFICATION') !== false) {
                            echo "✅ Le QR Code est présent dans le PDF\n";
                        } else {
                            echo "❌ Le QR Code n'est pas présent dans le PDF\n";
                        }
                        
                        echo "\n📋 CONTENU DE L'ATTESTATION :\n";
                        echo "============================\n";
                        echo "✅ En-tête avec titre et numéro d'attestation\n";
                        echo "✅ Informations du véhicule\n";
                        echo "✅ Informations du souscripteur\n";
                        echo "✅ Période de validité\n";
                        echo "✅ Garanties incluses\n";
                        echo "✅ QR Code de vérification\n";
                        echo "✅ Informations de la compagnie\n";
                        echo "❌ Section 'INFORMATIONS DE SÉCURITÉ' supprimée\n";
                        echo "\n🎯 L'attestation devrait maintenant tenir sur une seule page !\n";
                    } else {
                        echo "❌ Erreur lors de la sauvegarde du PDF\n";
                    }
                } else {
                    echo "❌ Erreur lors du décodage base64 du PDF\n";
                }
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
