<?php

echo "🧪 TEST QR CODE FINAL\n";
echo "=====================\n\n";

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
            echo "🔍 QR Code data: " . json_encode($data['data']['qr_code_data']) . "\n";
            
            // Sauvegarder le PDF pour vérifier
            if (isset($data['data']['attestation_pdf'])) {
                $pdfBase64 = $data['data']['attestation_pdf'];
                echo "📄 Taille du PDF base64: " . strlen($pdfBase64) . " caractères\n";
                
                $pdfContent = base64_decode($pdfBase64);
                if ($pdfContent !== false) {
                    $bytesWritten = file_put_contents('test-qr-final.pdf', $pdfContent);
                    if ($bytesWritten !== false) {
                        echo "💾 PDF sauvegardé: test-qr-final.pdf ($bytesWritten bytes)\n";
                        
                        // Vérifier si le PDF contient des références au QR Code
                        if (strpos($pdfContent, 'QR') !== false || strpos($pdfContent, 'qr') !== false) {
                            echo "✅ Le PDF contient des références au QR Code !\n";
                        } else {
                            echo "❌ Le PDF ne semble pas contenir de références au QR Code\n";
                        }
                        
                        // Vérifier si c'est un QR Code ASCII ou une vraie image
                        if (strpos($pdfContent, '█') !== false || strpos($pdfContent, '░') !== false) {
                            echo "🔤 Le PDF contient un QR Code ASCII (fallback)\n";
                        } else {
                            echo "🖼️ Le PDF contient probablement une vraie image QR Code !\n";
                        }
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