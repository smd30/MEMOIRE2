<?php

echo "🚀 Test de souscription finale qui fonctionne...\n";

$url = 'http://localhost:8000/api/working-final-souscription';

// Données exactement comme envoyées par Angular (avec types string)
$data = [
    'vehicule' => [
        'marque_vehicule' => 'PEUGEOT',
        'modele' => '206',
        'immatriculation' => 'DK4964AF',
        'puissance_fiscale' => '6', // String comme Angular
        'date_mise_en_circulation' => '2010-01-15',
        'valeur_vehicule' => '5000000', // String comme Angular
        'energie' => 'essence',
        'places' => '5', // String comme Angular
        'numero_chassis' => 'VF3XXXXXXXXXXXXXXX',
    ],
    'compagnie_id' => '1', // String comme Angular
    'periode_police' => '1', // String comme Angular
    'date_debut' => '2025-09-04', // String comme Angular
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
        'prime_rc' => '3405', // String comme Angular
        'garanties_optionnelles' => '200', // String comme Angular
        'accessoires_police' => '2000', // String comme Angular
        'prime_nette' => '5605', // String comme Angular
        'taxes_tuca' => '1065', // String comme Angular
        'prime_ttc' => '6670' // String comme Angular
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
echo "Données envoyées (simulant Angular):\n";
print_r($data);

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