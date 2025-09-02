<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Http;

echo "=== Test d'authentification ===\n";

// Test de connexion
echo "1. Test de connexion...\n";
$loginResponse = Http::post('http://localhost:8000/api/auth/login', [
    'email' => 'gestionnaire@example.com',
    'password' => 'password'
]);

echo "Status: " . $loginResponse->status() . "\n";
echo "Response: " . $loginResponse->body() . "\n\n";

if ($loginResponse->successful()) {
    $data = $loginResponse->json();
    $token = $data['data']['token'] ?? null;
    
    if ($token) {
        echo "Token obtenu: " . substr($token, 0, 20) . "...\n\n";
        
        // Test de déconnexion
        echo "2. Test de déconnexion...\n";
        $logoutResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('http://localhost:8000/api/auth/logout');
        
        echo "Status: " . $logoutResponse->status() . "\n";
        echo "Response: " . $logoutResponse->body() . "\n\n";
        
        // Test de déconnexion sans token (devrait échouer)
        echo "3. Test de déconnexion sans token (devrait échouer)...\n";
        $logoutNoTokenResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('http://localhost:8000/api/auth/logout');
        
        echo "Status: " . $logoutNoTokenResponse->status() . "\n";
        echo "Response: " . $logoutNoTokenResponse->body() . "\n\n";
        
    } else {
        echo "Erreur: Token non trouvé dans la réponse\n";
    }
} else {
    echo "Erreur de connexion\n";
}

echo "=== Fin du test ===\n";
