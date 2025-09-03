# Test avec le token spécifique trouvé dans les logs
Write-Host "=== Test avec le token spécifique ===" -ForegroundColor Green

$specificToken = "4|OmEZBjWXmolJB3suPDbCHnIoUoS9u9YGS6tBljxCccd0624b"

Write-Host "Token à tester: $specificToken" -ForegroundColor Yellow
Write-Host ""

# Test de déconnexion avec ce token spécifique
Write-Host "Test de déconnexion avec le token spécifique..." -ForegroundColor Yellow
try {
    $logoutResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/auth/logout" -Method POST -Headers @{
        "Authorization" = "Bearer $specificToken"
        "Accept" = "application/json"
        "Content-Type" = "application/json"
    }
    
    Write-Host "Status: $($logoutResponse.StatusCode)" -ForegroundColor Cyan
    Write-Host "Response: $($logoutResponse.Content)" -ForegroundColor White
} catch {
    Write-Host "Erreur: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "=== Test terminé ===" -ForegroundColor Green

