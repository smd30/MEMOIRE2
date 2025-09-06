<?php

echo "🧪 TEST CONTENU QR CODE\n";
echo "=======================\n\n";

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
            
            // Afficher le contenu attendu du QR Code
            echo "\n📱 CONTENU ATTENDU DU QR CODE :\n";
            echo "================================\n";
            echo "Le véhicule est assuré\n";
            echo "L'attestation d'assurance N° " . $data['data']['numero_attestation'] . " du véhicule de marque PEUGEOT 206 immatriculé " . $data['data']['qr_code_data']['immatriculation'] . " est valide\n";
            echo "du 2025-09-05 au 2026-09-06 23:59:59\n";
            echo "\n✅ Ce message devrait apparaître quand on scanne le QR Code !\n";
            
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
