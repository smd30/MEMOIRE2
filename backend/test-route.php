<?php

echo "🧪 TEST DES ROUTES\n";
echo "==================\n\n";

// Test 1: Route de test simple
echo "1️⃣ Test de la route de test simple...\n";
$url1 = 'http://localhost:8000/api/test-souscription';

$data1 = ['test' => 'data'];

$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url1);
curl_setopt($ch1, CURLOPT_POST, true);
curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($data1));
curl_setopt($ch1, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_TIMEOUT, 10);

$response1 = curl_exec($ch1);
$httpCode1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
curl_close($ch1);

echo "   Code HTTP: " . $httpCode1 . "\n";
if ($httpCode1 == 200) {
    echo "   ✅ Route de test fonctionne !\n";
    $responseData1 = json_decode($response1, true);
    echo "   Réponse: " . $responseData1['message'] . "\n";
} else {
    echo "   ❌ Erreur: " . $response1 . "\n";
}

echo "\n";

// Test 2: Route de test des modèles
echo "2️⃣ Test de la route de test des modèles...\n";
$url2 = 'http://localhost:8000/api/test-models';

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_POST, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode([]));
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 10);

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo "   Code HTTP: " . $httpCode2 . "\n";
if ($httpCode2 == 200) {
    echo "   ✅ Route de test des modèles fonctionne !\n";
    $responseData2 = json_decode($response2, true);
    echo "   Réponse: " . $responseData2['message'] . "\n";
    echo "   User ID: " . $responseData2['data']['user_id'] . "\n";
    echo "   Vehicule ID: " . $responseData2['data']['vehicule_id'] . "\n";
    echo "   Contrat ID: " . $responseData2['data']['contrat_id'] . "\n";
} else {
    echo "   ❌ Erreur: " . $response2 . "\n";
}

echo "\n==================\n";
echo "🏁 Test terminé\n";
