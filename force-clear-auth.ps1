# Force le nettoyage de l'authentification et redirection
Write-Host "=== FORCE NETTOYAGE AUTHENTIFICATION ===" -ForegroundColor Red

Write-Host "`n🚨 PROBLÈME DÉTECTÉ : Token obsolète dans localStorage" -ForegroundColor Red
Write-Host "🔧 SOLUTION : Nettoyage forcé + nouvelle connexion" -ForegroundColor Yellow

# Créer une page de nettoyage forcé
$htmlContent = @"
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nettoyage Forcé - Assurance Auto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #ff4444 0%, #cc0000 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 {
            color: #cc0000;
            margin-bottom: 30px;
        }
        .status {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
        }
        button {
            background: linear-gradient(135deg, #cc0000 0%, #ff4444 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin: 10px;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #cc0000;
        }
        .credential-item {
            margin: 5px 0;
            font-family: monospace;
        }
        .steps {
            text-align: left;
            margin: 20px 0;
        }
        .step {
            margin: 10px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚨 NETTOYAGE FORCÉ AUTHENTIFICATION</h1>
        
        <div id="status" class="status warning">
            🔄 Nettoyage en cours...
        </div>
        
        <div class="steps">
            <div class="step">
                <strong>Étape 1:</strong> Nettoyage automatique de l'authentification
            </div>
            <div class="step">
                <strong>Étape 2:</strong> Redirection vers la page de connexion
            </div>
            <div class="step">
                <strong>Étape 3:</strong> Connexion avec les identifiants admin
            </div>
        </div>
        
        <button onclick="forceClearAndRedirect()">🚀 NETTOYER ET ALLER À LA CONNEXION</button>
        <button onclick="clearAuth()">🧹 NETTOYER SEULEMENT</button>
        <button onclick="goToMain()">🏠 ALLER À L'ACCUEIL</button>
        
        <div class="credentials">
            <h3>📋 Identifiants Admin :</h3>
            <div class="credential-item"><strong>Email:</strong> admin@example.com</div>
            <div class="credential-item"><strong>Mot de passe:</strong> password</div>
        </div>
    </div>

    <script>
        // Nettoyer automatiquement au chargement
        clearAuth();
        
        function clearAuth() {
            const status = document.getElementById('status');
            
            // Nettoyer localStorage
            localStorage.clear();
            sessionStorage.clear();
            
            // Nettoyer spécifiquement les clés d'auth
            localStorage.removeItem('auth_token');
            localStorage.removeItem('current_user');
            sessionStorage.removeItem('auth_token');
            sessionStorage.removeItem('current_user');
            
            // Nettoyer cookies
            document.cookie.split(";").forEach(function(c) { 
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 
            });
            
            status.innerHTML = '✅ Authentification nettoyée avec succès !';
            status.className = 'status';
            
            console.log('Authentification nettoyée');
        }
        
        function forceClearAndRedirect() {
            clearAuth();
            setTimeout(() => {
                window.location.href = '/login';
            }, 1000);
        }
        
        function goToMain() {
            window.location.href = '/';
        }
        
        // Vérifier l'état de l'authentification
        function checkAuthStatus() {
            const token = localStorage.getItem('auth_token');
            const user = localStorage.getItem('current_user');
            
            if (token || user) {
                document.getElementById('status').innerHTML = '⚠️ Authentification détectée - Cliquez "NETTOYER"';
                document.getElementById('status').className = 'status error';
            } else {
                document.getElementById('status').innerHTML = '✅ Aucune authentification détectée';
                document.getElementById('status').className = 'status';
            }
        }
        
        // Vérifier toutes les 2 secondes
        setInterval(checkAuthStatus, 2000);
        checkAuthStatus();
    </script>
</body>
</html>
"@

# Écrire le fichier HTML
$htmlContent | Out-File -FilePath "force-clear.html" -Encoding UTF8

Write-Host "`n📄 Page de nettoyage forcé créée : force-clear.html" -ForegroundColor Green

# Ouvrir la page de nettoyage forcé
Write-Host "`n🌐 Ouverture de la page de nettoyage forcé..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "`n=== INSTRUCTIONS URGENTES ===" -ForegroundColor Red
Write-Host "1. La page de nettoyage forcé s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Cliquez sur '🚀 NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "3. Vous serez redirigé vers la page de connexion" -ForegroundColor White
Write-Host "4. Entrez : admin@example.com / password" -ForegroundColor White
Write-Host "5. Vous serez redirigé vers l'interface admin fonctionnelle" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n🚨 URGENT : Utilisez cette page pour résoudre le problème ! 🚨" -ForegroundColor Red








