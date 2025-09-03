# Script pour ouvrir tous les fichiers de test
Write-Host "=== Ouverture des Fichiers de Test ===" -ForegroundColor Green
Write-Host ""

Write-Host "1. Ouverture du test simple..." -ForegroundColor Yellow
Start-Process "test-inscription-simple.html"

Write-Host "2. Ouverture du test complet..." -ForegroundColor Yellow
Start-Process "test-inscription.html"

Write-Host "3. Ouverture de l'application Angular..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/register"

Write-Host ""
Write-Host "✅ Tous les fichiers de test sont ouverts !" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Fichiers ouverts:" -ForegroundColor Yellow
Write-Host "   • test-inscription-simple.html - Test direct de l'API" -ForegroundColor Cyan
Write-Host "   • test-inscription.html - Test complet avec interface" -ForegroundColor Cyan
Write-Host "   • http://localhost:4200/register - Application Angular" -ForegroundColor Cyan
Write-Host ""
Write-Host "🎯 Testez l'inscription avec vos données réelles !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
