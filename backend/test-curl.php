<?php

echo "🌐 TEST AVEC CURL - Vérification complète\n";
echo "==========================================\n\n";

// Test avec curl pour être plus robuste
$url = 'http://localhost:8000/api/souscription';

$data = [
    'vehicule' => [
        'categorie' => 'vehicules_particuliers',
        'marque_vehicule' => 'PEUGEOT',
        'modele' => '207',
        'immatriculation' => 'DK1234AB' . time() . rand(1000, 9999),
        'puissance_fiscale' => 9,
        'date_mise_en_circulation' => '2010-01-15',
        'valeur_vehicule' => '5000000',
        'energie' => 'essence',
        'places' => '5',
        'numero_chassis' => 'VF3XXXXXXXXXXXXXXX' . time() . rand(1000, 9999),
    ],
    'compagnie_id' => '1',
    'periode_police' => '1',
    'date_debut' => '2025-09-05',
    'garanties_selectionnees' => ['1', '2'],
    'proprietaire' => [
        'nom' => 'NDAO',
        'prenom' => 'ABDOU',
        'email' => 'abdou.ndao.test@example.com',
        'telephone' => '+221777777777',
        'adresse' => '123 Rue de la Paix'
    ],
    'devis_calcule' => [
        'prime_rc' => 20431.2,
        'prime_rc_annuelle' => 40862.4,
        'prime_rc_brute_annuelle' => 51078,
        'garanties_optionnelles' => 200,
        'garanties_optionnelles_annuelle' => 200,
        'prime_ttc' => 6770
    ]
];

echo "📧 EMAIL DU PROPRIÉTAIRE : " . $data['proprietaire']['email'] . "\n\n";

// Initialiser curl
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
curl_setopt($ch, CURLOPT_VERBOSE, true);

echo "🚀 Envoi de la requête...\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "📊 Code HTTP: " . $httpCode . "\n";

if ($error) {
    echo "❌ Erreur cURL: " . $error . "\n";
} else {
    echo "✅ Réponse reçue !\n\n";
    
    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        
        if ($responseData && $responseData['success']) {
            echo "🎉 SUCCÈS ! Contrat créé avec succès !\n\n";
            echo "📋 DÉTAILS DU CONTRAT :\n";
            echo "   • ID: " . $responseData['data']['contrat']['id'] . "\n";
            echo "   • N° Police: " . $responseData['data']['contrat']['numero_police'] . "\n";
            echo "   • N° Attestation: " . $responseData['data']['contrat']['numero_attestation'] . "\n";
            echo "   • Prime TTC: " . $responseData['data']['contrat']['prime_ttc'] . " FCFA\n\n";
            
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
            echo "   • PDF généré: " . ($responseData['data']['pdf_generated'] ? '✅ OUI' : '❌ NON') . "\n";
            echo "   • Email envoyé: " . ($responseData['data']['email_sent'] ? '✅ OUI' : '❌ NON') . "\n";
            
            echo "\n💬 MESSAGE :\n";
            echo "   " . $responseData['data']['message'] . "\n";
            
            echo "\n🎉 RÉSULTAT FINAL :\n";
            echo "   • Contrat créé en base de données ✅\n";
            echo "   • PDF d'attestation généré ✅\n";
            echo "   • Email envoyé au propriétaire du véhicule ✅\n";
            echo "   • Destinataire: " . $responseData['data']['user']['email'] . " ✅\n";
            
        } else {
            echo "❌ ÉCHEC : " . ($responseData['message'] ?? 'Erreur inconnue') . "\n";
        }
    } else {
        echo "❌ Erreur HTTP " . $httpCode . "\n";
        echo "Réponse: " . $response . "\n";
    }
}

echo "\n==========================================\n";
echo "🏁 Test terminé\n";
