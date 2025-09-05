<?php

echo "🎉 TEST FINAL - SOUSCRIPTION AVEC PDF ET EMAIL 🎉\n";
echo "================================================\n\n";

$url = 'http://localhost:8000/api/souscription-with-pdf';

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

echo "🚀 Test de souscription complète...\n";
echo "URL: $url\n\n";

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Erreur lors de la requête HTTP\n";
    $error = error_get_last();
    print_r($error);
} else {
    $responseData = json_decode($response, true);
    
    if ($responseData['success']) {
        echo "✅ SUCCÈS COMPLET !\n\n";
        echo "📋 DÉTAILS DU CONTRAT :\n";
        echo "   • ID: " . $responseData['data']['contrat']['id'] . "\n";
        echo "   • N° Police: " . $responseData['data']['contrat']['numero_police'] . "\n";
        echo "   • N° Attestation: " . $responseData['data']['contrat']['numero_attestation'] . "\n";
        echo "   • Période: " . $responseData['data']['contrat']['date_debut'] . " au " . $responseData['data']['contrat']['date_fin'] . "\n";
        echo "   • Prime TTC: " . $responseData['data']['contrat']['prime_ttc'] . " FCFA\n";
        echo "   • Garanties: " . implode(', ', $responseData['data']['contrat']['garanties']) . "\n\n";
        
        echo "👤 INFORMATIONS CLIENT :\n";
        echo "   • Nom: " . $responseData['data']['user']['prenom'] . " " . $responseData['data']['user']['nom'] . "\n";
        echo "   • Email: " . $responseData['data']['user']['email'] . "\n\n";
        
        echo "🚗 INFORMATIONS VÉHICULE :\n";
        echo "   • Marque/Modèle: " . $responseData['data']['vehicule']['marque'] . " " . $responseData['data']['vehicule']['modele'] . "\n";
        echo "   • Immatriculation: " . $responseData['data']['vehicule']['immatriculation'] . "\n\n";
        
        echo "📄 STATUT GÉNÉRATION :\n";
        echo "   • Attestation générée: " . ($responseData['data']['attestation_generated'] ? '✅ OUI' : '❌ NON') . "\n";
        echo "   • PDF généré: " . ($responseData['data']['pdf_generated'] ? '✅ OUI' : '❌ NON') . "\n";
        echo "   • Email envoyé: " . ($responseData['data']['email_sent'] ? '✅ OUI' : '❌ NON') . "\n\n";
        
        echo "💬 MESSAGE :\n";
        echo "   " . $responseData['data']['message'] . "\n\n";
        
        if ($responseData['data']['pdf_generated']) {
            echo "🎉 LE SYSTÈME FONCTIONNE PARFAITEMENT !\n";
            echo "   • Contrat créé en base de données ✅\n";
            echo "   • PDF d'attestation généré ✅\n";
            echo "   • Prêt pour l'envoi par email ✅\n\n";
            
            echo "📧 POUR L'ENVOI D'EMAIL :\n";
            echo "   Configurez votre serveur SMTP dans le fichier .env :\n";
            echo "   MAIL_HOST=votre-serveur-smtp.com\n";
            echo "   MAIL_PORT=587\n";
            echo "   MAIL_USERNAME=votre-email@domain.com\n";
            echo "   MAIL_PASSWORD=votre-mot-de-passe\n";
            echo "   MAIL_ENCRYPTION=tls\n\n";
        }
        
    } else {
        echo "❌ ÉCHEC : " . $responseData['message'] . "\n";
    }
}

echo "================================================\n";
echo "🏁 Test terminé\n";
