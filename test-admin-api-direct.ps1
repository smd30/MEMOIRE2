# Test direct de l'API admin
Write-Host "🧪 Test direct de l'API admin..." -ForegroundColor Cyan

# Étape 1: Connexion pour obtenir un token
Write-Host "🔐 Connexion pour obtenir un token..." -ForegroundColor Blue

$loginData = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/login" -Method POST -Body $loginData -ContentType "application/json"
    $loginResult = $loginResponse.Content | ConvertFrom-Json
    
    if ($loginResult.success) {
        $token = $loginResult.data.token
        Write-Host "✅ Token obtenu: $($token.Substring(0, 20))..." -ForegroundColor Green
        
        # Étape 2: Test de l'API admin/users
        Write-Host "👥 Test de l'API admin/users..." -ForegroundColor Blue
        
        $headers = @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
            "Content-Type" = "application/json"
        }
        
        $usersResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/admin/users" -Method GET -Headers $headers
        $usersResult = $usersResponse.Content | ConvertFrom-Json
        
        if ($usersResult.success) {
            Write-Host "✅ API admin/users fonctionne !" -ForegroundColor Green
            Write-Host "📊 Nombre d'utilisateurs: $($usersResult.data.Count)" -ForegroundColor White
        } else {
            Write-Host "❌ Erreur API admin/users: $($usersResult.message)" -ForegroundColor Red
        }
        
    } else {
        Write-Host "❌ Erreur de connexion: $($loginResult.message)" -ForegroundColor Red
    }
    
} catch {
    Write-Host "❌ Erreur lors du test: $($_.Exception.Message)" -ForegroundColor Red
}








