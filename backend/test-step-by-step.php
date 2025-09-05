<?php

echo "🚀 Test étape par étape...\n";

$url = 'http://localhost:8000/api/step-by-step';

// Données exactement comme envoyées par Angular
$data = [
    'vehicule' => [
        'marque_vehicule' => 'PEUGEOT',
        'modele' => '206',
        'immatriculation' => 'DK4964AF',
        'puissance_fiscale' => '6',
        'date_mise_en_circulation' => '2010-01-15',
        'valeur_vehicule' => '5000000',
        'energie' => 'essence',
        'places' => '5',
        'numero_chassis' => 'VF3XXXXXXXXXXXXXXX',
    ],
    'compagnie_id' => '1',
    'periode_police' => '1',
    'date_debut' => '2025-09-04',
    'garanties_selectionnees' => [
        'Responsabilité Civile',
        'Vol'
    ],
    'proprietaire' => [
        'nom' => 'NDAO',
        'prenom' => 'ABDOU',
        'email' => 'abdou.ndao.' . time() . '@test.com',
        'telephone' => '+221777777777',
        'adresse' => '123 Rue de la Paix'
    ],
    'devis_calcule' => [
        'prime_rc' => '3405',
        'garanties_optionnelles' => '200',
        'accessoires_police' => '2000',
        'prime_nette' => '5605',
        'taxes_tuca' => '1065',
        'prime_ttc' => '6670'
    ]
];

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