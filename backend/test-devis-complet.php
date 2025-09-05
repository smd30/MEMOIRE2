<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Compagnie;
use App\Models\Garantie;

echo "=== Test Complet de l'API Devis ===\n\n";

// 1. Vérifier l'utilisateur de test
echo "1. Vérification de l'utilisateur de test...\n";
$user = User::where('email', 'client@test.com')->first();
if (!$user) {
    echo "❌ Utilisateur de test non trouvé. Création...\n";
    $user = User::create([
        'nom' => 'Test',
        'prenom' => 'Client',
        'email' => 'client@test.com',
        'MotDePasse' => bcrypt('password123'),
        'role' => 'client',
        'statut' => 'actif'
    ]);
    echo "✅ Utilisateur créé avec l'ID: " . $user->id . "\n";
} else {
    echo "✅ Utilisateur trouvé avec l'ID: " . $user->id . "\n";
}

// 2. Créer un token d'authentification
echo "\n2. Création du token d'authentification...\n";
$token = $user->createToken('test-token')->plainTextToken;
echo "✅ Token créé: " . substr($token, 0, 20) . "...\n";

// 3. Vérifier les compagnies
echo "\n3. Vérification des compagnies...\n";
$compagnies = Compagnie::where('is_active', true)->get();
if ($compagnies->count() > 0) {
    echo "✅ " . $compagnies->count() . " compagnies trouvées:\n";
    foreach ($compagnies as $compagnie) {
        echo "   • " . $compagnie->nom . " (ID: " . $compagnie->id . ")\n";
    }
} else {
    echo "❌ Aucune compagnie trouvée. Exécution du seeder...\n";
    // Exécuter le seeder
    $seeder = new \Database\Seeders\DevisSeeder();
    $seeder->run();
    echo "✅ Seeder exécuté.\n";
}

// 4. Vérifier les garanties
echo "\n4. Vérification des garanties...\n";
$garanties = Garantie::where('statut', 'active')->get();
if ($garanties->count() > 0) {
    echo "✅ " . $garanties->count() . " garanties trouvées:\n";
    foreach ($garanties as $garantie) {
        echo "   • " . $garantie->display_name . " (Obligatoire: " . ($garantie->obligatoire ? 'Oui' : 'Non') . ")\n";
    }
} else {
    echo "❌ Aucune garantie trouvée.\n";
}

// 5. Test de l'API avec curl
echo "\n5. Test de l'API /api/devis/create...\n";
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
if ($httpCode === 200) {
    echo "✅ API fonctionne correctement!\n";
    $data = json_decode($response, true);
    if (isset($data['data']['compagnies'])) {
        echo "   • " . count($data['data']['compagnies']) . " compagnies disponibles\n";
    }
    if (isset($data['data']['periodes'])) {
        echo "   • " . count($data['data']['periodes']) . " périodes disponibles\n";
    }
} else {
    echo "❌ Erreur API: " . $response . "\n";
}

// 6. Test de l'API garanties
echo "\n6. Test de l'API /api/devis/garanties/{compagnieId}...\n";
$compagnieId = $compagnies->first()->id ?? 5;
$url = "http://localhost:8000/api/devis/garanties/{$compagnieId}";
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
if ($httpCode === 200) {
    echo "✅ API garanties fonctionne correctement!\n";
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        echo "   • " . count($data['data']) . " garanties pour la compagnie ID: {$compagnieId}\n";
    }
} else {
    echo "❌ Erreur API garanties: " . $response . "\n";
}

echo "\n=== Test Terminé ===\n";
echo "\n🎉 Si tous les tests sont verts, l'API est prête!\n";
echo "📋 Identifiants de connexion:\n";
echo "   • Email: client@test.com\n";
echo "   • Mot de passe: password123\n";
echo "\n🔗 Accédez à http://localhost:4200 pour tester le frontend\n";



