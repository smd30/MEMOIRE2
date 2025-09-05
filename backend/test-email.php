<?php

echo "🚀 Test des modèles...\n";

$url = 'http://localhost:8000/api/test-email';

$data = ['test' => 'data'];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        'content' => json_encode($data)
    ]
]);

echo "URL: $url\n";

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "\n❌ Erreur lors de la requête HTTP\n";
    $error = error_get_last();
    print_r($error);
} else {
    echo "\n✅ Réponse reçue:\n";
    $responseData = json_decode($response, true);
    print_r($responseData);
}

echo "\n--- Test terminé ---\n";