# Script de vérification rapide
Write-Host "=== Vérification des Serveurs ===" -ForegroundColor Green
Write-Host ""

# Vérifier le backend
Write-Host "🔍 Vérification du backend..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ Backend Laravel fonctionne (Port 8000)" -ForegroundColor Green
} catch {
    Write-Host "❌ Backend Laravel non accessible" -ForegroundColor Red
    Write-Host "   Démarrez avec: cd backend && php artisan serve --host=0.0.0.0 --port=8000" -ForegroundColor Cyan
}

# Vérifier le frontend
Write-Host "🔍 Vérification du frontend..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:4200" -TimeoutSec 5 -ErrorAction Stop
    Write-Host "✅ Frontend Angular fonctionne (Port 4200)" -ForegroundColor Green
} catch {
    Write-Host "❌ Frontend Angular non accessible" -ForegroundColor Red
    Write-Host "   Démarrez avec: cd frontend && ng serve" -ForegroundColor Cyan
}

Write-Host ""
Write-Host "=== Instructions de Test ===" -ForegroundColor Green
Write-Host ""
Write-Host "1. Ouvrez http://localhost:4200" -ForegroundColor White
Write-Host "2. Cliquez sur 'Se Connecter'" -ForegroundColor White
Write-Host "3. Utilisez les identifiants:" -ForegroundColor White
Write-Host "   • Email: client@test.com" -ForegroundColor Cyan
Write-Host "   • Mot de passe: password123" -ForegroundColor Cyan
Write-Host "4. Cliquez sur 'DEVIS' dans le menu" -ForegroundColor White
Write-Host "5. Cliquez sur 'Nouveau devis'" -ForegroundColor White
Write-Host "6. Testez le formulaire multi-étapes" -ForegroundColor White
Write-Host ""
Write-Host "🎉 Le module devis est maintenant prêt à être testé !" -ForegroundColor Green
