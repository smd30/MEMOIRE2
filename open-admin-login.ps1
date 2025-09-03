# Ouvrir la page de connexion admin
Write-Host "=== OUVERTURE DE LA PAGE DE CONNEXION ADMIN ===" -ForegroundColor Green

# Vérifier que les serveurs sont démarrés
Write-Host "`nVérification des serveurs..." -ForegroundColor Yellow

try {
    $backendResponse = Invoke-WebRequest -Uri "http://localhost:8000" -UseBasicParsing -TimeoutSec 5
    Write-Host "✅ Serveur backend Laravel: Fonctionnel" -ForegroundColor Green
} catch {
    Write-Host "❌ Serveur backend Laravel: Non accessible" -ForegroundColor Red
    Write-Host "Démarrez d'abord le serveur backend avec: cd backend; php artisan serve" -ForegroundColor Yellow
    exit 1
}

try {
    $frontendResponse = Invoke-WebRequest -Uri "http://localhost:4200" -UseBasicParsing -TimeoutSec 5
    Write-Host "✅ Serveur frontend Angular: Fonctionnel" -ForegroundColor Green
} catch {
    Write-Host "❌ Serveur frontend Angular: Non accessible" -ForegroundColor Red
    Write-Host "Démarrez d'abord le serveur frontend avec: cd frontend; ng serve" -ForegroundColor Yellow
    exit 1
}

# Ouvrir la page de connexion admin
Write-Host "`nOuverture de la page de connexion admin..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/src/app/pages/clear-auth.html"

Write-Host "`n=== INSTRUCTIONS ===" -ForegroundColor Yellow
Write-Host "1. La page de connexion s'ouvre automatiquement" -ForegroundColor White
Write-Host "2. Les identifiants admin sont pré-remplis" -ForegroundColor White
Write-Host "3. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "4. Vous serez redirigé vers l'interface admin" -ForegroundColor White

Write-Host "`n=== IDENTIFIANTS ADMIN ===" -ForegroundColor Cyan
Write-Host "Email: admin@example.com" -ForegroundColor White
Write-Host "Mot de passe: password" -ForegroundColor White

Write-Host "`n🎉 Prêt à tester l'interface admin ! 🎉" -ForegroundColor Green

