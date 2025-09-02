# Test de connexion admin
Write-Host "=== TEST DE CONNEXION ADMIN ===" -ForegroundColor Green

Write-Host "`nTest de connexion avec admin@example.com..." -ForegroundColor Yellow

$loginData = @{
    email = "admin@example.com"
    password = "password"
} | ConvertTo-Json

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8000/api/auth/login" -Method POST -Body $loginData -ContentType "application/json"
    
    Write-Host "✅ Connexion réussie !" -ForegroundColor Green
    Write-Host "Utilisateur: $($response.data.user.nom) $($response.data.user.prenom)" -ForegroundColor Cyan
    Write-Host "Rôle: $($response.data.user.role)" -ForegroundColor Cyan
    Write-Host "Token: $($response.data.token.Substring(0,20))..." -ForegroundColor Gray
    
    if ($response.data.user.role -eq "admin") {
        Write-Host "🎉 Utilisateur admin connecté avec succès !" -ForegroundColor Green
    } else {
        Write-Host "⚠️  L'utilisateur connecté n'est pas admin (rôle: $($response.data.user.role))" -ForegroundColor Yellow
    }
    
} catch {
    Write-Host "❌ Erreur de connexion:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
}

Write-Host "`n=== INSTRUCTIONS ===" -ForegroundColor Yellow
Write-Host "1. Ouvrez http://localhost:4200/src/app/pages/clear-auth.html" -ForegroundColor White
Write-Host "2. Connectez-vous avec admin@example.com / password" -ForegroundColor White
Write-Host "3. Vous serez redirigé vers l'interface admin" -ForegroundColor White
