<?php

$token = '44|pAtnRBbuoo46KO1pPtLaMjoCVy1hinPtu0Zqqw65bd9847a4';
$url = 'http://localhost:8000/api/devis/create';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code de réponse HTTP: " . $httpCode . "\n";
echo "Réponse:\n";
echo $response . "\n";





