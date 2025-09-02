# Test complet d'authentification
Write-Host "=== Test complet d'authentification ===" -ForegroundColor Green

# 1. Test de connexion
Write-Host "1. Test de connexion..." -ForegroundColor Yellow
$loginResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/login" -Method POST -Headers @{
    "Content-Type" = "application/json"
    "Accept" = "application/json"
} -Body '{"email":"gestionnaire@example.com","password":"password"}'

Write-Host "Status: $($loginResponse.StatusCode)" -ForegroundColor Cyan
$loginData = $loginResponse.Content | ConvertFrom-Json
Write-Host "Success: $($loginData.success)" -ForegroundColor White
Write-Host "Message: $($loginData.message)" -ForegroundColor White

if ($loginResponse.StatusCode -eq 200 -and $loginData.success) {
    $token = $loginData.data.token
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
    $logoutData = $logoutResponse.Content | ConvertFrom-Json
    Write-Host "Success: $($logoutData.success)" -ForegroundColor White
    Write-Host "Message: $($logoutData.message)" -ForegroundColor White
    Write-Host ""
    
    # 3. Test de déconnexion avec token invalide
    Write-Host "3. Test de déconnexion avec token invalide..." -ForegroundColor Yellow
    try {
        $logoutInvalidResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/logout" -Method POST -Headers @{
            "Authorization" = "Bearer invalid_token_123"
            "Accept" = "application/json"
            "Content-Type" = "application/json"
        }
        Write-Host "Status: $($logoutInvalidResponse.StatusCode)" -ForegroundColor Cyan
        Write-Host "Response: $($logoutInvalidResponse.Content)" -ForegroundColor White
    } catch {
        Write-Host "Erreur attendue: $($_.Exception.Message)" -ForegroundColor Red
    }
    Write-Host ""
    
    # 4. Test de déconnexion sans token
    Write-Host "4. Test de déconnexion sans token..." -ForegroundColor Yellow
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
    Write-Host "Erreur de connexion" -ForegroundColor Red
}

Write-Host "=== Fin du test ===" -ForegroundColor Green
