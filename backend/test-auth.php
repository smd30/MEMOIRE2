<?php
/**
 * Script pour tester l'authentification et créer un utilisateur de test
 */

echo "=== Test d'authentification pour le module devis ===\n\n";

// Vérifier si le serveur backend fonctionne
echo "1. Test de connexion au serveur backend...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/devis/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 401) {
    echo "✅ Serveur backend fonctionne (401 = authentification requise)\n";
} else if ($httpCode === 200) {
    echo "⚠️ Serveur fonctionne mais pas d'authentification requise\n";
} else {
    echo "❌ Erreur de connexion au serveur (Code: $httpCode)\n";
    echo "   Assurez-vous que le serveur backend est démarré:\n";
    echo "   cd backend && php artisan serve --host=0.0.0.0 --port=8000\n";
    exit(1);
}

echo "\n";

// Créer un utilisateur de test si nécessaire
echo "2. Création d'un utilisateur de test...\n";
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'client@test.com';
$user = User::where('email', $email)->first();

if (!$user) {
    $user = User::create([
        'nom' => 'Test',
        'prenom' => 'Client',
        'email' => $email,
        'MotDePasse' => Hash::make('password123'),
        'role' => 'client',
        'statut' => 'actif'
    ]);
    echo "✅ Utilisateur créé avec l'ID: " . $user->id . "\n";
} else {
    echo "✅ Utilisateur existant trouvé avec l'ID: " . $user->id . "\n";
}

echo "   Email: $email\n";
echo "   Mot de passe: password123\n";

echo "\n";

// Générer un token d'authentification
echo "3. Génération du token d'authentification...\n";
$token = $user->createToken('devis-token')->plainTextToken;
echo "✅ Token généré:\n";
echo "   $token\n";

echo "\n";

// Tester l'API avec le token
echo "4. Test de l'API avec authentification...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/devis/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    echo "✅ API fonctionne avec authentification!\n";
    $data = json_decode($response, true);
    echo "   Compagnies: " . count($data['data']['compagnies']) . "\n";
    echo "   Périodes: " . count($data['data']['periodes']) . "\n";
} else {
    echo "❌ Erreur API (Code: $httpCode)\n";
    echo "   Réponse: $response\n";
}

echo "\n";

echo "=== Instructions pour le frontend ===\n";
echo "1. Connectez-vous avec:\n";
echo "   Email: $email\n";
echo "   Mot de passe: password123\n";
echo "\n";
echo "2. Ou utilisez ce token dans les requêtes API:\n";
echo "   Authorization: Bearer $token\n";
echo "\n";
echo "🎉 Module devis prêt à être testé!\n";
