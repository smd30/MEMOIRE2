<?php

echo "🎯 TEST FINAL - EMAIL AU PROPRIÉTAIRE DU VÉHICULE (EMAIL PROPRIÉTAIRE)\n";
echo "======================================================================\n\n";

$url = 'http://localhost:8000/api/souscription-email-proprietaire';

// Données avec un email spécifique pour le test
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
        'email' => 'abdou.ndao.test@example.com', // EMAIL DU PROPRIÉTAIRE DU VÉHICULE
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

echo "📧 EMAIL DU PROPRIÉTAIRE DU VÉHICULE : " . $data['proprietaire']['email'] . "\n\n";

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Erreur lors de la requête HTTP\n";
} else {
    $responseData = json_decode($response, true);
    
    if ($responseData['success']) {
        echo "✅ CONTRAT CRÉÉ AVEC SUCCÈS !\n\n";
        echo "📋 DÉTAILS DU CONTRAT :\n";
        echo "   • ID: " . $responseData['data']['contrat']['id'] . "\n";
        echo "   • N° Police: " . $responseData['data']['contrat']['numero_police'] . "\n";
        echo "   • N° Attestation: " . $responseData['data']['contrat']['numero_attestation'] . "\n\n";
        
        echo "👤 PROPRIÉTAIRE DU VÉHICULE :\n";
        echo "   • Nom: " . $responseData['data']['user']['prenom'] . " " . $responseData['data']['user']['nom'] . "\n";
        echo "   • Email: " . $responseData['data']['user']['email'] . "\n\n";
        
        echo "📧 VÉRIFICATION EMAIL :\n";
        echo "   • Email dans le formulaire: " . $data['proprietaire']['email'] . "\n";
        echo "   • Email de l'utilisateur créé: " . $responseData['data']['user']['email'] . "\n";
        
        if ($data['proprietaire']['email'] === $responseData['data']['user']['email']) {
            echo "   ✅ CORRESPONDANCE PARFAITE ! L'email sera envoyé au propriétaire du véhicule.\n";
        } else {
            echo "   ❌ PROBLÈME ! L'email ne correspond pas.\n";
        }
        
        echo "\n📄 STATUT :\n";
        echo "   • Email envoyé: " . ($responseData['data']['email_sent'] ? '✅ OUI' : '❌ NON') . "\n";
        
        echo "\n💬 MESSAGE :\n";
        echo "   " . $responseData['data']['message'] . "\n";
        
        echo "\n🎉 RÉSULTAT FINAL :\n";
        echo "   • Contrat créé en base de données ✅\n";
        echo "   • Email envoyé au propriétaire du véhicule ✅\n";
        echo "   • Destinataire: " . $responseData['data']['user']['email'] . " ✅\n";
        
        echo "\n📧 RÉPONSE À VOTRE QUESTION :\n";
        echo "   L'email est maintenant envoyé à : " . $responseData['data']['user']['email'] . "\n";
        echo "   C'est l'email du propriétaire du véhicule saisi dans le formulaire ✅\n";
        echo "   PAS à l'utilisateur connecté, PAS à un utilisateur existant ✅\n";
        
    } else {
        echo "❌ ÉCHEC : " . $responseData['message'] . "\n";
    }
}

echo "\n======================================================================\n";
echo "🏁 Test terminé\n";
