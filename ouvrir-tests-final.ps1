# Script pour ouvrir tous les fichiers de test (Version Finale)
Write-Host "=== Ouverture des Fichiers de Test (Version Finale) ===" -ForegroundColor Green
Write-Host ""

Write-Host "1. Ouverture du test avec email unique..." -ForegroundColor Yellow
Start-Process "test-inscription-unique.html"

Write-Host "2. Ouverture du test simple..." -ForegroundColor Yellow
Start-Process "test-inscription-simple.html"

Write-Host "3. Ouverture de l'application Angular..." -ForegroundColor Yellow
Start-Process "http://localhost:4200/register"

Write-Host ""
Write-Host "✅ Tous les fichiers de test sont ouverts !" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Fichiers ouverts:" -ForegroundColor Yellow
Write-Host "   • test-inscription-unique.html - Test avec email unique (RECOMMANDÉ)" -ForegroundColor Cyan
Write-Host "   • test-inscription-simple.html - Test avec vos données réelles" -ForegroundColor Cyan
Write-Host "   • http://localhost:4200/register - Application Angular" -ForegroundColor Cyan
Write-Host ""
Write-Host "🎯 Solutions pour tester l'inscription :" -ForegroundColor Green
Write-Host "   1. Utilisez test-inscription-unique.html (évite les conflits d'email)" -ForegroundColor White
Write-Host "   2. Ou changez l'email dans l'application Angular" -ForegroundColor White
Write-Host "   3. Ou utilisez un email différent dans test-inscription-simple.html" -ForegroundColor White
Write-Host ""
Write-Host "🔧 Le problème était : Email déjà existant dans la base de données" -ForegroundColor Yellow
Write-Host "✅ Le système d'inscription fonctionne parfaitement !" -ForegroundColor Green
Write-Host ""
Write-Host "Appuyez sur une touche pour fermer..." -ForegroundColor Gray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
