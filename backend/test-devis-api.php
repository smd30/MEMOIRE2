<?php

echo "=== TEST API DEVIS ===\n\n";

// Test 1: Obtenir les catégories (route publique)
echo "1. Test - Obtenir les catégories\n";
$url = 'http://localhost:8000/api/devis/categories';
$result = file_get_contents($url);

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "✅ Catégories récupérées:\n";
        foreach ($response['data'] as $code => $nom) {
            echo "   {$code}: {$nom}\n";
        }
    } else {
        echo "❌ Erreur API: " . ($response['message'] ?? 'Erreur inconnue') . "\n";
    }
}

echo "\n";

// Test 2: Obtenir les garanties (route publique)
echo "2. Test - Obtenir les garanties\n";
$url = 'http://localhost:8000/api/devis/garanties';
$result = file_get_contents($url);

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "✅ Garanties récupérées:\n";
        foreach ($response['data'] as $code => $nom) {
            echo "   {$code}: {$nom}\n";
        }
    } else {
        echo "❌ Erreur API: " . ($response['message'] ?? 'Erreur inconnue') . "\n";
    }
}

echo "\n";

// Test 3: Calculer un devis (route publique)
echo "3. Test - Calculer un devis\n";
$url = 'http://localhost:8000/api/devis/calculer';
$data = [
    'categorie' => 'C1',
    'caracteristiques' => [
        'puissance' => 7
    ],
    'garanties' => [
        'vol' => true,
        'incendie' => true,
        'bris_glace' => true,
        'defense_recours' => true,
        'individuelle_conducteur' => false,
        'dommages_tous_accidents' => false
    ]
];

$options = [
    'http' => [
        'header' => "Content-type: application/json\r\nAccept: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data)
    ]
];

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "✅ Devis calculé avec succès:\n";
        $devis = $response['data'];
        echo "   Prime RC: " . number_format($devis['prime_rc'], 0, ',', ' ') . " FCFA\n";
        echo "   Garanties: " . number_format($devis['garanties_optionnelles'], 0, ',', ' ') . " FCFA\n";
        echo "   Accessoires: " . number_format($devis['accessoires_police'], 0, ',', ' ') . " FCFA\n";
        echo "   Prime nette: " . number_format($devis['prime_nette'], 0, ',', ' ') . " FCFA\n";
        echo "   Taxes (19%): " . number_format($devis['taxes_tuca'], 0, ',', ' ') . " FCFA\n";
        echo "   Prime TTC: " . number_format($devis['prime_ttc'], 0, ',', ' ') . " FCFA\n";
    } else {
        echo "❌ Erreur API: " . ($response['message'] ?? 'Erreur inconnue') . "\n";
        if (isset($response['errors'])) {
            print_r($response['errors']);
        }
    }
}

echo "\n";

// Test 4: Exemple de devis (route publique)
echo "4. Test - Exemple de devis\n";
$url = 'http://localhost:8000/api/devis/exemple';
$result = file_get_contents($url);

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "✅ Exemple de devis récupéré:\n";
        $devis = $response['devis'];
        echo "   Prime RC: " . number_format($devis['prime_rc'], 0, ',', ' ') . " FCFA\n";
        echo "   Prime TTC: " . number_format($devis['prime_ttc'], 0, ',', ' ') . " FCFA\n";
    } else {
        echo "❌ Erreur API: " . ($response['message'] ?? 'Erreur inconnue') . "\n";
    }
}

echo "\n";

// Test 5: Lister les devis (route temporairement publique)
echo "5. Test - Lister les devis\n";
$url = 'http://localhost:8000/api/devis';
$result = file_get_contents($url);

if ($result === FALSE) {
    echo "❌ Erreur lors de l'appel API\n";
} else {
    $response = json_decode($result, true);
    if (isset($response['success']) && $response['success']) {
        echo "✅ Liste des devis récupérée:\n";
        $devis = $response['data'];
        if (empty($devis)) {
            echo "   Aucun devis trouvé (normal en mode test)\n";
        } else {
            echo "   Nombre de devis: " . count($devis) . "\n";
            foreach ($devis as $index => $devisItem) {
                echo "   Devis #" . ($index + 1) . ": " . number_format($devisItem['montant'], 0, ',', ' ') . " FCFA\n";
            }
        }
    } else {
        echo "❌ Erreur API: " . ($response['message'] ?? 'Erreur inconnue') . "\n";
    }
}

echo "\n=== FIN DES TESTS API ===\n";
