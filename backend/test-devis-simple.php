<?php

echo "=== TEST ENDPOINT /API/DEVIS SIMPLE ===\n\n";

// Test direct de l'endpoint
echo "Test - Endpoint /api/devis\n";
$url = 'http://localhost:8000/api/devis';

// Utiliser cURL si disponible, sinon file_get_contents
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/json'
    ]);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Code HTTP: " . $httpCode . "\n";
} else {
    $result = file_get_contents($url);
    $httpCode = 200; // Approximation
}

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    echo "✅ Réponse reçue:\n";
    echo "Contenu brut: " . substr($result, 0, 500) . "...\n\n";
    
    $response = json_decode($result, true);
    if ($response === null) {
        echo "❌ Erreur de décodage JSON\n";
        echo "JSON Error: " . json_last_error_msg() . "\n";
    } else {
        echo "✅ JSON décodé avec succès:\n";
        if (isset($response['success'])) {
            echo "   Success: " . ($response['success'] ? 'true' : 'false') . "\n";
        }
        if (isset($response['message'])) {
            echo "   Message: " . $response['message'] . "\n";
        }
        if (isset($response['data'])) {
            if (is_array($response['data'])) {
                echo "   Data: Array avec " . count($response['data']) . " éléments\n";
            } else {
                echo "   Data: " . $response['data'] . "\n";
            }
        }
    }
}

echo "\n=== FIN DU TEST ===\n";

