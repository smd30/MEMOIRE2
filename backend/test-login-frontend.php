<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== Test de Connexion Frontend ===\n\n";

// 1. Vérifier l'utilisateur
echo "1. Vérification de l'utilisateur...\n";
$user = User::where('email', 'client@test.com')->first();
if (!$user) {
    echo "❌ Utilisateur non trouvé. Création...\n";
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

// 2. Test de connexion via API
echo "\n2. Test de connexion via API...\n";
$loginData = [
    'email' => 'client@test.com',
    'password' => 'password123'
];

$url = 'http://localhost:8000/api/auth/login';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code de réponse HTTP: " . $httpCode . "\n";
if ($httpCode === 200) {
    echo "✅ Connexion réussie!\n";
    $data = json_decode($response, true);
    if (isset($data['data']['token'])) {
        $token = $data['data']['token'];
        echo "✅ Token reçu: " . substr($token, 0, 20) . "...\n";
        
        // 3. Test de l'API devis avec le token
        echo "\n3. Test de l'API devis avec le token...\n";
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
        
        echo "Code de réponse API devis: " . $httpCode . "\n";
        if ($httpCode === 200) {
            echo "✅ API devis fonctionne avec le token!\n";
            $data = json_decode($response, true);
            if (isset($data['data']['compagnies'])) {
                echo "   • " . count($data['data']['compagnies']) . " compagnies disponibles\n";
            }
        } else {
            echo "❌ Erreur API devis: " . $response . "\n";
        }
    }
} else {
    echo "❌ Erreur de connexion: " . $response . "\n";
}

echo "\n=== Instructions pour le Frontend ===\n";
echo "1. Ouvrez http://localhost:4200\n";
echo "2. Cliquez sur 'Se Connecter'\n";
echo "3. Utilisez ces identifiants:\n";
echo "   • Email: client@test.com\n";
echo "   • Mot de passe: password123\n";
echo "4. Vérifiez que la connexion fonctionne\n";
echo "5. Puis testez le module devis\n";
echo "\n🎉 Si la connexion fonctionne, le module devis devrait marcher!\n";





