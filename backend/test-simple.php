<?php

echo "🔍 TEST SIMPLE - Vérification du serveur\n";
echo "==========================================\n\n";

// Test 1: Vérifier si le serveur répond
$url = 'http://localhost:8000/api/test';

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json'
        ]
    ]
]);

echo "1️⃣ Test de connexion au serveur...\n";
$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Le serveur ne répond pas sur http://localhost:8000\n";
    echo "   Vérifiez que le serveur Laravel est démarré avec: php artisan serve\n\n";
} else {
    echo "✅ Le serveur répond !\n";
    echo "   Réponse: " . $response . "\n\n";
}

// Test 2: Vérifier la route de souscription
echo "2️⃣ Test de la route de souscription...\n";
$url2 = 'http://localhost:8000/api/souscription';

$data = [
    'test' => 'data'
];

$context2 = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        'content' => json_encode($data)
    ]
]);

$response2 = @file_get_contents($url2, false, $context2);

if ($response2 === false) {
    echo "❌ Erreur lors de l'appel à la route de souscription\n";
    echo "   Vérifiez les logs Laravel pour plus de détails\n\n";
} else {
    echo "✅ La route de souscription répond !\n";
    $responseData = json_decode($response2, true);
    echo "   Réponse: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";
}

echo "==========================================\n";
echo "🏁 Test terminé\n";