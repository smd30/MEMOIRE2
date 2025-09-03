# Test final de l'interface admin
Write-Host "🧪 Test final de l'interface admin..." -ForegroundColor Cyan

# Attendre que les serveurs soient prêts
Write-Host "⏳ Attente des serveurs (10 secondes)..." -ForegroundColor Yellow
Start-Sleep -Seconds 10

# Vérifier que les serveurs répondent
Write-Host "🔍 Vérification des serveurs..." -ForegroundColor Blue

try {
    $laravelResponse = Invoke-WebRequest -Uri "http://localhost:8000" -Method GET -TimeoutSec 5
    Write-Host "✅ Serveur Laravel: OK" -ForegroundColor Green
} catch {
    Write-Host "❌ Serveur Laravel: Non accessible" -ForegroundColor Red
}

try {
    $angularResponse = Invoke-WebRequest -Uri "http://localhost:4200" -Method GET -TimeoutSec 5
    Write-Host "✅ Serveur Angular: OK" -ForegroundColor Green
} catch {
    Write-Host "❌ Serveur Angular: Non accessible" -ForegroundColor Red
}

# Ouvrir la page de nettoyage d'authentification
Write-Host "🌐 Ouverture de la page de nettoyage..." -ForegroundColor Green
Start-Process "http://localhost:4200/force-clear.html"

Write-Host "✅ Interface admin prête !" -ForegroundColor Green
Write-Host "📋 Instructions :" -ForegroundColor White
Write-Host "1. Cliquez sur 'NETTOYER ET ALLER À LA CONNEXION'" -ForegroundColor White
Write-Host "2. Connectez-vous avec admin@example.com / password" -ForegroundColor White
Write-Host "3. Vous devriez être redirigé vers l'interface admin" -ForegroundColor White
Write-Host "4. Testez la gestion des utilisateurs (voir, ajouter, bloquer/débloquer)" -ForegroundColor White
Write-Host "5. Vérifiez que seuls 'gestionnaire' et 'admin' peuvent être créés" -ForegroundColor White
