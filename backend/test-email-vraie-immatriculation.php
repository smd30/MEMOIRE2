<?php

echo "🧪 TEST EMAIL AVEC VRAIE IMMATRICULATION\n";
echo "========================================\n\n";

// Test avec curl pour être plus robuste
$url = 'http://localhost:8000/api/souscription';

// Simuler les données que l'utilisateur saisit vraiment dans le formulaire
$data = [
    'vehicule' => [
        'marque_vehicule' => 'TOYOTA',
        'modele' => 'COROLLA',
        'immatriculation' => 'AB123CD', // VRAIE IMMATRICULATION que l'utilisateur saisit
        'puissance_fiscale' => 8,
        'date_mise_en_circulation' => '2018-03-20',
        'valeur_vehicule' => 8000000,
        'energie' => 'essence',
        'places' => 5,
        'numero_chassis' => 'JT1234567890123456',
        'categorie' => 'voiture_particuliere',
        'proprietaire_nom' => 'Ndiaye',
        'proprietaire_prenom' => 'Fatou',
        'proprietaire_adresse' => 'Dakar, Sénégal',
        'proprietaire_telephone' => '+221701234567',
        'proprietaire_email' => 'diopsokhnambaye15@gmail.com', // VOTRE EMAIL RÉEL
    ],
    'proprietaire' => [
        'nom' => 'Ndiaye',
        'prenom' => 'Fatou',
        'email' => 'diopsokhnambaye15@gmail.com', // VOTRE EMAIL RÉEL
        'telephone' => '+221701234567',
        'adresse' => 'Dakar, Sénégal',
    ],
    'compagnie_id' => '1',
    'periode_police' => '1',
    'date_debut' => '2025-09-05',
    'garanties_selectionnees' => ['RC', 'Vol', 'Incendie'],
    'devis_calcule' => [
        'prime_rc' => 3405,
        'garanties_optionnelles' => 200,
        'accessoires_police' => 2000,
        'prime_nette' => 5605,
        'taxes_tuca' => 1065,
        'prime_ttc' => 6670,
    ]
];

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

echo "🚀 Envoi de la requête de souscription...\n";
echo "📧 Email de destination: diopsokhnambaye15@gmail.com\n";
echo "🚗 Véhicule: TOYOTA COROLLA - AB123CD (VRAIE IMMATRICULATION)\n\n";

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
            echo "🎉 SUCCÈS : Souscription créée !\n";
            echo "📄 Message: " . $data['message'] . "\n";
            
            if (isset($data['data']['contrat'])) {
                echo "📄 Contrat ID: " . $data['data']['contrat']['id'] . "\n";
                echo "📄 Numéro attestation: " . $data['data']['contrat']['numero_attestation'] . "\n";
                echo "📄 Numéro police: " . $data['data']['contrat']['numero_police'] . "\n";
                echo "📄 Prime TTC: " . $data['data']['contrat']['prime_ttc'] . " FCFA\n";
            }
            
            echo "📄 PDF généré: " . ($data['data']['pdf_generated'] ? 'Oui' : 'Non') . "\n";
            echo "📧 Email envoyé: " . ($data['data']['email_sent'] ? 'Oui' : 'Non') . "\n";
            
            if ($data['data']['email_sent']) {
                echo "✅ L'email a été envoyé avec succès !\n";
                echo "📧 Vérifiez votre boîte email: diopsokhnambaye15@gmail.com\n";
                echo "📎 L'attestation PDF devrait être en pièce jointe\n";
                echo "🚗 IMMATRICULATION dans l'email: AB123CD (TOYOTA COROLLA)\n";
            } else {
                echo "❌ L'email n'a pas été envoyé\n";
                echo "🔍 Vérifiez les logs pour plus de détails\n";
            }
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
echo "📧 Vérifiez votre email: diopsokhnambaye15@gmail.com\n";
echo "🚗 L'immatriculation dans l'email doit être: AB123CD\n";
