# Test d'authentification
Write-Host "=== Test d'authentification ===" -ForegroundColor Green

# 1. Test de connexion
Write-Host "1. Test de connexion..." -ForegroundColor Yellow
$loginResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/login" -Method POST -Headers @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body '{"email":"gestionnaire@example.com","password":"password"}'

Write-Host "Status: $($loginResponse.StatusCode)" -ForegroundColor Cyan
Write-Host "Response: $($loginResponse.Content)" -ForegroundColor White
Write-Host ""

if ($loginResponse.StatusCode -eq 200) {
    $data = $loginResponse.Content | ConvertFrom-Json
    $token = $data.data.token
    
    if ($token) {
        Write-Host "Token obtenu: $($token.Substring(0, 20))..." -ForegroundColor Green
        Write-Host ""
        
        # 2. Test de déconnexion avec token
        Write-Host "2. Test de déconnexion avec token..." -ForegroundColor Yellow
        $logoutResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/logout" -Method POST -Headers @{
            "Authorization" = "Bearer $token"
            "Accept" = "application/json"
            "Content-Type" = "application/json"
        }
        
        Write-Host "Status: $($logoutResponse.StatusCode)" -ForegroundColor Cyan
        Write-Host "Response: $($logoutResponse.Content)" -ForegroundColor White
        Write-Host ""
        
        # 3. Test de déconnexion sans token (devrait échouer)
        Write-Host "3. Test de déconnexion sans token (devrait échouer)..." -ForegroundColor Yellow
        try {
            $logoutNoTokenResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/logout" -Method POST -Headers @{
                "Accept" = "application/json"
                "Content-Type" = "application/json"
            }
            Write-Host "Status: $($logoutNoTokenResponse.StatusCode)" -ForegroundColor Cyan
            Write-Host "Response: $($logoutNoTokenResponse.Content)" -ForegroundColor White
        } catch {
            Write-Host "Erreur attendue: $($_.Exception.Message)" -ForegroundColor Red
        }
        Write-Host ""
        
    } else {
        Write-Host "Erreur: Token non trouvé dans la réponse" -ForegroundColor Red
    }
} else {
    Write-Host "Erreur de connexion" -ForegroundColor Red
}

Write-Host "=== Fin du test ===" -ForegroundColor Green
